<?php namespace FRED;

use Bitrix\Main\ {
    Config\Option,
    Localization\Loc,
    Loader,
    LoaderException
};

use Bitrix\Catalog\ProductTable;

Loc::loadLanguageFile( __FILE__ );

/**
 * Class Iblock
 * @package FD
 */
class Iblock
{

    /**
     * @throws LoaderException
     */
    public static function includeIblockModule()
    {
        if ( !Loader::includeModule('iblock') )
            throw new LoaderException( Loc::getMessage("MODULE_IBLOCK_ERROR") );
    }

    /**
     * @throws LoaderException
     */
    public static function includeSaleModule()
    {
        if ( !Loader::includeModule('sale') )
            throw new LoaderException( Loc::getMessage("MODULE_SALE_ERROR") );
    }

    /**
     * @param $arFields
     * @throws LoaderException
     */
    public static function OnBeforeIBlockElementAdd (&$arFields)
    {
        self::setDiscount($arFields);
    }

    /**
     * @param $arFields
     * @throws LoaderException
     */
    public static function OnBeforeIBlockElementUpdate (&$arFields)
    {
        self::setDiscount($arFields);
    }

    /**
     * @param $arFields
     * @throws LoaderException
     */
    public static function setDiscount (&$arFields)
    {
        self::includeIblockModule();
        self::includeSaleModule();

        $MODULE_ID = 'fred.discount';

        $SITE_ID = Option::get($MODULE_ID, 'SITE_ID');
        $IBLOCK_ID = Option::get($MODULE_ID, 'IBLOCK_ID');
        $DISCOUNT_PROPERTY = Option::get($MODULE_ID, 'DISCOUNT_PROPERTY');
        $DISCOUNT_TYPE = Option::get($MODULE_ID, 'DISCOUNT_TYPE');
        $USER_GROUPS = Option::get($MODULE_ID, 'USER_GROUPS');
        $arProperty = [];
        $arrEnumValue = '';

        if($arFields['IBLOCK_ID'] == $IBLOCK_ID || $arFields['IBLOCK_ID'] == 2) {
            $dbProps = \CIBlock::GetProperties(
                $arFields['IBLOCK_ID'],
                [],
                [
                    'CODE' => $DISCOUNT_PROPERTY
                ]
            );
            if($arProps = $dbProps->Fetch()) {
                $arProperty = &$arProps;
            }

            switch ($arProperty['PROPERTY_TYPE']) {
                case 'S':
                    $arrEnumValue = current(array_column($arFields['PROPERTY_VALUES'][$arProperty['ID']], 'VALUE'));

                    break;
                case 'L':
                    $arrEnum = \CIBlockPropertyEnum::GetByID(current(array_column($arFields['PROPERTY_VALUES'][$arProperty['ID']], 'VALUE')));
                    $arrEnumValue = $arrEnum['VALUE'];

                    break;
            }

            $productName = "[".$arFields['ID']."] Discount";

            $arPrice = \CPrice::GetBasePrice($arFields['ID']);

            if($arrEnumValue) {
                $rules = [
                    'CLASS_ID' => 'CondGroup',
                    'DATA' => [
                        'All' => 'AND',
                        'True' => 'True',
                    ],
                    'CHILDREN' => [
                        [
                            'CLASS_ID' => 'ActSaleBsktGrp',
                            'DATA' => [
                                'Type' => 'Discount',
                                'Value' => $arrEnumValue,
                                'Unit' => $DISCOUNT_TYPE,
                                'Max' => 0,
                                'All' => 'OR',
                                'True' => 'True',
                            ],
                            'CHILDREN' => [
                                [],
                                [
                                    'CLASS_ID' => 'ActSaleSubGrp',
                                    'DATA' => [
                                        'All' => 'AND',
                                        'True' => 'True',
                                    ],
                                    'CHILDREN' => [
                                        [
                                            'CLASS_ID' => 'CondIBElement',
                                            'DATA' => [
                                                'logic' => 'Equal',
                                                'value' => [
                                                    $arFields['ID']
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ];

                $arDiscountFields = [
                    'LID' => $SITE_ID,
                    'NAME' => $productName,
                    'ACTIVE' => 'Y',
                    'CURRENCY' => $arPrice['CURRENCY'],
                    'CONDITIONS' => $rules,
                    'ACTIONS' => $rules,
                    'USER_GROUPS' => is_array(explode(",", $USER_GROUPS)) ? explode(",", $USER_GROUPS) : [$USER_GROUPS],
                    'LAST_DISCOUNT' => 'N'
                ];

                $dbDiscount = \CSaleDiscount::GetList(
                    [],
                    ['NAME' => $productName],
                    false,
                    false,
                    ['ID']
                );

                if($discount = $dbDiscount->fetch())
                    \CSaleDiscount::Update($discount['ID'], $arDiscountFields);
                else
                    \CSaleDiscount::Add($arDiscountFields);

            } else {

                $dbDiscount = \CSaleDiscount::GetList(
                    [],
                    ['NAME' => $productName],
                    false,
                    false,
                    ['ID']
                );

                if($discount = $dbDiscount->fetch()) {
                    \CSaleDiscount::Delete($discount['ID']);
                }
            }
        }
    }
}
