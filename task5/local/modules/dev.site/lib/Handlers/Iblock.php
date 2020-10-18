<?php

namespace Dev\Site\Handlers;


class Iblock
{
    function addLog($arFields)
    {
        $iBlock = \CIBlock::GetByID($arFields["IBLOCK_ID"])->fetch();
        if ($iBlock["CODE"]!="LOG"){
            $ib = new \CIBlock;
            $ibCode = 'LOG';
            $ibType = 'content';

            $resIBlock = \CIBlock::GetList(
                array(),
                array(
                    "CODE" => $ibCode,
                    "TYPE" => $ibType
                )
            );

            if (!($logIBlock = $resIBlock->Fetch())) {

                //Создание ИБ LOG

                $arFieldsIB = array(
                    "ACTIVE" => "Y",
                    "NAME" => 'Лог',
                    "CODE" => $ibCode,
                    "IBLOCK_TYPE_ID" => $ibType,
                    "SITE_ID" => "s1",
                    "GROUP_ID" => array("2" => "R"),
                    "FIELDS" => array(
                        "CODE" => array(
                            "IS_REQUIRED" => "N",
                        )
                    )
                );
                $logIBlock = \CIBlock::GetByID($ib->Add($arFieldsIB))->fetch();
            }

            $resSection = \CIBlockSection::GetList(
                array(),
                array(
                    "NAME" => $iBlock["ID"],
                )
            );

            //Создание разделов в ИБ LOG

            if (!($ibSectionID = $resSection->Fetch())){
                $ibSection = new \CIBlockSection;
                $ibSFields = Array(
                    "ACTIVE" => "Y",
                    "IBLOCK_ID" => $logIBlock["ID"],
                    "NAME" => $iBlock["ID"],
                );
                $ibSectionID = \CIBlockSection::GetByID($ibSection->Add($ibSFields))->fetch();
                var_dump($ibSectionID);
            }

            //Рекурсивный поиск имен разделов

            if ($element = \CIBlockElement::GetByID($arFields["ID"])->fetch())
            {
                $elementName = $element["NAME"];
                $iBlockName = $iBlock["NAME"];
                $sectionTree = [];

                while (($sectionID = $element["IBLOCK_SECTION_ID"])!=null)
                {
                    $element = \CIBlockSection::GetByID($sectionID)->fetch();
                    array_unshift($sectionTree, $element["NAME"]);
                }

                array_unshift($sectionTree, $iBlockName);
                $sectionTree[] = $elementName;
                $sectionTreeStr = implode($sectionTree, " -> ");
            }
            else
                return;

            //Создание элементов в ИБ LOG

            $date = new \Bitrix\Main\Type\DateTime();
            $logElement = new \CIBlockElement;
            $arLoadLogElement = [
                "ACTIVE_FROM" => $date,
                "IBLOCK_SECTION_ID" => $ibSectionID["ID"],
                "IBLOCK_ID" => $logIBlock["ID"],
                "NAME" => $arFields["ID"],
                "PREVIEW_TEXT" => $sectionTreeStr,
                "ACTIVE" => "Y",
            ];
            $logElement->Add($arLoadLogElement);
        }
    }

}
