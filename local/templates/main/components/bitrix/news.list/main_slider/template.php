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
	<div class="b-main-slider">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?$renderImage = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], Array("width" => 2050, "height" => 1048), BX_RESIZE_IMAGE_EXACT, false, false, false, 50);?>
			<div class="b-main-slide" id="<?=$this->GetEditAreaId($arItem['ID']);?>" style="background-image: url(<?=$renderImage['src']?>);">
				<div class="b-main-slide-content"><?=$arItem["PREVIEW_TEXT"]?></div>
			</div>
		<?endforeach;?>
	</div>
<? endif; ?>