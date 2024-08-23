<?php
/**
 * This helper contains a list of functions used throughout the application
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.2.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * Check if user is connected, redirect to login form otherwise
 * Set the user context by retrieving infos from session
 * @param CI_Controller $controller reference to CI Controller object
 * @author Benjamin BALET <benjamin.balet@gmail.com>
 */
function setUserContext(CI_Controller $controller) {
    //Memorize the last displayed page except for Ajax queries
    if (filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') !== 'XMLHttpRequest') {
        $controller->session->set_userdata('last_page', current_url());
        $controller->session->set_userdata('last_page_params', $_SERVER['QUERY_STRING']);
    }
    if (!$controller->session->userdata('logged_in')) {
        //Test if the expired session was detected while responding to an Ajax request
        if (filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest') {
            $controller->output->set_status_header('401');
        } else {
            redirect('session/login');
        }
    }
    $controller->fullname = $controller->session->userdata('firstname') . ' ' .
            $controller->session->userdata('lastname');
    $controller->is_manager = $controller->session->userdata('is_manager');
    $controller->is_admin = $controller->session->userdata('is_admin');
    $controller->is_hr = $controller->session->userdata('is_hr');
    $controller->user_id = $controller->session->userdata('id');
    $controller->manager = $controller->session->userdata('manager');
    $controller->language = $controller->session->userdata('language');
    $controller->language_code = $controller->session->userdata('language_code');
}

/**
 * Prepare an array containing information about the current user
 * @param CI_Controller $controller reference to CI Controller object
 * @return array data to be passed to the view
 * @author Benjamin BALET <benjamin.balet@gmail.com>
 */
function getUserContext(CI_Controller $controller)
{
    $data['fullname'] = $controller->fullname;
    $data['is_manager'] = $controller->is_manager;
    $data['is_admin'] = $controller->is_admin;
    $data['is_hr'] = $controller->is_hr;
    $data['user_id'] =  $controller->user_id;
    $data['language'] = $controller->session->userdata('language');
    $data['language_code'] =  $controller->session->userdata('language_code');
    if ($controller->is_manager === TRUE) {
        $controller->load->model('leaves_model');
        $data['requested_leaves_count'] = $controller->leaves_model->countLeavesRequestedToManager($controller->user_id);
        if ($controller->config->item('disable_overtime') == FALSE) {
            $controller->load->model('overtime_model');
            $data['requested_extra_count'] = $controller->overtime_model->countExtraRequestedToManager($controller->user_id);
        } else {
            $data['requested_extra_count'] = 0;
        }
        if ($controller->config->item('disable_telework') == FALSE) {
            $controller->load->model('teleworks_model');
            $data['requested_teleworks_count'] = $controller->teleworks_model->countTeleworksRequestedToManager($controller->user_id);
            $data['requested_campaign_teleworks_count'] = $controller->teleworks_model->countCampaignTeleworksRequestedToManager($controller->user_id);
        } else {
            $data['requested_teleworks_count'] = 0;
            $data['requested_campaign_teleworks_count'] = 0;
        }
        $data['requests_count'] = $data['requested_leaves_count'] + $data['requested_extra_count'] + $data['requested_teleworks_count'] + $data['requested_campaign_teleworks_count'];
    }
    return $data;
}

/**
 * Check if user is connected, redirect to login form otherwise
 * Set the user context by retrieving infos from session
 * and load these data into the array passed to the view
 * @see setUserContext and getUserContext
 * @author Benjamin BALET <benjamin.balet@gmail.com>
 */
function getCIUserContext() {
    $controller = & get_instance();
    setUserContext($controller);
    return getUserContext($controller);
}

/**
 * Sanitizes an input (GET/POST) coming from outside a form (eg Ajax request)
 * @param string $value value to be cleansed from characters that prevent Jorani to work
 * @return string value where problematic characters have been removed
 * @author Benjamin BALET <benjamin.balet@gmail.com>
 */
function sanitize($value)
{
    $value = trim($value);
    $value = str_replace('\\','',$value);
    $value = strtr($value,array_flip(get_html_translation_table(HTML_ENTITIES)));
    $value = strip_tags($value);
    $value = htmlspecialchars($value);
    return $value;
}

/**
 * Wrapper between the controller and the e-mail library
 * @param CI_Controller $controller reference to CI Controller object
 * @param string $subject Subject of the e-mail
 * @param string $message Message of the e-mail
 * @param string $to Recipient of the e-mail
 * @param string $cc (optional) Copied to recipients
 * @author Benjamin BALET <benjamin.balet@gmail.com>
 */
function sendMailByWrapper(CI_Controller $controller, $subject, $message, $to, $cc = NULL)
{
    $controller->load->library('email');
    if ($controller->config->item('subject_prefix') !== NULL) {
        $controller->email->subject($controller->config->item('subject_prefix') . ' ' . $subject);
    } else {
       $controller->email->subject('[Jorani] ' . $subject);
    }
    $controller->email->set_encoding('quoted-printable');
    if (($controller->config->item('from_mail') !== NULL) && ($controller->config->item('from_name') !== NULL)) {
        $controller->email->from($controller->config->item('from_mail'), $controller->config->item('from_name'));
    } else {
       $controller->email->from('do.not@reply.me', 'LMS');
    }
    $controller->email->to($to);
    if (!is_null($cc)) {
        $controller->email->cc($cc);
    }
    $controller->email->message($message);
    $controller->email->send();
}

/**
 * Finalize the export to a spreadsheet. Called from an export view.
 * @param $spreadsheet reference to the spreadsheet to be exported
 * @param $exportName Excel filename
 * @author Benjamin BALET <benjamin.balet@gmail.com>
 */
function writeSpreadsheet(&$spreadsheet, $exportName='')
{
    $CI =& get_instance();
    $format = 'xlsx';
    $objWriter = NULL;
    if (in_array($CI->config->item('spreadsheet_format'), array('ods', 'xlsx'))) {
        $format = $CI->config->item('spreadsheet_format');
    }
    $filename = $exportName . '.' . $format;
    $CI->output->set_header('Cache-Control: max-age=0');
    $CI->output->set_header('Content-Disposition: attachment;filename="' . $filename . '"');
    switch ($format) {
        case 'ods':
            $CI->output->set_header('Content-Type: application/vnd.oasis.opendocument.spreadsheet');
            $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Ods');
            break;
        case 'xlsx':
            $CI->output->set_header('Content-Type: application/vnd.ms-excel');
            $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            break;
    }
    $objWriter->setIncludeCharts(true);
    $objWriter->save('php://output');
}

/**
 * Return the excel column name for a given column index
 * This code example:
 * <code>
 * echo $excel->column_name(6);
 * </code>
 * would return F
 * @param int $number Column index
 * @return string Excel representation of the column index
 * @author Benjamin BALET <benjamin.balet@gmail.com>
 */
function columnName($number) {
    if ($number < 27) {
        return substr("ABCDEFGHIJKLMNOPQRSTUVWXYZ", $number - 1, 1);
    } else {
        return substr("AAABACADAEAFAGAHAIAJAKALAMANAOAPAQARASATAUAVAWAXAYAZ", (($number -27) * 2), 2);
    }
}

//Function cal_days_in_month might not exist with HHVM and FreeBSD without proper config
if (!function_exists('cal_days_in_month'))
{
    /**
     * Alternative implementation of cal_days_in_month function
     * @param int $calendar calendar number
     * @param int $month month number
     * @param int $year year number
     * @return int number of days in the month or 0 if error
     */
    function cal_days_in_month($calendar, $month, $year) {
        if (checkdate($month, 31, $year))
            return 31;
        if (checkdate($month, 30, $year))
            return 30;
        if (checkdate($month, 29, $year))
            return 29;
        if (checkdate($month, 28, $year))
            return 28;
        return 0; // error
    }
}

if (! function_exists('campaign_dates')) {

    /**
     *
     * @param int $campaign campaign number
     * @return campaign dates
     */
    function campaign_dates($campaign)
    {
        $year = date('Y');
        $monthnumber = date('n');

        if (($monthnumber > 2 && $monthnumber < 9 && $campaign == 1) || ($monthnumber <= 2 && $campaign == 2)) {
            $startdate = $year . '-03-01';
            $enddate = $year . '-08-31';
        }

        if (($monthnumber > 2 && $monthnumber < 9 && $campaign == 2) || ($monthnumber >= 9 && $campaign == 1)) {
            $startdate = $year . '-09-01';
            $enddate = date('Y-m-t', strtotime(($year + 1) . '-02-01'));
        }

        if ($monthnumber <= 2 && $campaign == 1) {
            $startdate = ($year - 1) . '-09-01';
            $enddate = date('Y-m-t', strtotime($year . '-02-01'));
        }

        if ($monthnumber >= 9 && $campaign == 2) {
            $startdate = ($year + 1) . '-03-01';
            $enddate = ($year + 1) . '-08-31';
        }

        return array(
            'start' => $startdate,
            'end' => $enddate
        );
    }
}

if (! function_exists('list_days_for_campaign')) {

    /**
     *
     * @param int $campaign campaign number
     * @param int $day day number
     * @return list of campaign dates
     */
//     function list_days_for_campaign($campaign, $day)
//     {
//         $campaignDates = campaign_dates($campaign);

//         $startdate = (new DateTime($campaignDates['start']))->modify('first ' . $day);
//         $enddate = new DateTime($campaignDates['end']);

//         $days = array();
//         while ($startdate->format('Y-m-d') <= $enddate->format('Y-m-d')) {
//             $days[] = $startdate->format('Y-m-d');
//             $startdate->add(new \DateInterval('P1W'));
//         }

//         return $days;
//     }

    /**
     *
     * @param date $start start date of a campaign
     * @param date $end end date of a campaign
     * @param string $dayname name of the day
     * @return list of campaign dates
     */
    function list_days_for_campaign($start, $end, $dayname)
    {      
        $start = new DateTime($start);
        if ($start->format('l') == $dayname)
            $days = array(
                $start->format('Y-m-d')
            );
        else
            $days = array();
        $startdate = $start->modify('first ' . $dayname);
        $enddate = new DateTime($end);

        while ($startdate->format('Y-m-d') <= $enddate->format('Y-m-d')) {
            $days[] = $startdate->format('Y-m-d');
            $startdate->add(new \DateInterval('P1W'));
        }

        return $days;
    }
}

if (! function_exists('get_week_dates')) {
    
    /**
     *
     * @param date $date           
     * @param int $daynumber number of the day (0 = Sunday, 1 = Monday, etc...)
     * @return date of the day
     */   
    function get_week_dates($date, $daynumber)
    {
        $date = new DateTime($date);
        $week = $date->format('W');
        $year = $date->format('Y');
        
        $timestamp = mktime( 0, 0, 0, 1, 1,  $year ) + ( $week * 7 * 24 * 60 * 60 );
        $timestamp_for_day = $timestamp - 86400 * ( date( 'N', $timestamp ) - $daynumber );
        
        return date( 'Y-m-d', $timestamp_for_day );
    }
}

if (! function_exists('get_week_dates_by_week')) {
    
    /**
     *
     * @param int $week week number
     * @param int $year year
     * @return date of monday to friday
     */
    function get_week_dates_by_week($week, $year)
    {
        $date = new DateTime();
        $result = array();
        $date->setISODate($year, $week);
        $result['monday'] = $date->format('Y-m-d');
        $result['tuesday'] = ($date->modify('+1 days'))->format('Y-m-d');
        $result['wednesday'] = ($date->modify('+1 days'))->format('Y-m-d');
        $result['thursday'] = ($date->modify('+1 days'))->format('Y-m-d');
        $result['friday'] = ($date->modify('+1 days'))->format('Y-m-d');

        return $result;
    }
}

if (!defined('CAL_GREGORIAN'))
    define('CAL_GREGORIAN', 1);
