<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.6.1
 */
?>
<html lang="de">
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
        Sehr geehrter {Firstname} {Lastname}, <br />
        <br />
        <p>Ihre Absage wurde nicht akzeptiert, es wurde der ursprüngliche Status wiederhergestellt.</p>
        <p>Bitte wenden Sie sich an Ihren Vorgesetzten. </p>
        <table border="0">
            <tr>
                <td>Von &nbsp;</td><td>{StartDate}&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td>Bis &nbsp;</td><td>{EndDate}&nbsp;({EndDateType})</td>
            </tr>
            <tr>
                <td>Typ &nbsp;</td><td>{Type}</td>
            </tr>
            <tr>
                <td>Grund &nbsp;</td><td>{Cause}</td>
            </tr>
            <tr>
                <td>Neuster Kommentar &nbsp;</td><td>{Comments}</td>
            </tr>
        </table>
        <hr>
        <h5>*** Dies ist eine automatisch generierte Nachricht; bitte antworten Sie nicht auf diese Nachricht ***</h5>
    </body>
</html>
