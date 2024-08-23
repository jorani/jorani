<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.2
 * @author        Oleg Koptev, Yevhen Kyrylchenko
 */

$lang['contract_index_title'] = 'Список контрактов';
$lang['contract_index_thead_id'] = 'ID';
$lang['contract_index_thead_name'] = 'Имя';
$lang['contract_index_thead_start'] = 'Начало периода';
$lang['contract_index_thead_end'] = 'Окончание периода';
$lang['contract_index_tip_delete'] = 'удалить контракт';
$lang['contract_index_tip_edit'] = 'редактировать контракт';
$lang['contract_index_tip_entitled'] = 'Предоставляемые дни';
$lang['contract_index_tip_dayoffs'] = 'days off and weekends';
$lang['contract_index_tip_exclude_types'] = 'Exclude leave types';
$lang['contract_index_button_export'] = 'Экспортировать список';
$lang['contract_index_button_create'] = 'Создать контракт';
$lang['contract_index_popup_delete_title'] = 'Удалить контракт';
$lang['contract_index_popup_delete_description'] = 'Вы собираетесь удалить контракт. Это действие нельзя будет отменить.';
$lang['contract_index_popup_delete_confirm'] = 'Продолжить?';
$lang['contract_index_popup_delete_button_yes'] = 'Да';
$lang['contract_index_popup_delete_button_no'] = 'Нет';
$lang['contract_index_popup_entitled_title'] = 'Предоставляемые дни';
$lang['contract_index_popup_entitled_button_cancel'] = 'Отмена';
$lang['contract_index_popup_entitled_button_close'] = 'Закрыть';

$lang['contract_exclude_title'] = 'Exclude leave types from a contract';
$lang['contract_exclude_description'] = 'You cannot exclude leave types already in use (used at least one time by en employee attached to the contract) and the default leave type (set on the contract or into the configuration file).';
$lang['contract_exclude_title_included'] = 'Included leave types';
$lang['contract_exclude_title_excluded'] = 'Excluded leave types';
$lang['contract_exclude_tip_include_type'] = 'Include this leave type';
$lang['contract_exclude_tip_exclude_type'] = 'Exclude this leave type';
$lang['contract_exclude_tip_already_used'] = 'This leave type is already in use';
$lang['contract_exclude_tip_default_type'] = 'You cannot exclude the default leave type';

$lang['contract_edit_title'] = 'Редактировать контракт';
$lang['contract_edit_description'] = 'Редактировать контракт №';
$lang['contract_edit_field_name'] = 'Имя';
$lang['contract_edit_field_start_month'] = 'Месяц/Начало';
$lang['contract_edit_field_start_day'] = 'День/Начало';
$lang['contract_edit_field_end_month'] = 'Месяц/Окончание';
$lang['contract_edit_field_end_day'] = 'День/Окончание';
$lang['contract_edit_default_leave_type'] = 'Default leave type';
$lang['contract_edit_button_update'] = 'Обновить контракт';
$lang['contract_edit_button_cancel'] = 'Отмена';
$lang['contract_edit_msg_success'] = 'Контракт успешно обновлен';

$lang['contract_create_title'] = 'Создать новый контракт';
$lang['contract_create_field_name'] = 'Имя';
$lang['contract_create_field_start_month'] = 'Месяц/Начало';
$lang['contract_create_field_start_day'] = 'День/Начало';
$lang['contract_create_field_end_month'] = 'Месяц/Окончание';
$lang['contract_create_field_end_day'] = 'День/Окончание';
$lang['contract_create_default_leave_type'] = 'Default leave type';
$lang['contract_create_button_create'] = 'Создать контракт';
$lang['contract_create_button_cancel'] = 'Отмена';

$lang['contract_create_msg_success'] = 'Контракт успешно создан';
$lang['contract_delete_msg_success'] = 'Контракт успешно удален';

$lang['contract_export_title'] = 'Список контрактов';
$lang['contract_export_thead_id'] = 'ID';
$lang['contract_export_thead_name'] = 'Имя';
$lang['contract_export_thead_start'] = 'Начало периода';
$lang['contract_export_thead_end'] = 'Окончание периода';

$lang['contract_calendar_title'] = 'Календарь нерабочих дней';
$lang['contract_calendar_description'] = 'Days off and weekends are not configured by default. Click on a day to edit it individually or use the button "Series".';
$lang['contract_calendar_legend_title'] = 'Описание:';
$lang['contract_calendar_legend_allday'] = 'Весь день';
$lang['contract_calendar_legend_morning'] = 'Утро';
$lang['contract_calendar_legend_afternoon'] = 'После полудня';
$lang['contract_calendar_button_back'] = 'Вернуться к контрактам';
$lang['contract_calendar_button_series'] = 'Последовательности нерабочих дней';
$lang['contract_calendar_popup_dayoff_title'] = 'Редактировать нерабочий день';
$lang['contract_calendar_popup_dayoff_field_title'] = 'Название';
$lang['contract_calendar_popup_dayoff_field_type'] = 'Тип';
$lang['contract_calendar_popup_dayoff_type_working'] = 'Рабочий день';
$lang['contract_calendar_popup_dayoff_type_off'] = 'Все дни — нерабочие';
$lang['contract_calendar_popup_dayoff_type_morning'] = 'Утро — нерабочие';
$lang['contract_calendar_popup_dayoff_type_afternoon'] = ' После полудня — нерабочие';
$lang['contract_calendar_popup_dayoff_button_delete'] = 'Удалить';
$lang['contract_calendar_popup_dayoff_button_ok'] = 'Ок';
$lang['contract_calendar_popup_dayoff_button_cancel'] = 'Отмена';
$lang['contract_calendar_button_import'] = 'Импорт iCal';
$lang['contract_calendar_prompt_import'] = 'Ссылка на файл iCal с нерабочими днями';
$lang['contract_calendar_popup_series_title'] = 'Edit a series of days off';
$lang['contract_calendar_popup_series_field_occurences'] = 'Пометить каждый';
$lang['contract_calendar_popup_series_field_from'] = 'От';
$lang['contract_calendar_popup_series_button_current'] = 'Текущий';
$lang['contract_calendar_popup_series_field_to'] = 'До';
$lang['contract_calendar_popup_series_field_as'] = 'Как';
$lang['contract_calendar_popup_series_field_as_working'] = 'Рабочий день';
$lang['contract_calendar_popup_series_field_as_off'] = 'Все дни — нерабочие';
$lang['contract_calendar_popup_series_field_as_morning'] = 'Утро — нерабочие';
$lang['contract_calendar_popup_series_field_as_afternnon'] = ' После полудня — нерабочие';
$lang['contract_calendar_popup_series_field_title'] = 'Название';
$lang['contract_calendar_popup_series_button_ok'] = 'Ок';
$lang['contract_calendar_popup_series_button_cancel'] = 'Отмена';
$lang['contract_calendar_button_copy'] = 'Копия';
$lang['contract_calendar_copy_destination_js_msg'] = 'Необходимо выбрать контракт.';
$lang['contract_calendar_copy_msg_success'] = 'Данные успешно скопированы';
