<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.2
 * @author        Oleg Koptev, Yevhen Kyrylchenko
 */

$lang['leaves_summary_title'] = 'Моя сводная информация';
$lang['leaves_summary_title_overtime'] = 'Подробности сверхурочных (добавлено для компенсации отпусков)';
$lang['leaves_summary_key_overtime'] = 'Нагнать';
$lang['leaves_summary_thead_type'] = 'Тип отпуска';
$lang['leaves_summary_thead_available'] = 'Доступно';
$lang['leaves_summary_thead_taken'] = 'Использовано';
$lang['leaves_summary_thead_entitled'] = 'Предоставляемые дни';
$lang['leaves_summary_thead_description'] = 'Описание';
$lang['leaves_summary_thead_actual'] = 'actual';
$lang['leaves_summary_thead_simulated'] = 'simulated';
$lang['leaves_summary_tbody_empty'] = 'Нет предоставленных или использованных дней для этого периода. Обратитесь к своему руководителю.';
$lang['leaves_summary_flash_msg_error'] = 'Похоже вы не имеете контракта. Обратитесь к своему руководителю.';
$lang['leaves_summary_date_field'] = 'Дата отчёта';

$lang['leaves_index_title'] = 'Мои заявления на отпуск';
$lang['leaves_index_thead_tip_view'] = 'просмотреть';
$lang['leaves_index_thead_tip_edit'] = 'редактировать';
$lang['leaves_index_thead_tip_cancel'] = 'cancel';
$lang['leaves_index_thead_tip_delete'] = 'удалить';
$lang['leaves_index_thead_tip_history'] = 'show history';
$lang['leaves_index_thead_id'] = 'ID';
$lang['leaves_index_thead_start_date'] = 'Дата начала';
$lang['leaves_index_thead_end_date'] = 'Дата окончания';
$lang['leaves_index_thead_cause'] = 'Причина';
$lang['leaves_index_thead_duration'] = 'Продолжительность';
$lang['leaves_index_thead_type'] = 'Тип';
$lang['leaves_index_thead_status'] = 'Состояние';
$lang['leaves_index_thead_requested_date'] = 'Requested';
$lang['leaves_index_thead_last_change'] = 'Last change';
$lang['leaves_index_button_export'] = 'Экспортировать список';
$lang['leaves_index_button_create'] = 'Новый запрос';
$lang['leaves_index_popup_delete_title'] = 'Удалить заявление на отпуск';
$lang['leaves_index_popup_delete_message'] = 'Вы собираетесь удалить заявление на отпуск. Это действие нельзя будет отменить.';
$lang['leaves_index_popup_delete_question'] = 'Продолжить?';
$lang['leaves_index_popup_delete_button_yes'] = 'Да';
$lang['leaves_index_popup_delete_button_no'] = 'Нет';

$lang['leaves_history_thead_changed_date'] = 'Changed Date';
$lang['leaves_history_thead_change_type'] = 'Change Type';
$lang['leaves_history_thead_changed_by'] = 'Changed By';
$lang['leaves_history_thead_start_date'] = 'Start Date';
$lang['leaves_history_thead_end_date'] = 'End Date';
$lang['leaves_history_thead_cause'] = 'Reason';
$lang['leaves_history_thead_duration'] = 'Duration';
$lang['leaves_history_thead_type'] = 'Type';
$lang['leaves_history_thead_status'] = 'Status';

$lang['leaves_create_title'] = 'Отправить заявление на отпуск';
$lang['leaves_create_field_start'] = 'Дата начала';
$lang['leaves_create_field_end'] = 'Дата окончания';
$lang['leaves_create_field_type'] = 'Тип отпуска';
$lang['leaves_create_field_duration'] = 'Продолжительность';
$lang['leaves_create_field_duration_message'] = 'Вы превысили лимит предоставленных дней';
$lang['leaves_create_field_overlapping_message'] = 'Вы отправили еще одно заявление на отпуск на те же даты.';
$lang['leaves_create_field_cause'] = 'Причина (необязательно)';
$lang['leaves_create_field_status'] = 'Состояние';
$lang['leaves_create_button_create'] = 'Запросить отпуск';
$lang['leaves_create_button_cancel'] = 'Отмена';

$lang['leaves_create_flash_msg_success'] = 'Заявление на отпуск успешно создано.';
$lang['leaves_create_flash_msg_error'] = 'Заявление на отпуск было успешно создано/обновлено, но у вас нет руководителя.';

$lang['leaves_flash_spn_list_days_off'] = '%s non-working days in the period';
$lang['leaves_flash_msg_overlap_dayoff'] = 'Your leave request matches with a non-working day.';

$lang['leaves_cancellation_flash_msg_error'] = 'You can\'t cancel this leave request';
$lang['leaves_cancellation_flash_msg_success'] = 'The cancellation request has been successfully sent';
$lang['requests_cancellation_accept_flash_msg_success'] = 'The leave request has been successfully cancelled';
$lang['requests_cancellation_accept_flash_msg_error'] = 'An error occured while trying to accept the cancellation';
$lang['requests_cancellation_reject_flash_msg_success'] = 'The leave request has now its original status of Accepted';
$lang['requests_cancellation_reject_flash_msg_error'] = 'An error occured while trying to reject the cancellation';

$lang['leaves_edit_html_title'] = 'Редактировать заявление на отпуск';
$lang['leaves_edit_title'] = 'Редактировать заявление на отпуск №';
$lang['leaves_edit_field_start'] = 'Дата начала';
$lang['leaves_edit_field_end'] = 'Дата окончания';
$lang['leaves_edit_field_type'] = 'тип отпуска';
$lang['leaves_edit_field_duration'] = 'Продолжительность';
$lang['leaves_edit_field_duration_message'] = 'Вы превысили лимит предоставленных дней';
$lang['leaves_edit_field_cause'] = 'Причина (необязательно)';
$lang['leaves_edit_field_status'] = 'Состояние';
$lang['leaves_edit_button_update'] = 'Обновить отпуск';
$lang['leaves_edit_button_cancel'] = 'Отмена';
$lang['leaves_edit_flash_msg_error'] = 'Невозможно изменить уже отправленное заявление на отпуск';
$lang['leaves_edit_flash_msg_success'] = 'Заявление на отпуск успешно обновлено';

$lang['leaves_validate_mandatory_js_msg'] = '"Поле " + fieldname + " является обязательным."';
$lang['leaves_validate_flash_msg_no_contract'] = 'Похоже вы не имеете контракта. Обратитесь к своему руководителю.';
$lang['leaves_validate_flash_msg_overlap_period'] = 'Вы не можете создать заявление на отпуск для разных годов.  Пожалуйста, создайте два разных заявления на отпуск.';

$lang['leaves_cancel_flash_msg_error'] = 'You can\'t cancel this leave request';
$lang['leaves_cancel_flash_msg_success'] = 'The leave request has been successfully cancelled';
$lang['leaves_cancel_unauthorized_msg_error'] = 'You can\'t cancel a leave request starting in the past. Ask your manager for rejecting it.';

$lang['leaves_delete_flash_msg_error'] = 'Нельзя удалить это заявление на отпуск';
$lang['leaves_delete_flash_msg_success'] = 'Заявление на отпуск успешно удалено';

$lang['leaves_view_title'] = 'Просмотреть заявление на отпуск №';
$lang['leaves_view_html_title'] = 'Просмотреть заявление на отпуск';
$lang['leaves_view_field_start'] = 'Дата начала';
$lang['leaves_view_field_end'] = 'Дата окончания';
$lang['leaves_view_field_type'] = 'Тип отпуска';
$lang['leaves_view_field_duration'] = 'Продолжительность';
$lang['leaves_view_field_cause'] = 'Причина';
$lang['leaves_view_field_status'] = 'Состояние';
$lang['leaves_view_button_edit'] = 'Редактировать';
$lang['leaves_view_button_back_list'] = 'Вернуться к списку';
$lang['leaves_export_title'] = 'Список отпусков';
$lang['leaves_export_thead_id'] = 'ID';
$lang['leaves_export_thead_start_date'] = 'Дата начала';
$lang['leaves_export_thead_start_date_type'] = 'Утро/День';
$lang['leaves_export_thead_end_date'] = 'Дата окончания';
$lang['leaves_export_thead_end_date_type'] = 'Утро/День';
$lang['leaves_export_thead_cause'] = 'Причина';
$lang['leaves_export_thead_duration'] = 'Продолжительность';
$lang['leaves_export_thead_type'] = 'Тип';
$lang['leaves_export_thead_status'] = 'Состояние';

$lang['leaves_button_send_reminder'] = 'Send a reminder';
$lang['leaves_reminder_flash_msg_success'] = 'The reminder email was sent to the manager';

$lang['leaves_comment_title'] = 'Comments';
$lang['leaves_comment_new_comment'] = 'New comment';
$lang['leaves_comment_send_comment'] = 'Send comment';
$lang['leaves_comment_author_saying'] = ' says';
$lang['leaves_comment_status_changed'] = 'The status of the leave have been changed to ';
