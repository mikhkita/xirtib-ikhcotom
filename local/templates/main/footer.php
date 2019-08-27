<? if (!$isMain): ?>
	</div>
	<? if (!$isAbout): ?>
		</div>
	<? endif ?>
<? endif ?>
	<div class="b-footer">
		<div class="b-block clearfix">
			<div class="b-footer-top">
				<div class="b-footer-top-text">
					<h3><?includeArea("b-footer-text-1")?></h3>
					<p><?includeArea("b-footer-text-2")?></p>
				</div>
				<div class="b-footer-top-input">
					<a href="https://wa.me/79039538088?text=Здравствуйте!%20Хочу%20подробнее%20узнать%20о%20товаре%20с%20помощью%20видеосвязи." class="b-btn goal-click" target="_blank" data-goal="callback_whatsapp">Записаться на обзор пряжи</a>
				</div>
			</div>
			<div class="b-footer-bottom clearfix">
				<div class="b-footer-bottom-left">
					<p class="b-city"><?includeArea("b-footer-text-3")?></p>
					<a href="#" class="b-footer-logo"></a>
					<?includeArea("b-footer-text-4")?>
					<a href="#popup-callback" class="b-phone-call fancy underlined">Обратный звонок</a>
					<div class="b-write-us">
						<p><?includeArea("b-footer-text-5")?></p>
						<div class="b-footer-messenger">
							<?includeArea("b-footer-link-1")?>
							<?includeArea("b-footer-link-2")?>
							<?includeArea("b-footer-link-3")?>
						</div>
					</div>
					<a href="#popup-callback" class="b-btn fancy">Обратная связь</a>
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
						<h3 class="b-subscribe-us"><?includeArea("b-footer-text-6")?></h3>
						<form action="ajax/?action=SUBSCRIBE" method="POST" class="b-footer-inputs clearfix" data-goal="subscribe">
							<input type="text" name="name" placeholder="Имя" required>
							<input type="text" name="email" placeholder="E-mail" required>
							<input type="text" name="MAIL">
							<a href="#" class="b-btn ajax">Подписаться</a>
							<div class="politics"><?includeArea("b-footer-text-7")?></div>
							<a href="#b-popup-subscribe-success" class="b-thanks-link fancy" style="display:none;"></a>
							<a href="#b-popup-error" class="b-error-link fancy" style="display:none;"></a>
						</form>
					</div>
					<div class="b-social">
						<p><?includeArea("b-footer-text-8")?></p>
						<div class="b-footer-social">
							<?includeArea("b-footer-link-4")?>
							<?includeArea("b-footer-link-5")?>
							<!-- <a href="#" class="icon-yt" target="_blank"></a> -->
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
					<div><?includeArea("b-footer-text-9")?></div>
					<a href="http://redder.pro" target="_blank" class="b-redder">Разработка сайта: REDDER</a>
				</div>
			</div>
		</div>
	</div>
	<div class="b-menu-overlay" id="b-menu-overlay"></div>
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
			<form action="kitsend.php" method="POST" id="b-form-1">
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
			<p>Ваша заявка успешно отправлена.<br/>Наш менеджер свяжется с Вами в течение часа.</p>
			<div class="b-btn-container">
				<input type="submit" class="b-btn" onclick="$.fancybox.close(); return false;" value="Закрыть">
			</div>
		</div>

		<div class="b-thanks b-popup" id="b-popup-subscribe-success">
			<h3>Спасибо!</h3>
			<p>Вы успешно подписались на рассылку новостей.</p>
			<div class="b-btn-container">
				<input type="submit" class="b-btn" onclick="$.fancybox.close(); return false;" value="Закрыть">
			</div>
		</div>

		<div class="b-thanks b-popup" id="b-popup-review-success">
			<h3>Спасибо!</h3>
			<p>Ваш отзыв будет опубликован после модерации.</p>
			<div class="b-btn-container">
				<input type="submit" class="b-btn" onclick="$.fancybox.close(); return false;" value="Закрыть">
			</div>
		</div>

		<div class="b-thanks b-popup" id="b-popup-commnet-success">
			<h3>Спасибо!</h3>
			<p>Ваш комментарий будет опубликован после модерации.</p>
			<div class="b-btn-container">
				<input type="submit" class="b-btn" onclick="$.fancybox.close(); return false;" value="Закрыть">
			</div>
		</div>

		<div class="b-thanks b-popup" id="b-popup-success-reg">
			<h3>Подтвердите e-mail</h3>
			<p>Ссылка для подтверждения регистрации отправлена на ваш e-mail.</p>
			<div class="b-btn-container">
				<input type="submit" class="b-btn" onclick="$.fancybox.close(); return false;" value="Закрыть">
			</div>
		</div>

		<div class="b-thanks b-popup" id="b-popup-save-success">
			<h3>Изменения успешно сохранены!</h3>
			<div class="b-btn-container">
				<input type="submit" class="b-btn" onclick="$.fancybox.close(); return false;" value="Закрыть">
			</div>
		</div>

		<div class="b-thanks b-popup" id="b-popup-error">
			<h3>Ошибка отправки!</h3>
			<h4>Приносим свои извинения. Пожалуйста, попробуйте отправить Вашу заявку позже.</h4>
			<div class="b-btn-container">
				<input type="submit" class="b-btn" onclick="$.fancybox.close(); return false;" value="Закрыть">
			</div>
		</div>

		<div class="b-thanks b-popup" id="b-popup-error-reg">
			<h3>Ошибка!</h3>
			<h4>Приносим свои извинения. Пожалуйста, попробуйте позже.</h4>
			<div class="b-btn-container">
				<input type="submit" class="b-btn" onclick="$.fancybox.close(); return false;" value="Закрыть">
			</div>
		</div>

		<div class="b-popup" id="popup-comment">
			<img src="<?=SITE_TEMPLATE_PATH?>/i/popup-logo.svg" alt="" class="popup-img">
			<div class="popup-title"></div>
			<form method="POST" action="/ajax/?action=ADDREVIEW">
				<div class="b-inputs-2 clearfix">
					<? 
					if (isAuth()){
			    		$rsUser = CUser::GetByID($USER->GetID());
			    		$arUser = $rsUser->Fetch();
					}

					$name = isset($arUser['NAME']) ? $arUser['NAME'] : '';
		    		$phone = isset($arUser['PERSONAL_PHONE']) ? $arUser['PERSONAL_PHONE'] : '';

					?>
					<div class="b-input">
						<input type="text" name="name" placeholder="Ваше имя" required value="<?=trim($name)?>">
					</div>
					<div class="b-input">
						<input type="text" name="phone" placeholder="Номер телефона" required value="<?=trim($phone)?>">
					</div>
					<input type="text" name="MAIL" required="">
				</div>
				<div class="b-textarea">
					<textarea rows="3" name="comment" placeholder="Ваш отзыв" required></textarea>
				</div>
				<div class="politics">Отправляя форму, я даю согласие на обработку моих персональных данных в соответствии с <a href="/docs/politics/" class="underlined">политикой&nbsp;конфиденциальности</a></div>
				<a href="#" class="b-btn b-btn-callback ajax">Отправить отзыв</a>

				<a href="#b-popup-review-success" class="b-thanks-link fancy" style="display:none;"></a>
				<a href="#b-popup-error-reg" class="b-error-link fancy" style="display:none;"></a>
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
							        	<input type="text" id="postal-code" class="postal-code" required>
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

		<div class="b-popup popup-sign" id="popup-sign">
			<img src="<?=SITE_TEMPLATE_PATH?>/i/popup-logo.svg" alt="" class="popup-img">
			<ul class="popup-sign-list">
				<li><a href="#popup-form-sign-in" class="active">Войти</a></li>
				<li><a href="#popup-form-sign-up">Регистрация</a></li>
			</ul>
			<div class="popup-sign-form active" id="popup-form-sign-in">
				<form action="/personal/?action=authSite&login=yes" method="POST">
					<div class="b-popup-error"></div>
					<div class="b-popup-form">
						<input type="hidden" name="AUTH_FORM" value="Y">
		                <input type="hidden" name="TYPE" value="AUTH">
		                <input type="hidden" name="Login" value="Войти">
		                <input type="submit" style="display: none;">

						<input type="text" name="USER_LOGIN" placeholder="Электронная почта" required>
						<input type="password" name="USER_PASSWORD" placeholder="Пароль" required>

						<div class="clearfix">
							<a href="/personal/?forgot_password=yes" class="popup-sign-pass-a underline right">Забыли пароль?</a>
						</div>
						<div class="b-btn-container">
							<a href="#" class="b-btn b-btn-sign-in ajax">Войти</a>
						</div>
					</div>
				</form>
			</div>
			<div class="popup-sign-form" id="popup-form-sign-up">
				<form action="/ajax/?action=REG" method="POST" id="regForm">
					<div class="b-popup-error"></div>
					<div class="b-popup-form">
						<input type="text" name="email" placeholder="Электронная почта" required>
						<input type="password" name="password" placeholder="Пароль" required>
						<input type="text" name="MAIL">
						<input type="submit" style="display: none;">
						<div class="b-btn-container">
							<a href="#" class="b-btn b-btn-sign-up ajax">Зарегистрироваться</a>
						</div>
						<a href="#b-popup-success-reg" class="b-thanks-link fancy" style="display:none;"></a>
						<a href="#b-popup-error-reg" class="b-error-link fancy" style="display:none;"></a>
					</div>
				</form>
			</div>
		</div>

		<div class="b-popup b-review-popup" id="popup-callback">
			<img src="<?=SITE_TEMPLATE_PATH?>/i/popup-logo.svg" class="popup-img">
			<div class="popup-title"></div>
			<form method="POST" action="/ajax/?action=CALLBACK" data-goal="callback">
				<div class="b-inputs-2 clearfix">
					<div class="b-input">
						<input type="text" name="name" placeholder="Ваше имя">
					</div>
					<div class="b-input">
						<input type="text" name="phone" placeholder="Номер телефона" required>
					</div>
					<input type="text" name="MAIL">
				</div>
				<div class="b-textarea">
					<textarea rows="3" name="comment" placeholder="Ваш вопрос"></textarea>
				</div>
				<div class="politics">Отправляя форму, я даю согласие на обработку моих персональных данных в соответствии с <a href="#" class="underlined">политикой&nbsp;конфиденциальности</a></div>
				<a href="#" class="b-btn b-btn-callback ajax">Заказать звонок</a>
				<a href="#b-popup-success" class="b-thanks-link fancy" style="display:none;"></a>
				<a href="#b-popup-error" class="b-error-link fancy" style="display:none;"></a>
			</form>
		</div>

	</div>
	<? if($_SERVER["HTTP_HOST"] == "motochki-klubochki.ru" && !$USER->IsAuthorized() ): ?>
		<!-- Yandex.Metrika counter -->
			<script type="text/javascript" >
			   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
			   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
			   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
			   ym(36653305, "init", {
			        clickmap:true,
			        trackLinks:true,
			        accurateTrackBounce:true,
			        webvisor:true
			   });
			</script>
			<noscript><div><img src="https://mc.yandex.ru/watch/36653305" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
		<!-- /Yandex.Metrika counter -->
	<? endif; ?>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery.fancybox.min.js"></script>
	<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false&key=AIzaSyD6Sy5r7sWQAelSn-4mu2JtVptFkEQ03YI"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery.touch.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery.maskedinput.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/KitAnimate.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/plupload.full.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/mask.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/KitProgress.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/KitSend.js?<?=$GLOBALS["version"]?>"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/slick.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/autosize.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery.ui.touch-punch.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/slideout.min.js"></script>
	<!-- Плагины для страницы оформления заказа -->
	<? if($urlArr[1] == "cart" && $urlArr[2] == "order"): ?>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/vue.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/vee-validate.min.js"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery.sticky-kit.min.js"></script>
	<!-- <script type="text/javascript" src="js/cleave.min.js"></script> -->
	<!-- <script type="text/javascript" src="js/axios.min.js"></script> -->
	<!-- <script type="text/javascript" src="js/vue-resource.min.js"></script> -->
	<!-- <script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery.mask.min.js"></script> -->
	<script type="text/javascript" src="https://api-maps.yandex.ru/2.1.41/?load=package.full&amp;apikey=dcf82496-06b7-476e-b6f8-0078e5d46b67&amp;lang=ru-RU"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/address.js?<?=$GLOBALS["version"]?>"></script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/AddressDeliveryClass.js?<?=$GLOBALS["version"]?>"></script>
	<?$dataOrder = getOrderList();?>
	<script type="text/javascript">
		var dataOrder = <?=$dataOrder?>;
	</script>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/app-order.js?<?=$GLOBALS["version"]?>"></script>
	<? endif; ?>
	<!-- \\\\\\\\ -->
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/main.js?<?=$GLOBALS["version"]?>"></script>
</body>
</html>