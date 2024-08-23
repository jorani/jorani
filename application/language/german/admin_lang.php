<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.6.5
 * @author     Transifex users
 */

$lang['admin_diagnostic_title'] = 'Daten- und Konfigurationsdiagnose';
$lang['admin_diagnostic_description'] = 'Erkennung von Problemen mit der Konfiguration und Datenbasis';
$lang['admin_diagnostic_no_error'] = 'Kein Fehler';
$lang['admin_diagnostic_requests_tab'] = 'Abwesenheitsanfragen';
$lang['admin_diagnostic_requests_description'] = 'Akzeptierte, jedoch duplizierte Abwesenheitsanfragen';
$lang['admin_diagnostic_requests_thead_id'] = 'ID';
$lang['admin_diagnostic_requests_thead_employee'] = 'Angestellter';
$lang['admin_diagnostic_requests_thead_start_date'] = 'Anfangsdatum';
$lang['admin_diagnostic_requests_thead_status'] = 'Status';
$lang['admin_diagnostic_requests_thead_type'] = 'Typ';
$lang['admin_diagnostic_datetype_tab'] = 'Nachmittag/Morgen';
$lang['admin_diagnostic_datetype_description'] = 'Abwesenheitsanfrage mit einem falschen Start/Ende Typ.';
$lang['admin_diagnostic_datetype_thead_id'] = 'ID';
$lang['admin_diagnostic_datetype_thead_employee'] = 'Angestellter';
$lang['admin_diagnostic_datetype_thead_start_date'] = 'Datum';
$lang['admin_diagnostic_datetype_thead_start_type'] = 'Anfang';
$lang['admin_diagnostic_datetype_thead_end_type'] = 'Ende';
$lang['admin_diagnostic_datetype_thead_status'] = 'Status';
$lang['admin_diagnostic_entitlements_tab'] = 'Bezugsberechtigte Tage';
$lang['admin_diagnostic_entitlements_description'] = 'Liste der Verträge und Angestellten, die Ansprüche über mehr als ein Jahr haben.';
$lang['admin_diagnostic_entitlements_thead_id'] = 'ID';
$lang['admin_diagnostic_entitlements_thead_type'] = 'Typ';
$lang['admin_diagnostic_entitlements_thead_name'] = 'Name';
$lang['admin_diagnostic_entitlements_thead_start_date'] = 'Anfangsdatum';
$lang['admin_diagnostic_entitlements_thead_end_date'] = 'Enddatum';
$lang['admin_diagnostic_entitlements_type_contract'] = 'Vertrag';
$lang['admin_diagnostic_entitlements_type_employee'] = 'Angestellter';
$lang['admin_diagnostic_entitlements_deletion_problem'] = 'Unvollständige Löschung von Einträgen in der Datenbank';
$lang['admin_diagnostic_daysoff_tab'] = 'Arbeitsfreie Tage';
$lang['admin_diagnostic_daysoff_description'] = 'Anzahl an Tagen (pro Vertrag), für die eine Nicht-Arbeitsdauer festgelegt wurde.';
$lang['admin_diagnostic_daysoff_thead_id'] = 'ID';
$lang['admin_diagnostic_daysoff_thead_name'] = 'Name';
$lang['admin_diagnostic_daysoff_thead_ym1'] = 'Letztes Jahr';
$lang['admin_diagnostic_daysoff_thead_y'] = 'Dieses Jahr';
$lang['admin_diagnostic_daysoff_thead_yp1'] = 'Nächstes Jahr';
$lang['admin_diagnostic_overtime_tab'] = 'Überstunden';
$lang['admin_diagnostic_overtime_description'] = 'Überstundenanfrage mit einer negativen Dauer';
$lang['admin_diagnostic_overtime_thead_id'] = 'ID';
$lang['admin_diagnostic_overtime_thead_employee'] = 'Angestellter';
$lang['admin_diagnostic_overtime_thead_date'] = 'Datum';
$lang['admin_diagnostic_overtime_thead_duration'] = 'Dauer';
$lang['admin_diagnostic_overtime_thead_status'] = 'Status';

$lang['admin_diagnostic_teleworks_requests_tab'] = 'Anträge auf Telearbeit';
$lang['admin_diagnostic_teleworks_requests_description'] = 'Telearbeitsanträge akzeptiert und dupliziert';
$lang['admin_diagnostic_teleworks_requests_thead_id'] = 'Nein';
$lang['admin_diagnostic_teleworks_requests_thead_employee'] = 'Mitarbeiter';
$lang['admin_diagnostic_teleworks_requests_thead_start_date'] = 'Startdatum';
$lang['admin_diagnostic_teleworks_requests_thead_status'] = 'Status';
$lang['admin_diagnostic_teleworks_requests_thead_type'] = 'Typ';

$lang['admin_diagnostic_teleworks_datetype_tab'] = 'Nachmittag/Morgen (TT)';
$lang['admin_diagnostic_teleworks_datetype_description'] = 'Telearbeitsanträge mit falschem Start-/Endtyp';
$lang['admin_diagnostic_teleworks_datetype_thead_id'] = 'Nein';
$lang['admin_diagnostic_teleworks_datetype_thead_employee'] = 'Employee';
$lang['admin_diagnostic_teleworks_datetype_thead_start_date'] = 'Datum';
$lang['admin_diagnostic_teleworks_datetype_thead_start_type'] = 'Start';
$lang['admin_diagnostic_teleworks_datetype_thead_end_type'] = 'Ende';
$lang['admin_diagnostic_teleworks_datetype_thead_status'] = 'Status';

$lang['admin_diagnostic_daysoff_description'] = 'Überstundenanfrage mit einer negativen Dauer';
$lang['admin_diagnostic_daysoff_thead_id'] = 'ID';
$lang['admin_diagnostic_daysoff_thead_employee'] = 'Angestellter';
$lang['admin_diagnostic_daysoff_thead_date'] = 'Datum';
$lang['admin_diagnostic_daysoff_thead_duration'] = 'Dauer';
$lang['admin_diagnostic_daysoff_thead_status'] = 'Status';
$lang['admin_diagnostic_contract_tab'] = 'Verträge';
$lang['admin_diagnostic_contract_description'] = 'Nicht verwendete Verträge (stellen Sie sicher, dass der Vertrag nicht doppelt vorliegt)';
$lang['admin_diagnostic_contract_thead_id'] = 'ID';
$lang['admin_diagnostic_contract_thead_name'] = 'Name';
$lang['admin_diagnostic_balance_tab'] = 'Guthaben';
$lang['admin_diagnostic_balance_description'] = 'Abwesenheitsanfragen ohne bestehende Ansprüche';
$lang['admin_diagnostic_balance_thead_id'] = 'ID';
$lang['admin_diagnostic_balance_thead_employee'] = 'Angestellter';
$lang['admin_diagnostic_balance_thead_contract'] = 'Vertrag';
$lang['admin_diagnostic_balance_thead_start_date'] = 'Anfangsdatum';
$lang['admin_diagnostic_balance_thead_status'] = 'Status';

$lang['admin_diagnostic_overlapping_tab'] = 'Überschneidungen';
$lang['admin_diagnostic_overlapping_description'] = 'Urlaubsanträge, die sich in zwei Jahresperioden überschneiden.';
$lang['admin_diagnostic_overlapping_thead_id'] = 'ID';
$lang['admin_diagnostic_overlapping_thead_employee'] = 'Mitarbeiter';
$lang['admin_diagnostic_overlapping_thead_contract'] = 'Vertrag';
$lang['admin_diagnostic_overlapping_thead_start_date'] = 'Startdatum';
$lang['admin_diagnostic_overlapping_thead_end_date'] = 'Enddatum';
$lang['admin_diagnostic_overlapping_thead_status'] = 'Status';

$lang['admin_diagnostic_teleworks_overlapping_tab'] = 'Überlappung (TT)';
$lang['admin_diagnostic_teleworks_overlapping_description'] = 'Anträge auf Telearbeit, die sich über zwei Jahreszeiträume erstrecken.';
$lang['admin_diagnostic_teleworks_overlapping_thead_id'] = 'ID';
$lang['admin_diagnostic_teleworks_overlapping_thead_employee'] = 'Beschäftigter';
$lang['admin_diagnostic_teleworks_overlapping_thead_contract'] = 'Vertrag';
$lang['admin_diagnostic_teleworks_overlapping_thead_start_date'] = 'Startdatum';
$lang['admin_diagnostic_teleworks_overlapping_thead_end_date'] = 'Enddatum';
$lang['admin_diagnostic_teleworks_overlapping_thead_status'] = 'Status';

$lang['admin_oauthclients_title'] = 'OAuth-Clients und -Sitzungen';
$lang['admin_oauthclients_tab_clients'] = 'Clients';
$lang['admin_oauthclients_tab_clients_description'] = 'Liste an Clients, denen die Nutzung der REST API erlaubt wurde';
$lang['admin_oauthclients_thead_tip_edit'] = 'Client bearbeiten';
$lang['admin_oauthclients_thead_tip_delete'] = 'Client löschen';
$lang['admin_oauthclients_button_add'] = 'Hinzufügen';
$lang['admin_oauthclients_popup_add_title'] = 'OAuth-Client hinzufügen';
$lang['admin_oauthclients_popup_select_user_title'] = 'Tatsächlichen Benutzer zuweisen';
$lang['admin_oauthclients_error_exists'] = 'Diese client_id existiert bereits';
$lang['admin_oauthclients_confirm_delete'] = 'Sind Sie sicher, dass Sie fortfahren möchten?';
$lang['admin_oauthclients_tab_sessions'] = 'Sitzungen';
$lang['admin_oauthclients_tab_sessions_description'] = 'Liste aktiver REST-API-OAuth-Sitzungen';
$lang['admin_oauthclients_button_purge'] = 'Löschen';
