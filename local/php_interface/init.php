<?

use Bitrix\Sale;

AddEventHandler("main", "OnEndBufferContent", "replacePlaceholders");
function replacePlaceholders(&$content){
	// echo "string";
	// die();
	// $year = date("Y", strtotime("+2 month"));
	// $content = str_replace("#YEAR#", $year, $content);
}

AddEventHandler("main", "OnAdminListDisplay", "MyOnAdminListDisplay");
function MyOnAdminListDisplay(&$list)
{
    if ($list->table_id=="tbl_sale_order") {
       //  foreach ($list->aRows as $row){ // здесь мы вклиниваемся в контекстное меню каждой строки таблицы
       //      $row->aActions["all_orders"]["ICON"] = "";
       //      $row->aActions["all_orders"]["TEXT"] = "Переотправить заказ на почту";
       //      $row->aActions["all_orders"]["ACTION"] = "javascript:sendTo1C(".$row->id.")";  // здесь мы объявляем действие - js-функция orders_ms(), в которую будем передавать параметр (в данном случае id заказа)  
      	// }  
      	unset($list->arActions["cancel"]);
      	unset($list->arActions["cancel_n"]);
      	unset($list->arActions["allow_delivery"]);
      	unset($list->arActions["allow_delivery_n"]);
      	unset($list->arActions["update_payment_status"]);
      	unset($list->arActions["paid"]);
      	unset($list->arActions["paid_n"]);
      	unset($list->arActions["delivery_requests"]);
      	unset($list->arActions["archive"]);
      	unset($list->arActions["status_N"]);
      	unset($list->arActions["status_F"]);
      	unset($list->arActions["export_commerceml"]);
      	unset($list->arActions["export_commerceml2"]);
    }
} 

// #CLIENT_INFO#

// #ITEMS_INFO#
// Способ оплаты: #PAYMENT_INFO#
// Вид доставки: #DELIVERY_NAME#
// Стоимость доставки: #DELIVERY_PRICE# руб.

// #COMMENT#

// Ссылка для редактирования

AddEventHandler("main", "OnBeforeEventAdd", Array("MyEventHandlers", "OnBeforeEventAddHandler"));

class MyEventHandlers 
{ 
    function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
    {
		if($event==="SALE_NEW_ORDER"){

			$order = Bitrix\Sale\Order::load($arFields["ORDER_ID"]);

			$propCollection = $order->getPropertyCollection();
			$temp = $propCollection->getArray();
			$arFields['CLIENT_INFO'] = '';
			$telegramClientInfo = '';
			foreach ($temp["properties"] as $prop) {
				if (isset($prop['VALUE'][0])) {
					$arFields['CLIENT_INFO'] .= "<b>".$prop['NAME'].":</b> ".$prop['VALUE'][0]."<br>";
					$telegramClientInfo .= "<b>".$prop['NAME'].":</b> ".$prop['VALUE'][0]."\n";
				}
			}

			$paymentCollection = $order->getPaymentCollection();

			foreach ($paymentCollection as $payment) {
			    $isPaid = $payment->isPaid() == true ? 'заказ оплачен' : 'заказ не оплачен';
			    $psName = $payment->getPaymentSystemName();
			}

			$arFields['PAYMENT_INFO'] = $psName.', '.$isPaid;

			$descr = $order->getField('USER_DESCRIPTION');
			if (isset($descr)) {
				$arFields['COMMENT'] = '<b>Комментарий к заказу:</b> '.$descr;
			}

			$deliveryID = $order->getField("DELIVERY_ID");
			$arDelivery = Bitrix\Sale\Delivery\Services\Manager::getById($deliveryID);

			$arBasketFilter = array("LID" => 's1',"ORDER_ID" => $arFields["ORDER_ID"]);
			$arBasketSelect = array();
			$dbBasketItems = CSaleBasket::GetList(array("NAME" => "ASC","ID" => "ASC"), $arBasketFilter, false, false, $arBasketSelect);

			$arBasketItems = '<style>td{padding:2px 8px}td a{text-decoration:underline}</style>';
			$arBasketItems.= '<table>'.
								"<tr>".
								 	"<td style='padding-left:0px;'>Наименование товара</td>".
								 	"<td>Количество</td>".
								 	"<td>Цена</td>".
								 	"<td>Цена&nbsp;со&nbsp;скидкой</td>".
								 	"<td style='padding-right:0px;'>Сумма</td>".
								 "</tr>";
			$arBasketItemsTelegram = "\n<b>Список товаров:</b>\n";
			$totalSum = 0;

			while ($item = $dbBasketItems->Fetch()){

				$mxResult = CCatalogSku::GetProductInfo($item['PRODUCT_ID']);
				$el = CIBlockElement::GetByID($mxResult['ID']);
				$arElement = $el->fetch();
				$name = is_array($mxResult) ? $arElement['NAME']." (".$item['NAME'].")" : $item['NAME'];
				$discountPrice = ($item['DISCOUNT_PRICE'] == 0) ? $item['BASE_PRICE'] : $item['BASE_PRICE'] - $item['DISCOUNT_PRICE'];
				$sum = $item['QUANTITY'] * $discountPrice;
				$totalSum += $sum;

			    $arBasketItems.="<tr>".
					"<td style='padding-left:0px;'><a style='color: #77be32;' href='http://motochki-klubochki.ru".$item['DETAIL_PAGE_URL']."#".$item['PRODUCT_ID']."'>".$name."</a></td>".
					"<td>".round($item['QUANTITY'])."</td>".
					"<td>".convertPrice($item['BASE_PRICE'])."</td>".
					"<td>".convertPrice($discountPrice)."</td>".
					"<td style='padding-right:0px;'>".convertPrice($sum)."</td>".
				"</tr>";
				$arBasketItemsTelegram .= 
					"<a style='color: #77be32;' href='http://motochki-klubochki.ru".$item['DETAIL_PAGE_URL']."#".$item['PRODUCT_ID']."'>".$name."</a> ".round($item['QUANTITY'])." шт по ".convertPrice($discountPrice)." руб. = ".convertPrice($sum)." руб.\n";
					// "Количество: ".round($item['QUANTITY'])."\n".
					// "Цена: ".convertPrice($item['BASE_PRICE'])."\n".
					// "Цена со скидкой: ".$discountPrice."\n".
					// "Сумма: ".$sum."\n".
					// "______\n";
			}

			$arBasketItems.= "<tr>".
							 	"<td></td>".
							 	"<td></td>".
							 	"<td></td>".
							 	"<td style='text-align:right;'><b>Итого:</b></td>".
							 	"<td style='padding-right:0px;'>".convertPrice($totalSum)."</td>".
							 "</tr>".
						"</table>";

			$arFields['ITEMS_INFO'] = $arBasketItems;
			$arFields['DELIVERY_NAME'] = $arDelivery['NAME'];

			//Собираем сообщение в телеграм
			$msgTelegram = "Заказ с сайта № ".$arFields["ORDER_ID"]."\n";
			$msgTelegram .= $telegramClientInfo;
			$msgTelegram .= $arBasketItemsTelegram;
			$msgTelegram .= "<b>Сумма товаров:</b> ".convertPrice($totalSum)." руб.\n";
			$msgTelegram .= "\n<b>Способ оплаты:</b> ".$arFields['PAYMENT_INFO']."\n";
			$msgTelegram .= "<b>Вид доставки:</b> ".$arFields['DELIVERY_NAME']."\n";
			$msgTelegram .= "<b>Стоимость доставки:</b> ".$arFields['DELIVERY_PRICE']." руб.\n";
			$msgTelegram .= "<b>Итого к оплате:</b> ".convertPrice($totalSum + $arFields['DELIVERY_PRICE'])." руб.\n";
			$msgTelegram .= $arFields['COMMENT'];

			if( $_SERVER["HTTP_HOST"] == "motochki-klubochki.ru" ){
				sendMessage($msgTelegram);
			}

		}
    } 
}

AddEventHandler("sale", "OnSalePayOrder", "sendResult");

function sendResult($id, $val){
    $order = Sale\Order::load($id);

    if( $payment = CSalePaySystem::GetByID($order->getField("PAY_SYSTEM_ID")) ){
        if( $payment["CAN_PRINT_CHECK"] == "Y" && $val == "Y" ){
            sendMessage("Заказ №".$order->getId()." оплачен.\nТип оплаты: ".$payment["NAME"]);
        }
    }
}

// Для SMTP ----------------------------------------------------------------------

if (!function_exists('custom_mail') && COption::GetOptionString("webprostor.smtp", "USE_MODULE") == "Y")
{
   function custom_mail($to, $subject, $message, $additional_headers='', $additional_parameters='')
   {
      if(CModule::IncludeModule("webprostor.smtp"))
      {
         $smtp = new CWebprostorSmtp("s1");
         $result = $smtp->SendMail($to, $subject, $message, $additional_headers, $additional_parameters);

         if($result)
            return true;
         else
            return false;
      }
   }
}

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
		"count" => count($basketItems),
		//"sum" => number_format( $order->getPrice(), 0, ',', ' ' )
		"sum" => convertPrice($order->getPrice())
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

function getUserFields(){
	global $USER;
	$arUser = array();
	if( $USER->IsAuthorized() ){
		$idUser = $USER->GetID();
		$rsUser = CUser::GetByID($idUser);
		$arUser = $rsUser->Fetch();
	}
	return $arUser;
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
		'order' => array(
			'SORT' => 'ASC',
		),
	    'filter' => array(
	        'ACTIVE' => 'Y',
	    )
	));
	while ($paySystem = $paySystemResult->fetch()){
		$deliveryIDs = explode(",", $paySystem["CODE"]);
		foreach ($deliveryIDs as $key => $value) {
			$deliveryIDs[$key] = (int)$value;
		}
		$orders["payments"][] = array(
			"id" => $paySystem["ID"],
			"name" => $paySystem["NAME"],
			"deliveryIDs" => $deliveryIDs
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
			$db_res = CIBlockElement::GetList(
		        array(),
		        array("ID" => $productID["ID"]),
		        false,
		        array("nTopCount" => 10)
		    );
			if ($ar_res = $db_res->Fetch()){
				$arBasketItem["productImage"] = $ar_res["DETAIL_PICTURE"];
			    $arBasketItem["productName"] = $ar_res["NAME"];
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
			$img = CFile::ResizeImageGet($objElement->getDetailPicture(), array('width'=>73*2, 'height'=>73*2), BX_RESIZE_IMAGE_PROPORTIONAL, true, false, false, 50);
		}else if($objElement->getPreviewPicture()){
			$img = CFile::ResizeImageGet($objElement->getPreviewPicture(), array('width'=>73*2, 'height'=>73*2), BX_RESIZE_IMAGE_PROPORTIONAL, true, false, false, 50);
		}else if($arBasketItem["productImage"]){
			$img = CFile::ResizeImageGet($arBasketItem["productImage"], array('width'=>73*2, 'height'=>73*2), BX_RESIZE_IMAGE_PROPORTIONAL, true, false, false, 50);
		}else{
			$img["src"] = SITE_TEMPLATE_PATH.'/i/hank.svg';
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

function getItemLabel($arItem){
	$days = 14; // Если товар выложен меньше, чем столько дней назад, то это новинка
	$isNew = ($arItem['PROPERTIES']['NEW']['VALUE'] == 'Y');

	if( $isNew ){
		return "Новинка";
	}else{
		return false;
	}
}

function getElementImages($arResult, $isList = false){
	
	$arImg = array(
		'DETAIL_PHOTO' => array(),
		'COLOR_PHOTO' => array(),
	);

	$colorImg = false;

	if ($isList && $_REQUEST['arrFilter_4']) {
		foreach ($arResult["OFFERS"] as $key => $offer) {
			if ($_REQUEST['COLOR'] == $offer['PROPERTIES']['COLOR']['VALUE']) {
				
				$colorImg = true;
				if ($offer["DETAIL_PICTURE"]){
					$arDetailPhoto = resizePhotos($offer["DETAIL_PICTURE"], $isList);
				} else {
					if ($offer["PREVIEW_PICTURE"]) {
						$arDetailPhoto = resizePhotos($offer["PREVIEW_PICTURE"], $isList);
					} else {
						$arDetailPhoto['ORIGINAL'] = $arDetailPhoto['BIG'] = $arDetailPhoto['SMALL'] = SITE_TEMPLATE_PATH.'/i/hank.svg';
					}
				}

				array_push($arImg['DETAIL_PHOTO'], $arDetailPhoto);
			}
		}		
	}

	if (!$colorImg){
		if ($arResult["OFFERS"]){
			$flag = false;
			foreach ($arResult["OFFERS"] as $key => $offer) {
				
				if ($offer["DETAIL_PICTURE"]){
					$arDetailPhoto = resizePhotos($offer["DETAIL_PICTURE"], $isList);
					$flag = true;
				} else {
					if ($offer["PREVIEW_PICTURE"]) {
						$arDetailPhoto = resizePhotos($offer["PREVIEW_PICTURE"], $isList);
						$flag = true;
					} else {
						$arDetailPhoto['ORIGINAL'] = $arDetailPhoto['BIG'] = $arDetailPhoto['SMALL'] = SITE_TEMPLATE_PATH.'/i/hank.svg';
					}
				}

				if ($offer["PREVIEW_PICTURE"]) {
					$arColorPhoto = resizePhotos($offer["PREVIEW_PICTURE"], false, true);
					$colorFlag = true;
				} else {
					$arColorPhoto['ORIGINAL'] = $arColorPhoto['BIG'] = $arColorPhoto['SMALL'] = SITE_TEMPLATE_PATH.'/i/hank.svg';
				}	

				array_push($arImg['DETAIL_PHOTO'], $arDetailPhoto);
				array_push($arImg['COLOR_PHOTO'], $arColorPhoto);
			}

			if (!$flag && $arResult["DETAIL_PICTURE"]) {
				$arPhoto = resizePhotos($arResult["DETAIL_PICTURE"], $isList);
				foreach ($arImg['DETAIL_PHOTO'] as $key => $value) {
					$arImg['DETAIL_PHOTO'][$key] = $arPhoto;
				}
			}

			if (!$colorFlag) {
				unset($arImg['COLOR_PHOTO']);
			}
		} else {
			if ($arResult["DETAIL_PICTURE"]){
				$arPhoto = resizePhotos($arResult["DETAIL_PICTURE"], $isList);
			} else {
				$arPhoto['ORIGINAL'] = $arPhoto['BIG'] = $arPhoto['SMALL'] = SITE_TEMPLATE_PATH.'/i/hank.svg';
			}
			array_push($arImg['DETAIL_PHOTO'], $arPhoto);
		}
	}

	return $arImg;
}

function resizePhotos($photo, $isList = false, $isColor = false){
	$tmpBig = CFile::ResizeImageGet($photo, Array("width" => 692, "height" => 692), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, 50);
	$tmpOriginal = CFile::ResizeImageGet($photo, Array("width" => 2048, "height" => 2048), BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, 50);
	$smallSize = $isList ? Array("width" => 362, "height" => 362) : Array("width" => 146, "height" => 146);
	$resizeType = $isColor ? BX_RESIZE_IMAGE_EXACT : BX_RESIZE_IMAGE_PROPORTIONAL;
	$tmpSmall = CFile::ResizeImageGet($photo, $smallSize, $resizeType, false, false, false, 50);
	$arPhoto['ORIGINAL'] = $tmpOriginal['src'];
	$arPhoto['BIG'] = $tmpBig['src'];
	$arPhoto['SMALL'] = $tmpSmall['src'];

	// vardump($photo['ALT']);

	return $arPhoto;
}

function getOffers($id){
	$offers = CCatalogSKU::getOffersList($id, 1, array(), array("DETAIL_PICTURE", "PREVIEW_PICTURE"),array());
	return $offers[$id];
}

function isSectionActive($sectionID){
	foreach ($GLOBALS["SECTIONS"] as $key => $arSection) {
		if( $arSection["ID"] == $sectionID ){
			return true;
		}
	}

	return false;
}
function includeArea($file){
	global $APPLICATION;
	$APPLICATION->IncludeComponent("bitrix:main.include","",Array(
	        "AREA_FILE_SHOW" => "file", 
	        "PATH" => "/include/".$file.".php"
	    )
	);	
}

function sendMessage($messaggio) {
	$chatID = "-1001400160433";

    $token = "bot861797122:AAFU5Wfj2F1WdgfSuQSdVnDaaHr1USugXH0";
    $url = "https://api.telegram.org/" . $token . "/sendMessage?chat_id=" . $chatID;
    $url = $url . "&parse_mode=HTML&text=" . urlencode($messaggio);
    $ch = curl_init();
    $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($ch, $optArray);
    $result = curl_exec($ch);
    // var_dump($result);
    // die();
    curl_close($ch);
}











?>