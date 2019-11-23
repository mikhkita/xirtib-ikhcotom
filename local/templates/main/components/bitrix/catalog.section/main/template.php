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

<div id="catalog_section_component">
<?$frame = $this->createFrame("catalog_section_component", false)->begin();?>

<? if(count($arResult["ITEMS"])): ?>
	<div class="b-catalog-inner clearfix">
		<? foreach ($arResult["ITEMS"] as $arItem): ?>

			<? $class = ""; ?>
			<? $measureText = ''; ?>
			<? $minVal = 0; ?>
			<? $maxVal = 0; ?>
			<? if ($arItem["OFFERS"]): ?>
				<? $minVal = 100000; ?>
				<? $maxVal = 0; ?>

				<? foreach ($arItem["OFFERS"] as $offer): ?>

					<? if($offer['PROPERTIES']['COLOR']['VALUE'] == $_REQUEST['COLOR']): ?>
						<? $select_offer = '#'.$offer['ID']; ?>
					<? endif; ?>	

					<? if( $offer["PRICES"]["PRICE"]["DISCOUNT_VALUE"] != $offer["PRICES"]["PRICE"]["VALUE"] ): ?>
						<? $class = "has-discount"; ?>
					<? endif; ?>

					<? if( $offer["PRICES"]["PRICE"]["DISCOUNT_VALUE"] < $minVal): ?>
						<? $minVal = $offer["PRICES"]["PRICE"]["DISCOUNT_VALUE"]; ?>
					<? endif; ?>

					<? if( $offer["PRICES"]["PRICE"]["DISCOUNT_VALUE"] > $maxVal): ?>
						<? $maxVal = $offer["PRICES"]["PRICE"]["DISCOUNT_VALUE"]; ?>
					<? endif; ?>

					<? $price = convertPrice($offer["PRICES"]["PRICE"]["VALUE"]); ?>
					<? $discountPrice = convertPrice($offer["PRICES"]["PRICE"]["DISCOUNT_VALUE"]); ?>

				<? endforeach; ?>

				<? if($arItem["OFFERS"][0]['PRODUCT']['MEASURE'] == 3): ?>
					<? $measureText = 'за '.$arItem["OFFERS"][0]["CATALOG_MEASURE_RATIO"].' г.'; ?>
				<? else: ?>
					<? if (($minVal == $maxVal) && isset($arItem["OFFERS"][0]['PRODUCT']['WEIGHT']) && !empty($arItem["OFFERS"][0]['PRODUCT']['WEIGHT'])): ?>
						<? $measureText = $arItem["OFFERS"][0]['PRODUCT']['WEIGHT'].' гр.'; ?>
					<? endif; ?>
				<? endif; ?>

			<? else: ?>

				<? if ($arItem['PRODUCT']['MEASURE'] == 3): ?>
					<? $measureText = 'за '.$arItem["CATALOG_MEASURE_RATIO"].' г.'; ?>
				<? else: ?>
					<? if (isset($arItem['PRODUCT']['WEIGHT'])): ?>
						<? $measureText = $arItem['PRODUCT']['WEIGHT'].' гр.'; ?>
					<? endif; ?>
				<? endif; ?>

				<? if( $arItem["PRICES"]["PRICE"]["DISCOUNT_VALUE"] != $arItem["PRICES"]["PRICE"]["VALUE"] ): ?>
					<? $class = "has-discount"; ?>
				<? endif; ?>

				<? $price = convertPrice($arItem["PRICES"]["PRICE"]["VALUE"]); ?>
				<? $discountPrice = convertPrice($arItem["PRICES"]["PRICE"]["DISCOUNT_VALUE"]); ?>

			<? endif; ?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>

			<div class="b-item-card" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?><?=$select_offer?>" class="b-card-hover-frame"></a>
				<div class="b-card-top">
					<? $images = getElementImages($arItem, true);?>
					<? $renderImage['src'] = $images["DETAIL_PHOTO"][0]["SMALL"]; ?>
					<div class="b-card-top-img" style="background-image: url('<?=$renderImage['src']?>');"></div>
				
					<? if( $label = getItemLabel($arItem) ): ?>
						<div class="b-discount"><?=$label?></div>
					<? endif;?>

					<a href="/ajax/?ID=<?=$arItem['ID']?>" class="fav-link b-card-fav icon-fav-heart" data-id="<?=$arItem['ID']?>"></a>

					<a href="<?=$arItem["DETAIL_PAGE_URL"]?><?=$select_offer?>" class="b-card-top-dark"></a>
					<a href="#element_view" data-href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="element-view b-card-top-hover fancy">Быстрый просмотр</a>
				</div>
				<div class="b-card-bottom">
					<a href="<?=$arItem["DETAIL_PAGE_URL"]?><?=$select_offer?>" class="b-item-name"><?=(($arItem['PROPERTIES']['TITLE']['VALUE'])?($arItem['PROPERTIES']['TITLE']['VALUE']):($arItem['NAME']))?></a>
					<h5 class="b-item-subname"><?=$arItem['PROPERTIES']['SUBTITLE']['VALUE']?></h5>
				</div>
				<? $differentPriceClass = ''; ?>
				<? if ($minVal != $maxVal): ?>
					<? $minVal = convertPrice($minVal); ?>
					<? $maxVal = convertPrice($maxVal); ?>
					<? $price = $minVal.'</span> - <span class="icon-ruble-bold">'.$maxVal ?>
					<? $differentPriceClass = 'different-price'; ?>
					<? $class = ''; ?>
				<? endif; ?>
				<div class="b-price-container <?=$class?> <?=$differentPriceClass?>">
					<div class="b-price">
						<span class="icon-ruble-bold"><?=$price?></span>
					</div>
					<div class="b-discount-price">
						<span class="icon-ruble-bold"><?=$discountPrice?></span>
					</div>
				</div>
				<? if (!empty($measureText)): ?>
					<div class="measure-text"><?=$measureText?></div>
				<? endif; ?>
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
		<h3>В данном разделе пока нет товаров. В ближайшее время мы разместим весь ассортимент.</h3>
		<? endif; ?>
	</div>
<? endif; ?>

<?$frame->end();?>
</div>