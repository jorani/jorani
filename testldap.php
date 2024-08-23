<?php
/**
 * This diagnostic page helps you to check ldap setup.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.2
 */

define('BASEPATH', '.'); //Make this script works with nginx
$env = is_null(getenv('CI_ENV'))?'':getenv('CI_ENV');
if (!defined('LDAP_OPT_DIAGNOSTIC_MESSAGE')) {
    define('LDAP_OPT_DIAGNOSTIC_MESSAGE', 0x0032);
}
//Configuration values are taken from application/config/(env)/config.php
//This script may take some time especially if the LDAP is unreachable
//-----------------------------------------------------------------
//Please enter a valid username and password
define('LDAP_LOGIN', '');  //This login must exist in Jorani and LDAP
define('LDAP_PASSWORD', '');  //This is the password we will use to bind to LDAP
//-----------------------------------------------------------------
?>
<html>
    <head>
        <title>Jorani LDAP Configuration</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" type="image/x-icon" href="favicon.ico" sizes="32x32">
        <link rel="stylesheet" href="assets/dist/requirements.css">
        <script type="text/javascript" src="assets/dist/requirements.js"></script>
    </head>
    <body>
        <div class="container">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link" href="home" title="login to Jorani"><i class="mdi mdi-home nolink"></i></a></li>
                <li class="nav-item"><a class="nav-link" href="requirements.php">Requirements</a></li>
                <li class="nav-item"><a class="nav-link" href="testmail.php">Email</a></li>
                <li class="nav-item"><a class="nav-link active" href="#">LDAP</a></li>
                <li class="nav-item"><a class="nav-link" href="testssl.php">SSL</a></li>
                <li class="nav-item"><a class="nav-link" href="testoauth2.php">OAuth2</a></li>
                <li class="nav-item"><a class="nav-link" href="testapi.php">API HTTP</a></li>
            </ul>

            <h1>Test of your LDAP configuration</h1>

<?php
//Check if we can access to the configuration file
$pathConfigFile = realpath(join(DIRECTORY_SEPARATOR, array('application', 'config', $env, 'config.php')));
$pathDbFile = realpath(join(DIRECTORY_SEPARATOR, array('application', 'config', $env, 'database.php')));
$configFileExists = file_exists($pathConfigFile);
$dBFileExists = file_exists($pathDbFile);

if (LDAP_LOGIN == '') {
    echo '<div class="alert alert-danger" role="alert"><b>ERROR:</b> Please provide a valid login in testldap.php.</div>' . PHP_EOL;
} else {
    if ($configFileExists && $dBFileExists) {
        if (extension_loaded('ldap')) {
            include $pathConfigFile;
            try {
                if ($config['ldap_enabled'] == FALSE) {
                    echo '<b>WARNING:</b> LDAP is disabled into Jorani configuration file.<br />' . PHP_EOL;
                }

                $ldapUrl = 'ldap://' . $config['ldap_host'] . ':' . $config['ldap_port'] . '/';
                $handle = @ldap_connect($ldapUrl);
                if ($handle == FALSE) {
                    //This is tricky because LDAP 2.x.x will always return a resource id, next call might fail
                    echo '<b>ERROR:</b> Impossible to connect to LDAP server.<br />' . PHP_EOL;
                }
                //Protocol v3 is mandatory, because we might use Microsoft AD
                ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
                ldap_set_option($handle, LDAP_OPT_PROTOCOL_VERSION, 3);

                $basedn = "";
                if ($config['ldap_basedn_db'] == TRUE) {
                    echo '<b>INFO:</b> Using BaseDN defined into database.<br />' . PHP_EOL;
                    include $pathDbFile;
                    $dbConn = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password']);
                    $dbConn->select_db($db['default']['database']);
                    $sql = "SELECT ldap_path FROM users WHERE login = ?";
                    $stmt = $dbConn->prepare($sql);
                    $stmt->bind_param('s', $login);
                    $login = LDAP_LOGIN;
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $row = $res->fetch_assoc();
                    if (count($row) == 0) {
                        echo '<b>ERROR:</b> The user wasn\'t found into Jorani\'s database.<br />' . PHP_EOL;
                    } else {
                        $basedn = $row['ldap_path'];
                        if ($basedn == "") {
                            echo '<b>ERROR:</b> The baseDN from DB is empty.<br />' . PHP_EOL;
                        }
                    }
                } else {
                    echo '<b>INFO:</b> Using BaseDN defined into configuration file.<br />' . PHP_EOL;
                    $basedn = sprintf($config['ldap_basedn'], LDAP_LOGIN);
                }

                //Try to search for user
                if ($basedn != "") {
                    $bind = @ldap_bind($handle, $basedn, LDAP_PASSWORD);
                    if (!$bind) {
                        echo '<b>ERROR:</b>Binding to LDAP. Message = ' . ldap_error($handle) . '<br />' . PHP_EOL;
                    } else {
                        echo '<b>INFO:</b> Connection is successful.<br />' . PHP_EOL;
                    }
                }
                ldap_close($handle);
            } catch (Exception $e) {
                echo '<b>ERROR:</b> Unexpected error.<br />' . PHP_EOL;
                $text = $e->getMessage();
                $text = iconv(mb_detect_encoding($text, mb_detect_order(), true), "UTF-8", $text);
                echo $text . PHP_EOL;
            }
        } else {
            echo '<div class="alert alert-danger" role="alert"><b>ERROR:</b> PHP LDAP extension is not loaded.</div>' . PHP_EOL;
        }
    } else {
        echo '<div class="alert alert-danger" role="alert"><b>ERROR:</b> The configuration files were not found.</div>' . PHP_EOL;
    }
}
?>
            <h3>Troubleshooting</h3>
            <p>In case of error, here are some additional steps:</p>
            <ul>
                <li>Whether you are using LDAP or Microsoft Active Directory, the users must be created into Jorani (of course, we'll not use the password stored into DB).</li>
                <li>If you are using Microsoft Active Directory, you must enable LDAP v3 protocol.</li>
                <li>Check the configuration with your IT Admin team. Ask them about the pattern to be used for binding to LDAP.</li>
                <li>The LDAP port may be blocked by your organization/server's security policy (or firewall).</li>
                    <li>When running SELinux, the webserver is blocked by default (it cannot open a network connection). Please consider unblocking it:
                        <p>
<pre>
$ setsebool -P httpd_can_network_connect 1
</pre>
                        </p>
                    </li>
                <li>Some LDAP servers require the application server (eg Jorani) to be whitelisted.</li>
            </ul>

            <h3>Examples of BaseURL</h3>

            <p>With LDAP, if your users are all into the same OU, you'd use a common pattern:</p>
            <pre>uid=%s,ou=people,dc=company,dc=com</pre>

            <p>With Microsoft Active Directory, you would associate a user with its LDAP full path into the DB table <tt>users</tt>:</p>
            <pre>CN=BALET benjamin,OU=Users,DC=COMMON,DC=AD,DC=COMPANY,DC=FR</pre>

    </div>
</body>
</html>
