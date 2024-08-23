<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<html lang="nl">
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
        {Firstname} {Lastname} heeft een aangevraagde afwezigheid geannuleerd. Zie de <a href="{BaseUrl}leaves/{LeaveId}">details</a> hieronder:<br />
        <table border="0">
            <tr>
                <td>Van &nbsp;</td><td>{StartDate}&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td>Tot &nbsp;</td><td>{EndDate}&nbsp;({EndDateType})</td>
            </tr>
            <tr>
                <td>Type &nbsp;</td><td>{Type}</td>
            </tr>
            <tr>
                <td>Duur &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Balans &nbsp;</td><td>{Balance}</td>
            </tr>
            <tr>
                <td>Reden &nbsp;</td><td>{Reason}</td>
            </tr>
            <tr>
                <td>Laatste opmerking &nbsp;</td><td>{Comments}</td>
            </tr>
            <tr>
                <td><a href="{BaseUrl}requests/cancellation/accept/{LeaveId}">Annulering goedkeuren</a> &nbsp;
                </td><td><a href="{BaseUrl}requests?cancel_rejected={LeaveId}">Annulering afwijzen</a></td>
            </tr>
        </table>
        <br />
        <hr>
        <h5>*** Dit is een automatisch gegenereerd bericht, antwoord alsjeblieft niet op dit bericht ***</h5>
    </body>
</html>
