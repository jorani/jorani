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
        Benvolgut/da {Firstname} {Lastname}, <br />
        <br />
        Les hores extra que vas enviar s'han aprovat.<br />
        <table border="0">
            <tr>
                <td>Data &nbsp;</td><td>{Date}</td>
            </tr>
            <tr>
                <td>Duració &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Motiu &nbsp;</td><td>{Cause}</td>
            </tr>
        </table>
        <hr>
        <h5>*** Això és um missatge generat automàticament, si us plau no responguis a aquest missage ***</h5>
    </body>
</html>
