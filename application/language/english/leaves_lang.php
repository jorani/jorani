<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

$lang['leaves_summary_title'] = 'My summary';
$lang['leaves_summary_title_overtime'] = 'Overtime details (added to compensate leaves)';
$lang['leaves_summary_key_overtime'] = 'Catch up for';
$lang['leaves_summary_thead_type'] = 'Leave type';
$lang['leaves_summary_thead_available'] = 'Available';
$lang['leaves_summary_thead_taken'] = 'Taken';
$lang['leaves_summary_thead_entitled'] = 'Entitled';
$lang['leaves_summary_thead_description'] = 'Description';
$lang['leaves_summary_thead_actual'] = 'actual';
$lang['leaves_summary_thead_simulated'] = 'simulated';
$lang['leaves_summary_tbody_empty'] = 'No entitled or taken days for this period. Please contact your HR Officer / Manager.';
$lang['leaves_summary_flash_msg_error'] = 'It appears that you have no contract. Please contact your HR Officer / Manager.';
$lang['leaves_summary_date_field'] = 'Date of report';

$lang['leaves_index_title'] = 'My leave requests';
$lang['leaves_index_thead_tip_view'] = 'view';
$lang['leaves_index_thead_tip_edit'] = 'edit';
$lang['leaves_index_thead_tip_cancel'] = 'cancel';
$lang['leaves_index_thead_tip_delete'] = 'delete';
$lang['leaves_index_thead_tip_history'] = 'show history';
$lang['leaves_index_thead_id'] = 'ID';
$lang['leaves_index_thead_start_date'] = 'Start Date';
$lang['leaves_index_thead_end_date'] = 'End Date';
$lang['leaves_index_thead_cause'] = 'Reason';
$lang['leaves_index_thead_duration'] = 'Duration';
$lang['leaves_index_thead_type'] = 'Type';
$lang['leaves_index_thead_status'] = 'Status';
$lang['leaves_index_thead_requested_date'] = 'Requested';
$lang['leaves_index_thead_last_change'] = 'Last change';
$lang['leaves_index_button_export'] = 'Export this list';
$lang['leaves_index_button_create'] = 'New request';
$lang['leaves_index_popup_delete_title'] = 'Delete leave request';
$lang['leaves_index_popup_delete_message'] = 'You are about to delete one leave request, this procedure is irreversible.';
$lang['leaves_index_popup_delete_question'] = 'Do you want to proceed?';
$lang['leaves_index_popup_delete_button_yes'] = 'Yes';
$lang['leaves_index_popup_delete_button_no'] = 'No';

$lang['leaves_history_thead_changed_date'] = 'Changed Date';
$lang['leaves_history_thead_change_type'] = 'Change Type';
$lang['leaves_history_thead_changed_by'] = 'Changed By';
$lang['leaves_history_thead_start_date'] = 'Start Date';
$lang['leaves_history_thead_end_date'] = 'End Date';
$lang['leaves_history_thead_cause'] = 'Reason';
$lang['leaves_history_thead_duration'] = 'Duration';
$lang['leaves_history_thead_type'] = 'Type';
$lang['leaves_history_thead_status'] = 'Status';

$lang['leaves_create_title'] = 'Submit a leave request';
$lang['leaves_create_field_start'] = 'Start Date';
$lang['leaves_create_field_end'] = 'End Date';
$lang['leaves_create_field_type'] = 'Leave type';
$lang['leaves_create_field_duration'] = 'Duration';
$lang['leaves_create_field_duration_message'] = 'You are exceeding your entitled days';
$lang['leaves_create_field_overlapping_message'] = 'You have requested another leave request within the same dates.';
$lang['leaves_create_field_cause'] = 'Cause (optional)';
$lang['leaves_create_field_status'] = 'Status';
$lang['leaves_create_button_create'] = 'Request leave';
$lang['leaves_create_button_cancel'] = 'Cancel';
$lang['leaves_create_flash_msg_success'] = 'The leave request has been successfully created';
$lang['leaves_create_flash_msg_error'] = 'The leave request has been successfully created or updated, but you don\'t have a manager.';

$lang['leaves_flash_spn_list_days_off'] = '%s non-working days in the period';
$lang['leaves_flash_msg_overlap_dayoff'] = 'Your leave request matches with a non-working day.';

$lang['leaves_edit_html_title'] = 'Edit a leave request';
$lang['leaves_edit_title'] = 'Edit leave request #';
$lang['leaves_edit_field_start'] = 'Start Date';
$lang['leaves_edit_field_end'] = 'End Date';
$lang['leaves_edit_field_type'] = 'Leave type';
$lang['leaves_edit_field_duration'] = 'Duration';
$lang['leaves_edit_field_duration_message'] = 'You are exceeding your entitled days';
$lang['leaves_edit_field_cause'] = 'Cause (optional)';
$lang['leaves_edit_field_status'] = 'Status';
$lang['leaves_edit_button_update'] = 'Update leave';
$lang['leaves_edit_button_cancel'] = 'Cancel';
$lang['leaves_edit_flash_msg_error'] = 'You cannot edit a leave request already submitted';
$lang['leaves_edit_flash_msg_success'] = 'The leave request has been successfully updated';

$lang['leaves_validate_mandatory_js_msg'] = '"The field " + fieldname + " is mandatory."';
$lang['leaves_validate_flash_msg_no_contract'] = 'It appears that you have no contract. Please contact your HR Officer / Manager.';
$lang['leaves_validate_flash_msg_overlap_period'] = 'You can\'t create a leave request for two yearly leave periods. Please create two different leave requests.';

$lang['leaves_cancellation_flash_msg_error'] = 'You can\'t cancel this leave request';
$lang['leaves_cancellation_flash_msg_success'] = 'The cancellation request has been successfully sent';
$lang['requests_cancellation_accept_flash_msg_success'] = 'The leave request has been successfully cancelled';
$lang['requests_cancellation_accept_flash_msg_error'] = 'An error occured while trying to accept the cancellation';
$lang['requests_cancellation_reject_flash_msg_success'] = 'The leave request has now its original status of Accepted';
$lang['requests_cancellation_reject_flash_msg_error'] = 'An error occured while trying to reject the cancellation';

$lang['leaves_delete_flash_msg_error'] = 'You can\'t delete this leave request';
$lang['leaves_delete_flash_msg_success'] = 'The leave request has been successfully deleted';

$lang['leaves_view_title'] = 'View leave request #';
$lang['leaves_view_html_title'] = 'View a leave request';
$lang['leaves_view_field_start'] = 'Start Date';
$lang['leaves_view_field_end'] = 'End Date';
$lang['leaves_view_field_type'] = 'Leave type';
$lang['leaves_view_field_duration'] = 'Duration';
$lang['leaves_view_field_cause'] = 'Reason';
$lang['leaves_view_field_status'] = 'Status';
$lang['leaves_view_button_edit'] = 'Edit';
$lang['leaves_view_button_back_list'] = 'Back to list';

$lang['leaves_export_title'] = 'List of leaves';
$lang['leaves_export_thead_id'] = 'ID';
$lang['leaves_export_thead_start_date'] = 'Start Date';
$lang['leaves_export_thead_start_date_type'] = 'Morning/Afternoon';
$lang['leaves_export_thead_end_date'] = 'End Date';
$lang['leaves_export_thead_end_date_type'] = 'Morning/Afternoon';
$lang['leaves_export_thead_cause'] = 'Reason';
$lang['leaves_export_thead_duration'] = 'Duration';
$lang['leaves_export_thead_type'] = 'Type';
$lang['leaves_export_thead_status'] = 'Status';

$lang['leaves_button_send_reminder'] = 'Send a reminder';
$lang['leaves_reminder_flash_msg_success'] = 'The reminder email was sent to the manager';

$lang['leaves_comment_title'] = 'Comments';
$lang['leaves_comment_new_comment'] = 'New comment';
$lang['leaves_comment_send_comment'] = 'Send comment';
$lang['leaves_comment_author_saying'] = ' says';
$lang['leaves_comment_status_changed'] = 'The status of the leave have been changed to ';
