<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$GLOBALS['APPLICATION']->RestartBuffer();
// vardump($urlArr[2]);
$url = '/catalog/'.$urlArr[2].'/';
echo '<script>window.location = "'.$url.'";</script>';
// header('Location: /catalog/'.$urlArr[2].'/');
// vardump('ok');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>