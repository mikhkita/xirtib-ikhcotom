<?

foreach ($arResult['ITEMS'] as $k => $arItem) {
	
	foreach ($arResult['ITEMS'][$k]["OFFERS"] as $i => $offer) {
		$quantity = intval($offer["PRODUCT"]["QUANTITY"]);
		if( $quantity <= 0 ){
			unset($arResult['ITEMS'][$k]["OFFERS"][$i]);
		}
	}

}

?>