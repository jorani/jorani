<?php
/**
 * Translation file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license     http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link          https://github.com/bbalet/jorani
 * @since       0.4.6
 * @author      Ela Alptekin
 */

$lang['contract_index_title'] = 'Sözleşmelerin listesi';
$lang['contract_index_thead_id'] = 'ID';
$lang['contract_index_thead_name'] = 'İsim';
$lang['contract_index_thead_start'] = 'Başlangıç dönemi';
$lang['contract_index_thead_end'] = 'Bitiş dönemi';
$lang['contract_index_tip_delete'] = 'sözleşmeyi sil';
$lang['contract_index_tip_edit'] = 'sözleşmeyi düzenle';
$lang['contract_index_tip_entitled'] = 'hak edilen günler';
$lang['contract_index_tip_dayoffs'] = 'izin günleri ve hafta sonları';
$lang['contract_index_tip_exclude_types'] = 'Exclude leave types';
$lang['contract_index_button_export'] = 'Bu listeyi dışa aktar';
$lang['contract_index_button_create'] = 'Sözleşmeyi oluştur';
$lang['contract_index_popup_delete_title'] = 'Sözleşmeyi Sil';
$lang['contract_index_popup_delete_description'] = 'Bir sözleşmeyi silmek üzeresiniz, bu işlem geri döndürülemez.';
$lang['contract_index_popup_delete_confirm'] = 'Devam etmek istiyor musunuz?';
$lang['contract_index_popup_delete_button_yes'] = 'Evet';
$lang['contract_index_popup_delete_button_no'] = 'Hayır';
$lang['contract_index_popup_entitled_title'] = 'Hak edilen günler';
$lang['contract_index_popup_entitled_button_cancel'] = 'İptal';
$lang['contract_index_popup_entitled_button_close'] = 'Kapat';

$lang['contract_exclude_title'] = 'Exclude leave types from a contract';
$lang['contract_exclude_description'] = 'You cannot exclude leave types already in use (used at least one time by en employee attached to the contract) and the default leave type (set on the contract or into the configuration file).';
$lang['contract_exclude_title_included'] = 'Included leave types';
$lang['contract_exclude_title_excluded'] = 'Excluded leave types';
$lang['contract_exclude_tip_include_type'] = 'Include this leave type';
$lang['contract_exclude_tip_exclude_type'] = 'Exclude this leave type';
$lang['contract_exclude_tip_already_used'] = 'This leave type is already in use';
$lang['contract_exclude_tip_default_type'] = 'You cannot exclude the default leave type';

$lang['contract_edit_title'] = 'Bir sözleşme düzenle';
$lang['contract_edit_description'] = 'Sözleşmeyi düzenle #';
$lang['contract_edit_field_name'] = 'İsim';
$lang['contract_edit_field_start_month'] = 'Ay / Başlangıç';
$lang['contract_edit_field_start_day'] = 'Gün / Başlangıç';
$lang['contract_edit_field_end_month'] = 'Ay / Bitiş';
$lang['contract_edit_field_end_day'] = 'Gün / Bitiş';
$lang['contract_edit_default_leave_type'] = 'Default leave type';
$lang['contract_edit_button_update'] = 'Sözleşmeyi güncelle';
$lang['contract_edit_button_cancel'] = 'İptal';
$lang['contract_edit_msg_success'] = 'Sözleşme başarıyla güncellendi';

$lang['contract_create_title'] = 'Yeni bir sözleşme oluştur';
$lang['contract_create_field_name'] = 'İsim';
$lang['contract_create_field_start_month'] = 'Ay / Başlangıç';
$lang['contract_create_field_start_day'] = 'Gün / Başlangıç';
$lang['contract_create_field_end_month'] = 'Ay / Bitiş';
$lang['contract_create_field_end_day'] = 'Gün / Bitiş';
$lang['contract_create_default_leave_type'] = 'Default leave type';
$lang['contract_create_button_create'] = 'Sözleşmeyi oluştur';
$lang['contract_create_button_cancel'] = 'İptal';
$lang['contract_create_msg_success'] = 'Sözleşme başarıyla oluşturuldu';

$lang['contract_delete_msg_success'] = 'Sözleşme başarıyla silindi';

$lang['contract_export_title'] = 'Sözleşmelerin listesi';
$lang['contract_export_thead_id'] = 'ID';
$lang['contract_export_thead_name'] = 'İsim';
$lang['contract_export_thead_start'] = 'Başlangıç dönemi';
$lang['contract_export_thead_end'] = 'Bitiş dönemi';

$lang['contract_calendar_title'] = 'Çalışmadığım günlerin takvimi';
$lang['contract_calendar_description'] = 'İzin günleri ve hafta sonları varsayılan olarak yapılandırılmamıştır. Tek tek düzenlemek için bir günde tıklayın veya \"Seriler\" düğmesini kullanın.';
$lang['contract_calendar_legend_title'] = 'Efsane:';
$lang['contract_calendar_legend_allday'] = 'Tüm gün';
$lang['contract_calendar_legend_morning'] = 'Sabah';
$lang['contract_calendar_legend_afternoon'] = 'Öğleden sonra';
$lang['contract_calendar_button_back'] = 'Sözleşmelere geri dön';
$lang['contract_calendar_button_series'] = 'Çalışılmayan gün serileri';
$lang['contract_calendar_popup_dayoff_title'] = 'İzin gününü düzenle';
$lang['contract_calendar_popup_dayoff_field_title'] = 'Başlık';
$lang['contract_calendar_popup_dayoff_field_type'] = 'Tür';
$lang['contract_calendar_popup_dayoff_type_working'] = 'Çalışma günü';
$lang['contract_calendar_popup_dayoff_type_off'] = 'Tüm gün izinli';
$lang['contract_calendar_popup_dayoff_type_morning'] = 'Sabah izinli';
$lang['contract_calendar_popup_dayoff_type_afternoon'] = 'Öğleden sonra izinli';
$lang['contract_calendar_popup_dayoff_button_delete'] = 'Sil';
$lang['contract_calendar_popup_dayoff_button_ok'] = 'Tamam';
$lang['contract_calendar_popup_dayoff_button_cancel'] = 'İptal';
$lang['contract_calendar_button_import'] = 'iCal\'ı içe aktar';
$lang['contract_calendar_prompt_import'] = 'Çalışılmayan günlerin iCal dosyasının bağlantı adresi';
$lang['contract_calendar_popup_series_title'] = 'Bir seri izin günü düzenle';
$lang['contract_calendar_popup_series_field_occurences'] = 'Hepsini işaretle';
$lang['contract_calendar_popup_series_field_from'] = 'Kimden:';
$lang['contract_calendar_popup_series_button_current'] = 'Güncel';
$lang['contract_calendar_popup_series_field_to'] = 'Kime:';
$lang['contract_calendar_popup_series_field_as'] = 'Olarak';
$lang['contract_calendar_popup_series_field_as_working'] = 'Çalışma günü';
$lang['contract_calendar_popup_series_field_as_off'] = 'Tüm gün izinli';
$lang['contract_calendar_popup_series_field_as_morning'] = 'Sabah izinli';
$lang['contract_calendar_popup_series_field_as_afternnon'] = 'Öğleden sonra izinli';
$lang['contract_calendar_popup_series_field_title'] = 'Başlık';
$lang['contract_calendar_popup_series_button_ok'] = 'Tamam';
$lang['contract_calendar_popup_series_button_cancel'] = 'İptal';
$lang['contract_calendar_button_copy'] = 'Kopyala';
$lang['contract_calendar_copy_destination_js_msg'] = 'Bir sözleşme seçmelisiniz.';
$lang['contract_calendar_copy_msg_success'] = 'Veri başarıyla kopyalandı.';
