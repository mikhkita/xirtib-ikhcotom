<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/header.php");

$curPage = $APPLICATION->GetCurPage();
$urlArr = $GLOBALS["urlArr"] = explode("/", $curPage);
$page = $GLOBALS["page"] = ( $urlArr[2] == null || $urlArr[2] == "" )?$urlArr[1]:$urlArr[2];
$GLOBALS["version"] = 60;
$is404 = defined('ERROR_404') && ERROR_404=='Y' && !defined('ADMIN_SECTION');
$arPage = ( isset($arPages[$urlArr[2]]) )?$arPages[$urlArr[2]]:$arPages[$urlArr[1]];

$isMain = $GLOBALS["isMain"] = ( $curPage == "/" ) ? true : false;
$isAbout = $GLOBALS["isAbout"] = ($urlArr[1] == "about");
$isDetail = $GLOBALS["isDetail"] = ($urlArr[1] == "catalog" && isset($urlArr[4]));

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

	<meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1">
	<meta name="format-detection" content="telephone=no">

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/reset.css" type="text/css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/jquery.fancybox.css" type="text/css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/KitAnimate.css" type="text/css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/slick.css" type="text/css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/chosen.min.css" type="text/css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/jquery-ui.min.css" type="text/css">
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/layout.css?<?=$GLOBALS["version"]?>" type="text/css">

	<link rel="apple-touch-icon-precomposed" sizes="57x57" href="/favicon/apple-touch-icon-57x57.png" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/favicon/apple-touch-icon-114x114.png" />
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/favicon/apple-touch-icon-72x72.png" />
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/favicon/apple-touch-icon-144x144.png" />
	<link rel="apple-touch-icon-precomposed" sizes="60x60" href="/favicon/apple-touch-icon-60x60.png" />
	<link rel="apple-touch-icon-precomposed" sizes="120x120" href="/favicon/apple-touch-icon-120x120.png" />
	<link rel="apple-touch-icon-precomposed" sizes="76x76" href="/favicon/apple-touch-icon-76x76.png" />
	<link rel="apple-touch-icon-precomposed" sizes="152x152" href="/favicon/apple-touch-icon-152x152.png" />
	<link rel="icon" type="image/png" href="/favicon/favicon-196x196.png" sizes="196x196" />
	<link rel="icon" type="image/png" href="/favicon/favicon-96x96.png" sizes="96x96" />
	<link rel="icon" type="image/png" href="/favicon/favicon-32x32.png" sizes="32x32" />
	<link rel="icon" type="image/png" href="/favicon/favicon-16x16.png" sizes="16x16" />
	<link rel="icon" type="image/png" href="/favicon/favicon-128.png" sizes="128x128" />
	<meta name="application-name" content="Моточки Клубочки"/>
	<meta name="msapplication-TileColor" content="#" />
	<meta name="msapplication-TileImage" content="/favicon/mstile-144x144.png" />
	<meta name="msapplication-square70x70logo" content="/favicon/mstile-70x70.png" />
	<meta name="msapplication-square150x150logo" content="/favicon/mstile-150x150.png" />
	<meta name="msapplication-wide310x150logo" content="/favicon/mstile-310x150.png" />
	<meta name="msapplication-square310x310logo" content="/favicon/mstile-310x310.png" />

	<link rel="stylesheet" media="screen and (min-width: 768px) and (max-width: 1188px)" href="<?=SITE_TEMPLATE_PATH?>/css/layout-tablet.css?<?=$GLOBALS["version"]?>">
	<link rel="stylesheet" media="screen and (min-width: 240px) and (max-width: 767px)" href="<?=SITE_TEMPLATE_PATH?>/css/layout-mobile.css?<?=$GLOBALS["version"]?>">

	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/jquery-3.2.1.min.js"></script>
	<link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico">
	<script>
		!function(e){var n;if("function"==typeof define&&define.amd&&(define(e),n=!0),"object"==typeof exports&&(module.exports=e(),n=!0),!n){var t=window.Cookies,o=window.Cookies=e();o.noConflict=function(){return window.Cookies=t,o}}}(function(){function f(){for(var e=0,n={};e<arguments.length;e++){var t=arguments[e];for(var o in t)n[o]=t[o]}return n}function a(e){return e.replace(/(%[0-9A-Z]{2})+/g,decodeURIComponent)}return function e(u){function c(){}function t(e,n,t){if("undefined"!=typeof document){"number"==typeof(t=f({path:"/"},c.defaults,t)).expires&&(t.expires=new Date(1*new Date+864e5*t.expires)),t.expires=t.expires?t.expires.toUTCString():"";try{var o=JSON.stringify(n);/^[\{\[]/.test(o)&&(n=o)}catch(e){}n=u.write?u.write(n,e):encodeURIComponent(String(n)).replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g,decodeURIComponent),e=encodeURIComponent(String(e)).replace(/%(23|24|26|2B|5E|60|7C)/g,decodeURIComponent).replace(/[\(\)]/g,escape);var r="";for(var i in t)t[i]&&(r+="; "+i,!0!==t[i]&&(r+="="+t[i].split(";")[0]));return document.cookie=e+"="+n+r}}function n(e,n){if("undefined"!=typeof document){for(var t={},o=document.cookie?document.cookie.split("; "):[],r=0;r<o.length;r++){var i=o[r].split("="),c=i.slice(1).join("=");n||'"'!==c.charAt(0)||(c=c.slice(1,-1));try{var f=a(i[0]);if(c=(u.read||u)(c,f)||a(c),n)try{c=JSON.parse(c)}catch(e){}if(t[f]=c,e===f)break}catch(e){}}return e?t[e]:t}}return c.set=t,c.get=function(e){return n(e,!1)},c.getJSON=function(e){return n(e,!0)},c.remove=function(e,n){t(e,"",f(n,{expires:-1}))},c.defaults={},c.withConverter=e,c}(function(){})});
	</script>
</head>
<body>
	<?$APPLICATION->ShowPanel();?>
	<div id="mobile-menu" class="mobile-menu b-left-menu hide">
		<h2 class="b-bottom-border">Меню</h2>
		<div class="mobile-menu-login">
			<?$arUser = getUserFields();?>
			<? if ($arUser): ?>
				<p class="mobile-menu-user"><?=$arUser["NAME"]?></p>
				<a href="/personal/">Перейти в кабинет</a><br>
				<a href="?logout=yes">Выйти</a>
			<? else: ?>
				<a href="/personal/">Войти</a><br>
				<a href="/personal/">Регистрация</a>
			<? endif; ?>
		</div>
		<div class="mobile-menu-wrap">
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
	<div id="panel-page">
		<div class="b-page">
		<div class="b b-header">
			<div class="b-top">
				<div class="b-block">
					<p class="b-city"><?includeArea("b-header-text-1")?></p>
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
					<?includeArea("b-header-text-2")?>
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
						<?$APPLICATION->IncludeComponent(
	"bitrix:search.title", 
	"header", 
	array(
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
		),
		"CHECK_DATES" => "N",
		"CONTAINER_ID" => "title-search",
		"CONVERT_CURRENCY" => "N",
		"INPUT_ID" => "title-search-input",
		"NUM_CATEGORIES" => "1",
		"ORDER" => "rank",
		"PAGE" => "#SITE_DIR#search/",
		"PREVIEW_HEIGHT" => "75",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PREVIEW_WIDTH" => "75",
		"PRICE_CODE" => array(
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"SHOW_INPUT" => "Y",
		"SHOW_OTHERS" => "N",
		"SHOW_PREVIEW" => "Y",
		"TEMPLATE_THEME" => "site",
		"TOP_COUNT" => "8",
		"USE_LANGUAGE_GUESS" => "Y",
		"COMPONENT_TEMPLATE" => "header"
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
						<div class="mobile-btn-burger icon-list">
							<!-- div class="mobile-btn-burger">
								<div class="burger-el"></div>
								<div class="burger-el"></div>
								<div class="burger-el"></div>
							</div> -->
						</div>
						<span>Меню</span>
					</div>
					<div class="b-top-menu-cont">
						<? $GLOBALS['sectionsFilter'] = array(
							'!ID' => '116'
						);?>
						<?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "header_categories", Array(
								"FILTER_NAME" => "sectionsFilter",
								"ADD_SECTIONS_CHAIN" => "N",
								"CACHE_GROUPS" => "Y",
								"CACHE_TIME" => "36000000",
								"CACHE_TYPE" => "N",
								"COUNT_ELEMENTS" => "N",
								"IBLOCK_ID" => "1",
								"IBLOCK_TYPE" => "content",
								"SHOW_PARENT_NAME" => "N",
								"TOP_DEPTH" => "10",
								"VIEW_MODE" => "LIST",
							),
							false
						);?>
					</div>

					<? $basketInfo = getBasketCount(); ?>

					<a href='/cart/order/' class="b-price-button" id="b-price-button" <?/*?> style="display: none;"<?*/?>>
						<span class="b-cart-price icon-ruble" id="b-cart-sum"><?=$basketInfo['sum']?></span>
						<span class="b-cart-number-container">
							<span class="b-cart-number" id="b-cart-count"><?=$basketInfo['count']?></span>
						</span>
					</a>
					<script>
						// var sum = (sum = Cookies.get('sum'))?sum:0,
						// 	count = (count = Cookies.get('count'))?count:0;

						// document.getElementById("b-cart-count").innerHTML = count;
						// document.getElementById("b-cart-sum").innerHTML = sum;
						// document.getElementById("b-price-button").style.display = 'block';
					</script>
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
				<? if (!$isDetail): ?>
					<h1 class="b-title"><?$APPLICATION->ShowTitle();?></h1>
				<? endif; ?>
		<? endif; ?>