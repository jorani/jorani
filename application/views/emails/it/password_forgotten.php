<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<html lang="it">
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
        Si prega di utilizzare queste credenziali per <a href="{BaseURL}">accedere al sistema</a>:
        <table border="0">
            <tr>
                <td>Entra</td><td>{Login}</td>
            </tr>
            <tr>
                <td>Password</td><td>{Password}</td>
            </tr>            
        </table>
        Once connected, you can change your password, as explained <a href="https://jorani.org/how-to-change-my-password.html" title="Link to documentation" target="_blank">here</a>.
        <hr>
        <h5>*** Questo è un messaggio generato automaticamente, si prega di non rispondere a questo messaggio ***</h5>
    </body>
</html>