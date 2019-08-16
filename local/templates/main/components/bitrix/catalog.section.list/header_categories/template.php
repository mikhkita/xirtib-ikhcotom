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

?>
<?if( count($arResult["SECTIONS"]) ): ?>
	<? if ($arParams['CLASS'] !== 'inner-menu'): ?>
		<div class="b-top-menu">
	<? endif; ?>
		<ul class="b-header-catalog <?=$arParams['CLASS']?>">
			<?foreach($arResult["SECTIONS"] as $key => $arItem):?>
				<? if ($arItem['ID'] != 116): ?>
					<li class="b-menu-item">
						<a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="<? if( $isSectionActive ): ?>active <? endif; ?>"><?=$arItem["NAME"]?></a>
						<?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "header_categories", Array(
							"ADD_SECTIONS_CHAIN" => "N",
							"CACHE_GROUPS" => "Y",
							"CACHE_TIME" => "36000000",
							"CACHE_TYPE" => "N",
							"COUNT_ELEMENTS" => "Y",
							"IBLOCK_ID" => "1",
							"SECTION_ID" => $arItem["ID"],
							"IBLOCK_TYPE" => "content",
							"SHOW_PARENT_NAME" => "Y",
							"TOP_DEPTH" => "1",
							"VIEW_MODE" => "LINE",
							"CLASS" => 'inner-menu',
						),
						false
					);?>
					</li>
				<? endif;?>
			<?endforeach;?>
		</ul>
	<? if ($arParams['CLASS'] !== 'inner-menu'): ?>
		</div>
	<? endif; ?>
<? endif; ?>