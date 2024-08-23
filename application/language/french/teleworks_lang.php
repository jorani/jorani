<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

$lang['teleworks_summary_title'] = 'Mon compteur';
$lang['teleworks_summary_thead_available'] = 'Disponible';
$lang['teleworks_summary_thead_taken'] = 'Pris';
$lang['teleworks_summary_thead_type'] = 'Type de demande';
$lang['teleworks_summary_thead_entitled'] = 'Acquis';
$lang['teleworks_summary_thead_description'] = 'Description';
$lang['teleworks_summary_thead_actual'] = 'réel';
$lang['teleworks_summary_thead_simulated'] = 'simulé';
$lang['teleworks_summary_tbody_empty'] = 'Aucun jour pris ou disponible. Veuillez contacter un responsable des ressources humaines.';
$lang['teleworks_summary_flash_msg_error'] = 'Il semble que vous n\'ayez pas de contrat. Veuillez contacter un responsable des ressources humaines.';
$lang['teleworks_summary_date_field'] = 'Date du rapport';

$lang['teleworks_index_title'] = 'Mes demandes de télétravail';
$lang['campaign_teleworks_index_html_title'] = 'Liste des demandes de télétravail fixe pour la campagne';
$lang['teleworks_index_thead_tip_view'] = 'voir';
$lang['teleworks_index_thead_tip_edit'] = 'modifier';
$lang['teleworks_index_thead_tip_cancel'] = 'annuler';
$lang['teleworks_index_thead_tip_delete'] = 'supprimer';
$lang['teleworks_index_thead_tip_history'] = 'afficher l\'historique';
$lang['teleworks_index_thead_id'] = 'N°';
$lang['teleworks_index_thead_start_date'] = 'Date début';
$lang['teleworks_index_thead_end_date'] = 'Date fin';
$lang['teleworks_index_thead_cause'] = 'Cause';
$lang['teleworks_index_thead_duration'] = 'Durée';
$lang['teleworks_index_thead_type'] = 'Type';
$lang['teleworks_index_thead_campaign'] = 'Campagne';
$lang['teleworks_index_thead_status'] = 'Statut';
$lang['teleworks_index_thead_requested_date'] = 'Demandée le';
$lang['teleworks_index_thead_last_change'] = 'Modifiée le';
$lang['teleworks_index_button_export'] = 'Exporter cette liste';
$lang['teleworks_index_button_create'] = 'Nouvelle demande';
$lang['teleworks_index_popup_delete_title'] = 'Suppression d\'une demandes de télétravail';
$lang['teleworks_index_popup_delete_message'] = 'Vous êtes sur le point de supprimer une demandes de télétravail, cette procédure est irréversible.';
$lang['teleworks_index_popup_delete_question'] = 'Voulez-vous continuer ?';
$lang['teleworks_index_popup_delete_button_yes'] = 'Oui';
$lang['teleworks_index_popup_delete_button_no'] = 'Non';

$lang['teleworks_history_thead_changed_date'] = 'Modifié le';
$lang['teleworks_history_thead_change_type'] = 'Type de modif.';
$lang['teleworks_history_thead_changed_by'] = 'Modifié par';
$lang['teleworks_history_thead_start_date'] = 'Date début';
$lang['teleworks_history_thead_end_date'] = 'Date fin';
$lang['teleworks_history_thead_cause'] = 'Cause';
$lang['teleworks_history_thead_duration'] = 'Durée';
$lang['teleworks_history_thead_status'] = 'Statut';

$lang['teleworks_create_title'] = 'Créer une nouvelle demande de télétravail flottant';
$lang['teleworks_create_campaign_title'] = 'Créer une demande de télétravail fixe pour la campagne';
$lang['teleworks_create_field_start'] = 'Date de début';
$lang['teleworks_create_field_end'] = 'Date de fin';
$lang['teleworks_create_field_recurrence'] = 'Récurrence';
$lang['teleworks_create_field_duration'] = 'Durée';
$lang['teleworks_create_field_campaign'] = 'Campagne';
$lang['teleworks_create_field_daytype'] = 'Toute la journée/Matin/Après-midi';
$lang['teleworks_create_field_duration_message'] = 'Vous dépassez le nombre de jours permis';
$lang['teleworks_create_field_overlapping_message'] = 'Sur cette période, nous avons une demande de télétravail.';
$lang['teleworks_create_field_overlapping_leaves_message'] = 'Sur cette période, nous avons une demande de congé.';
$lang['teleworks_create_field_overlapping_time_organisations_message'] = 'Sur cette période, nous avons un aménagement du temps de travail.';
$lang['teleworks_create_field_past_date_message'] = 'Les demandes de télétravail à des dates antérieures ne sont pas possibles.';
$lang['teleworks_create_field_cause'] = 'Cause (optionnelle)';
$lang['teleworks_create_field_status'] = 'Statut';
$lang['teleworks_create_field_day'] = 'Jour';
$lang['teleworks_create_button_create'] = 'Créer la demande';
$lang['teleworks_create_button_cancel'] = 'Annuler';
$lang['teleworks_current_campaign'] = 'Campagne en cours';
$lang['teleworks_next_campaign'] = 'Prochaine campagne';

$lang['teleworks_create_flash_msg_success'] = 'La demande de télétravail a été créée avec succès.';
$lang['teleworks_create_flash_msg_error'] = 'La demande de télétravail a été créée ou modifiée avec succès, mais vous n\'avez pas de manager.';

$lang['teleworks_flash_spn_list_days_off'] = '%s jours non travaillés dans la période';
$lang['teleworks_flash_msg_overlap_dayoff'] = 'Votre demande coïncide avec un jour non travaillé.';
$lang['teleworks_flash_msg_limit_exceeded'] = 'Votre demande dépasse le nombre de jours de télétravail autorisés par semaine.';
$lang['teleworks_flash_msg_for_campaign_dates'] = 'Les dates de la demande de télétravail doivent correspondre aux campagnes valides.';
$lang['teleworks_flash_msg_deadline_respected'] = 'Le délai de prévenance n\'est pas respecté.';
$lang['teleworks_flash_msg_halfday_telework'] = 'La demande de télétravail d\'une demi-journée n\'est pas autorisée.';

$lang['teleworks_cancellation_flash_msg_error'] = 'Vous ne pouvez pas annuler cette demande de télétravail.';
$lang['teleworks_cancellation_flash_msg_success'] = 'La demande d\'annulation a été envoyée avec succès.';
$lang['teleworkrequests_cancellation_accept_flash_msg_success'] = 'La demande de télétravail a été annulée avec succès.';
$lang['teleworkrequests_cancellation_accept_flash_msg_error'] = 'Une erreur est apparue en tentant d\'annuler la demande.';
$lang['teleworkrequests_cancellation_reject_flash_msg_success'] = 'La demande de télétravail a maintenant son statut originel *Acceptée*';
$lang['teleworkrequests_cancellation_reject_flash_msg_error'] = 'Une erreur est apparue en tentant de rejetter la demande d\'annulation.';

$lang['teleworks_edit_html_title'] = 'Modifier la demande';
$lang['teleworks_edit_title'] = 'Modifier la demande N°';
$lang['teleworks_edit_field_start'] = 'Date de début';
$lang['teleworks_edit_field_end'] = 'Date de fin';
$lang['teleworks_edit_field_duration'] = 'Durée';
$lang['teleworks_edit_field_duration_message'] = 'Vous dépassez le nombre de jours permis';
$lang['teleworks_edit_field_cause'] = 'Cause (optionelle)';
$lang['teleworks_edit_field_status'] = 'Statut';
$lang['teleworks_edit_button_update'] = 'Mettre à jour';
$lang['teleworks_edit_button_cancel'] = 'Annuler';
$lang['teleworks_edit_flash_msg_error'] = 'Vous ne pouvez pas modifier une demande déjà soumise.';
$lang['teleworks_edit_flash_msg_success'] = 'La demande de télétravail a été modifiée avec succès.';

$lang['teleworks_validate_mandatory_js_msg'] = '"Le champ " + fieldname + " est obligatoire."';
$lang['teleworks_validate_flash_msg_no_contract'] = 'Il semble que vous n\'ayez pas de contrat. Veuillez contacter un responsable des ressources humaines.';
$lang['teleworks_validate_flash_msg_overlap_period'] = 'Vous ne pouvez pas créer une demande de télétravail pour deux périodes annuelles de télétravail. Veuillez créer deux demandes différentes.';

$lang['teleworks_cancel_flash_msg_error'] = 'Vous ne pouvez pas annuler cette demande de télétravail.';
$lang['teleworks_cancel_flash_msg_success'] = 'La demande de télétravail a été annulée avec succès.';
$lang['teleworks_cancel_unauthorized_msg_error'] = 'Vous ne pouvez pas annuler une demande de télétravail commançant dans le passé. Demandez à votre manager de la rejeter.' ;

$lang['teleworks_delete_flash_msg_error'] = 'Vous ne pouvez pas supprimer cette demande de télétravail.';
$lang['teleworks_delete_flash_msg_success'] = 'La demande de télétravail a été supprimée avec succès.';

$lang['teleworks_view_title'] = 'Visualiser la demande de télétravail N°';
$lang['teleworks_view_html_title'] = 'Visualiser une demande';
$lang['teleworks_view_field_start'] = 'Date de début';
$lang['teleworks_view_field_end'] = 'Date de fin';
$lang['teleworks_view_field_duration'] = 'Durée';
$lang['teleworks_view_field_cause'] = 'Cause';
$lang['teleworks_view_field_status'] = 'Statut';
$lang['teleworks_view_button_edit'] = 'Modifier';
$lang['teleworks_view_button_back_list'] = 'Retour à la liste';

$lang['teleworks_export_title'] = 'Liste des demandes de télétravail';
$lang['teleworks_export_thead_id'] = 'ID';
$lang['teleworks_export_thead_start_date'] = 'Date début';
$lang['teleworks_export_thead_start_date_type'] = 'Matin/Après-midi';
$lang['teleworks_export_thead_end_date'] = 'Date fin';
$lang['teleworks_export_thead_end_date_type'] = 'Matin/Après-midi';
$lang['teleworks_export_thead_cause'] = 'Cause';
$lang['teleworks_export_thead_duration'] = 'Durée';
$lang['teleworks_export_thead_type'] = 'Type';
$lang['teleworks_export_thead_campaign'] = 'Campagne';
$lang['teleworks_export_thead_status'] = 'Statut';

$lang['teleworks_button_send_reminder'] = 'Envoyer un rappel';
$lang['teleworks_reminder_flash_msg_success'] = 'L\'email de rappel a été envoyé au manager.';

$lang['teleworks_comment_title'] = 'Commentaires';
$lang['teleworks_comment_new_comment'] = 'Nouveau commentaire';
$lang['teleworks_comment_send_comment'] = 'Ajouter un commentaire';
$lang['teleworks_comment_author_saying'] = ' a dit';
$lang['teleworks_comment_status_changed'] = 'Le statut de la demande a changé : ';

$lang['Campaign'] = 'Campagne';
$lang['Floating'] = 'Flottant';

$lang['all_recurrence'] = 'Toutes les semaines';
$lang['even_week'] = 'Semaines paires';
$lang['odd_week'] = 'Semaines impaires';
