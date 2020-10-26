<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
    Bitrix\Iblock;

class TaskComponentClass extends CBitrixComponent {
    function getElements(){
        if (isset($this->arParams["IBLOCK_ID"]) & !empty($this->arParams["IBLOCK_ID"])) {
            if (is_numeric($this->arParams["IBLOCK_ID"])) {
                $rsIBlock = CIBlock::GetList([], ["ACTIVE" => "Y", "ID" => $this->arParams["IBLOCK_ID"], "SITE_ID" => SITE_ID,]);
            } else {
                $rsIBlock = CIBlock::GetList([], ["ACTIVE" => "Y", "CODE" => $this->arParams["IBLOCK_ID"], "SITE_ID" => SITE_ID,]);
            }
        } else {
            $rsIBlock = CIBlock::GetList([], ["ACTIVE" => "Y", "TYPE" => $this->arParams["IBLOCK_TYPE"], "SITE_ID" => SITE_ID,]);
        }

        if ($rsIBlock->result->num_rows<=0)
        {
            throw new Exception("Инфоблок не найден");
        }

        while ($IBlock = $rsIBlock->Fetch()) {
            $selectFields = [
                "ID",
                "IBLOCK_ID",
                "IBLOCK_SECTION_ID",
                "NAME",
                "ACTIVE_FROM",
                "TIMESTAMP_X",
                "DETAIL_PAGE_URL",
                "LIST_PAGE_URL",
                "DETAIL_TEXT",
                "DETAIL_TEXT_TYPE",
                "PREVIEW_TEXT",
                "PREVIEW_TEXT_TYPE",
                "PREVIEW_PICTURE",
            ];
            $rsElement = CIBlockElement::GetList(
                ["SORT" => "ASC"],
                ["IBLOCK_ID" => $IBlock["ID"]],
                false,
                false,
                $selectFields);
            while ($element = $rsElement->GetNext(true,false)) {
                $id = (int)$element['ID'];
                Iblock\Component\Tools::getFieldImageData(
                    $element,
                    ['PREVIEW_PICTURE', 'DETAIL_PICTURE'],
                    Iblock\Component\Tools::IPROPERTY_ENTITY_ELEMENT,
                    'IPROPERTY_VALUES'
                );
                $arResult["ITEMS"][$IBlock["ID"]][$id] = $element;

            }
            unset($IBlock);
            unset($element);
        }
        return isset($arResult) ? $arResult : [];
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
            $this->arResult = array_merge($this->arResult, $this->getElements());
            $this->includeComponentTemplate();
        }
        catch (exception $e)
        {
            $this->abortResultCache();
            ShowError($e->getMessage());
        }
    }
}