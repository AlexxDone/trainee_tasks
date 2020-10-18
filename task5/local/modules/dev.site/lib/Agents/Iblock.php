<?php

namespace Dev\Site\Agents;


class Iblock
{
    public static function clearOldLogs()
    {
        $ibCode = 'LOG';

        $resIBlock = \CIBlock::GetList(
            array(),
            array(
                "CODE" => $ibCode,
            )
        );

        if ($logIBlock=$resIBlock->Fetch())
        {
            $resIBlockElement = \CIBlockElement::GetList(
                array(
                    'TIMESTAMP_X' => 'DESC'
                ),
                array(
                    "IBLOCK_ID" => $logIBlock["ID"]
                )
            );
            $element = 0;
            while ($arLog = $resIBlockElement->Fetch()) {
                if ($element < 10) {
                    $element++;
                }
                else {
                    \CIBlockElement::Delete($arLog['ID']);
                }
            }
        }
        return '\\' . __CLASS__ . '::' . __FUNCTION__ . '();';
    }
}
