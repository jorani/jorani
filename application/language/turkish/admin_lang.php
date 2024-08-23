<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license     http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link          https://github.com/bbalet/jorani
 * @since       0.4.6
 * @author      Ela Alptekin
 */

$lang['admin_diagnostic_title'] = 'Veri ve Konfigürasyon Teşhisi';
$lang['admin_diagnostic_description'] = 'Konfigürasyon ve veri sorunlarının tespiti';
$lang['admin_diagnostic_no_error'] = 'Hata bulunamadı';

$lang['admin_diagnostic_requests_tab'] = 'İzin Talepleri';
$lang['admin_diagnostic_requests_description'] = 'Kabul edilmiş ancak tekrarlanan İzin Talepleri';
$lang['admin_diagnostic_requests_thead_id'] = 'ID';
$lang['admin_diagnostic_requests_thead_employee'] = 'Çalışan';
$lang['admin_diagnostic_requests_thead_start_date'] = 'Başlangıç Tarihi';
$lang['admin_diagnostic_requests_thead_status'] = 'Durum';
$lang['admin_diagnostic_requests_thead_type'] = 'Tür';

$lang['admin_diagnostic_datetype_tab'] = 'Öğleden sonra/Sabah';
$lang['admin_diagnostic_datetype_description'] = 'Yanlış bir başlangıç/bitiş türüne sahip İzin Talepleri.';
$lang['admin_diagnostic_datetype_thead_id'] = 'ID';
$lang['admin_diagnostic_datetype_thead_employee'] = 'Çalışan';
$lang['admin_diagnostic_datetype_thead_start_date'] = 'Tarih';
$lang['admin_diagnostic_datetype_thead_start_type'] = 'Başlangıç';
$lang['admin_diagnostic_datetype_thead_end_type'] = 'Bitiş';
$lang['admin_diagnostic_datetype_thead_status'] = 'Durum';

$lang['admin_diagnostic_entitlements_tab'] = 'Hak edilen günler';
$lang['admin_diagnostic_entitlements_description'] = 'Bir yıldan fazla izin hakkına sahip sözleşmelerin ve çalışanların listesi.';
$lang['admin_diagnostic_entitlements_thead_id'] = 'ID';
$lang['admin_diagnostic_entitlements_thead_type'] = 'Tür';
$lang['admin_diagnostic_entitlements_thead_name'] = 'İsim';
$lang['admin_diagnostic_entitlements_thead_start_date'] = 'Başlangıç Tarihi';
$lang['admin_diagnostic_entitlements_thead_end_date'] = 'Bitiş Tarihi';
$lang['admin_diagnostic_entitlements_type_contract'] = 'Contract';
$lang['admin_diagnostic_entitlements_type_employee'] = 'Çalışan';
$lang['admin_diagnostic_entitlements_deletion_problem'] = 'Veritabanındaki tamamlanmamış silme işlemleri.' ;

$lang['admin_diagnostic_daysoff_tab'] = 'Çalışılmayan günler';
$lang['admin_diagnostic_daysoff_description'] = 'Number of days (per contract) for which a non-working duration has been defined.';
$lang['admin_diagnostic_daysoff_thead_id'] = 'ID';
$lang['admin_diagnostic_daysoff_thead_name'] = 'İsim';
$lang['admin_diagnostic_daysoff_thead_ym1'] = 'Geçen yıl';
$lang['admin_diagnostic_daysoff_thead_y'] = 'Bu yıl';
$lang['admin_diagnostic_daysoff_thead_yp1'] = 'Gelecek yıl';

$lang['admin_diagnostic_overtime_tab'] = 'Fazla mesai';
$lang['admin_diagnostic_overtime_description'] = 'Negatif süreli fazla mesai istekleri';
$lang['admin_diagnostic_overtime_thead_id'] = 'ID';
$lang['admin_diagnostic_overtime_thead_employee'] = 'Çalışan';
$lang['admin_diagnostic_overtime_thead_date'] = 'Tarih';
$lang['admin_diagnostic_overtime_thead_duration'] = 'Süre';
$lang['admin_diagnostic_overtime_thead_status'] = 'Durum';

$lang['admin_diagnostic_contract_tab'] = 'Sözleşmeler';
$lang['admin_diagnostic_contract_description'] = 'Kullanılmamış sözleşmeler (sözleşmenin tekrarlanmış olup olmadığını kontrol et).';
$lang['admin_diagnostic_contract_thead_id'] = 'ID';
$lang['admin_diagnostic_contract_thead_name'] = 'İsim';

$lang['admin_diagnostic_balance_tab'] = 'Bakiye';
$lang['admin_diagnostic_balance_description'] = 'Kazanılmış izin hakkı olmayanların izin istekleri.';
$lang['admin_diagnostic_balance_thead_id'] = 'ID';
$lang['admin_diagnostic_balance_thead_employee'] = 'Çalışan';
$lang['admin_diagnostic_balance_thead_contract'] = 'Sözleşme';
$lang['admin_diagnostic_balance_thead_start_date'] = 'Başlangıç Tarihi';
$lang['admin_diagnostic_balance_thead_status'] = 'Durum';

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
