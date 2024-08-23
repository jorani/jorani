<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license     http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link          https://github.com/bbalet/jorani
 * @since        0.4.4
 * @author      Yevhen Kyrylchenko
 */

$lang['contract_index_title'] = 'Список контрактів';
$lang['contract_index_thead_id'] = 'ID';
$lang['contract_index_thead_name'] = 'Назва';
$lang['contract_index_thead_start'] = 'Початок періоду';
$lang['contract_index_thead_end'] = 'Закінчення періоду';
$lang['contract_index_tip_delete'] = 'видалити контракт';
$lang['contract_index_tip_edit'] = 'редагувати контракт';
$lang['contract_index_tip_entitled'] = 'надані дні';
$lang['contract_index_tip_dayoffs'] = 'days off and weekends';
$lang['contract_index_tip_exclude_types'] = 'Exclude leave types';
$lang['contract_index_button_export'] = 'Експортувати список';
$lang['contract_index_button_create'] = 'Створити контракт';
$lang['contract_index_popup_delete_title'] = 'Видалити контракт';
$lang['contract_index_popup_delete_description'] = 'Ви збираєтесь видалити контракт. Цю дію не можна буде скасувати.';
$lang['contract_index_popup_delete_confirm'] = 'Продовжити?';
$lang['contract_index_popup_delete_button_yes'] = 'Так';
$lang['contract_index_popup_delete_button_no'] = 'Ні';
$lang['contract_index_popup_entitled_title'] = 'Надані дні';
$lang['contract_index_popup_entitled_button_cancel'] = 'Скасувати';
$lang['contract_index_popup_entitled_button_close'] = 'Замкнути';

$lang['contract_exclude_title'] = 'Exclude leave types from a contract';
$lang['contract_exclude_description'] = 'You cannot exclude leave types already in use (used at least one time by en employee attached to the contract) and the default leave type (set on the contract or into the configuration file).';
$lang['contract_exclude_title_included'] = 'Included leave types';
$lang['contract_exclude_title_excluded'] = 'Excluded leave types';
$lang['contract_exclude_tip_include_type'] = 'Include this leave type';
$lang['contract_exclude_tip_exclude_type'] = 'Exclude this leave type';
$lang['contract_exclude_tip_already_used'] = 'This leave type is already in use';
$lang['contract_exclude_tip_default_type'] = 'You cannot exclude the default leave type';

$lang['contract_edit_title'] = 'Редагувати контракт';
$lang['contract_edit_description'] = 'Редагувати контракт №';
$lang['contract_edit_field_name'] = 'Назва';
$lang['contract_edit_field_start_month'] = 'Місяць/Початок';
$lang['contract_edit_field_start_day'] = 'День/Початок';
$lang['contract_edit_field_end_month'] = 'Місяць/Закінчення';
$lang['contract_edit_field_end_day'] = 'День/Закінчення';
$lang['contract_edit_default_leave_type'] = 'Default leave type';
$lang['contract_edit_button_update'] = 'Оновити контракт';
$lang['contract_edit_button_cancel'] = 'Скасувати';
$lang['contract_edit_msg_success'] = 'Контракт був успішно оновлений';

$lang['contract_create_title'] = 'Створити новий контракт';
$lang['contract_create_field_name'] = 'Назва';
$lang['contract_create_field_start_month'] = 'Місяць/Початок';
$lang['contract_create_field_start_day'] = 'День/Початок';
$lang['contract_create_field_end_month'] = 'Місяць/Закінчення';
$lang['contract_create_field_end_day'] = 'День/Закінчення';
$lang['contract_create_default_leave_type'] = 'Default leave type';
$lang['contract_create_button_create'] = 'Створити контракт';
$lang['contract_create_button_cancel'] = 'Скасувати';
$lang['contract_create_msg_success'] = 'Контракт був успішно створений';
$lang['contract_delete_msg_success'] = 'Контракт був успішно видалений';

$lang['contract_export_title'] = 'Список контрактів';
$lang['contract_export_thead_id'] = 'ID';
$lang['contract_export_thead_name'] = 'Назва';
$lang['contract_export_thead_start'] = 'Початок періоду';
$lang['contract_export_thead_end'] = 'Закінчення періоду';

$lang['contract_calendar_title'] = 'Календар неробочих днів';
$lang['contract_calendar_description'] = 'Вихідні та неробочі дні не задані по замовчуванню. Натисніть на день для індивідуального редагування або використовуйте кнопку "Послідовності"';
$lang['contract_calendar_legend_title'] = 'Позначення';
$lang['contract_calendar_legend_allday'] = 'Всі дні';
$lang['contract_calendar_legend_morning'] = 'Ранок';
$lang['contract_calendar_legend_afternoon'] = 'Після обіду';
$lang['contract_calendar_button_back'] = 'Назад до контрактів';
$lang['contract_calendar_button_series'] = 'Серії неробочих днів';
$lang['contract_calendar_popup_dayoff_title'] = 'Редагувати неробочий день';
$lang['contract_calendar_popup_dayoff_field_title'] = 'Назва';
$lang['contract_calendar_popup_dayoff_field_type'] = 'Тип';
$lang['contract_calendar_popup_dayoff_type_working'] = 'Робочий день';
$lang['contract_calendar_popup_dayoff_type_off'] = 'Всі дні неробочі';
$lang['contract_calendar_popup_dayoff_type_morning'] = 'Ранок неробочий';
$lang['contract_calendar_popup_dayoff_type_afternoon'] = 'Після обіду неробочий';
$lang['contract_calendar_popup_dayoff_button_delete'] = 'Видалити';
$lang['contract_calendar_popup_dayoff_button_ok'] = 'ОК';
$lang['contract_calendar_popup_dayoff_button_cancel'] = 'Скасувати';
$lang['contract_calendar_button_import'] = 'Імпортувати iCal';
$lang['contract_calendar_prompt_import'] = 'URL файлу неробочих днів iCal';
$lang['contract_calendar_popup_series_title'] = 'Edit a series of days off';
$lang['contract_calendar_popup_series_field_occurences'] = 'Помітити кожен';
$lang['contract_calendar_popup_series_field_from'] = 'Від';
$lang['contract_calendar_popup_series_button_current'] = 'Поточний';
$lang['contract_calendar_popup_series_field_to'] = 'До';
$lang['contract_calendar_popup_series_field_as'] = 'Як';
$lang['contract_calendar_popup_series_field_as_working'] = 'Робочий день';
$lang['contract_calendar_popup_series_field_as_off'] = 'Всі дні неробочі';
$lang['contract_calendar_popup_series_field_as_morning'] = 'Ранок неробочий';
$lang['contract_calendar_popup_series_field_as_afternnon'] = 'Після обіду неробочий';
$lang['contract_calendar_popup_series_field_title'] = 'Назва';
$lang['contract_calendar_popup_series_button_ok'] = 'ОК';
$lang['contract_calendar_popup_series_button_cancel'] = 'Скасувати';
$lang['contract_calendar_button_copy'] = 'Копія';
$lang['contract_calendar_copy_destination_js_msg'] = 'Необхідно обрати контракт.';
$lang['contract_calendar_copy_msg_success'] = 'Дані успішно скопійовані.';
