<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оформление заказа");

use Bitrix\Sale;

$order = Sale\Order::load($_REQUEST["ORDER_ID"]);

if( !$order || $order->getUserId() != $USER->GetID() ){
	LocalRedirect("/cart/order/");
}

// var_dump($order);



?>
<div class="b-block b-text b-politics-text">
	<? if( $order->isPaid() ): ?>
		<h1 class="b-title">Ваш заказ №<?=$orderID?> успешно создан!</h1>
		<p>Наш менеджер свяжется с Вами в ближайшее время по телефону, который Вы указали  при оформлении заказа, для уточнения деталей.</p>
	<? else: ?>
		<h1 class="b-title b-politics-text">Ваш заказ №<?=$orderID?> успешно оплачен</h1>
		<p>Оплата заказа прошла успешно! Наш менеджер свяжется с Вами в ближайшее время по телефону, который Вы указали при оформлении заказа, для уточнения деталей.</p>
	<? endif; ?>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>