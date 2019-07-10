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

<? if(count($arResult["ITEMS"])): ?>
		<div class="b-cabinet-order clearfix">
			<div class="b-order-list">
				<? foreach ($arResult["ITEMS"] as $arItem): ?>
					<? $class = "";?>
				<? if ($arItem["OFFERS"]): ?>
					<? foreach ($arItem["OFFERS"] as $offer): ?>
						<? if( $offer["PRICES"]["PRICE"]["DISCOUNT_VALUE"] != $offer["PRICES"]["PRICE"]["VALUE"] ): ?>
							<? $class = "has-discount"; ?>
						<? endif; ?>

						<? if ($offer["PRICES"]["PRICE"]["VALUE"] >= 100): ?>
							<? $price = number_format( $offer["PRICES"]["PRICE"]["VALUE"], 0, ',', ' ' ); ?>
							<? $discountPrice = number_format( $offer["PRICES"]["PRICE"]["DISCOUNT_VALUE"], 0, ',', ' ' ); ?>
						<? else: ?>
							<? $price = $offer["PRICES"]["PRICE"]["VALUE"]; ?>
							<? $discountPrice = $offer["PRICES"]["PRICE"]["DISCOUNT_VALUE"]; ?>
						<? endif; ?>
					<? endforeach; ?>
				<? else: ?>

					<? if( $arItem["PRICES"]["PRICE"]["DISCOUNT_VALUE"] != $arItem["PRICES"]["PRICE"]["VALUE"] ): ?>
						<? $class = "has-discount"; ?>
					<? endif; ?>

					<? if ($arItem["PRICES"]["PRICE"]["VALUE"] >= 100): ?>
						<? $price = number_format( $arItem["PRICES"]["PRICE"]["VALUE"], 0, ',', ' ' ); ?>
						<? $discountPrice = number_format( $arItem["PRICES"]["PRICE"]["DISCOUNT_VALUE"], 0, ',', ' ' ); ?>
					<? else: ?>
						<? $price = $offer["PRICES"]["PRICE"]["VALUE"]; ?>
						<? $discountPrice = $offer["PRICES"]["PRICE"]["DISCOUNT_VALUE"]; ?>
					<? endif; ?>

				<? endif; ?>
				<div class="b-order-item clearfix">
					<div class="b-order-item-left">
						<img src="<?=$arItem["PREVIEW_PICTURE"]['SRC']?>">
						<div class="b-order-item-name">
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem['NAME']?></a>
							<div class="meters">
								<a href="#element_view" class="element-view fancy" data-href="<?=$arItem["DETAIL_PAGE_URL"]?>">Быстрый просмотр</a>
							</div>
						</div>
					</div>
					<div class="b-order-item-right">
						<div class="b-order-item-price <?=$class?>">
							<div class="price-base"><?=$price?><span class="icon-ruble"></span></div>
							<div class="price-total"><?=$discountPrice?><span class="icon-ruble"></span></div>
						</div>
						<div class="b-order-item-icon">
							<a href="/ajax/?action=FAVOURITE_REMOVE&ID=<?=$arItem['ID']?>" class="icon-close"></a>
						</div>
					</div>
				</div>
			<? endforeach; ?>			
			</div>
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