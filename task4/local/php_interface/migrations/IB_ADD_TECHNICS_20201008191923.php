<?php

namespace Sprint\Migration;


class IB_ADD_TECHNICS_20201008191923 extends Version
{

    protected $description = "Добавляет миграцию для иб Техника/Оборудование";

    public function up() {

        $helper = new HelperManager();

        $arIBlockType = array(
            'ID' => 'CONTENT_RU',
            'SECTIONS' => 'Y',
            'IN_RSS' => 'N',
            'SORT' => 100,
            'LANG' => array(
                'ru' => array(
                    'NAME' => 'Каталог',
                    'SECTION_NAME' => 'Разделы',
                    'ELEMENT_NAME' => 'Элементы'
                ),
                'en' => array(
                    'NAME' => 'Catalog',
                    'SECTION_NAME' => 'Sections',
                    'ELEMENT_NAME' => 'Elements'
                ),
            )
        );


        $helper->Iblock()->addIblockTypeIfNotExists($arIBlockType);

        $iIBlockID = $helper->Iblock()->addIblockIfNotExists(array(
            'LID' => 's1',
            'IBLOCK_TYPE_ID' => 'CONTENT_RU',
            'CODE' => 'TECHNICS',
            'NAME' => 'Техника/Оборудование'
        ));
        $arProps = array(
            array(
                'NAME' => 'Предприятие',
                'CODE' => 'COMPANY',
                'PROPERTY_TYPE' => 'L',
                'LIST_TYPE' => 'L',
                'VALUES' => [
                    [
                        'XML_ID' => 'ORSK',
                        'VALUE' => 'ОАО "Орское КАРЬЕРОУПРАВЛЕНИЕ"'
                    ],
                    [
                        'XML_ID' => 'PAVLOVSK',
                        'VALUE' => 'ОАО "Павловск Неруд"  Воронежская Обл г. Павловск'
                    ],
                    [
                        'XML_ID' => 'SORTAVALI',
                        'VALUE' => 'ОАО "Сортавальский ДСЗ" Карелия г. Сортавалы, пос Кирьявалахти'
                    ],
                    [
                        'XML_ID' => 'BIANKA',
                        'VALUE' => 'ООО "Биянковский щебеночный завод" Челябинская Обл, г. Миньяр, Станция Биянка.'
                    ],
                    [
                        'XML_ID' => 'VYAZMA',
                        'VALUE' => 'ООО "Вяземский щебеночный завод" Смоленская Обл г. Вязьма'
                    ],
                    [
                        'XML_ID' => 'IMPULS',
                        'VALUE' => 'ООО "Импульс" Краснодарский край г. Белоречинск'
                    ],
                    [
                        'XML_ID' => 'ONEGA',
                        'VALUE' => 'ООО "Онега- Неруд" Архангельская Обл Онежский район пос Покровское станц Онега СевЖд'
                    ],
                    [
                        'XML_ID' => 'MANSUROVO',
                        'VALUE' => 'ООО "Сангаллыкский Диоритовый Завод" Республика Башкортостан, Учалинский район, д Мансурово, ст Шартымка.'
                    ],
                    [
                        'XML_ID' => 'SICHEVO',
                        'VALUE' => 'ООО "Сычевский ПТК" Московская Обл. Волоколамский Район. Поселок Сычево.'
                    ],
                    [
                        'XML_ID' => 'IVANOVO',
                        'VALUE' => 'ООО "Хромцовский Карьер" Ивановская Область'
                    ],
                ]
            ),
            array(
                'NAME' => 'Год выпуска',
                'CODE' => 'YEAR_OF_RELEASE',
                'PROPERTY_TYPE' => 'S'
            ),
            array(
                'NAME' => 'Наработка',
                'CODE' => 'OPERATING_TIME',
                'PROPERTY_TYPE' => 'S'
            ),
            array(
                'NAME' => 'Внутренний номер',
                'CODE' => 'INVENTORY_NUMBER',
                'PROPERTY_TYPE' => 'S'
            ),
            array(
                'NAME' => 'Количество',
                'CODE' => 'COUNT',
                'PROPERTY_TYPE' => 'S'
            ),
            array(
                'NAME' => 'Ед. измерения',
                'CODE' => 'UNIT',
                'PROPERTY_TYPE' => 'L',
                'LIST_TYPE' => 'L',
                'VALUES' => [
                    [
                        'XML_ID' => 'PC',
                        'VALUE' => 'шт'
                    ],
                ]
            ),
            array(
                'NAME' => 'Цена',
                'CODE' => 'PRICE',
                'PROPERTY_TYPE' => 'S'
            ),
            array(
                'NAME' => 'Примечание',
                'CODE' => 'NOTE',
                'PROPERTY_TYPE' => 'S',
                'ROW_COUNT' => 3,
                'COL_COUNT' => 90
            ),
        );
        if ($iIBlockID) {
            foreach ($arProps as $arProp) {
                $helper->Iblock()->addPropertyIfNotExists($iIBlockID, $arProp);
            }
            $helper->AdminIblock()->buildElementForm($iIBlockID, [
                'Техника' => [
                    'ACTIVE',
                    'PROPERTY_COMPANY',
                    'NAME'=>'Наименование техники/оборудования',
                    'PROPERTY_YEAR_OF_RELEASE',
                    'PROPERTY_OPERATING_TIME',
                    'PROPERTY_INVENTORY_NUMBER',
                    'PROPERTY_COUNT',
                    'PROPERTY_UNIT',
                    'PROPERTY_PRICE'
                ],
                'Описание' => [
                    'PROPERTY_NOTE'
                ]
            ]);
        }

    }

    public function down() {
        $helper = new HelperManager();

        $helper->Iblock()->deleteIblockIfExists('TECHNICS', 'CONTENT_RU');

    }

}
