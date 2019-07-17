<?

function vardump($str){
	echo "<pre style='text-align:left;'>";
	var_dump($str);
	echo "</pre>";
}

function getBasketCount(){
	CModule::IncludeModule("sale");

	$basket = Bitrix\Sale\Basket::loadItemsForFUser(Bitrix\Sale\Fuser::getId(), "s1");
	$basketItems = $basket->getBasketItems();

	$GLOBALS["BASKET_ITEMS"] = array();

	foreach ($basketItems as $key => $item) {
		$arItem = array(
			"BASKET_ID" => $item->getId(),
			"PRODUCT_ID" => $item->getProductId(),
			"QUANTITY" => $item->getQuantity(),
		);

		$GLOBALS["BASKET_ITEMS"][ $arItem["PRODUCT_ID"] ] = $arItem;
	}

	$order = Bitrix\Sale\Order::create("s1", \Bitrix\Sale\Fuser::getId());
	$order->setPersonTypeId(1);
	$order->setBasket($basket);

	$discounts = $order->getDiscount();
	$res = $discounts->getApplyResult();

	return array(
		"count" => array_sum($basket->getQuantityList()),
		"sum" => number_format( $order->getPrice(), 0, ',', ' ' )
	);
}

function convertPhoneNumber($str){
	if (strlen($str) == 11) {
	$str = '+7 ('.substr($str, 1, 3).') '.substr($str, 4, 3).'-'.substr($str, 7, 2).'-'.substr($str, 9, 2);
	} 
	return $str;
}

function isAuth(){
	global $USER;
	return $USER->IsAuthorized();
}

function getFavourites(){
	global $USER;

	$ids = array();
	if( $USER->IsAuthorized() ){
		$idUser = $USER->GetID();
		$rsUser = CUser::GetByID($idUser);
		$arUser = $rsUser->Fetch();
		$arElements = unserialize($arUser['UF_FAVOURITE']);

		foreach ($arElements as $id => $state) {
			if( $state == "Y" ){
				array_push($ids, $id);
			}
		}
	}
	if( count($ids) ){
		return $ids;
	}else{
		return 0;
	}
}

function getAllDiscounts()
{   
   	Bitrix\Main\Loader::includeModule('sale');
    require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sale/handlers/discountpreset/simpleproduct.php");

    $arDiscounts = array();
    $arProductDiscountsObject = \Bitrix\Sale\Internals\DiscountTable::getList(array(
        'filter' => array(
            // 'ID' => 1231,
        ),
        'select' => array(
            "*"
       	)
    ));

    while( $arProductDiscounts = $arProductDiscountsObject->fetch() ){
    	$discountObj = new Sale\Handlers\DiscountPreset\SimpleProduct();
    	$discount = $discountObj->generateState($arProductDiscounts);

    	array_push($arDiscounts, array(
    		"PRODUCTS" => $discount['discount_product'],
		    "TYPE" => $discount['discount_type'],
		    "SECTIONS" => $discount['discount_section'],
		    "VALUE" => $discount['discount_value'],
    	));
    }

    return $arDiscounts;
}

function getDiscountProducts(){
	$arDiscounts = getAllDiscounts();

	$out = array(
		"PRODUCTS" => array(),
		"SECTIONS" => array()
	);
	$sections = array();
	foreach ($arDiscounts as $key => $arDiscount) {
		if( isset( $arDiscount["PRODUCTS"] ) && count($arDiscount["PRODUCTS"]) ){
			$out["PRODUCTS"] = array_merge($out["PRODUCTS"], $arDiscount["PRODUCTS"]);
		}
		if( isset( $arDiscount["SECTIONS"] ) && count($arDiscount["SECTIONS"]) ){
			$out["SECTIONS"] = array_merge($out["SECTIONS"], $arDiscount["SECTIONS"]);
		}
	}

	return $out;
}

function getColors(){
	$hldata = Bitrix\Highloadblock\HighloadBlockTable::getById(1)->fetch();
	$hlentity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);

	$hlDataClass = $hldata['NAME'].'Table';

	$result = $hlDataClass::getList(array(
		'select' => array('UF_NAME', 'UF_XML_ID', 'UF_FILE'),
	    'order' => array('UF_NAME' =>'ASC'),
	));

	$arColors = array();

	while($res = $result->fetch()){
	    $arColors[] = $res;
	}

	return $arColors;
}

function getOrderList(){
	CModule::IncludeModule("sale");

	$orders = array();

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

	// $discounts = $order->getDiscount();
	// $arDiscounts = $discounts->getApplyResult();

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

	return json_encode($orders);
}

?>