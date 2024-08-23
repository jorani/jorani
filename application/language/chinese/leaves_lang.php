<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license     http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link          https://github.com/bbalet/jorani
 * @since       0.4.7
 * @author      Ceibga Bao <info@sansin.com.tw>
 */

$lang['leaves_summary_title'] = '我的紀錄';
$lang['leaves_summary_title_overtime'] = '加班明細（加到補休）';
$lang['leaves_summary_key_overtime'] = '補給';
$lang['leaves_summary_thead_type'] = '休假類別';
$lang['leaves_summary_thead_available'] = '可行';
$lang['leaves_summary_thead_taken'] = '拿取';
$lang['leaves_summary_thead_entitled'] = '可享有權利';
$lang['leaves_summary_thead_description'] = '描述';
$lang['leaves_summary_thead_actual'] = '实际';
$lang['leaves_summary_thead_simulated'] = '模拟';
$lang['leaves_summary_tbody_empty'] = '此時段無可休假天數,請聯繫HR部門/管理者';
$lang['leaves_summary_flash_msg_error'] = '你無類別.請連繫HR部門/管理者';
$lang['leaves_summary_date_field'] = '報告建立日期';

$lang['leaves_index_title'] = '我的休假申請';
$lang['leaves_index_thead_tip_view'] = '預覽';
$lang['leaves_index_thead_tip_edit'] = '編輯';
$lang['leaves_index_thead_tip_cancel'] = '取消';
$lang['leaves_index_thead_tip_delete'] = '刪除';
$lang['leaves_index_thead_tip_history'] = 'show history';
$lang['leaves_index_thead_id'] = '證號';
$lang['leaves_index_thead_start_date'] = '開始日期';
$lang['leaves_index_thead_end_date'] = '結束日期';
$lang['leaves_index_thead_cause'] = '理由';
$lang['leaves_index_thead_duration'] = '時段';
$lang['leaves_index_thead_type'] = '編輯';
$lang['leaves_index_thead_status'] = '職位';
$lang['leaves_index_thead_requested_date'] = 'Requested';
$lang['leaves_index_thead_last_change'] = 'Last change';
$lang['leaves_index_button_export'] = '匯出此單';
$lang['leaves_index_button_create'] = '新申請';
$lang['leaves_index_popup_delete_title'] = '刪除休假申請';
$lang['leaves_index_popup_delete_message'] = '你可以刪除一休假申請,但無法再做復原';
$lang['leaves_index_popup_delete_question'] = '你要繼續嗎？';
$lang['leaves_index_popup_delete_button_yes'] = '是';
$lang['leaves_index_popup_delete_button_no'] = '否';

$lang['leaves_history_thead_changed_date'] = 'Changed Date';
$lang['leaves_history_thead_change_type'] = 'Change Type';
$lang['leaves_history_thead_changed_by'] = 'Changed By';
$lang['leaves_history_thead_start_date'] = 'Start Date';
$lang['leaves_history_thead_end_date'] = 'End Date';
$lang['leaves_history_thead_cause'] = 'Reason';
$lang['leaves_history_thead_duration'] = 'Duration';
$lang['leaves_history_thead_type'] = 'Type';
$lang['leaves_history_thead_status'] = 'Status';

$lang['leaves_create_title'] = '送出休假申請';
$lang['leaves_create_field_start'] = '開始日期';
$lang['leaves_create_field_end'] = '結束日期';
$lang['leaves_create_field_type'] = '休假類別';
$lang['leaves_create_field_duration'] = '時段';
$lang['leaves_create_field_duration_message'] = '你已超出可使用天數';
$lang['leaves_create_field_overlapping_message'] = '你已有申請同一天休假';
$lang['leaves_create_field_cause'] = '原因（可不填）';
$lang['leaves_create_field_status'] = '職位';
$lang['leaves_create_button_create'] = '申請休假';
$lang['leaves_create_button_cancel'] = '取消';
$lang['leaves_create_flash_msg_success'] = '休假申請已建立成功';
$lang['leaves_create_flash_msg_error'] = '休假申請已建立或更新,但尚未被同意';

$lang['leaves_flash_spn_list_days_off'] = '％s非工作日於此時段';
$lang['leaves_flash_msg_overlap_dayoff'] = '你的休假申請符合非工作日';

$lang['leaves_cancellation_flash_msg_error'] = 'You can\'t cancel this leave request';
$lang['leaves_cancellation_flash_msg_success'] = 'The cancellation request has been successfully sent';
$lang['requests_cancellation_accept_flash_msg_success'] = 'The leave request has been successfully cancelled';
$lang['requests_cancellation_accept_flash_msg_error'] = 'An error occured while trying to accept the cancellation';
$lang['requests_cancellation_reject_flash_msg_success'] = 'The leave request has now its original status of Accepted';
$lang['requests_cancellation_reject_flash_msg_error'] = 'An error occured while trying to reject the cancellation';

$lang['leaves_edit_html_title'] = '編輯一休假申請';
$lang['leaves_edit_title'] = '編輯休假申請';
$lang['leaves_edit_field_start'] = '開始日期';
$lang['leaves_edit_field_end'] = '結束日期';
$lang['leaves_edit_field_type'] = '休假類別';
$lang['leaves_edit_field_duration'] = '時段';
$lang['leaves_edit_field_duration_message'] = '你已超出可使用天數';
$lang['leaves_edit_field_cause'] = '原因（可不填）';
$lang['leaves_edit_field_status'] = '職位';
$lang['leaves_edit_button_update'] = '更新休假';
$lang['leaves_edit_button_cancel'] = '取消';
$lang['leaves_edit_flash_msg_error'] = '你無法編輯已送出的休假申請';
$lang['leaves_edit_flash_msg_success'] = '休假申請已成功更新';

$lang['leaves_validate_mandatory_js_msg'] = '"The field " + fieldname + " is mandatory."';
$lang['leaves_validate_flash_msg_no_contract'] = '你無類別.請連繫HR部門/管理者';
$lang['leaves_validate_flash_msg_overlap_period'] = '你無法同時建立2個休假申請,請分別建立';

$lang['leaves_cancel_flash_msg_error'] = 'You can\'t cancel this leave request';
$lang['leaves_cancel_flash_msg_success'] = 'The leave request has been successfully cancelled';
$lang['leaves_cancel_unauthorized_msg_error'] = 'You can\'t cancel a leave request starting in the past. Ask your manager for rejecting it.';

$lang['leaves_delete_flash_msg_error'] = '你無法刪除此休假申請';
$lang['leaves_delete_flash_msg_success'] = '休假申請已成功刪除';

$lang['leaves_view_title'] = '預覽休假申請';
$lang['leaves_view_html_title'] = '預覽一休假申請';
$lang['leaves_view_field_start'] = '開始日期';
$lang['leaves_view_field_end'] = '結束日期';
$lang['leaves_view_field_type'] = '休假類別';
$lang['leaves_view_field_duration'] = '時段';
$lang['leaves_view_field_cause'] = '理由';
$lang['leaves_view_field_status'] = '職位';
$lang['leaves_view_button_edit'] = '編輯';
$lang['leaves_view_button_back_list'] = '返回列表';

$lang['leaves_export_title'] = '休假列表';
$lang['leaves_export_thead_id'] = '證號';
$lang['leaves_export_thead_start_date'] = '開始日期';
$lang['leaves_export_thead_start_date_type'] = '上午/下午';
$lang['leaves_export_thead_end_date'] = '結束日期';
$lang['leaves_export_thead_end_date_type'] = '上午/下午';
$lang['leaves_export_thead_cause'] = '理由';
$lang['leaves_export_thead_duration'] = '時段';
$lang['leaves_export_thead_type'] = '編輯';
$lang['leaves_export_thead_status'] = '職位';

$lang['leaves_button_send_reminder'] = 'Send a reminder';
$lang['leaves_reminder_flash_msg_success'] = 'The reminder email was sent to the manager';

$lang['leaves_comment_title'] = 'Comments';
$lang['leaves_comment_new_comment'] = 'New comment';
$lang['leaves_comment_send_comment'] = 'Send comment';
$lang['leaves_comment_author_saying'] = ' says';
$lang['leaves_comment_status_changed'] = 'The status of the leave have been changed to ';
