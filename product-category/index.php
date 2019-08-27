<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$GLOBALS['APPLICATION']->RestartBuffer();

header("HTTP/1.1 301 Moved Permanently");
header('Location: http://motochki-klubochki.ru/catalog/'.$urlArr[2].'/');
die();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>