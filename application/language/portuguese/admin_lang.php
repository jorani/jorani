<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.6.5
 * @author     Transifex users
 */

$lang['admin_diagnostic_title'] = 'Diagnostico de dados e configuração';
$lang['admin_diagnostic_description'] = 'Detecção de problemas de configuração e dados';
$lang['admin_diagnostic_no_error'] = 'Sem erro';
$lang['admin_diagnostic_requests_tab'] = 'Pedidos de Férias';
$lang['admin_diagnostic_requests_description'] = 'Aceite mas com pedidos duplicados';
$lang['admin_diagnostic_requests_thead_id'] = 'ID';
$lang['admin_diagnostic_requests_thead_employee'] = 'Colaborador';
$lang['admin_diagnostic_requests_thead_start_date'] = 'Data de inicio';
$lang['admin_diagnostic_requests_thead_status'] = 'Status';
$lang['admin_diagnostic_requests_thead_type'] = 'Tipo';
$lang['admin_diagnostic_datetype_tab'] = 'Tarde / Manhã';
$lang['admin_diagnostic_datetype_description'] = 'Pedidos com um tipo de início / fim errado.';
$lang['admin_diagnostic_datetype_thead_id'] = 'ID';
$lang['admin_diagnostic_datetype_thead_employee'] = 'Colaborador';
$lang['admin_diagnostic_datetype_thead_start_date'] = 'Data';
$lang['admin_diagnostic_datetype_thead_start_type'] = 'Inicio';
$lang['admin_diagnostic_datetype_thead_end_type'] = 'Fim';
$lang['admin_diagnostic_datetype_thead_status'] = 'Status';
$lang['admin_diagnostic_entitlements_tab'] = 'Dias atribuidos';
$lang['admin_diagnostic_entitlements_description'] = 'Lista de contratos e colaboradores com direitos a mais de um ano.';
$lang['admin_diagnostic_entitlements_thead_id'] = 'ID';
$lang['admin_diagnostic_entitlements_thead_type'] = 'Tipo';
$lang['admin_diagnostic_entitlements_thead_name'] = 'Nome';
$lang['admin_diagnostic_entitlements_thead_start_date'] = 'Data de inicio';
$lang['admin_diagnostic_entitlements_thead_end_date'] = 'Data final';
$lang['admin_diagnostic_entitlements_type_contract'] = 'Contrato';
$lang['admin_diagnostic_entitlements_type_employee'] = 'Colaborador';
$lang['admin_diagnostic_entitlements_deletion_problem'] = 'Eliminação incompleta no banco de dados.';
$lang['admin_diagnostic_daysoff_tab'] = 'Dias não úteis';
$lang['admin_diagnostic_daysoff_description'] = 'Número de dias (por contrato) para o qual uma duração não operacional foi definida.';
$lang['admin_diagnostic_daysoff_thead_id'] = 'ID';
$lang['admin_diagnostic_daysoff_thead_name'] = 'Nome';
$lang['admin_diagnostic_daysoff_thead_ym1'] = 'Ano anterior';
$lang['admin_diagnostic_daysoff_thead_y'] = 'Este ano';
$lang['admin_diagnostic_daysoff_thead_yp1'] = 'Próximo ano';
$lang['admin_diagnostic_overtime_tab'] = 'Horas extra';
$lang['admin_diagnostic_overtime_description'] = 'Pedidos de horas extras com duração negativa';
$lang['admin_diagnostic_overtime_thead_id'] = 'ID';
$lang['admin_diagnostic_overtime_thead_employee'] = 'Colaborador';
$lang['admin_diagnostic_overtime_thead_date'] = 'Data';
$lang['admin_diagnostic_overtime_thead_duration'] = 'Duração';
$lang['admin_diagnostic_overtime_thead_status'] = 'Status';
$lang['admin_diagnostic_contract_tab'] = 'Contratos';
$lang['admin_diagnostic_contract_description'] = 'Contratos não utilizados (verifique se o contrato não está duplicado).';
$lang['admin_diagnostic_contract_thead_id'] = 'ID';
$lang['admin_diagnostic_contract_thead_name'] = 'Nome';
$lang['admin_diagnostic_balance_tab'] = 'Saldo';
$lang['admin_diagnostic_balance_description'] = 'Pedidos para os quais não há nenhuma atribuição.';
$lang['admin_diagnostic_balance_thead_id'] = 'ID';
$lang['admin_diagnostic_balance_thead_employee'] = 'Colaborador';
$lang['admin_diagnostic_balance_thead_contract'] = 'Contrato';
$lang['admin_diagnostic_balance_thead_start_date'] = 'Data de inicio';
$lang['admin_diagnostic_balance_thead_status'] = 'Status';

$lang['admin_diagnostic_overlapping_tab'] = 'Overlapping';
$lang['admin_diagnostic_overlapping_description'] = 'Leave requests overlapping on two yearly periods.';
$lang['admin_diagnostic_overlapping_thead_id'] = 'ID';
$lang['admin_diagnostic_overlapping_thead_employee'] = 'Employee';
$lang['admin_diagnostic_overlapping_thead_contract'] = 'Contract';
$lang['admin_diagnostic_overlapping_thead_start_date'] = 'Start Date';
$lang['admin_diagnostic_overlapping_thead_end_date'] = 'End Date';
$lang['admin_diagnostic_overlapping_thead_status'] = 'Status';

$lang['admin_oauthclients_title'] = 'Clientes e sessões OAuth';
$lang['admin_oauthclients_tab_clients'] = 'Clientes';
$lang['admin_oauthclients_tab_clients_description'] = 'Lista de clientes autorizados a usar a API REST';
$lang['admin_oauthclients_thead_tip_edit'] = 'Editar cliente';
$lang['admin_oauthclients_thead_tip_delete'] = 'Eliminar cliente';
$lang['admin_oauthclients_button_add'] = 'Adicionar';
$lang['admin_oauthclients_popup_add_title'] = 'Adicionar Cliente OAuth ';
$lang['admin_oauthclients_popup_select_user_title'] = 'Associar ao utilizador atual';
$lang['admin_oauthclients_error_exists'] = 'O client_id já existe';
$lang['admin_oauthclients_confirm_delete'] = 'Tem certeza de que deseja prosseguir?';
$lang['admin_oauthclients_tab_sessions'] = 'Sessões';
$lang['admin_oauthclients_tab_sessions_description'] = 'Lista de Sessões ativas API REST OAuth';
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
