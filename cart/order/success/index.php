<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оформление заказа"); 

if ($order = \Bitrix\Sale\Order::load($_GET['ID'])) {?>
	<h2 class="b-title">Ваш заказ успешно создан</h2>
	<div>Для отслеживания изменения статуса заказа вы можете перейти в <a href="/personal/" class="underline">личный кабинет</a></div>
<?} else {?>
	<h2 class="b-title">Произошла ошибка создания заказа</h2>
<?}?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>