<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Моточки - клубочки");

$arFields["ORDER_ID"] = 21;

$order = Bitrix\Sale\Order::load($arFields["ORDER_ID"]);
$deliveryID = $order->getField("DELIVERY_ID");
$arDelivery = Bitrix\Sale\Delivery\Services\Manager::getById($deliveryID);

$arBasketFilter = array("LID" => 's1',"ORDER_ID" => $arFields["ORDER_ID"]);
$arBasketSelect = array("PRODUCT_ID", "NAME", "PRICE", "BASE_PRICE", "QUANTITY", "DISCOUNT_PRICE", "DETAIL_PAGE_URL");
$dbBasketItems = CSaleBasket::GetList(array("NAME" => "ASC","ID" => "ASC"), $arBasketFilter, false, false, $arBasketSelect);

$arBasketItems = '<style>td{padding:2px 8px}td a{text-decoration:underline}</style>';
$arBasketItems.= '<table>'.
					"<tr>".
					 	"<td>Наименование товара</td>".
					 	"<td>Количество</td>".
					 	"<td>Цена</td>".
					 	"<td>Цена со скидкой</td>".
					 	"<td>Сумма</td>".
					 "</tr>";
$totalSum = 0;

while ($item = $dbBasketItems->Fetch()){

	$discountPrice = (intval($item['DISCOUNT_PRICE']) == 0) ? round($item['BASE_PRICE']) : round($item['DISCOUNT_PRICE']);
	$sum = (intval($item['DISCOUNT_PRICE']) == 0) ? round($item['QUANTITY']*$item['BASE_PRICE']) : round($item['QUANTITY']*$item['DISCOUNT_PRICE']);
	$totalSum += $sum;

    $arBasketItems.="<tr>".
		"<td><a href='http://motochki-klubochki.ru".$item['DETAIL_PAGE_URL']."'>".$item['NAME']."</a></td>".
		"<td>".round($item['QUANTITY'])."</td>".
		"<td>".round($item['BASE_PRICE'])."</td>".
		"<td>".$discountPrice."</td>".
		"<td>".$sum."</td>".
	"</tr>";
}

$arBasketItems.= "<tr>".
				 	"<td></td>".
				 	"<td></td>".
				 	"<td></td>".
				 	"<td style='text-align:right;'><b>Итого:</b></td>".
				 	"<td>".$totalSum."</td>".
				 "</tr>".
			"</table>";

$arFields['ITEMS_INFO'] = $arBasketItems;
$arFields['DELIVERY_NAME'] = $arDelivery['NAME'];

echo $arFields['ITEMS_INFO'];

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>