<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if (!$USER->IsAdmin()) {
    LocalRedirect('/');
}

\Bitrix\Main\Loader::includeModule('iblock');
$row = 1;
$IBLOCK_ID = 99;

$el = new CIBlockElement;
$arProps = [];

$rsProp = CIBlockPropertyEnum::GetList(
    ["SORT" => "ASC", "VALUE" => "ASC"],
    ['IBLOCK_ID' => $IBLOCK_ID]
);
while ($arProp = $rsProp->Fetch()) {
    $key = trim($arProp['VALUE']);
    $arProps[$arProp['PROPERTY_CODE']][$key] = $arProp['ID'];
}

$rsElements = CIBlockElement::GetList([], ['IBLOCK_ID' => $IBLOCK_ID], false, false, ['ID']);
while ($element = $rsElements->GetNext()) {
    CIBlockElement::Delete($element['ID']);
}

if (($handle = fopen("parce.csv", "r")) !== false) {
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        if ($row <= 2) {
            $row++;
            continue;
        }
        $row++;

        $PROP['COMPANY'] = $data[0];
        $PROP['INVENTORY_NUMBER'] = $data[3];
        $PROP['UNIT'] = $data[5];
        $PROP['COUNT'] = $data[4];
        $PROP['PRICE'] = $data[6];
        $PROP['NOTE'] = $data[2];
        $PROP['YEAR_OF_RELEASE'] = '';
        $PROP['OPERATING_TIME'] = '';

        foreach ($PROP as $key => &$value) {
            $value = trim($value);
            $value = str_replace('\n', '', $value);
            if ($arProps[$key]) {
                $arSimilar = [];
                foreach ($arProps[$key] as $propKey => $propVal) {
                    $arSimilar[similar_text($value, $propKey)] = $propVal;
                    if (stripos($propKey, $value) !== false) {
                        $value = $propVal;
                        break;
                    }
                }
                if (!is_numeric($value)) {
                    ksort($arSimilar);
                    $value = array_pop($arSimilar);
                }
            }
        }

        $name = $data[1];

        if (preg_match('/,?\s((19|20)\d{2})?\s*(год|г.)\s*(выпуска|в.|вып)\s*((19|20)\d{2})?/iu', $name, $yearMatchString))
        {
            if (preg_match('/((19|20)\d{2})/', $yearMatchString[0], $yearMatch))
                $name = str_ireplace($yearMatchString[0], ' ', $name);
                $PROP['YEAR_OF_RELEASE'] = $yearMatch[0];
        }
        if (preg_match('/,?\s*(наработка|пробег)\s*(\d*\s?\d*)\s*(км.|м\/ч|км)/iu', $name, $opTimeMatchString))
        {
            $name = str_ireplace($opTimeMatchString[0], '', $name);
            $PROP['OPERATING_TIME'] = $opTimeMatchString[2].' '.$opTimeMatchString[3];
        }
        if (preg_match('/,?\d*\s*м\/ч/iu', $name, $opTimeMatchString))
        {
            $name = str_ireplace($opTimeMatchString[0], '', $name);
            $PROP['OPERATING_TIME'] = $opTimeMatchString[0];
        }
        if (preg_match('/,?\s*инв\s*номер\s*(\d*)/iu', $name, $invNumberMatchString))
        {
            $name = str_ireplace($invNumberMatchString[0], '', $name);
            $PROP['INVENTORY_NUMBER'] = $invNumberMatchString[1];
        }


        $arLoadProductArray = [
            "MODIFIED_BY" => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID" => $IBLOCK_ID,
            "PROPERTY_VALUES" => $PROP,
            "NAME" => trim($name),
            "ACTIVE" => "Y",
        ];

        if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
            echo "Добавлен элемент с ID : " . $PRODUCT_ID . "<br>";
        } else {
            echo "Error: " . $el->LAST_ERROR . '<br>';
        }
    }
    fclose($handle);
}


