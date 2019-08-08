<?

foreach ($arResult['ITEMS'] as $k => $arItem) {

	if ($arResult['ITEMS'][$k]["OFFERS"]) {
		foreach ($arResult['ITEMS'][$k]["OFFERS"] as $i => $offer) {
			$quantity = intval($offer["PRODUCT"]["QUANTITY"]);
			if( $quantity <= 0 ){
				unset($arResult['ITEMS'][$k]["OFFERS"][$i]);
			}
		}
		if (empty($arResult['ITEMS'][$k]["OFFERS"])) {
			unset($arResult['ITEMS'][$k]);
		}
	} else {
		$quantity = intval($arResult['ITEMS'][$k]["PRODUCT"]['QUANTITY']);
		if( $quantity <= 0 ){
			unset($arResult['ITEMS'][$k]);
		}
	}
	if (!empty($arResult['ITEMS'][$k]["OFFERS"])) {
		$arResult['ITEMS'][$k]["OFFERS"] = array_values($arResult['ITEMS'][$k]["OFFERS"]);
	}
	
}

$arResult['ITEMS'] = array_values($arResult['ITEMS']);

?>