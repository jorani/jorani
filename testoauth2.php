<?php
/**
 * This diagnostic page helps you to check OAuth2.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.5.0
 */

define('BASEPATH','.');//Make this script works with nginx
$env = is_null(getenv('CI_ENV'))?'':getenv('CI_ENV');

?>
<html>
    <head>
        <title>Jorani OAuth2 Configuration</title>
        <meta charset="utf-8">
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
                <li class="nav-item"><a class="nav-link" href="testldap.php">LDAP</a></li>
                <li class="nav-item"><a class="nav-link" href="testssl.php">SSL</a></li>
                <li class="nav-item"><a class="nav-link active" href="#">OAuth2</a></li>
                <li class="nav-item"><a class="nav-link" href="testapi.php">API HTTP</a></li>
            </ul>

        <h1>Setup of OAuth2 sign-in</h1>

        <p>The curl and openssl PHP extensions must be loaded if you want to use this feature.</p>

        <h2>Checkup of your environement</h2>

            <table class="table table-bordered table-hover table-condensed">
                <thead class="thead-dark">
                    <tr>
                      <th>Requirement</th>
                      <th>Value / Description</th>
                    </tr>
                  </thead>
                  <tbody>
<?php

$pathConfigFile = realpath(join(DIRECTORY_SEPARATOR, array('application', 'config', $env, 'config.php')));
include $pathConfigFile;

echo "<tr><td>PHP_VERSION</td><td>" . (version_compare(PHP_VERSION, '5.6.0', '>=')? '>=5.6.0' : '<5.6.0')  . '</td></tr>';
echo "<tr><td>openssl</td><td>" . ((extension_loaded('openssl'))? 'extension loaded' : 'extension not loaded')  . '</td></tr>';
echo "<tr><td>curl</td><td>" . ((extension_loaded('curl'))? 'extension loaded' : 'extension not loaded')  . '</td></tr>';
echo "<tr><td>oauth2_enabled</td><td>" . ($config['oauth2_enabled']===TRUE? 'TRUE': 'FALSE')  . '</td></tr>';
echo "<tr><td>oauth2_provider</td><td>" . $config['oauth2_provider'] . '</td></tr>';
echo "<tr><td>oauth2_client_id</td><td>" . ($config['oauth2_client_id']!=''? 'Provided': 'Empty')  . '</td></tr>';
echo "<tr><td>oauth2_client_secret</td><td>" . ($config['oauth2_client_secret']!=''? 'Provided': 'Empty')  . '</td></tr>';
echo "<tr><td>curl.cainfo</td><td>" . (file_exists(ini_get('curl.cainfo'))===TRUE? 'Found': 'Not found')  . '</td></tr>';
echo "<tr><td>openssl.cafile</td><td>" . (file_exists(ini_get('openssl.cafile'))===TRUE? 'Found': 'Not found')  . '</td></tr>';
echo "<tr><td>openssl_get_cert_locations</td><td>" . ((function_exists('openssl_get_cert_locations'))? 'Exists' : 'Doesn\'t exist')  . '</td></tr>';

if (function_exists('openssl_get_cert_locations')) {
    $filesToCheck = openssl_get_cert_locations();
    foreach ($filesToCheck as $key => $value) {
        echo "<tr><td>$key</td><td>" . (file_exists($value)===TRUE? 'Found': 'Not found')  . '</td></tr>';
    }
}
?>
                  </tbody>
            </table>


<h2>Additional information</h2>

<h3>Setup Google+ API</h3>

<p>Instruction for Google API:</p>
<ul>
    <li>Open <a target="_blank" href="https://console.developers.google.com/project">Google Developers Console</a>, and create or modify a project.</li>
    <li>In the dashboard, select "Enable APIs and get credentials like keys" or a similar entry.</li>
    <li>Google+ API must be enabled.</li>
    <li>In the left side bar, select "Credentials".</li>
    <li>Check that the domain that you'll use is defined into "Domain Verification tab" and add it otherwise.</li>
    <li>Optionnally, you may customize the "OAuth consent screen".</li>
    <li>Into "Credentials", create a new entry in "OAuth 2.0 client IDs".</li>
    <li>In "Authorized JavaScript origins", add the root of your installation (eg "http://demo.jorani.org").</li>
    <li>The field "Authorized redirect URIs" must be blank.</li>
    <li>Don't forget to save and to note Client ID and Client secret.</li>
</ul>

<h3>Setup Jorani</h3>

<p>Setup Jorani by editing <code>application/config/config.php</code></p>

<ul>
    <li>Switch <code>oauth2_enabled</code> to <code>TRUE</code>.</li>
    <li>Set <code>oauth2_provider</code> to <code>google</code> (only google is supported).</li>
    <li>Set <code>oauth2_client_id</code> with the value you got from Google developers console.</li>
    <li>Set <code>oauth2_client_secret</code> with the value you got from Google developers console.</li>
</ul>

            <h3>Setup your PHP environment</h3>

            <p>Depending on the version you are using, PHP will try to search for a <i>bundle</i> of certicates to be trusted. It is a file containing a list of public keys emitted by servers and APIs. Of course, the Google API must be listed prior trying to use the Google+ API for authentication purposes.</p>

            <p>In case your system is not up-to-date, Jorani is bundled with a list of trusted certicates into <code>assets/keys/cacert.pem</code>. The absolute path to this file must be known by either:</p>

            <ul>
                <li>OpenSSL, if your version of PHP is greater than 5.6.</li>
                <li>Curl, if your version of PHP is lesser than 5.6.</li>
            </ul>

            <p>You should modify the <code>PHP.ini</code> file depending on the version of PHP, as follow for PHP 5.6+:</p>

<pre>
[openssl]
; The location of a Certificate Authority (CA) file on the local filesystem
; to use when verifying the identity of SSL/TLS peers. Most users should
; not specify a value for this directive as PHP will attempt to use the
; OS-managed cert stores in its absence. If specified, this value may still
; be overridden on a per-stream basis via the "cafile" SSL stream context
; option.
openssl.cafile=C:\wamp\www\auth\cacert.pem

; If openssl.cafile is not specified or if the CA file is not found, the
; directory pointed to by openssl.capath is searched for a suitable
; certificate. This value must be a correctly hashed certificate directory.
; Most users should not specify a value for this directive as PHP will
; attempt to use the OS-managed cert stores in its absence. If specified,
; this value may still be overridden on a per-stream basis via the "capath"
; SSL stream context option.
;openssl.capath=
</pre>

            <p>Or as follow for older versions of PHP:</p>

<pre>
[curl]
; A default value for the CURLOPT_CAINFO option. This is required to be an
; absolute path.
curl.cainfo=C:\wamp\www\auth\cacert.pem
</pre>

            <p>Of course, adapt the path according to your environment.</p>

            <h3>Troubleshooting</h3>
            <p>In case of error, here are some additional steps:</p>
            <ul>
                <li>The HTTP/HTTPS ports may be blocked by your organization/server's security policy (or firewall).</li>
                    <li>When running SELinux, the webserver is blocked by default (it cannot open a network connection). Please consider unblocking it:
                        <p>
<pre>
$ setsebool -P httpd_can_network_connect 1
</pre>
                        </p>
                    </li>
            </ul>

        </div>
    </body>
</html>
