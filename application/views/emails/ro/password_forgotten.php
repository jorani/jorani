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
        <p>Utilizați aceste date pentru <a href="{BaseURL}">conectarea la sistem</a> :</p>
        <table border="0">
            <tr>
                <td>Logare</td><td>{Login}</td>
            </tr>
            <tr>
                <td>Parolă</td><td>{Password}</td>
            </tr>            
        </table>
        <p>Odată conectat, poti schimba parola, urmând <a href="https://jorani.org/how-to-change-my-password.html" title="Link to documentation" target="_blank">acest ghid</a>.</p>
        <hr>
        <h5>*** Acesta este un mesaj generat automat, vă rog să nu răspundeți la acest mesaj ***</h5>
    </body>
</html>
