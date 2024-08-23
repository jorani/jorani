<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.1.0
 */
?>
<html lang="hu">
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
        Üdvözöljük a Jorani-ban {Lastname} {Firstname}. Használd a következő hitelesítő adatokat a <a href="{BaseURL}">rendszerbe történő bejelentkezéshez</a> :
        <table border="0">
            <tr>
                <td>Bejelentkezés</td><td>{Login}</td>
            </tr>
            <tr>
                <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
                <td>Jelszó</td><td>{Password}</td>
                <?php } else { ?>
                <td>Jelszó</td><td><i>Az a jelszó amelyet az operációs rendszer (Windows, Linux, stb.) bejelentkezéséhez használsz.</i></td>
                <?php } ?>
            </tr>
        </table>
        <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
        Miután csatlakoztál lehetőséged van jelszavad módosítására ahogy azt <a href="https://jorani.org/how-to-change-my-password.html" title="Link to documentation" target="_blank">itt</a> leírják.
        <?php } ?>
        <hr>
        <h5>*** Ez egy automatikus üzenet, kérlek ne válaszolj rá ***</h5>
    </body>
</html>
