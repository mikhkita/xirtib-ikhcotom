<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$GLOBALS['APPLICATION']->RestartBuffer();

$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", "DETAIL_PAGE_URL", "PREVIEW_TEXT", "DETAIL_TEXT", "PREVIEW_PICTURE");
$arFilter = Array("IBLOCK_ID"=>1, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "CODE" => $urlArr[2]);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);

if($ob = $res->GetNextElement()){
	$arFields = $ob->GetFields();
}

header('Location: '.$arFields["DETAIL_PAGE_URL"]);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>