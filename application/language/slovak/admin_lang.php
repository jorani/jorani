<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      1.0.0
 * @author     Transifex users
 */

$lang['admin_diagnostic_title'] = 'Diagnostika dát a nastavenia';
$lang['admin_diagnostic_description'] = 'Detekcia problémov nastavenia a dát';
$lang['admin_diagnostic_no_error'] = 'Žiadna chyba';
$lang['admin_diagnostic_requests_tab'] = 'Žiadosti o voľno';
$lang['admin_diagnostic_requests_description'] = 'Schválené, ale duplicitná žiadosť o voľno';
$lang['admin_diagnostic_requests_thead_id'] = 'ID';
$lang['admin_diagnostic_requests_thead_employee'] = 'Zamestnanec';
$lang['admin_diagnostic_requests_thead_start_date'] = 'Dátum začiatku';
$lang['admin_diagnostic_requests_thead_status'] = 'Stav';
$lang['admin_diagnostic_requests_thead_type'] = 'Typ';
$lang['admin_diagnostic_datetype_tab'] = 'Poobede/Doobeda';
$lang['admin_diagnostic_datetype_description'] = 'Žiadosť o voľno s neplatným typom začiatku/konca.';
$lang['admin_diagnostic_datetype_thead_id'] = 'ID';
$lang['admin_diagnostic_datetype_thead_employee'] = 'Zamestnanec';
$lang['admin_diagnostic_datetype_thead_start_date'] = 'Dátum';
$lang['admin_diagnostic_datetype_thead_start_type'] = 'Začiatok';
$lang['admin_diagnostic_datetype_thead_end_type'] = 'Koniec';
$lang['admin_diagnostic_datetype_thead_status'] = 'Stav';
$lang['admin_diagnostic_entitlements_tab'] = 'Nárok na dni pracovného voľna';
$lang['admin_diagnostic_entitlements_description'] = 'Zoznam zmlúv a zamestnancov s nárokom na dni pracovného voľna na viac ako jeden rok.';
$lang['admin_diagnostic_entitlements_thead_id'] = 'ID';
$lang['admin_diagnostic_entitlements_thead_type'] = 'Typ';
$lang['admin_diagnostic_entitlements_thead_name'] = 'Meno';
$lang['admin_diagnostic_entitlements_thead_start_date'] = 'Dátum začiatku';
$lang['admin_diagnostic_entitlements_thead_end_date'] = 'Dátum ukončenia';
$lang['admin_diagnostic_entitlements_type_contract'] = 'Kontrakt';
$lang['admin_diagnostic_entitlements_type_employee'] = 'Zamestnanec';
$lang['admin_diagnostic_entitlements_deletion_problem'] = 'Neúplné vymazanie v databázi.';
$lang['admin_diagnostic_daysoff_tab'] = 'Nepracovné dni';
$lang['admin_diagnostic_daysoff_description'] = 'Počet dní (za jednotlivé kontrakty), pre ktoré bolo zadefinované nepracovné obdobie.';
$lang['admin_diagnostic_daysoff_thead_id'] = 'ID';
$lang['admin_diagnostic_daysoff_thead_name'] = 'Meno';
$lang['admin_diagnostic_daysoff_thead_ym1'] = 'Minulý rok';
$lang['admin_diagnostic_daysoff_thead_y'] = 'Tento rok';
$lang['admin_diagnostic_daysoff_thead_yp1'] = 'Nasledujúci rok';
$lang['admin_diagnostic_overtime_tab'] = 'Nadčas';
$lang['admin_diagnostic_overtime_description'] = 'Nadčas požadovaný so zápornou hodnotou';
$lang['admin_diagnostic_overtime_thead_id'] = 'ID';
$lang['admin_diagnostic_overtime_thead_employee'] = 'Zamestnanec';
$lang['admin_diagnostic_overtime_thead_date'] = 'Dátum';
$lang['admin_diagnostic_overtime_thead_duration'] = 'Trvanie';
$lang['admin_diagnostic_overtime_thead_status'] = 'Stav';
$lang['admin_diagnostic_contract_tab'] = 'Zmluvy.';
$lang['admin_diagnostic_contract_description'] = 'Nepoužité zmluvy (preverte, či zmluva nemá duplikát).';
$lang['admin_diagnostic_contract_thead_id'] = 'ID';
$lang['admin_diagnostic_contract_thead_name'] = 'Meno';
$lang['admin_diagnostic_balance_tab'] = 'Zostatok';
$lang['admin_diagnostic_balance_description'] = 'Žiadosti o pracovné voľno, ku ktorým neexistuje nárok na voľno.';
$lang['admin_diagnostic_balance_thead_id'] = 'ID';
$lang['admin_diagnostic_balance_thead_employee'] = 'Zamestnanec';
$lang['admin_diagnostic_balance_thead_contract'] = 'Kontrakt';
$lang['admin_diagnostic_balance_thead_start_date'] = 'Dátum začiatku';
$lang['admin_diagnostic_balance_thead_status'] = 'Stav';
$lang['admin_diagnostic_overlapping_tab'] = 'Overlapping';
$lang['admin_diagnostic_overlapping_description'] = 'Leave requests overlapping on two yearly periods.';
$lang['admin_diagnostic_overlapping_thead_id'] = 'ID';
$lang['admin_diagnostic_overlapping_thead_employee'] = 'Zamestnanec';
$lang['admin_diagnostic_overlapping_thead_contract'] = 'Kontrakt';
$lang['admin_diagnostic_overlapping_thead_start_date'] = 'Dátum začiatku';
$lang['admin_diagnostic_overlapping_thead_end_date'] = 'Dátum ukončenia';
$lang['admin_diagnostic_overlapping_thead_status'] = 'Stav';
$lang['admin_oauthclients_title'] = 'OAuth klienti a relácie.';
$lang['admin_oauthclients_tab_clients'] = 'Klienti.';
$lang['admin_oauthclients_tab_clients_description'] = 'Zoznam klientov oprávnených použiť REST API.';
$lang['admin_oauthclients_thead_tip_edit'] = 'Editovať klienta.';
$lang['admin_oauthclients_thead_tip_delete'] = 'Zmazať klienta.';
$lang['admin_oauthclients_button_add'] = 'Pridať';
$lang['admin_oauthclients_popup_add_title'] = 'Pridať OAuth klienta';
$lang['admin_oauthclients_popup_select_user_title'] = 'Zlúčiť s aktuálnym užívateľom';
$lang['admin_oauthclients_error_exists'] = 'Toto client_id už existuje';
$lang['admin_oauthclients_confirm_delete'] = 'Naozaj chcete pokračovať?';
$lang['admin_oauthclients_tab_sessions'] = 'Relácie';
$lang['admin_oauthclients_tab_sessions_description'] = 'Zoznam aktívnych REST API OAuth relácií';
$lang['admin_oauthclients_button_purge'] = 'Vyčistiť';

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
