<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 * @author       dario brignone <brignone@unitec.it>
 */

$lang['requests_index_title'] = 'Richieste ferie inviate da me';
$lang['requests_index_description'] = 'Questa pagina elenca le richieste di ferie inviate da te. Se non sei un manager, questo elenco sarà sempre vuoto.';
$lang['requests_index_thead_tip_view'] = 'vedi';
$lang['requests_index_thead_tip_accept'] = 'accetta';
$lang['requests_index_thead_tip_reject'] = 'rifiuta';
$lang['requests_index_thead_tip_history'] = 'show history';
$lang['requests_index_thead_id'] = 'ID';
$lang['requests_index_thead_fullname'] = 'Nome completo';
$lang['requests_index_thead_startdate'] = 'Data inizio';
$lang['requests_index_thead_enddate'] = 'Data fine';
$lang['requests_index_thead_duration'] = 'Durata';
$lang['requests_index_thead_type'] = 'Tipologia';
$lang['requests_index_thead_status'] = 'Stato';
$lang['requests_index_thead_requested_date'] = 'Requested';
$lang['requests_index_thead_last_change'] = 'Last change';

$lang['requests_collaborators_title'] = 'Elenco dei miei collaboratori';
$lang['requests_collaborators_description'] = 'Questa pagina elenca i tuoi collaboratori. Se non sei un manager, questo elenco sarà sempre vuoto.';
$lang['requests_collaborators_thead_id'] = 'ID';
$lang['requests_collaborators_thead_link_balance'] = 'Saldo ferie';
$lang['requests_collaborators_thead_link_presence'] = 'Report presenze';
$lang['requests_collaborators_thead_link_year'] = 'Yearly calendar';
$lang['requests_collaborators_thead_link_create_leave'] = 'Crea una richiesta di ferie per conto di questo collaboratore';
$lang['requests_collaborators_thead_firstname'] = 'Nome';
$lang['requests_collaborators_thead_lastname'] = 'Cognome';
$lang['requests_collaborators_thead_email'] = 'E-mail';
$lang['requests_collaborators_thead_identifier'] = 'identificatore';

$lang['requests_summary_title'] = 'Saldo ferie per l\'utente #';
$lang['requests_summary_thead_type'] = 'Tipologia ferie';
$lang['requests_summary_thead_available'] = 'Disponibile';
$lang['requests_summary_thead_taken'] = 'Occupato';
$lang['requests_summary_thead_entitled'] = 'Spettante';
$lang['requests_summary_thead_description'] = 'Descrizione';
$lang['requests_summary_flash_msg_error'] = 'Questo dipendente non ha un contratto';
$lang['requests_summary_flash_msg_forbidden'] = 'Non sei il gestore di questo dipendente.';
$lang['requests_summary_button_list'] = 'Elenco dei collaboratori';

$lang['requests_index_button_export'] = 'Esporta questo elenco';
$lang['requests_index_button_show_all'] = 'Tutte le richieste';
$lang['requests_index_button_show_pending'] = 'Richieste in sospeso';

$lang['requests_accept_flash_msg_error'] = 'Non sei il gestore di questo dipendente. Non puoi accettare la sua richiesta di ferie.';
$lang['requests_accept_flash_msg_success'] = 'La richiesta ferie è stata accettata con successo.';

$lang['requests_reject_flash_msg_error'] = 'Non sei il responsabile di linea di questo dipendente. Non puoi rifiutare questa richiesta di ferie.';
$lang['requests_reject_flash_msg_success'] = 'La richiesta di ferie è stata rifiutata con successo.';

$lang['requests_export_title'] = 'Elenco richieste di ferie';
$lang['requests_export_thead_id'] = 'ID';
$lang['requests_export_thead_fullname'] = 'Nome completo';
$lang['requests_export_thead_startdate'] = 'Data inizio';
$lang['requests_export_thead_startdate_type'] = 'Mattina/Pomeriggio';
$lang['requests_export_thead_enddate'] = 'Data fine';
$lang['requests_export_thead_enddate_type'] = 'Mattina/Pomeriggio';
$lang['requests_export_thead_duration'] = 'Durata';
$lang['requests_export_thead_type'] = 'Tipologia';
$lang['requests_export_thead_cause'] = 'Motivo';
$lang['requests_export_thead_status'] = 'Stato';

$lang['requests_delegations_title'] = 'Elenco deleghe';
$lang['requests_delegations_description'] = 'Questo è l\'elenco dei dipendenti che possono accettare o rifiutare una richiesta al posto tuo.';
$lang['requests_delegations_thead_employee'] = 'Dipendente';
$lang['requests_delegations_thead_tip_delete'] = 'Revoca';
$lang['requests_delegations_button_add'] = 'Aggiungi';
$lang['requests_delegations_popup_delegate_title'] = 'Aggiungi un delegato';
$lang['requests_delegations_popup_delegate_button_ok'] = 'OK';
$lang['requests_delegations_popup_delegate_button_cancel'] = 'Annulla';
$lang['requests_delegations_confirm_delete_message'] = 'Sei sicuro di voler revocare questa delega?';
$lang['requests_delegations_confirm_delete_cancel'] = 'Annulla';
$lang['requests_delegations_confirm_delete_yes'] = 'Si';

$lang['requests_balance_title'] = 'Leave balance (subordinates)';
$lang['requests_balance_description'] = 'Leave balance of my direct report subordinates. If you are not a manager, this list will always be empty.';
$lang['requests_balance_date_field'] = 'Date of report';

$lang['requests_comment_reject_request_title'] = 'Comment';
$lang['requests_comment_reject_request_button_cancel'] = 'Cancel';
$lang['requests_comment_reject_request_button_reject'] = 'Reject';
