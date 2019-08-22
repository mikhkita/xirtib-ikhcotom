<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Моточки - клубочки");?>
<?$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.order.list", 
	"main", 
	array(
		"STATUS_COLOR_N" => "green",
		"STATUS_COLOR_P" => "yellow",
		"STATUS_COLOR_F" => "gray",
		"STATUS_COLOR_PSEUDO_CANCELLED" => "red",
		"PATH_TO_DETAIL" => "order_detail.php?ID=#ID#",
		"PATH_TO_COPY" => "basket.php",
		"PATH_TO_CANCEL" => "order_cancel.php?ID=#ID#",
		"PATH_TO_BASKET" => "basket.php",
		"PATH_TO_PAYMENT" => "payment.php",
		"ORDERS_PER_PAGE" => "20",
		"ID" => $ID,
		"SET_TITLE" => "Y",
		"SAVE_IN_SESSION" => "Y",
		"NAV_TEMPLATE" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CACHE_GROUPS" => "Y",
		"HISTORIC_STATUSES" => array(
		),
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"COMPONENT_TEMPLATE" => "main",
		"PATH_TO_CATALOG" => "/catalog/",
		"DISALLOW_CANCEL" => "N",
		"RESTRICT_CHANGE_PAYSYSTEM" => array(
			0 => "0",
		),
		"REFRESH_PRICES" => "N",
		"DEFAULT_SORT" => "STATUS",
		"STATUS_COLOR_PA" => "gray"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>