<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.6.6
 * @author     Transifex users
 */

$lang['admin_diagnostic_title'] = 'Data & Config Diagnostic';
$lang['admin_diagnostic_description'] = 'Detectarea configurării și probleme cu datele';
$lang['admin_diagnostic_no_error'] = 'Fără eroare';
$lang['admin_diagnostic_requests_tab'] = 'Cereri de concediu';
$lang['admin_diagnostic_requests_description'] = 'Cereri de concediu acceptate dar dublate';
$lang['admin_diagnostic_requests_thead_id'] = 'ID';
$lang['admin_diagnostic_requests_thead_employee'] = 'Angajat';
$lang['admin_diagnostic_requests_thead_start_date'] = 'Dată start';
$lang['admin_diagnostic_requests_thead_status'] = 'Status';
$lang['admin_diagnostic_requests_thead_type'] = 'Tip';
$lang['admin_diagnostic_datetype_tab'] = 'După masă/Dimineaţă';
$lang['admin_diagnostic_datetype_description'] = 'Cereri de concediu cu data de start/sfârșit greșite.';
$lang['admin_diagnostic_datetype_thead_id'] = 'ID';
$lang['admin_diagnostic_datetype_thead_employee'] = 'Angajat';
$lang['admin_diagnostic_datetype_thead_start_date'] = 'Data';
$lang['admin_diagnostic_datetype_thead_start_type'] = 'Început';
$lang['admin_diagnostic_datetype_thead_end_type'] = 'Sfârşit';
$lang['admin_diagnostic_datetype_thead_status'] = 'Status';
$lang['admin_diagnostic_entitlements_tab'] = 'Zile de concediu disponibile';
$lang['admin_diagnostic_entitlements_description'] = 'Lista de contracte și angajați având drepturi pentru mai mult de un an.';
$lang['admin_diagnostic_entitlements_thead_id'] = 'ID';
$lang['admin_diagnostic_entitlements_thead_type'] = 'Tip';
$lang['admin_diagnostic_entitlements_thead_name'] = 'Nume';
$lang['admin_diagnostic_entitlements_thead_start_date'] = 'Dată start';
$lang['admin_diagnostic_entitlements_thead_end_date'] = 'Dată sfârşit';
$lang['admin_diagnostic_entitlements_type_contract'] = 'Contract';
$lang['admin_diagnostic_entitlements_type_employee'] = 'Angajat';
$lang['admin_diagnostic_entitlements_deletion_problem'] = 'Ștergere incompletă din baza de date.';
$lang['admin_diagnostic_daysoff_tab'] = 'Zile nelucrătoare';
$lang['admin_diagnostic_daysoff_description'] = 'Număr de zile (per contract) pentru care durata de zile nelucrătoare a fost definită.';
$lang['admin_diagnostic_daysoff_thead_id'] = 'ID';
$lang['admin_diagnostic_daysoff_thead_name'] = 'Nume';
$lang['admin_diagnostic_daysoff_thead_ym1'] = 'Anul trecut';
$lang['admin_diagnostic_daysoff_thead_y'] = 'Anul curent';
$lang['admin_diagnostic_daysoff_thead_yp1'] = 'Anul viitor';
$lang['admin_diagnostic_overtime_tab'] = 'Ore suplimentare';
$lang['admin_diagnostic_overtime_description'] = 'Ore suplimentare cu o durată negativă';
$lang['admin_diagnostic_overtime_thead_id'] = 'ID';
$lang['admin_diagnostic_overtime_thead_employee'] = 'Angajat';
$lang['admin_diagnostic_overtime_thead_date'] = 'Data';
$lang['admin_diagnostic_overtime_thead_duration'] = 'Durata';
$lang['admin_diagnostic_overtime_thead_status'] = 'Status';
$lang['admin_diagnostic_contract_tab'] = 'Contracte';
$lang['admin_diagnostic_contract_description'] = 'Contracte nefolosite (verifică dacă sunt duplicate)';
$lang['admin_diagnostic_contract_thead_id'] = 'ID';
$lang['admin_diagnostic_contract_thead_name'] = 'Nume';
$lang['admin_diagnostic_balance_tab'] = 'Balanță';
$lang['admin_diagnostic_balance_description'] = 'Cereri de concediu care nu au zile disponibile.';
$lang['admin_diagnostic_balance_thead_id'] = 'ID';
$lang['admin_diagnostic_balance_thead_employee'] = 'Angajat';
$lang['admin_diagnostic_balance_thead_contract'] = 'Contract';
$lang['admin_diagnostic_balance_thead_start_date'] = 'Dată start';
$lang['admin_diagnostic_balance_thead_status'] = 'Status';

$lang['admin_diagnostic_overlapping_tab'] = 'Overlapping';
$lang['admin_diagnostic_overlapping_description'] = 'Leave requests overlapping on two yearly periods.';
$lang['admin_diagnostic_overlapping_thead_id'] = 'ID';
$lang['admin_diagnostic_overlapping_thead_employee'] = 'Employee';
$lang['admin_diagnostic_overlapping_thead_contract'] = 'Contract';
$lang['admin_diagnostic_overlapping_thead_start_date'] = 'Start Date';
$lang['admin_diagnostic_overlapping_thead_end_date'] = 'End Date';
$lang['admin_diagnostic_overlapping_thead_status'] = 'Status';

$lang['admin_oauthclients_title'] = 'Clienți si sesiuni OAuth';
$lang['admin_oauthclients_tab_clients'] = 'Clienți';
$lang['admin_oauthclients_tab_clients_description'] = 'Lista de clienți acceptati pentru a folosi REST API';
$lang['admin_oauthclients_thead_tip_edit'] = 'editează client';
$lang['admin_oauthclients_thead_tip_delete'] = 'șterge client';
$lang['admin_oauthclients_button_add'] = 'Adaugă';
$lang['admin_oauthclients_popup_add_title'] = 'Adaugă client OAuth';
$lang['admin_oauthclients_popup_select_user_title'] = 'Asociază cu un utilizator existent';
$lang['admin_oauthclients_error_exists'] = 'Acest client_id deja există';
$lang['admin_oauthclients_confirm_delete'] = 'Sigur dorești să continui?';
$lang['admin_oauthclients_tab_sessions'] = 'Sesiuni';
$lang['admin_oauthclients_tab_sessions_description'] = 'Lista sesiunilor REST API OAuth active';
$lang['admin_oauthclients_button_purge'] = 'Elimină';

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
