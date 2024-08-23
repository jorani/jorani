<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
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
        Kérlek használd ezeket a hitelesítő adatokat a <a href="{BaseURL}">rendszerbe történő bejelentkezéshez</a> :
        <table border="0">
            <tr>
                <td>Bejelentkezés</td><td>{Login}</td>
            </tr>
            <tr>
                <td>Jelszó</td><td>{Password}</td>
            </tr>
        </table>
        Miután csatlakoztál lehetőséged van jelszavad módosítására ahogy azt <a href="https://jorani.org/how-to-change-my-password.html" title="Link to documentation" target="_blank">itt</a> leírják.
        <hr>
        <h5>*** Ez egy automatikus üzenet, kérlek ne válaszolj rá ***</h5>
    </body>
</html>
