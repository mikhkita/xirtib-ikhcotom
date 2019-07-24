<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Моточки - клубочки");
?>

	<div class="b b-main-slider">
		<?$APPLICATION->IncludeComponent(
			"bitrix:news.list",
			"main_slider",
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
				"COMPONENT_TEMPLATE" => "main_slider",
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
				"IBLOCK_ID" => "7",
				"IBLOCK_TYPE" => "content",
				"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
				"INCLUDE_SUBSECTIONS" => "Y",
				"MESSAGE_404" => "",
				"NEWS_COUNT" => "20",
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
	</div>
	<div class="b b-main-advantages">
		<div class="b-block">
			<div class="b-advantage clearfix">
				<div class="b-advantage-img-wrapper">
					<img src="<?=SITE_TEMPLATE_PATH?>/i/24-hours.svg">
				</div>
				<div class="b-advantage-description">
					<h3>Обработка заказа - <br>1 день</h3>
					<p>Отправим со склада на следующий&nbsp;день после оформления&nbsp;заказа.</p>
				</div>
			</div>
			<div class="b-advantage clearfix">
				<div class="b-advantage-img-wrapper">
					<img src="<?=SITE_TEMPLATE_PATH?>/i/knitting.svg">
				</div>
				<div class="b-advantage-description">
					<h3>Легко определить <br>расход пряжи</h3>
					<p>Мы вяжем образцы, чтобы вы&nbsp;смогли&nbsp;определиться с расходом&nbsp;пряжи на изделие.</p>
				</div>
			</div>
			<div class="b-advantage clearfix">
				<div class="b-advantage-img-wrapper">
					<img src="<?=SITE_TEMPLATE_PATH?>/i/percent.svg">
				</div>
				<div class="b-advantage-description">
					<h3>Скидки постоянным <br>покупателям</h3>
					<p>Регистрируйтесь на сайте и получайте скидки при покупках от … руб.</p>
				</div>
			</div>
		</div>
	</div>
	<div class="b b-stock">
		<div class="b-block">
			<h2 class="b-title">Скидки</h2>
			<?

			$arDiscounts = getDiscountProducts();
			$discountProducts = array();

			foreach ($arDiscounts['PRODUCTS'] as $discount) {
				$mxResult = CCatalogSku::GetProductInfo($discount);
				if (is_array($mxResult)){
					array_push($discountProducts, $mxResult['ID']);
				}
			}

			$discountProducts = array_unique($discountProducts);


			$GLOBALS["arrFilter2"][] = Array(
				"LOGIC"=>"OR",
				Array("ID" =>$discountProducts),
			);
			
			$APPLICATION->IncludeComponent(
				"bitrix:catalog.section",
				"slider",
				Array(
					"ACTION_VARIABLE" => "action",
					"ADD_PICT_PROP" => "MORE_PHOTO",
					"ADD_PROPERTIES_TO_BASKET" => "Y",
					"ADD_SECTIONS_CHAIN" => "Y",
					"ADD_TO_BASKET_ACTION" => "ADD",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_ADDITIONAL" => "",
					"AJAX_OPTION_HISTORY" => "Y",
					"AJAX_OPTION_JUMP" => "Y",
					"AJAX_OPTION_STYLE" => "N",
					"BACKGROUND_IMAGE" => "-",
					"BASKET_URL" => "/personal/cart/",
					"BROWSER_TITLE" => "-",
					"CACHE_FILTER" => "N",
					"CACHE_GROUPS" => "Y",
					"CACHE_TIME" => "36000000",
					"CACHE_TYPE" => "N",
					"COMPONENT_TEMPLATE" => ".default",
					"CONVERT_CURRENCY" => "N",
					"DETAIL_URL" => "",
					"DISABLE_INIT_JS_IN_COMPONENT" => "N",
					"DISPLAY_BOTTOM_PAGER" => "N",
					"DISPLAY_TOP_PAGER" => "N",
					"ELEMENT_SORT_FIELD" => $_REQUEST['SORT'],
					"ELEMENT_SORT_FIELD2" => "id",
					"ELEMENT_SORT_ORDER" => $_REQUEST['SORT_TYPE'],
					"ELEMENT_SORT_ORDER2" => "DESC",
					"FILTER_NAME" => "arrFilter2",
					"HIDE_NOT_AVAILABLE" => "N",
					"IBLOCK_ID" => "1",
					"IBLOCK_TYPE" => "catalog",
					"IBLOCK_TYPE_ID" => "catalog",
					"INCLUDE_SUBSECTIONS" => "A",
					"LABEL_PROP" => "SALELEADER",
					"LINE_ELEMENT_COUNT" => "1",
					"MESSAGE_404" => "",
					"MESS_BTN_ADD_TO_BASKET" => "В корзину",
					"MESS_BTN_BUY" => "Купить",
					"MESS_BTN_DETAIL" => "Подробнее",
					"MESS_BTN_SUBSCRIBE" => "Подписаться",
					"MESS_NOT_AVAILABLE" => "Заказ по телефону",
					"META_DESCRIPTION" => "-",
					"META_KEYWORDS" => "-",
					"OFFERS_CART_PROPERTIES" => array(0=>"COLOR_REF",1=>"SIZES_CLOTHES",),
					"OFFERS_FIELD_CODE" => array(0=>"",1=>"",),
					"OFFERS_LIMIT" => "5",
					"OFFERS_PROPERTY_CODE" => array(0=>"COLOR_REF",1=>"SIZES_CLOTHES",2=>"SIZES_SHOES",3=>"",),
					"OFFERS_SORT_FIELD" => $_REQUEST['SORT'],
					"OFFERS_SORT_FIELD2" => "id",
					"OFFERS_SORT_ORDER" => $_REQUEST['SORT_TYPE'],
					"OFFERS_SORT_ORDER2" => "desc",
					"OFFER_ADD_PICT_PROP" => "-",
					"OFFER_TREE_PROPS" => array(0=>"COLOR_REF",1=>"SIZES_SHOES",2=>"SIZES_CLOTHES",),
					"PAGER_BASE_LINK_ENABLE" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "Y",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_TEMPLATE" => "main",
					"PAGER_TITLE" => "Товары",
					"PAGE_ELEMENT_COUNT" => 12,
					"PARTIAL_PRODUCT_PROPERTIES" => "N",
					"PRICE_CODE" => array(0=>"PRICE",),
					"PRICE_VAT_INCLUDE" => "N",
					"PRODUCT_DISPLAY_MODE" => "N",
					"PRODUCT_ID_VARIABLE" => "id",
					"PRODUCT_PROPERTIES" => array(),
					"PRODUCT_PROPS_VARIABLE" => "prop",
					"PRODUCT_QUANTITY_VARIABLE" => "",
					"PRODUCT_SUBSCRIPTION" => "N",
					"PROPERTY_CODE" => array(0=>"",1=>"",),
					"SECTION_CODE" => $_REQUEST["SECTION_CODE"],
					"SECTION_CODE_PATH" => "",
					"SECTION_ID" => "",
					"SECTION_ID_VARIABLE" => "SECTION_ID",
					"SECTION_URL" => "",
					"SECTION_USER_FIELDS" => array(0=>"",1=>"",),
					"SEF_MODE" => "N",
					"SET_BROWSER_TITLE" => "Y",
					"SET_LAST_MODIFIED" => "N",
					"SET_META_DESCRIPTION" => "Y",
					"SET_META_KEYWORDS" => "Y",
					"SET_STATUS_404" => "Y",
					"SET_TITLE" => "Y",
					"SHOW_404" => "Y",
					"SHOW_ALL_WO_SECTION" => "Y",
					"SHOW_CLOSE_POPUP" => "N",
					"SHOW_DISCOUNT_PERCENT" => "N",
					"SHOW_OLD_PRICE" => "N",
					"SHOW_PRICE_COUNT" => "1",
					"TEMPLATE_THEME" => "site",
					"USE_MAIN_ELEMENT_SECTION" => "N",
					"USE_PRICE_COUNT" => "N",
					"USE_PRODUCT_QUANTITY" => "N",
				),
			false,
			Array(
				'ACTIVE_COMPONENT' => 'Y'
			)
			);?>
			<a href="/catalog/" class="b-btn">Смотреть все</a>
		</div>
	</div>
	<div class="b b-stock">
		<div class="b-block">
			<h2 class="b-title">Новинки</h2>
			<?
			$APPLICATION->IncludeComponent(
				"bitrix:catalog.section",
				"slider",
				Array(
					"ACTION_VARIABLE" => "action",
					"ADD_PICT_PROP" => "MORE_PHOTO",
					"ADD_PROPERTIES_TO_BASKET" => "Y",
					"ADD_SECTIONS_CHAIN" => "Y",
					"ADD_TO_BASKET_ACTION" => "ADD",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_ADDITIONAL" => "",
					"AJAX_OPTION_HISTORY" => "Y",
					"AJAX_OPTION_JUMP" => "Y",
					"AJAX_OPTION_STYLE" => "N",
					"BACKGROUND_IMAGE" => "-",
					"BASKET_URL" => "/personal/cart/",
					"BROWSER_TITLE" => "-",
					"CACHE_FILTER" => "N",
					"CACHE_GROUPS" => "Y",
					"CACHE_TIME" => "36000000",
					"CACHE_TYPE" => "N",
					"COMPONENT_TEMPLATE" => ".default",
					"CONVERT_CURRENCY" => "N",
					"DETAIL_URL" => "",
					"DISABLE_INIT_JS_IN_COMPONENT" => "N",
					"DISPLAY_BOTTOM_PAGER" => "N",
					"DISPLAY_TOP_PAGER" => "N",
					"ELEMENT_SORT_FIELD" => "created_date",
					"ELEMENT_SORT_FIELD2" => "id",
					"ELEMENT_SORT_ORDER" => $_REQUEST['SORT_TYPE'],
					"ELEMENT_SORT_ORDER2" => "DESC",
					"FILTER_NAME" => "",
					"HIDE_NOT_AVAILABLE" => "N",
					"IBLOCK_ID" => "1",
					"IBLOCK_TYPE" => "catalog",
					"IBLOCK_TYPE_ID" => "catalog",
					"INCLUDE_SUBSECTIONS" => "A",
					"LABEL_PROP" => "SALELEADER",
					"LINE_ELEMENT_COUNT" => "1",
					"MESSAGE_404" => "",
					"MESS_BTN_ADD_TO_BASKET" => "В корзину",
					"MESS_BTN_BUY" => "Купить",
					"MESS_BTN_DETAIL" => "Подробнее",
					"MESS_BTN_SUBSCRIBE" => "Подписаться",
					"MESS_NOT_AVAILABLE" => "Заказ по телефону",
					"META_DESCRIPTION" => "-",
					"META_KEYWORDS" => "-",
					"OFFERS_CART_PROPERTIES" => array(0=>"COLOR_REF",1=>"SIZES_CLOTHES",),
					"OFFERS_FIELD_CODE" => array(0=>"",1=>"",),
					"OFFERS_LIMIT" => "5",
					"OFFERS_PROPERTY_CODE" => array(0=>"COLOR_REF",1=>"SIZES_CLOTHES",2=>"SIZES_SHOES",3=>"",),
					"OFFERS_SORT_FIELD" => $_REQUEST['SORT'],
					"OFFERS_SORT_FIELD2" => "id",
					"OFFERS_SORT_ORDER" => $_REQUEST['SORT_TYPE'],
					"OFFERS_SORT_ORDER2" => "desc",
					"OFFER_ADD_PICT_PROP" => "-",
					"OFFER_TREE_PROPS" => array(0=>"COLOR_REF",1=>"SIZES_SHOES",2=>"SIZES_CLOTHES",),
					"PAGER_BASE_LINK_ENABLE" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "Y",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_TEMPLATE" => "main",
					"PAGER_TITLE" => "Товары",
					"PAGE_ELEMENT_COUNT" => 12,
					"PARTIAL_PRODUCT_PROPERTIES" => "N",
					"PRICE_CODE" => array(0=>"PRICE",),
					"PRICE_VAT_INCLUDE" => "N",
					"PRODUCT_DISPLAY_MODE" => "N",
					"PRODUCT_ID_VARIABLE" => "id",
					"PRODUCT_PROPERTIES" => array(),
					"PRODUCT_PROPS_VARIABLE" => "prop",
					"PRODUCT_QUANTITY_VARIABLE" => "",
					"PRODUCT_SUBSCRIPTION" => "N",
					"PROPERTY_CODE" => array(0=>"",1=>"",),
					"SECTION_CODE" => $_REQUEST["SECTION_CODE"],
					"SECTION_CODE_PATH" => "",
					"SECTION_ID" => "",
					"SECTION_ID_VARIABLE" => "SECTION_ID",
					"SECTION_URL" => "",
					"SECTION_USER_FIELDS" => array(0=>"",1=>"",),
					"SEF_MODE" => "N",
					"SET_BROWSER_TITLE" => "Y",
					"SET_LAST_MODIFIED" => "N",
					"SET_META_DESCRIPTION" => "Y",
					"SET_META_KEYWORDS" => "Y",
					"SET_STATUS_404" => "Y",
					"SET_TITLE" => "Y",
					"SHOW_404" => "Y",
					"SHOW_ALL_WO_SECTION" => "Y",
					"SHOW_CLOSE_POPUP" => "N",
					"SHOW_DISCOUNT_PERCENT" => "N",
					"SHOW_OLD_PRICE" => "N",
					"SHOW_PRICE_COUNT" => "1",
					"TEMPLATE_THEME" => "site",
					"USE_MAIN_ELEMENT_SECTION" => "N",
					"USE_PRICE_COUNT" => "N",
					"USE_PRODUCT_QUANTITY" => "N",
				),
			false,
			Array(
				'ACTIVE_COMPONENT' => 'Y'
			)
			);
			?>
			<a href="/catalog/" class="b-btn">Смотреть все</a>
		</div>
	</div>
	<div class="b b-choose-type">
		<div class="b-block">
			<?$APPLICATION->IncludeComponent(
				"bitrix:catalog.smart.filter",
				"index",
				Array(
					"CACHE_GROUPS" => "Y",
					"CACHE_TIME" => "36000000",
					"CACHE_TYPE" => "A",
					"COMPONENT_TEMPLATE" => "main",
					"CONVERT_CURRENCY" => "Y",
					"CURRENCY_ID" => "RUB",
					"DISPLAY_ELEMENT_COUNT" => "Y",
					"FILTER_NAME" => "arrFilter",
					"FILTER_VIEW_MODE" => "horizontal",
					"HIDE_NOT_AVAILABLE" => "N",
					"IBLOCK_ID" => "1",
					"CUSTOM_SECTION_CODE" => 'pryazha',
					"IBLOCK_TYPE" => "content",
					"INSTANT_RELOAD" => "Y",
					"PAGER_PARAMS_NAME" => "arrPager",
					"POPUP_POSITION" => "left",
					"PREFILTER_NAME" => "smartPreFilter",
					"PRICE_CODE" => array(0=>"PRICE",),
					"SAVE_IN_SESSION" => "N",
					"SECTION_CODE" => "",
					"SECTION_CODE_PATH" => "",
					"SECTION_DESCRIPTION" => "-",
					"SECTION_ID" => "1",
					"SECTION_TITLE" => "-",
					"SEF_MODE" => "N",
					"SEF_RULE" => "/catalog/#SECTION_CODE#/filter/#SMART_FILTER_PATH#/apply/",
					"SMART_FILTER_PATH" => $_REQUEST["SMART_FILTER_PATH"],
					"TEMPLATE_THEME" => "site",
					"XML_EXPORT" => "N"
				)
			);?>
		</div>
	</div>
	<div class="b b-im-watch">
		<h2 class="b-title">Следите за нами в Instagram</h2>
		<div class="b-im-block">
			<a href="https://www.instagram.com/motochkiklubochki/" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/i/im-post-1.jpg"></a>
			<a href="https://www.instagram.com/motochkiklubochki/" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/i/im-post-2.jpg"></a>
			<a href="https://www.instagram.com/motochkiklubochki/" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/i/im-post-3.jpg"></a>
			<a href="https://www.instagram.com/motochkiklubochki/" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/i/im-post-4.jpg"></a>
			<a href="https://www.instagram.com/motochkiklubochki/" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/i/im-post-5.jpg"></a>
			<a href="https://www.instagram.com/motochkiklubochki/" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/i/im-post-1.jpg"></a>
			<a href="https://www.instagram.com/motochkiklubochki/" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/i/im-post-2.jpg"></a>
			<a href="https://www.instagram.com/motochkiklubochki/" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/i/im-post-3.jpg"></a>
		</div>
		<div class="b-block">
			<a href="https://www.instagram.com/motochkiklubochki/" class="b-btn" target="_blank">Подписаться</a>
		</div>
	</div>
	<div class="b b-main-articles-block">
		<div class="b-block">
			<h2 class="b-title">Читайте наши статьи</h2>
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
			<div class="b-block">
				<a href="/blog/" class="b-btn">Читать статьи</a>
			</div>
		</div>
	</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>