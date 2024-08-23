<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.6
 */

$lang['admin_diagnostic_title'] = 'Diagnostic des données';
$lang['admin_diagnostic_description'] = 'Détection des anomalies de saisie et de configuration.';
$lang['admin_diagnostic_no_error'] = 'Aucune erreur détectée';

$lang['admin_diagnostic_requests_tab'] = 'Demandes de congé';
$lang['admin_diagnostic_requests_description'] = 'Demandes de congé acceptées et dupliquées';
$lang['admin_diagnostic_requests_thead_id'] = 'N°';
$lang['admin_diagnostic_requests_thead_employee'] = 'Employé';
$lang['admin_diagnostic_requests_thead_start_date'] = 'Date début';
$lang['admin_diagnostic_requests_thead_status'] = 'Statut';
$lang['admin_diagnostic_requests_thead_type'] = 'Type';

$lang['admin_diagnostic_datetype_tab'] = 'Après-midi/Matin';
$lang['admin_diagnostic_datetype_description'] = 'Demandes de congé avec un mauvais type de début/fin';
$lang['admin_diagnostic_datetype_thead_id'] = 'N°';
$lang['admin_diagnostic_datetype_thead_employee'] = 'Employé';
$lang['admin_diagnostic_datetype_thead_start_date'] = 'Date';
$lang['admin_diagnostic_datetype_thead_start_type'] = 'Début';
$lang['admin_diagnostic_datetype_thead_end_type'] = 'Fin';
$lang['admin_diagnostic_datetype_thead_status'] = 'Statut';

$lang['admin_diagnostic_entitlements_tab'] = 'Crédits congé';
$lang['admin_diagnostic_entitlements_description'] = 'Liste des contrats et des employés présentant des crédits congé supérieurs à un an.';
$lang['admin_diagnostic_entitlements_thead_id'] = 'N°';
$lang['admin_diagnostic_entitlements_thead_type'] = 'Type';
$lang['admin_diagnostic_entitlements_thead_name'] = 'Nom';
$lang['admin_diagnostic_entitlements_thead_start_date'] = 'Date début';
$lang['admin_diagnostic_entitlements_thead_end_date'] = 'Date fin';
$lang['admin_diagnostic_entitlements_type_contract'] = 'Contrat';
$lang['admin_diagnostic_entitlements_type_employee'] = 'Employé';
$lang['admin_diagnostic_entitlements_deletion_problem'] = 'La suppression de l\'objet n\'est pas complète.' ;

$lang['admin_diagnostic_daysoff_tab'] = 'Journées non travaillées';
$lang['admin_diagnostic_daysoff_description'] = 'Nombre de journées (par contrat) pour lesquelles une durée non travaillée a été définie.';
$lang['admin_diagnostic_daysoff_thead_id'] = 'N°';
$lang['admin_diagnostic_daysoff_thead_name'] = 'Nom';
$lang['admin_diagnostic_daysoff_thead_ym1'] = 'L\'année dernière';
$lang['admin_diagnostic_daysoff_thead_y'] = 'Cette année';
$lang['admin_diagnostic_daysoff_thead_yp1'] = 'L\'année prochaine';

$lang['admin_diagnostic_overtime_tab'] = 'Heures supp.';
$lang['admin_diagnostic_overtime_description'] = 'Déclarations d\'heures supplémentaires avec une durée négative.';
$lang['admin_diagnostic_overtime_thead_id'] = 'N°';
$lang['admin_diagnostic_overtime_thead_employee'] = 'Employé';
$lang['admin_diagnostic_overtime_thead_date'] = 'Date';
$lang['admin_diagnostic_overtime_thead_duration'] = 'Durée';
$lang['admin_diagnostic_overtime_thead_status'] = 'Statut';

$lang['admin_diagnostic_teleworks_requests_tab'] = 'Demandes de télétravail';
$lang['admin_diagnostic_teleworks_requests_description'] = 'Demandes de télétravail acceptées et dupliquées';
$lang['admin_diagnostic_teleworks_requests_thead_id'] = 'N°';
$lang['admin_diagnostic_teleworks_requests_thead_employee'] = 'Employé';
$lang['admin_diagnostic_teleworks_requests_thead_start_date'] = 'Date début';
$lang['admin_diagnostic_teleworks_requests_thead_status'] = 'Statut';
$lang['admin_diagnostic_teleworks_requests_thead_type'] = 'Type';

$lang['admin_diagnostic_teleworks_datetype_tab'] = 'Après-midi/Matin (TT)';
$lang['admin_diagnostic_teleworks_datetype_description'] = 'Demandes de télétravail avec un mauvais type de début/fin';
$lang['admin_diagnostic_teleworks_datetype_thead_id'] = 'N°';
$lang['admin_diagnostic_teleworks_datetype_thead_employee'] = 'Employé';
$lang['admin_diagnostic_teleworks_datetype_thead_start_date'] = 'Date';
$lang['admin_diagnostic_teleworks_datetype_thead_start_type'] = 'Début';
$lang['admin_diagnostic_teleworks_datetype_thead_end_type'] = 'Fin';
$lang['admin_diagnostic_teleworks_datetype_thead_status'] = 'Statut';

$lang['admin_diagnostic_contract_tab'] = 'Contrats';
$lang['admin_diagnostic_contract_description'] = 'Contrats non utilisés (vérifier si le contrat n\'est pas en double).';
$lang['admin_diagnostic_contract_thead_id'] = 'N°';
$lang['admin_diagnostic_contract_thead_name'] = 'Nom';

$lang['admin_diagnostic_balance_tab'] = 'Balance';
$lang['admin_diagnostic_balance_description'] = 'Demandes de congé pour lesquelles il n\'y a pas de crédit.';
$lang['admin_diagnostic_balance_thead_id'] = 'N°';
$lang['admin_diagnostic_balance_thead_employee'] = 'Employé';
$lang['admin_diagnostic_balance_thead_contract'] = 'Contrat';
$lang['admin_diagnostic_balance_thead_start_date'] = 'Date début';
$lang['admin_diagnostic_balance_thead_status'] = 'Statut';

$lang['admin_diagnostic_overlapping_tab'] = 'Chevauchement';
$lang['admin_diagnostic_overlapping_description'] = 'Demandes de congé chevauchant deux périodes annuelles.';
$lang['admin_diagnostic_overlapping_thead_id'] = 'ID';
$lang['admin_diagnostic_overlapping_thead_employee'] = 'Employé';
$lang['admin_diagnostic_overlapping_thead_contract'] = 'Contrat';
$lang['admin_diagnostic_overlapping_thead_start_date'] = 'Date début';
$lang['admin_diagnostic_overlapping_thead_end_date'] = 'Date fin';
$lang['admin_diagnostic_overlapping_thead_status'] = 'Statut';

$lang['admin_diagnostic_teleworks_overlapping_tab'] = 'Chevauchement (TT)';
$lang['admin_diagnostic_teleworks_overlapping_description'] = 'Demandes de télétravail chevauchant deux périodes annuelles.';
$lang['admin_diagnostic_teleworks_overlapping_thead_id'] = 'ID';
$lang['admin_diagnostic_teleworks_overlapping_thead_employee'] = 'Employé';
$lang['admin_diagnostic_teleworks_overlapping_thead_contract'] = 'Contrat';
$lang['admin_diagnostic_teleworks_overlapping_thead_start_date'] = 'Date début';
$lang['admin_diagnostic_teleworks_overlapping_thead_end_date'] = 'Date fin';
$lang['admin_diagnostic_teleworks_overlapping_thead_status'] = 'Statut';

$lang['admin_oauthclients_title'] = 'Clients et sessions OAuth';
$lang['admin_oauthclients_tab_clients'] = 'Clients';
$lang['admin_oauthclients_tab_clients_description'] = 'Liste des clients habilités pour l\'API REST';
$lang['admin_oauthclients_thead_tip_edit'] = 'modifier le client';
$lang['admin_oauthclients_thead_tip_delete'] = 'supprimer le client';
$lang['admin_oauthclients_button_add'] = 'Ajouter';
$lang['admin_oauthclients_popup_add_title'] = 'Ajouter un client OAuth';
$lang['admin_oauthclients_popup_select_user_title'] = 'Associer à un utilisateur réel';
$lang['admin_oauthclients_error_exists'] = 'Ce client_id existe déjà';
$lang['admin_oauthclients_confirm_delete'] = 'Êtes-vous sûr de vouloir continuer?';
$lang['admin_oauthclients_tab_sessions'] = 'Sessions';
$lang['admin_oauthclients_tab_sessions_description'] = 'Liste des Sessions OAuth API REST actives';
$lang['admin_oauthclients_button_purge'] = 'Purge';
