<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.2
 * @author        Oleg Koptev, Yevhen Kyrylchenko
 */

$lang['requests_index_title'] = 'Заявления на отпуск, представленные на ваше рассмотрение';
$lang['requests_index_description'] = 'Здесь показан список заявлений на отпуск, представленных на ваше рассмотрение. Если вы не руководитель, этот список всегда будет пуст.';
$lang['requests_index_thead_tip_view'] = 'просмотреть';
$lang['requests_index_thead_tip_accept'] = 'утвердить';
$lang['requests_index_thead_tip_reject'] = 'отклонить';
$lang['requests_index_thead_tip_history'] = 'show history';
$lang['requests_index_thead_id'] = 'ID';
$lang['requests_index_thead_fullname'] = 'ФИО';
$lang['requests_index_thead_startdate'] = 'Дата начала';
$lang['requests_index_thead_enddate'] = 'Дата окончания';
$lang['requests_index_thead_duration'] = 'Продолжительность';
$lang['requests_index_thead_type'] = 'Тип';
$lang['requests_index_thead_status'] = 'Состояние';
$lang['requests_index_thead_requested_date'] = 'Requested';
$lang['requests_index_thead_last_change'] = 'Last change';

$lang['requests_collaborators_title'] = 'Список моих сотрудников';
$lang['requests_collaborators_description'] = 'Здесь показан список ваших сотрудников. Если вы не руководитель, этот список всегда будет пуст.';
$lang['requests_collaborators_thead_id'] = 'ID';
$lang['requests_collaborators_thead_link_balance'] = 'Сальдо по отпускам';
$lang['requests_collaborators_thead_link_presence'] = 'Отчет посещаемости';
$lang['requests_collaborators_thead_link_year'] = 'Календарь на год';
$lang['requests_collaborators_thead_link_create_leave'] = 'Создать заявление на отпуск от имени этого сотрудника';
$lang['requests_collaborators_thead_firstname'] = 'Имя';
$lang['requests_collaborators_thead_lastname'] = 'Фамилия';
$lang['requests_collaborators_thead_email'] = 'Эл. почта';
$lang['requests_collaborators_thead_identifier'] = 'Идентификатор';

$lang['requests_summary_title'] = 'Сальдо по отпускам, для сотрудника N°';
$lang['requests_summary_thead_type'] = 'Тип отпуска';
$lang['requests_summary_thead_available'] = 'Доступно';
$lang['requests_summary_thead_taken'] = 'Использовано';
$lang['requests_summary_thead_entitled'] = 'Предоставляемые дни';
$lang['requests_summary_thead_description'] = 'Описание';
$lang['requests_summary_flash_msg_error'] = 'Этот работник не имеет контракта.';
$lang['requests_summary_flash_msg_forbidden'] = 'Вы не являетесь прямым руководителем этого сотрудника.';
$lang['requests_summary_button_list'] = 'Список сотрудников';

$lang['requests_index_button_export'] = 'Экспортировать список';
$lang['requests_index_button_show_all'] = 'Все запросы';
$lang['requests_index_button_show_pending'] = 'Запросы на рассмотрении';

$lang['requests_accept_flash_msg_error'] = 'Вы не являетесь прямым руководителем этого сотрудника. Вы не можете утвердить это заявление на отпуск.';
$lang['requests_accept_flash_msg_success'] = 'Заявление на отпуск успешно утверждено.';
$lang['requests_reject_flash_msg_error'] = 'Вы не являетесь прямым руководителем этого сотрудника. Вы не можете отклонить это заявление на отпуск.';
$lang['requests_reject_flash_msg_success'] = 'Заявление на отпуск успешно отклонено.';

$lang['requests_export_title'] = 'Список заявлений на отпуск';
$lang['requests_export_thead_id'] = 'ID';
$lang['requests_export_thead_fullname'] = 'ФИО';
$lang['requests_export_thead_startdate'] = 'Дата начала';
$lang['requests_export_thead_startdate_type'] = 'Утро/после полудня';
$lang['requests_export_thead_enddate'] = 'Дата окончания';
$lang['requests_export_thead_enddate_type'] = 'Утро/после полудня';
$lang['requests_export_thead_duration'] = 'Продолжительность';
$lang['requests_export_thead_type'] = 'Тип';
$lang['requests_export_thead_cause'] = 'Причина';
$lang['requests_export_thead_status'] = 'Состояние';

$lang['requests_delegations_title'] = 'Список передачи обязанностей';
$lang['requests_delegations_description'] = 'Это список работников, которые могут утверждать или отклонять заявления от вашего имени.';
$lang['requests_delegations_thead_employee'] = 'Сотрудник';
$lang['requests_delegations_thead_tip_delete'] = 'Отозвать полномочия';
$lang['requests_delegations_button_add'] = 'Добавить';
$lang['requests_delegations_popup_delegate_title'] = 'Добавить уполномоченное лицо';
$lang['requests_delegations_popup_delegate_button_ok'] = 'Ок';
$lang['requests_delegations_popup_delegate_button_cancel'] = 'Отмена';
$lang['requests_delegations_confirm_delete_message'] = 'Вы уверены, что хотите отозвать данное полномочие?';
$lang['requests_delegations_confirm_delete_cancel'] = 'Отмена';
$lang['requests_delegations_confirm_delete_yes'] = 'Да';

$lang['requests_balance_title'] = 'Leave balance (subordinates)';
$lang['requests_balance_description'] = 'Leave balance of my direct report subordinates. If you are not a manager, this list will always be empty.';
$lang['requests_balance_date_field'] = 'Date of report';
