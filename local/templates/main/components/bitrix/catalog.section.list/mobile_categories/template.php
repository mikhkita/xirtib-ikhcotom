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
	<ul class="mobile-menu-catalog-list">
		<?foreach($arResult["SECTIONS"] as $key => $arItem):?>
			<? $items = GetIBlockSectionList(1, $arItem['ID'], Array("sort"=>"asc"), 2, array()); ?>
			<? $innerSections = $items->GetNext(); ?>
			<li class="b-accordeon">
				<div class="b-accrodeon-head">
					<a href="<?=$arItem["SECTION_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
					<? if ($innerSections): ?>
						<a href="#" class="b-accordeon-plus"></a>
					<? endif; ?>
				</div>
				<? if ($innerSections): ?>
					<div class="b-accordeon-body">
						<?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "mobile_categories", Array(
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
					</div>
				<? endif; ?>
			</li>
		<?endforeach;?>
	</ul>
<? endif; ?>