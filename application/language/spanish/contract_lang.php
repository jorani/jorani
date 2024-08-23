<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.0
 * @author hnestar
 */

$lang['contract_index_title'] = 'Lista de contratos';
$lang['contract_index_thead_id'] = 'ID';
$lang['contract_index_thead_name'] = 'Nombre';
$lang['contract_index_thead_start'] = 'Inicio de período';
$lang['contract_index_thead_end'] = 'Fin de período';
$lang['contract_index_tip_delete'] = 'suprimir contrato';
$lang['contract_index_tip_edit'] = 'modificar contrato';
$lang['contract_index_tip_entitled'] = 'Autorizar días';
$lang['contract_index_tip_dayoffs'] = 'Días libres y fines de semana.';
$lang['contract_index_tip_exclude_types'] = 'Exclude leave types';
$lang['contract_index_button_export'] = 'Exporta a listado';
$lang['contract_index_button_create'] = 'Crear contrato';
$lang['contract_index_popup_delete_title'] = 'Suprimir contrato';
$lang['contract_index_popup_delete_description'] = 'Estas seguro de borrar el contrato, la acción no se puede desacer.';
$lang['contract_index_popup_delete_confirm'] = '¿Quieres continuar?';
$lang['contract_index_popup_delete_button_yes'] = 'Sí';
$lang['contract_index_popup_delete_button_no'] = 'No';
$lang['contract_index_popup_entitled_title'] = 'Autorizar días';
$lang['contract_index_popup_entitled_button_cancel'] = 'Cancelar';
$lang['contract_index_popup_entitled_button_close'] = 'Cerrar';

$lang['contract_exclude_title'] = 'Exclude leave types from a contract';
$lang['contract_exclude_description'] = 'You cannot exclude leave types already in use (used at least one time by en employee attached to the contract) and the default leave type (set on the contract or into the configuration file).';
$lang['contract_exclude_title_included'] = 'Included leave types';
$lang['contract_exclude_title_excluded'] = 'Excluded leave types';
$lang['contract_exclude_tip_include_type'] = 'Include this leave type';
$lang['contract_exclude_tip_exclude_type'] = 'Exclude this leave type';
$lang['contract_exclude_tip_already_used'] = 'This leave type is already in use';
$lang['contract_exclude_tip_default_type'] = 'You cannot exclude the default leave type';

$lang['contract_edit_title'] = 'Modificar contrato';
$lang['contract_edit_description'] = 'Modificar contrato n°';
$lang['contract_edit_field_name'] = 'Nombre';
$lang['contract_edit_field_start_month'] = 'Mes / Inicio';
$lang['contract_edit_field_start_day'] = 'Día / Inicio';
$lang['contract_edit_field_end_month'] = 'Mes / Fin';
$lang['contract_edit_field_end_day'] = 'Día / Fin';
$lang['contract_edit_default_leave_type'] = 'Default leave type';
$lang['contract_edit_button_update'] = 'Modificar contrato';
$lang['contract_edit_button_cancel'] = 'Cancelar';
$lang['contract_edit_msg_success'] = 'El contrato ha sido modificado correctamente';

$lang['contract_create_title'] = 'Crear un nuevo contrato';
$lang['contract_create_field_name'] = 'Nombre';
$lang['contract_create_field_start_month'] = 'Mes / Inicio';
$lang['contract_create_field_start_day'] = 'Día / Inicio';
$lang['contract_create_field_end_month'] = 'Mes / Fin';
$lang['contract_create_field_end_day'] = 'Día / Fin';
$lang['contract_create_default_leave_type'] = 'Default leave type';
$lang['contract_create_button_create'] = 'Crear contrato';
$lang['contract_create_button_cancel'] = 'Cancelar';
$lang['contract_create_msg_success'] = 'El contrato ha sido creado correctamente';

$lang['contract_delete_msg_success'] = 'El contrato ha sido borrado correctamente';

$lang['contract_export_title'] = 'Lista de contratos';
$lang['contract_export_thead_id'] = 'ID';
$lang['contract_export_thead_name'] = 'Nombre';
$lang['contract_export_thead_start'] = 'Inicio de período';
$lang['contract_export_thead_end'] = 'Fin de período';

$lang['contract_calendar_title'] = 'Calendario de días no laborales';
$lang['contract_calendar_description'] = 'Los días libres y fines de semana no se han configurado por defecto. Haga clic en un día de editar';
$lang['contract_calendar_legend_title'] = 'Leyenda:';
$lang['contract_calendar_legend_allday'] = 'Todo el Día';
$lang['contract_calendar_legend_morning'] = 'Mañana';
$lang['contract_calendar_legend_afternoon'] = 'Tarde';
$lang['contract_calendar_button_back'] = 'Volver a la lista de contratos';
$lang['contract_calendar_button_series'] = 'Serie de dias no laborables';
$lang['contract_calendar_popup_dayoff_title'] = 'Editar día libre';
$lang['contract_calendar_popup_dayoff_field_title'] = 'Título';
$lang['contract_calendar_popup_dayoff_field_type'] = 'Tipo';
$lang['contract_calendar_popup_dayoff_type_working'] = 'Día Laborable';
$lang['contract_calendar_popup_dayoff_type_off'] = 'Todos los días libres';
$lang['contract_calendar_popup_dayoff_type_morning'] = 'Mañana libre';
$lang['contract_calendar_popup_dayoff_type_afternoon'] = 'Tarde libre';
$lang['contract_calendar_popup_dayoff_button_delete'] = 'Suprimir';
$lang['contract_calendar_popup_dayoff_button_ok'] = 'OK';
$lang['contract_calendar_popup_dayoff_button_cancel'] = 'Cancelar';
$lang['contract_calendar_button_import'] = 'Import iCal';
$lang['contract_calendar_prompt_import'] = 'URL of non-working days iCal file';

$lang['contract_calendar_popup_series_title'] = 'Editar una serie de días libres';
$lang['contract_calendar_popup_series_field_occurences'] = 'Seleccionar Todos';
$lang['contract_calendar_popup_series_field_from'] = 'Desde';
$lang['contract_calendar_popup_series_button_current'] = 'Actual';
$lang['contract_calendar_popup_series_field_to'] = 'A';
$lang['contract_calendar_popup_series_field_as'] = 'Como';
$lang['contract_calendar_popup_series_field_as_working'] = 'Día Laborable';
$lang['contract_calendar_popup_series_field_as_off'] = 'Todos los días libres';
$lang['contract_calendar_popup_series_field_as_morning'] = 'Mañana libre';
$lang['contract_calendar_popup_series_field_as_afternnon'] = 'Tarde libre';
$lang['contract_calendar_popup_series_field_title'] = 'Título';
$lang['contract_calendar_popup_series_button_ok'] = 'OK';
$lang['contract_calendar_popup_series_button_cancel'] = 'Cancelar';

$lang['contract_calendar_button_copy'] = 'Copia';
$lang['contract_calendar_copy_destination_js_msg'] = 'You must select a contract.';
$lang['contract_calendar_copy_msg_success'] = 'Data has been copied successfully.';
