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
	$payment = $_REQUEST["payment"];

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

	// Способ оплаты
	$paymentCollection = $order->getPaymentCollection();
	$paymentItem = $paymentCollection->createItem();
	$paySystemService = \Bitrix\Sale\PaySystem\Manager::getObjectById($payment);
	$paymentItem->setFields(array(
	    'PAY_SYSTEM_ID' => $paySystemService->getField("ID"),
	    'PAY_SYSTEM_NAME' => $paySystemService->getField("NAME"),
	));

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

	if ($orderId > 0) {

		if (!isAuth()) {
			
			$rsUser = CUser::GetByLogin($email);
			$arUser = $rsUser->Fetch();

			if (!is_object($user)) $user = new CUser;

			if (!$arUser) {

				$password = randomPassword();
				$arFields = Array(
				  "EMAIL"             => $email,
				  "LOGIN"             => $email,
				  "LID"               => "ru",
				  "ACTIVE"            => "Y",
				  "PASSWORD"          => $password,
				  "CONFIRM_PASSWORD"  => $password,
				);

				if ($id = $user->Add($arFields)){
					CEvent::Send("NEW_USER_FROM_ORDER", "s1", array('EMAIL' => $email, "PASS" => $password));

					$user->Authorize($arUser['ID']);
					
					$tmpOrder = \Bitrix\Sale\Order::load($orderId);
					$tmpOrder->setFieldNoDemand('USER_ID', $arUser['ID']);
					$tmpOrder->save();
				}
			}
		}
	}

	LocalRedirect("/cart/order/success/?ID=".$orderId);

?>

<!-- <div class="b-block">
	<?if($orderID > 0):?>
		<h2 class="b-title">Ваш заказ №<?=$orderID?> успешно оформлен</h2>
	<?else:?>
		<h2 class="b-title">Не удалось создать заказ</h2>
	<?endif;?>
</div> -->

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>