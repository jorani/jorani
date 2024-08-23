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
        Willkommen bei Jorani, {Firstname} {Lastname}. Bitte nutzen Sie den angegebenen Usernamen und Passwort um sich <a href="{BaseURL}">ins System einzuloggen!</a> :
        <table border="0">
            <tr>
                <td>Login</td><td>{Login}</td>
            </tr>
            <tr>
                <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
                <td>Passwort</td><td>{Password}</td>
                <?php } else { ?>
                <td>Passwort</td><td><i>Das Passwort entspricht dem Anmeldepasswort Ihres Computers (Windows, Linux, etc.) bzw. einem anderen Ihrer genutzten Dienste (Exchange, Sharepoint, etc.).</i></td>
                <?php } ?>
            </tr>
        </table>
        <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
        <a href="https://jorani.org/how-to-change-my-password.html" title="Link to documentation" target="_blank">Hier</a> wird beschrieben können Sie Ihr Passwort ändern sobald Sie eingeloggt sind.
        <?php } ?>
        <hr>
        <h5>*** Dies ist eine automatisch generierte Nachricht; bitte antworten Sie nicht auf diese Nachricht ***</h5>
    </body>
</html>
