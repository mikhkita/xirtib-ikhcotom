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

<div id="smart_filter_component">
<?$frame = $this->createFrame("smart_filter_component", false)->begin();?>

<?

$arColors = array();
$arStructure = array();

foreach($arResult["ITEMS"] as $arItem){
	if ($arItem['ID'] == '4') {
		$arColors[] = $arItem;
	}
	if ($arItem["ID"] == '25') {
		$arStructure = $arItem['VALUES'];
	}
}

?>

<div class="b-main-filter">
	<? if (!empty($arColors[0]['VALUES'])): ?>
	<h3>По цвету</h3>
	<div class="b-product-texture">
		<div class="main-texture-list">
			<? foreach ($arColors[0]['VALUES'] as $xmlID => $color): ?>
				<?$renderImage = CFile::ResizeImageGet($color["FILE"], Array("width" => 61, "height" => 61), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arFilters );?>
				<div class="b-filter-color-item">
					<input class="main-texture" type="radio" name="arrFilter_4" id="<?=$color["CONTROL_ID"]?>" value="<?=$color["HTML_VALUE_ALT"]?>">
					<label for="<?=$color["CONTROL_ID"]?>"><img src="<?=$renderImage['src']?>"></label>
				</div>
			<? endforeach; ?>
		</div>
	</div>
	<?endif;?>
	<div class="b-main-tabs">
		<div class="b-wool-tabs">
			<h3>По составу</h3>
			<div class="filter-list">
				<? $i = 0; ?>
				<? foreach ($arStructure as $xmlID => $structure): ?>
					<? if ($i == 6): ?>
						<div class="b-more-tabs">
					<? endif; ?>
						<input class="wool-input" name="<?=$structure["CONTROL_ID"]?>" type="checkbox" id="<?=$structure["CONTROL_ID"]?>" value="<?=$structure["HTML_VALUE"]?>">
						<label class="b-btn b-tab-wool" for="<?=$structure["CONTROL_ID"]?>"><?=$structure["VALUE"]?></label>
					<?if ($i == (count($arStructure) - 1)): ?>
						</div>
					<?endif; ?>
					<? $i ++; ?>
				<? endforeach; ?>
				<? if ($i >= 6): ?>
					<div class="b-show-more">смотреть больше</div>
				<? endif; ?>
			</div>
		</div>
		<div class="b-wool-tabs">
			<h3>По метражу</h3>
			<div class="filter-list">
				<input class="lenght-input" name="lenght" type="radio" id="lenght-1" data-min="0" data-max="100">
				<label class="b-btn b-tab-wool" for="lenght-1">0-100</label>
				<input class="lenght-input" name="lenght" type="radio" id="lenght-2" data-min="100" data-max="180">
				<label class="b-btn b-tab-wool" for="lenght-2">100-180</label>
				<input class="lenght-input" name="lenght" type="radio" id="lenght-3" data-min="180" data-max="250">
				<label class="b-btn b-tab-wool" for="lenght-3">180-250</label>
				<input class="lenght-input" name="lenght" type="radio" id="lenght-4" data-min="250" data-max="500">
				<label class="b-btn b-tab-wool" for="lenght-4">250-500</label>
				<input class="lenght-input" name="lenght" type="radio" id="lenght-5" data-min="500" data-max="800">
				<label class="b-btn b-tab-wool" for="lenght-5">500-800</label>
				<input class="lenght-input" name="lenght" type="radio" id="lenght-6" data-min="800" data-max="1200">
				<label class="b-btn b-tab-wool" for="lenght-6">800-1200</label>
				<input type="hidden" name="arrFilter_28_MIN">
				<input type="hidden" name="arrFilter_28_MAX">
			</div>
		</div>
	</div>
	<input class="b-btn b-filter-submit" type="submit" id="set_filter" name="set_filter" value="Подобрать"/>
</div>

<?$frame->end();?>
</div>