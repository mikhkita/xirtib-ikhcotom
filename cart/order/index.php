<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оформление заказа");
?>
<?
// $APPLICATION->IncludeComponent(
// 	"bitrix:sale.order.ajax",
// 	"",
// 	Array(
// 		"ACTION_VARIABLE" => "soa-action",
// 		"ADDITIONAL_PICT_PROP_1" => "-",
// 		"ALLOW_APPEND_ORDER" => "Y",
// 		"ALLOW_AUTO_REGISTER" => "Y",
// 		"ALLOW_NEW_PROFILE" => "Y",
// 		"ALLOW_USER_PROFILES" => "Y",
// 		"BASKET_IMAGES_SCALING" => "adaptive",
// 		"BASKET_POSITION" => "after",
// 		"COMPATIBLE_MODE" => "Y",
// 		"DELIVERIES_PER_PAGE" => "999",
// 		"DELIVERY_FADE_EXTRA_SERVICES" => "N",
// 		"DELIVERY_NO_AJAX" => "Y",
// 		"DELIVERY_NO_SESSION" => "Y",
// 		"DELIVERY_TO_PAYSYSTEM" => "d2p",
// 		"DISABLE_BASKET_REDIRECT" => "N",
// 		"EMPTY_BASKET_HINT_PATH" => "/",
// 		"HIDE_ORDER_DESCRIPTION" => "N",
// 		"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
// 		"PATH_TO_AUTH" => "/auth/",
// 		"PATH_TO_BASKET" => "/cart/",
// 		"PATH_TO_PAYMENT" => "payment.php",
// 		"PATH_TO_PERSONAL" => "index.php",
// 		"PAY_FROM_ACCOUNT" => "N",
// 		"PAY_SYSTEMS_PER_PAGE" => "9",
// 		"PICKUPS_PER_PAGE" => "5",
// 		"PICKUP_MAP_TYPE" => "yandex",
// 		"PRODUCT_COLUMNS_HIDDEN" => array(),
// 		"PRODUCT_COLUMNS_VISIBLE" => array("PREVIEW_PICTURE","PROPS"),
// 		"SEND_NEW_USER_NOTIFY" => "Y",
// 		"SERVICES_IMAGES_SCALING" => "adaptive",
// 		"SET_TITLE" => "Y",
// 		"SHOW_BASKET_HEADERS" => "N",
// 		"SHOW_COUPONS_BASKET" => "Y",
// 		"SHOW_COUPONS_DELIVERY" => "Y",
// 		"SHOW_COUPONS_PAY_SYSTEM" => "Y",
// 		"SHOW_DELIVERY_INFO_NAME" => "Y",
// 		"SHOW_DELIVERY_LIST_NAMES" => "Y",
// 		"SHOW_DELIVERY_PARENT_NAMES" => "Y",
// 		"SHOW_MAP_IN_PROPS" => "N",
// 		"SHOW_NEAREST_PICKUP" => "N",
// 		"SHOW_NOT_CALCULATED_DELIVERIES" => "Y",
// 		"SHOW_ORDER_BUTTON" => "final_step",
// 		"SHOW_PAYMENT_SERVICES_NAMES" => "Y",
// 		"SHOW_PAY_SYSTEM_INFO_NAME" => "Y",
// 		"SHOW_PAY_SYSTEM_LIST_NAMES" => "Y",
// 		"SHOW_PICKUP_MAP" => "Y",
// 		"SHOW_STORES_IMAGES" => "Y",
// 		"SHOW_TOTAL_ORDER_BUTTON" => "N",
// 		"SHOW_VAT_PRICE" => "Y",
// 		"SKIP_USELESS_BLOCK" => "Y",
// 		"SPOT_LOCATION_BY_GEOIP" => "Y",
// 		"TEMPLATE_LOCATION" => ".default",
// 		"TEMPLATE_THEME" => "site",
// 		"USER_CONSENT" => "N",
// 		"USER_CONSENT_ID" => "0",
// 		"USER_CONSENT_IS_CHECKED" => "Y",
// 		"USER_CONSENT_IS_LOADED" => "N",
// 		"USE_CUSTOM_ADDITIONAL_MESSAGES" => "N",
// 		"USE_CUSTOM_ERROR_MESSAGES" => "N",
// 		"USE_CUSTOM_MAIN_MESSAGES" => "N",
// 		"USE_ENHANCED_ECOMMERCE" => "N",
// 		"USE_PHONE_NORMALIZATION" => "Y",
// 		"USE_PRELOAD" => "Y",
// 		"USE_PREPAYMENT" => "N",
// 		"USE_YM_GOALS" => "N"
// 	)
// );
?>
		<h2 class="b-title"><?$APPLICATION->ShowTitle()?></h2>
		<div class="b-order-parent clearfix">
			<div id="app-order">
				<v-order></v-order>
			</div>
		</div>
		<?

		// CCatalogDiscountCoupon::SetCoupon("zima10");
		

		// $arFavourites = getFavourites();
		// $orders["isAuth"] = isAuth();

		// $basket = \Bitrix\Sale\Basket::loadItemsForFUser(
		//    \Bitrix\Sale\Fuser::getId(),
		//    \Bitrix\Main\Context::getCurrent()->getSite()
		// );

		// $order = Bitrix\Sale\Order::create(
		// 	\Bitrix\Main\Context::getCurrent()->getSite(),
		// 	\Bitrix\Sale\Fuser::getId());
		// $order->setPersonTypeId(1);
		// $order->setBasket($basket);

		// $orders["orderID"] = $order->getId();

		// $arBasket = array();
		// $basketItems = $basket->getBasketItems(); // массив объектов Sale\BasketItem
		// foreach ($basketItems as $basketItem) {
		// 	$arBasketItem = array();

		// 	$arBasketItem["id"] = $basketItem->getProductId();//торговое предложение
		// 	$productID = CCatalogSku::GetProductInfo($arBasketItem["id"]);//получить id товара по id торгового предложения
		// 	$arBasketItem["productID"] = $productID["ID"];//товар

		// 	$objElement = \Bitrix\Iblock\ElementTable::getByPrimary($arBasketItem["id"])->fetchObject();
		// 	$arBasketItem["image"] = CFile::GetPath($objElement->getPreviewPicture());
		// 	$arBasketItem["name"] = $basketItem->getField('NAME');
		// 	$arBasketItem["url"] = $basketItem->getField('DETAIL_PAGE_URL');
		// 	$arBasketItem["quantity"] = $basketItem->getQuantity();
		// 	$arBasketItem["basePriceForOne"] = $basketItem->getBasePrice();
		// 	$arBasketItem["totalPriceForOne"] = $basketItem->getPrice();
		// 	$product = \Bitrix\Catalog\ProductTable::getByPrimary($arBasketItem["id"])->fetchObject();
		// 	$arBasketItem["maxCount"] = $product->getQuantity();
		// 	$arBasketItem["favorite"] = in_array($arBasketItem["productID"], $arFavourites);
		//     $arBasket[] = $arBasketItem;
		// }
		// $orders["items"] = $arBasket;

		// $discounts = $order->getDiscount();
		// $arDiscounts = $discounts->getApplyResult();

		// $arCoupons = Bitrix\Sale\DiscountCouponsManager::get(true, array(), true, true);
		// print_r($arCoupons);
		
		// print_r($arDiscounts);


		?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>