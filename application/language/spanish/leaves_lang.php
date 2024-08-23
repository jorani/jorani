<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.0
 * @author hnestar
 */

$lang['leaves_summary_title'] = 'Mi resumen';
$lang['leaves_summary_title_overtime'] = 'Detalle de las horas extras (añadido para compensar los permisos)';
$lang['leaves_summary_key_overtime'] = 'Póngase al día para';
$lang['leaves_summary_thead_type'] = 'Tipo de permiso';
$lang['leaves_summary_thead_available'] = 'Disponible';
$lang['leaves_summary_thead_taken'] = 'Recibido';
$lang['leaves_summary_thead_entitled'] = 'Asociado';
$lang['leaves_summary_thead_description'] = 'Descripción';
$lang['leaves_summary_thead_actual'] = 'actual';
$lang['leaves_summary_thead_simulated'] = 'simulated';
$lang['leaves_summary_tbody_empty'] = 'No hay día asociado o recibido para este período. Por favor, póngase en contacto con el administrador.';
$lang['leaves_summary_flash_msg_error'] = 'Parece que no tiene contrato. Pongase en contacto con el administrador.';
$lang['leaves_summary_date_field'] = 'Fecha del informe';

$lang['leaves_index_title'] = 'Mis solicitudes de permiso';
$lang['leaves_index_thead_tip_view'] = 'ver';
$lang['leaves_index_thead_tip_edit'] = 'modificar';
$lang['leaves_index_thead_tip_cancel'] = 'cancel';
$lang['leaves_index_thead_tip_delete'] = 'suprimir';
$lang['leaves_index_thead_tip_history'] = 'show history';
$lang['leaves_index_thead_id'] = 'ID';
$lang['leaves_index_thead_start_date'] = 'Primera fecha';
$lang['leaves_index_thead_end_date'] = 'Última fecha';
$lang['leaves_index_thead_cause'] = 'Motivo';
$lang['leaves_index_thead_duration'] = 'Duración';
$lang['leaves_index_thead_type'] = 'Tipo';
$lang['leaves_index_thead_status'] = 'Estado';
$lang['leaves_index_thead_requested_date'] = 'Requested';
$lang['leaves_index_thead_last_change'] = 'Last change';
$lang['leaves_index_button_export'] = 'Exporta a listado';
$lang['leaves_index_button_create'] = 'Nueva solicitud';
$lang['leaves_index_popup_delete_title'] = 'Borrar solicitud de permisos';
$lang['leaves_index_popup_delete_message'] = 'Estás a punto de borrar una solicitud de permiso, esta acción no se puede deshacer.';
$lang['leaves_index_popup_delete_question'] = '¿Quieres continuar?';
$lang['leaves_index_popup_delete_button_yes'] = 'Sí';
$lang['leaves_index_popup_delete_button_no'] = 'No';

$lang['leaves_history_thead_changed_date'] = 'Changed Date';
$lang['leaves_history_thead_change_type'] = 'Change Type';
$lang['leaves_history_thead_changed_by'] = 'Changed By';
$lang['leaves_history_thead_start_date'] = 'Start Date';
$lang['leaves_history_thead_end_date'] = 'End Date';
$lang['leaves_history_thead_cause'] = 'Reason';
$lang['leaves_history_thead_duration'] = 'Duration';
$lang['leaves_history_thead_type'] = 'Type';
$lang['leaves_history_thead_status'] = 'Status';

$lang['leaves_create_title'] = 'Enviar una solicitud de permiso';
$lang['leaves_create_field_start'] = 'Primera fecha';
$lang['leaves_create_field_end'] = 'Última fecha';
$lang['leaves_create_field_type'] = 'Tipo de permiso';
$lang['leaves_create_field_duration'] = 'Duración';
$lang['leaves_create_field_duration_message'] = 'Estas excediendo tu número de días libres';
$lang['leaves_create_field_overlapping_message'] = 'Ha solicitado otra solicitud de permiso, en las mismas fechas.';
$lang['leaves_create_field_cause'] = 'Causa (opcional)';
$lang['leaves_create_field_status'] = 'Estado';
$lang['leaves_create_button_create'] = 'Solicitud de permiso';
$lang['leaves_create_button_cancel'] = 'Cancelar';

$lang['leaves_create_flash_msg_success'] = 'La solicitud de permisos se ha creado correctamente';
$lang['leaves_create_flash_msg_error'] = 'La solicitud del permiso se ha creado o modificado correctamente, pero tu no tienes un administrador.';

$lang['leaves_flash_spn_list_days_off'] = '%s non-working days in the period';
$lang['leaves_flash_msg_overlap_dayoff'] = 'Your leave request matches with a non-working day.';

$lang['leaves_cancellation_flash_msg_error'] = 'You can\'t cancel this leave request';
$lang['leaves_cancellation_flash_msg_success'] = 'The cancellation request has been successfully sent';
$lang['requests_cancellation_accept_flash_msg_success'] = 'The leave request has been successfully cancelled';
$lang['requests_cancellation_accept_flash_msg_error'] = 'An error occured while trying to accept the cancellation';
$lang['requests_cancellation_reject_flash_msg_success'] = 'The leave request has now its original status of Accepted';
$lang['requests_cancellation_reject_flash_msg_error'] = 'An error occured while trying to reject the cancellation';

$lang['leaves_edit_html_title'] = 'Editar una solicitud de permiso';
$lang['leaves_edit_title'] = 'Editar solicitudes de permiso #';
$lang['leaves_edit_field_start'] = 'Primera fecha';
$lang['leaves_edit_field_end'] = 'Última fecha';
$lang['leaves_edit_field_type'] = 'Tipo de permiso';
$lang['leaves_edit_field_duration'] = 'Duración';
$lang['leaves_edit_field_duration_message'] = 'Estas excediendo tu número de días libres';
$lang['leaves_edit_field_cause'] = 'Causa (opcional)';
$lang['leaves_edit_field_status'] = 'Estado';
$lang['leaves_edit_button_update'] = 'Modificar permiso';
$lang['leaves_edit_button_cancel'] = 'Cancelar';
$lang['leaves_edit_flash_msg_error'] = 'No se puede editar una solicitud de permiso ya enviada';
$lang['leaves_edit_flash_msg_success'] = 'La solicitud de permiso se ha actualizado correctamente';

$lang['leaves_validate_mandatory_js_msg'] = '"El campo" + fieldname + " es obligatorio."';
$lang['leaves_validate_flash_msg_no_contract'] = 'Parece que no tiene contrato. Pongase en contacto con el administrador.';
$lang['leaves_validate_flash_msg_overlap_period'] = 'No se puede crear una solicitud de vacaciones por dos períodos de vacaciones anuales. Por favor, cree dos solicitud de vacaciones diferent.';

$lang['leaves_cancel_flash_msg_error'] = 'You can\'t cancel this leave request';
$lang['leaves_cancel_flash_msg_success'] = 'The leave request has been successfully cancelled';
$lang['leaves_cancel_unauthorized_msg_error'] = 'You can\'t cancel a leave request starting in the past. Ask your manager for rejecting it.';

$lang['leaves_delete_flash_msg_error'] = 'No se puede eliminar esta solicitud de permiso';
$lang['leaves_delete_flash_msg_success'] = 'La solicitud de permiso se ha eliminado correctamente';

$lang['leaves_view_title'] = 'Ver solicitud de permiso #';
$lang['leaves_view_html_title'] = 'Ver una solicitud de permiso';
$lang['leaves_view_field_start'] = 'Primera fecha';
$lang['leaves_view_field_end'] = 'Última fecha';
$lang['leaves_view_field_type'] = 'Tipo de permiso';
$lang['leaves_view_field_duration'] = 'Duración';
$lang['leaves_view_field_cause'] = 'Motivo';
$lang['leaves_view_field_status'] = 'Estado';
$lang['leaves_view_button_edit'] = 'Modificar';
$lang['leaves_view_button_back_list'] = 'Vuelta a la lista';

$lang['leaves_export_title'] = 'Lista de permisos ';
$lang['leaves_export_thead_id'] = 'ID';
$lang['leaves_export_thead_start_date'] = 'Primera fecha';
$lang['leaves_export_thead_start_date_type'] = 'Mañana/Tarde';
$lang['leaves_export_thead_end_date'] = 'Última fecha';
$lang['leaves_export_thead_end_date_type'] = 'Mañana/Tarde';
$lang['leaves_export_thead_cause'] = 'Motivo';
$lang['leaves_export_thead_duration'] = 'Duración';
$lang['leaves_export_thead_type'] = 'Tipo';
$lang['leaves_export_thead_status'] = 'Estado';

$lang['leaves_button_send_reminder'] = 'Send a reminder';
$lang['leaves_reminder_flash_msg_success'] = 'The reminder email was sent to the manager';

$lang['leaves_comment_title'] = 'Comments';
$lang['leaves_comment_new_comment'] = 'New comment';
$lang['leaves_comment_send_comment'] = 'Send comment';
$lang['leaves_comment_author_saying'] = ' says';
$lang['leaves_comment_status_changed'] = 'The status of the leave have been changed to ';
