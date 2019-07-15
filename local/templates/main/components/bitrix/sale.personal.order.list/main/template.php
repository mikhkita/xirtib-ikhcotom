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
				<p class="b-order-empty"><?= Loc::getMessage('SPOL_TPL_EMPTY_CANCELED_ORDER')?></p>
				<?
			}
			else
			{
				?>
				<p class="b-order-empty"><?= Loc::getMessage('SPOL_TPL_EMPTY_HISTORY_ORDER_LIST')?></p>
				<?
			}
		}
		else
		{
			?>
			<p class="b-order-empty"><?= Loc::getMessage('SPOL_TPL_EMPTY_ORDER_LIST')?></p>
			<?
		}
	}
	?>
	<!-- <div class="row col-md-12 col-sm-12">
		<?
		$nothing = !isset($_REQUEST["filter_history"]) && !isset($_REQUEST["show_all"]);
		$clearFromLink = array("filter_history","filter_status","show_all", "show_canceled");

		if ($nothing || $_REQUEST["filter_history"] == 'N')
		{
			?>
			<a class="sale-order-history-link" href="<?=$APPLICATION->GetCurPageParam("filter_history=Y", $clearFromLink, false)?>">
				<?echo Loc::getMessage("SPOL_TPL_VIEW_ORDERS_HISTORY")?>
			</a>
			<?
		}
		if ($_REQUEST["filter_history"] == 'Y')
		{
			?>
			<a class="sale-order-history-link" href="<?=$APPLICATION->GetCurPageParam("", $clearFromLink, false)?>">
				<?echo Loc::getMessage("SPOL_TPL_CUR_ORDERS")?>
			</a>
			<?
			if ($_REQUEST["show_canceled"] == 'Y')
			{
				?>
				<a class="sale-order-history-link" href="<?=$APPLICATION->GetCurPageParam("filter_history=Y", $clearFromLink, false)?>">
					<?echo Loc::getMessage("SPOL_TPL_VIEW_ORDERS_HISTORY")?>
				</a>
				<?
			}
			else
			{
				?>
				<a class="sale-order-history-link" href="<?=$APPLICATION->GetCurPageParam("filter_history=Y&show_canceled=Y", $clearFromLink, false)?>">
					<?echo Loc::getMessage("SPOL_TPL_VIEW_ORDERS_CANCELED")?>
				</a>
				<?
			}
		}
		?>
	</div> -->
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

	if ($_REQUEST["filter_history"] !== 'Y')
	{

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
				<div class="b-order-list">
					<? foreach($order["BASKET_ITEMS"] as $item): ?>

						<?
						$class = "";
						$tmp = GetIBlockElement($item["PRODUCT_ID"]);
						$img = CFile::ResizeImageGet($tmp['PREVIEW_PICTURE'], Array("width" => 73, "height" => 73), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arFilters );
						if ($item['PRICE'] != $item['BASE_PRICE']):
							$class = 'has-discount';
						endif;
						?>

						<div class="b-order-item clearfix">
							<div class="b-order-item-left">
								<img src="<?=$img['src']?>">
								<div class="b-order-item-name">
									<a href="<?=$item["DETAIL_PAGE_URL"]?>"><?=$item['NAME']?></a>
									<div class="meters"><?=$item['QUANTITY']?> шт.</div>
								</div>
							</div>
							<div class="b-order-item-right">
								<div class="b-order-item-price <?=$class?>">
									<div class="price-base"><?=number_format((float)$item['BASE_PRICE']*$item['QUANTITY'], 2, '.', '')?><span class="icon-ruble"></span></div>
									<div class="price-total"><?=number_format((float)$item['PRICE']*$item['QUANTITY'], 2, '.', '')?><span class="icon-ruble"></span></div>
								</div>
							</div>
						</div>
					<? endforeach; ?>
				</div>
				<div class="b-order-total">
					<div class="total-border"></div>
					<div class="b-order-total-inner">
						<p>Итого:</p>
						<div class="total"><?=number_format((float)$order['ORDER']["PRICE"], 2, '.', '')?><span class="icon-ruble"></span></div>
					</div>
				</div>
			</div>
			<?
		}
	}
	else
	{
		$orderHeaderStatus = null;

		if ($_REQUEST["show_canceled"] === 'Y' && count($arResult['ORDERS']))
		{
			?>
			<h1 class="sale-order-title">
				<?= Loc::getMessage('SPOL_TPL_ORDERS_CANCELED_HEADER') ?>
			</h1>
			<?
		}

		foreach ($arResult['ORDERS'] as $key => $order)
		{
			if ($orderHeaderStatus !== $order['ORDER']['STATUS_ID'] && $_REQUEST["show_canceled"] !== 'Y')
			{
				$orderHeaderStatus = $order['ORDER']['STATUS_ID'];
				?>
				<h1 class="sale-order-title">
					<?= Loc::getMessage('SPOL_TPL_ORDER_IN_STATUSES') ?> &laquo;<?=htmlspecialcharsbx($arResult['INFO']['STATUS'][$orderHeaderStatus]['NAME'])?>&raquo;
				</h1>
				<?
			}
			?>
			<div class="col-md-12 col-sm-12 sale-order-list-container">
				<div class="row">
					<div class="col-md-12 col-sm-12 sale-order-list-accomplished-title-container">
						<div class="row">
							<div class="col-md-8 col-sm-12 sale-order-list-accomplished-title-container">
								<h2 class="sale-order-list-accomplished-title">
									<?= Loc::getMessage('SPOL_TPL_ORDER') ?>
									<?= Loc::getMessage('SPOL_TPL_NUMBER_SIGN') ?>
									<?= htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER'])?>
									<?= Loc::getMessage('SPOL_TPL_FROM_DATE') ?>
									<?= $order['ORDER']['DATE_INSERT'] ?>,
									<?= count($order['BASKET_ITEMS']); ?>
									<?
									$count = substr(count($order['BASKET_ITEMS']), -1);
									if ($count == '1')
									{
										echo Loc::getMessage('SPOL_TPL_GOOD');
									}
									elseif ($count >= '2' || $count <= '4')
									{
										echo Loc::getMessage('SPOL_TPL_TWO_GOODS');
									}
									else
									{
										echo Loc::getMessage('SPOL_TPL_GOODS');
									}
									?>
									<?= Loc::getMessage('SPOL_TPL_SUMOF') ?>
									<?= $order['ORDER']['FORMATED_PRICE'] ?>
								</h2>
							</div>
							<div class="col-md-4 col-sm-12 sale-order-list-accomplished-date-container">
								<?
								if ($_REQUEST["show_canceled"] !== 'Y')
								{
									?>
									<span class="sale-order-list-accomplished-date">
										<?= Loc::getMessage('SPOL_TPL_ORDER_FINISHED')?>
									</span>
									<?
								}
								else
								{
									?>
									<span class="sale-order-list-accomplished-date canceled-order">
										<?= Loc::getMessage('SPOL_TPL_ORDER_CANCELED')?>
									</span>
									<?
								}
								?>
								<span class="sale-order-list-accomplished-date-number"><?= $order['ORDER']['DATE_STATUS_FORMATED'] ?></span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 sale-order-list-inner-accomplished">
						<div class="row sale-order-list-inner-row">
							<div class="col-md-3 col-sm-12 sale-order-list-about-accomplished">
								<a class="sale-order-list-about-link" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"])?>">
									<?=Loc::getMessage('SPOL_TPL_MORE_ON_ORDER')?>
								</a>
							</div>
							<div class="col-md-3 col-md-offset-6 col-sm-12 sale-order-list-repeat-accomplished">
								<a class="sale-order-list-repeat-link sale-order-link-accomplished" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_COPY"])?>">
									<?=Loc::getMessage('SPOL_TPL_REPEAT_ORDER')?>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?
		}

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
