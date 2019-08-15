<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?>
		<div class="b-contacts clearfix">
			<div class="b-contacts-left">
				<ul class="b-contacts-list">
					<li class="contacts-map">
						<img src="<?=SITE_TEMPLATE_PATH?>/i/icon-map.svg">
						<p>г.&nbsp;Томск, пер.&nbsp;Дербышевского, 26Б, 3&nbsp;этаж, офис&nbsp;304</p>
					</li>
					<li class="contacts-phone">
						<img src="<?=SITE_TEMPLATE_PATH?>/i/call-icon.svg">
						<div class="telephone telephone-1"><a href="tel:+79138200534">+7 (913) 820 0534</a><img src="<?=SITE_TEMPLATE_PATH?>/i/contacts-call-1.svg"></div><br>
						<div class="telephone telephone-2"><a href="tel:+79039538088">+7 (903) 953 8088</a><img src="<?=SITE_TEMPLATE_PATH?>/i/contacts-call-2.svg"></div>
					</li>
					<li class="contacts-email">
						<img src="<?=SITE_TEMPLATE_PATH?>/i/email-icon.svg">
						<a href="mailto:mkv70@yandex.ru">mkv70@yandex.ru</a>
					</li>
					<li class="contacts-vk">
						<img src="<?=SITE_TEMPLATE_PATH?>/i/icon-vk.svg">
						<a href="https://www.vk.com/mkv70" target="_blank">/mkv70</a>
					</li>
					<li class="contacts-insta">
						<img src="<?=SITE_TEMPLATE_PATH?>/i/icon-insta.svg">
						<a href="https://www.instagram.com/motochkiklubochki" target="_blank">/motochkiklubochki</a>
					</li>
				</ul>
			</div>
			<div class="b-contacts-right">
				<div class="b-contacts-desc b-text">
					<h3>Время работы</h3>
					<p>Интернет-магазин «Моточки Клубочки» работает <b>круглосуточно</b>.</p>
					<p>Время работы офиса  на текущую неделю не установлено, встречи только по <b>предварительной договоренности</b>.</p>
					<p>За графиком работы офиса Вы можете следить<br>на <a href="/"><b>сайте</b></a> или в нашем <a href="https://www.instagram.com/motochkiklubochki" target="_blank"><b>профиле Instagram</b></a>.</p>
				</div>
			</div>
		</div>
	</div>
	<div id="map_canvas"></div>
<div class="b-block">
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>