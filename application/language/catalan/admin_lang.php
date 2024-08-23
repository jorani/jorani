<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.6.6
 * @author     Transifex users
 */

$lang['admin_diagnostic_title'] = 'Dades de configuració i diagnòstic';
$lang['admin_diagnostic_description'] = 'Detectar problemes de la configuració i les dades';
$lang['admin_diagnostic_no_error'] = 'No hi han errades';
$lang['admin_diagnostic_requests_tab'] = 'Peticions d‘Absència';
$lang['admin_diagnostic_requests_description'] = 'Peticions d\'Absència acceptades però duplicades';
$lang['admin_diagnostic_requests_thead_id'] = 'ID';
$lang['admin_diagnostic_requests_thead_employee'] = 'Empleat';
$lang['admin_diagnostic_requests_thead_start_date'] = 'Data Inici';
$lang['admin_diagnostic_requests_thead_status'] = 'Estat';
$lang['admin_diagnostic_requests_thead_type'] = 'Tipus';
$lang['admin_diagnostic_datetype_tab'] = 'Matí/Tarda';
$lang['admin_diagnostic_datetype_description'] = 'Peticions d\'absència amb tipus d\'inici/fi erronis.';
$lang['admin_diagnostic_datetype_thead_id'] = 'ID';
$lang['admin_diagnostic_datetype_thead_employee'] = 'Empleat';
$lang['admin_diagnostic_datetype_thead_start_date'] = 'Data';
$lang['admin_diagnostic_datetype_thead_start_type'] = 'Inici';
$lang['admin_diagnostic_datetype_thead_end_type'] = 'Fi';
$lang['admin_diagnostic_datetype_thead_status'] = 'Estat';
$lang['admin_diagnostic_entitlements_tab'] = 'Autoritzar dies';
$lang['admin_diagnostic_entitlements_description'] = 'Llista de contractes i empleats amb dies lliures en més d\'un any.';
$lang['admin_diagnostic_entitlements_thead_id'] = 'ID';
$lang['admin_diagnostic_entitlements_thead_type'] = 'Tipus';
$lang['admin_diagnostic_entitlements_thead_name'] = 'Nom';
$lang['admin_diagnostic_entitlements_thead_start_date'] = 'Data Inici';
$lang['admin_diagnostic_entitlements_thead_end_date'] = 'Darrera data';
$lang['admin_diagnostic_entitlements_type_contract'] = 'Contracte';
$lang['admin_diagnostic_entitlements_type_employee'] = 'Empleat';
$lang['admin_diagnostic_entitlements_deletion_problem'] = 'Esborrat incomplet a la base de dades.';

$lang['admin_diagnostic_daysoff_tab'] = 'Dies no laborables';
$lang['admin_diagnostic_daysoff_description'] = 'Número de dies (definits per contracte) per les duracions d\'absència.';
$lang['admin_diagnostic_daysoff_thead_id'] = 'ID';
$lang['admin_diagnostic_daysoff_thead_name'] = 'Nom';
$lang['admin_diagnostic_daysoff_thead_ym1'] = 'Any passat';
$lang['admin_diagnostic_daysoff_thead_y'] = 'Aquest any';
$lang['admin_diagnostic_daysoff_thead_yp1'] = 'Proper any';
$lang['admin_diagnostic_overtime_tab'] = 'Hores extra';
$lang['admin_diagnostic_overtime_description'] = 'Petició d\'hores extra amb duració negativa';
$lang['admin_diagnostic_overtime_thead_id'] = 'ID';
$lang['admin_diagnostic_overtime_thead_employee'] = 'Empleat';
$lang['admin_diagnostic_overtime_thead_date'] = 'Data';
$lang['admin_diagnostic_overtime_thead_duration'] = 'Duració';
$lang['admin_diagnostic_overtime_thead_status'] = 'Estat';
$lang['admin_diagnostic_contract_tab'] = 'Contractes';
$lang['admin_diagnostic_contract_description'] = 'Contractes sense ús (revisa que el contracte no estigui duplicat)';
$lang['admin_diagnostic_contract_thead_id'] = 'ID';
$lang['admin_diagnostic_contract_thead_name'] = 'Nom';
$lang['admin_diagnostic_balance_tab'] = 'Saldo';
$lang['admin_diagnostic_balance_description'] = 'Peticions d\'absència sense dies disponibles.';
$lang['admin_diagnostic_balance_thead_id'] = 'ID';
$lang['admin_diagnostic_balance_thead_employee'] = 'Empleat';
$lang['admin_diagnostic_balance_thead_contract'] = 'Contracte';
$lang['admin_diagnostic_balance_thead_start_date'] = 'Data Inici';
$lang['admin_diagnostic_balance_thead_status'] = 'Estat';

$lang['admin_diagnostic_overlapping_tab'] = 'Overlapping';
$lang['admin_diagnostic_overlapping_description'] = 'Leave requests overlapping on two yearly periods.';
$lang['admin_diagnostic_overlapping_thead_id'] = 'ID';
$lang['admin_diagnostic_overlapping_thead_employee'] = 'Employee';
$lang['admin_diagnostic_overlapping_thead_contract'] = 'Contract';
$lang['admin_diagnostic_overlapping_thead_start_date'] = 'Start Date';
$lang['admin_diagnostic_overlapping_thead_end_date'] = 'End Date';
$lang['admin_diagnostic_overlapping_thead_status'] = 'Status';

$lang['admin_oauthclients_title'] = 'OAuth clients i  sessions';
$lang['admin_oauthclients_tab_clients'] = 'Clients';
$lang['admin_oauthclients_tab_clients_description'] = 'Llistat de clients autoritzats per utilitzar REST API';
$lang['admin_oauthclients_thead_tip_edit'] = 'editar client';
$lang['admin_oauthclients_thead_tip_delete'] = 'esborrar client';
$lang['admin_oauthclients_button_add'] = 'Afegir';
$lang['admin_oauthclients_popup_add_title'] = 'Afegir client OAuth';
$lang['admin_oauthclients_popup_select_user_title'] = 'Associar amb un usuari actual';
$lang['admin_oauthclients_error_exists'] = 'Aquest client_id ja existeix';
$lang['admin_oauthclients_confirm_delete'] = 'Estàs segur de procedir?';
$lang['admin_oauthclients_tab_sessions'] = 'Sessions';
$lang['admin_oauthclients_tab_sessions_description'] = 'Llista de sessions REST API OAuth actives';
$lang['admin_oauthclients_button_purge'] = 'Purgar';

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
