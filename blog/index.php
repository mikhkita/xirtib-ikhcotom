<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Блог");
?>
<h2 class="b-title b-title-left"><?$APPLICATION->ShowTitle()?></h2>

<? 
	$arFilter = Array('IBLOCK_ID'=>4);
	$dbList = CIBlockSection::GetList(Array(), $arFilter, true);
	while($arRes = $dbList->GetNext()){
		$arSections[] = $arRes;
	}
?>

<ul class="b-blog-filter">
	<? if ($_REQUEST['SECTION_CODE'] == NULL): ?>
		<? $class = "active"; ?>
	<? else: ?>
		<? $class = ""; ?>
	<? endif; ?>
	<li><a href="/blog/" class="<?=$class?>">Всё</a></li>
	<? foreach($arSections as $section): ?>
		<? if ($section['ELEMENT_CNT'] == 0): ?>
			<? continue; ?>
		<? endif; ?>
		<? $class = ""; ?>
		<? if ($section['CODE'] == $_REQUEST['SECTION_CODE']): ?>
			<? $class = "active"; ?>
		<? endif ;?>
		<li><a href="<?=$section["SECTION_PAGE_URL"]?>" class="<?=$class?>"><?=$section['NAME']?></a></li>
	<? endforeach; ?>
</ul>

<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"blog",
	Array(
		"ACTIVE_DATE_FORMAT" => "j F, H:i",
		"ADD_SECTIONS_CHAIN" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "blog",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"ID",1=>"ACTIVE_TO",2=>"CREATED_DATE",),
		"FILTER_NAME" => "arrFilter",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "4",
		"IBLOCK_TYPE" => "content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "5",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => $_REQUEST['SECTION_CODE'],
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(0=>"",1=>"ITEM_LIST",2=>"",),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"COUNT_COMMENT" => "Y",
	)
);?>		
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>