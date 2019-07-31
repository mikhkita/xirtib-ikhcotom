<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

if(empty($arResult))
	return "";

foreach ($arResult as $key => $arItem) {
	if (empty($arItem["TITLE"]) && empty($arItem["LINK"])) {
		unset($arResult[$key]);
	}
}

$strReturn = '<ul class="b-breadcrumbs clearfix">';

$itemSize = count($arResult);

for($index = 0; $index < $itemSize; $index++)
{
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);

	$nextRef = ($index < $itemSize-2 && $arResult[$index+1]["LINK"] <> ""? ' itemref="bx_breadcrumb_'.($index+1).'"' : '');
	$child = ($index > 0? ' itemprop="child"' : '');

	if($arResult[$index]["LINK"] <> "" && ($index != $itemSize - 1 || $tog) )
	{
		$strReturn .= '
			<li id="bx_breadcrumb_'.$index.'" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"'.$child.$nextRef.'>
				<a href="'.$arResult[$index]["LINK"].'" title="'.$title.'" itemprop="url">'.$title.'</a>
			</li>';
	}
	else
	{
		$strReturn .= '
			<li class="bx-breadcrumb-item">
				<span>'.$title.'</span>
			</li>';
	}
}

$strReturn .= '</ul>';

return $strReturn;
