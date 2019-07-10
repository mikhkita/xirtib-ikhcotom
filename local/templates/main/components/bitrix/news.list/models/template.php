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
	
	<div class="tabs-content models-block clearfix hide">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<div class="models-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">

				<? if( $arItem["PREVIEW_PICTURE"] ): ?>
					<?$renderImage = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], Array("width" => 364, "height" => 313), BX_RESIZE_IMAGE_EXACT, false, $arFilters );?>
				<? endif; ?>

				<div class="models-img">
					<div class="icon-zoom"></div>
					<a class="fancy-img" href="<?=$arItem["PREVIEW_PICTURE"]['SRC']?>">
			        	<img src="<?=$renderImage['src']?>">
			        </a>
				</div>
				<p><span>Цвет пряжи:</span> <?=$arItem["NAME"]?></p>
				<p><span>Количество пряжи:</span> <?=$arItem["CODE"]?></p>
				<p><span>Время вязания:</span> <?=$arItem["PROPERTIES"]['TIME']['VALUE']?></p>
			</div>
		<?endforeach;?>
	</div>
<? endif; ?>