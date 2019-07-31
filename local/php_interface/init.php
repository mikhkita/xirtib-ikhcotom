<?

// Для импорта ----------------------------------------------------------------------
function getFileArray($url){
	return CFile::MakeFileArray($url);
}
function addNewSection($item){
	CModule::IncludeModule("iblock");
	
	$section = new CIBlockSection;
    $arLoadSectionArray = Array(
        "IBLOCK_ID" => 1,
        "NAME" => $item->name,
        "IBLOCK_SECTION_ID" => 1,
        "CODE" => getUniqueCode($item->slug),
        "DESCRIPTION" => $item->description,
    );
    if($PRODUCT_ID = $section->Add($arLoadSectionArray)){
        return $PRODUCT_ID;
    }else{
        echo "Error: ".$section->LAST_ERROR."<br>";
        return false;
    }
}
function addNewProduct($data, $product = false){
	// $intSKUIBlock = 2; // ID инфоблока предложений (должен быть торговым каталогом)
	// $arCatalog = CCatalog::GetByID($intSKUIBlock);
	// if (!$arCatalog)
	//    return;
	// $intProductIBlock = $arCatalog['PRODUCT_IBLOCK_ID']; // ID инфоблока товаров

	// // var_dump($arCatalog);
	// // die();

	// $intSKUProperty = $arCatalog['SKU_PROPERTY_ID']; // ID свойства в инфоблоке предложений типа "Привязка к товарам (SKU)"

	// $obElement = new CIBlockElement();
	// // // Сохраняем товар
	// // $arFields = array(
	// //    'NAME' => 'Товар',
	// //    'IBLOCK_ID' => $intProductIBlock,
	// //    'ACTIVE' => 'Y'
	// // );

	// // $intProductID = $obElement->Add($arFields); // добавили товар, получили ID
	// $intProductID = 39;
	// if ($intProductID) {
	//    $arProp[$intSKUProperty] = $intProductID;
	//    $arFields = array(
	//       'NAME' => 'Тороговое предложение',
	//       'IBLOCK_ID' => $intSKUIBlock,
	//       'ACTIVE' => 'Y',
	//       'PROPERTY_VALUES' => $arProp
	//    );
	//    $intOfferID = $obElement->Add($arFields); // ID торгового предложения
	//    var_dump($intOfferID);
	//    // дальше сохранять цены, количество на складе и т.п.
	// }


	$el = new CIBlockElement;
	$PROP = array();

	$arLoadProductArray = Array(
		"DETAIL_TEXT_TYPE" 	=> "html",
	);

	$arLoadProductArray = $arLoadProductArray + $data;

	if($PRODUCT_ID = $el->Add($arLoadProductArray)){
		file_put_contents("log.txt", "New ID: ".$PRODUCT_ID."\n", FILE_APPEND);

		if( !empty($product) ){
			$arFields = array(
		       	"ID" => $PRODUCT_ID, 
		        "VAT_INCLUDED" => "Y",
		        "MEASURE" => $product["MEASURE"],
		        "QUANTITY" => $product["QUANTITY"],
		        "WEIGHT" => $product["WEIGHT"]
		    );

		    if(CCatalogProduct::Add($arFields)) {
		        $arFields = Array(
		            "PRODUCT_ID" => $PRODUCT_ID,
		            "CATALOG_GROUP_ID" => 1,
		            "PRICE" => $product["PRICE"],
		            "CURRENCY" => "RUB",
		        );
		        CPrice::Add($arFields);
		    }else{
		        file_put_contents("log.txt", 'Ошибка добавления параметров товара\n', FILE_APPEND);
		    }
		}
		return $PRODUCT_ID;
	}else{
		file_put_contents("log.txt", "Error: ".$el->LAST_ERROR." | ".print_r($arLoadProductArray, true)."\n", FILE_APPEND);
		return false;
	}

}
function getUniqueCode($code, $index = 0){
	CModule::IncludeModule('iblock');

	$findCode = ($index == 0)?$code:($code.$index);
	$rsSections = CIBlockElement::GetList(array(),array('IBLOCK_ID' => 1, '=CODE' => $findCode));
	if ($arSection = $rsSections->Fetch()){
		return getUniqueCode( $code, $index + 1 );
	}else{
		return $findCode;
	}
}
function wpautop( $pee, $br = true ) {
	$pre_tags = array();

	if ( trim($pee) === '' )
		return '';

	// Just to make things a little easier, pad the end.
	$pee = $pee . "\n";

	/*
	 * Pre tags shouldn't be touched by autop.
	 * Replace pre tags with placeholders and bring them back after autop.
	 */
	if ( strpos($pee, '<pre') !== false ) {
		$pee_parts = explode( '</pre>', $pee );
		$last_pee = array_pop($pee_parts);
		$pee = '';
		$i = 0;

		foreach ( $pee_parts as $pee_part ) {
			$start = strpos($pee_part, '<pre');

			// Malformed html?
			if ( $start === false ) {
				$pee .= $pee_part;
				continue;
			}

			$name = "<pre wp-pre-tag-$i></pre>";
			$pre_tags[$name] = substr( $pee_part, $start ) . '</pre>';

			$pee .= substr( $pee_part, 0, $start ) . $name;
			$i++;
		}

		$pee .= $last_pee;
	}
	// Change multiple <br>s into two line breaks, which will turn into paragraphs.
	$pee = preg_replace('|<br\s*/?>\s*<br\s*/?>|', "\n\n", $pee);

	$allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';

	// Add a double line break above block-level opening tags.
	$pee = preg_replace('!(<' . $allblocks . '[\s/>])!', "\n\n$1", $pee);

	// Add a double line break below block-level closing tags.
	$pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);

	// Standardize newline characters to "\n".
	$pee = str_replace(array("\r\n", "\r"), "\n", $pee);

	// Find newlines in all elements and add placeholders.
	$pee = wp_replace_in_html_tags( $pee, array( "\n" => " <!-- wpnl --> " ) );

	// Collapse line breaks before and after <option> elements so they don't get autop'd.
	if ( strpos( $pee, '<option' ) !== false ) {
		$pee = preg_replace( '|\s*<option|', '<option', $pee );
		$pee = preg_replace( '|</option>\s*|', '</option>', $pee );
	}

	/*
	 * Collapse line breaks inside <object> elements, before <param> and <embed> elements
	 * so they don't get autop'd.
	 */
	if ( strpos( $pee, '</object>' ) !== false ) {
		$pee = preg_replace( '|(<object[^>]*>)\s*|', '$1', $pee );
		$pee = preg_replace( '|\s*</object>|', '</object>', $pee );
		$pee = preg_replace( '%\s*(</?(?:param|embed)[^>]*>)\s*%', '$1', $pee );
	}

	/*
	 * Collapse line breaks inside <audio> and <video> elements,
	 * before and after <source> and <track> elements.
	 */
	if ( strpos( $pee, '<source' ) !== false || strpos( $pee, '<track' ) !== false ) {
		$pee = preg_replace( '%([<\[](?:audio|video)[^>\]]*[>\]])\s*%', '$1', $pee );
		$pee = preg_replace( '%\s*([<\[]/(?:audio|video)[>\]])%', '$1', $pee );
		$pee = preg_replace( '%\s*(<(?:source|track)[^>]*>)\s*%', '$1', $pee );
	}

	// Collapse line breaks before and after <figcaption> elements.
	if ( strpos( $pee, '<figcaption' ) !== false ) {
		$pee = preg_replace( '|\s*(<figcaption[^>]*>)|', '$1', $pee );
		$pee = preg_replace( '|</figcaption>\s*|', '</figcaption>', $pee );
	}

	// Remove more than two contiguous line breaks.
	$pee = preg_replace("/\n\n+/", "\n\n", $pee);

	// Split up the contents into an array of strings, separated by double line breaks.
	$pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);

	// Reset $pee prior to rebuilding.
	$pee = '';

	// Rebuild the content as a string, wrapping every bit with a <p>.
	foreach ( $pees as $tinkle ) {
		$pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
	}

	// Under certain strange conditions it could create a P of entirely whitespace.
	$pee = preg_replace('|<p>\s*</p>|', '', $pee);

	// Add a closing <p> inside <div>, <address>, or <form> tag if missing.
	$pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee);

	// If an opening or closing block element tag is wrapped in a <p>, unwrap it.
	$pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);

	// In some cases <li> may get wrapped in <p>, fix them.
	$pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee);

	// If a <blockquote> is wrapped with a <p>, move it inside the <blockquote>.
	$pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
	$pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);

	// If an opening or closing block element tag is preceded by an opening <p> tag, remove it.
	$pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);

	// If an opening or closing block element tag is followed by a closing <p> tag, remove it.
	$pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);

	// Optionally insert line breaks.
	if ( $br ) {
		// Replace newlines that shouldn't be touched with a placeholder.
		$pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', '_autop_newline_preservation_helper', $pee);

		// Normalize <br>
		$pee = str_replace( array( '<br>', '<br/>' ), '<br />', $pee );

		// Replace any new line characters that aren't preceded by a <br /> with a <br />.
		$pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee);

		// Replace newline placeholders with newlines.
		$pee = str_replace('<WPPreserveNewline />', "\n", $pee);
	}

	// If a <br /> tag is after an opening or closing block tag, remove it.
	$pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);

	// If a <br /> tag is before a subset of opening or closing block tags, remove it.
	$pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
	$pee = preg_replace( "|\n</p>$|", '</p>', $pee );

	// Replace placeholder <pre> tags with their original content.
	if ( !empty($pre_tags) )
		$pee = str_replace(array_keys($pre_tags), array_values($pre_tags), $pee);

	// Restore newlines in all elements.
	if ( false !== strpos( $pee, '<!-- wpnl -->' ) ) {
		$pee = str_replace( array( ' <!-- wpnl --> ', '<!-- wpnl -->' ), "\n", $pee );
	}

	return $pee;
}
function wp_html_split( $input ) {
	return preg_split( get_html_split_regex(), $input, -1, PREG_SPLIT_DELIM_CAPTURE );
}
function get_html_split_regex() {
	static $regex;

	if ( ! isset( $regex ) ) {
		$comments =
			  '!'           // Start of comment, after the <.
			. '(?:'         // Unroll the loop: Consume everything until --> is found.
			.     '-(?!->)' // Dash not followed by end of comment.
			.     '[^\-]*+' // Consume non-dashes.
			. ')*+'         // Loop possessively.
			. '(?:-->)?';   // End of comment. If not found, match all input.

		$cdata =
			  '!\[CDATA\['  // Start of comment, after the <.
			. '[^\]]*+'     // Consume non-].
			. '(?:'         // Unroll the loop: Consume everything until ]]> is found.
			.     '](?!]>)' // One ] not followed by end of comment.
			.     '[^\]]*+' // Consume non-].
			. ')*+'         // Loop possessively.
			. '(?:]]>)?';   // End of comment. If not found, match all input.

		$escaped =
			  '(?='           // Is the element escaped?
			.    '!--'
			. '|'
			.    '!\[CDATA\['
			. ')'
			. '(?(?=!-)'      // If yes, which type?
			.     $comments
			. '|'
			.     $cdata
			. ')';

		$regex =
			  '/('              // Capture the entire match.
			.     '<'           // Find start of element.
			.     '(?'          // Conditional expression follows.
			.         $escaped  // Find end of escaped element.
			.     '|'           // ... else ...
			.         '[^>]*>?' // Find end of normal element.
			.     ')'
			. ')/';
	}

	return $regex;
}
function wp_replace_in_html_tags( $haystack, $replace_pairs ) {
	// Find all elements.
	$textarr = wp_html_split( $haystack );
	$changed = false;

	// Optimize when searching for one item.
	if ( 1 === count( $replace_pairs ) ) {
		// Extract $needle and $replace.
		foreach ( $replace_pairs as $needle => $replace );

		// Loop through delimiters (elements) only.
		for ( $i = 1, $c = count( $textarr ); $i < $c; $i += 2 ) {
			if ( false !== strpos( $textarr[$i], $needle ) ) {
				$textarr[$i] = str_replace( $needle, $replace, $textarr[$i] );
				$changed = true;
			}
		}
	} else {
		// Extract all $needles.
		$needles = array_keys( $replace_pairs );

		// Loop through delimiters (elements) only.
		for ( $i = 1, $c = count( $textarr ); $i < $c; $i += 2 ) {
			foreach ( $needles as $needle ) {
				if ( false !== strpos( $textarr[$i], $needle ) ) {
					$textarr[$i] = strtr( $textarr[$i], $replace_pairs );
					$changed = true;
					// After one strtr() break out of the foreach loop and look at next element.
					break;
				}
			}
		}
	}

	if ( $changed ) {
		$haystack = implode( $textarr );
	}

	return $haystack;
}
// Для импорта ----------------------------------------------------------------------

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
		//"sum" => number_format( $order->getPrice(), 0, ',', ' ' )
		"sum" => rtrim(rtrim(number_format($order->getPrice(), 1, '.', ' '),"0"),".")
	);
}

function convertPrice($price){
	return rtrim(rtrim(number_format($price, 1, '.', ' '),"0"),".");
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


	// vardump($order->getPrice());

	// $discount = $order->getDiscount();
	// \Bitrix\Sale\DiscountCouponsManager::clearByOrder($order);
	// $discount->setOrderRefresh(true);
	// $basket = $order->getBasket();
	// $basket->refreshData(array('PRICE', 'COUPONS'));
	// $discount->calculate();

	$arBasket = array();
	$basketItems = $basket->getBasketItems(); // массив объектов Sale\BasketItem
	foreach ($basketItems as $basketItem) {
		$arBasketItem = array();

		$arBasketItem["id"] = $basketItem->getProductId();//торговое предложение
		$productID = CCatalogSku::GetProductInfo($arBasketItem["id"]);//получить id товара по id торгового предложения
		if(is_array($productID)){
			$arBasketItem["productID"] = $productID["ID"];
			$db_res = CCatalogProduct::GetList(
		        array(),
		        array("ID" => $productID["ID"]),
		        false,
		        array("nTopCount" => 10)
		    );
			if ($ar_res = $db_res->Fetch()){
			    $arBasketItem["productName"] = $ar_res["ELEMENT_NAME"];
			}
		}else{
			$arBasketItem["productID"] = $arBasketItem["id"];
		}

		$objElement = \Bitrix\Iblock\ElementTable::getByPrimary($arBasketItem["id"])->fetchObject();
		if(empty($objElement)){
			$basketItem->delete();
			$basket->save();
			continue;
		}
		if($objElement->getDetailPicture()){
			$img = CFile::ResizeImageGet($objElement->getDetailPicture(), array('width'=>73*2, 'height'=>73*2), BX_RESIZE_IMAGE_PROPORTIONAL, true, false, false, 70);
		}else{
			$img = CFile::ResizeImageGet($objElement->getPreviewPicture(), array('width'=>73*2, 'height'=>73*2), BX_RESIZE_IMAGE_PROPORTIONAL, true, false, false, 70);
		}
		$arBasketItem["image"] = $img["src"];
		$arBasketItem["name"] = $basketItem->getField('NAME');
		$arBasketItem["url"] = $basketItem->getField('DETAIL_PAGE_URL');
		$arBasketItem["quantity"] = $basketItem->getQuantity();
		$arBasketItem["basePriceForOne"] = $basketItem->getBasePrice();
		$arBasketItem["totalPriceForOne"] = $basketItem->getPrice();
		$product = \Bitrix\Catalog\ProductTable::getByPrimary($arBasketItem["id"])->fetchObject();
		$arBasketItem["maxCount"] = $product->getQuantity();
		$arBasketItem["limitWarning"] = false;
		if((int)$arBasketItem["quantity"] > (int)$arBasketItem["maxCount"]){
			//$arBasketItem["limitWarning"] = "Товар в количестве ".$arBasketItem["quantity"]." шт. недоступен. В наличии ".$arBasketItem["maxCount"]." шт.";
			$arBasketItem["limitWarning"] = true;
			$basketItem->setField('QUANTITY', $arBasketItem["maxCount"]);
			$basketItem->save();
			$arBasketItem["quantity"] = $arBasketItem["maxCount"];
		}
		$arBasketItem["favorite"] = in_array($arBasketItem["productID"], $arFavourites);
		$arBasketItem["visible"] = true;
	    $arBasket[] = $arBasketItem;
	}
	$orders["items"] = $arBasket;

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