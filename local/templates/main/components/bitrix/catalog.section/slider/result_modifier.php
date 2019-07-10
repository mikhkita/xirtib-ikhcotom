<?

// vardump($arResult["ORIGINAL_PARAMETERS"]["SECTION_CODE"]);

// filterLength();
// filterPriceAndColor();

// function filterLength(){

// 	$arResult["FILTER_LENGTH"] = array();
// 	$arLength = array();

// 	$arSelect = Array();
// 	$arFilter = Array("IBLOCK_ID"=>1, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");

// 	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
// 	while($ob = $res->GetNextElement()){
// 		$arr = array();
// 		$arr = $ob->GetProperties();
// 		$arLength[] = $arr['LENGTH']['VALUE'];
// 	}

// 	$arLength = array_filter($arLength, function($element) {
// 	    return !empty($element);
// 	});

// 	sort($arLength);

// 	$arResult["FILTER_LENGTH"]['MIN'] = $arLength[0];
// 	$arResult["FILTER_LENGTH"]['MAX'] = $arLength[count($arLength)-1];

// }

// function filterPriceAndColor(){

// 	$arResult["FILTER_PRICE"] = array();
// 	$arResult["FILTER_COLOR"] = array();
// 	$arPrice = array();

// 	$arSelect = Array();
// 	$arFilter = Array("IBLOCK_ID"=>2, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "IBLOCK_CODE" => $arResult["ORIGINAL_PARAMETERS"]["SECTION_CODE"]);

// 	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
// 	while($ob = $res->GetNextElement()){
// 		$props = array();
// 		$props = $ob->GetProperties();
// 		$fields = $ob->GetFields();

//         // vardump($fields);

// 		$arColor[] = $props['COLOR']["VALUE_ENUM_ID"];
// 	}

// 	// vardump($arPrice);
// 	// vardump($arColor);
// 	// die();

// 	$arPrice = array_filter($arPrice, function($element) {
// 	    return !empty($element);
// 	});

// 	sort($arPrice);

// 	$arResult["FILTER_PRICE"]['MIN'] = $arPrice[0];
// 	$arResult["FILTER_PRICE"]['MAX'] = $arPrice[count($arPrice)-1];

// }

?>