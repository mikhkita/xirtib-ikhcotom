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
$compID = "";
if(isset($arParams["COMPOSITE_ID"])){
	$compID = $arParams["COMPOSITE_ID"];
}
?>
<?if(!empty($compID)):?>
	<div id="<?=$compID?>">
	<?$frame = $this->createFrame($compID, false)->begin();?>
<?else:?>
	<div id="slider_component">
	<?$frame = $this->createFrame("slider_component", false)->begin();?>
<?endif;?>

<? if(count($arResult["ITEMS"])): ?>

<? 

if (isAuth($USER)){
	$ids = getFavourites();

	if( empty($ids) || !count($ids) ){
		$ids = 0;
	}
}

?>
	<?if ($arParams['CUSTOM_HEADER']): ?>
		<h3 class="b-title"><?=$arParams['CUSTOM_HEADER']?></h3>
	<? endif ?>
	<div class="b-item-cards">
		<? foreach ($arResult["ITEMS"] as $arItem): ?>
			<? $class = "";?>
			<? $measureText = ''; ?>
			<? $minVal = 0; ?>
			<? $maxVal = 0; ?>
			<? if ($arItem["OFFERS"]): ?>
				<? $minVal = 100000; ?>
				<? $maxVal = 0; ?>

				<? if($arItem["OFFERS"][0]['PRODUCT']['MEASURE'] == 3): ?>
					<? $measureText = 'за '.$arItem["OFFERS"][0]["CATALOG_MEASURE_RATIO"].' г.'; ?>
				<? endif; ?>
				
				<? foreach ($arItem["OFFERS"] as $offer): ?>

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
			<? else: ?>
				<? if ($arItem['PRODUCT']['MEASURE'] == 3): ?>
					<? $measureText = 'за '.$arItem["CATALOG_MEASURE_RATIO"].' г.'; ?>
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
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="b-card-hover-frame"></a>
				<div class="b-card-top">
					<? 
					$images = getElementImages($arItem, true);
					$renderImage['src'] = $images["DETAIL_PHOTO"][0]["SMALL"];
					?>
					<div class="b-card-top-img" style="background-image: url('<?=$renderImage['src']?>');"></div>

					<? if( $label = getItemLabel($arItem) ): ?>
						<div class="b-discount"><?$label?></div>
					<? endif;?>

					<?// if (isAuth($USER)): ?>
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
						<a href="/ajax/?ID=<?=$arItem['ID']?>" class="fav-link b-card-fav icon-fav-heart <?=$favClass?>" data-action="<?=$favAction?>" data-id="<?=$arItem['ID']?>"></a>
					<?// endif; ?>

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
					<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="b-item-name"><?=(($arItem['PROPERTIES']['TITLE']['VALUE'])?($arItem['PROPERTIES']['TITLE']['VALUE']):($arItem['NAME']))?></a>
					<h5 class="b-item-subname"><?=$arItem['PROPERTIES']['SUBTITLE']['VALUE']?></h5>
				</div>
				<? if ($minVal != $maxVal): ?>
					<? $minVal = convertPrice($minVal); ?>
					<? $maxVal = convertPrice($maxVal); ?>
					<? $price = $minVal.'</span> - <span class="icon-ruble-bold">'.$maxVal ?>
					<? $class = ''; ?>
				<? endif; ?>
				<div class="b-price-container <?=$class?>">
					<div class="b-price">
						<span class="icon-ruble-bold"><?=$price?></span>
						<span class="measure-text"><?=$measureText?></span>
					</div>
					<div class="b-discount-price">
						<span class="icon-ruble-bold"><?=$discountPrice?></span>
						<span class="measure-text"><?=$measureText?></span>
					</div>
				</div>
			</div>
		<? endforeach; ?>
	</div>
	<? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
		<?=$arResult["NAV_STRING"];?>
	<?endif;?>
<?/*?>
<? else: ?>
	<div class="b-not-result b-text">
		<br>
		<? if( $arParams["CUSTOM_MESSAGE"] ): ?>
			<h3><?=$arParams["CUSTOM_MESSAGE"]?></h3>
		<? else: ?>
		<h3>По Вашему запросу товаров не найдено.</h3>
		<? endif; ?>
	</div>
<?*/?>
<? endif; ?>

<?$frame->end();?>
</div>