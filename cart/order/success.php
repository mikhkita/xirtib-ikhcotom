<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оформление заказа");
?>

<?
	use Bitrix\Main\Context,
    Bitrix\Currency\CurrencyManager,
    Bitrix\Sale\Order,
    Bitrix\Sale\Basket,
    Bitrix\Sale\Delivery,
    Bitrix\Sale\PaySystem;

	global $USER;

	Bitrix\Main\Loader::includeModule("sale");
	Bitrix\Main\Loader::includeModule("catalog");

	// $request = Context::getCurrent()->getRequest();

	$name = $_REQUEST["name"];
	$phone = $_REQUEST["phone"];
	$email = $_REQUEST["email"];
	$address = $_REQUEST["address"];
	$comment = $_REQUEST["comment"];

	$siteId = Context::getCurrent()->getSite();
	$currencyCode = CurrencyManager::getBaseCurrency();

	// Создаёт новый заказ
	$order = Order::create($siteId, $USER->isAuthorized() ? $USER->GetID() : null);
	$order->setPersonTypeId(1);
	$order->setField('CURRENCY', $currencyCode);
	if ($comment) {
	    $order->setField('USER_DESCRIPTION', $comment); // Устанавливаем поля комментария покупателя
	}

	$basket = \Bitrix\Sale\Basket::loadItemsForFUser(
	   \Bitrix\Sale\Fuser::getId(),
	   \Bitrix\Main\Context::getCurrent()->getSite()
	);

	$order = Bitrix\Sale\Order::create(
		\Bitrix\Main\Context::getCurrent()->getSite(),
		\Bitrix\Sale\Fuser::getId());
	$order->setBasket($basket);

	// Создаём одну отгрузку и устанавливаем способ доставки - "Без доставки" (он служебный)
	// $shipmentCollection = $order->getShipmentCollection();
	// $shipment = $shipmentCollection->createItem();
	// $service = Delivery\Services\Manager::getById(Delivery\Services\EmptyDeliveryService::getEmptyDeliveryServiceId());
	// $shipment->setFields(array(
	//     'DELIVERY_ID' => $service['ID'],
	//     'DELIVERY_NAME' => $service['NAME'],
	// ));
	// $shipmentItemCollection = $shipment->getShipmentItemCollection();
	// $shipmentItem = $shipmentItemCollection->createItem($item);
	// $shipmentItem->setQuantity($item->getQuantity());

	// // Создаём оплату со способом #1
	// $paymentCollection = $order->getPaymentCollection();
	// $payment = $paymentCollection->createItem();
	// $paySystemService = PaySystem\Manager::getObjectById(1);
	// $payment->setFields(array(
	//     'PAY_SYSTEM_ID' => $paySystemService->getField("PAY_SYSTEM_ID"),
	//     'PAY_SYSTEM_NAME' => $paySystemService->getField("NAME"),
	// ));

	// Устанавливаем свойства
	$propertyCollection = $order->getPropertyCollection();
	$nameProp = $propertyCollection->getPayerName();
	$nameProp->setValue($name);
	$phoneProp = $propertyCollection->getPhone();
	$phoneProp->setValue($phone);
	$emailProp = $propertyCollection->getUserEmail();
	$emailProp->setValue($email);
	$addrProp = $propertyCollection->getAddress();
	$addrProp->setValue($address);

	// Сохраняем
	$order->doFinalAction(true);
	$result = $order->save();
	$orderId = $order->getId();
?>

<div class="b-block">
	<?$orderID = $order->getId();?>
	<?if($orderID > 0):?>
		<h2 class="b-title">Ваш заказ №<?=$orderID?> успешно оформлен</h2>
	<?else:?>
		<h2 class="b-title">Не удалось создать заказ</h2>
	<?endif;?>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>