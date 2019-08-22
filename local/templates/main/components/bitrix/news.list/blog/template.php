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
	<ul class="b-blog-list">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<li id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<? $arDate = explode('.', $arItem['CREATED_DATE']); ?>
				<? $date = $arDate[2].'.'.$arDate[1].'.'.$arDate[0]; ?>
				<? if( $arItem["PREVIEW_PICTURE"] ): ?>
					<?
					$renderImage = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], Array("width" => 558, "height" => 313), BX_RESIZE_IMAGE_EXACT, false, $arFilters );
					?>
				<? else: ?>
					<? $renderImage['src'] = SITE_TEMPLATE_PATH.'/i/blog-img.svg'?>
				<? endif; ?>
				<div class="b-blog-list-image">
					<img src="<?=$renderImage['src']?>">
				</div>
				
				<div class="b-blog-list-text">
					<div class="blog-title"><?=$arItem["NAME"]?></div>
					<div class="blog-info">
						<div class="blog-date"><?=$date?></div>
						<div class="blog-author">Автор: <?=$arItem['PROPERTIES']['AUTHOR']['VALUE']?></div>
					</div>
					<p><?=$arItem["PREVIEW_TEXT"]?></p>
					<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="blog-more-a"><span>Читать статью</span> →</a>
				</div>
			</li>
		<?endforeach;?>
	</ul>
	<? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
		<?=$arResult["NAV_STRING"];?>
	<?endif;?>
<? endif; ?>