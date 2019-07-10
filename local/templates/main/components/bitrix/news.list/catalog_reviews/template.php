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
	<div class="b-detail-review-list">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<? $arDate = explode('.', $arItem['CREATED_DATE']); ?>
			<? $date = $arDate[2].'.'.$arDate[1].'.'.$arDate[0]; ?>
			<div class="b-detail-review" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<? if( $arItem["PREVIEW_PICTURE"] ): ?>
					<?
					$renderImage = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], Array("width" => 267, "height" => 267), BX_RESIZE_IMAGE_EXACT, false, $arFilters );
					?>
				<? else: ?>
					<? $renderImage['src'] = SITE_TEMPLATE_PATH.'/i/icon-man.svg'?>
				<? endif; ?>
				<div class="b-detail-review-header">
					<img src="<?=$renderImage['src']?>">
					<div class="b-detail-review-name">
						<h3><?=$arItem['NAME']?></h3>
						<div class="b-detail-review-date"><?=$date?></div>
					</div>
				</div>
				<div class="b-detail-review-text">
					<p><?=$arItem['PREVIEW_TEXT']?></p>
				</div>
			</div>
		<?endforeach;?>
	</div>
	<? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
		<?=$arResult["NAV_STRING"];?>
	<?endif;?>
<? endif; ?>