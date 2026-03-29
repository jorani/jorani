<?php
/**
 * This helper contains a list of functions used throughout the application
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/jorani/jorani
 * @since   0.2.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Check if user is connected, redirect to login form otherwise
 * Set the user context by retrieving infos from session
 * @param CI_Controller $controller reference to CI Controller object
 */
function setUserContext(CI_Controller $controller): void
{
    // Fix for CLI mode
    if (PHP_SAPI === 'cli') {
        $queryString = '';
    } else {
        $queryString = $_SERVER['QUERY_STRING'] ?? '';
    }

    //Memorize the last displayed page except for Ajax queries
    if (filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') !== 'XMLHttpRequest') {
        $currentUrl = current_url();
        // Do not overwrite last_page with URLs pointing to static assets (vendor, assets, etc.)
        // This prevents the DebugBar or other vendor assets from corrupting the post-login redirect.
        $isStaticAsset = (
            strpos($currentUrl, '/vendor/') !== false ||
            strpos($currentUrl, '/assets/') !== false ||
            strpos($currentUrl, '.json') !== false ||
            strpos($currentUrl, '.js') !== false
        );
        if (!$isStaticAsset) {
            $controller->session->set_userdata('last_page', current_url());
            $controller->session->set_userdata('last_page_params', $queryString);
        }
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
 */
function getUserContext(CI_Controller $controller): array
{
    $data['fullname'] = $controller->fullname;
    $data['is_manager'] = $controller->is_manager;
    $data['is_admin'] = $controller->is_admin;
    $data['is_hr'] = $controller->is_hr;
    $data['user_id'] = $controller->user_id;
    $data['language'] = $controller->session->userdata('language');
    $data['language_code'] = $controller->session->userdata('language_code');
    if ($controller->is_manager === TRUE) {
        $controller->load->model('leaves_model');
        $data['requested_leaves_count'] = $controller->leaves_model->countLeavesRequestedToManager($controller->user_id);
        if ($controller->config->item('disable_overtime') == FALSE) {
            $controller->load->model('overtime_model');
            $data['requested_extra_count'] = $controller->overtime_model->countExtraRequestedToManager($controller->user_id);
        } else {
            $data['requested_extra_count'] = 0;
        }
        $data['requests_count'] = $data['requested_leaves_count'] + $data['requested_extra_count'];
    }
    return $data;
}

/**
 * Check if user is connected, redirect to login form otherwise
 * Set the user context by retrieving infos from session
 * and load these data into the array passed to the view
 * @see self::getUserContext()
 */
function getCIUserContext(): array
{
    $controller = &get_instance();
    setUserContext($controller);
    return getUserContext($controller);
}

/**
 * Sanitizes an input (GET/POST) coming from outside a form (eg Ajax request)
 * @param string $value value to be cleansed from characters that prevent Jorani to work
 * @return string value where problematic characters have been removed
 */
function sanitize(string $value): string
{
    $value = trim($value);
    $value = str_replace('\\', '', $value);
    $value = strtr($value, array_flip(get_html_translation_table(HTML_ENTITIES)));
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
 * @param string|null $cc (optional) Copied to recipients
 */
function sendMailByWrapper(CI_Controller $controller, string $subject, string $message, string $to, ?string $cc = NULL): void
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
 * @param Spreadsheet $spreadsheet reference to the spreadsheet to be exported
 * @param string $exportName Excel filename
 */
function writeSpreadsheet(Spreadsheet $spreadsheet, string $exportName = ''): void
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
            $objWriter = IOFactory::createWriter($spreadsheet, 'Ods');
            break;
        case 'xlsx':
            $CI->output->set_header('Content-Type: application/vnd.ms-excel');
            $objWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
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
 */
function columnName(int $number): string
{
    if ($number < 27) {
        return substr("ABCDEFGHIJKLMNOPQRSTUVWXYZ", $number - 1, 1);
    } else {
        return substr("AAABACADAEAFAGAHAIAJAKALAMANAOAPAQARASATAUAVAWAXAYAZ", (($number - 27) * 2), 2);
    }
}

//Function cal_days_in_month might not exist with HHVM and FreeBSD without proper config
if (!function_exists('cal_days_in_month')) {
    /**
     * Alternative implementation of cal_days_in_month function
     * @param int $calendar calendar number
     * @param int $month month number
     * @param int $year year number
     * @return int number of days in the month or 0 if error
     */
    function cal_days_in_month(int $calendar, int $month, int $year): int
    {
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

if (!defined('CAL_GREGORIAN'))
    define('CAL_GREGORIAN', 1);
