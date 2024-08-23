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
        {Firstname} {Lastname} szabadság kérelmet adott be. A <a href="{BaseUrl}leaves/requests/{LeaveId}">részleteket</a> alább láthatod:<br />
        <table border="0">
            <tr>
                <td colspan="2">{StartDate}-tól&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td colspan="2">{EndDate}-ig&nbsp;({EndDateType})</td>
            </tr>
            <tr>
                <td>Típus &nbsp;</td><td>{Type}</td>
            </tr>
            <tr>
                <td>Időtartam &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Szabadnapok száma &nbsp;</td><td>{Balance}</td>
            </tr>
            <tr>
                <td>Ok &nbsp;</td><td>{Reason}</td>
            </tr>
            <tr>
              <td>Utolsó hozzászólás &nbsp;</td><td>{Comments}</td>
            </tr>
            <tr>
                <td><a href="{BaseUrl}requests/accept/{LeaveId}">Elfogad</a> &nbsp;</td>
                <td><a href="{BaseUrl}requests?rejected={LeaveId}">Elutasít</a></td>
            </tr>
        </table>
        <br />
        Lehetőséged van ellenőrizni a <a href="{BaseUrl}hr/counters/collaborators/{UserId}">szabadnapok számát</a> mielőtt engedélyezed vagy elutasítod a szabadság kérelmet.
        <hr>
        <h5>*** Ez egy automatikus üzenet, kérlek ne válaszolj rá ***</h5>
    </body>
</html>
