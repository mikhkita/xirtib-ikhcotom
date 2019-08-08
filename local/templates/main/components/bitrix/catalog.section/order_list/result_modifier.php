<?

$temp = null; //первая фотка

foreach ($arResult['ITEMS'] as $k => $arItem) {

	if ($arResult['ITEMS'][$k]["OFFERS"]) {
		foreach ($arResult['ITEMS'][$k]["OFFERS"] as $i => $offer) {
			if ($temp == null && $offer['DETAIL_PICTURE']) {
				$temp = $offer['DETAIL_PICTURE'];
			}
			$quantity = intval($offer["PRODUCT"]["QUANTITY"]);
			if( $quantity <= 0 ){
				unset($arResult['ITEMS'][$k]["OFFERS"][$i]);
			}
		}

		if (empty($arResult['ITEMS'][$k]["OFFERS"]) && ($temp != null)) {
			$arResult['ITEMS'][$k]['DETAIL_PICTURE'] = $temp;
		}

	}

	if (!empty($arResult['ITEMS'][$k]["OFFERS"])) {
		$arResult['ITEMS'][$k]["OFFERS"] = array_values($arResult['ITEMS'][$k]["OFFERS"]);
	}
	
}

$arResult['ITEMS'] = array_values($arResult['ITEMS']);

?>