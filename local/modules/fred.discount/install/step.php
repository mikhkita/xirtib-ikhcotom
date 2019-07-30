<?php 
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;

Loc::loadMessages(__FILE__);

if(!check_bitrix_sessid()) return;

try
{
    if($APPLICATION->GetException())
        throw new SystemException($APPLICATION->GetException());

    echo CAdminMessage::ShowNote(Loc::getMessage('FRED_DISCOUNT_STEP_BEFORE') . ' ' . Loc::getMessage('FRED_DISCOUNT_STEP_AFTER'));
}
catch (SystemException $exception)
{
    CAdminMessage::ShowMessage($exception->getMessage());
}
?>

<form action="<?=$APPLICATION->GetCurPage()?>">
	<input type="hidden" name="lang" value="<?=LANG?>" />
	<input type="submit" value="<?=Loc::getMessage('FRED_DISCOUNT_STEP_SUBMIT_BACK');?>">
</form>