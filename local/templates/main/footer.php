<? if (!$isMain): ?>
</div>
	</div>
<? endif ?>
	<div class="b-footer">
		<div class="b-block clearfix">
			<div class="b-footer-top">
				<div class="b-footer-top-text">
					<h3>Видео-магазин в WhatsApp</h3>
					<p>Если вы сомневаетесь в выборе того или иного товара, мы можем провести его полный обзор с помощью видео-связи в приложении WhatsApp. Просто оставьте свой номер телефона и мы перезвоним вам для записи.</p>
				</div>
				<div class="b-footer-top-input">
					<form action="/ajax/action=WHATSAPP" method="POST" class="b-footer-inputs clearfix">
						<input type="text" name="name" placeholder="Ваше имя" required>
						<input type="text" name="phone" placeholder="Ваш телефон" required>
						<input type="text" name="MAIL">
						<a href="#" class="b-btn ajax">Записаться</a>
						<div class="politics">Отправляя форму, я даю согласие на обработку моих персональных данных в соответствии с <a href="/docs/politics/" class="underlined">политикой конфиденциальности</a></div>
					</form>
				</div>
			</div>
			<div class="b-footer-bottom clearfix">
				<div class="b-footer-bottom-left">
					<p class="b-city">Интернет-магазин пряжи</p>
					<a href="#" class="b-footer-logo"></a>
					<a href="tel:+79039538088" class="b-phone">+7 (903) 953-8088</a>
					<a class="b-phone-call underlined">Обратный звонок</a>
					<div class="b-write-us">
						<p>Напишите нам:</p>
						<div class="b-footer-messenger">
							<a href="#" class="icon-vk"></a>
							<a href="#" class="icon-whatsapp"></a>
							<a href="#" class="icon-viber"></a>
						</div>
					</div>
					<a href="#" class="b-btn">Обратная связь</a>
				</div>
				<div class="b-footer-bottom-middle">
					<?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "footer_categories", Array(
							"ADD_SECTIONS_CHAIN" => "N",
							"CACHE_GROUPS" => "Y",
							"CACHE_TIME" => "36000000",
							"CACHE_TYPE" => "N",
							"COUNT_ELEMENTS" => "Y",
							"IBLOCK_ID" => "1",
							"IBLOCK_TYPE" => "content",
							"SHOW_PARENT_NAME" => "Y",
							"TOP_DEPTH" => "1",
							"VIEW_MODE" => "LINE",
						),
						false
					);?>
					<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_menu", array(
						"ROOT_MENU_TYPE" => "main",
						"MAX_LEVEL" => "1",
						"MENU_CACHE_TYPE" => "A",
						"CACHE_SELECTED_ITEMS" => "N",
						"MENU_CACHE_TIME" => "36000000",
						"MENU_CACHE_USE_GROUPS" => "Y",
						"MENU_CACHE_GET_VARS" => array(),
					),
						false
					);?>
				</div>
				<div class="b-footer-bottom-right">
					<div class="b-subscribe">
						<h3 class="b-subscribe-us">Будьте в курсе новинок, подпишитесь на рассылку!</h3>
						<form class="b-footer-inputs clearfix">
							<input type="text" name="name" placeholder="Имя" required>
							<input type="text" name="email" placeholder="E-mail" required>
							<a href="#" class="b-btn ajax">Подписаться</a>
							<div class="politics">Отправляя форму, я даю согласие на обработку моих персональных данных в соответствии с <a href="/docs/politics/">политикой конфиденциальности</a></div>
						</form>
					</div>
					<div class="b-social">
						<p>Присоединяйтесь к нам в соцсетях:</p>
						<div class="b-footer-social">
							<a href="#" class="icon-vk"></a>
							<a href="#" class="icon-im"></a>
							<a href="#" class="icon-yt"></a>
						</div>
					</div>
					<div class="b-all-politics">
						<a href="/docs/rules/" class="underlined">Правила предоставления услуг</a>
						<a href="/docs/agreement/" class="underlined">Пользовательское соглашение</a>
						<a href="/docs/politics/" class="underlined">Политика конфиденциальности</a>
						<a href="/docs/offer/" class="underlined">Договор-оферта</a>
					</div>
				</div>
				<div class="b-copyright">
					<p>© Моточки-Клубочки. Все права защищены</p>
					<a href="http://redder.pro" class="b-redder">Разработка сайта: REDDER</a>
				</div>
			</div>
		</div>
	</div>
	<div style="display:none;">
		<a href="#b-popup-error" class="b-error-link fancy" style="display:none;"></a>
		<div id="element_view" class="b-popup">
			<div class="b-popup-element-cont b-block"></div>
		</div>
		<div class="b-popup" id="b-popup-1">
			<h3>Оставьте заявку</h3>
			<h4>и наши специалисты<br>свяжутся с Вами в ближайшее время</h4>
			<form action="kitsend.php" data-goal="CALLBACK" method="POST" id="b-form-1">
				<div class="b-popup-form">
					<label for="name">Введите Ваше имя</label>
					<input type="text" id="name" name="name" required/>
					<label for="tel">Введите Ваш номер телефона</label>
					<input type="text" id="tel" name="phone" required/>
					<label for="tel">Введите Ваш E-mail</label>
					<input type="text" id="tel" name="email" required/>
					<input type="hidden" name="subject" value="Заказ"/>
					<input type="submit" style="display:none;">
					<a href="#" class="b-btn b-blue-btn ajax">Заказать</a>
					<a href="#b-popup-success" class="b-thanks-link fancy" style="display:none;"></a>
				</div>
			</form>
		</div>
		<div class="b-thanks b-popup" id="b-popup-success">
			<h3>Спасибо!</h3>
			<h4>Ваша заявка успешно отправлена.<br/>Наш менеджер свяжется с Вами в течение часа.</h4>
			<input type="submit" class="b-orange-butt" onclick="$.fancybox.close(); return false;" value="Закрыть">
		</div>
		<div class="b-thanks b-popup" id="b-popup-error">
			<h3>Ошибка отправки!</h3>
			<h4>Приносим свои извинения. Пожалуйста, попробуйте отправить Вашу заявку позже.</h4>
			<input type="submit" class="b-orange-butt" onclick="$.fancybox.close(); return false;" value="Закрыть">
		</div>

		<div class="b-popup" id="popup-comment">
			<img src="<?=SITE_TEMPLATE_PATH?>/i/popup-logo.svg" alt="" class="popup-img">
			<div class="popup-title"></div>
			<form method="POST" action="/ajax/?action=ADDREVIEW">
				<div class="b-inputs-2 clearfix">
					<div class="b-input">
						<input type="text" name="name" placeholder="Ваше имя">
					</div>
					<div class="b-input">
						<input type="text" name="phone" placeholder="Номер телефона">
					</div>
					<input type="text" name="MAIL" required="">
				</div>
				<div class="b-textarea">
					<textarea rows="3" name="comment" placeholder="Ваш отзыв"></textarea>
				</div>
				<div class="politics">Отправляя форму, я даю согласие на обработку моих персональных данных в соответствии с <a href="/docs/politics/" class="underlined">политикой&nbsp;конфиденциальности</a></div>
				<a href="#" class="b-btn b-btn-callback ajax">Отправить отзыв</a>
			</form>
		</div>

		<a href="#b-popup-map" class="b-popup-map-link fancy" style="display:none;"></a>
		<div class="b-popup b-popup-map" id="b-popup-map">
			<h3>Выбор адреса доставки</h3>
			<div class="js-popup-map-address">
				<div class="b-map-padding">
				    <form class="order-adress-map-form clearfix" style="position: relative">
				        <div class="order-adress-map-form-content">
				            <div class="b-inputs form-item __adress b-ui-autocomplete clearfix">
								<div class="b-inputs-left">
					            	<div class="b-input ui-menu ui-widget ui-widget-content ui-autocomplete ui-front">
					            		<p>Адрес, дом</p>
					            		<input type="text" id="js-order-adress-map-input" class="js-order-adress-map-input" autocomplete="off">
					                </div>
								</div>
								<div class="b-inputs-right">
					                <div class="b-input number-room">
							        	<p>Квартира/офис</p>
										<input type="text" id="number-room-input" autocomplete="off" class="number-room-input" maxlength="5">
							        </div>
							        <div class="b-input">
							        	<p>Индекс</p>
							        	<input type="text" id="postal-code" class="postal-code">
							        </div>
							        <a href="#" class="b-btn b-btn-buy b-btn-address">Принять</a>
								</div>
				            </div>
				        </div>
				        <input type="submit" class="b-popup-map-submit" style="display:none;">
				    </form>
			    </div>
			    <div id="map-address"></div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery.fancybox.min.js"></script>
	<!-- <script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false&key=AIzaSyD6Sy5r7sWQAelSn-4mu2JtVptFkEQ03YI"></script> -->
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery.touch.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery.maskedinput.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/KitAnimate.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/plupload.full.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/mask.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/KitProgress.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/KitSend.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/slick.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/autosize.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery-ui.min.js"></script>
	<!-- Плагины для страницы оформления заказа -->
	<? if($urlArr[1] == "personal"): ?>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/vue.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/vee-validate.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery.sticky-kit.min.js"></script>
	<!-- <script type="text/javascript" src="js/cleave.min.js"></script> -->
	<!-- <script type="text/javascript" src="js/axios.min.js"></script> -->
	<!-- <script type="text/javascript" src="js/vue-resource.min.js"></script> -->
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery.mask.min.js"></script>
	<script type="text/javascript" src="https://api-maps.yandex.ru/2.1.41/?load=package.full&amp;lang=ru-RU"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/address.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/AddressDeliveryClass.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/app-order.js"></script>
	<? endif; ?>
	<!-- \\\\\\\\ -->
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/main.js"></script>
</body>
</html>