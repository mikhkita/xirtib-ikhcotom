<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	
	use Bitrix\Main;
	use Bitrix\Sale;
	CModule::IncludeModule("sale");

	$orders = array();

	$orders["coupons"][] = array(
		"id" => 723,
		"name" => "zima10",
		"success" => true,
	);
	$orders["coupons"][] = array(
		"id" => 724,
		"name" => "wefe",
		"success" => false,
	);
	$orders["coupons"][] = array(
		"id" => 725,
		"name" => "YTCCTRCT",
		"success" => false,
	);

	// =================

	$arFavourites = getFavourites();

	$basket = \Bitrix\Sale\Basket::loadItemsForFUser(
	   \Bitrix\Sale\Fuser::getId(),
	   \Bitrix\Main\Context::getCurrent()->getSite()
	);

	$order = Bitrix\Sale\Order::create(
		\Bitrix\Main\Context::getCurrent()->getSite(),
		\Bitrix\Sale\Fuser::getId());
	$order->setPersonTypeId(1);
	$order->setBasket($basket);

	$arBasket = array();
	$basketItems = $basket->getBasketItems(); // массив объектов Sale\BasketItem
	foreach ($basketItems as $basketItem) {
		$arBasketItem = array();

		$arBasketItem["id"] = $basketItem->getProductId();//торговое предложение
		$productID = CCatalogSku::GetProductInfo($arBasketItem["id"]);//получить id товара по id торгового предложения
		$arBasketItem["productID"] = $productID["ID"];//товар

		$objElement = \Bitrix\Iblock\ElementTable::getByPrimary($arBasketItem["id"])->fetchObject();
		$arBasketItem["image"] = CFile::GetPath($objElement->getPreviewPicture());
		$arBasketItem["name"] = $basketItem->getField('NAME');
		$arBasketItem["url"] = $basketItem->getField('DETAIL_PAGE_URL');
		$arBasketItem["quantity"] = $basketItem->getQuantity();
		$arBasketItem["basePriceForOne"] = $basketItem->getBasePrice();
		$arBasketItem["totalPriceForOne"] = $basketItem->getPrice();
		$product = \Bitrix\Catalog\ProductTable::getByPrimary($arBasketItem["id"])->fetchObject();
		$arBasketItem["maxCount"] = $product->getQuantity();
		$arBasketItem["favorite"] = in_array($arBasketItem["productID"], $arFavourites);
	    $arBasket[] = $arBasketItem;
	}
	$orders["items"] = $arBasket;

	$discounts = $order->getDiscount();
	$res = $discounts->getApplyResult();

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