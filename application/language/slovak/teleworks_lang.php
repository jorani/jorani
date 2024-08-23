<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

$lang['teleworks_summary_title'] = 'My counter';
$lang['teleworks_summary_thead_available'] = 'Available';
$lang['teleworks_summary_thead_taken'] = 'Taken';
$lang['teleworks_summary_thead_type'] = 'Type of request';
$lang['teleworks_summary_thead_entitled'] = 'Acquired';
$lang['teleworks_summary_thead_description'] = 'Description';
$lang['teleworks_summary_thead_actual'] = 'actual';
$lang['teleworks_summary_thead_simulated'] = 'simulated';
$lang['teleworks_summary_tbody_empty'] = 'No days taken or available. Please contact a human resources manager.';
$lang['teleworks_summary_flash_msg_error'] = 'It appears that you do not have a contract. Please contact a human resources manager';
$lang['teleworks_summary_date_field'] = 'Date of report';
    
$lang['teleworks_index_title'] = 'My telework requests';
$lang['campaign_teleworks_index_html_title'] = 'List of fixed telework requests for the campaign';
$lang['teleworks_index_thead_tip_view'] = 'view';
$lang['teleworks_index_thead_tip_edit'] = 'edit';
$lang['teleworks_index_thead_tip_cancel'] = 'cancel';
$lang['teleworks_index_thead_tip_delete'] = 'delete';
$lang['teleworks_index_thead_tip_history'] = 'display history';
$lang['teleworks_index_thead_id'] = 'No';
$lang['teleworks_index_thead_start_date'] = 'Start date';
$lang['teleworks_index_thead_end_date'] = 'End date';
$lang['teleworks_index_thead_cause'] = 'Cause';
$lang['teleworks_index_thead_duration'] = 'Duration';
$lang['teleworks_index_thead_type'] = 'Type';
$lang['teleworks_index_thead_campaign'] = 'Campaign';
$lang['teleworks_index_thead_status'] = 'Status';
$lang['teleworks_index_thead_requested_date'] = 'Requested on';
$lang['teleworks_index_thead_last_change'] = 'Modified on';
$lang['teleworks_index_button_export'] = 'Export this list';
$lang['teleworks_index_button_create'] = 'New request';
$lang['teleworks_index_popup_delete_title'] = 'Delete a telework request';
$lang['teleworks_index_popup_delete_message'] = 'You are about to delete a telework request, this procedure is irreversible';
$lang['teleworks_index_popup_delete_question'] = 'Do you want to continue?';
$lang['teleworks_index_popup_delete_button_yes'] = 'Yes';
$lang['teleworks_index_popup_delete_button_no'] = 'No';
    
$lang['teleworks_history_thead_changed_date'] = 'Changed on';
$lang['teleworks_history_thead_change_type'] = 'Change type';
$lang['teleworks_history_thead_changed_by'] = 'Modified by';
$lang['teleworks_history_thead_start_date'] = 'Start date';
$lang['teleworks_history_thead_end_date'] = 'End date';
$lang['teleworks_history_thead_cause'] = 'Cause';
$lang['teleworks_history_thead_duration'] = 'Duration';
$lang['teleworks_history_thead_status'] = 'Status';
    
$lang['teleworks_create_title'] = 'Create a new floating telework request';
$lang['teleworks_create_campaign_title'] = 'Create a fixed telework request for the campaign';
$lang['teleworks_create_field_start'] = 'Start date';
$lang['teleworks_create_field_end'] = 'End date';
$lang['teleworks_create_field_recurrence'] = 'Recurrence';
$lang['teleworks_create_field_duration'] = 'Duration';
$lang['teleworks_create_field_campaign'] = 'Campaign';
$lang['teleworks_create_field_daytype'] = 'Whole day/Morning/Afternoon';
$lang['teleworks_create_field_duration_message'] = 'You are exceeding the number of days allowed';
$lang['teleworks_create_field_overlapping_message'] = 'In this period, we have a request to telework';
$lang['teleworks_create_field_overlapping_leaves_message'] = 'In this period, we have a request for a leave of absence.';
$lang['teleworks_create_field_overlapping_time_organisations_message'] = 'In this period, we have a working time arrangement.';
$lang['teleworks_create_field_past_date_message'] = 'Requests to telework on earlier dates are not possible.';
$lang['teleworks_create_field_cause'] = 'Cause (optional)';
$lang['teleworks_create_field_status'] = 'Status';
$lang['teleworks_create_field_day'] = 'Day';
$lang['teleworks_create_button_create'] = 'Create request';
$lang['teleworks_create_button_cancel'] = 'Cancel';
$lang['teleworks_current_campaign'] = 'Current campaign';
$lang['teleworks_next_campaign'] = 'Next campaign';

$lang['teleworks_create_flash_msg_success'] = 'The telework request has been successfully created';
$lang['teleworks_create_flash_msg_error'] = 'The telework request was created or modified successfully, but you do not have a manager';

$lang['teleworks_flash_spn_list_days_off'] = '%s days not worked in the period';
$lang['teleworks_flash_msg_overlap_dayoff'] = 'Your request coincides with a non-working day.';
$lang['teleworks_flash_msg_limit_exceeded'] = 'Your request exceeds the number of telework days allowed per week.';
$lang['teleworks_flash_msg_for_campaign_dates'] = 'The dates of the telework request must match the valid campaigns';
$lang['teleworks_flash_msg_deadline_respected'] = 'The notice period is not respected';
$lang['teleworks_flash_msg_halfday_telework'] = 'The request to telework for half a day is not authorised';
    
$lang['teleworks_cancellation_flash_msg_error'] = 'You cannot cancel this telework request';
$lang['teleworks_cancellation_flash_msg_success'] = 'The cancellation request was sent successfully';
$lang['teleworkrequests_cancellation_accept_flash_msg_success'] = 'The telework request was successfully cancelled';
$lang['teleworkrequests_cancellation_accept_flash_msg_error'] = 'An error occurred while trying to cancel the request';
$lang['teleworkrequests_cancellation_reject_flash_msg_success'] = 'The telework request is now in its original *Accepted* status';
$lang['teleworkrequests_cancellation_reject_flash_msg_error'] = 'An error occurred while attempting to reject the cancellation request';

$lang['teleworks_edit_html_title'] = 'Modify request';
$lang['teleworks_edit_title'] = 'Modify request No.';
$lang['teleworks_edit_field_start'] = 'Start date';
$lang['teleworks_edit_field_end'] = 'End date';
$lang['teleworks_edit_field_duration'] = 'Duration';
$lang['teleworks_edit_field_duration_message'] = 'You are exceeding the number of days allowed';
$lang['teleworks_edit_field_cause'] = 'Cause (optional)';
$lang['teleworks_edit_field_status'] = 'Status';
$lang['teleworks_edit_button_update'] = 'Update';
$lang['teleworks_edit_button_cancel'] = 'Cancel';
$lang['teleworks_edit_flash_msg_error'] = 'You cannot edit a previously submitted request';
$lang['teleworks_edit_flash_msg_success'] = 'The telework request has been successfully modified';

$lang['teleworks_validate_mandatory_js_msg'] = '"The field " + fieldname + " is mandatory."';
$lang['teleworks_validate_flash_msg_no_contract'] = 'It appears that you do not have a contract. Please contact a human resources manager.';
$lang['teleworks_validate_flash_msg_overlap_period'] = 'You cannot create a telework request for two annual telework periods. Please create two different applications';
    
$lang['teleworks_cancel_flash_msg_error'] = 'You cannot cancel this telework request';
$lang['teleworks_cancel_flash_msg_success'] = 'The telework request has been successfully cancelled';
$lang['teleworks_cancel_unauthorized_msg_error'] = 'You cannot cancel a telework request that started in the past. Ask your manager to reject it.' ;
    
$lang['teleworks_delete_flash_msg_error'] = 'You cannot delete this telework request';
$lang['teleworks_delete_flash_msg_success'] = 'The telework request has been successfully deleted';
    
$lang['teleworks_view_title'] = 'View telework request No';
$lang['teleworks_view_html_title'] = 'View a request';
$lang['teleworks_view_field_start'] = 'Start date';
$lang['teleworks_view_field_end'] = 'End date';
$lang['teleworks_view_field_duration'] = 'Duration';
$lang['teleworks_view_field_cause'] = 'Cause';
$lang['teleworks_view_field_status'] = 'Status';
$lang['teleworks_view_button_edit'] = 'Edit';
$lang['teleworks_view_button_back_list'] = 'Return to list';
    
$lang['teleworks_export_title'] = 'List of telework requests';
$lang['teleworks_export_thead_id'] = 'ID';
$lang['teleworks_export_thead_start_date'] = 'Start date';
$lang['teleworks_export_thead_start_date_type'] = 'Morning/Afternoon';
$lang['teleworks_export_thead_end_date'] = 'End date';
$lang['teleworks_export_thead_end_date_type'] = 'Morning/Afternoon';
$lang['teleworks_export_thead_cause'] = 'Cause';
$lang['teleworks_export_thead_duration'] = 'Duration';
$lang['teleworks_export_thead_type'] = 'Type';
$lang['teleworks_export_thead_campaign'] = 'Campaign';
$lang['teleworks_export_thead_status'] = 'Status';
    
$lang['teleworks_button_send_reminder'] = 'Send a reminder';
$lang['teleworks_reminder_flash_msg_success'] = 'Reminder email has been sent to manager';

$lang['teleworks_comment_title'] = 'Comments';
$lang['teleworks_comment_new_comment'] = 'New comment';
$lang['teleworks_comment_send_comment'] = 'Add a comment';
$lang['teleworks_comment_author_saying'] = ' said';
$lang['teleworks_comment_status_changed'] = 'The status of the request has changed: ';

$lang['Campaign'] = 'Campaign';
$lang['Floating'] = 'Floating';

$lang['all_recurrence'] = 'Every week';
$lang['even_week'] = 'Even weeks';
$lang['odd_week'] = 'Odd weeks';
