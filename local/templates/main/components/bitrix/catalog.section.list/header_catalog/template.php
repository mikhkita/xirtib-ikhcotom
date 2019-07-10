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
	<ul class="b-header-catalog">
		<?foreach($arResult["SECTIONS"] as $key => $arItem):?>
			<li>
				<a href="<?=$arItem["SECTION_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
				<?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "header_catalog", Array(
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
					),
					false
				);?>
			</li>
		<?endforeach;?>
	</ul>
<? endif; ?>