<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оформление заказа");
?>
	<div class="b-order-parent clearfix">
		<div id="app-order">
			<v-order></v-order>
		</div>
		<div id="b-order-preloader" class="b-order-preloader">
          	<img src="/local/templates/main/i/preloader.svg">
		</div>
	</div>

	<div style="display: none;" class="b-cdek-map-cont">
		<div class="b-cdek-map">
			<div class="b-cdek-punkt">
				Пункт самовывоза: <span id="b-cdek-punk-addr">не выбран</span>
			</div>
			<div class="b-postamat-error">Выберите пункт самовывоза</div>
		<?$APPLICATION->IncludeComponent(
		    "ipol:ipol.sdekPickup",
		    "cdek",
		    Array(
		        "CITIES" => "",
		        "CNT_BASKET" => "N",
		        "CNT_DELIV" => "N",
		        "COUNTRIES" => array(
		            0 => "rus",
		        ),
		        "FORBIDDEN" => array(
		            0 => "inpost",
		        ),
		        "NOMAPS" => "N",
		        "PAYER" => "1",
		        "PAYSYSTEM" => "2",
		    ),
		    false
		);?>
		</div>
	</div>
	<?

	// $res = getOrderList();
	// print_r($res);

	?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>