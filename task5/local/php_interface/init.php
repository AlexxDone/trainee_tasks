<?php
if (CModule::IncludeModule('dev.site'))
{
    AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("Dev\Site\Handlers\IBlock", "addLog"));
    AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("Dev\Site\Handlers\IBlock", "addLog"));
}
?>