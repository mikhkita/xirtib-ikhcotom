<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Моточки - клубочки");

$arFields["ORDER_ID"] = 21;


$order = Bitrix\Sale\Order::load($arFields["ORDER_ID"]);

$arBasketItems = array();
$arBasketFilter = array("LID" => 's1',"ORDER_ID" => $arFields["ORDER_ID"]);
$arBasketSelect = array("PRODUCT_ID", "NAME", "PRICE", "BASE_PRICE", "QUANTITY", "DISCOUNT_PRICE");
$dbBasketItems = CSaleBasket::GetList(array("NAME" => "ASC","ID" => "ASC"), $arBasketFilter, false, false, $arBasketSelect);

while ($arItems = $dbBasketItems->Fetch()){
    $arBasketItems[] = $arItems;
}

vardump($arBasketItems);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>