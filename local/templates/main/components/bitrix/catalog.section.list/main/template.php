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
<div id="catalog_section_list_component">
<?$frame = $this->createFrame("catalog_section_list_component", false)->begin();?>

<?if( count($arResult["SECTIONS"]) ): ?>
	<div class="b-section clearfix">
		<?foreach($arResult["SECTIONS"] as $key => $arItem):?>
			<a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="b-section-item">
				<div class="b-section-item-img">
					<img src="<?=$arItem["PICTURE"]['SRC']?>">
				</div>
				<div class="b-section-item-name">
					<div class="b-section-gradient"></div>
					<h2><?=$arItem["NAME"]?></h2>
				</div>
			</a>
		<?endforeach;?>
	</ul>
<? endif; ?>

<?$frame->end();?>
</div>