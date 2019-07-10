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
$this->setFrameMode(true);

if(count($arResult["ITEMS"])): ?>
	<ul class="b-reviews-list">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<li id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<? if( $arItem["PREVIEW_PICTURE"] ): ?>
					<?
					$renderImage = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], Array("width" => 267, "height" => 267), BX_RESIZE_IMAGE_EXACT, false, $arFilters );
					?>
				<? else: ?>
					<? $renderImage['src'] = SITE_TEMPLATE_PATH.'/i/icon-man.svg'?>
				<? endif; ?>
				<img src="<?=$renderImage['src']?>" alt="" class="review-img">
				<div class="review-name"><?=$arItem["NAME"]?></div>
				<div class="review-text"><?=$arItem["PREVIEW_TEXT"]?></div>
				<a href="#popup-review-a" class="review-more-a">Читать отзыв полностью</a>
			</li>
		<?endforeach;?>
	</ul>
	<div style="display: none;">
		<a href="#popup-review" class="fancybox-a" id="popup-review-a"></a>
		<div class="b-popup popup-review" id="popup-review">
			<div class="popup-review-head">
				<img src="" alt="" class="popup-review-img">
				<div class="popup-review-name"></div>
			</div>
			<div class="popup-review-text"></div>
			<a href="#" class="b-btn popup-review-btn" data-fancybox-close>Закрыть</a>
		</div>
	</div>
	<? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
		<?=$arResult["NAV_STRING"];?>
	<?endif;?>
<? endif; ?>