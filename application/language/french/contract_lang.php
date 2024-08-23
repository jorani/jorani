<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */

$lang['contract_index_title'] = 'Liste des contrats';
$lang['contract_index_thead_id'] = 'N°';
$lang['contract_index_thead_name'] = 'Nom';
$lang['contract_index_thead_start'] = 'Début période';
$lang['contract_index_thead_end'] = 'Fin période';
$lang['contract_index_tip_delete'] = 'supprimer contrat';
$lang['contract_index_tip_edit'] = 'Modifier contrat';
$lang['contract_index_tip_entitled'] = 'Crédit congés';
$lang['contract_index_tip_dayoffs'] = 'Jours non travaillés';
$lang['contract_index_tip_exclude_types'] = 'Exclure des types de congé';
$lang['contract_index_button_export'] = 'Exporter cette liste';
$lang['contract_index_button_create'] = 'Créer contrat';
$lang['contract_index_popup_delete_title'] = 'Supprimer contrat';
$lang['contract_index_popup_delete_description'] = 'Vous êtes sur le point de supprimer un contrat, cette procédure est irréversible.';
$lang['contract_index_popup_delete_confirm'] = 'Voulez-vous continuer ?';
$lang['contract_index_popup_delete_button_yes'] = 'Oui';
$lang['contract_index_popup_delete_button_no'] = 'Non';
$lang['contract_index_popup_entitled_title'] = 'Crédit congés';
$lang['contract_index_popup_entitled_button_cancel'] = 'Annuler';
$lang['contract_index_popup_entitled_button_close'] = 'Fermer';

$lang['contract_exclude_title'] = 'Exclure un type de congé pour un contrat';
$lang['contract_exclude_description'] = 'Vous ne pouvez pas exclure des types de congé déjà utilisés (utilisés au moins une fois par un employé attaché au contrat) et le type de congé par défaut (défini sur le contrat ou dans le fichier de configuration).';
$lang['contract_exclude_title_included'] = 'Types de congé inclus';
$lang['contract_exclude_title_excluded'] = 'Types de congé exclus';
$lang['contract_exclude_tip_include_type'] = 'Inclure ce type de congé';
$lang['contract_exclude_tip_exclude_type'] = 'Exclure ce type de congé';
$lang['contract_exclude_tip_already_used'] = 'Ce type de congé est utilisé';
$lang['contract_exclude_tip_default_type'] = 'Vous ne pouvez pas exclure le type de congé par défaut';

$lang['contract_edit_title'] = 'Modifier un contrat';
$lang['contract_edit_description'] = 'Modifier le contrat n°';
$lang['contract_edit_field_name'] = 'Nom';
$lang['contract_edit_field_start_month'] = 'Mois / Début';
$lang['contract_edit_field_start_day'] = 'Jour / Début';
$lang['contract_edit_field_end_month'] = 'Mois / Fin';
$lang['contract_edit_field_end_day'] = 'Jour / Fin';
$lang['contract_edit_default_leave_type'] = 'Type de congé par défaut';
$lang['contract_edit_button_update'] = 'Modifier contrat';
$lang['contract_edit_button_cancel'] = 'Annuler';
$lang['contract_edit_msg_success'] = 'Le contrat a été modifié avec succès';

$lang['contract_create_title'] = 'Créer un nouveau contrat';
$lang['contract_create_field_name'] = 'Nom';
$lang['contract_create_field_start_month'] = 'Mois / Début';
$lang['contract_create_field_start_day'] = 'Jour / Début';
$lang['contract_create_field_end_month'] = 'Mois / Fin';
$lang['contract_create_field_end_day'] = 'Jour / Fin';
$lang['contract_create_default_leave_type'] = 'Type de congé par défaut';
$lang['contract_create_button_create'] = 'Créer contrat';
$lang['contract_create_button_cancel'] = 'Annuler';
$lang['contract_create_msg_success'] = 'Le contrat a été créé avec succès';

$lang['contract_delete_msg_success'] = 'Le contrat a été supprimé avec succès';

$lang['contract_export_title'] = 'Liste des contrats';
$lang['contract_export_thead_id'] = 'N°';
$lang['contract_export_thead_name'] = 'Nom';
$lang['contract_export_thead_start'] = 'Début période';
$lang['contract_export_thead_end'] = 'Fin période';

$lang['contract_calendar_title'] = 'Calendrier des jours non travaillés';
$lang['contract_calendar_description'] = 'Les jours non travaillés et les week-ends ne sont par configurés par défaut. Cliquez sur une journée pour la modifier ou utilisez le bouton "Série".';
$lang['contract_calendar_legend_title'] = 'Légende :';
$lang['contract_calendar_legend_allday'] = 'Journée';
$lang['contract_calendar_legend_morning'] = 'Matin';
$lang['contract_calendar_legend_afternoon'] = 'Après-midi';
$lang['contract_calendar_button_back'] = 'Retour aux contrats';
$lang['contract_calendar_button_series'] = 'Série de jours non travaillés';
$lang['contract_calendar_popup_dayoff_title'] = 'Modifier une journée non travaillée';
$lang['contract_calendar_popup_dayoff_field_title'] = 'Titre';
$lang['contract_calendar_popup_dayoff_field_type'] = 'Type';
$lang['contract_calendar_popup_dayoff_type_working'] = 'journée travaillée';
$lang['contract_calendar_popup_dayoff_type_off'] = 'journée non travaillée';
$lang['contract_calendar_popup_dayoff_type_morning'] = 'Matinée non travaillée';
$lang['contract_calendar_popup_dayoff_type_afternoon'] = 'Après-midi  non travaillé';
$lang['contract_calendar_popup_dayoff_button_delete'] = 'Supprimer';
$lang['contract_calendar_popup_dayoff_button_ok'] = 'OK';
$lang['contract_calendar_popup_dayoff_button_cancel'] = 'Annuler';
$lang['contract_calendar_button_import'] = 'Importer iCal';
$lang['contract_calendar_prompt_import'] = 'URL du fichier iCal des jours non travaillés';

$lang['contract_calendar_popup_series_title'] = 'Modifier une série de jours non travaillés';
$lang['contract_calendar_popup_series_field_occurences'] = 'Marquer tous les';
$lang['contract_calendar_popup_series_field_from'] = 'Du';
$lang['contract_calendar_popup_series_button_current'] = 'Courante';
$lang['contract_calendar_popup_series_field_to'] = 'Au';
$lang['contract_calendar_popup_series_field_as'] = 'En tant que';
$lang['contract_calendar_popup_series_field_as_working'] = 'Jour travaillé';
$lang['contract_calendar_popup_series_field_as_off'] = 'Jour non travaillé';
$lang['contract_calendar_popup_series_field_as_morning'] = 'Matin non travaillé';
$lang['contract_calendar_popup_series_field_as_afternnon'] = 'Après-midi non travaillé';
$lang['contract_calendar_popup_series_field_title'] = 'Titre';
$lang['contract_calendar_popup_series_button_ok'] = 'OK';
$lang['contract_calendar_popup_series_button_cancel'] = 'Annuler';

$lang['contract_calendar_button_copy'] = 'Copier';
$lang['contract_calendar_copy_destination_js_msg'] = 'Vous devez sélectionner un contrat.';
$lang['contract_calendar_copy_msg_success'] = 'Les données ont été copiées avec succès.';
