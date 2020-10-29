<?php
use \Bitrix\Main\Localization\Loc;

IncludeModuleLangFile(__FILE__);

class CustomTypeCProp extends CUserTypeString
{
    private static $showedCss = false;
    private static $showedJs = false;

    function GetUserTypeDescription()
    {
        return array(
            "USER_TYPE_ID" => "customcprop",
            "CLASS_NAME" => "CustomTypeCProp",
            "DESCRIPTION" => GetMessage("IEX_CPROP_DESC"),
            "BASE_TYPE" => "string",
        );
    }

    function GetEditFormHTML($arUserField, $arHtmlControl)
    {
        $hideText = Loc::getMessage('IEX_CPROP_HIDE_TEXT');
        $clearText = Loc::getMessage('IEX_CPROP_CLEAR_TEXT');
        self::showCss();
        self::showJs();

        if(!empty($arUserField['SETTINGS'])){
            $arFields = self::prepareSetting($arUserField['SETTINGS']);
        }
        else{
            return '<span>'.Loc::getMessage('IEX_CPROP_ERROR_INCORRECT_SETTINGS').'</span>';
        }

        $result = '';
        $result .= '<div class="mf-gray"><a class="cl mf-toggle">'.$hideText.'</a>';
        if($arUserField['MULTIPLE'] === 'Y'){
            $result .= ' | <a class="cl mf-delete">'.$clearText.'</a></div>';
        }
        $result .= '<table class="mf-fields-list active">';

        $arUserField['VALUE'] = json_decode($arUserField['VALUE'], true);

        foreach ($arFields as $code => $arItem){
            if($arItem['TYPE'] === 'string'){
                $result .= self::showString($code, $arItem['TITLE'], $arUserField);
            }
            else if($arItem['TYPE'] === 'text'){
                $result .= self::showTextarea($code, $arItem['TITLE'], $arUserField);
            }
            else if($arItem['TYPE'] === 'date'){
                $result .= self::showDate($code, $arItem['TITLE'], $arUserField);
            }
            else if($arItem['TYPE'] === 'element'){
                $result .= self::showBindElement($code, $arItem['TITLE'], $arUserField);
            }
            else if($arItem['TYPE'] === 'html'){
                $result .= self::showHtmlEditor($code, $arItem['TITLE'], $arUserField);
            }
        }

        $result .= '</table>';

        return $result;
    }

    function GetSettingsHTML($arUserField, $arHtmlControl, $bVarsFromForm){

        $btnAdd = Loc::getMessage('IEX_CPROP_SETTING_BTN_ADD');

        self::showJsForSetting($arHtmlControl["NAME"]);
        self::showCssForSetting();

        $result = '<tr><td colspan="2" align="center">
            <table id="many-fields-table" class="many-fields-table internal">        
                <tr valign="top" class="heading mf-setting-title">
                   <td>XML_ID</td>
                   <td>'.Loc::getMessage('IEX_CPROP_SETTING_FIELD_TITLE').'</td>
                   <td>'.Loc::getMessage('IEX_CPROP_SETTING_FIELD_SORT').'</td>
                   <td>'.Loc::getMessage('IEX_CPROP_SETTING_FIELD_TYPE').'</td>
                </tr>';

        $arSetting = self::prepareSetting($arUserField['SETTINGS']);

        if(!empty($arSetting)){
            foreach ($arSetting as $code => $arItem) {
                $result .= '
                       <tr valign="top">
                           <td><input type="text" class="inp-code" size="20" value="'.$code.'"></td>
                           <td><input type="text" class="inp-title" size="35" name="'.$arHtmlControl["NAME"].'['.$code.'_TITLE]" value="'.$arItem['TITLE'].'"></td>
                           <td><input type="text" class="inp-sort" size="5" name="'.$arHtmlControl["NAME"].'['.$code.'_SORT]" value="'.$arItem['SORT'].'"></td>
                           <td>
                                <select class="inp-type" name="'.$arHtmlControl["NAME"].'['.$code.'_TYPE]">
                                    '.self::getOptionList($arItem['TYPE']).'
                                </select>
                           </td>
                       </tr>';
            }
        }

        $result .= '
               <tr valign="top">
                    <td><input type="text" class="inp-code" size="20"></td>
                    <td><input type="text" class="inp-title" size="35"></td>
                    <td><input type="text" class="inp-sort" size="5" value="500"></td>
                    <td>
                        <select class="inp-type"> '.self::getOptionList().'</select>
                    </td>
               </tr>
             </table>

                <tr>
                    <td colspan="2" style="text-align: center;">
                        <input type="button" value="'.$btnAdd.'" onclick="addNewRows()">
                    </td>
                </tr>
                </td></tr>';

        return $result;
    }

    function PrepareSettings($arProperty)
    {
        $result = [];
        if(!empty($arProperty['SETTINGS'])){
            foreach ($arProperty['SETTINGS'] as $code => $value) {
                $result[$code] = $value;
            }
        }
        return $result;
    }

    private static function showString($code, $title, $arValue)
    {
        $result = '';

        $v = !empty($arValue['VALUE'][$code]) ? $arValue['VALUE'][$code] : '';
        $result .= '<tr>
                    <td align="right">'.$title.': </td>
                    <td><input type="text" value="'.$v.'" name="'.$arValue['FIELD_NAME'].'['.$code.']'.'"/></td>
                </tr>';

        return $result;

    }

    public static function showTextarea($code, $title, $arValue)
    {
        $result = '';

        $v = !empty($arValue['VALUE'][$code]) ? $arValue['VALUE'][$code] : '';
        $result .= '<tr>
                    <td align="right" valign="top">'.$title.': </td>
                    <td><textarea rows="8" name="'.$arValue['FIELD_NAME'].'['.$code.']">'.$v.'</textarea></td>
                </tr>';

        return $result;
    }

    public static function showDate($code, $title, $arValue)
    {
        $result = '';

        $v = !empty($arValue['VALUE'][$code]) ? $arValue['VALUE'][$code] : '';
        $result .= '<tr>
                        <td align="right" valign="top">'.$title.': </td>
                        <td>
                            <table>
                                <tr>
                                    <td style="padding: 0;">
                                        <div class="adm-input-wrap adm-input-wrap-calendar">
                                            <input class="adm-input adm-input-calendar" type="text" name="'.$arValue['FIELD_NAME'].'['.$code.']" size="23" value="'.$v.'">
                                            <span class="adm-calendar-icon"
                                                  onclick="BX.calendar({node: this, field:\''.$arValue['FIELD_NAME'].'['.$code.']\', form: \'\', bTime: true, bHideTime: false});"></span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>';

        return $result;
    }

    public static function showBindElement($code, $title, $arValue)
    {
        $result = '';

        $v = !empty($arValue['VALUE'][$code]) ? $arValue['VALUE'][$code] : '';

        $elUrl = '';
        if(!empty($v)){
            $arElem = \CIBlockElement::GetList([], ['ID' => $v],false, ['nPageSize' => 1], ['ID', 'IBLOCK_ID', 'IBLOCK_TYPE_ID', 'NAME'])->Fetch();
            if(!empty($arElem)){
                $elUrl .= '<a target="_blank" href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$arElem['IBLOCK_ID'].'&ID='.$arElem['ID'].'&type='.$arElem['IBLOCK_TYPE_ID'].'">'.$arElem['NAME'].'</a>';
            }
        }

        $result .= '<tr>
                    <td align="right">'.$title.': </td>
                    <td>
                        <input name="'.$arValue['FIELD_NAME'].'['.$code.']" id="'.$arValue['FIELD_NAME'].'['.$code.']" value="'.$v.'" size="8" type="text" class="mf-inp-bind-elem">
                        <input type="button" value="..." onClick="jsUtils.OpenWindow(\'/bitrix/admin/iblock_element_search.php?lang=ru&IBLOCK_ID=0&n='.$arValue['FIELD_NAME'].'&k='.$code.'\', 900, 700);">&nbsp;
                        <span>'.$elUrl.'</span>
                    </td>
                </tr>';

        return $result;
    }

    public static function showHtmlEditor($code, $title, $arValue)
    {
        $v = !empty($arValue['VALUE'][$code]) ? $arValue['VALUE'][$code] : '';
        $oldName = $arValue['FIELD_NAME'].'['.$code.']';
        $name = preg_replace("/[^a-zA-Z0-9_:\.]/is", "_", $oldName);
        ob_start();
        CFileMan::AddHTMLEditorFrame(
            $name,
            $v,
            $name."_TYPE",
            "html",
            array(
                'height' => 120,
            )
        );
        $result = '<tr>
                    <td align="right" valign="top">'.$title.': </td>
                    <td>'.preg_replace("/name=\".*?\"/","name=\"$oldName\"",ob_get_contents()).'</td>
                </tr>';
        ob_end_clean();
        return $result;
    }

    private static function showCss()
    {
        if(!self::$showedCss) {
            self::$showedCss = true;
            ?>
            <style>
                .cl {cursor: pointer;}
                .mf-gray {color: #797777;}
                .mf-fields-list {display: none; padding-top: 10px; margin-bottom: 10px!important; margin-left: -300px!important; border-bottom: 1px #e0e8ea solid!important;}
                .mf-fields-list.active {display: block;}
                .mf-fields-list td {padding-bottom: 5px;}
                .mf-fields-list td:first-child {width: 300px; color: #616060;}
                .mf-fields-list td:last-child {padding-left: 5px;}
                .mf-fields-list input[type="text"] {width: 350px!important;}
                .mf-fields-list textarea {min-width: 350px; max-width: 650px; color: #000;}
                .mf-fields-list img {max-height: 150px; margin: 5px 0;}
                .mf-img-table {background-color: #e0e8e9; color: #616060; width: 100%;}
                .mf-fields-list input[type="text"].adm-input-calendar {width: 170px!important;}
                .mf-file-name {word-break: break-word; padding: 5px 5px 0 0; color: #101010;}
                .mf-fields-list input[type="text"].mf-inp-bind-elem {width: unset!important;}
            </style>
            <?
        }
    }

    private static function showJs()
    {
        $showText = Loc::getMessage('IEX_CPROP_SHOW_TEXT');
        $hideText = Loc::getMessage('IEX_CPROP_HIDE_TEXT');

        CJSCore::Init(array("jquery"));
        if(!self::$showedJs) {
            self::$showedJs = true;
            ?>
            <script>
                $(document).on('click', 'a.mf-toggle', function (e) {
                    e.preventDefault();

                    var table = $(this).closest('tr').find('table.mf-fields-list');
                    $(table).toggleClass('active');
                    if($(table).hasClass('active')){
                        $(this).text('<?=$hideText?>');
                    }
                    else{
                        $(this).text('<?=$showText?>');
                    }
                });

                $(document).on('click', 'a.mf-delete', function (e) {
                    e.preventDefault();

                    var textInputs = $(this).closest('tr').find('input[type="text"]');
                    $(textInputs).each(function (i, item) {
                        $(item).val('');
                    });

                    var textarea = $(this).closest('tr').find('textarea');
                    $(textarea).each(function (i, item) {
                        $(item).text('');
                    });

                    var checkBoxInputs = $(this).closest('tr').find('input[type="checkbox"]');
                    $(checkBoxInputs).each(function (i, item) {
                        $(item).attr('checked', 'checked');
                    });

                    $(this).closest('tr').hide('slow');
                });
            </script>
            <?
        }
    }

    private static function showJsForSetting($inputName)
    {
        CJSCore::Init(array("jquery"));
        ?>
        <script>
            function addNewRows() {
                $("#many-fields-table").append('' +
                    '<tr valign="top">' +
                    '<td><input type="text" class="inp-code" size="20"></td>' +
                    '<td><input type="text" class="inp-title" size="35"></td>' +
                    '<td><input type="text" class="inp-sort" size="5" value="500"></td>' +
                    '<td><select class="inp-type"><?=self::getOptionList()?></select></td>' +
                    '</tr>');
            }


            $(document).on('change', '.inp-code', function(){
                var code = $(this).val();

                if(code.length <= 0){
                    $(this).closest('tr').find('input.inp-title').removeAttr('name');
                    $(this).closest('tr').find('input.inp-sort').removeAttr('name');
                    $(this).closest('tr').find('select.inp-type').removeAttr('name');
                }
                else{
                    $(this).closest('tr').find('input.inp-title').attr('name', '<?=$inputName?>[' + code + '_TITLE]');
                    $(this).closest('tr').find('input.inp-sort').attr('name', '<?=$inputName?>[' + code + '_SORT]');
                    $(this).closest('tr').find('select.inp-type').attr('name', '<?=$inputName?>[' + code + '_TYPE]');
                }
            });

            $(document).on('input', '.inp-sort', function(){
                var num = $(this).val();
                $(this).val(num.replace(/[^0-9]/gim,''));
            });
        </script>
        <?
    }

    private static function showCssForSetting()
    {
        if(!self::$showedCss) {
            self::$showedCss = true;
            ?>
            <style>
                .many-fields-table {margin: 0 auto; /*display: inline;*/}
                .mf-setting-title td {text-align: center!important; border-bottom: unset!important;}
                .many-fields-table td {text-align: center;}
                .many-fields-table > input, .many-fields-table > select{width: 90%!important;}
                .inp-sort{text-align: center;}
                .inp-type{min-width: 125px;}
            </style>
            <?
        }
    }

    private static function prepareSetting($arSetting)
    {
        $arResult = [];

        foreach ($arSetting as $key => $value){
            if(strstr($key, '_TITLE') !== false) {
                $code = str_replace('_TITLE', '', $key);
                $arResult[$code]['TITLE'] = $value;
            }
            else if(strstr($key, '_SORT') !== false) {
                $code = str_replace('_SORT', '', $key);
                $arResult[$code]['SORT'] = $value;
            }
            else if(strstr($key, '_TYPE') !== false) {
                $code = str_replace('_TYPE', '', $key);
                $arResult[$code]['TYPE'] = $value;
            }
        }

        if(!function_exists('cmp')){
            function cmp($a, $b)
            {
                if ($a['SORT'] == $b['SORT']) {
                    return 0;
                }
                return ($a['SORT'] < $b['SORT']) ? -1 : 1;
            }
        }

        uasort($arResult, 'cmp');

        return $arResult;
    }

    private static function getOptionList($selected = 'string')
    {
        $result = '';
        $arOption = [
            'string' => Loc::getMessage('IEX_CPROP_FIELD_TYPE_STRING'),
            'text' => Loc::getMessage('IEX_CPROP_FIELD_TYPE_TEXT'),
            'date' => Loc::getMessage('IEX_CPROP_FIELD_TYPE_DATE'),
            'element' => Loc::getMessage('IEX_CPROP_FIELD_TYPE_ELEMENT'),
            'html' => Loc::getMessage('IEX_CPROP_FIELD_TYPE_HTML')
        ];

        foreach ($arOption as $code => $name){
            $s = '';
            if($code === $selected){
                $s = 'selected';
            }

            $result .= '<option value="'.$code.'" '.$s.'>'.$name.'</option>';
        }

        return $result;
    }

    function OnBeforeSave($arUserField, $value)
    {
        if (
            is_array($value)
        ) {
            return json_encode($value);
        } else {
            return '';
        }
    }
}
?>