<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main,
    Bitrix\Main\Loader,
    Bitrix\Iblock;

class TaskComponentClass extends CBitrixComponent {
    function getIBlockID(){
        $arResult["IBLOCK_ID"] = [];
        if (isset($this->arParams["IBLOCK_ID"]) & !empty($this->arParams["IBLOCK_ID"])) {
            if (is_numeric($this->arParams["IBLOCK_ID"])) {
                $rsIBlock = CIBlock::GetList([], [
                    "ACTIVE" => "Y",
                    "ID" => $this->arParams["IBLOCK_ID"],
                    "SITE_ID" => SITE_ID,
                ]);
            } else {
                $rsIBlock = CIBlock::GetList([], [
                    "ACTIVE" => "Y",
                    "CODE" => $this->arParams["IBLOCK_ID"],
                    "SITE_ID" => SITE_ID,
                ]);
            }
        } else {
            $rsIBlock = CIBlock::GetList([], [
                "ACTIVE" => "Y",
                "TYPE" => $this->arParams["IBLOCK_TYPE"],
                "SITE_ID" => SITE_ID,
            ]);
        }
        while ($IBlock = $rsIBlock->Fetch()) {
            $arResult["IBLOCK_ID"][] = $IBlock["ID"];
        }
        unset($IBlock);
        if (count($arResult["IBLOCK_ID"])<=0)
            throw new Exception("Инфоблок не найден");
        return $arResult;
    }

    function getElements()
    {
        $arResult = [];
        foreach ($this->arResult["IBLOCK_ID"] as $IBlock) {
            $rsElement = CIBlockElement::GetList(
                ["SORT" => "ASC"],
                ["IBLOCK_ID" => $IBlock],
                false);
            while ($element = $rsElement->Fetch()) {
                $id = (int)$element['ID'];
                Iblock\Component\Tools::getFieldImageData(
                    $element,
                    ['PREVIEW_PICTURE', 'DETAIL_PICTURE'],
                    Iblock\Component\Tools::IPROPERTY_ENTITY_ELEMENT,
                    'IPROPERTY_VALUES'
                );
                $arResult["ITEMS"][$IBlock][$id] = $element;

            }
            unset($element);
        }
        return $arResult;
    }

    function loadModules(){
        if(!Loader::includeModule("iblock"))
        {
            throw new Exception("Модуль Информационных блоков не установлен");
        }
    }

    public function executeComponent()
    {
        try {
            $this->loadModules();
            $this->arResult = array_merge($this->arResult, $this->getIBlockID());
            $this->arResult = array_merge($this->arResult, $this->getElements());
            $this->includeComponentTemplate();
        }
        catch (exception $e)
        {
            //$this->abortResultCache();
            ShowError($e->getMessage());
        }
    }
}