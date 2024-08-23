<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

$lang['requests_index_title'] = 'Demandes d\'absence à valider';
$lang['requests_index_description'] = 'Cet écran liste les demandes de congé qui m\'ont été soumises pour validation. Si vous n\'êtes pas un manager, cette liste sera vide.';
$lang['requests_index_thead_tip_view'] = 'voir';
$lang['requests_index_thead_tip_accept'] = 'accepter';
$lang['requests_index_thead_tip_reject'] = 'refuser';
$lang['requests_index_thead_tip_history'] = 'afficher l\'historique';
$lang['requests_index_thead_id'] = 'N°';
$lang['requests_index_thead_fullname'] = 'Nom complet';
$lang['requests_index_thead_startdate'] = 'Date début';
$lang['requests_index_thead_enddate'] = 'Date fin';
$lang['requests_index_thead_duration'] = 'Durée';
$lang['requests_index_thead_type'] = 'Type';
$lang['requests_index_thead_status'] = 'Statut';
$lang['requests_index_thead_requested_date'] = 'Demandée le';
$lang['requests_index_thead_last_change'] = 'Modifiée le';

$lang['requests_collaborators_title'] = 'Liste de mes collaborateurs';
$lang['requests_collaborators_description'] = 'Cet écran liste vos collaborateurs. Si vous n\'êtes pas un manager, cette liste sera vide.';
$lang['requests_collaborators_thead_id'] = 'ID';
$lang['requests_collaborators_thead_link_balance'] = 'Etat des congés';
$lang['requests_collaborators_thead_link_presence'] = 'Rapport sur la présence';
$lang['requests_collaborators_thead_link_year'] = 'Calendrier annuel';
$lang['requests_collaborators_thead_link_create_leave'] = 'Créer une demande de congé pour ce collaborateur';
$lang['requests_collaborators_thead_firstname'] = 'Prénom';
$lang['requests_collaborators_thead_lastname'] = 'Nom';
$lang['requests_collaborators_thead_email'] = 'E-mail';
$lang['requests_collaborators_thead_identifier'] = 'Identifiant';

$lang['requests_summary_title'] = 'Etat des congés pour l\'utilisateur #';
$lang['requests_summary_thead_type'] = 'Type de congé';
$lang['requests_summary_thead_available'] = 'Disponible';
$lang['requests_summary_thead_taken'] = 'Pris';
$lang['requests_summary_thead_entitled'] = 'Acquis';
$lang['requests_summary_thead_description'] = 'Description';
$lang['requests_summary_flash_msg_error'] = 'Cet employé n\'a pas de contrat.';
$lang['requests_summary_flash_msg_forbidden'] = 'Vous n\'êtes pas le supérieur hierarchique de cet employé.';
$lang['requests_summary_button_list'] = 'Liste des collaborateurs';

$lang['requests_index_button_export'] = 'Exporter cette liste';
$lang['requests_index_button_show_all'] = 'Toutes les demandes';
$lang['requests_index_button_show_pending'] = 'Demandes en cours';

$lang['requests_accept_flash_msg_error'] = 'Vous n\'êtes pas le supérieur hiérarchique de cet employé. Vous ne pouvez pas accepter sa demande.';
$lang['requests_accept_flash_msg_success'] = 'La demande d\'absence a été acceptée avec succès.';

$lang['requests_reject_flash_msg_error'] = 'Vous n\'êtes pas le supérieur hiérarchique de cet employé. Vous ne pouvez pas refuser sa demande.';
$lang['requests_reject_flash_msg_success'] = 'La demande d\'absence a été refusée avec succès.';

$lang['requests_export_title'] = 'Liste des demandes d\'absence';
$lang['requests_export_thead_id'] = 'ID';
$lang['requests_export_thead_fullname'] = 'Nom complet';
$lang['requests_export_thead_startdate'] = 'Date début';
$lang['requests_export_thead_startdate_type'] = 'Matin/Après-midi';
$lang['requests_export_thead_enddate'] = 'Date fin';
$lang['requests_export_thead_enddate_type'] = 'Matin/Après-midi';
$lang['requests_export_thead_duration'] = 'Durée';
$lang['requests_export_thead_type'] = 'Type';
$lang['requests_export_thead_cause'] = 'Cause';
$lang['requests_export_thead_status'] = 'Statut';

$lang['requests_delegations_title'] = 'Liste des délégations';
$lang['requests_delegations_description'] = 'Liste des employés pouvant accepter ou refuser des demandes à votre place.';
$lang['requests_delegations_thead_employee'] = 'Employé';
$lang['requests_delegations_thead_tip_delete'] = 'Révoquer';
$lang['requests_delegations_button_add'] = 'Ajouter';
$lang['requests_delegations_popup_delegate_title'] = 'Ajouter un délégué';
$lang['requests_delegations_popup_delegate_button_ok'] = 'OK';
$lang['requests_delegations_popup_delegate_button_cancel'] = 'Annuler';
$lang['requests_delegations_confirm_delete_message'] = 'Etes vous sûr de vouloir révoquer cette délégation ?';
$lang['requests_delegations_confirm_delete_cancel'] = 'Annuler';
$lang['requests_delegations_confirm_delete_yes'] = 'Oui';

$lang['requests_balance_title'] = 'État des congés (collaborateurs)';
$lang['requests_balance_description'] = 'État des congés de mes collaborateurs. Si vous n\'êtes pas un manager, cette liste sera vide.';
$lang['requests_balance_date_field'] = 'Date du rapport';

$lang['requests_comment_reject_request_title'] = 'Commentaire';
$lang['requests_comment_reject_request_button_cancel'] = 'Annuler';
$lang['requests_comment_reject_request_button_reject'] = 'Refuser';
