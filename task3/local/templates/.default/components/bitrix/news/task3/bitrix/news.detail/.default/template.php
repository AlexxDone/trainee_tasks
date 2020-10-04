<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$this->addExternalCss($templateFolder.'/css/common.css');
?>

<div class="article-card">
    <?if($arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
    <div class="article-card__title"><?=$arResult["NAME"]?></div>
    <?endif;?>

    <div class="article-card__date"><?=FormatDate($arParams["ACTIVE_DATE_FORMAT"], MakeTimeStamp($arResult["DATE_CREATE"]))?></div>
    <div class="article-card__content">
        <?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
        <div class="article-card__image sticky"><img
                    alt=""
                    src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
                    data-object-fit="cover">
        </div>
        <?endif?>
        <div class="article-card__text">
            <div class="block-content" data-anim="anim-3"><p><?echo $arResult["DETAIL_TEXT"];?></p></div>
            <a class="article-card__button" href="<?=$arResult["LIST_PAGE_URL"]?>"><?=GetMessage("T_NEWS_DETAIL_BACK")?></a></div>
    </div>
</div>