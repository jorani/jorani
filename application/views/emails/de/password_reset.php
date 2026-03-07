<?php
/**
 * Email template.You can change the content of this template
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/jorani/jorani
 * @since         0.1.0
 */
?>
<html lang="de">

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
    <p>Lieber {Firstname} {Lastname},</p>
    <p>Sollten Sie kein neues Passwort angefordert haben kontaktieren Sie bitte Ihren Administrator.</p>
    <hr>
    <h5>*** Dies ist eine automatisch generierte Nachricht; bitte antworten Sie nicht auf diese Nachricht ***</h5>
</body>

</html>