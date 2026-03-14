<!DOCTYPE html>
<html>
    <head>
<?php
//Pass some configuration to the embedded JS application
$baseUrl = dirname((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
$baseUrl = $baseUrl . "/api/doc";
$config['baseURL'] = $baseUrl;
$configAsJson = json_encode($config);
?>
        <script id="configTag" type="application/json"><?php echo $configAsJson; ?></script>
        <title>ServiceDesk Service Swagger</title>
        <link rel="icon" type="image/x-icon" href="favicon.ico" sizes="32x32">
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
                <li class="nav-item"><a class="nav-link" href="testldap">LDAP</a></li>
                <li class="nav-item"><a class="nav-link active" href="#">API HTTP</a></li>
            </ul>

            <div id="swagger-ui"></div>
        </div>
    <script type="text/javascript" src="assets/dist/swagger.js"></script>
</body>
</html>