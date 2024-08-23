<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<html lang="sk">
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
        Vitajte v Jorani {Firstname} {Lastname}. Použite prosím prihlasovacie údaje na <a href="{BaseURL}">prihlásenie do systému</a>:
        <table border="0">
            <tr>
                <td>Login</td><td>{Login}</td>
            </tr>
            <tr>
                <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
                <td>Heslo</td><td>{Password}</td>
                <?php } else { ?>
                <td>Heslo</td><td><i>Heslo, ktoré používate pre prihlásenie do operačného systému (Windows, Linux a pod.).</i></td>
                <?php } ?>
            </tr>            
        </table>
        <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
            Akonáhle ste prihlásený, môžete zmeniť svoje heslo, ako je vysvetlené <a href="https://jorani.org/how-to-change-my-password.html" title="Link to documentation" target="_blank">tu</a>.
        <?php } ?>
        <hr>
        <h5>*** Toto je automaticky generovaná správa, neodpovedajte prosím na túto správu ***</h5>
    </body>
</html>
