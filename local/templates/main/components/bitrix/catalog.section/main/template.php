<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);?>

<? 

if (isAuth($USER)){
	$ids = getFavourites();

	if( empty($ids) || !count($ids) ){
		$ids = 0;
	}
}

if(count($arResult["ITEMS"])): ?>
	<div class="b-catalog-inner clearfix">
		<? foreach ($arResult["ITEMS"] as $arItem): ?>
			<? $class = "";?>
			<? $minVal = 0; ?>
			<? $maxVal = 0; ?>
			<? if ($arItem["OFFERS"]): ?>
				<? $minVal = 100000; ?>
				<? $maxVal = 0; ?>
				<? foreach ($arItem["OFFERS"] as $offer): ?>

					<? if( $offer["PRICES"]["PRICE"]["DISCOUNT_VALUE"] != $offer["PRICES"]["PRICE"]["VALUE"] ): ?>
						<? $class = "has-discount"; ?>
					<? endif; ?>

					<? if( $offer["PRICES"]["PRICE"]["VALUE"] < $minVal): ?>
						<? $minVal = $offer["PRICES"]["PRICE"]["VALUE"]; ?>
						<? if ($offer["PRICES"]["PRICE"]["DISCOUNT_VALUE"]): ?>
							<? $minVal = $offer["PRICES"]["PRICE"]["DISCOUNT_VALUE"]; ?>
						<? endif; ?>
					<? endif; ?>

					<? if( $offer["PRICES"]["PRICE"]["VALUE"] > $maxVal): ?>
						<? $maxVal = $offer["PRICES"]["PRICE"]["VALUE"]; ?>
					<? endif; ?>

					<? $price = convertPrice($offer["PRICES"]["PRICE"]["VALUE"]); ?>
					<? $discountPrice = convertPrice($offer["PRICES"]["PRICE"]["DISCOUNT_VALUE"]); ?>

				<? endforeach; ?>
			<? else: ?>

				<? if( $arItem["PRICES"]["PRICE"]["DISCOUNT_VALUE"] != $arItem["PRICES"]["PRICE"]["VALUE"] ): ?>
					<? $class = "has-discount"; ?>
				<? endif; ?>

				<? $price = convertPrice($arItem["PRICES"]["PRICE"]["VALUE"]); ?>
				<? $discountPrice = convertPrice($arItem["PRICES"]["PRICE"]["DISCOUNT_VALUE"]); ?>

			<? endif; ?>

			<div class="b-item-card">
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="b-card-hover-frame"></a>
				<div class="b-card-top">
					<? 
					$images = getElementImages($arItem);
					$renderImage['src'] = $images["DETAIL_PHOTO"][0]["SMALL"];
					?>
					<? /* ?><? if ($arItem['OFFERS']): ?>
						<? if ($arItem['OFFERS'][0]["DETAIL_PICTURE"]): ?>
							<? $renderImage = CFile::ResizeImageGet($arItem['OFFERS'][0]["DETAIL_PICTURE"], Array("width" => 267, "height" => 267), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arFilters ); ?>
						<? else: ?>
							<? $renderImage['src'] = SITE_TEMPLATE_PATH.'/i/hank.svg'; ?>
						<? endif; ?>
					<? else: ?>
						<? if ($arItem["DETAIL_PICTURE"]): ?>
							<? $renderImage = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], Array("width" => 267, "height" => 267), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arFilters ); ?>
						<? else: ?>
							<? $renderImage['src'] = SITE_TEMPLATE_PATH.'/i/hank.svg'; ?>
						<? endif; ?>
					<? endif; ?>
					<? */ ?>
					<div class="b-card-top-img" style="background-image: url('<?=$renderImage['src']?>');"></div>

					<? if( $label = getItemLabel($arItem) ): ?>
						<div class="b-discount"><?$label?></div>
					<? endif;?>

					<? if (isAuth($USER)): ?>
						<? if ($ids != 0): ?>
							<? $favClass = ""; ?>
							<? $favAction = "ADD"; ?>
							<? foreach ($ids as $key => $value) {
								if ($value == $arItem['ID']) {
									$favClass = "active";
									$favAction = "REMOVE";
									break;
								} 
							}
						else:
							$favClass = "";
							$favAction = "ADD";
						endif; ?>
						<a href="/ajax/?ID=<?=$arItem['ID']?>" class="fav-link b-card-fav icon-fav-heart <?=$favClass?>" data-action="<?=$favAction?>"></a>
					<? endif; ?>

					<? /* ?>
					<? $text = "Нет в наличии" ?>
					<? if ($arItem['OFFERS']): ?>
						<? foreach ($arItem['OFFERS'] as $offer): ?>
							<? if ($offer['PRODUCT']['QUANTITY'] != 0): ?>
								<? $text = 'В наличии'?>
								<? break; ?>
							<? endif ?>
						<? endforeach ?>
					<? else: ?>
						<? if ($arItem['QUANTITY'] != 0): ?>
							<? $text = 'В наличии'?>
						<? endif ?>
					<? endif ?>

					<div class="b-in-stock"><?=$text?></div>
					<? */ ?>
					<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="b-card-top-dark"></a>
					<a href="#element_view" data-href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="element-view b-card-top-hover fancy">Быстрый просмотр</a>
				</div>
				<div class="b-card-bottom">
					<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="b-item-name"><?=$arItem['NAME']?></a>
				</div>
				<? if ($minVal != $maxVal): ?>
					<? $minVal = convertPrice($minVal); ?>
					<? $maxVal = convertPrice($maxVal); ?>
					<? $price = $minVal.'</span> - <span class="icon-ruble">'.$maxVal ?>
					<? $class = ''; ?>
				<? endif; ?>
				<div class="b-price-container <?=$class?>">
					<div class="b-price">
						<span class="icon-ruble"><?=$price?></span>
					</div>
					<div class="b-discount-price">
						<span class="icon-ruble"><?=$discountPrice?></span>
					</div>
				</div>
			</div>
		<? endforeach; ?>
	</div>
	<? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
		<?=$arResult["NAV_STRING"];?>
	<?endif;?>
<? else: ?>
	<div class="b-not-result b-text">
		<br>
		<? if( $arParams["CUSTOM_MESSAGE"] ): ?>
			<h3><?=$arParams["CUSTOM_MESSAGE"]?></h3>
		<? else: ?>
		<h3>По Вашему запросу товаров не найдено.</h3>
		<? endif; ?>
	</div>
<? endif; ?>