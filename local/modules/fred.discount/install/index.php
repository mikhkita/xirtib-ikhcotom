<?
global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));

use Bitrix\Main\ {
    Localization\Loc,
    ModuleManager,
    EventManager,
    SystemException
};

Loc::loadMessages(__FILE__);

/**
 * Class fred_discount
 */
class fred_discount extends CModule
{

    /** @var string */
    var $MODULE_ID = 'fred.discount';

    /** @var string */
    var $MODULE_VERSION;

    /** @var string */
    var $MODULE_VERSION_DATE;

    /** @var string */
    var $MODULE_NAME;

    /** @var string */
    var $MODULE_DESCRIPTION;

    /** @var string */
    var $MODULE_GROUP_RIGHTS;

    /** @var string */
    var $PARTNER_NAME;

    /** @var string */
    var $PARTNER_URI;

    /**
     * fred_discount constructor.
     */
    function fred_discount ()
    {
        if(file_exists(__DIR__ . "/version.php")){
    
            $arModuleVersion = [];
    
            require(__DIR__ . "/version.php");
        }
        
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = GetMessage("FRED_DISCOUNT_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("FRED_DISCOUNT_DESCRIPTION");
        $this->MODULE_GROUP_RIGHTS = "N";
        $this->PARTNER_NAME = GetMessage("FRED_DISCOUNT_PARTNER_NAME");
        $this->PARTNER_URI = GetMessage("FRED_DISCOUNT_PARTNER_URI");
    
        return false;
    }

    /**
     * @return bool
     */
    function DoInstall ()
    {
        global $APPLICATION;

        try
        {
            if(CheckVersion(ModuleManager::getVersion("main"), "17.00.00")) {

                $this->InstallFiles();
                $this->InstallDB();

                ModuleManager::registerModule($this->MODULE_ID);

                $this->InstallEvents();

            } else {

                throw new SystemException( Loc::getMessage("FRED_DISCOUNT_INSTALL_ERROR_VERSION"));
            }
        }
        catch (SystemException $exception)
        {
            echo $exception->getMessage();
        }

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage("FRED_DISCOUNT_INSTALL_TITLE") . ' \'' . Loc::getMessage("FRED_DISCOUNT_NAME") . '\'',
            __DIR__ . '/step.php'
        );
    
        return false;
    }

    /**
     * @return bool
     */
    function InstallFiles ()
    {
        //
        return false;
    }

    /**
     * @return bool
     */
    function InstallDB ()
    {
        //
        return false;
    }

    /**
     * @return bool
     */
    function InstallEvents ()
    {
        EventManager::getInstance()->registerEventHandler(
            "iblock",
            "OnBeforeIBlockElementAdd",
            $this->MODULE_ID,
            "FRED\\Iblock",
            "OnBeforeIBlockElementAdd"
        );

        EventManager::getInstance()->registerEventHandler(
            "iblock",
            "OnBeforeIBlockElementUpdate",
            $this->MODULE_ID,
            "FRED\\Iblock",
            "OnBeforeIBlockElementUpdate"
        );
    
        return false;
    }

    /**
     * @return bool
     */
    function DoUninstall ()
    {
        global $APPLICATION;
    
        $this->UnInstallFiles();
        $this->UnInstallDB();
        $this->UnInstallEvents();
    
        ModuleManager::unRegisterModule($this->MODULE_ID);
    
        $APPLICATION->IncludeAdminFile(
            Loc::getMessage("FRED_DISCOUNT_UNINSTALL_TITLE") . ' \'' . Loc::getMessage("FRED_DISCOUNT_NAME") . '\'',
            __DIR__ . '/unstep.php'
        );
    
        return false;
    }

    /**
     * @return bool
     */
    function UnInstallFiles ()
    {
        //
        return false;
    }

    /**
     * @return bool
     */
    function UnInstallDB ()
    {
        //
        return false;
    }

    /**
     * @return bool
     */
    function UnInstallEvents ()
    {
        EventManager::getInstance()->unRegisterEventHandler(
            "iblock",
            "OnBeforeIBlockElementAdd",
            $this->MODULE_ID,
            "FRED\\Iblock",
            "OnBeforeIBlockElementAdd"
        );

        EventManager::getInstance()->unRegisterEventHandler(
            "iblock",
            "OnBeforeIBlockElementUpdate",
            $this->MODULE_ID,
            "FRED\\Iblock",
            "OnBeforeIBlockElementUpdate"
        );
    
        return false;
    }
}
?>
