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

<div id="catalog_section_sub_component">
<?$frame = $this->createFrame("catalog_section_sub_component", false)->begin();?>

<?if( count($arResult["SECTIONS"]) ): ?>
	<div class="b-category-tiles">
		<?foreach($arResult["SECTIONS"] as $key => $arItem):?>
			<? if ($arItem["DEPTH_LEVEL"] <= 2): ?>
				<a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="b-tile-item">
					<p><?=$arItem["NAME"]?></p>
				</a>
			<? endif; ?>
		<?endforeach;?>
	</div>
<? endif; ?>

<?$frame->end();?>
</div>