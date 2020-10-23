<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
    Bitrix\Iblock;

class TaskComponentClass extends CBitrixComponent {
    function getElements($arParams)
    {
        $arResult = [];
        $arResult["ITEMS"] = [];

        if (isset($arParams["IBLOCK_ID"]) & !empty($arParams["IBLOCK_ID"])) {
            if (is_numeric($arParams["IBLOCK_ID"])) {
                $rsIBlock = CIBlock::GetList([], [
                        "ACTIVE" => "Y",
                        "ID" => $arParams["IBLOCK_ID"],
                        "SITE_ID" => SITE_ID,
                ]);
            } else {
                $rsIBlock = CIBlock::GetList([], [
                    "ACTIVE" => "Y",
                    "CODE" => $arParams["IBLOCK_ID"],
                    "SITE_ID" => SITE_ID,
                ]);
            }
        } else {
            $rsIBlock = CIBlock::GetList([], [
                "ACTIVE" => "Y",
                "TYPE" => $arParams["IBLOCK_TYPE"],
                "SITE_ID" => SITE_ID,
            ]);
        }
        while ($IBlock = $rsIBlock->Fetch()) {
            $rsElement = CIBlockElement::GetList(
                ["SORT" => "ASC"],
                ["IBLOCK_ID" => $IBlock["ID"]],
                false);

            while ($element = $rsElement->Fetch()) {
                $id = (int)$element['ID'];
                Iblock\Component\Tools::getFieldImageData(
                    $element,
                    ['PREVIEW_PICTURE', 'DETAIL_PICTURE'],
                    Iblock\Component\Tools::IPROPERTY_ENTITY_ELEMENT,
                    'IPROPERTY_VALUES'
                );
                $arResult["ITEMS"][$IBlock["ID"]][$id] = $element;

            }
            unset($element);
        }
        return $arResult;
    }

    public function executeComponent()
    {
        if(!Loader::includeModule("iblock"))
        {
            $this->abortResultCache();
            ShowError("Модуль Информационных блоков не установлен");
            return;
        }
        $this->arResult = array_merge($this->arResult, $this->getElements($this->arParams));
        $this->includeComponentTemplate();
    }
}