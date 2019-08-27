<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$GLOBALS['APPLICATION']->RestartBuffer();
echo '<script>window.location = "/catalog/";</script>';

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>