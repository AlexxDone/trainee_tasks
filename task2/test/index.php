<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("test");
?>

<?$APPLICATION->IncludeComponent("bitrix:form.result.new" , 'task2', Array(
	"SEF_MODE" => "Y",	// Включить поддержку ЧПУ
		"WEB_FORM_ID" => "3",	// ID веб-формы
		"LIST_URL" => "",	// Страница со списком результатов
		"EDIT_URL" => "",	// Страница редактирования результата
		"SUCCESS_URL" => "/success.php",	// Страница с сообщением об успешной отправке
		"CHAIN_ITEM_TEXT" => "",	// Название дополнительного пункта в навигационной цепочке
		"CHAIN_ITEM_LINK" => "",	// Ссылка на дополнительном пункте в навигационной цепочке
		"IGNORE_CUSTOM_TEMPLATE" => "Y",	// Игнорировать свой шаблон
		"USE_EXTENDED_ERRORS" => "Y",	// Использовать расширенный вывод сообщений об ошибках
		"CACHE_TYPE" => "A",	// Тип кеширования
		"CACHE_TIME" => "3600",	// Время кеширования (сек.)
		"SEF_FOLDER" => "/",	// Каталог ЧПУ (относительно корня сайта)
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>