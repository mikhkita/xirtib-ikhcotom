<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $APPLICATION;

$this->setFrameMode(true);
$APPLICATION->SetPageProperty('title', $arResult["NAME"]); ?>

<? $img = CFile::ResizeImageGet($arResult['DETAIL_PICTURE']['ID'], Array("width" => 1000, "height" => 1000), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arFilters );

$arColors = getColors();

if (isAuth($USER)){
	$ids = getFavourites();

	if( empty($ids) || !count($ids) ){
		$ids = 0;
	}
}

if ($ids != 0){
	foreach ($ids as $key => $value) {
		$favClass = "";
		$favAction = "ADD";
		if ($value == $arResult['ID']) {
			$favClass = "active";
			$favAction = "REMOVE";
			break;
		} 
	}
} else{
	$favClass = "";
	$favAction = "ADD";
}

$GLOBALS["arProductReviews"] = array("PROPERTY_PRODUCT_ID" => $arResult["ID"]);
$GLOBALS['arModelsFilter'] = array('PROPERTY_MATERIALS' => $arResult['ID']);
$GLOBALS['arSliderFilter'] = array('TAGS' => $arResult['TAGS']);

vardump($arResult['TAGS']);

$arFilter = Array("IBLOCK_ID" => 6, "ACTIVE"=>"Y", 'PROPERTY_MATERIALS' => $arResult['ID']);
$modelsCount = CIBlockElement::GetList(Array(), $arFilter, array(), Array("nPageSize"=>50), array());

$arFilter = Array("IBLOCK_ID" => 3, "IBLOCK_SECTION_ID" => 8, "ACTIVE"=>"Y", 'PROPERTY_PRODUCT_ID' => $arResult['ID']);
$reviewsCount = CIBlockElement::GetList(Array(), $arFilter, array(), Array("nPageSize"=>50), array());

$id = $arResult['OFFERS'] ? $arResult['OFFERS'][0]['ID'] : $arResult['ID'];
$quantity = intval($arResult['OFFERS'] ? $arResult['OFFERS'][0]["PRODUCT"]["QUANTITY"] : $arResult["PRODUCT"]["QUANTITY"]);
$article = $arResult["OFFERS"] ? $arResult["OFFERS"][0]['CODE'] : $arResult["PROPERTIES"]["ARTICLE"]["VALUE"];

$arImg = getElementImages($arResult);
$bigImage = $arImg['DETAIL_PHOTO'][0]['BIG'];
$isSliderImg = false;

foreach ($arImg['DETAIL_PHOTO'] as $img) {
	if ($bigImage != $img['BIG']) {
		$isSliderImg = true;	
	}
}

$tabClass = 'active';
$tabBlockClass = '';

$reviewTabClass = '';
$reviewTabBlockClass = 'hide';

$measures = array(
	1 => "за 1 метр",
	3 => "за 1 грамм",
	5 => "за 1 шт.",
);

if ($_GET['review'] == 'Y'){
	$tabClass = '';
	$reviewTabClass = 'active';
	$tabBlockClass = 'hide';
	$reviewTabBlockClass = '';
}

$nav = CIBlockSection::GetNavChain(false, $arResult["SECTION"]["ID"]);
$sections = array();
while($arSectionPath = $nav->GetNext()){
	array_push($sections, $arSectionPath);
}
if ($sections[0]["CODE"] == "pryazha") {
	$isPryazha = true;
} else {
	$isPryazha = false;
}

if ($arResult["OFFERS"]){
	$zoomClass = 'hide';
	foreach ($arResult["OFFERS"] as $key => $offer){
		if ($offer["DETAIL_PICTURE"]){
			$zoomClass = '';
		}
		if ($key == 0){
			$price = convertPrice($offer["PRICES"]["PRICE"]["VALUE"]);
			$discountPrice = convertPrice($offer["PRICES"]["PRICE"]["DISCOUNT_VALUE"]);
			$priceType = $measures[$offer["ITEM_MEASURE"]["ID"]];
		}
		if($offer["PRICES"]["PRICE"]["DISCOUNT_VALUE"] != $offer["PRICES"]["PRICE"]["VALUE"]){
			$class = "has-discount";
		}
	}

	$arr = $arResult["OFFERS"][0]["PROPERTIES"];
	unset($arr["CML2_LINK"]);
	foreach ($arr as $key => $value) {
		if (!empty($value['VALUE'])) {
			$name = $value['NAME'];
			break;
		}
	}
	if (!isset($name)) {
		if ($isPryazha) {
			$name = 'Цвет';
		} else {
			$name = 'Опция';
		}
	}
} else {
	if (!$arResult["DETAIL_PICTURE"]) {
		$zoomClass = 'hide';
		$imgAttr = 'onclick="return false;"';
	}
	if( $arResult["PRICES"]["PRICE"]["DISCOUNT_VALUE"] != $arResult["PRICES"]["PRICE"]["VALUE"] ){
		$class = "has-discount";
	}
	$priceType = $measures[$arResult["ITEM_MEASURE"]["ID"]];
	$price = number_format( $arResult["PRICES"]["PRICE"]["VALUE"], 0, ',', ' ' );
	$discountPrice = number_format( $arResult["PRICES"]["PRICE"]["DISCOUNT_VALUE"], 0, ',', ' ' );
}

if ($price != $discountPrice){
	$discount = true;
}

if (count($arResult["OFFERS"]) < 5){
	$sliderClass = "no-slider";
}

?>
<div class="b-detail">
	<? if ($_REQUEST['element_view'] == "Y"): ?>
		<? $GLOBALS['APPLICATION']->RestartBuffer(); ?>
	<? endif; ?>
	<div class="b-product">
		<div class="b-product-photo">
			<div class="main-photo">
				<div class="icon-zoom <?=$zoomClass?>"></div>
				<div class="b-product-main after-load">
					<? if ($arResult["OFFERS"]): ?>
						<? foreach ($arResult["OFFERS"] as $key => $offer): ?>
							<a class="fancy-img" href="<?=$arImg["DETAIL_PHOTO"][$key]["BIG"]?>" data-color-id="<?=$offer['ID']?>" data-fancybox="gallery-1">
								<div class="catalog-element-img" style="background-image: url('<?=$arImg["DETAIL_PHOTO"][$key]["SMALL"]?>');"></div>
							</a>
						<? endforeach; ?>
					<? else: ?>
						<a class="fancy-img" href="<?=$arImg["DETAIL_PHOTO"][0]["BIG"]?>" data-color-id="<?=$offer['ID']?>" data-fancybox="gallery-1" <?=$imgAttr?>>
							<div class="catalog-element-img" style="background-image: url('<?=$arImg["DETAIL_PHOTO"][0]["BIG"]?>');"></div>
						</a>
					<? endif; ?>
				</div>
			</div>
			<? if ($arResult["OFFERS"] && $isSliderImg): ?>
				<div class="b-product-photo-slider <?=$sliderClass?>">
					<? foreach ($arResult["OFFERS"] as $key => $offer): ?>
						<? $class = ($key == 0) ? 'active' : ''; ?>
						<img data-color-id="<?=$offer['ID']?>" src="<?=$arImg["DETAIL_PHOTO"][$key]["SMALL"]?>" class="<?=$class?>">
					<? endforeach; ?>
				</div>
			<? endif; ?>
		</div>
		<div class="b-product-content">
			<h1 class="b-product-name"><?=$arResult['NAME']?></h1>
			<div class="b-product-actions-wrap">
				<ul class="b-product-actions clearfix">
					<li>
						<? if (isAuth($USER)): ?>
							<a href="/ajax/?ID=<?=$arResult['ID']?>" class="fav-link <?=$favClass?>" data-action="<?=$favAction?>">
								<span class="icon icon-star"></span>
								В избранное
							</a>
						<? endif; ?>
					</li>
					<li class="b-share-link">
						<a href="#">
							<span class="icon icon-share"></span>
							<span class="icon icon-share-green"></span>Поделиться
						</a>
						<script src="https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
						<script src="https://yastatic.net/share2/share.js"></script>
						<div class="ya-share2" data-url="<?=$shareLink?>"  data-services="vkontakte,facebook,odnoklassniki,twitter,whatsapp,telegram"></div>
					</li>
					<li>
						<? if ($_REQUEST['element_view'] == "Y"): ?>
							<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?review=Y#element-tabs">
								<span class="icon icon-reviews"></span>
								<span class="icon icon-reviews-green"></span>
								Отзывов: <span class="review-count"><?=$reviewsCount?></span>
							</a>
						<? else: ?>
							<a class="go-tab" data-tab=".tab-reviews" href="#">
								<span class="icon icon-reviews"></span>
								<span class="icon icon-reviews-green"></span>
								Отзывов: <span class="review-count"><?=$reviewsCount?></span>
							</a>
						<? endif; ?>
					</li>
				</ul>
			</div>
			<div class="b-product-params">
				<div class="b-product-params-left">
					<? if ($arResult["OFFERS"]): ?>
					<div class="b-product-colors">
						<span><?=$name?>:</span>
						<select name="colors" class="colors-select">
							<? foreach ($arResult["OFFERS"] as $key => $offer):
								$selected = ($key == 0) ? 'selected' : '';
								foreach ($arColors as $color) {
									if ($color['UF_XML_ID'] == $offer['PROPERTIES']['COLOR']['VALUE']) {
										$offer['PROPERTIES']['COLOR']['NAME'] = $color['UF_NAME'];
									}
								}
					            ?>
								<option 
								data-color-id="<?=$offer['ID']?>" 
								data-price="<?=$offer["PRICES"]["PRICE"]["VALUE"]?>" 
								data-discount-price="<?=$offer["PRICES"]["PRICE"]["DISCOUNT_VALUE"]?>" 
								data-article="<?=$offer["PRODUCT"]["CODE"]?>" 
								data-quantity="<?=$offer["PRODUCT"]["QUANTITY"]?>" 
								<?=$selected?> ><?=$offer['NAME']?></option>
							<? endforeach; ?>
						</select>
					</div>
						<? if (isset($arImg["COLOR_PHOTO"])): ?>
						<div class="b-product-texture">
							<div class="texture-list clearfix">
								<? foreach ($arResult["OFFERS"] as $key => $offer): ?>
									<? $class = ($key == 0) ? 'active' : ''; ?>
									<img data-color-id="<?=$offer['ID']?>" src="<?=$arImg["COLOR_PHOTO"][$key]["SMALL"]?>" class="<?=$class?>">
								<? endforeach; ?>
							</div>
							<? if (count($arResult["OFFERS"]) > 10): ?>
								<div class="center"><a href="#" class="dashed more-colors">Показать больше</a></div>
							<? endif; ?>
						</div>
						<? endif; ?>
					<? endif; ?>
					<div class="b-product-info">
						<p><b>Артикул:</b> <span id="article"><?=$article?></span></p>
						<p><b>В наличии:</b> <span id="quantity"><?=$quantity?></span></p>
						<? if ($arResult["PROPERTIES"]["TYPE"]["VALUE"]): ?>
							<p><b>Вид:</b> 
							<? foreach($arResult["PROPERTIES"]["TYPE"]["VALUE"] as $key => $type):
								echo mb_strtolower($type);
								if ($key != count($arResult["PROPERTIES"]["TYPE"]["VALUE"]) - 1):
									echo ', ';
								endif;
							endforeach; ?>
							</p>
						<? endif; ?>
						<? if ($arResult["PROPERTIES"]["STRUCTURE"]["VALUE"] != 0): ?>
							<p><b>Состав:</b> 
								<? foreach($arResult["PROPERTIES"]["STRUCTURE"]["VALUE"] as $key => $structure):
									echo mb_strtolower($structure);
									if ($key != count($arResult["PROPERTIES"]["STRUCTURE"]["VALUE"]) - 1):
										echo ', ';
									endif;
								endforeach; ?>
							</p>
						<? endif; ?>
						<? if ($_REQUEST['element_view'] != "Y"): ?>
						<a href="#" class="dashed go-tab" data-tab=".tab-spec">Все характеристики</a>
						<? endif; ?>
					</div>
				</div>
				
					<div class="b-product-params-right<? if ($discount): ?> has-discount<? endif; ?>">
						<div class="b-product-price-base">
							<span id="price"><? if ($discount): ?><?=$price?><? endif; ?></span>
							<span class="icon-ruble"></span>
						</div>
						<div class="b-product-price-total gram">
							<span id="discount-price"><?=$discountPrice?></span>
							<span class="icon-ruble-bold"></span>
							<small><?=$priceType?></small>
						</div>
				
					<?if($quantity <= 0){
						$inputVal = 0;
						$btnClass = "unavailable";
					} else {
						$inputVal = 1;
					}?>
					<div class="b-product-quantity <?=$btnClass?>">
						<span>Количество:</span>
						<div class="product-quantity">
							<a href="#" class="icon-minus quantity-reduce"></a>
							<input type="text" name="count" class="quantity-input" data-quantity="<?=$quantity?>" maxlength="3" oninput="this.value = this.value.replace(/\D/g, '')" value="<?=$inputVal?>">
							<a href="#" class="icon-plus quantity-add"></a>
						</div>
						<div class="b-product-quantity-info hide"><span>В наличии:&nbsp;</span><span id="quantity-info"><?=$quantity?></span></div>
					</div>
					<a href="/ajax/?action=ADD2BASKET" data-id="<?=$id?>" class="b-btn b-btn-to-cart <?=$btnClass?>">
						<span class="icon-cart"></span>
						<span class="b-btn-to-cart-text">
						<? if ($quantity <= 0): ?>
							Товара нет в наличии
						<? else: ?>
							Добавить в корзину
						<? endif; ?>
						</span>
					</a>
					<? if ($quantity <= 0): ?>
						<p>Попробуйте выбрать другую опцию</p>
					<? endif; ?>
					<div href="#" onclick="return false;" class="b-btn b-btn-to-cart-cap hide">
						<span class="icon-checked"></span>
						<span class="icon-close"></span>
						<span class="b-cap-text">Товар успешно добавлен</span>
					</div>
					<ul class="b-product-advantages">
						<li><img src="<?=SITE_TEMPLATE_PATH?>/i/icon-delivery.svg">Быстрая доставка.</li>
						<li><img src="<?=SITE_TEMPLATE_PATH?>/i/icon-pay.svg">Оплата банковской картой без комиссии. <a href="/delivery">Подробнее...</a></li>
					</ul>
				</div>
			</div>
			<? if( $isPryazha ): ?>
				<br>
				<p><b>Уважаемые покупатели, обращаем ваше внимание на возможное отличие цвета пряжи, в зависимости от настроек Вашего монитора</b></p>
			<? endif; ?>
		</div>
	</div>
	<? if ($_REQUEST['element_view'] == "Y"): ?>
		<? die(); ?>
	<? endif; ?>
	<div class="b-detail-tabs" id="element-tabs">
		<div class="tabs clearfix">
			<div class="tab tab-desc <?=$tabClass?>" data-block=".desc-block"><span>Описание</span></div>
			<div class="tab tab-spec" data-block=".spec-block"><span>Характеристики</span></div>
			<? if ($modelsCount != '0'): ?>
				<div class="tab tab-models" data-block=".models-block"><span>Модели</span></div>
			<? endif; ?>
			<div class="tab tab-reviews <?=$reviewTabClass?>" data-block=".reviews-block"><span>Отзывы</span></div>
		</div>
		<div class="b-detail-tabs-content">

			<div class="tabs-content desc-block b-text <?=$tabBlockClass?>">
				<?=$arResult['DETAIL_TEXT']?>
			</div>
			<div class="tabs-content spec-block b-text hide">
				<div class="spec-block-left">
					<? foreach ($arResult["PROPERTIES"] as $key => $arProp) {
						if( empty($arProp["VALUE"]) || (is_array($arProp["VALUE"]) && count($arProp["VALUE"]) == 0) || in_array($arProp["CODE"], array("ARTICLE")) ){
							continue;
						}
						?>
						<p><span><?=$arProp["NAME"]?>:</span> <? if( is_array($arProp["VALUE"]) ): ?><?=mb_strtolower(implode(", ", $arProp["VALUE"]), "UTF-8")?><? else: ?><?=$arProp["VALUE"]?><? endif; ?></p>
						<?
					} ?>
					<? /* ?><? if ($arResult["PROPERTIES"]["COUNTRY"]["VALUE"]): ?>
						<p><span>Страна:</span> <?=$arResult["PROPERTIES"]["COUNTRY"]["VALUE"]?></p>
					<? endif; ?>
					<? if ($arResult["PROPERTIES"]["FACTORY"]["VALUE"]): ?>
						<p><span>Производитель:</span> <?=$arResult["PROPERTIES"]["FACTORY"]["VALUE"]?></p>
					<? endif; ?>
					<? if ($arResult["PROPERTIES"]["TYPE"]["VALUE"]): ?>
						<p><span>Вид:</span> 
							<? foreach($arResult["PROPERTIES"]["TYPE"]["VALUE"] as $key => $type):
								echo mb_strtolower($type);
								if ($key != count($arResult["PROPERTIES"]["TYPE"]["VALUE"]) - 1):
									echo ', ';
								endif;
							endforeach; ?>
						</p>
					<? endif; ?>
					<? if ($arResult["PROPERTIES"]["LENGTH"]["VALUE"]): ?>
						<p><span>Длина:</span> <?=$arResult["PROPERTIES"]["LENGTH"]["VALUE"]?> метров</p>
					<? endif; ?>
					<? if ($arResult["PROPERTIES"]["WEIGHT"]["VALUE"]): ?>
						<p><span>Вес:</span> <?=$arResult["PROPERTIES"]["WEIGHT"]["VALUE"]?> грамм</p>
					<? endif; ?>
					<? if ($arResult["PROPERTIES"]["THICKNESS"]["VALUE"]): ?>
						<p><span>Толщина пряжи:</span> <?=$arResult["PROPERTIES"]["THICKNESS"]["VALUE"]?></p>
					<? endif; ?>
					<? if ($arResult["PROPERTIES"]["STRUCTURE"]["VALUE"]): ?>
						<p><span>Состав:</span> 
							<? foreach($arResult["PROPERTIES"]["STRUCTURE"]["VALUE"] as $key => $structure):
								echo mb_strtolower($structure);
								if ($key != count($arResult["PROPERTIES"]["STRUCTURE"]["VALUE"]) - 1):
									echo ', ';
								endif;
							endforeach; ?>
						</p>
					<? endif; ?>
					<? if ($arResult["PROPERTIES"]["NEEDLES_SIZE"]["VALUE"]): ?>
						<p><span>Рекомендуемый размер спиц:</span><?=$arResult["PROPERTIES"]["NEEDLES_SIZE"]["VALUE"]?> мм</p>
					<? endif; ?>
					<? if ($arResult["PROPERTIES"]["DENSITY"]["VALUE"]): ?>
						<p><span>Плотность вязания:</span> <?=$arResult["PROPERTIES"]["DENSITY"]["VALUE"]?></p>
					<? endif; ?>
					<? if ($arResult['PROPERTIES']['CARE']['VALUE']): ?>
						<p><span>Уход:</span> <?=$arResult['PROPERTIES']['CARE']['VALUE']?></p>
					<? endif; ?>
					<? */ ?>
				</div>
				<? if ($arResult["TAGS"]): ?>
					<div class="spec-block-right">
						<p><span>Смотрите также:</span></p>
						<? $arTags = explode(',', $arResult["TAGS"]); ?>
						<ul>
							<? foreach($arTags as $tag): ?>
								<? 
								// $trnsTag = Cutil::translit($tag,"ru"); 
								?>
								<li><a href="/catalog-tag/<?=urlencode($tag)?>/"><?=$tag?></a></li>
							<? endforeach; ?>
						</ul>
					</div>
				<? endif; ?>
			</div>
			<?$APPLICATION->IncludeComponent(
				"bitrix:news.list", 
				"models", 
				array(
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"ADD_SECTIONS_CHAIN" => "N",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_ADDITIONAL" => "",
					"AJAX_OPTION_HISTORY" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "N",
					"CACHE_FILTER" => "N",
					"CACHE_GROUPS" => "N",
					"CACHE_TIME" => "36000000",
					"CACHE_TYPE" => "A",
					"CHECK_DATES" => "Y",
					"DETAIL_URL" => "",
					"DISPLAY_BOTTOM_PAGER" => "Y",
					"DISPLAY_DATE" => "N",
					"DISPLAY_NAME" => "Y",
					"DISPLAY_PICTURE" => "Y",
					"DISPLAY_PREVIEW_TEXT" => "Y",
					"DISPLAY_TOP_PAGER" => "N",
					"FIELD_CODE" => array(
						0 => "CREATED_DATE",
						1 => "",
					),
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"IBLOCK_ID" => "6",
					"IBLOCK_TYPE" => "content",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"INCLUDE_SUBSECTIONS" => "N",
					"MESSAGE_404" => "",
					"NEWS_COUNT" => "6",
					"PAGER_BASE_LINK" => "/",
					"PAGER_BASE_LINK_ENABLE" => "Y",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_PARAMS_NAME" => "arrPager",
					"PAGER_SHOW_ALL" => "Y",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_TEMPLATE" => "main",
					"PAGER_TITLE" => "Новости",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"PREVIEW_TRUNCATE_LEN" => "",
					"PROPERTY_CODE" => array(
						0 => "PRODUCT_ID",
						1 => "USER_ID",
					),
					"SET_BROWSER_TITLE" => "N",
					"SET_LAST_MODIFIED" => "N",
					"SET_META_DESCRIPTION" => "N",
					"SET_META_KEYWORDS" => "N",
					"SET_STATUS_404" => "N",
					"SET_TITLE" => "N",
					"SHOW_404" => "N",
					"SORT_BY1" => "SORT",
					"SORT_BY2" => "ACTIVE_FROM",
					"SORT_ORDER1" => "ASC",
					"SORT_ORDER2" => "DESC",
					"COMPONENT_TEMPLATE" => "models",
					"FILTER_NAME" => "arModelsFilter",
					"STRICT_SECTION_CHECK" => "N"
				),
				false
			);?> 
			<div class="tabs-content reviews-block <?=$reviewTabBlockClass?>">
				
				<?$APPLICATION->IncludeComponent(
					"bitrix:news.list", 
					"catalog_reviews", 
					array(
						"ACTIVE_DATE_FORMAT" => "d.m.Y",
						"ADD_SECTIONS_CHAIN" => "N",
						"AJAX_MODE" => "N",
						"AJAX_OPTION_ADDITIONAL" => "",
						"AJAX_OPTION_HISTORY" => "N",
						"AJAX_OPTION_JUMP" => "N",
						"AJAX_OPTION_STYLE" => "N",
						"CACHE_FILTER" => "N",
						"CACHE_GROUPS" => "N",
						"CACHE_TIME" => "36000000",
						"CACHE_TYPE" => "A",
						"CHECK_DATES" => "Y",
						"DETAIL_URL" => "",
						"DISPLAY_BOTTOM_PAGER" => "Y",
						"DISPLAY_DATE" => "N",
						"DISPLAY_NAME" => "Y",
						"DISPLAY_PICTURE" => "Y",
						"DISPLAY_PREVIEW_TEXT" => "Y",
						"DISPLAY_TOP_PAGER" => "N",
						"FIELD_CODE" => array(
							0 => "CREATED_DATE",
							1 => "",
						),
						"HIDE_LINK_WHEN_NO_DETAIL" => "N",
						"IBLOCK_ID" => "3",
						"IBLOCK_SECTION_ID" => "8",
						"IBLOCK_TYPE" => "content",
						"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
						"INCLUDE_SUBSECTIONS" => "N",
						"MESSAGE_404" => "",
						"NEWS_COUNT" => "6",
						"PAGER_BASE_LINK" => "/reviews/",
						"PAGER_BASE_LINK_ENABLE" => "Y",
						"PAGER_DESC_NUMBERING" => "N",
						"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
						"PAGER_PARAMS_NAME" => "arrPager",
						"PAGER_SHOW_ALL" => "Y",
						"PAGER_SHOW_ALWAYS" => "N",
						"PAGER_TEMPLATE" => "main",
						"PAGER_TITLE" => "Новости",
						"PARENT_SECTION" => "",
						"PARENT_SECTION_CODE" => "",
						"PREVIEW_TRUNCATE_LEN" => "",
						"PROPERTY_CODE" => array(
							0 => "",
							1 => "PRODUCT_ID",
							2 => "USER_ID",
							3 => "",
						),
						"SET_BROWSER_TITLE" => "N",
						"SET_LAST_MODIFIED" => "N",
						"SET_META_DESCRIPTION" => "N",
						"SET_META_KEYWORDS" => "N",
						"SET_STATUS_404" => "N",
						"SET_TITLE" => "N",
						"SHOW_404" => "N",
						"SORT_BY1" => "SORT",
						"SORT_BY2" => "ACTIVE_FROM",
						"SORT_ORDER1" => "ASC",
						"SORT_ORDER2" => "DESC",
						"COMPONENT_TEMPLATE" => "reviews",
						"FILTER_NAME" => "arProductReviews",
						"STRICT_SECTION_CHECK" => "N"
					),
					false
				);?> 
				<div class="b-detail-feedback">
					<h3>Оставьте свой отзыв</h3>
					<form class="clearfix" action="/ajax/?action=ADDREVIEW&PRODUCT_ID=<?=$arResult["ID"]?>" method="POST">
						<div class="b-textarea">
							<textarea rows="2" name="comment" required placeholder="Отзывы могут оставлять только авторизованные пользователи"></textarea>
						</div>
						<input type="text" name="MAIL">
						<? if (isAuth()): ?>
							<a href="#" class="b-btn ajax">Оставить отзыв</a>
						<? else: ?>
							<a href="#popup-sign" class="b-btn fancy">Войти</a>
						<? endif; ?>
						<a href="#b-popup-review-success" class="b-thanks-link fancy" style="display:none;"></a>
						<a href="#b-popup-error-reg" class="b-error-link fancy" style="display:none;"></a>
					</form>
				</div>
			</div>
		</div>
	</div>
	<h3 class="b-title">Похожие товары</h3>
	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		"slider",
		Array(
			"ACTION_VARIABLE" => "action",
			"ADD_PICT_PROP" => "MORE_PHOTO",
			"ADD_PROPERTIES_TO_BASKET" => "Y",
			"ADD_SECTIONS_CHAIN" => "N",
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
			"FILTER_NAME" => "arSliderFilter",
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
</div>