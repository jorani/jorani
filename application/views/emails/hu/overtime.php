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
        {Firstname} {Lastname} submitted overtime worked. See the details below:<br />
        <table border="0">
            <tr>
                <td>Dátum &nbsp;</td><td>{Date}</td>
            </tr>
            <tr>
                <td>Időtartam &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Ok &nbsp;</td><td>{Cause}</td>
            </tr>
        </table>
        <a href="{UrlAccept}">Elfogad</a>
        <a href="{UrlReject}">Elutasít</a>
        <hr>
        <h5>*** Ez egy automatikus üzenet, kérlek ne válaszolj rá ***</h5>
    </body>
</html>
