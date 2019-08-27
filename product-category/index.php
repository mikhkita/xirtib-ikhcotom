<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$GLOBALS['APPLICATION']->RestartBuffer();
// vardump($urlArr[2]);
// $url = '/catalog/'.$urlArr[2].'/';
// echo '<script>window.location = "'.$url.'";</script>';
header("HTTP/1.1 301 Moved Permanently");
header('Location: http://motochki-klubochki.ru/catalog/'.$urlArr[2].'/');
die();
// vardump('ok');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>