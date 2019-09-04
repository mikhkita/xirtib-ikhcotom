<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

use Bitrix\Sale,
	Bitrix\Sale\PaySystem;


$order = Sale\Order::load($_REQUEST["ORDER_ID"]);

if( !$order || !isset($_REQUEST["ORDER_ID"]) ){
	LocalRedirect("/cart/order/");
}

$paymentId = array_pop($order->getPaymentSystemId());
if( $paymentId ){
	$paymentSystem = \Bitrix\Sale\PaySystem\Manager::getById($paymentId);
}else{
	$paymentSystem = array(
		"NAME" => "Не указан"
	);
}

$deliveryId = array_pop($order->getDeliverySystemId());
if( $deliveryId ){
	$delivery = \Bitrix\Sale\Delivery\Services\Manager::getById($deliveryId);
}else{
	$delivery = array(
		"NAME" => "Не указан"
	);
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

// var_dump($bufferedOutput);
if( $order->isPaid() ){
	$APPLICATION->SetTitle("Ваш заказ №".$order->getId()." успешно оплачен");
}else{
	$APPLICATION->SetTitle("Ваш заказ №".$order->getId()." успешно создан!");
}


?>
<div class="b-text">
	<? if( $order->isPaid() ): ?>
		<!-- <p style="max-width: 600px;">Оплата заказа прошла успешно! Наш менеджер свяжется с Вами в ближайшее время по телефону, который Вы указали при оформлении заказа, для уточнения деталей.</p> -->
	<? else: ?>
		<!-- <p style="max-width: 600px;">Наш менеджер свяжется с Вами в ближайшее время по телефону, который Вы указали  при оформлении заказа, для уточнения деталей.</p> -->
		<ul>
			<li><b>Способ доставки:</b> <?=$delivery["NAME"]?></li>
			<li><b>Способ оплаты:</b> <?=$paymentSystem["NAME"]?><?=(($paymentSystem["ID"] == 1)?" (счет на оплату придет на Ваш e-mail)":"")?></li>
			<? /* ?><li><b>Стоимость доставки:</b> <?=rtrim(rtrim(number_format($order->getDeliveryPrice(), 1, '.', ' '),"0"),".")?> руб.</li><? */ ?>
			<li><b>Сумма к оплате:</b> <?=rtrim(rtrim(number_format($order->getPrice(), 1, '.', ' '),"0"),".")?> руб.</li>
		</ul>
	<? endif; ?>
</div>
<? if( !$order->isPaid() && $paymentSystem["ID"] == 1 ): ?>
	<a href="/cart/order/bill/?HASH=<?=base64_encode("bill-".$order->getId())?>" class="b-btn b-bill-btn" target="_blank">Открыть счет на оплату</a>
<? endif; ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>