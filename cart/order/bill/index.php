<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

use Bitrix\Sale,
	Bitrix\Sale\PaySystem;

$data = base64_decode($_REQUEST["HASH"]);

$orderId = array_pop(explode("-", $data));

$order = Sale\Order::load($orderId);

// var_dump($order);
// die();

if( !$order || !isset($orderId) ){
	LocalRedirect("/cart/order/");
}

$paymentCollection = $order->getPaymentCollection();
$payment = null;
foreach ($paymentCollection as $paymentTmp){
	$payment = $paymentTmp;
	break;
}

$bufferedOutput = null;
$paySystemService = PaySystem\Manager::getObjectById($payment->getPaymentSystemId());
$initResult = $paySystemService->initiatePay($payment, null, PaySystem\BaseServiceHandler::STRING);
if ($initResult->isSuccess()){
	$bufferedOutput = $initResult->getTemplate();
}

$GLOBALS['APPLICATION']->RestartBuffer();
echo $bufferedOutput;
die();