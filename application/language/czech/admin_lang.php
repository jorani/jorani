<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.6.0
 * @author     Transifex contributors
 */

$lang['admin_diagnostic_title'] = 'Data & Nastavení Diagnostiky';
$lang['admin_diagnostic_description'] = 'Detekce konfigurace a problémů s daty';
$lang['admin_diagnostic_no_error'] = 'Žádná chyba';
$lang['admin_diagnostic_requests_tab'] = 'Žádosti o dovolenou';
$lang['admin_diagnostic_requests_description'] = 'Schválené, ale duplicitní žádosti o dovolenou';
$lang['admin_diagnostic_requests_thead_id'] = 'Identifikace';
$lang['admin_diagnostic_requests_thead_employee'] = 'Zaměstnanec';
$lang['admin_diagnostic_requests_thead_start_date'] = 'Datum začátku';
$lang['admin_diagnostic_requests_thead_status'] = 'Stav';
$lang['admin_diagnostic_requests_thead_type'] = 'Typ';
$lang['admin_diagnostic_datetype_tab'] = 'Odpoledne/Ráno';
$lang['admin_diagnostic_datetype_description'] = 'Požadavek dovolené s neplatným typem začátku/konce.';
$lang['admin_diagnostic_datetype_thead_id'] = 'Identifikace';
$lang['admin_diagnostic_datetype_thead_employee'] = 'Zaměstnanec';
$lang['admin_diagnostic_datetype_thead_start_date'] = 'Datum';
$lang['admin_diagnostic_datetype_thead_start_type'] = 'Začátek';
$lang['admin_diagnostic_datetype_thead_end_type'] = 'Konec';
$lang['admin_diagnostic_datetype_thead_status'] = 'Stav';
$lang['admin_diagnostic_entitlements_tab'] = 'Nárok dní';
$lang['admin_diagnostic_entitlements_description'] = 'Seznam smluv a zaměstnanců, kteří mají nároky na dobu delší než jeden rok.';
$lang['admin_diagnostic_entitlements_thead_id'] = 'Identifikace';
$lang['admin_diagnostic_entitlements_thead_type'] = 'Typ';
$lang['admin_diagnostic_entitlements_thead_name'] = 'Jméno';
$lang['admin_diagnostic_entitlements_thead_start_date'] = 'Datum začátku';
$lang['admin_diagnostic_entitlements_thead_end_date'] = 'Datum konce';
$lang['admin_diagnostic_entitlements_type_contract'] = 'Smlouva';
$lang['admin_diagnostic_entitlements_type_employee'] = 'Zaměstnanec';
$lang['admin_diagnostic_entitlements_deletion_problem'] = 'Neúplné vymazání v databázi.';

$lang['admin_diagnostic_daysoff_tab'] = 'Nepracovní dny';
$lang['admin_diagnostic_daysoff_description'] = 'Počet dnů (za smlouva) pracovního volna je definováno ve smlouvě.';
$lang['admin_diagnostic_daysoff_thead_id'] = 'Identifikace';
$lang['admin_diagnostic_daysoff_thead_name'] = 'Jméno';
$lang['admin_diagnostic_daysoff_thead_ym1'] = 'Poslední rok';
$lang['admin_diagnostic_daysoff_thead_y'] = 'Tento rok';
$lang['admin_diagnostic_daysoff_thead_yp1'] = 'Další rok';
$lang['admin_diagnostic_overtime_tab'] = 'Přesčasy';
$lang['admin_diagnostic_overtime_description'] = 'Žádosti o přesčas se záporným počtem dní';
$lang['admin_diagnostic_overtime_thead_id'] = 'Identifikace';
$lang['admin_diagnostic_overtime_thead_employee'] = 'Zaměstnanec';
$lang['admin_diagnostic_overtime_thead_date'] = 'Datum';
$lang['admin_diagnostic_overtime_thead_duration'] = 'Počet dnů';
$lang['admin_diagnostic_overtime_thead_status'] = 'Stav';
$lang['admin_diagnostic_contract_tab'] = 'Smlouvy';
$lang['admin_diagnostic_contract_description'] = 'Nepoužité smlouvy (zkontrolujte zda není smlouva duplicitní)';
$lang['admin_diagnostic_contract_thead_id'] = 'Identifikace';
$lang['admin_diagnostic_contract_thead_name'] = 'Jméno';
$lang['admin_diagnostic_balance_tab'] = 'Bilance';
$lang['admin_diagnostic_balance_description'] = 'Žádosti o dovolenou, na které není nárok.';
$lang['admin_diagnostic_balance_thead_id'] = 'Identifikace';
$lang['admin_diagnostic_balance_thead_employee'] = 'Zaměstnanec';
$lang['admin_diagnostic_balance_thead_contract'] = 'Smlouva';
$lang['admin_diagnostic_balance_thead_start_date'] = 'Datum začátku';
$lang['admin_diagnostic_balance_thead_status'] = 'Stav';

$lang['admin_diagnostic_overlapping_tab'] = 'Overlapping';
$lang['admin_diagnostic_overlapping_description'] = 'Leave requests overlapping on two yearly periods.';
$lang['admin_diagnostic_overlapping_thead_id'] = 'ID';
$lang['admin_diagnostic_overlapping_thead_employee'] = 'Employee';
$lang['admin_diagnostic_overlapping_thead_contract'] = 'Contract';
$lang['admin_diagnostic_overlapping_thead_start_date'] = 'Start Date';
$lang['admin_diagnostic_overlapping_thead_end_date'] = 'End Date';
$lang['admin_diagnostic_overlapping_thead_status'] = 'Status';

$lang['admin_oauthclients_title'] = 'OAuth klienti a relací';
$lang['admin_oauthclients_tab_clients'] = 'Klienti';
$lang['admin_oauthclients_tab_clients_description'] = 'Seznam klientů kteří mají dovoleno použít REST API';
$lang['admin_oauthclients_thead_tip_edit'] = 'editovat klienta';
$lang['admin_oauthclients_thead_tip_delete'] = 'smazat klienta';
$lang['admin_oauthclients_button_add'] = 'Přidat';
$lang['admin_oauthclients_popup_add_title'] = 'Přidat OAuth klienta';
$lang['admin_oauthclients_popup_select_user_title'] = 'Spojit se současným uživatelem';
$lang['admin_oauthclients_error_exists'] = 'Toto client_id existuje';
$lang['admin_oauthclients_confirm_delete'] = 'Jste si jisti, že chcete pokračovat?';
$lang['admin_oauthclients_tab_sessions'] = 'Relace';
$lang['admin_oauthclients_tab_sessions_description'] = 'Seznam aktivních REST API Oauth relací';
$lang['admin_oauthclients_button_purge'] = 'Vyčistit';

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
