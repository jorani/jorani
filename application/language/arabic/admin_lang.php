<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.6.5
 * @author     Transifex users
 */

$lang['admin_diagnostic_title'] = 'التاريخ وإعدادات التشخيص';
$lang['admin_diagnostic_description'] = 'كشف للإعدادات والمشاكل في البيانات';
$lang['admin_diagnostic_no_error'] = 'لا يوجد اخطاء';
$lang['admin_diagnostic_requests_tab'] = 'طلبات الإجازات';
$lang['admin_diagnostic_requests_description'] = 'تم قبول طلبات الإجازات ولكن يوجد تداخلات';
$lang['admin_diagnostic_requests_thead_id'] = 'التعريف';
$lang['admin_diagnostic_requests_thead_employee'] = 'الموظف';
$lang['admin_diagnostic_requests_thead_start_date'] = 'تاريخ البدئ';
$lang['admin_diagnostic_requests_thead_status'] = 'الحالة';
$lang['admin_diagnostic_requests_thead_type'] = 'النوع';

$lang['admin_diagnostic_datetype_tab'] = 'بعد الظهر/ قبل الظهر';
$lang['admin_diagnostic_datetype_description'] = 'طلبات الإجازات ذات الأخطاء في البدئ والنهاية';
$lang['admin_diagnostic_datetype_thead_id'] = 'التعريف';
$lang['admin_diagnostic_datetype_thead_employee'] = 'الموظف';
$lang['admin_diagnostic_datetype_thead_start_date'] = 'التاريخ';
$lang['admin_diagnostic_datetype_thead_start_type'] = 'البدئ';
$lang['admin_diagnostic_datetype_thead_end_type'] = 'النهاية';
$lang['admin_diagnostic_datetype_thead_status'] = 'الحالة';

$lang['admin_diagnostic_entitlements_tab'] = 'الأيام المخولة';
$lang['admin_diagnostic_entitlements_description'] = 'قائمة العقود والموظفين المخولين لأكثر من عام.';
$lang['admin_diagnostic_entitlements_thead_id'] = 'التعريف';
$lang['admin_diagnostic_entitlements_thead_type'] = 'النوع';
$lang['admin_diagnostic_entitlements_thead_name'] = 'الإسم';
$lang['admin_diagnostic_entitlements_thead_start_date'] = 'تاريخ البدئ';
$lang['admin_diagnostic_entitlements_thead_end_date'] = 'تاريخ الإنتهاء';
$lang['admin_diagnostic_entitlements_type_contract'] = 'العقد';
$lang['admin_diagnostic_entitlements_type_employee'] = 'الموظف';
$lang['admin_diagnostic_entitlements_deletion_problem'] = 'مسح غير تام في قاعدة البيانات.';

$lang['admin_diagnostic_daysoff_tab'] = 'أيام العطل';
$lang['admin_diagnostic_daysoff_description'] = 'عدد الأيام (حسب العقد) المعرفة فيها العطل.';
$lang['admin_diagnostic_daysoff_thead_id'] = 'التعريف';
$lang['admin_diagnostic_daysoff_thead_name'] = 'الإسم';
$lang['admin_diagnostic_daysoff_thead_ym1'] = 'العام الماضي';
$lang['admin_diagnostic_daysoff_thead_y'] = 'هذا العام';
$lang['admin_diagnostic_daysoff_thead_yp1'] = 'العام القادم';
$lang['admin_diagnostic_overtime_tab'] = 'ساعات اضافية';
$lang['admin_diagnostic_overtime_description'] = 'طلبات الساعات الإضافية بفترات سالبة';
$lang['admin_diagnostic_overtime_thead_id'] = 'التعريف';
$lang['admin_diagnostic_overtime_thead_employee'] = 'الموظف';
$lang['admin_diagnostic_overtime_thead_date'] = 'التاريخ';
$lang['admin_diagnostic_overtime_thead_duration'] = 'المدة';
$lang['admin_diagnostic_overtime_thead_status'] = 'الحالة';
$lang['admin_diagnostic_contract_tab'] = 'العقود';
$lang['admin_diagnostic_contract_description'] = 'عقود غير مستخدمة (يرجى التأكد من عدم تكرار العقد).';
$lang['admin_diagnostic_contract_thead_id'] = 'التعريف';
$lang['admin_diagnostic_contract_thead_name'] = 'الإسم';
$lang['admin_diagnostic_balance_tab'] = 'الرصيد';
$lang['admin_diagnostic_balance_description'] = 'طلبات اجازة بدون رصيد.';
$lang['admin_diagnostic_balance_thead_id'] = 'التعريف';
$lang['admin_diagnostic_balance_thead_employee'] = 'الموظف';
$lang['admin_diagnostic_balance_thead_contract'] = 'العقد';
$lang['admin_diagnostic_balance_thead_start_date'] = 'تاريخ البدئ';
$lang['admin_diagnostic_balance_thead_status'] = 'الحالة';

$lang['admin_diagnostic_overlapping_tab'] = 'Overlapping';
$lang['admin_diagnostic_overlapping_description'] = 'Leave requests overlapping on two yearly periods.';
$lang['admin_diagnostic_overlapping_thead_id'] = 'ID';
$lang['admin_diagnostic_overlapping_thead_employee'] = 'Employee';
$lang['admin_diagnostic_overlapping_thead_contract'] = 'Contract';
$lang['admin_diagnostic_overlapping_thead_start_date'] = 'Start Date';
$lang['admin_diagnostic_overlapping_thead_end_date'] = 'End Date';
$lang['admin_diagnostic_overlapping_thead_status'] = 'Status';

$lang['admin_oauthclients_title'] = 'المستخدمين وجلسات الولوج';
$lang['admin_oauthclients_tab_clients'] = 'المستخدمين';
$lang['admin_oauthclients_tab_clients_description'] = 'قائمة المستخدمين بصلاحيات استخدام الأدوات الشبكية REST API';
$lang['admin_oauthclients_thead_tip_edit'] = 'تعديل مستخدم';
$lang['admin_oauthclients_thead_tip_delete'] = 'مسح مستخدم';
$lang['admin_oauthclients_button_add'] = 'إضافة';
$lang['admin_oauthclients_popup_add_title'] = 'إضافة مستخدم شبكات OAuth';
$lang['admin_oauthclients_popup_select_user_title'] = 'ربط مع مستخدم فعلي';
$lang['admin_oauthclients_error_exists'] = 'رقم المستخدم التعريفي client_id موجود';
$lang['admin_oauthclients_confirm_delete'] = 'هل انت متأكد من الإستمرار؟';
$lang['admin_oauthclients_tab_sessions'] = 'جلسات الولوج';
$lang['admin_oauthclients_tab_sessions_description'] = 'قائمة جلسات ولوج شبكية REST API OAuth';
$lang['admin_oauthclients_button_purge'] = 'تطهير';

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
