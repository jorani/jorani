# Installation

Jorani has been tested with Apache, ngnix, IIS and lighttpd (it works fine with any webserver supporting fastcgi).
Jorani is compatible with HHVM as Codeigniter is 100% compatible with the VM. 
Jorani is 100% compatible with PHP 7.0 (starting from v0.4.0).

## General considerations

* Jorani must have write privileges on <code>application/logs</code> and <code>local/upload/*</code> folders.
* Some PHP extensions are required (e.g. mcrypt, mysqli, xml, zip, gd). 
* The default user is *bbalet* and password is *bbalet*.
* The script <code>requirements.php</code>, at the root of the installation (e.g. *http://localhost/jorani/requirements.php*) allows you to check your system.
* Change <code>application/config/config.php</code> in order to modify the default behavior of Jorani.
* Jorani uses MySQL procedures (PROCEDURE). Some web hosting companies don't allow using custom MySQL functions.
* Jorani doesn't import users from external authentication sources (LDAP, OAuth2, SAML, etc.), so you must create the users into Jorani.

## Database setup

Jorani has been tested with MySQL and MariaDB (please note that functions are created in the schema). Please follow these steps :
* Create a database.
* Import <code>/sql/lms.sql</code>.
* Change <code>application/config/database.php</code> according to your environment.

Please note that the schema contains procedures, so the user used for Jorani must have EXECUTE permission. 
Please check errors output by the script as some users reported that procedures are not created if you don't have SUPER privilege. 
A possible workaround is to start your MySQL session by this command `SET GLOBAL log_bin_trust_function_creators = 1;` (please refer to MySQL documentation).

## E-mail setup

Jorani uses e-mail to notify users and their line managers. In order to setup e-mail modify 
<code>/application/config/email.php</code> file according to your environment.
Please use the script <code>testmail.php</code> at the root of your installation if you want to debug and read the troubleshooting section of this script for additional hints.

### Example for GMail

    $config['protocol'] = 'smtp';
    $config['useragent'] = 'phpmailer';
    $config['smtp_host'] = 'ssl://smtp.googlemail.com';
    $config['smtp_user'] = 'my.account.@gmail.com';
    $config['smtp_pass'] = 'my password';
    $config['_smtp_auth'] = TRUE;
    $config['smtp_port'] = '465';

## LDAP

You must activate PHP LDAP module prior using this feature.
Please use the script <code>testldap.php</code> at the root of your installation if you want to debug.
In order to configure LDAP, locate these lines of code into <code>application/config/config.php</code> :

    $config['ldap_enabled'] = FALSE;
    $config['ldap_host'] = '127.0.0.1';
    $config['ldap_port'] = 389;
    $config['ldap_basedn'] = 'uid=%s,ou=people,dc=company,dc=com';

* Switch ldap_enabled to <code>TRUE</code>.
* Change <code>ldap_host</code> and <code>ldap_port</code> according to your environement.
* Jorani tries to bind to LDAP according to the content of <code>ldap_basedn</code> in where <code>%s</code> is a placeholder for the user id to be checked into LDAP (e.g. <code>%s</code> will be replaced by the login from LMS db).
* Contact your IT Admin in order to know more about how LDAP is configured into your organization. Change the value but <code>%s</code> must remain somewhere into this string.
* The user id into Jorani and LDAP must be the same. When LDAP is activated, LMS doesn't use anymore the password stored into the database.

Since version 0.1.5, Jorani supports complex LDAP authentication schemes (where users are in different loactions in the directory). In order to use this feature :

1. Set <code>ldap_basedn_db</code> to TRUE.
2. The Base DN is not based on <code>ldap_basedn</code>, but read from the users table, column <code>ldap_path</code> (e.g. from database).
3. The Base DN should look like <code>uid=bbalet,ou=people,dc=company,dc=com</code>. Note that this feature allows you to authenticate users from different OU.

## SSO/OAuth2 with Google+

Please refer to the script <code>testoauth2.php</code> at the root of installation for guidance and tests. Please read <code>application/config/saml-example-onelogin.php</code> for an example.

## SSO/SAML

Since v0.5.0, Jorani can use SAML for SSO. It has been tested with onelogin as IDP.

## Apache

Jorani is a PHP/CI application using rewrite rules and .htaccess files. 
So your Apache configuration must **allow overwriting configuration by .htaccess files and mod_rewrite must be enabled**.

## Other webservers

nginx or lighttpd in conjunction with fpm-php are for advanced users because they are more complicated to tune than Apache (see troubleshooting section of this document).
We recommend to validate your production environement with a load test prior going live.

### nginx

For your convinience, a sample ngnix configuration file is provided in this folder
<code>/docs/install/ngnix/default</code>

If you are using HTTP protocol, don't forget to disable HTTPS in <code>application/config/config.php</code>
<code>$_SERVER['HTTPS'] = 'off';</code>

Other parameters should be considered carefully (in <code>/etc/nginx/nginx.conf</code>), such as:
* events.worker_connections
* events.multi_accept
* http.keepalive_timeout
The values depend on your environment and the load that you are expecting.

### lighttpd

To enable PHP in lighttpd, you must modify your php.ini and uncomment the line <code>cgi.fix_pathinfo=1</code>.

For your convinience, a sample lighttpd configuration file is provided in this folder <code>/docs/install/lighttpd/lighttpd.conf</code>

### IIS7

To enable PHP in IIS7, you must follow the instructions provided on the official IIS website : http://www.iis.net/learn/application-frameworks/install-and-configure-php-applications-on-iis/using-fastcgi-to-host-php-applications-on-iis

Jorani uses rewriting techniques, so you must install the rewriting module prior using Jorani http://www.iis.net/downloads/microsoft/url-rewrite

Jorani uses icons contained in a woff2 font file, so you must make sure that the following MIME types are configured on IIS: `.woff    application/font-woff` and `.woff2    application/font-woff2`.

For your convinience, a sample IIS7 configuration file is provided in this folder <code>/docs/install/iis7/web.config</code>. You need to copy this file at the root of your Jorani installation and to adapt it to your needs.

## HHVM

You must activate the Zend Compatibility Layer as PHP function cal_days_in_month is not yet implemented (in /etc/hhvm/php.ini) :

    hhvm.enable_zend_compat = true

See:
* http://docs.hhvm.com/manual/en/function.cal-days-in-month.php
* http://docs.hhvm.com/manual/en/configuration.zend.compat.php


# Troubleshooting

## A load test raises error at a given number of simultaneous user

If you are running php-fpm, examine <code>/var/log/php5-fpm.log</code>, if you see this message :
<code>WARNING: [pool www] server reached pm.max_children setting (5), consider raising it</code>
In <code>/etc/php5/fpm/pool.d/www.conf</code> which is set by default to 5
pm.max_children = xxxx (number of simultaneous processes)
For a load test, allow a margin of 25%

## Error upstream sent too big header

If you get this error : <code>upstream sent too big header while reading response header from upstream</code>, you need to enlarge the buffers used by nginx.

Add this to your *http* section of the nginx.conf file normally located at /etc/nginx/nginx.conf:

    proxy_buffer_size   128k;
    proxy_buffers   4 256k;
    proxy_busy_buffers_size   256k;

Then add this to your php location block, this will be located in your vhost file look for the block that begins with <code>location ~ .php$ {</code>:

    fastcgi_buffer_size 128k;
    fastcgi_buffers 4 256k;
    fastcgi_busy_buffers_size 256k;

If you are running nginx, tune your configuration (see <code>/etc/nginx/nginx.conf</code>).

# Migration

It is recommended to change the file log extension to log instead of php in order to prevent security exploits based on log injection.
If you migrate from an older version of Jorani, please add this variable into the configuration:

    $config['log_file_extension'] = 'log';

