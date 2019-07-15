<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Доставка и оплата");
?>

	<h2 class="b-title b-title-left"><?$APPLICATION->ShowTitle()?></h2>
	<div class="b-delivery-info">
		<div class="delivery-info-item">
			<div class="delivery-info-item-title">Совершить покупку<br> в нашем магазине очень просто</div>
			<p>Заказы принимаются круглосуточно на нашем сайте motochki-klubochki.ru. <b>Заказы по телефону не принимаются.</b></p>
			<div class="delivery-info-list-title">Совершая заказ в нашем магазине вы принимаете</div>
			<ul class="delivery-info-list">
				<li><a href="/docs/agreement/" target="_blank">Пользовательское соглашение.</a></li>
				<li><a href="/docs/politics/" target="_blank">Политика конфиденциальности персональных данных</a></li>
				<li><a href="/docs/offer/" target="_blank">Договор оферта</a></li>
			</ul>
		</div>
		<div class="delivery-info-text">
			<p>Как только Вы сделали заказ, вам на почту приходит автоматическое подтверждение с номером заказа, затем с вами свяжется наш менеджер (по электронной почте или по телефону) и уточнит наличие заказанного вами товара.</p>
			<p>Чтобы сформировать заказ нам необходимо некоторое время. Мы стараемся собрать заказ как можно быстрее, обычно нам требуется от 1 до 2 рабочих дней.</p>
			<p>Доводим до Вашего сведения, что сформированные (согласованные по электронной почте или по телефону), но не оплаченные заказы находятся в резерве не более 2 рабочих дней</p>
		</div>
	</div>
	<div class="b-payment">
		<h2 class="payment-title">Оплата товара</h2>
		<ul class="payment-list">
			<li>
				<div class="payment-list-image"><img src="<?=SITE_TEMPLATE_PATH?>/i/payment-img-1.svg" alt=""></div>
				<div class="payment-list-text">Банковской картой на сайте</div>
			</li>
			<li>
				<div class="payment-list-image"><img src="<?=SITE_TEMPLATE_PATH?>/i/payment-img-2.svg" alt=""></div>
				<div class="payment-list-text">Переводом через Сбербанк.Онлайн</div>
			</li>
		</ul>
		<div class="payment-info">
			<div class="payment-info-title">Банковские реквизиты</div>
			<div class="payment-info-text">
				<p><b>ИП Шпичко Елена Николаевна</b></p>
				<p><b>Номер счета:</b> 40802810264000006683</p>
				<p>ПАО Сбербанк Томское отделение №8616</p>
				<p><b>Кор.счет:</b> 30101810800000000606</p>
				<p><b>ИНН:</b> 701900718370</p>
				<p><b>БИК:</b> 046902606</p>
			</div>
		</div>
	</div>
	<div class="b-delivery-methods">
		<div class="delivery-methods-title">Доставка товара</div>
		<div class="delivery-methods-list">
			<div class="delivery-method">
				<div class="delivery-method-image"><img src="<?=SITE_TEMPLATE_PATH?>/i/delivery-method-1.svg" alt=""></div>
				<div class="delivery-method-title">Почта России</div>
				<div class="delivery-method-text">Сроки доставки почтой России от 1 недели. Отследить посылку можно с помощью ТРЭКа.</div>
			</div>
			<div class="delivery-method">
				<div class="delivery-method-image"><img src="<?=SITE_TEMPLATE_PATH?>/i/delivery-method-2.svg" alt=""></div>
				<div class="delivery-method-title">Доставка СДЭК</div>
				<div class="delivery-method-text">Стоимость доставки зависит от вашего города. Доставка до склада в Томске – бесплатно.</div>
			</div>
			<div class="delivery-method">
				<div class="delivery-method-image"><img src="<?=SITE_TEMPLATE_PATH?>/i/delivery-method-3.svg" alt=""></div>
				<div class="delivery-method-title">Курьерская доставка</div>
				<div class="delivery-method-text">Стоимость доставки равна стоимости такси от нашего офиса до вашего дома.</div>
			</div>
			<div class="delivery-method">
				<div class="delivery-method-image"><img src="<?=SITE_TEMPLATE_PATH?>/i/delivery-method-4.svg" alt=""></div>
				<div class="delivery-method-title">Самовывоз из офиса</div>
				<div class="delivery-method-text">Забрать товар из офиса можно только при предварительной договоренности.</div>
			</div>
		</div>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>