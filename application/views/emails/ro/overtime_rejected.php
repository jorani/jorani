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
        <p>Dragă {Firstname} {Lastname},</p>

        <p>Din păcate, timpul suplimentar trimis a fost respins. Contactează managerul pentru a discuta această problemă.</p>
        
        <table border="0">
            <tr>
                <td>Data &nbsp;</td><td>{Date}</td>
            </tr>
            <tr>
                <td>Durata &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Motiv &nbsp;</td><td>{Cause}</td>
            </tr>
        </table>
        <hr>
        <h5>*** Acesta este un mesaj generat automat, vă rog să nu răspundeți la acest mesaj ***</h5>
    </body>
</html>
