<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<html lang="ca">
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
        Benvingut a Jorani {Firstname} {Lastname}.
        Si us plau, utilitza aquestes credencials per <a href="{BaseURL}">accedir al sistema</a> :
        <table border="0">
            <tr>
                <td>inici de sessió</td><td>{Login}</td>
            </tr>
            <tr>
                <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
                <td>Contrasenya</td><td>{Password}</td>
                <?php } else { ?>
                <td>Contrasenya</td><td><i>La contrasenya que utilitzes per obrir sessió al teu sistema operatiu (Windows, Linux, etc.).</i></td>
                <?php } ?>
            </tr>
        </table>
        <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
        Un cop connectat, pots canviar la teva contrasenya tal com s'explica <a href="https://jorani.org/how-to-change-my-password.html" title="Link to documentation" target="_blank">aquí</a>.
        <?php } ?>
        <hr>
        <h5>*** Això és um missatge generat automàticament, si us plau no responguis a aquest missage ***</h5>
    </body>
</html>
