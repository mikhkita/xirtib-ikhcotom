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


?>