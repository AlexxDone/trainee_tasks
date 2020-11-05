<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

if(!$USER->IsAuthorized()) {
    LocalRedirect('/');
}
else {
    $APPLICATION->IncludeComponent(
        "bitrix:main.profile",
        "",
        array(
            "CHECK_RIGHTS" => "N",
            "SEND_INFO" => "N",
            "SET_TITLE" => "Y",
            "USER_PROPERTY_NAME" => ""
        )
    );
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
