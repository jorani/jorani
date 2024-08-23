<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license     http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link          https://github.com/bbalet/jorani
 * @since       0.4.7
 * @author      Ceibga Bao <info@sansin.com.tw>
 */

$lang['admin_diagnostic_title'] = '資料&設定診斷';
$lang['admin_diagnostic_description'] = '判定資料和設定問題';
$lang['admin_diagnostic_no_error'] = '沒有錯誤';

$lang['admin_diagnostic_requests_tab'] = '請假求';
$lang['admin_diagnostic_requests_description'] = 'Accepted but duplicated Leave Requests';
$lang['admin_diagnostic_requests_thead_id'] = '證號';
$lang['admin_diagnostic_requests_thead_employee'] = '員工';
$lang['admin_diagnostic_requests_thead_start_date'] = '開始日期';
$lang['admin_diagnostic_requests_thead_status'] = '職位';
$lang['admin_diagnostic_requests_thead_type'] = '編輯';

$lang['admin_diagnostic_datetype_tab'] = '上午/下午';
$lang['admin_diagnostic_datetype_description'] = '請假時間錯誤';
$lang['admin_diagnostic_datetype_thead_id'] = '證號';
$lang['admin_diagnostic_datetype_thead_employee'] = '員工';
$lang['admin_diagnostic_datetype_thead_start_date'] = '日期';
$lang['admin_diagnostic_datetype_thead_start_type'] = '開始';
$lang['admin_diagnostic_datetype_thead_end_type'] = '結束';
$lang['admin_diagnostic_datetype_thead_status'] = '職位';

$lang['admin_diagnostic_entitlements_tab'] = '享有類別';
$lang['admin_diagnostic_entitlements_description'] = 'List of contracts and employees having entitlements for more than one year.';
$lang['admin_diagnostic_entitlements_thead_id'] = '證號';
$lang['admin_diagnostic_entitlements_thead_type'] = '編輯';
$lang['admin_diagnostic_entitlements_thead_name'] = '名字';
$lang['admin_diagnostic_entitlements_thead_start_date'] = '開始日期';
$lang['admin_diagnostic_entitlements_thead_end_date'] = '結束日期';
$lang['admin_diagnostic_entitlements_type_contract'] = '類別';
$lang['admin_diagnostic_entitlements_type_employee'] = '員工';
$lang['admin_diagnostic_entitlements_deletion_problem'] = '刪除資料失敗';

$lang['admin_diagnostic_daysoff_tab'] = '休假日';
$lang['admin_diagnostic_daysoff_description'] = 'Number of days (per contract) for which a non-working duration has been defined.';
$lang['admin_diagnostic_daysoff_thead_id'] = '證號';
$lang['admin_diagnostic_daysoff_thead_name'] = '名字';
$lang['admin_diagnostic_daysoff_thead_ym1'] = '去年';
$lang['admin_diagnostic_daysoff_thead_y'] = '今年';
$lang['admin_diagnostic_daysoff_thead_yp1'] = '明年';

$lang['admin_diagnostic_overtime_tab'] = '加班';
$lang['admin_diagnostic_overtime_description'] = 'Overtime requests with a negative duration';
$lang['admin_diagnostic_overtime_thead_id'] = '證號';
$lang['admin_diagnostic_overtime_thead_employee'] = '員工';
$lang['admin_diagnostic_overtime_thead_date'] = '日期';
$lang['admin_diagnostic_overtime_thead_duration'] = '時段';
$lang['admin_diagnostic_overtime_thead_status'] = '職位';

$lang['admin_diagnostic_contract_tab'] = '類別';
$lang['admin_diagnostic_contract_description'] = '沒使用的類別（請檢查重複類別）';
$lang['admin_diagnostic_contract_thead_id'] = '證號';
$lang['admin_diagnostic_contract_thead_name'] = '名字';

$lang['admin_diagnostic_balance_tab'] = '餘額';
$lang['admin_diagnostic_balance_description'] = 'Leave requests for which there are no entitlments.';
$lang['admin_diagnostic_balance_thead_id'] = '證號';
$lang['admin_diagnostic_balance_thead_employee'] = '員工';
$lang['admin_diagnostic_balance_thead_contract'] = '類別';
$lang['admin_diagnostic_balance_thead_start_date'] = '開始日期';
$lang['admin_diagnostic_balance_thead_status'] = '職位';

$lang['admin_diagnostic_overlapping_tab'] = 'Overlapping';
$lang['admin_diagnostic_overlapping_description'] = 'Leave requests overlapping on two yearly periods.';
$lang['admin_diagnostic_overlapping_thead_id'] = 'ID';
$lang['admin_diagnostic_overlapping_thead_employee'] = 'Employee';
$lang['admin_diagnostic_overlapping_thead_contract'] = 'Contract';
$lang['admin_diagnostic_overlapping_thead_start_date'] = 'Start Date';
$lang['admin_diagnostic_overlapping_thead_end_date'] = 'End Date';
$lang['admin_diagnostic_overlapping_thead_status'] = 'Status';

$lang['admin_oauthclients_title'] = 'OAuth clients and sessions';
$lang['admin_oauthclients_tab_clients'] = 'Clients';
$lang['admin_oauthclients_tab_clients_description'] = 'List of clients allowed to use the REST API';
$lang['admin_oauthclients_thead_tip_edit'] = 'edit client';
$lang['admin_oauthclients_thead_tip_delete'] = 'delete client';
$lang['admin_oauthclients_button_add'] = 'Add';
$lang['admin_oauthclients_popup_add_title'] = 'Add OAuth Client';
$lang['admin_oauthclients_popup_select_user_title'] = 'Associate to an actual user';
$lang['admin_oauthclients_error_exists'] = 'This client_id already exists';
$lang['admin_oauthclients_confirm_delete'] = 'Are you sure that you want to proceed?';
$lang['admin_oauthclients_tab_sessions'] = 'Sessions';
$lang['admin_oauthclients_tab_sessions_description'] = 'List of active REST API OAuth Sessions';
$lang['admin_oauthclients_button_purge'] = 'Purge';

$lang['admin_diagnostic_teleworks_requests_tab'] = 'Telework requests';
$lang['admin_diagnostic_teleworks_requests_description'] = 'Telework requests accepted and duplicated';
$lang['admin_diagnostic_teleworks_requests_thead_id'] = 'N°';
$lang['admin_diagnostic_teleworks_requests_thead_employee'] = 'Employee';
$lang['admin_diagnostic_teleworks_requests_thead_start_date'] = 'Start date';
$lang['admin_diagnostic_teleworks_requests_thead_status'] = 'Status';
$lang['admin_diagnostic_teleworks_requests_thead_type'] = 'Type';

$lang['admin_diagnostic_teleworks_datetype_tab'] = 'Afternoon/Morning (TW)';
$lang['admin_diagnostic_teleworks_datetype_description'] = 'Telework requests with wrong start/end type';
$lang['admin_diagnostic_teleworks_datetype_thead_id'] = 'N°';
$lang['admin_diagnostic_teleworks_datetype_thead_employee'] = 'Employee';
$lang['admin_diagnostic_teleworks_datetype_thead_start_date'] = 'Date';
$lang['admin_diagnostic_teleworks_datetype_thead_start_type'] = 'Start';
$lang['admin_diagnostic_teleworks_datetype_thead_end_type'] = 'End';
$lang['admin_diagnostic_teleworks_datetype_thead_status'] = 'Status';

$lang['admin_diagnostic_teleworks_overlapping_tab'] = 'Overlapping (TW)';
$lang['admin_diagnostic_teleworks_overlapping_description'] = 'Telework requests overlapping two annual periods';
$lang['admin_diagnostic_teleworks_overlapping_thead_id'] = 'ID';
$lang['admin_diagnostic_teleworks_overlapping_thead_employee'] = 'Employee';
$lang['admin_diagnostic_teleworks_overlapping_thead_contract'] = 'Contract';
$lang['admin_diagnostic_teleworks_overlapping_thead_start_date'] = 'Start date';
$lang['admin_diagnostic_teleworks_overlapping_thead_end_date'] = 'End date';
$lang['admin_diagnostic_teleworks_overlapping_thead_status'] = 'Status';
