<?php
/**
 * This diagnostic page helps you to check your setup.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.3.0
 */

define('BASEPATH','.');//Make this script works with nginx
define('ENVIRONMENT','');//Compatibility with CI3 new index

$mod_rewrite =  getenv('HTTP_MOD_REWRITE');
if (function_exists('apache_get_modules')) {
  $modules = apache_get_modules();
  $mod_rewrite = in_array('mod_rewrite', $modules)?TRUE:FALSE;
} else {
  $mod_rewrite = strtolower(getenv('HTTP_MOD_REWRITE')) == 'on'?TRUE:FALSE;
}

$env = is_null(getenv('CI_ENV'))?'':getenv('CI_ENV');
$allow_overwrite =  getenv('ALLOW_OVERWRITE');
$server_software = getenv('SERVER_SOFTWARE');
$mod_gzip = getenv('HTTP_MOD_GZIP');
$rowOrg = NULL;

if ($mod_rewrite == "") $mod_rewrite = '<b>.htaccess not visited</b>';
if ($allow_overwrite == "") $allow_overwrite = '<b>Off</b>';

$tmz = @date_default_timezone_get();

//Check if we can access to the configuration file
$pathConfigFile = realpath(join(DIRECTORY_SEPARATOR, array('application', 'config', $env, 'database.php')));
$configFileExists = file_exists($pathConfigFile);
$dbErrorMessages = array();
$dbConn = NULL;
$rowsSchema = NULL;
$dbConnError = FALSE;
$dbQueryError = FALSE;
$dbDbError = FALSE;
$dbProcsError = FALSE;

if ($configFileExists) {
    include $pathConfigFile;
    //Try to connect to database
    try {
        $dbConn = new PDO($db[$active_group]['dsn'], $db[$active_group]['username'], $db[$active_group]['password']);
    } catch(PDOException $ex) {
        $dbConnError = TRUE;
        array_push($dbErrorMessages, $ex->getMessage());
    }
    if (!$dbConnError) {
        //Examine organization structure
        try {
            $resultOrg = $dbConn->query("SELECT id FROM organization WHERE parent_id=-1");
        } catch(PDOException $ex) {
            $dbQueryError = TRUE;
            array_push($dbErrorMessages, $ex->getMessage());
        }
        if (!$dbQueryError) {
            $rowOrg = $resultOrg->fetch(PDO::FETCH_ASSOC);

            //Try to use a procedure in order to check the install script
            //We don't know if the user has access to information schema
            //So we try to call one of the procedures with a parameter returning a small set of results
            try {
                $dbConn->query("SELECT GetParentIDByID(0) AS result");
            } catch(PDOException $ex) {
                $dbProcsError = TRUE;
                array_push($dbErrorMessages, $ex->getMessage());
            }
            $sql = "SELECT TABLE_NAME, MD5(GROUP_CONCAT(CONCAT(TABLE_NAME, COLUMN_NAME, COALESCE(COLUMN_DEFAULT, ''), IS_NULLABLE, COLUMN_TYPE, COALESCE(COLLATION_NAME, '')) SEPARATOR ', ')) AS signature"
                    . " FROM information_schema.columns"
                    . " WHERE table_schema =  DATABASE()"
                    . " GROUP BY TABLE_NAME"
                    . " ORDER BY TABLE_NAME";
            try {
                $stmt = $dbConn->query($sql);
            } catch(PDOException $ex) {
                $dbQueryError = TRUE;
                array_push($dbErrorMessages, $ex->getMessage());
            }
            $rowsSchema = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
?>
<html>
    <head>
        <title>Jorani Requirements</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" type="image/x-icon" href="favicon.ico" sizes="32x32">
        <link rel="stylesheet" href="assets/dist/requirements.css">
        <script type="text/javascript" src="assets/dist/requirements.js"></script>
    </head>
    <body>
        <noscript>
            Javascript is disabled. Jorani requires Javascript to be enabled.
        </noscript>

        <div class="container">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link" href="home" title="login to Jorani"><i class="mdi mdi-home nolink"></i></a></li>
                <li class="nav-item"><a class="nav-link active" href="#">Requirements</a></li>
                <li class="nav-item"><a class="nav-link" href="testmail.php">Email</a></li>
                <li class="nav-item"><a class="nav-link" href="testldap.php">LDAP</a></li>
                <li class="nav-item"><a class="nav-link" href="testssl.php">SSL</a></li>
                <li class="nav-item"><a class="nav-link" href="testoauth2.php">OAuth2</a></li>
                <li class="nav-item"><a class="nav-link" href="testapi.php">API HTTP</a></li>
            </ul>

            <h1>
                Jorani Requirements
                <button class="btn btn-light" onclick="export2csv();"><i class="mdi mdi-download"></i>&nbsp;Export to a CSV file</button>
            </h1>

            <h2>Web Server</h2>

            <table class="table table-bordered table-hover table-condensed">
                <thead class="thead-dark">
                    <tr>
                      <th>Requirement</th>
                      <th>Value / Description</th>
                    </tr>
                  </thead>
                  <tbody id="tblServer">
                      <?php if ($env != '') {?>
                      <tr><td><i class="mdi mdi-information-outline"></i>&nbsp;Environment</td><td><?php echo $env; ?></td></tr>
                      <?php } ?>

                      <tr><td><i class="mdi mdi-information-outline"></i>&nbsp;Server software</td><td><?php echo $server_software; ?></td></tr>

                      <tr><td><?php if (strtolower($allow_overwrite) == "on") {?><i class="mdi mdi-check"></i><?php } else { ?><i class="mdi mdi-alert"></i><?php } ?>
                      &nbsp;Allow overwrite (.htaccess files)</td><td><?php echo $allow_overwrite; ?> (used for cool URLs). Ignore this message if you are running something else than Apache.</td></tr>

                      <tr><td><?php if ($mod_rewrite) {?><i class="mdi mdi-check"></i><?php } else { ?><i class="mdi mdi-alert"></i><?php } ?>
                      &nbsp;Apache module rewrite (mod_rewrite)</td><td><?php echo $mod_rewrite; ?> (used for cool URLs). Ignore this message if you are running something else than Apache.</td></tr>

                      <tr><td><?php if (strtolower($mod_gzip) == "on") {?><i class="mdi mdi-check"></i><?php } else { ?><i class="mdi mdi-alert"></i><?php } ?>
                      &nbsp;Apache module gzip (mod_gzip)</td><td><?php echo $mod_gzip; ?> (turning it On would improve response times).</td></tr>

                      <?php if (version_compare(PHP_VERSION, '5.6.0') >= 0) {?>
                      <tr><td><i class="mdi mdi-check"></i>&nbsp;PHP 5.6.0+</td>
                      <?php } else { ?>
                      <tr><td><i class="mdi mdi-alert"></i>&nbsp;Old PHP version</td>
                      <?php } ?><td>Ignore this message if you are running an exotic PHP runtime</td></tr>

                      <?php if (defined('HHVM_VERSION')) {?>
                       <tr><td><i class="mdi mdi-information-outline"></i>&nbsp;HHVM</td><td><?php echo HHVM_VERSION; ?></td></tr>
                       <?php } else { ?>
                       <tr><td><i class="mdi mdi-information-outline"></i>&nbsp;PHP</td><td><?php echo PHP_VERSION; ?></td></tr>
                       <?php } ?>

                      <?php if ($tmz != 'UTC') {?>
                      <tr><td><i class="mdi mdi-check"></i>&nbsp;Timezone defined</td>
                      <?php } else { ?>
                      <tr><td><i class="mdi mdi-alert"></i>&nbsp;Timezone undefined</td>
                      <?php } ?><td>If error, please check date.timezone into PHP.ini.</td></tr>

                      <?php if (function_exists('mb_strimwidth')) {?>
                      <tr><td><i class="mdi mdi-check"></i>&nbsp;<code>mb_strimwidth</code> function exists</td>
                      <?php } else { ?>
                      <tr><td><i class="mdi mdi-alert"></i>&nbsp;<code>mb_strimwidth</code> function doesn't exist</td>
                      <?php } ?><td>PHP must be compiled with <a href="http://php.net/manual/en/mbstring.installation.php" target="_blank">multibyte string support<a>.</td></tr>

                      <?php if (function_exists('json_encode')) {?>
                      <tr><td><i class="mdi mdi-check"></i>&nbsp;<code>json_encode</code> function exists</td>
                      <?php } else { ?>
                      <tr><td><i class="mdi mdi-alert"></i>&nbsp;<code>json_encode</code> function doesn't exist</td>
                      <?php } ?><td>PHP must be compiled with <a href="http://php.net/manual/en/json.installation.php" target="_blank">json support<a>.</td></tr>

                      <?php if (is_writable(realpath('application/logs/'))) {?>
                      <tr><td><i class="mdi mdi-check"></i>&nbsp;Jorani can write into logs folder</td>
                      <?php } else { ?>
                      <tr><td><i class="mdi mdi-alert"></i>&nbsp;Jorani can't write into logs folder</td>
                      <?php } ?><td>The folder application/logs/ must be writable.</td></tr>

                      <?php if (is_writable(realpath('local/upload/leaves/'))) {?>
                      <tr><td><i class="mdi mdi-check"></i>&nbsp;Jorani can write files</td>
                      <?php } else { ?>
                      <tr><td><i class="mdi mdi-alert"></i>&nbsp;Jorani can't write files</td>
                      <?php } ?><td>The folder local/upload/leaves/ must be writable.</td></tr>
					  
					  <?php if (is_writable(realpath('local/upload/teleworks/'))) {?>
                      <tr><td><i class="mdi mdi-check"></i>&nbsp;Jorani can write files</td>
                      <?php } else { ?>
                      <tr><td><i class="mdi mdi-alert"></i>&nbsp;Jorani can't write files</td>
                      <?php } ?><td>The folder local/upload/teleworks/ must be writable.</td></tr>
                      
                      <?php if (is_writable(realpath('local/upload/teleworkrules/'))) {?>
                      <tr><td><i class="mdi mdi-check"></i>&nbsp;Jorani can write files</td>
                      <?php } else { ?>
                      <tr><td><i class="mdi mdi-alert"></i>&nbsp;Jorani can't write files</td>
                      <?php } ?><td>The folder local/upload/teleworkrules/ must be writable.</td></tr>

					  <?php if (is_writable(realpath('local/upload/timeorganisations/'))) {?>
                      <tr><td><i class="mdi mdi-check"></i>&nbsp;Jorani can write files</td>
                      <?php } else { ?>
                      <tr><td><i class="mdi mdi-alert"></i>&nbsp;Jorani can't write files</td>
                      <?php } ?><td>The folder local/upload/timeorganisations/ must be writable.</td></tr>

                      <?php if (extension_loaded('pdo_mysql')) {?>
                      <tr><td><i class="mdi mdi-check"></i>&nbsp;pdo_mysql is LOADED</td>
                      <?php } else { ?>
                      <tr><td><i class="mdi mdi-alert"></i>&nbsp;PDO/mysql IS NOT LOADED.</td>
                      <?php } ?><td>PDO/mysql is the recommended database driver.</td></tr>

                      <?php if (extension_loaded('Zend OPcache')) {?>
                      <tr><td><i class="mdi mdi-check"></i>&nbsp;OPcache is LOADED</td>
                      <?php } else { ?>
                      <tr><td><i class="mdi mdi-alert"></i>&nbsp;OPcache IS NOT LOADED.</td>
                      <?php } ?><td>Please consider activating OPcache for the best performances.</td></tr>

                      <?php if (extension_loaded('openssl')) {?>
                      <tr><td><i class="mdi mdi-check"></i>&nbsp;openssl is LOADED</td>
                      <?php } else { ?>
                      <tr><td><i class="mdi mdi-alert"></i>&nbsp;openssl IS NOT LOADED.</td>
                      <?php } ?><td>PHP Extension openssl is required if you use PHP7.1.</td></tr>

                      <?php if (extension_loaded('curl')) {?>
                      <tr><td><i class="mdi mdi-check"></i>&nbsp;curl is LOADED</td>
                      <?php } else { ?>
                      <tr><td><i class="mdi mdi-alert"></i>&nbsp;curl IS NOT LOADED.</td>
                      <?php } ?><td>PHP Extension curl is needed for OAuth2 authentication.</td></tr>

                      <?php if (extension_loaded('ldap')) {?>
                      <tr><td><i class="mdi mdi-check"></i>&nbsp;ldap is LOADED</td>
                      <?php } else { ?>
                      <tr><td><i class="mdi mdi-alert"></i>&nbsp;ldap IS NOT LOADED</td>
                      <?php } ?><td>PHP Extension ldap is optional and allows you to use LDAP for authentication.</td></tr>

                      <?php if (extension_loaded('zip')) {?>
                      <tr><td><i class="mdi mdi-check"></i>&nbsp;zip is LOADED</td>
                      <?php } else { ?>
                      <tr><td><i class="mdi mdi-alert"></i>&nbsp;zip IS NOT LOADED</td>
                      <?php } ?><td>PHP Extension zip allows you to use the export to Excel feature.</td></tr>

                      <?php if (extension_loaded('xml')) {?>
                      <tr><td><i class="mdi mdi-check"></i>&nbsp;xml is LOADED</td>
                      <?php } else { ?>
                      <tr><td><i class="mdi mdi-alert"></i>&nbsp;xml IS NOT LOADED</td>
                      <?php } ?><td>PHP Extension xml allows you to use the export to Excel feature (and SAML/SSO).</td></tr>

                      <?php if (extension_loaded('gd')) {?>
                      <tr><td><i class="mdi mdi-check"></i>&nbsp;gd is LOADED</td>
                      <?php } else { ?>
                      <tr><td><i class="mdi mdi-alert"></i>&nbsp;gd IS NOT LOADED</td>
                      <?php } ?><td>PHP Extension gd2 allows you to use the export to Excel feature.</td></tr>

                      <?php if (extension_loaded('date')) {?>
                      <tr><td><i class="mdi mdi-check"></i>&nbsp;date is LOADED</td>
                      <?php } else { ?>
                      <tr><td><i class="mdi mdi-alert"></i>&nbsp;date IS NOT LOADED</td>
                      <?php } ?><td>PHP Extension zlib allows you to use the Authentication by SAML feature (SSO).</td></tr>

                      <?php if (extension_loaded('zlib')) {?>
                      <tr><td><i class="mdi mdi-check"></i>&nbsp;zlib is LOADED</td>
                      <?php } else { ?>
                      <tr><td><i class="mdi mdi-alert"></i>&nbsp;zlib IS NOT LOADED</td>
                      <?php } ?><td>PHP Extension zlib allows you to use the Authentication by SAML feature (SSO).</td></tr>

                  </tbody>
            </table>

            <h2>Additional configuration</h2>

            <p>You can test the following settings, but you need to edit the corresponding PHP scripts :</p>
            <ul>
                <li><a href="testssl.php" target="_blank">SSL Configuration and Utility.</a></li>
                <li><a href="testoauth2.php" target="_blank">OAuth2 Settings.</a></li>
                <li><a href="testmail.php" target="_blank">E-mail Settings.</a></li>
                <li><a href="testldap.php" target="_blank">LDAP Settings.</a></li>
                <li><a href="opcache.php" target="_blank">OPCache Tester.</a></li>
            </ul>

            <h2>Database</h2>

            <table class="table table-bordered table-hover table-condensed">
                <thead class="thead-dark">
                    <tr>
                      <th>Requirement</th>
                      <th>Value / Description</th>
                    </tr>
                  </thead>
                  <tbody id="tblDatabase">
                      <?php if ($configFileExists) { ?><tr><td><i class="mdi mdi-check"></i>&nbsp;Configuration file</td><td>Found</td></tr>
                      <?php } else { ?><tr><td><i class="mdi mdi-alert"></i>&nbsp;Configuration file</td><td>Not Found</td></tr>
                      <?php } ?>

                      <?php if (!$dbConnError) { ?><tr><td><i class="mdi mdi-check"></i>&nbsp;Database connection</td><td>OK</td></tr>
                      <?php } else { ?><tr><td><i class="mdi mdi-alert"></i>&nbsp;Database connection</td><td>Error</td></tr>
                      <?php } ?>

                      <?php if (!$dbQueryError) { ?><tr><td><i class="mdi mdi-check"></i>&nbsp;Database query</td><td>OK</td></tr>
                      <?php } else { ?><tr><td><i class="mdi mdi-alert"></i>&nbsp;Database query</td><td>Error</td></tr>
                      <?php } ?>

                      <?php if (!$dbProcsError) { ?><tr><td><i class="mdi mdi-check"></i>&nbsp;Database procedures</td><td>OK</td></tr>
                      <?php } else { ?><tr><td><i class="mdi mdi-alert"></i>&nbsp;Database procedures</td><td>Error. Please check if your hosting company allows custom procedures (e.g. <a href="https://techtavern.wordpress.com/2013/06/17/mysql-triggers-and-amazon-rds/" target="_blank">Amazon RDS</a>).</td></tr>
                      <?php } ?>

                      <?php if (is_null($rowOrg)) { ?><tr><td><i class="mdi mdi-alert"></i>&nbsp;Organization structure</td><td>No root entity was found.</td></tr>
                      <?php } else { ?>
                            <?php if ($rowOrg['id'] != 0) { ?><tr><td><i class="mdi mdi-alert"></i>&nbsp;Organization structure</td><td>The root entity must be equal to zero. To fix a problem of backup/restore, please execute this query: <br />
                                    <code>UPDATE `organization` SET `organization`.`id` = 0 WHERE `parent_id` = -1</code></td></tr>
                            <?php } else { ?><tr><td><i class="mdi mdi-check"></i>&nbsp;Organization structure</td><td>OK</td></tr>
                      <?php }
                           }?>

                      <?php foreach ($dbErrorMessages as $msg) {?>
                            <tr>
                                <td><i class="mdi mdi-alert"></i>&nbsp;Error</td>
                                <td><?php echo $msg; ?></td>
                            </tr>
                      <?php } ?>
                  </tbody>
            </table>

            <h2>Schema</h2>

            <table class="table table-bordered table-hover table-condensed">
                <thead class="thead-dark">
                    <tr>
                      <th>Table</th>
                      <th>Signature</th>
                    </tr>
                  </thead>
                  <tbody id="tblSchema">
<?php if (!$dbConnError && !$dbQueryError) {
	foreach ($rowsSchema as $row) {  ?>
                <tr><td><i class="mdi mdi-information-outline"></i>&nbsp;<?php echo $row['TABLE_NAME']; ?></td><td><?php echo $row['signature']; ?></td></tr>
<?php }
        } else { ?>
                <tr><td colspan="2"><i>Impossible to query database</i></td></tr>
<?php } ?>
                  </tbody>
            </table>
        </div>
    </body>
</html>
