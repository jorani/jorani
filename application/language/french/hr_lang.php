<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

$lang['hr_employees_title'] = 'Liste des employés';
$lang['hr_employees_thead_tip_edit'] = 'Modifier employé';
$lang['hr_employees_thead_tip_entitlment'] = 'crédit congés';
$lang['hr_employees_thead_link_leaves'] = 'Congés';
$lang['hr_employees_thead_link_teleworks'] = 'Télétravail';
$lang['hr_employees_thead_link_extra'] = 'Heures supp.';
$lang['hr_employees_thead_link_balance'] = 'État des congés';
$lang['hr_employees_thead_link_create_leave'] = 'Créer demande d\'absence';
$lang['hr_employees_thead_link_create_telework'] = 'Créer demande de télétravail flottant';
$lang['hr_employees_thead_link_create_campaign_telework'] = 'Créer demande de télétravail fixe pour la campagne';
$lang['hr_employees_thead_link_calendar'] = 'Calendrier annuel';
$lang['hr_employees_thead_link_presence'] = 'Rapport sur la présence';
$lang['hr_employees_thead_link_delegation'] = 'Délégations';
$lang['hr_employees_thead_id'] = 'N°';
$lang['hr_employees_thead_firstname'] = 'Prénom';
$lang['hr_employees_thead_lastname'] = 'Nom';
$lang['hr_employees_thead_email'] = 'E-mail';
$lang['hr_employees_thead_entity'] = 'Entité';
$lang['hr_employees_thead_contract'] = 'Contrat';
$lang['hr_employees_thead_manager'] = 'Manager';
$lang['hr_employees_thead_identifier'] = 'Identifiant';
$lang['hr_employees_thead_datehired'] = 'Date d\'embauche';
$lang['hr_employees_thead_position'] = 'Position';
$lang['hr_employees_button_create_user'] = 'Créer';
$lang['hr_employees_button_export'] = 'Exporter cette liste';
$lang['hr_employees_popup_entitlment_title'] = 'Crédit congés';
$lang['hr_employees_popup_entitlment_button_cancel'] = 'Annuler';
$lang['hr_employees_popup_entitlment_button_close'] = 'Fermer';
$lang['hr_employees_button_select'] = 'Selectionner';
$lang['hr_employees_field_entity'] = 'Entité';
$lang['hr_employees_popup_entity_title'] = 'Selectionner une entité';
$lang['hr_employees_popup_entity_button_ok'] = 'OK';
$lang['hr_employees_popup_entity_button_cancel'] = 'Annuler';
$lang['hr_employees_description'] = 'Clic droit / taphold sur un employé pour les actions contextuelles.';
$lang['hr_employees_field_subdepts'] = 'Inclure les sous-départments';

$lang['hr_employees_button_all'] = 'Tous';
$lang['hr_employees_button_active'] = 'Actifs';
$lang['hr_employees_button_inactive'] = 'Inactifs';
$lang['hr_employees_button_selection'] = 'Sélection';
$lang['hr_employees_button_select_all'] = 'Tout sélectionner';
$lang['hr_employees_button_deselect_all'] = 'Tout désélectionner';
$lang['hr_employees_button_select_manager'] = 'Sélectionner le Manager';
$lang['hr_employees_button_select_contract'] = 'Sélectionner le Contract';
$lang['hr_employees_button_select_entity'] = 'Sélectionner l\'entité';
$lang['hr_employees_popup_contract_title'] = 'Sélectionner le contract';
$lang['hr_employees_button_entitleddays'] = 'Crédits congés';
$lang['hr_employees_button_create_request'] = 'Créer une nouvelle demande';

$lang['hr_employees_popup_manager_title'] = 'Sélectionnez le manager';
$lang['hr_employees_multiple_edit_selection_msg'] = 'Vous devez sélectionner au moins une ligne dans la table';

$lang['hr_export_employees_title'] = 'Liste des employés';
$lang['hr_export_employees_thead_id'] = 'N°';
$lang['hr_export_employees_thead_firstname'] = 'Prénom';
$lang['hr_export_employees_thead_lastname'] = 'Nom';
$lang['hr_export_employees_thead_email'] = 'E-mail';
$lang['hr_export_employees_thead_entity'] = 'Entité';
$lang['hr_export_employees_thead_contract'] = 'Contrat';
$lang['hr_export_employees_thead_manager'] = 'Manager';

$lang['hr_leaves_create_title'] = 'Créer une nouvelle demande';
$lang['hr_leaves_create_field_start'] = 'Date de début';
$lang['hr_leaves_create_field_end'] = 'Date de fin';
$lang['hr_leaves_create_field_type'] = 'Type de congé';
$lang['hr_leaves_create_field_duration'] = 'Durée';
$lang['hr_leaves_create_field_duration_message'] = 'Vous dépassez le nombre de jours permis';
$lang['hr_leaves_create_field_overlapping_message'] = 'Vous avez demandé une absence durant la même période.';
$lang['hr_leaves_create_field_cause'] = 'Cause (optionelle)';
$lang['hr_leaves_create_field_status'] = 'Statut';
$lang['hr_leaves_create_button_create'] = 'Créer la demande';
$lang['hr_leaves_create_button_cancel'] = 'Annuler';
$lang['hr_leaves_create_flash_msg_success'] = 'La demande d\'absence a été créée avec succès.';
$lang['hr_leaves_create_flash_msg_error'] = 'La demande d\'absence a été créée ou modifiée avec succès, mais vous n\'avez pas de manager.';
$lang['hr_leaves_flash_spn_list_days_off'] = '%s jours non travaillés dans la période';
$lang['hr_leaves_flash_msg_overlap_dayoff'] = 'Votre demande coïncide avec un jour non travaillé.';
$lang['hr_leaves_validate_mandatory_js_msg'] = '"Le champ " + fieldname + " est obligatoire."';
$lang['hr_leaves_validate_flash_msg_no_contract'] = 'Il semble que vous n\'ayez pas de contrat. Veuillez contacter un responsable des ressources humaines.';
$lang['hr_leaves_validate_flash_msg_overlap_period'] = 'Vous ne pouvez pas créer une demande de congé pour deux périodes annuelles de congé. Veuillez créer deux demandes différentes.';

$lang['hr_leaves_title'] = 'Liste des demandes de congé';
$lang['hr_leaves_html_title'] = 'Liste des demandes de congé de l\'employé n°';
$lang['hr_leaves_thead_tip_edit'] = 'Modifier';
$lang['hr_leaves_thead_tip_accept'] = 'Accepter';
$lang['hr_leaves_thead_tip_reject'] = 'Refuser';
$lang['hr_leaves_thead_tip_delete'] = 'Supprimer';
$lang['hr_leaves_thead_tip_history'] = 'Afficherl\'historique';
$lang['hr_leaves_thead_id'] = 'ID';
$lang['hr_leaves_thead_status'] = 'Statut';
$lang['hr_leaves_thead_start'] = 'Date début';
$lang['hr_leaves_thead_end'] = 'Date fin';
$lang['hr_leaves_thead_duration'] = 'Durée';
$lang['hr_leaves_thead_type'] = 'Type';
$lang['hr_leaves_button_export'] = 'Exporter cette liste';
$lang['hr_leaves_button_list'] = 'Liste des employés';
$lang['hr_leaves_popup_delete_title'] = 'Supprimer la demande de congés';
$lang['hr_leaves_popup_delete_message'] = 'Vous êtes sur le point de supprimer une demande de congés, cette procédure est irréversible.';
$lang['hr_leaves_popup_delete_question'] = 'Voulez-vous continuer ?';
$lang['hr_leaves_popup_delete_button_yes'] = 'Oui';
$lang['hr_leaves_popup_delete_button_no'] = 'Non';
$lang['hr_leaves_deleted_title'] = 'Liste des demandes supprimées';

$lang['hr_export_leaves_title'] = 'Liste des demandes de congés';
$lang['hr_export_leaves_thead_id'] = 'N°';
$lang['hr_export_leaves_thead_status'] = 'Statut';
$lang['hr_export_leaves_thead_start'] = 'Date début';
$lang['hr_export_leaves_thead_end'] = 'Date fin';
$lang['hr_export_leaves_thead_duration'] = 'Durée';
$lang['hr_export_leaves_thead_type'] = 'Type';

$lang['hr_overtime_title'] = 'Déclarations d\'heures supp.';
$lang['hr_overtime_html_title'] = 'Déclarations d\'heures supp. de l\'employé n°';
$lang['hr_overtime_thead_tip_edit'] = 'Modifier';
$lang['hr_overtime_thead_tip_accept'] = 'Accepter';
$lang['hr_overtime_thead_tip_reject'] = 'Refuser';
$lang['hr_overtime_thead_tip_delete'] = 'Supprimer';
$lang['hr_overtime_thead_id'] = 'ID';
$lang['hr_overtime_thead_status'] = 'Statut';
$lang['hr_overtime_thead_date'] = 'Date';
$lang['hr_overtime_thead_duration'] = 'Durée';
$lang['hr_overtime_thead_cause'] = 'Cause';
$lang['hr_overtime_button_export'] = 'Exporter cette liste';
$lang['hr_overtime_button_list'] = 'Liste des employés';
$lang['hr_overtime_popup_delete_title'] = 'Supprimer déclaration';
$lang['hr_overtime_popup_delete_message'] = 'Vous êtes sur le point de supprimer une déclaration d\'heures supp., cette procédure est irréversible.';
$lang['hr_overtime_popup_delete_question'] = 'Voulez-vous continuer ?';
$lang['hr_overtime_popup_delete_button_yes'] = 'Oui';
$lang['hr_overtime_popup_delete_button_no'] = 'Non';

$lang['hr_export_overtime_title'] = 'Déclarations d\'heures supp.';
$lang['hr_export_overtime_thead_id'] = 'N°';
$lang['hr_export_overtime_thead_status'] = 'Statut';
$lang['hr_export_overtime_thead_date'] = 'Date';
$lang['hr_export_overtime_thead_duration'] = 'Durée';
$lang['hr_export_overtime_thead_cause'] = 'Cause';

$lang['hr_teleworks_create_title'] = 'Créer une nouvelle demande de télétravail flottant';
$lang['hr_teleworks_create_campaign_title'] = 'Créer une demande de télétravail fixe pour la campagne';
$lang['hr_teleworks_create_field_start'] = 'Date de début';
$lang['hr_teleworks_create_field_end'] = 'Date de fin';
$lang['hr_teleworks_create_field_recurrence'] = 'Récurrence';
$lang['hr_teleworks_create_field_duration'] = 'Durée';
$lang['hr_teleworks_create_field_campaign'] = 'Campagne';
$lang['hr_teleworks_create_field_duration_message'] = 'Vous dépassez le nombre de jours permis';
$lang['hr_teleworks_create_field_overlapping_message'] = 'Sur cette période, nous avons une demande de télétravail.';
$lang['hr_teleworks_create_field_overlapping_leaves_message'] = 'Sur cette période, nous avons une demande de congé.';
$lang['hr_teleworks_create_field_overlapping_time_organisations_message'] = 'Sur cette période, nous avons un aménagement du temps de travail.';
$lang['hr_teleworks_create_field_past_date_message'] = 'Les demandes de télétravail à des dates antérieures ne sont pas possibles.';
$lang['hr_teleworks_create_field_cause'] = 'Cause (optionelle)';
$lang['hr_teleworks_create_field_status'] = 'Statut';
$lang['hr_teleworks_create_field_day'] = 'Jour';
$lang['hr_teleworks_create_field_campaign'] = 'Campagne';
$lang['hr_teleworks_current_campaign'] = 'Campagne en cours';
$lang['hr_teleworks_next_campaign'] = 'Prochaine campagne';
$lang['hr_teleworks_create_button_create'] = 'Créer la demande';
$lang['hr_teleworks_create_button_cancel'] = 'Annuler';
$lang['hr_teleworks_create_flash_msg_success'] = 'La demande de télétravail a été créée avec succès.';
$lang['hr_teleworks_create_flash_msg_error'] = 'La demande de télétravail a été créée ou modifiée avec succès, mais vous n\'avez pas de manager.';
$lang['hr_teleworks_flash_spn_list_days_off'] = '%s jours non travaillés dans la période';
$lang['hr_teleworks_flash_msg_overlap_dayoff'] = 'Votre demande coïncide avec un jour non travaillé.';
$lang['hr_teleworks_validate_mandatory_js_msg'] = '"Le champ " + fieldname + " est obligatoire."';
$lang['hr_teleworks_validate_flash_msg_no_contract'] = 'Il semble que vous n\'ayez pas de contrat. Veuillez contacter un responsable des ressources humaines.';
$lang['hr_teleworks_validate_flash_msg_overlap_period'] = 'Vous ne pouvez pas créer une demande de télétravail pour deux périodes annuelles de télétravail. Veuillez créer deux demandes différentes.';
$lang['hr_teleworks_flash_msg_limit_exceeded'] = 'Votre demande dépasse le nombre de jours de télétravail autorisés par semaine.';
$lang['hr_teleworks_flash_msg_halfday_telework'] = 'La demande de télétravail d\'une demi-journée n\'est pas autorisée.';
$lang['hr_teleworks_thead_link_accept_all'] = 'Accepter tous';
$lang['hr_teleworks_thead_link_reject_all'] = 'Rejeter tous';

$lang['hr_teleworks_title'] = 'Liste des demandes de télétravail';
$lang['hr_teleworks_html_title'] = 'Liste des demandes de télétravail de l\'employé n°';
$lang['hr_teleworks_thead_tip_edit'] = 'Modifier';
$lang['hr_teleworks_thead_tip_accept'] = 'Accepter';
$lang['hr_teleworks_thead_tip_reject'] = 'Refuser';
$lang['hr_teleworks_thead_tip_delete'] = 'Supprimer';
$lang['hr_teleworks_thead_tip_history'] = 'Afficherl\'historique';
$lang['hr_teleworks_thead_id'] = 'ID';
$lang['hr_teleworks_thead_status'] = 'Statut';
$lang['hr_teleworks_thead_start'] = 'Date début';
$lang['hr_teleworks_thead_end'] = 'Date fin';
$lang['hr_teleworks_thead_duration'] = 'Durée';
$lang['hr_teleworks_thead_type'] = 'Type';
$lang['hr_teleworks_thead_campaign'] = 'Campagne';
$lang['hr_teleworks_button_export'] = 'Exporter cette liste';
$lang['hr_teleworks_button_list'] = 'Liste des employés';
$lang['hr_teleworks_popup_delete_title'] = 'Supprimer la demande de télétravail';
$lang['hr_teleworks_popup_delete_message'] = 'Vous êtes sur le point de supprimer une demande de télétravail, cette procédure est irréversible.';
$lang['hr_teleworks_popup_delete_question'] = 'Voulez-vous continuer ?';
$lang['hr_teleworks_popup_delete_button_yes'] = 'Oui';
$lang['hr_teleworks_popup_delete_button_no'] = 'Non';
$lang['hr_teleworks_deleted_title'] = 'Liste des demandes de télétravail supprimées';

$lang['hr_export_teleworks_title'] = 'Liste des demandes de télétravail';
$lang['hr_export_teleworks_thead_id'] = 'N°';
$lang['hr_export_teleworks_thead_status'] = 'Statut';
$lang['hr_export_teleworks_thead_start'] = 'Date début';
$lang['hr_export_teleworks_thead_end'] = 'Date fin';
$lang['hr_export_teleworks_thead_duration'] = 'Durée';
$lang['hr_export_teleworks_thead_type'] = 'Type';
$lang['hr_export_teleworks_thead_campaign'] = 'Campagne';

$lang['hr_summary_title'] = 'Compteur de congés pour l\'employé #';
$lang['hr_summary_thead_type'] = 'Type de congés';
$lang['hr_summary_thead_available'] = 'Disponible';
$lang['hr_summary_thead_taken'] = 'Pris';
$lang['hr_summary_thead_entitled'] = 'Acquis';
$lang['hr_summary_thead_description'] = 'Description';
$lang['hr_summary_flash_msg_error'] = 'Cet employé n\'a pas de contrat.';
$lang['hr_summary_button_list'] = 'Liste des employés';
$lang['hr_summary_date_field'] = 'Date du rapport';

$lang['hr_presence_title'] = 'Rapport de présence';
$lang['hr_presence_description'] = 'Par défaut, ce rapport montre les valeurs du mois précédent. Veuillez noter que la liste des absences ne contient que les demandes de congé acceptées.';
$lang['hr_presence_thead_tip_edit'] = 'modifier';
$lang['hr_presence_thead_id'] = 'N°';
$lang['hr_presence_thead_start'] = 'Date début';
$lang['hr_presence_thead_end'] = 'Date de fin';
$lang['hr_presence_thead_duration'] = 'Durée';
$lang['hr_presence_thead_type'] = 'Type';
$lang['hr_presence_button_execute'] = 'Exécuter';
$lang['hr_presence_button_list'] = 'Liste des employés';
$lang['hr_presence_employee'] = 'Employé';
$lang['hr_presence_contract'] = 'Contrat';
$lang['hr_presence_month'] = 'Mois';
$lang['hr_presence_days'] = 'Nombre de jours';
$lang['hr_presence_working_days'] = 'Nombre de jours ouvrés';
$lang['hr_presence_non_working_days'] = 'Nombre de jours non travaillés';
$lang['hr_presence_leave_duration'] = 'Durée des absences';
$lang['hr_presence_work_duration'] = 'Durée de présence';
$lang['hr_presence_overlapping_detected'] = 'Chevauchement detecté';
$lang['hr_presence_no_contract'] = 'L\'employé n\'a pas de contrat';
$lang['hr_presence_please_check'] = 'Veuillez vérifier';
$lang['hr_presence_leaves_list_title'] = 'Liste des absences du mois';
$lang['hr_presence_button_export'] = 'Exporter';

$lang['hr_telework_campaigns_create_field_overlapping_message'] = 'Vous avez créé une campaign durant la même période.';

$lang['Campaign'] = 'Campagne';
$lang['Floating'] = 'Flottant';

$lang['all_recurrence'] = 'Toutes les semaines';
$lang['even_week'] = 'Semaines paires';
$lang['odd_week'] = 'Semaines impaires';
