<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/header.php");

$curPage = $APPLICATION->GetCurPage();
$urlArr = $GLOBALS["urlArr"] = explode("/", $curPage);
$page = $GLOBALS["page"] = ( $urlArr[2] == null || $urlArr[2] == "" )?$urlArr[1]:$urlArr[2];
$GLOBALS["version"] = 45;
$is404 = defined('ERROR_404') && ERROR_404=='Y' && !defined('ADMIN_SECTION');
$arPage = ( isset($arPages[$urlArr[2]]) )?$arPages[$urlArr[2]]:$arPages[$urlArr[1]];

$isMain = $GLOBALS["isMain"] = ( $curPage == "/" ) ? true : false;
$isAbout = $GLOBALS["isAbout"] = ($urlArr[1] == "about");

$GLOBALS['partial'] = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

CModule::IncludeModule('iblock');

$arFav = getFavourites();
$favClass = 'hide';
$favCount = 0;

if ($arFav > 0){
	$favClass = '';
	$favCount = count($arFav);
}

?>
<!DOCTYPE html>
<html>
<head>
	<title><?$APPLICATION->ShowTitle()?></title>
	<?$APPLICATION->ShowHead();?>

	<meta name="keywords" content=''>
	<meta name="description" content=''>

	<meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1">
	<meta name="format-detection" content="telephone=no">

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/reset.css" type="text/css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/jquery.fancybox.css" type="text/css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/KitAnimate.css" type="text/css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/slick.css" type="text/css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/chosen.min.css" type="text/css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/jquery-ui.min.css" type="text/css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/layout.css" type="text/css">

	<link rel="stylesheet" media="screen and (min-width: 768px) and (max-width: 1188px)" href="<?=SITE_TEMPLATE_PATH?>/css/layout-tablet.css">
	<link rel="stylesheet" media="screen and (min-width: 240px) and (max-width: 767px)" href="<?=SITE_TEMPLATE_PATH?>/css/layout-mobile.css">

	<!-- <link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico"> -->
</head>
<body>
	<?$APPLICATION->ShowPanel();?>
	<div class="b-left-menu">
		<div class="mobile-menu-bg"></div>
		<div class="mobile-menu">
			<div class="mobile-menu-wrap">
				<div class="mobile-menu-close-btn">Закрыть</div>
				<?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "mobile_categories", Array(
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
				<?$APPLICATION->IncludeComponent("bitrix:menu", "mobile_menu", array(
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
		</div>
	</div>
	<div class="b-page">
	<div class="b b-header">
		<div class="b-top">
			<div class="b-block">
				<p class="b-city">Интернет-магазин пряжи</p>
					<?$APPLICATION->IncludeComponent("bitrix:menu", "top_menu", array(
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
				<a href="tel:+79039538088" class="b-phone">+7 (903) 953-80-88</a>
			</div>
		</div>
		<div class="b-block">
			<div class="b-bottom">
				<a href="/" class="b-logo"></a>
				<div class="b-header-catalog-block">
					<a href="#" class="b-header-catalogue icon-list">Каталог</a>
					<?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "header_catalog", Array(
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
				</div>
				<div class="b-search-form">
					<?$APPLICATION->IncludeComponent("bitrix:search.title", "header", Array(
						"CATEGORY_0" => array(	// Ограничение области поиска
								0 => "iblock_content",
							),
							"CATEGORY_0_TITLE" => "",	// Название категории
							"CATEGORY_0_forum" => array(
								0 => "all",
							),
							"CATEGORY_0_iblock_content" => array(	// Искать в информационных блоках типа "iblock_content"
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
				<div class="b-control">
					<? if (isAuth()): ?>
						<a href="/personal/" class="b-profile icon-login"></a>	
					<? else: ?>
						<a href="#popup-sign" class="b-profile icon-login fancy"></a>
					<? endif; ?>
					<a href="/personal/?tab=favourite" class="b-fav icon-star">
						<div class="b-fav-round <?=$favClass?>">
							<span class="b-fav-number"><?=$favCount?></span>
						</div>
					</a>
				</div>
			</div>
		</div>
		<div class="b-main-menu">
			<div class="b-block clearfix">
				<div class="mobile-btn">
					<div class="mobile-btn-burger">
						<div class="mobile-btn-burger">
							<div class="burger-el"></div>
							<div class="burger-el"></div>
							<div class="burger-el"></div>
						</div>
					</div>
					<span>Меню</span>
				</div>
				<?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "header_categories", Array(
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
				
				<? $basketInfo = getBasketCount(); ?>
				<? if ($basketInfo['sum'] >= 1000): ?>
					<? $basketInfo['sum'] = number_format( $basketInfo['sum'], 0, ',', ' ' ); ?>
				<? endif; ?>

				<a href='/cart/order/' class="b-price-button">
					<span class="b-cart-price icon-ruble"><?=$basketInfo['sum']?></span>
					<span class="b-cart-number-container">
						<span class="b-cart-number"><?=$basketInfo['count']?></span>
					</span>
				</a>
			</div>
		</div>
	</div>
	<? if (!$isMain): ?>
	<div class="b-content-inner">
		<div class="b-block">
			<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "main", Array(
				"COMPONENT_TEMPLATE" => ".defaults",
				"START_FROM" => "0",
				"PATH" => "",
				"SITE_ID" => "s1",
			),false );?>	
	<? endif; ?>