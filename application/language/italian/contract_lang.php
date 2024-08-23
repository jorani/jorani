<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 * @author       dario brignone <brignone@unitec.it>
 */

$lang['contract_index_title'] = 'Elenco dei contratti';
$lang['contract_index_thead_id'] = 'ID';
$lang['contract_index_thead_name'] = 'Nome';
$lang['contract_index_thead_start'] = 'Inizio periodo';
$lang['contract_index_thead_end'] = 'Fine periodo';
$lang['contract_index_tip_delete'] = 'elimina contratto';
$lang['contract_index_tip_edit'] = 'modifica contratto';
$lang['contract_index_tip_entitled'] = 'giorni spettanti';
$lang['contract_index_tip_dayoffs'] = 'festività e weekend';
$lang['contract_index_tip_exclude_types'] = 'Exclude leave types';
$lang['contract_index_button_export'] = 'Esporta questo elenco';
$lang['contract_index_button_create'] = 'Crea un contratto';
$lang['contract_index_popup_delete_title'] = 'Elimina Contratto';
$lang['contract_index_popup_delete_description'] = 'Stai per cancellare un contratto, questa azione è irreversibile.';
$lang['contract_index_popup_delete_confirm'] = 'Vuoi proseguire?';
$lang['contract_index_popup_delete_button_yes'] = 'Si';
$lang['contract_index_popup_delete_button_no'] = 'No';
$lang['contract_index_popup_entitled_title'] = 'Giorni spettanti';
$lang['contract_index_popup_entitled_button_cancel'] = 'Annulla';
$lang['contract_index_popup_entitled_button_close'] = 'Chiudi';

$lang['contract_exclude_title'] = 'Exclude leave types from a contract';
$lang['contract_exclude_description'] = 'You cannot exclude leave types already in use (used at least one time by en employee attached to the contract) and the default leave type (set on the contract or into the configuration file).';
$lang['contract_exclude_title_included'] = 'Included leave types';
$lang['contract_exclude_title_excluded'] = 'Excluded leave types';
$lang['contract_exclude_tip_include_type'] = 'Include this leave type';
$lang['contract_exclude_tip_exclude_type'] = 'Exclude this leave type';
$lang['contract_exclude_tip_already_used'] = 'This leave type is already in use';
$lang['contract_exclude_tip_default_type'] = 'You cannot exclude the default leave type';

$lang['contract_edit_title'] = 'Modifica un contratto';
$lang['contract_edit_description'] = 'Modifica contratto #';
$lang['contract_edit_field_name'] = 'Nome';
$lang['contract_edit_field_start_month'] = 'Mese / Inizio';
$lang['contract_edit_field_start_day'] = 'Giorno / Inizio';
$lang['contract_edit_field_end_month'] = 'Mese / Fine';
$lang['contract_edit_field_end_day'] = 'Giorno / Fine';
$lang['contract_edit_default_leave_type'] = 'Default leave type';
$lang['contract_edit_button_update'] = 'Aggiorna contratto';
$lang['contract_edit_button_cancel'] = 'Annulla';
$lang['contract_edit_msg_success'] = 'Il contratto è statto aggiornato con successo';

$lang['contract_create_title'] = 'Crea un nuovo contratto';
$lang['contract_create_field_name'] = 'Nome';
$lang['contract_create_field_start_month'] = 'Mese / Inizio';
$lang['contract_create_field_start_day'] = 'Giorno / Inizio';
$lang['contract_create_field_end_month'] = 'Mese / Fine';
$lang['contract_create_field_end_day'] = 'Giorno / Fine';
$lang['contract_create_default_leave_type'] = 'Default leave type';
$lang['contract_create_button_create'] = 'Crea un contratto';
$lang['contract_create_button_cancel'] = 'Annulla';
$lang['contract_create_msg_success'] = 'Il contratto è stato creato con successo';

$lang['contract_delete_msg_success'] = 'Il contratto è stato cancellato con successo';

$lang['contract_export_title'] = 'Elenco dei contratti';
$lang['contract_export_thead_id'] = 'ID';
$lang['contract_export_thead_name'] = 'Nome';
$lang['contract_export_thead_start'] = 'Inizio periodo';
$lang['contract_export_thead_end'] = 'Fine periodo';

$lang['contract_calendar_title'] = 'Calendario dei giorni non lavorativi';
$lang['contract_calendar_description'] = 'I giorni festivi e i weekend non sono configurati per default. Clicca su un giorno per editarlo singolarmente oppure usa il pulsante "Serie"';
$lang['contract_calendar_legend_title'] = 'Legenda:';
$lang['contract_calendar_legend_allday'] = 'Giorno intero';
$lang['contract_calendar_legend_morning'] = 'Mattina';
$lang['contract_calendar_legend_afternoon'] = 'Pomeriggio';
$lang['contract_calendar_button_back'] = 'Torna ai contratti';
$lang['contract_calendar_button_series'] = 'Serie di giorni festivi';
$lang['contract_calendar_popup_dayoff_title'] = 'Modifica giorno di chiusura';
$lang['contract_calendar_popup_dayoff_field_title'] = 'Titolo';
$lang['contract_calendar_popup_dayoff_field_type'] = 'Tipologia';
$lang['contract_calendar_popup_dayoff_type_working'] = 'Giorno lavorativo';
$lang['contract_calendar_popup_dayoff_type_off'] = 'Chiusura tutto il giorno';
$lang['contract_calendar_popup_dayoff_type_morning'] = 'Chiusura mattutina';
$lang['contract_calendar_popup_dayoff_type_afternoon'] = 'Chiusura pomeridiana';
$lang['contract_calendar_popup_dayoff_button_delete'] = 'Elimina';
$lang['contract_calendar_popup_dayoff_button_ok'] = 'OK';
$lang['contract_calendar_popup_dayoff_button_cancel'] = 'Annulla';
$lang['contract_calendar_popup_series_title'] = 'Modifica una serie di giorni di chiusura';
$lang['contract_calendar_popup_series_field_occurences'] = 'Seleziona tutti';
$lang['contract_calendar_popup_series_field_from'] = 'Da';
$lang['contract_calendar_popup_series_button_current'] = 'Attuale';
$lang['contract_calendar_popup_series_field_to'] = 'A';
$lang['contract_calendar_popup_series_field_as'] = 'Come';
$lang['contract_calendar_popup_series_field_as_working'] = 'Giorno lavorativo';
$lang['contract_calendar_popup_series_field_as_off'] = 'Chiusura tutto il giorno';
$lang['contract_calendar_popup_series_field_as_morning'] = 'Chiusura mattutina';
$lang['contract_calendar_popup_series_field_as_afternnon'] = 'Chiusura pomeridiana';
$lang['contract_calendar_popup_series_field_title'] = 'Titolo';
$lang['contract_calendar_popup_series_button_ok'] = 'OK';
$lang['contract_calendar_popup_series_button_cancel'] = 'Annulla';
$lang['contract_calendar_button_import'] = 'Importa iCal';
$lang['contract_calendar_prompt_import'] = 'URL of non-working days iCal file';

$lang['contract_calendar_button_copy'] = 'Copia';
$lang['contract_calendar_copy_destination_js_msg'] = 'Devi selezionare un contratto.';
$lang['contract_calendar_copy_msg_success'] = 'I dati sono stati copiati con successo.';
