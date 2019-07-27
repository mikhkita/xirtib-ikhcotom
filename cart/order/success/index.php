<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Оформление заказа");

$orderID = $_REQUEST["ORDER_ID"];

?>
<div class="b-block">
	<?if($orderID > 0):?>
		<h2 class="b-title">Ваш заказ №<?=$orderID?> успешно оформлен</h2>
	<?else:?>
		<h2 class="b-title">Не удалось создать заказ</h2>
	<?endif;?>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>