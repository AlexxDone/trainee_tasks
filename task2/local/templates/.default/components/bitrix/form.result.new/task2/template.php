<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->addExternalCss($templateFolder.'/css/common.css');
?>
<?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>
    <div class="contact-form">
        <div class="contact-form__head">
            <div class="contact-form__head-title"><?=$arResult["FORM_TITLE"]?></div>
            <div class="contact-form__head-text"><?=$arResult["FORM_DESCRIPTION"]?></div>
        </div>
        <?=str_replace('<form','<form class="contact-form__form"', $arResult['FORM_HEADER'])?>

            <div class="contact-form__form-inputs">
                <?foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion):
                    if ($FIELD_SID!="message"):?>
                    <div class="input contact-form__input"><label class="input__label" for="medicine_name">
                            <div class="input__label-text"><?=$arQuestion["CAPTION"].($arQuestion["REQUIRED"]=="Y"?"*":"");?></div>
                            <input class="input__input" type="<?=$arQuestion["STRUCTURE"][0]["FIELD_TYPE"];?>"
                                   <? if ($FIELD_SID=="tel_number"):?>
                                   data-inputmask="'mask': '+79999999999', 'clearIncomplete': 'true'" maxlength="12"
                                   x-autocompletetype="phone-full" name="medicine_phone"
                                   <?endif?>
                                   id="<?="form"."_".$arQuestion["STRUCTURE"][0]["FIELD_TYPE"]."_".$arQuestion["STRUCTURE"][0]["ID"]?>"
                                   name="<?="form"."_".$arQuestion["STRUCTURE"][0]["FIELD_TYPE"]."_".$arQuestion["STRUCTURE"][0]["ID"]?>"
                                   <?=$arQuestion["REQUIRED"]=="Y"?"required":""?>>
                            <div class="input__notification"></div>
                        </label></div>
                    <?endif;
                endforeach;?>
            </div>
            <?if (($message = $arResult["QUESTIONS"]["message"])!=NULL):?>
            <div class="contact-form__form-message">
                <div class="input"><label class="input__label" for="medicine_message">
                        <div class="input__label-text"><?=$message["CAPTION"]?></div>
                        <textarea class="input__input" type="text"
                                  id="<?="form"."_".$message["STRUCTURE"][0]["FIELD_TYPE"]."_".$arQuestion["STRUCTURE"][0]["ID"]?>"
                                  name="<?="form"."_".$message["STRUCTURE"][0]["FIELD_TYPE"]."_".$arQuestion["STRUCTURE"][0]["ID"]?>"
                                  <?=$arQuestion["REQUIRED"]=="Y"?"required":""?>>
                        </textarea>
                        <div class="input__notification"></div>
                    </label></div>
            </div>
            <? endif ?>
            <div class="contact-form__bottom">
                <div class="contact-form__bottom-policy">Нажимая &laquo;Отправить&raquo;, Вы&nbsp;подтверждаете, что
                    ознакомлены, полностью согласны и&nbsp;принимаете условия &laquo;Согласия на&nbsp;обработку персональных
                    данных&raquo;.
                </div>
                <button name="web_form_submit" class="form-button contact-form__bottom-button" data-success="Отправлено"
                        data-error="Ошибка отправки" value="submit">
                    <div class="form-button__title">Оставить заявку</div>
                </button>
            </div>
    </div>