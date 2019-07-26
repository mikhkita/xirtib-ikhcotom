<?

function vardump($str){
	echo "<pre style='text-align:left;'>";
	var_dump($str);
	echo "</pre>";
}

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
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
	if( $USER->IsAuthorized() && CModule::IncludeModule("catalog") ){
		$idUser = $USER->GetID();
		$rsUser = CUser::GetByID($idUser);
		$arUser = $rsUser->Fetch();
		$arElements = unserialize($arUser['UF_FAVOURITE']);

		foreach ($arElements as $id => $state) {
			if( $state == "Y" ){
				$res = CCatalogProduct::GetByID($id);
				if($res){
					array_push($ids, $id);
				}
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
	global $USER;

	$orders = array();

	$arFavourites = getFavourites();
	$orders["isAuth"] = false;
	$orders["user"] = array();
	if(isAuth()){
		$orders["isAuth"] = true;
		//получить ФИО, телефон, email
		$idUser = $USER->GetID();
		$rsUser = CUser::GetByID($idUser);
		$arUser = $rsUser->Fetch();
		$orders["user"]["name"] = $arUser['NAME'];
		$orders["user"]["phone"] = $arUser['PERSONAL_PHONE'];
		$orders["user"]["email"] = $arUser['EMAIL'];
	}

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
		if(is_array($productID)){
			$arBasketItem["productID"] = $productID["ID"];
		}else{
			$arBasketItem["productID"] = $arBasketItem["id"];
		}

		$objElement = \Bitrix\Iblock\ElementTable::getByPrimary($arBasketItem["id"])->fetchObject();
		$img = CFile::ResizeImageGet($objElement->getDetailPicture(), array('width'=>73*2, 'height'=>73*2), BX_RESIZE_IMAGE_PROPORTIONAL, true, false, false, 70);
		$arBasketItem["image"] = $img["src"];
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

	$deliveryList = \Bitrix\Sale\Delivery\Services\Manager::getActiveList();
	$orders["delivery"] = array();
	foreach ($deliveryList as $item) {
		if($item["ID"] != 1 && $item["ACTIVE"] == "Y" && !in_array($item["CLASS_NAME"], array("\Bitrix\Sale\Delivery\Services\Group", "\Sale\Handlers\Delivery\AdditionalHandler", "\Bitrix\Sale\Delivery\Services\Automatic"))){
			$orders["delivery"][] = array(
				"id" => $item["ID"],
				"name" => $item["NAME"],
				"fixedCost" => ($item["CLASS_NAME"] == "\Bitrix\Sale\Delivery\Services\Configurable"),
		        "cost"=> (int)$item["CONFIG"]["MAIN"]["PRICE"],
		        "text"=> $item["DESCRIPTION"],
			);
		}
	}

	$paySystemResult = \Bitrix\Sale\PaySystem\Manager::getList(array(
	    'filter'  => array(
	        'ACTIVE' => 'Y',
	    )
	));
	while ($paySystem = $paySystemResult->fetch()){
		$orders["payments"][] = array(
			"id" => $paySystem["ID"],
			"name"=> $paySystem["NAME"],
		);                   
	}

	return json_encode($orders);
}

function getLocationByZIP($zip){
	$res = \Bitrix\Sale\Location\ExternalTable::getList(array(
        'filter' => array(
            // '=SERVICE.CODE' => self::ZIP_EXT_SERVICE_CODE,
            '=XML_ID' => $zip
        ),
        'select' => array(
            'LOCATION_ID',
        ),
        'limit' => 1
    ));

    if($item = $res->fetch()){
    	$res = \Bitrix\Sale\Location\LocationTable::getByPrimary($item["LOCATION_ID"]);
    	if($item = $res->fetch()) {
		    return $item["CODE"];
		}
    }
    return false;
}

?>