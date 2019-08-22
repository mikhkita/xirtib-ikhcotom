<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$GLOBALS['APPLICATION']->RestartBuffer();
header('Location: /catalog/'.$urlArr[2].'/');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>