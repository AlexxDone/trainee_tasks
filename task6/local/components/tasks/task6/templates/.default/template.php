<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */

$this->setFrameMode(true);
?>

<div class="news">
    <div class="news-list">
        <?foreach($arResult["ITEMS"] as $arIBlock):?>
            <?foreach($arIBlock as $arItem):?>
                    <div class="news-item">
                        <div class="news-item__preview">
                            <img class="news-item__preview-img" src="<?=$arItem['PREVIEW_PICTURE']['SRC'];?>" alt=""/>
                        </div>
                        <div class="news-item__textbox">
                            <div class="news-item__title"><?=$arItem['NAME'];?></div>
                            <div class="news-item__content"><?=$arItem["PREVIEW_TEXT"];?></div>
                            <a class="news-item__button" href="<?=$arItem['DETAIL_PAGE_URL'];?>">Подробнее</a>
                        </div>
                    </div>
            <?endforeach;?>
        <?endforeach;?>
    </div>
</div>
