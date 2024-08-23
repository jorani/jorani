<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<html lang="pl">
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
        {Firstname} {Lastname} prosi o urlop. Poniżej, <a href="{BaseUrl}leaves/requests/{LeaveId}">detale</a> :
        <table border="0">
            <tr>
                <td>Od &nbsp;</td><td>{StartDate}&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td>Do &nbsp;</td><td>{EndDate}&nbsp;({EndDateType})</td>
            </tr>
            <tr>
                <td>Typ &nbsp;</td><td>{Type}</td>
            </tr>
            <tr>
                <td>Czas trwania &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Balans urlopu &nbsp;</td><td>{Balance}</td>
            </tr>
            <tr>
                <td>Przyczyna &nbsp;</td><td>{Reason}</td>
            </tr>
            <tr>
              <td>Last Comment &nbsp;</td><td>{Comments}</td>
            </tr>
            <tr>
                <td><a href="{BaseUrl}requests/accept/{LeaveId}">Akceptować</a> &nbsp;</td>
                <td><a href="{BaseUrl}requests?rejected={LeaveId}">Odmówić</a></td>
            </tr>
        </table>
        <br />
        Możesz sprawdzić <a href="{BaseUrl}hr/counters/collaborators/{UserId}">balans urlopu</a> przed weryfikacją wniosku urlopowego..
        <hr>
        <h5>*** Ta wiadomość została wygenerowana automatycznie, prosimy nie odpowiadać na tę wiadomość ***</h5>
    </body>
</html>
