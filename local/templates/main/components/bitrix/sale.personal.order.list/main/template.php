<?

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Page\Asset;

Loc::loadMessages(__FILE__);

if (!empty($arResult['ERRORS']['FATAL']))
{
	foreach($arResult['ERRORS']['FATAL'] as $error)
	{
		ShowError($error);
	}
	$component = $this->__component;
	if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED]))
	{
		$APPLICATION->AuthForm('', false, false, 'N', false);
	}

}
else
{
	if (!empty($arResult['ERRORS']['NONFATAL']))
	{
		foreach($arResult['ERRORS']['NONFATAL'] as $error)
		{
			ShowError($error);
		}
	}
	if (!count($arResult['ORDERS']))
	{
		if ($_REQUEST["filter_history"] == 'Y')
		{
			if ($_REQUEST["show_canceled"] == 'Y')
			{
				?>
				<p class="b-order-empty">У вас ещё не было покупок</p>
				<?
			}
			else
			{
				?>
				<p class="b-order-empty">У вас ещё не было покупок</p>
				<?
			}
		}
		else
		{
			?>
			<p class="b-order-empty">У вас ещё не было покупок</p>
			<?
		}
	}
	?>
	<?
	if (!count($arResult['ORDERS']))
	{
		?>
		<div class="row col-md-12 col-sm-12">
			<a href="<?=htmlspecialcharsbx($arParams['PATH_TO_CATALOG'])?>" class="sale-order-history-link">
				<?=Loc::getMessage('SPOL_TPL_LINK_TO_CATALOG')?>
			</a>
		</div>
		<?
	}

	$orderHeaderStatus = null;
	foreach ($arResult['ORDERS'] as $key => $order)
	{
		$date = $order['ORDER']['DATE_INSERT']->format($arParams['ACTIVE_DATE_FORMAT']);
		$statusClass = '';

		$orderHeaderStatus = $order['ORDER']['STATUS_ID'];
		$status = $arResult['INFO']['STATUS'][$orderHeaderStatus]['NAME'];

		switch ($orderHeaderStatus) {
			case 'N':
				$statusClass = 'status-wait-pay';
				break;
			case 'DN':
				$statusClass = 'status-wait-send';
				break;
			case 'DF':
				$statusClass = 'status-wait-send';
				break;
			case 'PA':
				$statusClass = 'status-wait-send';
				break;
			case 'F':
				$statusClass = 'status-success';
				break;
			
			default:
				break;
		}

		?>
		<div class="b-cabinet-order clearfix">
			<div class="b-order-header clearfix">
				<div class="order-header-left">
					<h3>Заказ №<b><?=$order['ORDER']['ACCOUNT_NUMBER']?></h3>
					<div class="order-date"><?=$date?> г.</div>
				</div>
				<div class="order-header-right">
					<p>Статус заказа:</p>
					<div class="order-status <?=$statusClass?>"><?=$status?></div>
				</div>
			</div>
			<? if ($order['ORDER']["TRACKING_NUMBER"]): ?>
				<div class="b-order-header clearfix">
					<div class="track-number">Трек-номер: <b><?=$order['ORDER']["TRACKING_NUMBER"]?></b></div>
				</div>
			<? endif; ?>
			<div class="b-order-list">
				<? foreach($order["BASKET_ITEMS"] as $item): ?>

					<?
					$class = "";
					$tmp = GetIBlockElement($item["PRODUCT_ID"]);
					$img = CFile::ResizeImageGet($tmp['DETAIL_PICTURE'], Array("width" => 73, "height" => 73), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arFilters );
					if (!$img) {
						$img['src'] = SITE_TEMPLATE_PATH.'/i/hank.svg';
					}
					if ($item['PRICE'] != $item['BASE_PRICE']):
						$class = 'has-discount';
					endif;
					?>

					<?
					$mxResult = CCatalogSku::GetProductInfo($item['PRODUCT_ID']);
					$el = CIBlockElement::GetByID($mxResult['ID']);
					$arElement = $el->fetch();
					$name = is_array($mxResult) ? $arElement['NAME']." (".$item['NAME'].")" : $item['NAME'];
					?>

					<div class="b-order-item clearfix">
						<div class="b-order-item-left">
							<div class="b-order-item-image" style="background-image: url('<?=$img['src']?>');"></div>
							<div class="b-order-item-name">
								<a href="<?=$item["DETAIL_PAGE_URL"]?>#<?=$item['PRODUCT_ID']?>"><?=$name?></a>
								<div class="meters"><?=$item['QUANTITY']?> шт.</div>
							</div>
						</div>
						<div class="b-order-item-right">
							<div class="b-order-item-price <?=$class?>">
								<div class="price-base"><?=convertPrice($item['BASE_PRICE']*$item['QUANTITY'])?><span class="icon-ruble"></span></div>
								<div class="price-total"><?=convertPrice($item['PRICE']*$item['QUANTITY']);?><span class="icon-ruble"></span></div>
							</div>
						</div>
					</div>
				<? endforeach; ?>
			</div>
			<div class="b-order-total">
				<? if (intval($order['ORDER']['PRICE_DELIVERY']) !== 0): ?>
					<div class="b-order-total-inner">
						<p>Стоимость доставки:</p>
						<div class="total"><?=convertPrice($order['ORDER']['PRICE_DELIVERY']);?><span class="icon-ruble"></span></div>
					</div>
				<? endif ?>
				<div class="b-order-total-inner">
					<p>Итого:</p>
					<div class="total"><?=convertPrice($order['ORDER']["PRICE"])?><span class="icon-ruble"></span></div>
				</div>
			</div>
		</div>
		<?
	}


	?>
	<div class="clearfix"></div>
	<?
	echo $arResult["NAV_STRING"];

	if ($_REQUEST["filter_history"] !== 'Y')
	{
		$javascriptParams = array(
			"url" => CUtil::JSEscape($this->__component->GetPath().'/ajax.php'),
			"templateFolder" => CUtil::JSEscape($templateFolder),
			"templateName" => $this->__component->GetTemplateName(),
			"paymentList" => $paymentChangeData
		);
		$javascriptParams = CUtil::PhpToJSObject($javascriptParams);
		?>
		<script>
			BX.Sale.PersonalOrderComponent.PersonalOrderList.init(<?=$javascriptParams?>);
		</script>
		<?
	}
}
?>
