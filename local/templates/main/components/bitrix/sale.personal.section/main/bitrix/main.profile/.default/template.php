<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Localization\Loc;

$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();

?>

<h2 class="b-title"><?$APPLICATION->ShowTitle();?></h2>
<div class="b-cabinet">
	<div class="b-cabinet-profile">
		<div class="b-profile-photo">
			<? if (empty($arUser['PERSONAL_PHOTO'])): ?>
				<div class="empty-photo">
					<a href="edit" class="empty-photo-icon"></a>
				</div>
			<? else: ?>
				<? $photo = CFile::ResizeImageGet($arUser['PERSONAL_PHOTO'], Array("width" => 267, "height" => 267), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arFilters ); ?>
				<div class="current-photo">
					<img src="<?=$photo['src']?>">
				</div>
			<? endif; ?>
		</div>
		<div class="b-profile-name"><?=$arUser['LAST_NAME']?> <?=$arUser['NAME']?> <?=$arUser['SECOND_NAME']?></div>
		<div class="b-profile-phone"><span class="b-profile-phone-icon"></span><?=convertPhoneNumber($arUser['PERSONAL_PHONE'])?></div>
		<div class="b-profile-email"><span class="b-profile-phone-email"></span><?=$arUser['EMAIL']?></div>
		<div><a href="edit" class="b-btn b-btn-edit">Редактировать профиль</a></div>
		<div><a href="?action=logout" class="b-btn b-btn-logout">Выйти</a></div>
	</div>
	<div class="b-cabinet-content">

		<?
		$favouriteClass = '';
		$historyClass = '';
		if ($_REQUEST['tab'] == 'favourite'){
			$favouriteClass = 'active';
		} else {
			$historyClass = 'active';
		} 
		?>

		<div class="b-cabinet-tabs">
			<a href="/personal/" class="b-btn b-btn-tab <?=$historyClass?>">История покупок</a>
			<a href="/personal/?tab=favourite" class="b-btn b-btn-tab <?=$favouriteClass?>">Избранное</a>
		</div>

		<? if ($_REQUEST['tab'] == 'favourite'): ?>
			<?

			$ids = getFavourites();

			if( empty($ids) || !count($ids) ){
				$ids = 0;
			}

			$GLOBALS["arrFilter2"] = array("ID" => $ids);

			if( !isset($_REQUEST["ORDER_FIELD"]) ){
				$_REQUEST["ORDER_FIELD"] = "NAME";
			}

			if( !isset($_REQUEST["ORDER_TYPE"]) ){
				$_REQUEST["ORDER_TYPE"] = "ASC";
			}
			?>
			<div class="b-cabinet-orders tabs-content favorites-block">
			<?
			$APPLICATION->IncludeComponent(
				"bitrix:catalog.section",
				"order_list",
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
					"AJAX_OPTION_STYLE" => "Y",
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
					"ELEMENT_SORT_FIELD" => $_REQUEST["ORDER_FIELD"],
					"ELEMENT_SORT_FIELD2" => "id",
					"ELEMENT_SORT_ORDER" => $_REQUEST["ORDER_TYPE"],
					"ELEMENT_SORT_ORDER2" => "DESC",
					"FILTER_NAME" => "arrFilter2",
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
					"OFFERS_SORT_FIELD" => "sort",
					"OFFERS_SORT_FIELD2" => "id",
					"OFFERS_SORT_ORDER" => "desc",
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
					"PAGE_ELEMENT_COUNT" => 10,
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
					"SET_STATUS_404" => "N",
					"SET_TITLE" => "Y",
					"SHOW_404" => "N",
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
					"CLASS" => "b-limit",
					// "CUSTOM_ORDER" => $ids,
					"CUSTOM_MESSAGE" => "У вас пока нет любимых товаров"
				),
			false,
			Array(
				'ACTIVE_COMPONENT' => 'Y'
			)
			);

			?>
			</div>
		<? else: ?>
			<div class="b-cabinet-orders tabs-content history-block">
				<?$APPLICATION->IncludeComponent(
					"bitrix:sale.personal.order.list",
					"main",
					Array(
				        "STATUS_COLOR_N" => "green",
				        "STATUS_COLOR_P" => "yellow",
				        "STATUS_COLOR_F" => "gray",
				        "STATUS_COLOR_PSEUDO_CANCELLED" => "red",
				        "PATH_TO_DETAIL" => "order_detail.php?ID=#ID#",
				        "PATH_TO_COPY" => "basket.php",
				        "PATH_TO_CANCEL" => "order_cancel.php?ID=#ID#",
				        "PATH_TO_BASKET" => "basket.php",
				        "PATH_TO_PAYMENT" => "payment.php",
				        "ORDERS_PER_PAGE" => 20,
				        "ID" => $ID,
				        "SET_TITLE" => "Y",
				        "SAVE_IN_SESSION" => "Y",
				        "NAV_TEMPLATE" => "",
				        "CACHE_TYPE" => "A",
				        "CACHE_TIME" => "3600",
				        "CACHE_GROUPS" => "Y",
				        "HISTORIC_STATUSES" => "F",
				        "ACTIVE_DATE_FORMAT" => "d.m.Y"
				    )
				);?>
			</div>
		<? endif; ?>

	</div>
</div>
<!-- 
<div class="bx_profile">
	<?
	ShowError($arResult["strProfileError"]);

	if ($arResult['DATA_SAVED'] == 'Y')
	{
		ShowNote(Loc::getMessage('PROFILE_DATA_SAVED'));
	}

	?>
	<form method="post" name="form1" action="<?=$APPLICATION->GetCurUri()?>" enctype="multipart/form-data" role="form">
		<?=$arResult["BX_SESSION_CHECK"]?>
		<input type="hidden" name="lang" value="<?=LANG?>" />
		<input type="hidden" name="ID" value="<?=$arResult["ID"]?>" />
		<input type="hidden" name="save" value="Y" />
		<input type="hidden" name="LOGIN" value="<?=$arResult["arUser"]["LOGIN"]?>" />
		<div class="main-profile-block-shown" id="user_div_reg">
			<?
			if (!in_array(LANGUAGE_ID,array('ru', 'ua')))
			{
				?>
				<div class="form-group">
					<label class="main-profile-form-label col-sm-12 col-md-3 text-md-right" for="main-profile-title"><?=Loc::getMessage('main_profile_title')?></label>
					<div class="col-sm-12">
						<input class="form-control" type="text" name="TITLE" maxlength="50" id="main-profile-title" value="<?=$arResult["arUser"]["TITLE"]?>" />
					</div>
				</div>
				<?
			}
			?>
			<div class="form-group">
				<label class="main-profile-form-label col-sm-12 col-md-3 text-md-right" for="main-profile-name"><?=Loc::getMessage('NAME')?></label>
				<div class="col-sm-12">
					<input class="form-control" type="text" name="NAME" maxlength="50" id="main-profile-name" value="<?=$arResult["arUser"]["NAME"]?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="main-profile-form-label col-sm-12 col-md-3 text-md-right" for="main-profile-last-name"><?=Loc::getMessage('LAST_NAME')?></label>
				<div class="col-sm-12">
					<input class="form-control" type="text" name="LAST_NAME" maxlength="50" id="main-profile-last-name" value="<?=$arResult["arUser"]["LAST_NAME"]?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="main-profile-form-label col-sm-12 col-md-3 text-md-right" for="main-profile-email"><?=Loc::getMessage('EMAIL')?></label>
				<div class="col-sm-12">
					<input class="form-control" type="text" name="EMAIL" maxlength="50" id="main-profile-email" value="<?=$arResult["arUser"]["EMAIL"]?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="main-profile-form-label col-sm-12 col-md-3 text-md-right" for="main-profile-email"><?=Loc::getMessage('PERSONAL_PHONE')?></label>
				<div class="col-sm-12">
					<input class="form-control phone" type="text" name="PERSONAL_PHONE" maxlength="50" id="main-profile-email" value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="main-profile-form-label col-sm-12 col-md-3 text-md-right" for="main-profile-email"><?=Loc::getMessage('WORK_PHONE')?></label>
				<div class="col-sm-12">
					<input class="form-control phone" type="text" name="WORK_PHONE" maxlength="50" id="main-profile-email" value="<?=$arResult["arUser"]["WORK_PHONE"]?>" />
				</div>
			</div>
			<?
			if ($arResult['CAN_EDIT_PASSWORD'])
			{
				?>
				<div class="form-group">
					<p class="main-profile-form-password-annotation col-sm-9 col-sm-offset-3 small">
						<?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?>
					</p>
				</div>
				<div class="form-group">
					<label class="main-profile-form-label col-sm-12 col-md-3 text-md-right" for="main-profile-password"><?=Loc::getMessage('NEW_PASSWORD_REQ')?></label>
					<div class="col-sm-12">
						<input class=" form-control bx-auth-input main-profile-password" type="password" name="NEW_PASSWORD" maxlength="50" id="main-profile-password" value="" autocomplete="off"/>
					</div>
				</div>
				<div class="form-group">
					<label class="main-profile-form-label main-profile-password col-sm-12 col-md-3 text-md-right" for="main-profile-password-confirm">
						<?=Loc::getMessage('NEW_PASSWORD_CONFIRM')?>
					</label>
					<div class="col-sm-12">
						<input class="form-control" type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" value="" id="main-profile-password-confirm" autocomplete="off" />
					</div>
				</div>
				<?
			}
			?>
		</div>
		<p class="main-profile-form-buttons-block col-sm-9 col-md-offset-3">
			<input type="submit" name="save1" class="b-btn b-green-btn main-profile-submit not-ajax" value="<?=(($arResult["ID"]>0) ? Loc::getMessage("MAIN_SAVE") : Loc::getMessage("MAIN_ADD"))?>">
			&nbsp;
			<input type="submit" class="b-btn b-brown-btn"  name="reset" value="<?echo GetMessage("MAIN_RESET")?>">
		</p>
	</form>
	<div class="col-sm-12 main-profile-social-block">
		<?
		if ($arResult["SOCSERV_ENABLED"])
		{
			$APPLICATION->IncludeComponent("bitrix:socserv.auth.split", ".default", array(
				"SHOW_PROFILES" => "Y",
				"ALLOW_DELETE" => "Y"
			),
				false
			);
		}
		?>
	</div>
	<div class="clearfix"></div>
</div> -->