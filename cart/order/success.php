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
	$address = $_REQUEST["rdrdlvr"];
	$comment = $_REQUEST["ORDER_DESCRIPTION"];
	$paymentId = $_REQUEST["payment"];
	$deliveryId = $_REQUEST["delivery"];
	$deliveryPrice = $_REQUEST["delivery-cost"];
	$cdek = $_REQUEST["CDEK"];

	$siteId = Context::getCurrent()->getSite();
	$currencyCode = CurrencyManager::getBaseCurrency();

	// Создаёт новый заказ
	$order = Order::create($siteId, $USER->isAuthorized() ? $USER->GetID() : null);
	$order->setPersonTypeId(1);
	$order->setField('CURRENCY', $currencyCode);

	$basket = \Bitrix\Sale\Basket::loadItemsForFUser(
	   \Bitrix\Sale\Fuser::getId(),
	   \Bitrix\Main\Context::getCurrent()->getSite()
	);

	if( count($basket) ){
		$order = Bitrix\Sale\Order::create(
			\Bitrix\Main\Context::getCurrent()->getSite(),
			\Bitrix\Sale\Fuser::getId());
		$order->setBasket($basket);

		if ($comment) {
		    $order->setField('USER_DESCRIPTION', $comment); // Устанавливаем поля комментария покупателя
		}

		// Способ доставки
		$shipmentCollection = $order->getShipmentCollection();
		$shipment = $shipmentCollection->createItem();
		$service = Delivery\Services\Manager::getById($deliveryId);
		$shipment->setFields(array(
		    'DELIVERY_ID' => $service['ID'],
		    'DELIVERY_NAME' => $service['NAME'],
		    'PRICE_DELIVERY' => $deliveryPrice,
    		'BASE_PRICE_DELIVERY' => $deliveryPrice,
		));
		$shipmentItemCollection = $shipment->getShipmentItemCollection();
		foreach ($basket as $key => $item) {
			$shipmentItem = $shipmentItemCollection->createItem($item);
			$shipmentItem->setQuantity($item->getQuantity());
		}

		function getPropertyByCode($propertyCollection, $code)  {
		    foreach ($propertyCollection as $property)
		    {
		        if($property->getField('CODE') == $code)
		            return $property;
		    }
		}

		// Способ оплаты
		$paymentCollection = $order->getPaymentCollection();
		$paymentItem = $paymentCollection->createItem();
		$paySystemService = \Bitrix\Sale\PaySystem\Manager::getObjectById($paymentId);
		$paymentItem->setFields(array(
		    'PAY_SYSTEM_ID' => $paySystemService->getField("ID"),
		    'PAY_SYSTEM_NAME' => $paySystemService->getField("NAME"),
		    "SUM" => $order->getPrice(),
		    "CURRENCY" => $order->getCurrency(),
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
		$cdekProp = getPropertyByCode($propertyCollection, "CDEK");
		$cdekProp->setValue($cdek);

		// Сохраняем
		$order->doFinalAction(true);

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
					"PERSONAL_PHONE"    => $phone,
					"NAME"  			=> $name,
				);

				if ($id = $user->Add($arFields)){
					CEvent::Send("NEW_USER_FROM_ORDER", "s1", array('EMAIL' => $email, "PASS" => $password));

					$user->Authorize($id);
					
					$order->setFieldNoDemand('USER_ID', $id);
				}
			}else{
				$order->setFieldNoDemand('USER_ID', $arUser["ID"]);
			}
		}else{
			$order->setFieldNoDemand('USER_ID', $USER->GetID());
		}

		$result = $order->save();
		$orderId = $order->getId();

		$paymentCollection = $order->getPaymentCollection();
		foreach ($paymentCollection as $payment) {
			if (intval($payment->getPaymentSystemId()) > 0 && !$payment->isPaid()) {
				$paySystemService = PaySystem\Manager::getObjectById($payment->getPaymentSystemId());
				$arPaySysAction = $paySystemService->getFieldsValues();

				if ($paySystemService->getField('NEW_WINDOW') === 'N' || $paySystemService->getField('ID') == PaySystem\Manager::getInnerPaySystemId()) {
					$initResult = $paySystemService->initiatePay($payment, null, PaySystem\BaseServiceHandler::STRING);
					if ($initResult->isSuccess())
						$arPaySysAction['BUFFERED_OUTPUT'] = $initResult->getTemplate();
					else
						$arPaySysAction["ERROR"] = $initResult->getErrorMessages();

					// var_dump($arPaySysAction);
					// die();
				}
			}
		}

		if( !empty($arPaySysAction["BUFFERED_OUTPUT"]) && $arPaySysAction["ID"] != 1 ){
			$GLOBALS['APPLICATION']->RestartBuffer();?>
			<div style="display: none;">
			<?
				$_SESSION['SALE_ORDER_ID'] = array($orderId);
				echo $arPaySysAction["BUFFERED_OUTPUT"];
			?>
			</div>
			<script>
				document.getElementsByClassName("btn-buy")[0].click();
			</script>
			<?
			die();
		}

		LocalRedirect("/cart/order/success/?ORDER_ID=".$orderId);
	}else{
		LocalRedirect("/cart/order/");
	}

?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>