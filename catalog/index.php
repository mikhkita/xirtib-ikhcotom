<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог");?>

<? if($_REQUEST["SECTION_CODE"] || $_REQUEST['TAGS'] || $_REQUEST['SECTION_CODE_CUSTOM']):

	if ($_REQUEST['TAGS'] && CModule::IncludeModule('search')) {
		$rsTags = CSearchTags::GetList(array(),array("MODULE_ID" => "iblock"), array("CNT" => "DESC"));
	    $arTag = Array();
	    while($arTag = $rsTags->Fetch()){
	    	
	    	if( $_REQUEST['TAGS'] == Cutil::translit($arTag['NAME'],"ru") ){
			    $tagName = mb_strtoupper(mb_substr($arTag['NAME'], 0, 1)).mb_substr($arTag['NAME'], 1);
	    		$APPLICATION->SetTitle($tagName);
	    		$GLOBALS['arrFilter'] = array("?TAGS" => $arTag['NAME']);
	    		break;
	    	}
	    }
	}

	if ($_REQUEST['SECTION_CODE_CUSTOM']) {

		$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*", 'PREVIEW_TEXT');
		$arFilter = Array("IBLOCK_ID"=>1, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", 'CODE' => $_REQUEST['SECTION_CODE_CUSTOM']);
		$res = CIBlockSection::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
		
		while($ob = $res->GetNextElement()){ 
			$arFields = $ob->GetFields(); 
			$seoSectoinTitle = $arFields['NAME'];
			$seoText = $arFields['DESCRIPTION'];
		}

	}

	$sortList = array(
		0 => array(
			'NAME' => 'По возрастанию цены',
			'FIELD' => 'catalog_price_1',
			'TYPE' => 'ASC'
		),
		1 => array(
			'NAME' => 'По убыванию цены',
			'FIELD' => 'catalog_price_1',
			'TYPE' => 'DESC'
		),
		2 => array(
			'NAME' => 'По популярности',
			'FIELD' => 'sort',
			'TYPE' => 'ASC'
		), 
		3 => array(
			'NAME' => 'По алфавиту',
			'FIELD' => 'name',
			'TYPE' => 'ASC'
		)
	);

	if (!$_REQUEST['SORT_FIELD']) {
		$_REQUEST['SORT_FIELD'] = "sort";
	}

	if (!$_REQUEST['SORT_TYPE']) {
		$_REQUEST['SORT_TYPE'] = "ASC";
	} ?>

	<h2 class="b-title"><?$APPLICATION->ShowTitle();?></h2>

	<div class="b-catalog clearfix" id="b-catalog">
		<?$APPLICATION->IncludeComponent(
			"bitrix:catalog.smart.filter",
			"main",
			Array(
				"CACHE_GROUPS" => "Y",
				"CACHE_TIME" => "36000000",
				"CACHE_TYPE" => "A",
				"COMPONENT_TEMPLATE" => "main",
				"CONVERT_CURRENCY" => "Y",
				"CURRENCY_ID" => "RUB",
				"DISPLAY_ELEMENT_COUNT" => "Y",
				"FILTER_NAME" => "arrFilter",
				"FILTER_VIEW_MODE" => "horizontal",
				"HIDE_NOT_AVAILABLE" => "N",
				"IBLOCK_ID" => "1",
				// "CUSTOM_SECTION_CODE" => $_REQUEST["SECTION_CODE"],
				"IBLOCK_TYPE" => "content",
				"INSTANT_RELOAD" => "Y",
				"PAGER_PARAMS_NAME" => "arrPager",
				"POPUP_POSITION" => "left",
				"PREFILTER_NAME" => "smartPreFilter",
				"PRICE_CODE" => array(0=>"PRICE",),
				"SAVE_IN_SESSION" => "N",
				"SECTION_CODE" => $_REQUEST["SECTION_CODE"],
				"SECTION_CODE_PATH" => "",
				"SECTION_DESCRIPTION" => "-",
				"SECTION_TITLE" => "-",
				"SEF_MODE" => "N",
				"SEF_RULE" => "/catalog/#SECTION_CODE#/filter/#SMART_FILTER_PATH#/apply/",
				"SMART_FILTER_PATH" => $_REQUEST["SMART_FILTER_PATH"],
				"TEMPLATE_THEME" => "site",
				"XML_EXPORT" => "N"
			)
		);?>

		<div class="b-catalog-list after-load">
			<div class="clearfix">
				<div class="b-product-colors">
					<span>Сортировать:</span>
					<select name="colors" class="sort-select">
						<? foreach( $sortList as $sort): ?>
							<? 
							$attr = '';
							if( $_REQUEST['SORT_FIELD'] == $sort['FIELD'] && $_REQUEST['SORT_TYPE'] == $sort['TYPE']){
								$attr = 'selected';
							}
							?>
							<option data-type="<?=$sort['TYPE']?>" value="<?=$sort['FIELD']?>" <?=$attr?>><?=$sort['NAME']?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>
			<div class="b-catalog-list-cont">
				<? if ($GLOBALS['partial']): ?>
					<? $APPLICATION->RestartBuffer(); ?>
				<? endif; ?>

				<?$APPLICATION->IncludeComponent(
					"bitrix:catalog.section",
					"main",
					Array(
						"ACTION_VARIABLE" => "action",
						"ADD_PICT_PROP" => "MORE_PHOTO",
						"ADD_PROPERTIES_TO_BASKET" => "Y",
						"ADD_SECTIONS_CHAIN" => "Y",
						"ADD_TO_BASKET_ACTION" => "ADD",
						"AJAX_MODE" => "N",
						"AJAX_OPTION_ADDITIONAL" => "",
						"AJAX_OPTION_HISTORY" => "Y",
						"AJAX_OPTION_JUMP" => "Y",
						"AJAX_OPTION_STYLE" => "N",
						"BACKGROUND_IMAGE" => "-",
						"BASKET_URL" => "/personal/cart/",
						"BROWSER_TITLE" => "-",
						"CACHE_FILTER" => "N",
						"CACHE_GROUPS" => "Y",
						"CACHE_TIME" => "36000000",
						"CACHE_TYPE" => "N",
						"COMPONENT_TEMPLATE" => ".default",
						"CONVERT_CURRENCY" => "N",
						"DETAIL_URL" => "",
						"DISABLE_INIT_JS_IN_COMPONENT" => "N",
						"DISPLAY_BOTTOM_PAGER" => "Y",
						"DISPLAY_TOP_PAGER" => "N",
						"ELEMENT_SORT_FIELD" => $_REQUEST['SORT'],
						"ELEMENT_SORT_FIELD2" => "id",
						"ELEMENT_SORT_ORDER" => $_REQUEST['SORT_TYPE'],
						"ELEMENT_SORT_ORDER2" => "DESC",
						"FILTER_NAME" => "arrFilter",
						"HIDE_NOT_AVAILABLE" => "N",
						"IBLOCK_ID" => "1",
						"IBLOCK_TYPE" => "catalog",
						"IBLOCK_TYPE_ID" => "catalog",
						"INCLUDE_SUBSECTIONS" => "A",
						"LABEL_PROP" => "SALELEADER",
						"LINE_ELEMENT_COUNT" => "1",
						"MESSAGE_404" => "",
						"MESS_BTN_ADD_TO_BASKET" => "В корзину",
						"MESS_BTN_BUY" => "Купить",
						"MESS_BTN_DETAIL" => "Подробнее",
						"MESS_BTN_SUBSCRIBE" => "Подписаться",
						"MESS_NOT_AVAILABLE" => "Заказ по телефону",
						"META_DESCRIPTION" => "-",
						"META_KEYWORDS" => "-",
						"OFFERS_CART_PROPERTIES" => array(0=>"COLOR_REF",1=>"SIZES_CLOTHES",),
						"OFFERS_FIELD_CODE" => array(0=>"",1=>"",),
						"OFFERS_LIMIT" => "5",
						"OFFERS_PROPERTY_CODE" => array(0=>"COLOR_REF",1=>"SIZES_CLOTHES",2=>"SIZES_SHOES",3=>"",),
						"OFFERS_SORT_FIELD" => $_REQUEST['SORT'],
						"OFFERS_SORT_FIELD2" => "id",
						"OFFERS_SORT_ORDER" => $_REQUEST['SORT_TYPE'],
						"OFFERS_SORT_ORDER2" => "desc",
						"OFFER_ADD_PICT_PROP" => "-",
						"OFFER_TREE_PROPS" => array(0=>"COLOR_REF",1=>"SIZES_SHOES",2=>"SIZES_CLOTHES",),
						"PAGER_BASE_LINK_ENABLE" => "N",
						"PAGER_DESC_NUMBERING" => "N",
						"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
						"PAGER_SHOW_ALL" => "Y",
						"PAGER_SHOW_ALWAYS" => "N",
						"PAGER_TEMPLATE" => "main",
						"PAGER_TITLE" => "Товары",
						"PAGE_ELEMENT_COUNT" => 12,
						"PARTIAL_PRODUCT_PROPERTIES" => "N",
						"PRICE_CODE" => array(0=>"PRICE",),
						"PRICE_VAT_INCLUDE" => "N",
						"PRODUCT_DISPLAY_MODE" => "N",
						"PRODUCT_ID_VARIABLE" => "id",
						"PRODUCT_PROPERTIES" => array(),
						"PRODUCT_PROPS_VARIABLE" => "prop",
						"PRODUCT_QUANTITY_VARIABLE" => "",
						"PRODUCT_SUBSCRIPTION" => "N",
						"PROPERTY_CODE" => array(0=>"",1=>"",),
						"SECTION_CODE" => $_REQUEST["SECTION_CODE"],
						"SECTION_CODE_PATH" => "",
						"SECTION_ID" => "",
						"SECTION_ID_VARIABLE" => "SECTION_ID",
						"SECTION_URL" => "",
						"SECTION_USER_FIELDS" => array(0=>"",1=>"",),
						"SEF_MODE" => "N",
						"SET_BROWSER_TITLE" => "Y",
						"SET_LAST_MODIFIED" => "N",
						"SET_META_DESCRIPTION" => "Y",
						"SET_META_KEYWORDS" => "Y",
						"SET_STATUS_404" => "Y",
						"SET_TITLE" => "Y",
						"SHOW_404" => "Y",
						"SHOW_ALL_WO_SECTION" => "Y",
						"SHOW_CLOSE_POPUP" => "N",
						"SHOW_DISCOUNT_PERCENT" => "N",
						"SHOW_OLD_PRICE" => "N",
						"SHOW_PRICE_COUNT" => "1",
						"TEMPLATE_THEME" => "site",
						"USE_MAIN_ELEMENT_SECTION" => "N",
						"USE_PRICE_COUNT" => "N",
						"USE_PRODUCT_QUANTITY" => "N",
						"WITH_REVIEWS" => ($isFirst)?"Y":"N",
						"WITH_CALLBACK" => ($isLast)?"Y":"N",
						"TAGS" => ($_REQUEST['TAGS'])?"Y":"N",
					),
				false,
				Array(
					'ACTIVE_COMPONENT' => 'Y'
				)
				);?>

				<? if ($GLOBALS['partial']): ?>
					<? die(); ?>
				<? endif; ?>
			</div>
		</div>
	</div>
	<? if ($seoText): ?>
		<?$APPLICATION->SetTitle($seoSectoinTitle);?>
	<div class="b-text b-seo">
		<br>
		<br>
		<p><?=$seoText?></p>
	</div>
	<? endif; ?>
<? else: ?>
	<div class="b-catalog-section">
		<h2 class="b-title"><?$APPLICATION->ShowTitle();?></h2>
			<?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "main", Array(
					"ADD_SECTIONS_CHAIN" => "N",
					"CACHE_GROUPS" => "Y",
					"CACHE_TIME" => "36000000",
					"CACHE_TYPE" => "N",
					"COUNT_ELEMENTS" => "Y",
					"IBLOCK_ID" => "1",
					"IBLOCK_TYPE" => "content",
					"SHOW_PARENT_NAME" => "Y",
					"TOP_DEPTH" => "1",
					"VIEW_MODE" => "LINE",
				),
				false
			);?>
	</div>
	<div class="b-stock">
		<h3 class="b-title">Вам может понравиться</h3>
		<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		"slider",
		Array(
			"ACTION_VARIABLE" => "action",
			"ADD_PICT_PROP" => "MORE_PHOTO",
			"ADD_PROPERTIES_TO_BASKET" => "Y",
			"ADD_SECTIONS_CHAIN" => "Y",
			"ADD_TO_BASKET_ACTION" => "ADD",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"AJAX_OPTION_HISTORY" => "Y",
			"AJAX_OPTION_JUMP" => "Y",
			"AJAX_OPTION_STYLE" => "N",
			"BACKGROUND_IMAGE" => "-",
			"BASKET_URL" => "/personal/cart/",
			"BROWSER_TITLE" => "-",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "N",
			"COMPONENT_TEMPLATE" => ".default",
			"CONVERT_CURRENCY" => "N",
			"DETAIL_URL" => "",
			"DISABLE_INIT_JS_IN_COMPONENT" => "N",
			"DISPLAY_BOTTOM_PAGER" => "N",
			"DISPLAY_TOP_PAGER" => "N",
			"ELEMENT_SORT_FIELD" => "created_date",
			"ELEMENT_SORT_FIELD2" => "id",
			"ELEMENT_SORT_ORDER" => $_REQUEST['SORT_TYPE'],
			"ELEMENT_SORT_ORDER2" => "DESC",
			"FILTER_NAME" => "",
			"HIDE_NOT_AVAILABLE" => "N",
			"IBLOCK_ID" => "1",
			"IBLOCK_TYPE" => "catalog",
			"IBLOCK_TYPE_ID" => "catalog",
			"INCLUDE_SUBSECTIONS" => "A",
			"LABEL_PROP" => "SALELEADER",
			"LINE_ELEMENT_COUNT" => "1",
			"MESSAGE_404" => "",
			"MESS_BTN_ADD_TO_BASKET" => "В корзину",
			"MESS_BTN_BUY" => "Купить",
			"MESS_BTN_DETAIL" => "Подробнее",
			"MESS_BTN_SUBSCRIBE" => "Подписаться",
			"MESS_NOT_AVAILABLE" => "Заказ по телефону",
			"META_DESCRIPTION" => "-",
			"META_KEYWORDS" => "-",
			"OFFERS_CART_PROPERTIES" => array(0=>"COLOR_REF",1=>"SIZES_CLOTHES",),
			"OFFERS_FIELD_CODE" => array(0=>"",1=>"",),
			"OFFERS_LIMIT" => "5",
			"OFFERS_PROPERTY_CODE" => array(0=>"COLOR_REF",1=>"SIZES_CLOTHES",2=>"SIZES_SHOES",3=>"",),
			"OFFERS_SORT_FIELD" => $_REQUEST['SORT'],
			"OFFERS_SORT_FIELD2" => "id",
			"OFFERS_SORT_ORDER" => $_REQUEST['SORT_TYPE'],
			"OFFERS_SORT_ORDER2" => "desc",
			"OFFER_ADD_PICT_PROP" => "-",
			"OFFER_TREE_PROPS" => array(0=>"COLOR_REF",1=>"SIZES_SHOES",2=>"SIZES_CLOTHES",),
			"PAGER_BASE_LINK_ENABLE" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "Y",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => "main",
			"PAGER_TITLE" => "Товары",
			"PAGE_ELEMENT_COUNT" => 12,
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"PRICE_CODE" => array(0=>"PRICE",),
			"PRICE_VAT_INCLUDE" => "N",
			"PRODUCT_DISPLAY_MODE" => "N",
			"PRODUCT_ID_VARIABLE" => "id",
			"PRODUCT_PROPERTIES" => array(),
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"PRODUCT_QUANTITY_VARIABLE" => "",
			"PRODUCT_SUBSCRIPTION" => "N",
			"PROPERTY_CODE" => array(0=>"",1=>"",),
			"SECTION_CODE" => $_REQUEST["SECTION_CODE"],
			"SECTION_CODE_PATH" => "",
			"SECTION_ID" => "",
			"SECTION_ID_VARIABLE" => "SECTION_ID",
			"SECTION_URL" => "",
			"SECTION_USER_FIELDS" => array(0=>"",1=>"",),
			"SEF_MODE" => "N",
			"SET_BROWSER_TITLE" => "Y",
			"SET_LAST_MODIFIED" => "N",
			"SET_META_DESCRIPTION" => "Y",
			"SET_META_KEYWORDS" => "Y",
			"SET_STATUS_404" => "Y",
			"SET_TITLE" => "Y",
			"SHOW_404" => "Y",
			"SHOW_ALL_WO_SECTION" => "Y",
			"SHOW_CLOSE_POPUP" => "N",
			"SHOW_DISCOUNT_PERCENT" => "N",
			"SHOW_OLD_PRICE" => "N",
			"SHOW_PRICE_COUNT" => "1",
			"TEMPLATE_THEME" => "site",
			"USE_MAIN_ELEMENT_SECTION" => "N",
			"USE_PRICE_COUNT" => "N",
			"USE_PRODUCT_QUANTITY" => "N",
		),
	false,
	Array(
		'ACTIVE_COMPONENT' => 'Y'
	)
	);
	?>
	</div>
	<div class="b-text b-seo">
		<p>Мы очень любим вязать и обязательно проверяем каждый вид пряжи, который продаем. В нашем магазине продается пряжа для вязания от лучших производителей: итальянская, немецкая, перуанская, турецкая. А также аксессуары для вязания и другие товары для hand-made творчества отличного качества. Мы очень любим вязать и обязательно проверяем каждый вид пряжи, который продаем. В нашем магазине продается пряжа для вязания от лучших производителей: итальянская, немецкая, перуанская, турецкая. А также аксессуары для вязания и другие товары для hand-made творчества отличного качества.</p>
		<br>
		<br>
	</div>
<? endif; ?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>