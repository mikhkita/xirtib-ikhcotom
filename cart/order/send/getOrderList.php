<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	
	use Bitrix\Main;
	use Bitrix\Sale;
	CModule::IncludeModule("sale");

	$orders = array();

	// =================

	$arFavourites = getFavourites();
	$orders["isAuth"] = isAuth();

	$basket = \Bitrix\Sale\Basket::loadItemsForFUser(
	   \Bitrix\Sale\Fuser::getId(),
	   \Bitrix\Main\Context::getCurrent()->getSite()
	);

	$order = Bitrix\Sale\Order::create(
		\Bitrix\Main\Context::getCurrent()->getSite(),
		\Bitrix\Sale\Fuser::getId());
	$order->setPersonTypeId(1);
	$order->setBasket($basket);

	//$orders["orderID"] = $order->getId();

	$arBasket = array();
	$basketItems = $basket->getBasketItems(); // массив объектов Sale\BasketItem
	foreach ($basketItems as $basketItem) {
		$arBasketItem = array();

		$arBasketItem["id"] = $basketItem->getProductId();//торговое предложение
		$productID = CCatalogSku::GetProductInfo($arBasketItem["id"]);//получить id товара по id торгового предложения
		$arBasketItem["productID"] = $productID["ID"];//товар

		$objElement = \Bitrix\Iblock\ElementTable::getByPrimary($arBasketItem["id"])->fetchObject();
		$arBasketItem["image"] = CFile::GetPath($objElement->getDetailPicture());
		$arBasketItem["name"] = $basketItem->getField('NAME');
		$arBasketItem["url"] = $basketItem->getField('DETAIL_PAGE_URL');
		$arBasketItem["quantity"] = $basketItem->getQuantity();
		$arBasketItem["basePriceForOne"] = $basketItem->getBasePrice();
		$arBasketItem["totalPriceForOne"] = $basketItem->getPrice();
		$product = \Bitrix\Catalog\ProductTable::getByPrimary($arBasketItem["id"])->fetchObject();
		$arBasketItem["maxCount"] = $product->getQuantity();
		$arBasketItem["favorite"] = in_array($arBasketItem["productID"], $arFavourites);
		$arBasketItem["visible"] = true;
	    $arBasket[] = $arBasketItem;
	}
	$orders["items"] = $arBasket;

	$discounts = $order->getDiscount();
	$arDiscounts = $discounts->getApplyResult();

	// =================

	$arCoupons = Bitrix\Sale\DiscountCouponsManager::get(true, array(), true, true);
	$orders["coupons"] = array();
	$i = 0;
	foreach ($arCoupons as $value) {
		if ($value['STATUS'] == Bitrix\Sale\DiscountCouponsManager::STATUS_NOT_FOUND 
			|| $value['STATUS'] == Bitrix\Sale\DiscountCouponsManager::STATUS_FREEZE
			|| $value['STATUS'] == Bitrix\Sale\DiscountCouponsManager::STATUS_NOT_APPLYED 
			|| $value['STATUS'] == Bitrix\Sale\DiscountCouponsManager::STATUS_ENTERED){
			$status = false;
		}
		else{
			$status = true;
		}
		$orders["coupons"][] = array(
			"id" => $i,
			"name" => $value["COUPON"],
			"success" => $status,
			"visible" => true
		);
		$i++;
	}

	// $orders["coupons"][] = array(
	// 	"id" => 723,
	// 	"name" => "zima10",
	// 	"success" => true,
	// );

	// =================

	$orders["delivery"][] = array(
		"id" => 512,
		"name" => "Почта России",
		"value"=>"post",
        "cost"=> 350,
        "text"=> "1. Без объявленной ценности. Если хотите ценную посылку, пишите в примечании к заказу, какую ценность указать, и мы пересчитаем доставку."
	);
	$orders["delivery"][] = array(
		"id" => 513,
		"name"=> "СДЭК",
		"value"=> "SDEC",
        "cost"=> 550,
        "text"=> "2. Без объявленной ценности. Если хотите ценную посылку, пишите в примечании к заказу, какую ценность указать, и мы пересчитаем доставку."
	);
	$orders["delivery"][] = array(
		"id" => 514,
		"name"=> "Курьер по Томску",
		"value"=> "courier",
        "cost"=> 700,
        "text"=> "3. Без объявленной ценности. Если хотите ценную посылку, пишите в примечании к заказу, какую ценность указать, и мы пересчитаем доставку."
	);
	$orders["delivery"][] = array(
		"id" => 515,
		"name"=> "Самовывоз из офиса",
		"value"=>"pickup",
        "cost"=> 0,
        "text"=>"4. Без объявленной ценности. Если хотите ценную посылку, пишите в примечании к заказу, какую ценность указать, и мы пересчитаем доставку."
	);

	$orders["payments"][] = array(
		"id" => 4234,
		"name"=> "Онлайн-оплата картой",
		"value"=> "online",
	);
	$orders["payments"][] = array(
		"id" => 4235,
		"name"=>"Сбербанк.Онлайн",
		"value"=> "sber",
	);

	echo json_encode($orders);

?>