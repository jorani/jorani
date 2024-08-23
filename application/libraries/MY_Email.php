<?php defined('BASEPATH') OR exit('No direct script access allowed.');

/**
 * CodeIgniter compatible email-library powered by PHPMailer.
 *
 * @author Ivan Tcholakov <ivantcholakov@gmail.com>, 2012-2019.
 * @license The MIT License (MIT), http://opensource.org/licenses/MIT
 * @link https://github.com/ivantcholakov/codeigniter-phpmailer
 *
 * This class is intended to be compatible with CI 3.1.x and PHPMailer 6.0.x.
 */

class MY_Email extends CI_Email {

    public $phpmailer;  // This property has been made public for testing purposes.

    protected static $default_properties = array(
        'useragent' => 'CodeIgniter',
        'mailpath' => '/usr/sbin/sendmail',
        'protocol' => 'mail',
        'smtp_host' => '',
        'smtp_auth' => NULL,
        'smtp_user' => '',
        'smtp_pass' => '',
        'smtp_port' => 25,
        'smtp_timeout' => 5,
        'smtp_keepalive' => FALSE,
        'smtp_crypto' => '',
        'wordwrap' => TRUE,
        'wrapchars' => 76,
        'mailtype' => 'text',
        'charset' => 'UTF-8',
        'multipart' => 'mixed',
        'alt_message' => '',
        'validate' => FALSE,
        'priority' => 3,
        'newline' => "\n",
        'crlf' => "\n",
        'dsn' => FALSE,
        'send_multipart' => TRUE,
        'bcc_batch_mode' => FALSE,
        'bcc_batch_size' => 200,
        'debug_output' => '',
        'smtp_debug' => 0,
        'encoding' => '8bit',
        'smtp_auto_tls' => true,
        'smtp_conn_options' => array(),
        'dkim_domain' => '',
        'dkim_private' => '',
        'dkim_private_string' => '',
        'dkim_selector' => '',
        'dkim_passphrase' => '',
        'dkim_identity' => '',
    );

    protected $properties = array();

    protected $mailer_engine = 'codeigniter';
    protected $CI;

    protected static $protocols = array('mail', 'sendmail', 'smtp');
    protected static $mailtypes = array('html', 'text');
    protected static $encodings_ci = array('8bit', '7bit');
    protected static $encodings_phpmailer = array('8bit', '7bit', 'binary', 'base64', 'quoted-printable');


    // The Constructor ---------------------------------------------------------

    public function __construct(array $config = array()) {

        $this->CI = get_instance();
        $this->CI->load->helper('email');
        $this->CI->load->helper('html');

        // Set the default property 'debug_output' by using CLI autodetection.
        self::$default_properties['debug_output'] = (strpos(PHP_SAPI, 'cli') !== false OR defined('STDIN')) ? 'echo' : 'html';

        // Wipe out certain properties that are declared within the parent class.
        // These properties would be accessed by magic.
        foreach (array_keys(self::$default_properties) as $name) {

            if (property_exists($this, $name)) {
                unset($this->{$name});
            }
        }

        $this->properties = self::$default_properties;
        $this->refresh_properties();

        $this->_safe_mode = (!is_php('5.4') && ini_get('safe_mode'));

        if (!isset($config['charset'])) {
            $config['charset'] = config_item('charset');
        }

        $this->initialize($config);

        log_message('info', 'Email Class Initialized (Engine: '.$this->mailer_engine.')');
    }

    // Triggers the setter functions to do their job.
    protected function refresh_properties() {

        foreach (array_keys(self::$default_properties) as $name) {
            $this->{$name} = $this->{$name};
        }
    }


    // The Destructor ----------------------------------------------------------

    public function __destruct() {

        if (is_callable('parent', '__destruct')) {
            parent::__destruct();
        }
    }


    // Magic -------------------------------------------------------------------

    function __set($name, $value) {

        $method = 'set_'.$name;

        if (is_callable(array($this, $method))) {
            $this->$method($value);
        } else {
            $this->properties[$name] = $value;
        }
    }

    function __get($name) {

        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        } else {
            throw new \OutOfBoundsException('The property '.$name.' does not exists.');
        }
    }

    public function __isset($name) {

        return isset($this->properties[$name]);
    }

    public function __unset($name) {

        $this->$name = null;

        if (array_key_exists($name, $this->properties)) {
            unset($this->properties[$name]);
        } else {
            unset($this->$name);
        }
    }


    // Keep the API Fluent -----------------------------------------------------

    /**
     * An empty method that keeps chaining, the parameter does the desired operation as a side-effect.
     *
     * @param   mixed   $expression     A (conditional) expression that is to be executed.
     * @return  object                  Returns a reference to the created library instance.
     */
    public function that($expression = NULL) {

        return $this;
    }


    // Initialization & Clearing -----------------------------------------------

    public function initialize(array $config = array()) {

        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }

        $this->clear();

        return $this;
    }

    public function clear($clear_attachments = false) {

        $clear_attachments = !empty($clear_attachments);

        parent::clear($clear_attachments);

        if ($this->mailer_engine == 'phpmailer') {

            $this->phpmailer->clearAllRecipients();
            $this->phpmailer->clearReplyTos();
            if ($clear_attachments) {
                $this->phpmailer->clearAttachments();
            }

            $this->phpmailer->clearCustomHeaders();

            $this->phpmailer->Subject = '';
            $this->phpmailer->Body = '';
            $this->phpmailer->AltBody = '';
        }

        return $this;
    }


    // Prepare & Send a Message ------------------------------------------------

    public function from($from, $name = '', $return_path = NULL) {

        $from = (string) $from;
        $name = (string) $name;
        $return_path = (string) $return_path;

        if ($this->mailer_engine == 'phpmailer') {

            if (preg_match( '/\<(.*)\>/', $from, $match)) {
                $from = $match['1'];
            }

            if ($this->validate) {

                $this->validate_email($this->_str_to_array($from));

                if ($return_path) {
                    $this->validate_email($this->_str_to_array($return_path));
                }
            }

            $this->phpmailer->setFrom($from, $name, 0);

            if (!$return_path) {
                $return_path = $from;
            }

            $this->phpmailer->Sender = $return_path;

        } else {

            parent::from($from, $name, $return_path);
        }

        return $this;
    }

    public function reply_to($replyto, $name = '') {

        $replyto = (string) $replyto;
        $name = (string) $name;

        if ($this->mailer_engine == 'phpmailer') {

            if (preg_match( '/\<(.*)\>/', $replyto, $match)) {
                $replyto = $match['1'];
            }

            if ($this->validate) {
                $this->validate_email($this->_str_to_array($replyto));
            }

            if ($name == '') {
                $name = $replyto;
            }

            $this->phpmailer->addReplyTo($replyto, $name);

            $this->_replyto_flag = TRUE;

        } else {

            parent::reply_to($replyto, $name);
        }

        return $this;
    }

    public function to($to) {

        if ($this->mailer_engine == 'phpmailer') {

            $to = $this->_str_to_array($to);
            $names = $this->_extract_name($to);
            $to = $this->clean_email($to);

            if ($this->validate) {
                $this->validate_email($to);
            }

            $i = 0;

            foreach ($to as $address) {

                $this->phpmailer->addAddress($address, $names[$i]);

                $i++;
            }

        } else {

            parent::to($to);
        }

        return $this;
    }

    public function cc($cc) {

        if ($this->mailer_engine == 'phpmailer') {

            $cc = $this->_str_to_array($cc);
            $names = $this->_extract_name($cc);
            $cc = $this->clean_email($cc);

            if ($this->validate) {
                $this->validate_email($cc);
            }

            $i = 0;

            foreach ($cc as $address) {

                $this->phpmailer->addCC($address, $names[$i]);

                $i++;
            }

        } else {

            parent::cc($cc);
        }

        return $this;
    }

    public function bcc($bcc, $limit = '') {

        if ($this->mailer_engine == 'phpmailer') {

            $bcc = $this->_str_to_array($bcc);
            $names = $this->_extract_name($bcc);
            $bcc = $this->clean_email($bcc);

            if ($this->validate) {
                $this->validate_email($bcc);
            }

            $i = 0;

            foreach ($bcc as $address) {

                $this->phpmailer->addBCC($address, $names[$i]);

                $i++;
            }

        } else {

            parent::bcc($bcc, $limit);
        }

        return $this;
    }

    public function subject($subject) {

        $subject = (string) $subject;

        if ($this->mailer_engine == 'phpmailer') {

            // Modified by Ivan Tcholakov, 01-AUG-2015.
            // See https://github.com/ivantcholakov/codeigniter-phpmailer/issues/8
            // This change probably is not needed, done anyway.
            //$this->phpmailer->Subject = $subject;
            $this->phpmailer->Subject = str_replace(array('{unwrap}', '{/unwrap}'), '', $subject);
            //

        } else {

            parent::subject($subject);
        }

        return $this;
    }

    public function message($body) {

        $body = (string) $body;

        if ($this->mailer_engine == 'phpmailer') {

            // Modified by Ivan Tcholakov, 01-AUG-2015.
            // See https://github.com/ivantcholakov/codeigniter-phpmailer/issues/8
            //$this->phpmailer->Body = $body;
            $this->phpmailer->Body = str_replace(array('{unwrap}', '{/unwrap}'), '', $body);
            //
        }

        parent::message($body);

        return $this;
    }

    // Modified by Ivan Tcholakov, 16-JAN-2014.
    //public function attach($file, $disposition = '', $newname = NULL, $mime = '') {
    public function attach($file, $disposition = '', $newname = NULL, $mime = '', $embedded_image = false) {
    //

        $file = (string) $file;

        $disposition = (string) $disposition;

        if ($disposition == '') {
            $disposition ='attachment';
        }

        $newname = (string) $newname;

        if ($newname == '') {
            // For making strict NULL checks happy.
            $newname = NULL;
        }

        $mime = (string) $mime;

        if ($this->mailer_engine == 'phpmailer') {

            if ($mime == '') {

                if (strpos($file, '://') === FALSE && ! file_exists($file)) {

                    $this->_set_error_message('lang:email_attachment_missing', $file);
                    // Modified by Ivan Tcholakov, 14-JAN-2014.
                    //return FALSE;
                    return $this;
                    //
                }

                if (!$fp = @fopen($file, FOPEN_READ)) {

                    $this->_set_error_message('lang:email_attachment_unreadable', $file);
                    // Modified by Ivan Tcholakov, 14-JAN-2014.
                    //return FALSE;
                    return $this;
                    //
                }

                $file_content = stream_get_contents($fp);
                $mime = $this->_mime_types(pathinfo($file, PATHINFO_EXTENSION));
                fclose($fp);

                $this->_attachments[] = array(
                    'name' => array($file, $newname),
                    'disposition' => $disposition,
                    'type' => $mime,
                );

                $newname = $newname === NULL ? basename($file) : $newname;
                $cid = $this->attachment_cid($file);

            } else {

                // A buffered file, in this case make sure that $newname has been set.

                $file_content =& $file;

                $this->_attachments[] = array(
                    'name' => array($newname, $newname),
                    'disposition' => $disposition,
                    'type' => $mime,
                );

                $cid = $this->attachment_cid($newname);
            }

            if (empty($embedded_image)) {
                $this->phpmailer->addStringAttachment($file_content, $newname, 'base64', $mime, $disposition);
            } else {
                $this->phpmailer->addStringEmbeddedImage($file_content, $cid, $newname, 'base64', $mime, $disposition);
            }

        } else {

            parent::attach($file, $disposition, $newname, $mime);
        }

        return $this;
    }

    public function attachment_cid($filename) {

        if ($this->mailer_engine == 'phpmailer') {

            for ($i = 0, $c = count($this->_attachments); $i < $c; $i++) {

                if ($this->_attachments[$i]['name'][0] === $filename) {

                    $this->_attachments[$i]['cid'] = uniqid(basename($this->_attachments[$i]['name'][0]).'@');
                    return $this->_attachments[$i]['cid'];
                }
            }

        } else {

            return parent::attachment_cid($filename);
        }

        return FALSE;
    }

    // Added by Ivan Tcholakov, 16-JAN-2014.
    public function get_attachment_cid($filename) {

        for ($i = 0, $c = count($this->_attachments); $i < $c; $i++) {

            if ($this->_attachments[$i]['name'][0] === $filename) {
                return empty($this->_attachments[$i]['cid']) ? FALSE : $this->_attachments[$i]['cid'];
            }
        }

        return FALSE;
    }

    public function set_header($header, $value) {

        $header = (string) $header;
        $value = (string) $value;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->addCustomHeader($header, str_replace(array("\n", "\r"), '', $value));
        }

        parent::set_header($header, $value);

        return $this;
    }

    public function send($auto_clear = true) {

        $auto_clear = !empty($auto_clear);

        if ($this->mailer_engine == 'phpmailer') {

            if ($this->mailtype == 'html') {

                // Modified by Ivan Tcholakov, 01-AUG-2015.
                // See https://github.com/ivantcholakov/codeigniter-phpmailer/issues/8
                //$this->phpmailer->AltBody = $this->_get_alt_message();
                $this->phpmailer->AltBody = str_replace(array('{unwrap}', '{/unwrap}'), '', $this->_get_alt_message());
                //
            }

            $result = (bool) $this->phpmailer->send();

            if ($result) {

                $this->_set_error_message('lang:email_sent', $this->_get_protocol());

                if ($auto_clear) {
                    $this->clear();
                }

            } else {

                $this->_set_error_message($this->phpmailer->ErrorInfo);
            }

        } else {

            $result = parent::send($auto_clear);
        }

        return $result;
    }


    // Methods for setting configuration options -------------------------------

    public function set_mailer_engine($mailer_engine) {

        $mailer_engine = strpos(strtolower($mailer_engine), 'phpmailer') !== false ? 'phpmailer' : 'codeigniter';

        if ($this->mailer_engine == $mailer_engine) {
            return $this;
        }

        $this->mailer_engine = $mailer_engine;

        if ($mailer_engine == 'phpmailer') {

            if (!is_object($this->phpmailer)) {

                // Try to autoload the PHPMailer if there is already a registered autoloader.
                $phpmailer_class_exists = class_exists('PHPMailer\\PHPMailer\\PHPMailer', true);

                if (!$phpmailer_class_exists) {
                    throw new \Exception('The class PHPMailer\\PHPMailer\\PHPMailer can not be found.');
                }

                $this->phpmailer = new \PHPMailer\PHPMailer\PHPMailer();
                \PHPMailer\PHPMailer\PHPMailer::$validator = 'valid_email';
            }
        }

        $this->refresh_properties();
        $this->clear(true);

        return $this;
    }

    public function set_useragent($useragent) {

        $useragent = (string) $useragent;

        $this->properties['useragent'] = $useragent;

        $this->set_mailer_engine($useragent);

        return $this;
    }

    public function set_mailpath($value) {

        $value = (string) $value;

        $this->properties['mailpath'] = $value;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->Sendmail = $value;
        }

        return $this;
    }

    public function set_protocol($protocol = 'mail') {

        $protocol = in_array($protocol, self::$protocols, TRUE) ? strtolower($protocol) : 'mail';

        $this->properties['protocol'] = $protocol;

        if ($this->mailer_engine == 'phpmailer') {

            switch ($protocol) {

                case 'mail':
                    $this->phpmailer->isMail();
                    break;

                case 'sendmail':
                    $this->phpmailer->isSendmail();
                    break;

                case 'smtp':
                    $this->phpmailer->isSMTP();
                    break;
            }
        }

        return $this;
    }

    public function set_smtp_host($value) {

        $value = (string) $value;

        $this->properties['smtp_host'] = $value;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->Host = $value;
        }

        return $this;
    }

    // See https://github.com/ivantcholakov/codeigniter-phpmailer/issues/31
    public function set_smtp_auth($value) {

        $this->properties['smtp_auth'] = $value;

        $this->_smtp_auth =
            $value === NULL
                ? !($this->smtp_user == '' && $this->smtp_pass == '')
                : !empty($value);

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->SMTPAuth = $this->_smtp_auth;
        }

        return $this;
    }

    public function set_smtp_user($value) {

        $value = (string) $value;

        $this->properties['smtp_user'] = $value;

        $this->_smtp_auth =
            $this->smtp_auth === NULL
                ? !($value == '' && $this->smtp_pass == '')
                : !empty($this->smtp_auth);

        if ($this->mailer_engine == 'phpmailer') {

            $this->phpmailer->Username = $value;
            $this->phpmailer->SMTPAuth = $this->_smtp_auth;
        }

        return $this;
    }

    public function set_smtp_pass($value) {

        $value = (string) $value;

        $this->properties['smtp_pass'] = $value;

        $this->_smtp_auth =
            $this->smtp_auth === NULL
                ? !($this->smtp_user == '' && $value == '')
                : !empty($this->smtp_auth);

        if ($this->mailer_engine == 'phpmailer') {

            $this->phpmailer->Password = $value;
            $this->phpmailer->SMTPAuth = $this->_smtp_auth;
        }

        return $this;
    }

    public function set_smtp_port($value) {

        $value = (int) $value;

        $this->properties['smtp_port'] = $value;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->Port = $value;
        }

        return $this;
    }

    public function set_smtp_timeout($value) {

        $value = (int) $value;

        $this->properties['smtp_timeout'] = $value;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->Timeout = $value;
        }

        return $this;
    }

    public function set_smtp_keepalive($value) {

        $value = !empty($value);

        $this->properties['smtp_keepalive'] = $value;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->SMTPKeepAlive = $value;
        }

        return $this;
    }

    public function set_smtp_crypto($smtp_crypto = '') {

        $smtp_crypto = trim(strtolower($smtp_crypto));

        if ($smtp_crypto != 'tls' && $smtp_crypto != 'ssl') {
            $smtp_crypto = '';
        }

        $this->properties['smtp_crypto'] = $smtp_crypto;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->SMTPSecure = $smtp_crypto;
        }

        return $this;
    }

    public function set_wordwrap($wordwrap = TRUE) {

        $wordwrap = !empty($wordwrap);

        $this->properties['wordwrap'] = $wordwrap;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->WordWrap = $wordwrap ? (int) $this->wrapchars : 0;
        }

        return $this;
    }

    public function set_wrapchars($wrapchars) {

        $wrapchars = (int) $wrapchars;

        $this->properties['wrapchars'] = $wrapchars;

        if ($this->mailer_engine == 'phpmailer') {

            if (!$this->wordwrap) {

                $this->phpmailer->WordWrap = 0;

            } else {

                if (empty($wrapchars)) {
                    $wrapchars = 76;
                }

                $this->phpmailer->WordWrap = (int) $wrapchars;
            }
        }

        return $this;
    }

    public function set_mailtype($type = 'text') {

        $type = trim(strtolower($type));
        $type = in_array($type, self::$mailtypes) ? $type : 'text';

        $this->properties['mailtype'] = $type;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->isHTML($type == 'html');
        }

        return $this;
    }

    public function set_charset($charset) {

        if ($charset == '') {
            $charset = config_item('charset');
        }

        $charset = strtoupper($charset);

        $this->properties['charset'] = $charset;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->CharSet = $charset;
        }

        return $this;
    }

    // Not used by PHPMailer.
    public function set_multipart($value) {

        $this->properties['multipart'] = (string) $value;

        return $this;
    }

    public function set_alt_message($str) {

        $this->properties['alt_message'] = (string) $str;

        return $this;
    }

    public function set_validate($value) {

        $this->properties['validate'] = !empty($value);

        return $this;
    }

    public function set_priority($n = 3) {

        $n = preg_match('/^[1-5]$/', $n) ? (int) $n : 3;

        $this->properties['priority'] = $n;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->Priority = $n;
        }

        return $this;
    }

    public function set_newline($newline = "\n") {

        $newline = in_array($newline, array("\n", "\r\n", "\r")) ? $newline : "\n";

        $this->properties['newline'] = $newline;

        if ($this->mailer_engine == 'phpmailer') {

            if (property_exists('\\PHPMailer\\PHPMailer\\PHPMailer', 'LE')) {

                $reflection = new \ReflectionProperty('\\PHPMailer\\PHPMailer\\PHPMailer', 'LE');
                $reflection->setAccessible(true);
                $reflection->setValue(null, $newline);
            }
        }

        return $this;
    }

    // A CodeIgniter specific option, PHPMailer uses the standard value "\r\n" only.
    public function set_crlf($crlf = "\n") {

        $crlf = ($crlf !== "\n" && $crlf !== "\r\n" && $crlf !== "\r") ? "\n" : $crlf;

        $this->properties['crlf'] = $crlf;

        return $this;
    }

    // Not used by PHPMailer.
    public function set_dsn($value) {

        $this->properties['dsn'] = !empty($value);

        return $this;
    }

    // Not used by PHPMailer.
    public function set_send_multipart($value) {

        $this->properties['send_multipart'] = !empty($value);

        return $this;
    }

    // Not used by PHPMailer.
    public function set_bcc_batch_mode($value) {

        $this->properties['bcc_batch_mode'] = !empty($value);

        return $this;
    }

    // Not used by PHPMailer.
    public function set_bcc_batch_size($value) {

        $this->properties['bcc_batch_size'] = (int) $value;

        return $this;
    }

    // PHPMailer's SMTP debug info level.
    // 0 = off, 1 = commands, 2 = commands and data, 3 = as 2 plus connection status, 4 = low level data output.
    public function set_smtp_debug($level) {

        $level = (int) $level;

        if ($level < 0) {
            $level = 0;
        }

        $this->properties['smtp_debug'] = $level;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->SMTPDebug = $level;
        }

        return $this;
    }

    // PHPMailer's SMTP debug output.
    // How to handle debug output.
    // Options:
    // `html` - the output gets escaped, line breaks are to be converted to `<br>`, appropriate for browser output;
    // `echo` - the output is plain-text "as-is", it should be avoided in production web pages;
    // `error_log` - the output is saved in error log as it is configured in php.ini;
    // NULL or '' - default: 'echo' on CLI, 'html' otherwise.
    //
    // Alternatively, you can provide a callable expecting two params: a message string and the debug level:
    // <code>
    // function custom_debug($str, $level) {echo "debug level $level; message: $str";};
    // $this->email->set_debug_output('custom_debug');
    // </code>
    public function set_debug_output($handle) {

        if ($handle === null
            ||
            is_string($handle) && $handle == ''
        ) {
            $handle = self::$default_properties['debug_output'];
        }

        $this->properties['debug_output'] = $handle;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->Debugoutput = $handle;
        }

        return $this;
    }

    // Setting explicitly the body encoding.
    // See https://github.com/ivantcholakov/codeigniter-phpmailer/issues/3
    public function set_encoding($encoding) {

        $encoding = (string) $encoding;

        if (!in_array($encoding, $this->mailer_engine == 'phpmailer' ? self::$encodings_phpmailer : self::$encodings_ci)) {
            $encoding = '8bit';
        }

        $this->properties['encoding'] = $encoding;
        $this->_encoding = $encoding;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->Encoding = $encoding;
        }

        return $this;
    }

    // PHPMailer: Whether to enable TLS encryption automatically if a server supports it,
    // even if `SMTPSecure` is not set to 'tls'.
    // Be aware that in PHP >= 5.6 this requires that the server's certificates are valid.
    public function set_smtp_auto_tls($value) {

        $value = !empty($value);

        $this->properties['smtp_auto_tls'] = $value;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->SMTPAutoTLS = $value;
        }

        return $this;
    }

    // PHPMailer: Options array passed to stream_context_create when connecting via SMTP.
    // See https://github.com/ivantcholakov/codeigniter-phpmailer/issues/12
    public function set_smtp_conn_options($value) {

        if (!is_array($value)) {
            $value = array();
        }

        $this->properties['smtp_conn_options'] = $value;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->SMTPOptions = $value;
        }

        return $this;
    }

    // DKIM signing, see https://github.com/ivantcholakov/codeigniter-phpmailer/issues/11

    // PHPMailer: DKIM signing domain name, for exmple 'example.com'.
    public function set_dkim_domain($value) {

        $value = (string) $value;

        $this->properties['dkim_domain'] = $value;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->DKIM_domain = $value;
        }

        return $this;
    }

    // PHPMailer: DKIM private key, set as a file path.
    public function set_dkim_private($value) {

        $value = (string) $value;

        $this->properties['dkim_private'] = $value;

        // Parse the provided path seek for constant and translate it.
        // For example the path to the private key could be set as follows:
        // {APPPATH}config/rsa.private
        $value_parsed = str_replace(array_keys(self::_get_file_name_variables()), array_values(self::_get_file_name_variables()), $value);

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->DKIM_private = $value_parsed;
        }

        if ($value != '') {

            // Reset the alternative setting.
            $this->set_dkim_private_string('');
        }

        return $this;
    }

    // PHPMailer: DKIM private key, set directly from a string.
    public function set_dkim_private_string($value) {

        $value = (string) $value;

        $this->properties['dkim_private_string'] = $value;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->DKIM_private_string = $value;
        }

        if ($value != '') {

            // Reset the alternative setting.
            $this->set_dkim_private('');
        }

        return $this;
    }

    // PHPMailer: DKIM selector.
    public function set_dkim_selector($value) {

        $value = (string) $value;

        $this->properties['dkim_selector'] = $value;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->DKIM_selector = $value;
        }

        return $this;
    }

    // PHPMailer: DKIM passphrase, used if your key is encrypted.
    public function set_dkim_passphrase($value) {

        $value = (string) $value;

        $this->properties['dkim_passphrase'] = $value;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->DKIM_passphrase = $value;
        }

        return $this;
    }

    // PHPMailer: DKIM Identity, usually the email address used as the source of the email.
    public function set_dkim_identity($value) {

        $value = (string) $value;

        $this->properties['dkim_identity'] = $value;

        if ($this->mailer_engine == 'phpmailer') {
            $this->phpmailer->DKIM_identity = $value;
        }

        return $this;
    }


    // Overridden public methods -----------------------------------------------

    public function valid_email($email) {

        if ($this->mailer_engine == 'phpmailer') {

            return valid_email($email);
        }

        return parent::valid_email($email);
    }


    // Custom public methods ---------------------------------------------------

    public function full_html($subject, $message) {

        $full_html =
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset='.strtolower($this->charset).'" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>'.htmlspecialchars($subject, ENT_QUOTES, $this->charset).'</title>

    <style type="text/css">

        /* See http://htmlemailboilerplate.com/ */

        /* Based on The MailChimp Reset INLINE: Yes. */
        /* Client-specific Styles */
        #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
        body {
            width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:40px;
            font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 16px;
        }
        /* End reset */

        /* Some sensible defaults for images
        Bring inline: Yes. */
        img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
        a img {border:none;}

        /* Yahoo paragraph fix
        Bring inline: Yes. */
        p {margin: 1em 0;}

        /* Hotmail header color reset
        Bring inline: Yes. */
        h1, h2, h3, h4, h5, h6 {color: black !important;}

        h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: blue !important;}

        h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {
        color: red !important; /* Preferably not the same color as the normal header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
        }

        h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
        color: purple !important; /* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */
        }

        /* Outlook 07, 10 Padding issue fix
        Bring inline: No.*/
        table td {border-collapse: collapse;}

        /* Remove spacing around Outlook 07, 10 tables
        Bring inline: Yes */
        table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }

        /* Styling your links has become much simpler with the new Yahoo.  In fact, it falls in line with the main credo of styling in email and make sure to bring your styles inline.  Your link colors will be uniform across clients when brought inline.
        Bring inline: Yes. */
        a {color: blue;}

    </style>

</head>

<body>

'.$message.'

</body>
</html>';

        return $full_html;
    }


    // Protected methods -------------------------------------------------------

    protected function _get_alt_message() {

        $alt_message = (string) $this->alt_message;

        if ($alt_message == '') {
            $alt_message = $this->_plain_text($this->_body);
        }

        if ($this->mailer_engine == 'phpmailer') {
            // PHPMailer would do the word wrapping.
            return $alt_message;
        }

        return ($this->wordwrap)
            ? $this->word_wrap($alt_message, 76)
            : $alt_message;
    }

    protected function _plain_text($html) {

        if (!function_exists('html_to_text')) {

            $body = @ html_entity_decode($html, ENT_QUOTES, $this->charset); // Added by Ivan Tcholakov, 28-JUL-2013.

            $body = preg_match('/\<body.*?\>(.*)\<\/body\>/si', $body, $match) ? $match[1] : $body;
            $body = str_replace("\t", '', preg_replace('#<!--(.*)--\>#', '', trim(strip_tags($body))));

            for ($i = 20; $i >= 3; $i--) {
                $body = str_replace(str_repeat("\n", $i), "\n\n", $body);
            }

            // Reduce multiple spaces
            $body = preg_replace('| +|', ' ', $body);

            return $body;
        }

        // You can implement your own helper function html_to_text().
        //
        // An example of Markdownify-based implementation, see https://github.com/Elephant418/Markdownify
        //
        // Install using Composer the following package: pixel418/markdownify
        // Place in MY_html_helper.php the following function:
        //
        // function html_to_text($html) {
        //
        //     static $parser;
        //
        //     if (!isset($parser)) {
        //         $parser = new \Markdownify\ConverterExtra();
        //     }
        //
        //     $parser->setKeepHTML(false);
        //
        //     return @ $parser->parseString($html);
        // }
        //

        return html_to_text($html);
    }

    protected function _extract_name($address) {

        if (!is_array($address)) {

            $address = trim($address);

            if (preg_match('/(.*)\<(.*)\>/', $address, $match)) {
                return trim($match['1']);
            } else {
                return '';
            }
        }

        $result = array();

        foreach ($address as $addr) {

            $addr = trim($addr);

            if (preg_match('/(.*)\<(.*)\>/', $addr, $match)) {
                $result[] = trim($match['1']);
            } else {
                $result[] = '';
            }
        }

        return $result;
    }

    protected static function _get_file_name_variables() {

        static $result = null;

        if ($result === null) {

            $result = array('{APPPATH}' => APPPATH);

            if (defined('COMMONPATH')) {
                $result['{COMMONPATH}'] = COMMONPATH;
            }

            if (defined('PLATFORMPATH')) {
                $result['{PLATFORMPATH}'] = PLATFORMPATH;
            }
        }

        return $result;
    }

}
