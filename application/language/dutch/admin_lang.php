<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.6.5
 * @author     Transifex users
 */

$lang['admin_diagnostic_title'] = 'Data & Configuratie Diagnose';
$lang['admin_diagnostic_description'] = 'Opsporen van configuratie en data problemen';
$lang['admin_diagnostic_no_error'] = 'Geen fouten';
$lang['admin_diagnostic_requests_tab'] = 'Afwezigheid verzoek(en)';
$lang['admin_diagnostic_requests_description'] = 'Geaccepteerd maar duplicaat afwezigheidsverzoek';
$lang['admin_diagnostic_requests_thead_id'] = 'ID';
$lang['admin_diagnostic_requests_thead_employee'] = 'Werknemer';
$lang['admin_diagnostic_requests_thead_start_date'] = 'Begin datum';
$lang['admin_diagnostic_requests_thead_status'] = 'Status';
$lang['admin_diagnostic_requests_thead_type'] = 'Type';
$lang['admin_diagnostic_datetype_tab'] = 'Middag/Ochtend';
$lang['admin_diagnostic_datetype_description'] = 'Afwezigheid verzoek met foutief start/eind type.';
$lang['admin_diagnostic_datetype_thead_id'] = 'ID';
$lang['admin_diagnostic_datetype_thead_employee'] = 'Werknemer';
$lang['admin_diagnostic_datetype_thead_start_date'] = 'Datum';
$lang['admin_diagnostic_datetype_thead_start_type'] = 'Start';
$lang['admin_diagnostic_datetype_thead_end_type'] = 'Einde';
$lang['admin_diagnostic_datetype_thead_status'] = 'Status';
$lang['admin_diagnostic_entitlements_tab'] = 'Beschikbare dagen';
$lang['admin_diagnostic_entitlements_description'] = 'Lijst van contracten en werknemers met aanspraken voor meer dan een jaar. ';
$lang['admin_diagnostic_entitlements_thead_id'] = 'ID';
$lang['admin_diagnostic_entitlements_thead_type'] = 'Type';
$lang['admin_diagnostic_entitlements_thead_name'] = 'Naam';
$lang['admin_diagnostic_entitlements_thead_start_date'] = 'Begin datum';
$lang['admin_diagnostic_entitlements_thead_end_date'] = 'Eind datum';
$lang['admin_diagnostic_entitlements_type_contract'] = 'Contract';
$lang['admin_diagnostic_entitlements_type_employee'] = 'Werknemer';
$lang['admin_diagnostic_entitlements_deletion_problem'] = 'Onvolledige verwijdering in de database.';
$lang['admin_diagnostic_daysoff_tab'] = 'Niet-werkdagen';
$lang['admin_diagnostic_daysoff_description'] = 'Aantal dagen (per contract) die als niet-werkdagen zijn gedefinieerd.';
$lang['admin_diagnostic_daysoff_thead_id'] = 'ID';
$lang['admin_diagnostic_daysoff_thead_name'] = 'Naam';
$lang['admin_diagnostic_daysoff_thead_ym1'] = 'Afgelopen jaar';
$lang['admin_diagnostic_daysoff_thead_y'] = 'Dit jaar';
$lang['admin_diagnostic_daysoff_thead_yp1'] = 'Volgend jaar';
$lang['admin_diagnostic_overtime_tab'] = 'Overuren';
$lang['admin_diagnostic_overtime_description'] = 'Overwerk aanvragen met een negatieve duur';
$lang['admin_diagnostic_overtime_thead_id'] = 'ID';
$lang['admin_diagnostic_overtime_thead_employee'] = 'Werknemer';
$lang['admin_diagnostic_overtime_thead_date'] = 'Datum';
$lang['admin_diagnostic_overtime_thead_duration'] = 'Duur';
$lang['admin_diagnostic_overtime_thead_status'] = 'Status';
$lang['admin_diagnostic_contract_tab'] = 'Contracten';
$lang['admin_diagnostic_contract_description'] = 'Ongebruikte contracten (controleer of dit geen duplicaat is).';
$lang['admin_diagnostic_contract_thead_id'] = 'ID';
$lang['admin_diagnostic_contract_thead_name'] = 'Naam';
$lang['admin_diagnostic_balance_tab'] = 'Balans';
$lang['admin_diagnostic_balance_description'] = 'Afwezigheid verzoeken waarvoor er geen rechten zijn.';
$lang['admin_diagnostic_balance_thead_id'] = 'ID';
$lang['admin_diagnostic_balance_thead_employee'] = 'Werknemer';
$lang['admin_diagnostic_balance_thead_contract'] = 'Contract';
$lang['admin_diagnostic_balance_thead_start_date'] = 'Begin datum';
$lang['admin_diagnostic_balance_thead_status'] = 'Status';

$lang['admin_diagnostic_overlapping_tab'] = 'Overlapping';
$lang['admin_diagnostic_overlapping_description'] = 'Leave requests overlapping on two yearly periods.';
$lang['admin_diagnostic_overlapping_thead_id'] = 'ID';
$lang['admin_diagnostic_overlapping_thead_employee'] = 'Employee';
$lang['admin_diagnostic_overlapping_thead_contract'] = 'Contract';
$lang['admin_diagnostic_overlapping_thead_start_date'] = 'Start Date';
$lang['admin_diagnostic_overlapping_thead_end_date'] = 'End Date';
$lang['admin_diagnostic_overlapping_thead_status'] = 'Status';

$lang['admin_oauthclients_title'] = 'OAuth clienten en sessies';
$lang['admin_oauthclients_tab_clients'] = 'Clienten';
$lang['admin_oauthclients_tab_clients_description'] = 'Lijst met clienten die gebruik mag maken van de REST API';
$lang['admin_oauthclients_thead_tip_edit'] = 'wijzig client';
$lang['admin_oauthclients_thead_tip_delete'] = 'verwijder client';
$lang['admin_oauthclients_button_add'] = 'Toevoegen';
$lang['admin_oauthclients_popup_add_title'] = 'Voeg OAuth client toe';
$lang['admin_oauthclients_popup_select_user_title'] = 'Associëren met een werkelijke gebruiker';
$lang['admin_oauthclients_error_exists'] = 'Deze client_id bestaat al';
$lang['admin_oauthclients_confirm_delete'] = 'Weet u zeker dat u wilt doorgaan?';
$lang['admin_oauthclients_tab_sessions'] = 'Sessies';
$lang['admin_oauthclients_tab_sessions_description'] = 'Lijst met actieve REST API OAuth sessies';
$lang['admin_oauthclients_button_purge'] = 'Opschonen';

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
