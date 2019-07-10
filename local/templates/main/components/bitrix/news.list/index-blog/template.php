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
	<div class="b-main-articles clearfix">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<div class="b-main-article clearfix" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<? $arDate = explode('.', $arItem['CREATED_DATE']); ?>
				<? $date = $arDate[2].'.'.$arDate[1].'.'.$arDate[0]; ?>
				<? $text = substr($arItem["PREVIEW_TEXT"], 0, 130); ?>
				<? $pos = strrpos($text, ' '); ?>
				<? $text = substr($text, 0, $pos).'...'; ?>

				<? if( $arItem["PREVIEW_PICTURE"] ): ?>
					<?
					$renderImage = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], Array("width" => 261, "height" => 186), BX_RESIZE_IMAGE_EXACT, false, $arFilters );
					?>
				<? else: ?>
					<? $renderImage['src'] = SITE_TEMPLATE_PATH.'/i/popup-logo.svg'?>
				<? endif; ?>

				<img src="<?=$renderImage['src']?>">
				<div class="b-main-article-text">
					<h3 class="b-main-article-header"><?=$arItem["NAME"]?></h3>
					<p class="b-main-article-description"><?=$text?></p>
					<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="article-link">Читать статью</a>
				</div>
			</div>
		<?endforeach;?>
	</div>
	<? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
		<?=$arResult["NAV_STRING"];?>
	<?endif;?>
<? endif; ?>