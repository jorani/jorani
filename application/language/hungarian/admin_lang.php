<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.6.5
 * @author     Transifex users
 */

$lang['admin_diagnostic_title'] = 'Adat & Konfiguráció Diagnosztika';
$lang['admin_diagnostic_description'] = 'Konfiguráció és adat problémák detektálása';
$lang['admin_diagnostic_no_error'] = 'Nincs hiba';
$lang['admin_diagnostic_requests_tab'] = 'Szabadság kérelemek';
$lang['admin_diagnostic_requests_description'] = 'Elfogadott de duplikált szabadság kérelmek';
$lang['admin_diagnostic_requests_thead_id'] = 'ID';
$lang['admin_diagnostic_requests_thead_employee'] = 'Alkalmazott';
$lang['admin_diagnostic_requests_thead_start_date'] = 'Kezdési dátum';
$lang['admin_diagnostic_requests_thead_status'] = 'Állapot';
$lang['admin_diagnostic_requests_thead_type'] = 'Típus';
$lang['admin_diagnostic_datetype_tab'] = 'Délután/Reggel';
$lang['admin_diagnostic_datetype_description'] = 'Szabadság kérelmek amelyek rossz kezdő/vége típusúak.';
$lang['admin_diagnostic_datetype_thead_id'] = 'ID';
$lang['admin_diagnostic_datetype_thead_employee'] = 'Alkalmazott';
$lang['admin_diagnostic_datetype_thead_start_date'] = 'Dátum';
$lang['admin_diagnostic_datetype_thead_start_type'] = 'Kezdés';
$lang['admin_diagnostic_datetype_thead_end_type'] = 'Vége';
$lang['admin_diagnostic_datetype_thead_status'] = 'Állapot';
$lang['admin_diagnostic_entitlements_tab'] = 'Jogosult napok';
$lang['admin_diagnostic_entitlements_description'] = 'Az egy évnél hosszabb jogosultságokkal rendelkező szerződések és alkalmazottak jegyzéke.';
$lang['admin_diagnostic_entitlements_thead_id'] = 'ID';
$lang['admin_diagnostic_entitlements_thead_type'] = 'Típus';
$lang['admin_diagnostic_entitlements_thead_name'] = 'Név';
$lang['admin_diagnostic_entitlements_thead_start_date'] = 'Kezdési dátum';
$lang['admin_diagnostic_entitlements_thead_end_date'] = 'Vége dátum';
$lang['admin_diagnostic_entitlements_type_contract'] = 'Szerződés';
$lang['admin_diagnostic_entitlements_type_employee'] = 'Alkalmazott';
$lang['admin_diagnostic_entitlements_deletion_problem'] = 'Nem befejezett törlések az adatbázisban.';
$lang['admin_diagnostic_daysoff_tab'] = 'Munkaszüneti napok';
$lang['admin_diagnostic_daysoff_description'] = 'Azon napok száma (szerződésenként), amelyeknél a nem munkavégzési idő meghatározásra került.';
$lang['admin_diagnostic_daysoff_thead_id'] = 'ID';
$lang['admin_diagnostic_daysoff_thead_name'] = 'Név';
$lang['admin_diagnostic_daysoff_thead_ym1'] = 'Előző év';
$lang['admin_diagnostic_daysoff_thead_y'] = 'Jelenlegi év';
$lang['admin_diagnostic_daysoff_thead_yp1'] = 'Következő év';
$lang['admin_diagnostic_overtime_tab'] = 'Túlóra';
$lang['admin_diagnostic_overtime_description'] = 'Túlóra kérelem negatív időtartammal';
$lang['admin_diagnostic_overtime_thead_id'] = 'ID';
$lang['admin_diagnostic_overtime_thead_employee'] = 'Alkalmazott';
$lang['admin_diagnostic_overtime_thead_date'] = 'Dátum';
$lang['admin_diagnostic_overtime_thead_duration'] = 'Időtartam';
$lang['admin_diagnostic_overtime_thead_status'] = 'Állapot';
$lang['admin_diagnostic_contract_tab'] = 'Szerződések';
$lang['admin_diagnostic_contract_description'] = 'Fel nem használt szerződések (ellenőrizze, hogy a szerződés nem duplikált-e).';
$lang['admin_diagnostic_contract_thead_id'] = 'ID';
$lang['admin_diagnostic_contract_thead_name'] = 'Név';
$lang['admin_diagnostic_balance_tab'] = 'Egyenleg';
$lang['admin_diagnostic_balance_description'] = 'Jogosultság nélküli szabadság kérelmek.';
$lang['admin_diagnostic_balance_thead_id'] = 'ID';
$lang['admin_diagnostic_balance_thead_employee'] = 'Alkalmazott';
$lang['admin_diagnostic_balance_thead_contract'] = 'Szerződés';
$lang['admin_diagnostic_balance_thead_start_date'] = 'Kezdési dátum';
$lang['admin_diagnostic_balance_thead_status'] = 'Állapot';

$lang['admin_diagnostic_overlapping_tab'] = 'Overlapping';
$lang['admin_diagnostic_overlapping_description'] = 'Leave requests overlapping on two yearly periods.';
$lang['admin_diagnostic_overlapping_thead_id'] = 'ID';
$lang['admin_diagnostic_overlapping_thead_employee'] = 'Employee';
$lang['admin_diagnostic_overlapping_thead_contract'] = 'Contract';
$lang['admin_diagnostic_overlapping_thead_start_date'] = 'Start Date';
$lang['admin_diagnostic_overlapping_thead_end_date'] = 'End Date';
$lang['admin_diagnostic_overlapping_thead_status'] = 'Status';

$lang['admin_oauthclients_title'] = 'OAuth ügyfelek és munkamanatek';
$lang['admin_oauthclients_tab_clients'] = 'Ügyfelek';
$lang['admin_oauthclients_tab_clients_description'] = 'A REST API használatához engedélyezett ügyfelek listája';
$lang['admin_oauthclients_thead_tip_edit'] = 'ügyfél szerkesztése';
$lang['admin_oauthclients_thead_tip_delete'] = 'ügyfél törlése';
$lang['admin_oauthclients_button_add'] = 'Hozzáad';
$lang['admin_oauthclients_popup_add_title'] = 'OAuth ügyfél hozzáadása';
$lang['admin_oauthclients_popup_select_user_title'] = 'Társítás egy létező felhasználóhoz ';
$lang['admin_oauthclients_error_exists'] = 'Ez a client_id már létezik';
$lang['admin_oauthclients_confirm_delete'] = 'Biztos vagy benne, hogy folytatni kívánod?';
$lang['admin_oauthclients_tab_sessions'] = 'Munkamenetek';
$lang['admin_oauthclients_tab_sessions_description'] = 'Aktív REST API OAuth munkamenetek listája';
$lang['admin_oauthclients_button_purge'] = 'Tisztítás';

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
