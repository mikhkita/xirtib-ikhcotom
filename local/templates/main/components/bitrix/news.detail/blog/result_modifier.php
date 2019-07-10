<? 
	$arSelect = Array();
	$arFilter = Array("IBLOCK_ID"=>15, "ACTIVE"=>"Y");
	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
	while($ob = $res->GetNextElement())
	{
		$arFields[] = $ob->GetProperties();
	}

	$arResult['COMMENT_COUNT'] = 0;
	foreach ($arFields as $key => $value) {
		if ($arResult['ID'] == $value["COMMENT_ARTICLE"]['VALUE']) {
			$arResult['COMMENT_COUNT'] += 1; 
		}
	}
?>
