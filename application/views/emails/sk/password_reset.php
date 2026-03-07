<?php
/**
 * Email template.You can change the content of this template
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/jorani/jorani
 * @since         0.1.0
 */
?>
<html lang="sk">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta charset="UTF-8">
    <style>
        table {
            width: 50%;
            margin: 5px;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 20px;
        }

        h5 {
            color: red;
        }
    </style>
</head>

<body>
    <h3>{Title}</h3>
    <p>Vážená/ý {Firstname} {Lastname},</p>
    <p>Vaše heslo bolo zmenené. Ak ste nevykonali túto operáciu Vy, kontaktujte prosím svojho manažéra.</p>
    <hr>
    <h5>*** Toto je automaticky generovaná správa, neodpovedajte prosím na túto správu ***</h5>
</body>

</html>