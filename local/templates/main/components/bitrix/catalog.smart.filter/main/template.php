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

$templateData = array(
	'TEMPLATE_CLASS' => 'bx-'.$arParams['TEMPLATE_THEME']
);

if (isset($templateData['TEMPLATE_THEME']))
{
	$this->addExternalCss($templateData['TEMPLATE_THEME']);
}
// $this->addExternalCss("/bitrix/css/main/bootstrap.css");
// $this->addExternalCss("/bitrix/css/main/font-awesome.css");

$filterClass = '';

if(count($arResult["ITEMS"]) <= 2){
	$filterClass = 'no-filter';
}

?>


<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="b-filter <?=$filterClass?>" id="b-filter-panel">
	<? $isSortField = false; ?>
	<? $isSortType = false; ?>

	<?foreach($arResult["HIDDEN"] as $arItem):?>
	
	<? if ($arItem["CONTROL_NAME"] == 'SORT_FIELD'): ?>
		<? $isSortField = true; ?>
	<? endif;?>
	<? if ($arItem["CONTROL_NAME"] == 'SORT_TYPE'): ?>
		<? $isSortField = true; ?>
	<? endif;?>
	<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
	
	<?endforeach;?>

	<? if (!$isSortField): ?>
		<input type="hidden" name="SORT_FIELD" value="SORT"/>
	<? endif ?>
	<? if (!$isSortType): ?>
		<input type="hidden" name="SORT_TYPE" value="ASC"/>
	<? endif ?>
		<?//prices
			//not prices
		foreach($arResult["ITEMS"] as $key=>$arItem)
		{
			if(empty($arItem["VALUES"])|| isset($arItem["PRICE"])){
				continue;
			}

			if ($arItem["DISPLAY_TYPE"] == "A"&& ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0))
				continue;
			?>
			<? $class = ($arItem["DISPLAY_EXPANDED"] == "Y")?'open':''; ?>
				<? foreach($arItem["VALUES"] as $item): ?>
					<? if ($item["CHECKED"]):?>
						<? $class = 'open';?>
						<? break; ?>
					<?endif;?>
				<? endforeach; ?>


				<?if(!empty($arItem["VALUES"]["MIN"]["HTML_VALUE"]) && !empty($arItem["VALUES"]["MAX"]["HTML_VALUE"])):?>
				<?if(intval($arItem["VALUES"]["MIN"]["HTML_VALUE"]) != intval($arItem["VALUES"]["MIN"]["VALUE"]) || intval($arItem["VALUES"]["MAX"]["HTML_VALUE"]) != intval($arItem["VALUES"]["MAX"]["VALUE"])):?>
				<? $class = ""; ?>
				<?endif;?>
				<?endif;?>

				<div class="b-filter-item <?=$class?>">
					<div class="b-filter-tab">
						<div class="b-filter-item-name">
							<h3><?=$arItem["NAME"]?></h3>
							<div class="filter-toggle">
								<div class="icon-minus"></div>
								<div class="icon-plus"></div>
							</div>
						</div>
					</div>

					<?
					$arCur = current($arItem["VALUES"]);
					switch ($arItem["DISPLAY_TYPE"])
					{
				case "A"://NUMBERS_WITH_SLIDER
				?>
				<div class="b-filter-toggle b-filter-item-range">
					<div class="range-inputs">
						<input
						class="range-from"
						type="text"
						name="<?=$arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
						id="<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
						value="<?=$arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
						oninput="this.value = this.value.replace(/\D/g, '')"
						/>
						<div class="icon-minus"></div>
						<input
						class="range-to"
						type="text"
						name="<?=$arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
						id="<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
						value="<?=$arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
						oninput="this.value = this.value.replace(/\D/g, '')"
						/>
					</div>
					<div class="slider-range" data-range-from='<?=$arItem["VALUES"]["MIN"]["VALUE"]?>' data-range-to='<?=$arItem["VALUES"]["MAX"]["VALUE"]?>'></div>
				</div>
				<?
				$arJsParams = array(
					"leftSlider" => 'left_slider_'.$key,
					"rightSlider" => 'right_slider_'.$key,
					"tracker" => "drag_tracker_".$key,
					"trackerWrap" => "drag_track_".$key,
					"minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
					"maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
					"minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
					"maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
					"curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
					"curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
					"fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"] ,
					"fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
					"precision" => $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0,
					"colorUnavailableActive" => 'colorUnavailableActive_'.$key,
					"colorAvailableActive" => 'colorAvailableActive_'.$key,
					"colorAvailableInactive" => 'colorAvailableInactive_'.$key,
				);
				?>
				<?
				break;
				case "B"://NUMBERS
				?>
				<div class="col-xs-6 bx-filter-parameters-box-container-block bx-left">
					<i class="bx-ft-sub"><?=GetMessage("CT_BCSF_FILTER_FROM")?></i>
					<div class="bx-filter-input-container">
						<input
						class="min-price"
						type="text"
						name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
						id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
						value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
						/>
					</div>
				</div>
				<div class="col-xs-6 bx-filter-parameters-box-container-block bx-right">
					<i class="bx-ft-sub"><?=GetMessage("CT_BCSF_FILTER_TO")?></i>
					<div class="bx-filter-input-container">
						<input
						class="max-price"
						type="text"
						name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
						id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
						value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
						/>
					</div>
				</div>
				<?
				break;
				case "G"://CHECKBOXES_WITH_PICTURES
				?>
				<div class="b-filter-toggle">
					<?foreach ($arItem["VALUES"] as $val => $ar):?>
					<ul class="b-filter-more b-filter-texture-list clearfix">
						<li>
							<input
							style="display: none"
							type="checkbox"
							name="<?=$ar["CONTROL_NAME"]?>"
							id="<?=$ar["CONTROL_ID"]?>"
							value="<?=$ar["HTML_VALUE"]?>"
							<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
							/>
							<label for="<?=$ar["CONTROL_ID"]?>" onclick="BX.toggleClass(this, 'bx-active');">
								<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
								<img src="<?=$ar["FILE"]["SRC"]?>">
								<?endif?>
							</label>
						</li>
						<?endforeach?>
					</div>
					<?
					break;
				case "H"://CHECKBOXES_WITH_PICTURES_AND_LABELS
				?>
				<div class="col-xs-12">
					<div class="bx-filter-param-btn-block">
						<?foreach ($arItem["VALUES"] as $val => $ar):?>
						<input
						style="display: none"
						type="checkbox"
						name="<?=$ar["CONTROL_NAME"]?>"
						id="<?=$ar["CONTROL_ID"]?>"
						value="<?=$ar["HTML_VALUE"]?>"
						<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
						/>
						<?
						$class = "";
						if ($ar["CHECKED"])
							$class.= " bx-active";
						if ($ar["DISABLED"])
							$class.= " disabled";
						?>
						<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label<?=$class?>" onclick="BX.toggleClass(this, 'bx-active');">
							<span class="bx-filter-param-btn bx-color-sl">
								<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
								<span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
								<?endif?>
							</span>
							<span class="bx-filter-param-text" title="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?><?
							if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
								?> (<span data-role="count_<?=$ar["CONTROL_ID"]?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
								endif;?></span>
							</label>
							<?endforeach?>
						</div>
					</div>
					<?
					break;
				case "P"://DROPDOWN
				$checkedItemExist = false;
				?>
				<div class="col-xs-12">
					<div class="bx-filter-select-container">
						<div class="bx-filter-select-block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
							<div class="bx-filter-select-text" data-role="currentOption">
								<?
								foreach ($arItem["VALUES"] as $val => $ar)
								{
									if ($ar["CHECKED"])
									{
										echo $ar["VALUE"];
										$checkedItemExist = true;
									}
								}
								if (!$checkedItemExist)
								{
									echo GetMessage("CT_BCSF_FILTER_ALL");
								}
								?>
							</div>
							<div class="bx-filter-select-arrow"></div>
							<input
							style="display: none"
							type="radio"
							name="<?=$arCur["CONTROL_NAME_ALT"]?>"
							id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
							value=""
							/>
							<?foreach ($arItem["VALUES"] as $val => $ar):?>
							<input
							style="display: none"
							type="radio"
							name="<?=$ar["CONTROL_NAME_ALT"]?>"
							id="<?=$ar["CONTROL_ID"]?>"
							value="<? echo $ar["HTML_VALUE_ALT"] ?>"
							<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
							/>
							<?endforeach?>
							<div class="bx-filter-select-popup" data-role="dropdownContent" style="display: none;">
								<ul>
									<li>
										<label for="<?="all_".$arCur["CONTROL_ID"]?>" class="bx-filter-param-label" data-role="label_<?="all_".$arCur["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')">
											<? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
										</label>
									</li>
									<?
									foreach ($arItem["VALUES"] as $val => $ar):
										$class = "";
										if ($ar["CHECKED"])
											$class.= " selected";
										if ($ar["DISABLED"])
											$class.= " disabled";
										?>
										<li>
											<label for="<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label<?=$class?>" data-role="label_<?=$ar["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')"><?=$ar["VALUE"]?></label>
										</li>
										<?endforeach?>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<?
					break;
				case "R"://DROPDOWN_WITH_PICTURES_AND_LABELS
				?>
				<div class="col-xs-12">
					<div class="bx-filter-select-container">
						<div class="bx-filter-select-block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
							<div class="bx-filter-select-text fix" data-role="currentOption">
								<?
								$checkedItemExist = false;
								foreach ($arItem["VALUES"] as $val => $ar):
									if ($ar["CHECKED"])
									{
										?>
										<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
										<span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
										<?endif?>
										<span class="bx-filter-param-text">
											<?=$ar["VALUE"]?>
										</span>
										<?
										$checkedItemExist = true;
									}
								endforeach;
								if (!$checkedItemExist)
								{
									?><span class="bx-filter-btn-color-icon all"></span> <?
									echo GetMessage("CT_BCSF_FILTER_ALL");
								}
								?>
							</div>
							<div class="bx-filter-select-arrow"></div>
							<input
							style="display: none"
							type="radio"
							name="<?=$arCur["CONTROL_NAME_ALT"]?>"
							id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
							value=""
							/>
							<?foreach ($arItem["VALUES"] as $val => $ar):?>
							<input
							style="display: none"
							type="radio"
							name="<?=$ar["CONTROL_NAME_ALT"]?>"
							id="<?=$ar["CONTROL_ID"]?>"
							value="<?=$ar["HTML_VALUE_ALT"]?>"
							<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
							/>
							<?endforeach?>
							<div class="bx-filter-select-popup" data-role="dropdownContent" style="display: none">
								<ul>
									<li style="border-bottom: 1px solid #e5e5e5;padding-bottom: 5px;margin-bottom: 5px;">
										<label for="<?="all_".$arCur["CONTROL_ID"]?>" class="bx-filter-param-label" data-role="label_<?="all_".$arCur["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')">
											<span class="bx-filter-btn-color-icon all"></span>
											<? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
										</label>
									</li>
									<?
									foreach ($arItem["VALUES"] as $val => $ar):
										$class = "";
										if ($ar["CHECKED"])
											$class.= " selected";
										if ($ar["DISABLED"])
											$class.= " disabled";
										?>
										<li>
											<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label<?=$class?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')">
												<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
												<span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
												<?endif?>
												<span class="bx-filter-param-text">
													<?=$ar["VALUE"]?>
												</span>
											</label>
										</li>
										<?endforeach?>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<?
					break;
				case "K"://RADIO_BUTTONS
				?>
				<div class="col-xs-12">
					<div class="radio">
						<label class="bx-filter-param-label" for="<? echo "all_".$arCur["CONTROL_ID"] ?>">
							<span class="bx-filter-input-checkbox">
								<input
								type="radio"
								value=""
								name="<? echo $arCur["CONTROL_NAME_ALT"] ?>"
								id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
								onclick="smartFilter.click(this)"
								/>
								<span class="bx-filter-param-text"><? echo GetMessage("CT_BCSF_FILTER_ALL"); ?></span>
							</span>
						</label>
					</div>
					<?foreach($arItem["VALUES"] as $val => $ar):?>
					<div class="radio">
						<label data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label" for="<? echo $ar["CONTROL_ID"] ?>">
							<span class="bx-filter-input-checkbox <? echo $ar["DISABLED"] ? 'disabled': '' ?>">
								<input
								type="radio"
								value="<? echo $ar["HTML_VALUE_ALT"] ?>"
								name="<? echo $ar["CONTROL_NAME_ALT"] ?>"
								id="<? echo $ar["CONTROL_ID"] ?>"
								<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
								onclick="smartFilter.click(this)"
								/>
								<span class="bx-filter-param-text" title="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?><?
								if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
									?>&nbsp;(<span data-role="count_<?=$ar["CONTROL_ID"]?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
									endif;?></span>
								</span>
							</label>
						</div>
						<?endforeach;?>
					</div>
					<?
					break;
				case "U"://CALENDAR
				?>
				<?
				break;
				default://CHECKBOXES
				?>
				<div class="b-filter-toggle">
					<ul class="b-filter-more b-filter-checkbox-list">
						<?foreach($arItem["VALUES"] as $val => $ar):?>
						<li class="b-checkbox">
							<input
							type="checkbox"
							value="<?=$ar["HTML_VALUE"] ?>"
							name="<?=$ar["CONTROL_NAME"] ?>"
							id="<?=$ar["CONTROL_ID"] ?>"
							<?=$ar["CHECKED"]? 'checked="checked"': '' ?>
							/>
							<label data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label <?=$ar["DISABLED"] ? 'disabled': '' ?>" for="<?=$ar["CONTROL_ID"] ?>">
								<div class="b-checked icon-checked"></div>
								<p><?=$ar["VALUE"];?></p>
							</label>
						</li>
					<? endforeach; ?>
				</ul>
				<? if (count($arItem["VALUES"]) > 5): ?>
					<div class="center">
						<a href="#" class="dashed show-more">смотреть больше</a>
					</div>
				<? endif ?>
			</div>
			<?
		}
			?>
		</div>
		<?
	}

	foreach($arResult["ITEMS"] as $key=>$arItem){
			$key = $arItem["ENCODED_ID"];
			if(isset($arItem["PRICE"])):
				if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
					continue;

				$step_num = 4;
				$step = ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / $step_num;
				$prices = array();
				if (Bitrix\Main\Loader::includeModule("currency"))
				{
					for ($i = 0; $i < $step_num; $i++)
					{
						$prices[$i] = CCurrencyLang::CurrencyFormat($arItem["VALUES"]["MIN"]["VALUE"] + $step*$i, $arItem["VALUES"]["MIN"]["CURRENCY"], false);
					}
					$prices[$step_num] = CCurrencyLang::CurrencyFormat($arItem["VALUES"]["MAX"]["VALUE"], $arItem["VALUES"]["MAX"]["CURRENCY"], false);
				}
				else
				{
					$precision = $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0;
					for ($i = 0; $i < $step_num; $i++)
					{
						$prices[$i] = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step*$i, $precision, ".", "");
					}
					$prices[$step_num] = number_format($arItem["VALUES"]["MAX"]["VALUE"], $precision, ".", "");
				}

				$last = count($prices) - 1;

				$prices[$last] = str_replace('&nbsp;', '', $prices[$last]);
				$prices[$last] = preg_replace("/\s+/", "", $prices[$last]);

				?>
				<?if(intval($arItem["VALUES"]["MIN"]["HTML_VALUE"]) != intval($arItem["VALUES"]["MIN"]["VALUE"]) || intval($arItem["VALUES"]["MAX"]["HTML_VALUE"]) != intval($arItem["VALUES"]["MAX"]["VALUE"])):?>
				<? $class = ""; ?>
				<?endif;?>

				<div class="b-filter-item <?=$class?>">
					<div class="b-filter-tab">
						<div class="b-filter-item-name">
							<h3><?=$arItem["NAME"]?></h3>
							<div class="filter-toggle">
								<div class="icon-minus"></div>
								<div class="icon-plus"></div>
							</div>
						</div>
					</div>
					<div class="b-filter-toggle b-filter-item-range">
						<div class="range-inputs">
							<input class="range-from" type="text" name="<?=$arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>" oninput="this.value = this.value.replace(/\D/g, '')" value="<?=floor($arItem["VALUES"]["MIN"]["HTML_VALUE"])?>">
							<div class="icon-minus"></div>
							<input class="range-to" type="text" name="<?=$arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>" oninput="this.value = this.value.replace(/\D/g, '')" value="<?=ceil($arItem["VALUES"]["MAX"]["HTML_VALUE"])?>">
						</div>
						<div class="slider-range" data-range-from="<?=floor($prices[0])?>" data-range-to="<?=ceil($prices[$last])?>"></div>
					</div>
				</div>
				<?
				$arJsParams = array(
					"leftSlider" => 'left_slider_'.$key,
					"rightSlider" => 'right_slider_'.$key,
					"tracker" => "drag_tracker_".$key,
					"trackerWrap" => "drag_track_".$key,
					"minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
					"maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
					"minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
					"maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
					"curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
					"curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
					"fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"] ,
					"fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
					"precision" => $precision,
					"colorUnavailableActive" => 'colorUnavailableActive_'.$key,
					"colorAvailableActive" => 'colorAvailableActive_'.$key,
					"colorAvailableInactive" => 'colorAvailableInactive_'.$key,
				);
				?>
			<?endif;
		}
	?>
</form>