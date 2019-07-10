<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (empty($arResult["CATEGORIES"]))
	return;
?>
<div class="bx_searche">
<?foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>
	<?foreach($arCategory["ITEMS"] as $i => $arItem):?>
		<?if($category_id === "all"):?>
			<a href="<?echo $arItem["URL"]?>" class="bx_item_block all_result">
				<div class="bx_img_element"></div>
				<div class="bx_item_element">
					<span class="all_result_title"><?echo $arItem["NAME"]?></span>
				</div>
				<div style="clear:both;"></div>
			</a>
		<?elseif(isset($arResult["ELEMENTS"][$arItem["ITEM_ID"]])):
			$arElement = $arResult["ELEMENTS"][$arItem["ITEM_ID"]];?>
			<a href="<?echo $arItem["URL"]?>" class="bx_item_block">
				<?if (is_array($arElement["PICTURE"])):?>
				<div class="bx_img_element">
					<div class="bx_image" style="background-image: url('<?echo $arElement["PICTURE"]["src"]?>')"></div>
				</div>
				<?endif;?>
				<div class="bx_item_element">
					<span><?echo $arItem["NAME"]?></span>
					<?
					foreach($arElement["PRICES"] as $code=>$arPrice)
					{
						if ($arPrice["MIN_PRICE"] != "Y")
							continue;

						if($arPrice["CAN_ACCESS"])
						{
							if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
								<div class="bx_price">
									<?=$arPrice["PRINT_DISCOUNT_VALUE"]?>
									<span class="old"><?=$arPrice["PRINT_VALUE"]?></span>
								</div>
							<?else:?>
								<div class="bx_price"><?=$arPrice["PRINT_VALUE"]?></div>
							<?endif;
						}
						if ($arPrice["MIN_PRICE"] == "Y")
							break;
					}
					?>
				</div>
				<div style="clear:both;"></div>
			</a>
		<?endif;?>
	<?endforeach;?>
<?endforeach;?>
</div>