<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license     http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link          https://github.com/bbalet/jorani
 * @since       0.4.7
 * @author      Ceibga Bao <info@sansin.com.tw>
 */

$lang['contract_index_title'] = '類別名單';
$lang['contract_index_thead_id'] = '證號';
$lang['contract_index_thead_name'] = '名字';
$lang['contract_index_thead_start'] = '開始期間';
$lang['contract_index_thead_end'] = '結束期間';
$lang['contract_index_tip_delete'] = '刪除類別';
$lang['contract_index_tip_edit'] = '編輯類別';
$lang['contract_index_tip_entitled'] = '享有天數';
$lang['contract_index_tip_dayoffs'] = '休假與週末';
$lang['contract_index_tip_exclude_types'] = 'Exclude leave types';
$lang['contract_index_button_export'] = '匯出此單';
$lang['contract_index_button_create'] = '創造類別';
$lang['contract_index_popup_delete_title'] = '刪除類別';
$lang['contract_index_popup_delete_description'] = '你可以刪除一個類別,但無法再做復原';
$lang['contract_index_popup_delete_confirm'] = '你要繼續嗎？';
$lang['contract_index_popup_delete_button_yes'] = '是';
$lang['contract_index_popup_delete_button_no'] = '否';
$lang['contract_index_popup_entitled_title'] = '享有類別';
$lang['contract_index_popup_entitled_button_cancel'] = '取消';
$lang['contract_index_popup_entitled_button_close'] = '關閉';

$lang['contract_exclude_title'] = 'Exclude leave types from a contract';
$lang['contract_exclude_description'] = 'You cannot exclude leave types already in use (used at least one time by en employee attached to the contract) and the default leave type (set on the contract or into the configuration file).';
$lang['contract_exclude_title_included'] = 'Included leave types';
$lang['contract_exclude_title_excluded'] = 'Excluded leave types';
$lang['contract_exclude_tip_include_type'] = 'Include this leave type';
$lang['contract_exclude_tip_exclude_type'] = 'Exclude this leave type';
$lang['contract_exclude_tip_already_used'] = 'This leave type is already in use';
$lang['contract_exclude_tip_default_type'] = 'You cannot exclude the default leave type';

$lang['contract_edit_title'] = '編輯的類別';
$lang['contract_edit_description'] = '編輯類別';
$lang['contract_edit_field_name'] = '名字';
$lang['contract_edit_field_start_month'] = '月/開始';
$lang['contract_edit_field_start_day'] = '天/開始';
$lang['contract_edit_field_end_month'] = '月/結束';
$lang['contract_edit_field_end_day'] = '天/結束';
$lang['contract_edit_default_leave_type'] = 'Default leave type';
$lang['contract_edit_button_update'] = '更新類別';
$lang['contract_edit_button_cancel'] = '取消';

$lang['contract_edit_msg_success'] = '此類別已更新成功';

$lang['contract_create_title'] = '創立新類別';
$lang['contract_create_field_name'] = '名字';
$lang['contract_create_field_start_month'] = '月/開始';
$lang['contract_create_field_start_day'] = '天/開始';
$lang['contract_create_field_end_month'] = '月/結束';
$lang['contract_create_field_end_day'] = '天/結束';
$lang['contract_create_default_leave_type'] = 'Default leave type';
$lang['contract_create_button_create'] = '創造類別';
$lang['contract_create_button_cancel'] = '取消';

$lang['contract_create_msg_success'] = '類別已成功創立';
$lang['contract_delete_msg_success'] = '類別已成功刪除';

$lang['contract_export_title'] = '類別名單';
$lang['contract_export_thead_id'] = '證號';
$lang['contract_export_thead_name'] = '名字';
$lang['contract_export_thead_start'] = '開始期間';
$lang['contract_export_thead_end'] = '結束期間';

$lang['contract_calendar_title'] = '非工作天行事曆';
$lang['contract_calendar_description'] = '休假日及週末非刻意安排,選取日期或按鈕"Series"做個別編輯';
$lang['contract_calendar_legend_title'] = '圖例';
$lang['contract_calendar_legend_allday'] = '全天';
$lang['contract_calendar_legend_morning'] = '早上';
$lang['contract_calendar_legend_afternoon'] = '下午';
$lang['contract_calendar_button_back'] = '返回類別';
$lang['contract_calendar_button_series'] = '非工作天時段';
$lang['contract_calendar_popup_dayoff_title'] = '編輯休假日';
$lang['contract_calendar_popup_dayoff_field_title'] = '抬頭';
$lang['contract_calendar_popup_dayoff_field_type'] = '編輯';
$lang['contract_calendar_popup_dayoff_type_working'] = '工作日';
$lang['contract_calendar_popup_dayoff_type_off'] = '全日休假';
$lang['contract_calendar_popup_dayoff_type_morning'] = '上午休假';
$lang['contract_calendar_popup_dayoff_type_afternoon'] = '下午休假';
$lang['contract_calendar_popup_dayoff_button_delete'] = '刪除';
$lang['contract_calendar_popup_dayoff_button_ok'] = '好';
$lang['contract_calendar_popup_dayoff_button_cancel'] = '取消';
$lang['contract_calendar_button_import'] = '匯入 ical';
$lang['contract_calendar_prompt_import'] = 'URL 非工作天於 ICal 資料夾';
$lang['contract_calendar_popup_series_title'] = '編輯休假時段';
$lang['contract_calendar_popup_series_field_occurences'] = '標註每一項';
$lang['contract_calendar_popup_series_field_from'] = '自';
$lang['contract_calendar_popup_series_button_current'] = '現在';
$lang['contract_calendar_popup_series_field_to'] = '至';
$lang['contract_calendar_popup_series_field_as'] = '如到..';
$lang['contract_calendar_popup_series_field_as_working'] = '工作日';
$lang['contract_calendar_popup_series_field_as_off'] = '全日休假';
$lang['contract_calendar_popup_series_field_as_morning'] = '上午休假';
$lang['contract_calendar_popup_series_field_as_afternnon'] = '下午休假';
$lang['contract_calendar_popup_series_field_title'] = '抬頭';
$lang['contract_calendar_popup_series_button_ok'] = '好';
$lang['contract_calendar_popup_series_button_cancel'] = '取消';
$lang['contract_calendar_button_copy'] = '複製';
$lang['contract_calendar_copy_destination_js_msg'] = '你必須選擇類別';
$lang['contract_calendar_copy_msg_success'] = '資料已複製成功';
