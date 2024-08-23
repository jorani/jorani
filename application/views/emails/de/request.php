<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
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
        {Firstname} {Lastname} beantragt Urlaub. Hierzu die <a href="{BaseUrl}leaves/requests/{LeaveId}">Details</a> :
        <table border="0">
            <tr>
                <td>Von &nbsp;</td><td>{StartDate}&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td>Bis &nbsp;</td><td>{EndDate}&nbsp;({EndDateType})</td>
            </tr>
            <tr>
                <td>Art &nbsp;</td><td>{Type}</td>
            </tr>
            <tr>
                <td>Dauer &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Guthaben &nbsp;</td><td>{Balance}</td>
            </tr>
            <tr>
                <td>Begründung &nbsp;</td><td>{Reason}</td>
            </tr>
            <tr>
              <td>Neuster Kommentar &nbsp;</td><td>{Comments}</td>
            </tr>
            <tr>
                <td><a href="{BaseUrl}requests/accept/{LeaveId}">Akzeptieren</a> &nbsp;</td>
                <td><a href="{BaseUrl}requests?rejected={LeaveId}">Ablehnen</a></td>
            </tr>
        </table>
        <br />
        Über die <a href="{BaseUrl}hr/counters/collaborators/{UserId}">Urlaubsstatistik</a> können Sie sich vor Beantwortung der Anfrage einen Überblick verschaffen.
        <hr>
        <h5>*** Dies ist eine automatisch generierte Nachricht; bitte antworten Sie nicht auf diese Nachricht ***</h5>
    </body>
</html>
