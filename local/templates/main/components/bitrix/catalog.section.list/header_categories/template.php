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
	<ul class="b-menu-items">
		<?foreach($arResult["SECTIONS"] as $key => $arItem):?>
			<? if ($arItem['ID'] != 5): ?>
				<li class="b-menu-item"><a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="<? if( $isSectionActive ): ?>active <? endif; ?>"><?=$arItem["NAME"]?></a></li>
			<? endif;?>
		<?endforeach;?>
	</ul>
<? endif; ?>