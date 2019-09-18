<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$APPLICATION->RestartBuffer();

if( !$_REQUEST["USER_LOGIN"] ){
	echo json_encode(array('result' => 'error', 'message' => 'Вы не ввели e-mail.<br>', "action" => "messageError"));
}elseif( !$_REQUEST["USER_PASSWORD"] ){
	echo json_encode(array('result' => 'error', 'message' => 'Вы не ввели пароль.<br>', "action" => "messageError"));
}elseif( !$arResult["ERROR"] ){
	$arResult = array();
	$arResult["isAuth"] = true;
	$arResult["arFav"] = getFavourites();
	$arResult["favCount"] = (!empty($arResult["arFav"])) ? count($arResult["arFav"]) : 0;
	$basketInfo = getBasketCount();
	$arResult["sum"] = $basketInfo["sum"];
	$arResult["count"] = $basketInfo["count"];
	$arUser = getUserFields();
	$arResult["userName"] = ($arUser && $arUser["NAME"]) ? $arUser["NAME"] : "";

	echo json_encode(array(
		'result' => "success", 
		'action' => 'reload',
		'userData' => $arResult
	));
}else{
	echo json_encode(array(
		"result" => "error",
		"message" => $arResult["ERROR_MESSAGE"]["MESSAGE"],
		"action" => "messageError"
	));
}
die();
?>