<?php
/**
 * CAS Configuration file (for phpCAS)
 * Full documentation is available at https://apereo.github.io/cas/7.3.x/index.html
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since         1.0.4
 */

// You must switch $config['cas_enabled'] to TRUE into config/config.php prior using CAS
// SAML authentication has the priority over CAS authentication

$config['cas_server_hostname'] = ($v = getenv('CAS_SERVER_HOSTNAME')) !== false ? $v : 'localhost';
$config['cas_server_port'] = ($v = getenv('CAS_SERVER_PORT')) !== false ? $v : 8443;
$config['cas_server_path'] = ($v = getenv('CAS_SERVER_PATH')) !== false ? $v : '/cas';
$config['cas_server_version'] = ($v = getenv('CAS_SERVER_VERSION')) !== false ? $v : '2.0';
$config['cas_no_cas_server_validation'] = ($v = getenv('CAS_NO_CAS_SERVER_VALIDATION')) !== false ? $v : 'FALSE';
