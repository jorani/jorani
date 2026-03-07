<?php
/**
 * Translation file
 * @copyright Copyright (c) 2014-2015 Benjamin BALET
 * @license     http://opensource.org/licenses/MIT MIT
 * @link           https://github.com/jorani/jorani
 * @since        0.4.4
 * @author      Yevhen Kyrylchenko
 */

$lang['leaves_summary_title'] = 'Моя зведена інформація';
$lang['leaves_summary_title_overtime'] = 'Подробиці понаднормових (додано для компенсації відпусток)';
$lang['leaves_summary_key_overtime'] = 'Нагнати';
$lang['leaves_summary_thead_type'] = 'Тип відпустки';
$lang['leaves_summary_thead_available'] = 'Доступно';
$lang['leaves_summary_thead_taken'] = 'Використано';
$lang['leaves_summary_thead_entitled'] = 'Надано';
$lang['leaves_summary_thead_description'] = 'Опис';
$lang['leaves_summary_thead_actual'] = 'actual';
$lang['leaves_summary_thead_simulated'] = 'simulated';
$lang['leaves_summary_tbody_empty'] = 'Немає наданих або використаних днів за цей період. Зверніться до свого керівника.';
$lang['leaves_summary_flash_msg_error'] = 'Схоже не те, що у вас немає контракту. Зверніться до свого керівника.';
$lang['leaves_summary_date_field'] = 'Дата звіту';

$lang['leaves_index_title'] = 'Мої заяви на відпустку';
$lang['leaves_index_thead_tip_view'] = 'переглянути';
$lang['leaves_index_thead_tip_edit'] = 'редагувати';
$lang['leaves_index_thead_tip_cancel'] = 'cancel';
$lang['leaves_index_thead_tip_delete'] = 'видалити';
$lang['leaves_index_thead_tip_history'] = 'show history';
$lang['leaves_index_thead_id'] = 'ID';
$lang['leaves_index_thead_start_date'] = 'Дата початку';
$lang['leaves_index_thead_end_date'] = 'Дата закінчення';
$lang['leaves_index_thead_cause'] = 'Причина';
$lang['leaves_index_thead_duration'] = 'Тривалість';
$lang['leaves_index_thead_type'] = 'Тип';
$lang['leaves_index_thead_status'] = 'Статус';
$lang['leaves_index_thead_requested_date'] = 'Requested';
$lang['leaves_index_thead_last_change'] = 'Last change';
$lang['leaves_index_button_export'] = 'Експортувати список';
$lang['leaves_index_button_create'] = 'Нова заява';
$lang['leaves_index_popup_delete_title'] = 'Видалити заяву на відпустку';
$lang['leaves_index_popup_delete_message'] = 'Ви збираєтесь видалити заяву на відпустку. Цю дію не можна буде скасувати.';
$lang['leaves_index_popup_delete_question'] = 'Продовжити?';
$lang['leaves_index_popup_delete_button_yes'] = 'Так';
$lang['leaves_index_popup_delete_button_no'] = 'Ні';

$lang['leaves_history_thead_changed_date'] = 'Changed Date';
$lang['leaves_history_thead_change_type'] = 'Change Type';
$lang['leaves_history_thead_changed_by'] = 'Changed By';
$lang['leaves_history_thead_start_date'] = 'Start Date';
$lang['leaves_history_thead_end_date'] = 'End Date';
$lang['leaves_history_thead_cause'] = 'Reason';
$lang['leaves_history_thead_duration'] = 'Duration';
$lang['leaves_history_thead_type'] = 'Type';
$lang['leaves_history_thead_status'] = 'Status';

$lang['leaves_create_title'] = 'Відправити заяву на відпустку';
$lang['leaves_create_field_start'] = 'Дата початку';
$lang['leaves_create_field_end'] = 'Дата закінчення';
$lang['leaves_create_field_type'] = 'Тип відпустки';
$lang['leaves_create_field_duration'] = 'Тривалість';
$lang['leaves_create_field_duration_message'] = 'Ви перевищили ліміт наданих днів';
$lang['leaves_create_field_overlapping_message'] = 'Ви відправили іншу заяву на відпустку на такі самі дні.';
$lang['leaves_create_field_cause'] = 'Причина (не обов\'язково)';
$lang['leaves_create_field_status'] = 'Статус';
$lang['leaves_create_button_create'] = 'Запит відпустки';
$lang['leaves_create_button_cancel'] = 'Скасувати';

$lang['leaves_create_flash_msg_success'] = 'Заява на відпустку успішно створена';
$lang['leaves_create_flash_msg_error'] = 'Заява на відпустку успішно створена або оновлена, але у вас немає керівника.';

$lang['leaves_flash_spn_list_days_off'] = '%s non-working days in the period';
$lang['leaves_flash_msg_overlap_dayoff'] = 'Your leave request matches with a non-working day.';

$lang['leaves_edit_html_title'] = 'Редагувати заяву на відпустку';
$lang['leaves_edit_title'] = 'Редагувати заяву на відпустку №';
$lang['leaves_edit_field_start'] = 'Дата початку';
$lang['leaves_edit_field_end'] = 'Дата закінчення';
$lang['leaves_edit_field_type'] = 'Тип відпустки';
$lang['leaves_edit_field_duration'] = 'Тривалість';
$lang['leaves_edit_field_duration_message'] = 'Ви перевищили ліміт наданих днів';
$lang['leaves_edit_field_cause'] = 'Причина (не обов\'язково)';
$lang['leaves_edit_field_status'] = 'Статус';
$lang['leaves_edit_button_update'] = 'Оновити заяву на відпустку';
$lang['leaves_edit_button_cancel'] = 'Скасувати';
$lang['leaves_edit_flash_msg_error'] = 'Не можна оновити відправлену заяву на відпустку';
$lang['leaves_edit_flash_msg_success'] = 'Заява на понаднормові відпустку оновлена';

$lang['leaves_validate_mandatory_js_msg'] = '"Поле " + fieldname + " є обов\'язковим."';
$lang['leaves_validate_flash_msg_no_contract'] = 'Схоже не те, що у вас немає контракту. Зверніться до свого керівника.';
$lang['leaves_validate_flash_msg_overlap_period'] = 'Ви не можете створити заяву на відпустку для різних років. Будь ласка створіть дві окремі заяви на відпустку.';

$lang['leaves_cancellation_flash_msg_error'] = 'You can\'t cancel this leave request';
$lang['leaves_cancellation_flash_msg_success'] = 'The cancellation request has been successfully sent';
$lang['requests_cancellation_accept_flash_msg_success'] = 'The leave request has been successfully cancelled';
$lang['requests_cancellation_accept_flash_msg_error'] = 'An error occured while trying to accept the cancellation';
$lang['requests_cancellation_reject_flash_msg_success'] = 'The leave request has now its original status of Accepted';
$lang['requests_cancellation_reject_flash_msg_error'] = 'An error occured while trying to reject the cancellation';

$lang['leaves_cancel_flash_msg_error'] = 'You can\'t cancel this leave request';
$lang['leaves_cancel_flash_msg_success'] = 'The leave request has been successfully cancelled';
$lang['leaves_cancel_unauthorized_msg_error'] = 'You can\'t cancel a leave request starting in the past. Ask your manager for rejecting it.';

$lang['leaves_delete_flash_msg_error'] = 'Не можна видалити цю заяву на відпустку';
$lang['leaves_delete_flash_msg_success'] = 'Заява на відпустку успішно видалена';

$lang['leaves_view_title'] = 'Перегляд заяви на відпустку №';
$lang['leaves_view_html_title'] = 'Перегляд заяви на відпустку';
$lang['leaves_view_field_start'] = 'Дата початку';
$lang['leaves_view_field_end'] = 'Дата закінчення';
$lang['leaves_view_field_type'] = 'Тип відпустки';
$lang['leaves_view_field_duration'] = 'Тривалість';
$lang['leaves_view_field_cause'] = 'Причина';
$lang['leaves_view_field_status'] = 'Статус';
$lang['leaves_view_button_edit'] = 'Редагувати';
$lang['leaves_view_button_back_list'] = 'Назад до списку';

$lang['leaves_export_title'] = 'Список відпусток';
$lang['leaves_export_thead_id'] = 'ID';
$lang['leaves_export_thead_start_date'] = 'Дата початку';
$lang['leaves_export_thead_start_date_type'] = 'Ранок/Після обіду';
$lang['leaves_export_thead_end_date'] = 'Дата закінчення';
$lang['leaves_export_thead_end_date_type'] = 'Ранок/Після обіду';
$lang['leaves_export_thead_cause'] = 'Причина';
$lang['leaves_export_thead_duration'] = 'Тривалість';
$lang['leaves_export_thead_type'] = 'Тип';
$lang['leaves_export_thead_status'] = 'Статус';

$lang['leaves_button_send_reminder'] = 'Send a reminder';
$lang['leaves_reminder_flash_msg_success'] = 'The reminder email was sent to the manager';

$lang['leaves_comment_title'] = 'Comments';
$lang['leaves_comment_new_comment'] = 'New comment';
$lang['leaves_comment_send_comment'] = 'Send comment';
$lang['leaves_comment_author_saying'] = ' says';
$lang['leaves_comment_status_changed'] = 'The status of the leave have been changed to ';
