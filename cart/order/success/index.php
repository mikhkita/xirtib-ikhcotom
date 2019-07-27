<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Оформление заказа");

use Bitrix\Sale;

$order = Sale\Order::load($_REQUEST["ORDER_ID"]);

if( !$order || $order->getUserId() != $USER->GetID() || !isset($_REQUEST["ORDER_ID"]) ){
	LocalRedirect("/cart/order/");
}

$paymentId = array_pop($order->getPaymentSystemId());
$payment = \Bitrix\Sale\PaySystem\Manager::getById($paymentId);

$deliveryId = array_pop($order->getDeliverySystemId());
$delivery = \Bitrix\Sale\Delivery\Services\Manager::getById($deliveryId);

// var_dump($payment);



?>
<div class="b-text">
	<? if( $order->isPaid() ): ?>
		<h1 class="b-title b-politics-text">Ваш заказ №<?=$order->getId()?> успешно оплачен</h1>
		<p style="max-width: 600px;">Оплата заказа прошла успешно! Наш менеджер свяжется с Вами в ближайшее время по телефону, который Вы указали при оформлении заказа, для уточнения деталей.</p>
	<? else: ?>
		<h1 class="b-title">Ваш заказ №<?=$order->getId()?> успешно создан!</h1>
		<p style="max-width: 600px;">Наш менеджер свяжется с Вами в ближайшее время по телефону, который Вы указали  при оформлении заказа, для уточнения деталей.</p>
		<ul>
			<li><b>Способ доставки:</b> <?=$delivery["NAME"]?></li>
			<li><b>Способ оплаты:</b> <?=$payment["NAME"]?></li>
			<li><b>Стоимость доставки:</b> <?=$order->getDeliveryPrice()?> руб.</li>
			<li><b>Сумма к оплате:</b> <?=$order->getPrice()?> руб.</li>
		</ul>
	<? endif; ?>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>