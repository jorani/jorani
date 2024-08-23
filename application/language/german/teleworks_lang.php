<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

$lang['teleworks_summary_title'] = 'Mein Zähler';
$lang['teleworks_summary_thead_available'] = 'Verfügbar';
$lang['teleworks_summary_thead_taken'] = 'Genommen';
$lang['teleworks_summary_thead_type'] = 'Art der Anfrage';
$lang['teleworks_summary_thead_entitled'] = 'Erfasst';
$lang['teleworks_summary_thead_description'] = 'Beschreibung';
$lang['teleworks_summary_thead_actual'] = 'tatsächlich';
$lang['teleworks_summary_thead_simulated'] = 'simuliert';
$lang['teleworks_summary_tbody_empty'] = 'Keine Tage genommen oder verfügbar. Bitte wenden Sie sich an einen Manager der Personalabteilung.';
$lang['teleworks_summary_flash_msg_error'] = 'Es scheint, dass Sie keinen Vertrag haben. Bitte wenden Sie sich an einen Personalverantwortlichen';
$lang['teleworks_summary_date_field'] = 'Datum des Berichts';

$lang['teleworks_index_title'] = 'Meine Telearbeitsanträge';
$lang['campaign_teleworks_index_html_title'] = 'Liste der festgelegten Telearbeitsanträge für die Kampagne';
$lang['teleworks_index_thead_tip_view'] = 'Ansicht';
$lang['teleworks_index_thead_tip_edit'] = 'bearbeiten';
$lang['teleworks_index_thead_tip_cancel'] = 'abbrechen';
$lang['teleworks_index_thead_tip_delete'] = 'löschen';
$lang['teleworks_index_thead_tip_history'] = 'Verlauf anzeigen';
$lang['teleworks_index_thead_id'] = 'Nein';
$lang['teleworks_index_thead_start_date'] = 'Startdatum';
$lang['teleworks_index_thead_end_date'] = 'Enddatum';
$lang['teleworks_index_thead_cause'] = 'Ursache';
$lang['teleworks_index_thead_duration'] = 'Dauer';
$lang['teleworks_index_thead_type'] = 'Typ';
$lang['teleworks_index_thead_campaign'] = 'Kampagne';
$lang['teleworks_index_thead_status'] = 'Status';
$lang['teleworks_index_thead_requested_date'] = 'Angefordert am';
$lang['teleworks_index_thead_last_change'] = 'Geändert am';
$lang['teleworks_index_button_export'] = 'Diese Liste exportieren';
$lang['teleworks_index_button_create'] = 'Neue Anfrage';
$lang['teleworks_index_popup_delete_title'] = 'Einen Telearbeitsantrag löschen';
$lang['teleworks_index_popup_delete_message'] = 'Sie sind dabei, einen Telearbeitsantrag zu löschen, dieser Vorgang ist nicht umkehrbar';
$lang['teleworks_index_popup_delete_question'] = 'Möchten Sie fortfahren?';
$lang['teleworks_index_popup_delete_button_yes'] = 'Ja';
$lang['teleworks_index_popup_delete_button_no'] = 'Nein';

$lang['teleworks_history_thead_changed_date'] = 'Geändert am';
$lang['teleworks_history_thead_change_type'] = 'Änderungsart';
$lang['teleworks_history_thead_changed_by'] = 'Geändert von';
$lang['teleworks_history_thead_start_date'] = 'Startdatum';
$lang['teleworks_history_thead_end_date'] = 'Enddatum';
$lang['teleworks_history_thead_cause'] = 'Ursache';
$lang['teleworks_history_thead_duration'] = 'Dauer';
$lang['teleworks_history_thead_status'] = 'Status';

$lang['teleworks_create_title'] = 'Einen neuen schwebenden Telearbeitsantrag erstellen';
$lang['teleworks_create_campaign_title'] = 'Erstellen eines festen Telearbeitsantrags für die Kampagne';
$lang['teleworks_create_field_start'] = 'Startdatum';
$lang['teleworks_create_field_end'] = 'Enddatum';
$lang['teleworks_create_field_recurrence'] = 'Wiederholung';
$lang['teleworks_create_field_duration'] = 'Dauer';
$lang['teleworks_create_field_campaign'] = 'Kampagne';
$lang['teleworks_create_field_daytype'] = 'Ganzer Tag/Vormittag/Nachmittag';
$lang['teleworks_create_field_duration_message'] = 'Sie überschreiten die zulässige Anzahl von Tagen';
$lang['teleworks_create_field_overlapping_message'] = 'In diesem Zeitraum haben wir einen Antrag auf Telearbeit';
$lang['teleworks_create_field_overlapping_leaves_message'] = 'In diesem Zeitraum liegt uns ein Antrag auf Urlaub vor';
$lang['teleworks_create_field_overlapping_time_organisations_message'] = 'In diesem Zeitraum haben wir eine Arbeitszeitregelung.';
$lang['teleworks_create_field_past_date_message'] = 'Anträge auf Telearbeit zu früheren Terminen sind nicht möglich.';
$lang['teleworks_create_field_cause'] = 'Ursache (optional)';
$lang['teleworks_create_field_status'] = 'Status';
$lang['teleworks_create_field_day'] = 'Tag';
$lang['teleworks_create_button_create'] = 'Antrag erstellen';
$lang['teleworks_create_button_cancel'] = 'Abbrechen';
$lang['teleworks_current_campaign'] = 'Aktuelle Kampagne';
$lang['teleworks_next_campaign'] = 'Nächste Kampagne';
    
$lang['teleworks_create_flash_msg_success'] = 'Der Telearbeitsantrag wurde erfolgreich erstellt';
$lang['teleworks_create_flash_msg_error'] = 'Der Telearbeitsantrag wurde erfolgreich erstellt oder geändert, aber Sie haben keinen Manager';
    
$lang['teleworks_flash_spn_list_days_off'] = '%s nicht gearbeitete Tage im Zeitraum';
$lang['teleworks_flash_msg_overlap_dayoff'] = 'Ihr Antrag fällt auf einen arbeitsfreien Tag.';
$lang['teleworks_flash_msg_limit_exceeded'] = 'Ihr Antrag überschreitet die Anzahl der zulässigen Telearbeitstage pro Woche.';
$lang['teleworks_flash_msg_for_campaign_dates'] = 'Die Daten des Telearbeitsantrags müssen mit den gültigen Kampagnen übereinstimmen';
$lang['teleworks_flash_msg_deadline_respected'] = 'Die Kündigungsfrist wird nicht eingehalten';
$lang['teleworks_flash_msg_halfday_telework'] = 'Der Antrag auf halbtägige Telearbeit ist nicht zulässig';
    
$lang['teleworks_cancellation_flash_msg_error'] = 'Sie können diesen Telearbeitsantrag nicht stornieren';
$lang['teleworks_cancellation_flash_msg_success'] = 'Die Stornierungsanfrage wurde erfolgreich gesendet';
$lang['teleworkrequests_cancellation_accept_flash_msg_success'] = 'Der Telearbeitsantrag wurde erfolgreich storniert';
$lang['teleworkrequests_cancellation_accept_flash_msg_error'] = 'Beim Versuch, den Antrag zu stornieren, ist ein Fehler aufgetreten';
$lang['teleworkrequests_cancellation_reject_flash_msg_success'] = 'Der Telearbeitsantrag befindet sich jetzt in seinem ursprünglichen Status *Accepted*';
$lang['teleworkrequests_cancellation_reject_flash_msg_error'] = 'Beim Versuch, den Stornierungsantrag abzulehnen, ist ein Fehler aufgetreten';

$lang['teleworks_edit_html_title'] = 'Antrag ändern';
$lang['teleworks_edit_title'] = 'Antrag Nr. ändern';
$lang['teleworks_edit_field_start'] = 'Startdatum';
$lang['teleworks_edit_field_end'] = 'Enddatum';
$lang['teleworks_edit_field_duration'] = 'Dauer';
$lang['teleworks_edit_field_duration_message'] = 'Sie überschreiten die zulässige Anzahl von Tagen';
$lang['teleworks_edit_field_cause'] = 'Ursache (optional)';
$lang['teleworks_edit_field_status'] = 'Status';
$lang['teleworks_edit_button_update'] = 'Aktualisieren';
$lang['teleworks_edit_button_cancel'] = 'Abbrechen';
$lang['teleworks_edit_flash_msg_error'] = 'Sie können einen zuvor eingereichten Antrag nicht bearbeiten';
$lang['teleworks_edit_flash_msg_success'] = 'Der Telearbeitsantrag wurde erfolgreich geändert';

$lang['teleworks_validate_mandatory_js_msg'] = '"Das Feld " + fieldname + " ist obligatorisch."';
$lang['teleworks_validate_flash_msg_no_contract'] = 'Es scheint, dass Sie keinen Vertrag haben. Bitte wenden Sie sich an einen Mitarbeiter der Personalabteilung.';
$lang['teleworks_validate_flash_msg_overlap_period'] = 'Sie können keinen Telearbeitsantrag für zwei jährliche Telearbeitszeiträume erstellen. Bitte erstellen Sie zwei verschiedene Anträge';

$lang['teleworks_cancel_flash_msg_error'] = 'Sie können diesen Telearbeitsantrag nicht stornieren';
$lang['teleworks_cancel_flash_msg_success'] = 'Der Telearbeitsantrag wurde erfolgreich storniert';
$lang['teleworks_cancel_unauthorized_msg_error'] = 'Sie können eine Telearbeitsbestellung, die in der Vergangenheit begonnen hat, nicht stornieren. Bitten Sie Ihren Vorgesetzten, den Antrag abzulehnen.' ;

$lang['teleworks_delete_flash_msg_error'] = 'Sie können diese Telearbeitsbestellung nicht löschen';
$lang['teleworks_delete_flash_msg_success'] = 'Der Telearbeitsantrag wurde erfolgreich gelöscht';

$lang['teleworks_view_title'] = 'Telearbeitsanfrage anzeigen Nein';
$lang['teleworks_view_html_title'] = 'Einen Antrag anzeigen';
$lang['teleworks_view_field_start'] = 'Startdatum';
$lang['teleworks_view_field_end'] = 'Enddatum';
$lang['teleworks_view_field_duration'] = 'Dauer';
$lang['teleworks_view_field_cause'] = 'Ursache';
$lang['teleworks_view_field_status'] = 'Status';
$lang['teleworks_view_button_edit'] = 'Bearbeiten';
$lang['teleworks_view_button_back_list'] = 'Zurück zur Liste';

$lang['teleworks_export_title'] = 'Liste der Telearbeitsanträge';
$lang['teleworks_export_thead_id'] = 'ID';
$lang['teleworks_export_thead_start_date'] = 'Startdatum';
$lang['teleworks_export_thead_start_date_type'] = 'Vormittag/Nachmittag';
$lang['teleworks_export_thead_end_date'] = 'Enddatum';
$lang['teleworks_export_thead_end_date_type'] = 'Vormittag/Nachmittag';
$lang['teleworks_export_thead_cause'] = 'Ursache';
$lang['teleworks_export_thead_duration'] = 'Dauer';
$lang['teleworks_export_thead_type'] = 'Typ';
$lang['teleworks_export_thead_campaign'] = 'Kampagne';
$lang['teleworks_export_thead_status'] = 'Status';

$lang['teleworks_button_send_reminder'] = 'Eine Erinnerung senden';
$lang['teleworks_reminder_flash_msg_success'] = 'Erinnerungs-E-Mail wurde an den Manager gesendet';

$lang['teleworks_comment_title'] = 'Kommentare';
$lang['teleworks_comment_new_comment'] = 'Neuer Kommentar';
$lang['teleworks_comment_send_comment'] = 'Einen Kommentar hinzufügen';
$lang['teleworks_comment_author_saying'] = ' sagte';
$lang['teleworks_comment_status_changed'] = 'Der Status der Anfrage hat sich geändert: ';

$lang['Campaign'] = 'Kampagne';
$lang['Floating'] = 'Schwebend';

$lang['all_recurrence'] = 'Jede Woche';
$lang['even_week'] = 'Gerade Wochen';
$lang['odd_week'] = 'Ungerade Wochen';
