<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Моточки - клубочки");?>
<a href='#' class="b-cdek">выбрать пункт</a>

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


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>