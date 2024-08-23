<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<html lang="de">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta charset="UTF-8">
    <style>
        table {width:50%;margin:5px;border-collapse:collapse;}
        table, th, td {border: 1px solid black;}
        th, td {padding: 20px;}
        h5 {color:red;}
    </style>
</head>
    <body>
        <h3>{Title}</h3>
        Bitte benutzen Sie diese Anmeldedaten um sich <a href="{BaseURL}">ins System einzuloggen</a> :
        <table border="0">
            <tr>
                <td>Username</td><td>{Login}</td>
            </tr>
            <tr>
                <td>Passwort</td><td>{Password}</td>
            </tr>
        </table>
        <a href="https://jorani.org/how-to-change-my-password.html" title="Link to documentation" target="_blank">Hier</a> wird beschrieben können Sie Ihr Passwort ändern sobald Sie eingeloggt sind..
        <hr>
        <h5>*** Dies ist eine automatisch generierte Nachricht; bitte antworten Sie nicht auf diese Nachricht ***</h5>
    </body>
</html>
