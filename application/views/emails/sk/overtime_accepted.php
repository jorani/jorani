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
        Vážená/ý {Firstname} {Lastname}, <br />
        <br />
        <p>Nadčas, ktorý ste predložil/a bol schválený.</p>
        <table border="0">
            <tr>
                <td>Dátum &nbsp;</td><td>{Date}</td>
            </tr>
            <tr>
                <td>Trvanie &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Dôvod &nbsp;</td><td>{Cause}</td>
            </tr>
        </table>
        <hr>
        <h5>*** Toto je automaticky generovaná správa, neodpovedajte prosím na túto správu ***</h5>
    </body>
</html>
