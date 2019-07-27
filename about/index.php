<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("О магазине");
?>

	<h1 class="b-title b-title-left"><?$APPLICATION->ShowTitle()?></h1>
	</div>
	<div class="b b-about-face">
		<div class="b-block">
			<div class="about-face-title">Интернет-магазин пряжи</div>
			<img src="<?=SITE_TEMPLATE_PATH?>/i/about-face-img.svg" class="about-face-img">
			<div class="about-face-subtitle">Создан рукодельницами для рукодельниц</div>
		</div>
	</div>
	<div class="b b-about-info">
		<div class="b-block">
			<div class="about-info-text">
				<h3>Здравствуйте, дорогие друзья!</h3>
				<p>Приветствуем вас в интернет-магазине «Моточки Клубочки». Магазине, который был открыт рукодельницами для рукодельниц.</p>
				<p>Мы очень любим вязать и обязательно проверяем каждый вид пряжи, который продаем. В нашем магазине продается пряжа для вязания от лучших производителей: итальянская, немецкая, перуанская, турецкая. А также аксессуары для вязания и другие товары для hand-made творчества отличного качества.</p>
				<p>Мы всегда рады новым единомышленникам – присоединяйтесь к нам в <a href="#" target="_blank">Инстаграм</a> и вступайте в нашу группу <a href="#">ВКонтакте</a>.</p>
				<p>Всегда рады вашему участию – оставляйте отзывы и пожелания о товаре и нашей работе, на <a href="#">странице в комментариях</a> или по адресу mkv70@yandex.ru</p>
			</div>
			<div class="about-info-wrap">
				<div class="about-info-wrap-title">Юридическая информация</div>
				<div class="about-info-wrap-text">
					<p><b>ИП Шпичко Елена Николаевна</b></p>
					<p><b>Номер счета:</b> 40802810264000006683</p>
					<p>ПАО Сбербанк Томское отделение №8616</p>
					<p><b>Кор.счет:</b> 30101810800000000606</p>
					<p><b>ИНН:</b> 701900718370</p>
					<p><b>БИК:</b> 046902606</p>
					<p><b>Адрес:</b> г. Томск, пер. Дербышевского, 26Б, 3 этаж, офис 304</p>
				</div>
			</div>
		</div>
	</div>
	<div class="b b-about-articles-block">
		<div class="b-block">
			<h2 class="about-articles-block-title">Свежее в блоге</h2>
			<?$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"index-blog",
				Array(
					"ACTIVE_DATE_FORMAT" => "j F, H:i",
					"ADD_SECTIONS_CHAIN" => "Y",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_ADDITIONAL" => "",
					"AJAX_OPTION_HISTORY" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"CACHE_FILTER" => "N",
					"CACHE_GROUPS" => "Y",
					"CACHE_TIME" => "36000000",
					"CACHE_TYPE" => "A",
					"CHECK_DATES" => "Y",
					"COMPONENT_TEMPLATE" => "blog",
					"DETAIL_URL" => "",
					"DISPLAY_BOTTOM_PAGER" => "Y",
					"DISPLAY_DATE" => "Y",
					"DISPLAY_NAME" => "Y",
					"DISPLAY_PICTURE" => "Y",
					"DISPLAY_PREVIEW_TEXT" => "Y",
					"DISPLAY_TOP_PAGER" => "N",
					"FIELD_CODE" => array(0=>"ID",1=>"ACTIVE_TO",2=>"CREATED_DATE",),
					"FILTER_NAME" => "",
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"IBLOCK_ID" => "4",
					"IBLOCK_TYPE" => "content",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"INCLUDE_SUBSECTIONS" => "Y",
					"MESSAGE_404" => "",
					"NEWS_COUNT" => "2",
					"PAGER_BASE_LINK_ENABLE" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_TEMPLATE" => ".default",
					"PAGER_TITLE" => "Новости",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"PREVIEW_TRUNCATE_LEN" => "",
					"PROPERTY_CODE" => array(0=>"",1=>"ITEM_LIST",2=>"",),
					"SET_BROWSER_TITLE" => "N",
					"SET_LAST_MODIFIED" => "N",
					"SET_META_DESCRIPTION" => "Y",
					"SET_META_KEYWORDS" => "Y",
					"SET_STATUS_404" => "N",
					"SET_TITLE" => "N",
					"SHOW_404" => "N",
					"SORT_BY1" => "SORT",
					"SORT_BY2" => "SORT",
					"SORT_ORDER1" => "ASC",
					"SORT_ORDER2" => "ASC",
					"STRICT_SECTION_CHECK" => "N",
					"COUNT_COMMENT" => "Y",
				)
			);?>
			<a href="/blog/" class="b-btn">Читать блог</a>
		</div>
	</div>
	<div class="b b-about-qwestions">
		<div class="b-block">
			<div class="about-qwestions-vk">
				<script type="text/javascript" src="https://vk.com/js/api/openapi.js?161"></script>

				<!-- VK Widget -->
				<div id="vk_groups"></div>
				<script type="text/javascript">
					function VK_Widget_Init(){
			            document.getElementById('vk_groups').innerHTML = "";
			            VK.Widgets.Group("vk_groups", {mode: 4, wide: 1, color3: '77BE32', width: 'auto', height: '390'}, 118479740);
			        };
			        window.addEventListener('load', VK_Widget_Init, false);
			        window.addEventListener('resize', VK_Widget_Init, false);
				
				</script>
			</div>
			<div class="about-qwestions-form">
				<h2 class="about-qwestions-title">Есть вопросы? Задайте!</h2>
				<form action="/ajax/?action=ASK" method="POST">
					<div class="b-inputs-2 clearfix">
						<div class="b-input">
							<input type="text" name="name" placeholder="Ваше имя">
						</div>
						<div class="b-input">
							<input type="text" name="phone" placeholder="Номер телефона" required>
						</div>
					</div>
					<div class="b-textarea">
						<textarea rows="5" name="comment" placeholder="Напишите нам"></textarea>
					</div>
					<input type="text" name="MAIL">
					<a href="#" class="b-btn ajax">Отправить сообщение</a>
					<div class="politics">Отправляя форму, я даю согласие на обработку моих персональных данных в соответствии с <a href="#" class="underlined">политикой конфиденциальности</a></div>
					<a href="#b-popup-success" class="b-thanks-link fancy" style="display:none;"></a>
					<a href="#b-popup-error" class="b-error-link fancy" style="display:none;"></a>
				</form>
			</div>
		</div>
	</div>
	<div class="b b-im-watch">
		<div class="b-block">
			<h2 class="b-title">Следите за нами в Instagram</h2>
		</div>
		<div class="b-im-block about-im-block">
			<a href="https://www.instagram.com/motochkiklubochki" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/i/im-post-1.jpg"></a>
			<a href="https://www.instagram.com/motochkiklubochki" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/i/im-post-2.jpg"></a>
			<a href="https://www.instagram.com/motochkiklubochki" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/i/im-post-3.jpg"></a>
			<a href="https://www.instagram.com/motochkiklubochki" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/i/im-post-4.jpg"></a>
			<a href="https://www.instagram.com/motochkiklubochki" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/i/im-post-5.jpg"></a>
			<a href="https://www.instagram.com/motochkiklubochki" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/i/im-post-1.jpg"></a>
			<a href="https://www.instagram.com/motochkiklubochki" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/i/im-post-2.jpg"></a>
			<a href="https://www.instagram.com/motochkiklubochki" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/i/im-post-3.jpg"></a>
		</div>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>