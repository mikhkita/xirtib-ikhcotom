<?
define("SEARCH", "Y");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Поиск");

global $f;
if(!empty(trim($_REQUEST["q"]))){
	global $f;
	$f = array(
		array(
			"LOGIC" => "OR",
			array("NAME" => "%".$_REQUEST["q"]."%"),
			array("TAGS" => "%".$_REQUEST["q"]."%"),
			array("PREVIEW_TEXT" => "%".$_REQUEST["q"]."%")
		),
	);
	$APPLICATION->SetTitle("Поиск по запросу '".trim($_REQUEST["q"])."'");
}
?>

<div class="b-search-form b-search-inner-form">
	<?$APPLICATION->IncludeComponent("bitrix:search.title", "header", Array(
		"CATEGORY_0" => array(
				0 => "iblock_content",
			),
			"CATEGORY_0_TITLE" => "",
			"CATEGORY_0_forum" => array(
				0 => "all",
			),
			"CATEGORY_0_iblock_content" => array(
				0 => "1",
			),
			"CATEGORY_0_main" => array(
				0 => "",
			),
			"CHECK_DATES" => "N",	// Искать только в активных по дате документах
			"CONTAINER_ID" => "title-search",	// ID контейнера, по ширине которого будут выводиться результаты
			"CONVERT_CURRENCY" => "N",	// Показывать цены в одной валюте
			"INPUT_ID" => "title-search-input",	// ID строки ввода поискового запроса
			"NUM_CATEGORIES" => "1",	// Количество категорий поиска
			"ORDER" => "rank",	// Сортировка результатов
			"PAGE" => "#SITE_DIR#search/",	// Страница выдачи результатов поиска (доступен макрос #SITE_DIR#)
			"PREVIEW_HEIGHT" => "75",	// Высота картинки
			"PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода
			"PREVIEW_WIDTH" => "75",	// Ширина картинки
			"PRICE_CODE" => "",	// Тип цены
			"PRICE_VAT_INCLUDE" => "Y",	// Включать НДС в цену
			"SHOW_INPUT" => "Y",	// Показывать форму ввода поискового запроса
			"SHOW_OTHERS" => "N",	// Показывать категорию "прочее"
			"SHOW_PREVIEW" => "Y",	// Показать картинку
			"TEMPLATE_THEME" => "site",
			"TOP_COUNT" => "8",	// Количество результатов в каждой категории
			"USE_LANGUAGE_GUESS" => "Y",	// Включить автоопределение раскладки клавиатуры
		),
		false
	);?>
</div>
<div class="no-filter"></div>
<div class="b-catalog-list b-catalog-list-full after-load clearfix" id="b-catalog">
	<div class="b-catalog-list-cont">
				<?
				$APPLICATION->IncludeComponent(
					"bitrix:catalog.section",
					"main",
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
						"CACHE_TYPE" => "A",
						"COMPONENT_TEMPLATE" => ".default",
						"CONVERT_CURRENCY" => "N",
						"DETAIL_URL" => "",
						"DISABLE_INIT_JS_IN_COMPONENT" => "N",
						"DISPLAY_BOTTOM_PAGER" => "Y",
						"DISPLAY_TOP_PAGER" => "N",
						"ELEMENT_SORT_FIELD" => $_REQUEST['SORT_FIELD'],
						"ELEMENT_SORT_FIELD2" => "id",
						"ELEMENT_SORT_ORDER" => $_REQUEST['SORT_TYPE'],
						"ELEMENT_SORT_ORDER2" => "DESC",
						"FILTER_NAME" => "f",
						"HIDE_NOT_AVAILABLE" => "Y",
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
						"OFFERS_LIMIT" => "",
						"OFFERS_PROPERTY_CODE" => array(0=>"COLOR_REF",1=>"SIZES_CLOTHES",2=>"SIZES_SHOES",3=>"",),
						"OFFERS_SORT_FIELD" => 'sort',
						"OFFERS_SORT_FIELD2" => "id",
						"OFFERS_SORT_ORDER" => 'asc',
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
						"PAGE_ELEMENT_COUNT" => 16,
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
						"USE_PRODUCT_QUANTITY" => "Y",
						"WITH_REVIEWS" => ($isFirst)?"Y":"N",
						"WITH_CALLBACK" => ($isLast)?"Y":"N",
						"CLASS" => "b-limit",
					),
				false,
				Array(
					'ACTIVE_COMPONENT' => 'Y'
				)
				);?>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>