<?php
use Bitrix\Main\ {
    Localization\Loc,
    Loader,
    Context,
    Config\Option,
    ArgumentOutOfRangeException,
    LoaderException,
    Entity\Query,
    GroupTable,
    SiteTable
};

use Bitrix\Iblock\IblockTable;

Loc::loadMessages(__FILE__);

$request = Context::getCurrent()->getRequest();

$module_id = $request->get('mid') ? $request->get('mid'):$request->get('id');

try {
    if(!Loader::includeModule($module_id))
        throw new LoaderException(Loc::getMessage("FRED_DISCOUNT_OPTIONS_ERROR_MODULE", ['#MODILE#' => $module_id]));

    if(!Loader::includeModule('iblock'))
        throw new LoaderException(Loc::getMessage("FRED_DISCOUNT_OPTIONS_ERROR_MODULE", ['#MODILE#' => 'iblock']));

    $rsSite = (new Query(SiteTable::getEntity()))
        ->setSelect(['LID', 'NAME'])
        ->setFilter([
            'ACTIVE' => 'Y'
        ])
        ->addGroup('LID')
        ->exec();

    $siteList = [];

    foreach($rsSite->fetchAll() as $arSite) {
        $siteList[$arSite['LID']] = sprintf('[%s] %s ', $arSite['LID'], $arSite['NAME']);
    }

    $rsiblock = (new Query(IblockTable::getEntity()))
        ->setSelect(['ID', 'NAME', 'CODE'])
        ->setFilter([
            'ACTIVE' => 'Y'
        ])
        ->addGroup('ID')
        ->exec();

    $iblockList = [];

    foreach($rsiblock->fetchAll() as $ariblock) {
       $iblockList[$ariblock['ID']] = sprintf('(%d) %s [%s]', $ariblock['ID'], $ariblock['NAME'], $ariblock['CODE']);
    }

    $rsGroup = (new Query(GroupTable::getEntity()))
        ->setSelect(['ID', 'NAME'])
        ->setFilter([
            'ACTIVE' => 'Y'
        ])
        ->addGroup('ID')
        ->exec();

    $groupList = [];

    foreach($rsGroup->fetchAll() as $arGroup) {
        $groupList[$arGroup['ID']] = sprintf('%s', $arGroup['NAME']);
    }

} catch (LoaderException $exception) {

    echo CAdminMessage::ShowMessage($exception->getMessage());
} catch (Exception $exception) {

    echo CAdminMessage::ShowMessage($exception->getMessage());
}

$aTabs = [
	[
		"DIV" 	  => "edit",
		"TAB" 	  => Loc::getMessage("FRED_DISCOUNT_OPTIONS_TAB_NAME"),
		"TITLE"   => Loc::getMessage("FRED_DISCOUNT_OPTIONS_TAB_TITLE"),
		"OPTIONS" => [
            [
                'SITE_ID',
                Loc::getMessage("FRED_DISCOUNT_OPTIONS_TAB_SITE_ID"),
                '',
                ['selectbox', $siteList]
            ],
            [
                'IBLOCK_ID',
                Loc::getMessage("FRED_DISCOUNT_OPTIONS_TAB_IBLOCK_ID"),
                '',
                ['selectbox', $iblockList]
            ],
            [
                'DISCOUNT_PROPERTY',
                Loc::getMessage("FRED_DISCOUNT_OPTIONS_TAB_DISCOUNT_PROPERTY"),
                'DISCOUNT',
                ['text', 50]
            ],
            [
                'DISCOUNT_TYPE',
                Loc::getMessage("FRED_DISCOUNT_OPTIONS_TAB_DISCOUNT_TYPE"),
                'Perc',
                ['selectbox',
                    [
                        'Perc' => Loc::getMessage("FRED_DISCOUNT_OPTIONS_TAB_DISCOUNT_TYPE_PERC"),
                        'CurEach' => Loc::getMessage("FRED_DISCOUNT_OPTIONS_TAB_DISCOUNT_TYPE_CUREACH"),
                    ]
                ]
            ],
            [
                'USER_GROUPS',
                Loc::getMessage("FRED_DISCOUNT_OPTIONS_TAB_USER_GROUPS"),
                'DISCOUNT_PERCENT',
                ['multiselectbox', $groupList]
            ],
        ]
    ]
];

if($request->isPost() && check_bitrix_sessid()) {

    foreach($aTabs as $aTab){

        foreach($aTab["OPTIONS"] as $arOption) {

            if(!is_array($arOption)){

                continue;
            }

            if($arOption["note"]){

                continue;
            }

            if($request["apply"]){

                $value = $request->getPost($arOption[0]);

                $optionValue = is_array($value) ? implode(",", $value) : $value;

                try {

                    if(!Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(",", $optionValue) : $optionValue))
                        throw new ArgumentOutOfRangeException($arOption[1]);

                } catch (ArgumentOutOfRangeException $exception) {

                    echo CAdminMessage::ShowNote($exception->getMessage());
                }

            } elseif($request["default"]) {

                try {

                    if(!Option::set($module_id, $arOption[0], $arOption[2]))
                        throw new ArgumentOutOfRangeException($arOption[1]);

                } catch (ArgumentOutOfRangeException $exception) {

                    echo CAdminMessage::ShowNote($exception->getMessage());
                }
            }

        }

    }

    LocalRedirect($APPLICATION->GetCurPage() . '?mid=' . $module_id . '&lang=' . LANG);
}

$tabControl = new CAdminTabControl(
	"tabControl",
	$aTabs
);

$tabControl->Begin();
?>
<form action="<?=$APPLICATION->GetCurPage()?>?mid=<?=$module_id?>&lang=<?=LANG?>" method="post">
<?
foreach($aTabs as $aTab) {

    if($aTab["OPTIONS"]) {

        $tabControl->BeginNextTab();

        __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
    }
}
$tabControl->Buttons();
?>
<input type="submit" name="apply" value="<?=Loc::GetMessage("FRED_DISCOUNT_OPTIONS_INPUT_APPLY")?>" class="adm-btn-save" />
<input type="submit" name="default" value="<?=Loc::GetMessage("FRED_DISCOUNT_OPTIONS_INPUT_DEFAULT")?>" />
<?=bitrix_sessid_post()?>
</form>
<?php
$tabControl->End();
?>