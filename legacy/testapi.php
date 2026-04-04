<!DOCTYPE html>
<html>

<head>
    <?php
    //Pass some configuration to the embedded JS application
    $baseUrl = dirname((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    $baseUrl = $baseUrl . "/api/doc";
    $baseUrl = strip_tags($baseUrl);
    $baseUrl = preg_replace('/[\x00-\x1F\x7F]/', '', $baseUrl);
    ?>
    <title>API Jorani</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico" sizes="32x32">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" sizes="32x32">
    <link rel="stylesheet" href="assets/dist/requirements.css">
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui.css" />
</head>

<body>
    <div class="container">
        <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link" href="home" title="login to Jorani"><i
                        class="mdi mdi-home nolink"></i></a></li>
            <li class="nav-item"><a class="nav-link" href="requirements.php">Requirements</a></li>
            <li class="nav-item"><a class="nav-link" href="testmail.php">Email</a></li>
            <li class="nav-item"><a class="nav-link" href="testldap.php">LDAP</a></li>
            <li class="nav-item"><a class="nav-link active" href="#">API</a></li>
        </ul>

        <div id="swagger-ui"></div>
    </div>
    <script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-bundle.js" crossorigin></script>
    <script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-standalone-preset.js" crossorigin></script>
    <script>
        window.onload = () => {
            window.ui = SwaggerUIBundle({
                url: '<?php echo $baseUrl; ?>',
                dom_id: '#swagger-ui',
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                layout: "StandaloneLayout",
            });
        };
    </script>
</body>

</html>