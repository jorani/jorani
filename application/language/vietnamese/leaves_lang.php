<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license     http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link          https://github.com/bbalet/jorani
 * @since       0.4.5
 * @author      See list on Transifex https://www.transifex.com/jorani/
 */

$lang['leaves_summary_title'] = 'Tổng hợp';
$lang['leaves_summary_title_overtime'] = 'Chi tiết làm thêm giờ (đã thêm vào để bù ngày phép)';
$lang['leaves_summary_key_overtime'] = 'Theo kịp với';
$lang['leaves_summary_thead_type'] = 'Loại ngày nghỉ';
$lang['leaves_summary_thead_available'] = 'Có sẵn';
$lang['leaves_summary_thead_taken'] = 'Được dùng';
$lang['leaves_summary_thead_entitled'] = 'Được phép';
$lang['leaves_summary_thead_description'] = 'Miêu tả';
$lang['leaves_summary_thead_actual'] = 'actual';
$lang['leaves_summary_thead_simulated'] = 'simulated';
$lang['leaves_summary_tbody_empty'] = 'Không có số ngày được phép hay được dùng trong khoảng thời gian này. Vui lòng liên hệ Trưởng bộ phận nhân sự của bạn.';
$lang['leaves_summary_flash_msg_error'] = 'Hiển thị bạn không có hợp đồng. Vui lòng liên hệ Trưởng bộ phận nhân sự của bạn.';
$lang['leaves_summary_date_field'] = 'Ngày báo cáo';

$lang['leaves_index_title'] = 'Yêu cầu nghỉ của tôi';
$lang['leaves_index_thead_tip_view'] = 'xem';
$lang['leaves_index_thead_tip_edit'] = 'sửa';
$lang['leaves_index_thead_tip_cancel'] = 'cancel';
$lang['leaves_index_thead_tip_delete'] = 'xóa';
$lang['leaves_index_thead_tip_history'] = 'show history';
$lang['leaves_index_thead_id'] = 'ID';
$lang['leaves_index_thead_start_date'] = 'Ngày bắt đầu';
$lang['leaves_index_thead_end_date'] = 'Ngày kết thúc';
$lang['leaves_index_thead_cause'] = 'lý do';
$lang['leaves_index_thead_duration'] = 'Khoảng thời gian';
$lang['leaves_index_thead_type'] = 'Loại';
$lang['leaves_index_thead_status'] = 'Trạng thái';
$lang['leaves_index_thead_requested_date'] = 'Requested';
$lang['leaves_index_thead_last_change'] = 'Last change';
$lang['leaves_index_button_export'] = 'Xuất danh sách';
$lang['leaves_index_button_create'] = 'Yêu cầu mới';
$lang['leaves_index_popup_delete_title'] = 'Xóa yêu cầu nghỉ';
$lang['leaves_index_popup_delete_message'] = 'Bạn muốn xóa một đề nghị nghỉ phép, thao tác này không thể phục hồi lại.';
$lang['leaves_index_popup_delete_question'] = 'Bạn muốn tiếp tục chứ?';
$lang['leaves_index_popup_delete_button_yes'] = 'Có';
$lang['leaves_index_popup_delete_button_no'] = 'không';

$lang['leaves_history_thead_changed_date'] = 'Changed Date';
$lang['leaves_history_thead_change_type'] = 'Change Type';
$lang['leaves_history_thead_changed_by'] = 'Changed By';
$lang['leaves_history_thead_start_date'] = 'Start Date';
$lang['leaves_history_thead_end_date'] = 'End Date';
$lang['leaves_history_thead_cause'] = 'Reason';
$lang['leaves_history_thead_duration'] = 'Duration';
$lang['leaves_history_thead_type'] = 'Type';
$lang['leaves_history_thead_status'] = 'Status';

$lang['leaves_create_title'] = 'Đệ trình một yêu cầu nghỉ';
$lang['leaves_create_field_start'] = 'Ngày bắt đầu';
$lang['leaves_create_field_end'] = 'Ngày kết thúc';
$lang['leaves_create_field_type'] = 'Loại ngày nghỉ';
$lang['leaves_create_field_duration'] = 'Khoảng thời gian';
$lang['leaves_create_field_duration_message'] = 'Bạn đang vượt quá số ngày được phép của bạn';
$lang['leaves_create_field_overlapping_message'] = 'Bạn đã yêu cầu một đề nghị nghỉ phép khác trong cùng một ngày.';
$lang['leaves_create_field_cause'] = 'Nguyên nhân (tùy chọn)';
$lang['leaves_create_field_status'] = 'Trạng thái';
$lang['leaves_create_button_create'] = 'Yêu cầu nghỉ';
$lang['leaves_create_button_cancel'] = 'Hủy bỏ';
$lang['leaves_create_flash_msg_success'] = 'Yêu cầu nghỉ đã được tạo thành công';
$lang['leaves_create_flash_msg_error'] = 'Yêu cầu nghỉ đã được tạo và cập nhật thành công, nhưng User của bạn không có ai quản lý.';

$lang['leaves_flash_spn_list_days_off'] = '%s non-working days in the period';
$lang['leaves_flash_msg_overlap_dayoff'] = 'Your leave request matches with a non-working day.';

$lang['leaves_cancellation_flash_msg_error'] = 'You can\'t cancel this leave request';
$lang['leaves_cancellation_flash_msg_success'] = 'The cancellation request has been successfully sent';
$lang['requests_cancellation_accept_flash_msg_success'] = 'The leave request has been successfully cancelled';
$lang['requests_cancellation_accept_flash_msg_error'] = 'An error occured while trying to accept the cancellation';
$lang['requests_cancellation_reject_flash_msg_success'] = 'The leave request has now its original status of Accepted';
$lang['requests_cancellation_reject_flash_msg_error'] = 'An error occured while trying to reject the cancellation';

$lang['leaves_edit_html_title'] = 'Sửa một yêu cầu nghỉ';
$lang['leaves_edit_title'] = 'Sửa yêu cầu nghỉ #';
$lang['leaves_edit_field_start'] = 'Ngày bắt đầu';
$lang['leaves_edit_field_end'] = 'Ngày kết thúc';
$lang['leaves_edit_field_type'] = 'Loại ngày nghỉ';
$lang['leaves_edit_field_duration'] = 'Khoảng thời gian';
$lang['leaves_edit_field_duration_message'] = 'Bạn đang vượt quá số ngày được phép của bạn';
$lang['leaves_edit_field_cause'] = 'Nguyên nhân (tùy chọn)';
$lang['leaves_edit_field_status'] = 'Trạng thái';
$lang['leaves_edit_button_update'] = 'Cập nhật yêu cầu nghỉ';
$lang['leaves_edit_button_cancel'] = 'Hủy bỏ';

$lang['leaves_edit_flash_msg_error'] = 'Bạn không thể sửa một yêu cầu nghỉ đã được đệ trình';
$lang['leaves_edit_flash_msg_success'] = 'Yêu cầu nghỉ đã được cập nhật thành công';
$lang['leaves_validate_mandatory_js_msg'] = '"Trường " + fieldname + " là bắt buộc."';
$lang['leaves_validate_flash_msg_no_contract'] = 'Hiển thị bạn không có hợp đồng. Vui lòng liên hệ Trưởng bộ phận nhân sự của bạn..';
$lang['leaves_validate_flash_msg_overlap_period'] = 'You can\'t create a leave request for two yearly leave periods. Please create two different leave requests.';

$lang['leaves_cancel_flash_msg_error'] = 'You can\'t cancel this leave request';
$lang['leaves_cancel_flash_msg_success'] = 'The leave request has been successfully cancelled';
$lang['leaves_cancel_unauthorized_msg_error'] = 'You can\'t cancel a leave request starting in the past. Ask your manager for rejecting it.';

$lang['leaves_delete_flash_msg_error'] = 'Bạn không thể xóa yêu cầu nghỉ này';
$lang['leaves_delete_flash_msg_success'] = 'Yêu cầu nghỉ đã được xóa thành công';

$lang['leaves_view_title'] = 'Xem yêu cầu nghỉ #';
$lang['leaves_view_html_title'] = 'Xem một yêu cầu nghỉ';
$lang['leaves_view_field_start'] = 'Ngày bắt đầu';
$lang['leaves_view_field_end'] = 'Ngày kết thúc';
$lang['leaves_view_field_type'] = 'Loại ngày nghỉ';
$lang['leaves_view_field_duration'] = 'Khoảng thời gian';
$lang['leaves_view_field_cause'] = 'lý do';
$lang['leaves_view_field_status'] = 'Trạng thái';
$lang['leaves_view_button_edit'] = 'Sửa';
$lang['leaves_view_button_back_list'] = 'Trở về danh sách';
$lang['leaves_export_title'] = 'Danh sách yêu cầu nghỉ';
$lang['leaves_export_thead_id'] = 'ID';
$lang['leaves_export_thead_start_date'] = 'Ngày bắt đầu';
$lang['leaves_export_thead_start_date_type'] = 'Sáng/Chiều';
$lang['leaves_export_thead_end_date'] = 'Ngày kết thúc';
$lang['leaves_export_thead_end_date_type'] = 'Sáng/Chiều';
$lang['leaves_export_thead_cause'] = 'lý do';
$lang['leaves_export_thead_duration'] = 'Khoảng thời gian';
$lang['leaves_export_thead_type'] = 'Loại';
$lang['leaves_export_thead_status'] = 'Trạng thái';

$lang['leaves_button_send_reminder'] = 'Send a reminder';
$lang['leaves_reminder_flash_msg_success'] = 'The reminder email was sent to the manager';

$lang['leaves_comment_title'] = 'Comments';
$lang['leaves_comment_new_comment'] = 'New comment';
$lang['leaves_comment_send_comment'] = 'Send comment';
$lang['leaves_comment_author_saying'] = ' says';
$lang['leaves_comment_status_changed'] = 'The status of the leave have been changed to ';
