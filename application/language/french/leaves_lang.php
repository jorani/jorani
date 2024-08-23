<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

$lang['leaves_summary_title'] = 'Mes compteurs';
$lang['leaves_summary_title_overtime'] = 'Détail de la compensation (ajouté au repos compensatoire)';
$lang['leaves_summary_key_overtime'] = 'Récupération du';
$lang['leaves_summary_thead_type'] = 'Type d\'absence';
$lang['leaves_summary_thead_available'] = 'Disponible';
$lang['leaves_summary_thead_taken'] = 'Pris';
$lang['leaves_summary_thead_entitled'] = 'Acquis';
$lang['leaves_summary_thead_description'] = 'Description';
$lang['leaves_summary_thead_actual'] = 'réel';
$lang['leaves_summary_thead_simulated'] = 'simulé';
$lang['leaves_summary_tbody_empty'] = 'Aucun jour pris ou disponible. Veuillez contacter un responsable des ressources humaines.';
$lang['leaves_summary_flash_msg_error'] = 'Il semble que vous n\'ayez pas de contrat. Veuillez contacter un responsable des ressources humaines.';
$lang['leaves_summary_date_field'] = 'Date du rapport';

$lang['leaves_index_title'] = 'Mes demandes de congé';
$lang['leaves_index_thead_tip_view'] = 'voir';
$lang['leaves_index_thead_tip_edit'] = 'modifier';
$lang['leaves_index_thead_tip_cancel'] = 'annuler';
$lang['leaves_index_thead_tip_delete'] = 'supprimer';
$lang['leaves_index_thead_tip_history'] = 'afficher l\'historique';
$lang['leaves_index_thead_id'] = 'N°';
$lang['leaves_index_thead_start_date'] = 'Date début';
$lang['leaves_index_thead_end_date'] = 'Date fin';
$lang['leaves_index_thead_cause'] = 'Cause';
$lang['leaves_index_thead_duration'] = 'Durée';
$lang['leaves_index_thead_type'] = 'Type';
$lang['leaves_index_thead_status'] = 'Statut';
$lang['leaves_index_thead_requested_date'] = 'Demandée le';
$lang['leaves_index_thead_last_change'] = 'Modifiée le';
$lang['leaves_index_button_export'] = 'Exporter cette liste';
$lang['leaves_index_button_create'] = 'Nouvelle demande';
$lang['leaves_index_popup_delete_title'] = 'Suppression d\'une demandes de congé';
$lang['leaves_index_popup_delete_message'] = 'Vous êtes sur le point de supprimer une demandes de congé, cette procédure est irréversible.';
$lang['leaves_index_popup_delete_question'] = 'Voulez-vous continuer ?';
$lang['leaves_index_popup_delete_button_yes'] = 'Oui';
$lang['leaves_index_popup_delete_button_no'] = 'Non';

$lang['leaves_history_thead_changed_date'] = 'Modifié le';
$lang['leaves_history_thead_change_type'] = 'Type de modif.';
$lang['leaves_history_thead_changed_by'] = 'Modifié par';
$lang['leaves_history_thead_start_date'] = 'Date début';
$lang['leaves_history_thead_end_date'] = 'Date fin';
$lang['leaves_history_thead_cause'] = 'Cause';
$lang['leaves_history_thead_duration'] = 'Durée';
$lang['leaves_history_thead_type'] = 'Type';
$lang['leaves_history_thead_status'] = 'Statut';

$lang['leaves_create_title'] = 'Créer une nouvelle demande';
$lang['leaves_create_field_start'] = 'Date de début';
$lang['leaves_create_field_end'] = 'Date de fin';
$lang['leaves_create_field_type'] = 'Type de congé';
$lang['leaves_create_field_duration'] = 'Durée';
$lang['leaves_create_field_duration_message'] = 'Vous dépassez le nombre de jours permis';
$lang['leaves_create_field_overlapping_message'] = 'Vous avez demandé une absence durant la même période.';
$lang['leaves_create_field_cause'] = 'Cause (optionnelle)';
$lang['leaves_create_field_status'] = 'Statut';
$lang['leaves_create_button_create'] = 'Créer la demande';
$lang['leaves_create_button_cancel'] = 'Annuler';

$lang['leaves_create_flash_msg_success'] = 'La demande d\'absence a été créée avec succès.';
$lang['leaves_create_flash_msg_error'] = 'La demande d\'absence a été créée ou modifiée avec succès, mais vous n\'avez pas de manager.';

$lang['leaves_flash_spn_list_days_off'] = '%s jours non travaillés dans la période';
$lang['leaves_flash_msg_overlap_dayoff'] = 'Votre demande coïncide avec un jour non travaillé.';

$lang['leaves_cancellation_flash_msg_error'] = 'Vous ne pouvez pas annuler cette demande de congé.';
$lang['leaves_cancellation_flash_msg_success'] = 'La demande d\'annulation a été envoyée avec succès.';
$lang['requests_cancellation_accept_flash_msg_success'] = 'La demande de congé a été annulée avec succès.';
$lang['requests_cancellation_accept_flash_msg_error'] = 'Une erreur est apparue en tentant d\'annuler la demande.';
$lang['requests_cancellation_reject_flash_msg_success'] = 'La demande de congé a maintenant son statut originel *Acceptée*';
$lang['requests_cancellation_reject_flash_msg_error'] = 'Une erreur est apparue en tentant de rejetter la demande d\'annulation.';

$lang['leaves_edit_html_title'] = 'Modifier la demande';
$lang['leaves_edit_title'] = 'Modifier la demande N°';
$lang['leaves_edit_field_start'] = 'Date de début';
$lang['leaves_edit_field_end'] = 'Date de fin';
$lang['leaves_edit_field_type'] = 'Type de congé';
$lang['leaves_edit_field_duration'] = 'Durée';
$lang['leaves_edit_field_duration_message'] = 'Vous dépassez le nombre de jours permis';
$lang['leaves_edit_field_cause'] = 'Cause (optionelle)';
$lang['leaves_edit_field_status'] = 'Statut';
$lang['leaves_edit_button_update'] = 'Mettre à jour';
$lang['leaves_edit_button_cancel'] = 'Annuler';
$lang['leaves_edit_flash_msg_error'] = 'Vous ne pouvez pas modifier une demande déjà soumise.';
$lang['leaves_edit_flash_msg_success'] = 'La demande d\'absence a été modifiée avec succès.';

$lang['leaves_validate_mandatory_js_msg'] = '"Le champ " + fieldname + " est obligatoire."';
$lang['leaves_validate_flash_msg_no_contract'] = 'Il semble que vous n\'ayez pas de contrat. Veuillez contacter un responsable des ressources humaines.';
$lang['leaves_validate_flash_msg_overlap_period'] = 'Vous ne pouvez pas créer une demande de congé pour deux périodes annuelles de congé. Veuillez créer deux demandes différentes.';

$lang['leaves_cancel_flash_msg_error'] = 'Vous ne pouvez pas annuler cette demande d\'absence.';
$lang['leaves_cancel_flash_msg_success'] = 'La demande d\'absence a été annulée avec succès.';
$lang['leaves_cancel_unauthorized_msg_error'] = 'Vous ne pouvez pas annuler une demande d\'absence commançant dans le passé. Demandez à votre manager de la rejeter.' ;

$lang['leaves_delete_flash_msg_error'] = 'Vous ne pouvez pas supprimer cette demande d\'absence.';
$lang['leaves_delete_flash_msg_success'] = 'La demande d\'absence a été supprimée avec succès.';

$lang['leaves_view_title'] = 'Visualiser la demande N°';
$lang['leaves_view_html_title'] = 'Visualiser une demande';
$lang['leaves_view_field_start'] = 'Date de début';
$lang['leaves_view_field_end'] = 'Date de fin';
$lang['leaves_view_field_type'] = 'Type de congé';
$lang['leaves_view_field_duration'] = 'Durée';
$lang['leaves_view_field_cause'] = 'Cause';
$lang['leaves_view_field_status'] = 'Statut';
$lang['leaves_view_button_edit'] = 'Modifier';
$lang['leaves_view_button_back_list'] = 'Retour à la liste';

$lang['leaves_export_title'] = 'Liste des demandes d\'absence';
$lang['leaves_export_thead_id'] = 'ID';
$lang['leaves_export_thead_start_date'] = 'Date début';
$lang['leaves_export_thead_start_date_type'] = 'Matin/Après-midi';
$lang['leaves_export_thead_end_date'] = 'Date fin';
$lang['leaves_export_thead_end_date_type'] = 'Matin/Après-midi';
$lang['leaves_export_thead_cause'] = 'Cause';
$lang['leaves_export_thead_duration'] = 'Durée';
$lang['leaves_export_thead_type'] = 'Type';
$lang['leaves_export_thead_status'] = 'Statut';

$lang['leaves_button_send_reminder'] = 'Envoyer un rappel';
$lang['leaves_reminder_flash_msg_success'] = 'L\'email de rappel a été envoyé au manager.';

$lang['leaves_comment_title'] = 'Commentaires';
$lang['leaves_comment_new_comment'] = 'Nouveau commentaire';
$lang['leaves_comment_send_comment'] = 'Ajouter un commentaire';
$lang['leaves_comment_author_saying'] = ' a dit';
$lang['leaves_comment_status_changed'] = 'Le statut de la demande a changé : ';
