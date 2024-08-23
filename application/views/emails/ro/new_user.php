<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<html lang="ro">
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
        <p>Bine ai venit la Jorani {Firstname} {Lastname}. 
        Utilizează aceste credențiale pentru <a href="{BaseURL}">conectarea la aplicație</a>:</p>
        <table border="0">
            <tr>
                <td>Logare</td><td>{Login}</td>
            </tr>
            <tr>
                <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
                <td>Parolă</td><td>{Password}</td>
                <?php } else { ?>
                <td>Parolă</td><td><i>Parola pe care o folosești pentru a deschide o sesiune a sistemului de operare (Windows, Linux, etc.).</i></td>
                <?php } ?>
            </tr>            
        </table>
        <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
        <p>Odată conectat, poti schimba parola, urmând <a href="https://jorani.org/how-to-change-my-password.html" title="Link to documentation" target="_blank">acest ghid</a>.</p>
        <?php } ?>
        <hr>
        <h5>*** Acesta este un mesaj generat automat, vă rog să nu răspundeți la acest mesaj ***</h5>
    </body>
</html>
