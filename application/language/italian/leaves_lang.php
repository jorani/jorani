<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 * @author       dario brignone <brignone@unitec.it>
 */

$lang['leaves_summary_title'] = 'Mio sommario';
$lang['leaves_summary_title_overtime'] = 'Dettagli straordinario (aggiunto per compensare le ferie)';
$lang['leaves_summary_key_overtime'] = 'Recupero ritardo per';
$lang['leaves_summary_thead_type'] = 'Tipologia ferie';
$lang['leaves_summary_thead_available'] = 'Disponibile';
$lang['leaves_summary_thead_taken'] = 'Occupato';
$lang['leaves_summary_thead_entitled'] = 'Spettante';
$lang['leaves_summary_thead_description'] = 'Descrizione';
$lang['leaves_summary_thead_actual'] = 'effettivo';
$lang['leaves_summary_thead_simulated'] = 'simulata';
$lang['leaves_summary_tbody_empty'] = 'Nessun giorno spettante o preso per questo periodo. Sei pregato di contattare il tuo responsabile delle Risorse Umane / Manager';
$lang['leaves_summary_flash_msg_error'] = 'Sembra che tu non abbia un contratto. Sei pregato di contattare il tuo responsabile delle Risorse Umane / Manager';
$lang['leaves_summary_date_field'] = 'Data del report';

$lang['leaves_index_title'] = 'Le mie richieste di ferie';
$lang['leaves_index_thead_tip_view'] = 'vedi';
$lang['leaves_index_thead_tip_edit'] = 'modifica';
$lang['leaves_index_thead_tip_cancel'] = 'cancel';
$lang['leaves_index_thead_tip_delete'] = 'elimina';
$lang['leaves_index_thead_tip_history'] = 'show history';
$lang['leaves_index_thead_id'] = 'ID';
$lang['leaves_index_thead_start_date'] = 'Data inizio';
$lang['leaves_index_thead_end_date'] = 'Data fine';
$lang['leaves_index_thead_cause'] = 'Motivo';
$lang['leaves_index_thead_duration'] = 'Durata';
$lang['leaves_index_thead_type'] = 'Tipologia';
$lang['leaves_index_thead_status'] = 'Stato';
$lang['leaves_index_thead_requested_date'] = 'Requested';
$lang['leaves_index_thead_last_change'] = 'Last change';
$lang['leaves_index_button_export'] = 'Esporta questo elenco';
$lang['leaves_index_button_create'] = 'Nuova richiesta';
$lang['leaves_index_popup_delete_title'] = 'Elimina richiesta di ferie';
$lang['leaves_index_popup_delete_message'] = 'Stai per cancellare una richiesta di ferie, questa procedura è irreversibile';
$lang['leaves_index_popup_delete_question'] = 'Vuoi proseguire?';
$lang['leaves_index_popup_delete_button_yes'] = 'Si';
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

$lang['leaves_create_title'] = 'Invia una richiesta di ferie';
$lang['leaves_create_field_start'] = 'Data inizio';
$lang['leaves_create_field_end'] = 'Data fine';
$lang['leaves_create_field_type'] = 'Tipologia ferie';
$lang['leaves_create_field_duration'] = 'Durata';
$lang['leaves_create_field_duration_message'] = 'Hai superato i giorni a tua disposizione';
$lang['leaves_create_field_overlapping_message'] = 'Hai effettuato una richiesta di ferie per gli stessi giorni.';
$lang['leaves_create_field_cause'] = 'Motivazione (opzionale)';
$lang['leaves_create_field_status'] = 'Stato';
$lang['leaves_create_button_create'] = 'Richiedi ferie';
$lang['leaves_create_button_cancel'] = 'Annulla';

$lang['leaves_create_flash_msg_success'] = 'La richiesta di ferie è stata creata con successo';
$lang['leaves_create_flash_msg_error'] = 'La richiesta di ferie è stata creata con successo oppure aggiornata, ma non hai un manager.';

$lang['leaves_flash_spn_list_days_off'] = '%s non-working days in the period';
$lang['leaves_flash_msg_overlap_dayoff'] = 'Your leave request matches with a non-working day.';

$lang['leaves_cancellation_flash_msg_error'] = 'You can\'t cancel this leave request';
$lang['leaves_cancellation_flash_msg_success'] = 'The cancellation request has been successfully sent';
$lang['requests_cancellation_accept_flash_msg_success'] = 'The leave request has been successfully cancelled';
$lang['requests_cancellation_accept_flash_msg_error'] = 'An error occured while trying to accept the cancellation';
$lang['requests_cancellation_reject_flash_msg_success'] = 'The leave request has now its original status of Accepted';
$lang['requests_cancellation_reject_flash_msg_error'] = 'An error occured while trying to reject the cancellation';

$lang['leaves_edit_html_title'] = 'Modifica una richiesta di ferie';
$lang['leaves_edit_title'] = 'Modifica richiesta di ferie #';
$lang['leaves_edit_field_start'] = 'Data inizio';
$lang['leaves_edit_field_end'] = 'Data fine';
$lang['leaves_edit_field_type'] = 'Tipologia ferie';
$lang['leaves_edit_field_duration'] = 'Durata';
$lang['leaves_edit_field_duration_message'] = 'Hai superato i giorni a tua disposizione';
$lang['leaves_edit_field_cause'] = 'Motivazione (opzionale)';
$lang['leaves_edit_field_status'] = 'Stato';
$lang['leaves_edit_button_update'] = 'Aggiorna ferie';
$lang['leaves_edit_button_cancel'] = 'Annulla';
$lang['leaves_edit_flash_msg_error'] = 'Non puoi modificare una richiesta di ferie già inoltrata';
$lang['leaves_edit_flash_msg_success'] = 'La richiesta di ferie è stata aggiornata con successo';

$lang['leaves_validate_mandatory_js_msg'] = '"Il campo " + fieldname + " è obbligatorio."';
$lang['leaves_validate_flash_msg_no_contract'] = 'Sembra che tu non abbia un contratto. Sei pregato di contattare il tuo responsabile delle Risorse Umane / Manager';
$lang['leaves_validate_flash_msg_overlap_period'] = 'Non è possibile creare una richiesta di congedo per due periodi di ferie annuali. Si prega di creare due diverse richieste di ferie.';

$lang['leaves_cancel_flash_msg_error'] = 'You can\'t cancel this leave request';
$lang['leaves_cancel_flash_msg_success'] = 'The leave request has been successfully cancelled';
$lang['leaves_cancel_unauthorized_msg_error'] = 'You can\'t cancel a leave request starting in the past. Ask your manager for rejecting it.';

$lang['leaves_delete_flash_msg_error'] = 'Non puoi cancellare questa richiesta di ferie';
$lang['leaves_delete_flash_msg_success'] = 'La richiesta di ferie è stata cancellata con successo';

$lang['leaves_view_title'] = 'Vedi richiesta di ferie #';
$lang['leaves_view_html_title'] = 'Vedi una richiesta di ferie';
$lang['leaves_view_field_start'] = 'Data inizio';
$lang['leaves_view_field_end'] = 'Data fine';
$lang['leaves_view_field_type'] = 'Tipologia ferie';
$lang['leaves_view_field_duration'] = 'Durata';
$lang['leaves_view_field_cause'] = 'Motivo';
$lang['leaves_view_field_status'] = 'Stato';
$lang['leaves_view_button_edit'] = 'Modifica';
$lang['leaves_view_button_back_list'] = 'Torna all\'elenco';

$lang['leaves_export_title'] = 'Elenco ferie';
$lang['leaves_export_thead_id'] = 'ID';
$lang['leaves_export_thead_start_date'] = 'Data inizio';
$lang['leaves_export_thead_start_date_type'] = 'Mattina/Pomeriggio';
$lang['leaves_export_thead_end_date'] = 'Data fine';
$lang['leaves_export_thead_end_date_type'] = 'Mattina/Pomeriggio';
$lang['leaves_export_thead_cause'] = 'Motivo';
$lang['leaves_export_thead_duration'] = 'Durata';
$lang['leaves_export_thead_type'] = 'Tipologia';
$lang['leaves_export_thead_status'] = 'Stato';

$lang['leaves_button_send_reminder'] = 'Send a reminder';
$lang['leaves_reminder_flash_msg_success'] = 'The reminder email was sent to the manager';

$lang['leaves_comment_title'] = 'Comments';
$lang['leaves_comment_new_comment'] = 'New comment';
$lang['leaves_comment_send_comment'] = 'Send comment';
$lang['leaves_comment_author_saying'] = ' says';
$lang['leaves_comment_status_changed'] = 'The status of the leave have been changed to ';
