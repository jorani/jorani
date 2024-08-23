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
        {Firstname} {Lastname} požaduje pracovné voľno. Pozrite <a href="{BaseUrl}leaves/requests/{LeaveId}">detaily</a> nižšie:<br />
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
                <td>Trvanie &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Zostatok  &nbsp;</td><td>{Balance}</td>
            </tr>
            <tr>
                <td>Dôvod &nbsp;</td><td>{Reason}</td>
            </tr>
            <tr>
              <td>Posledná poznámka &nbsp;</td><td>{Comments}</td>
            </tr>
            <tr>
                <td><a href="{BaseUrl}requests/accept/{LeaveId}">Schváliť</a> &nbsp;</td>
                <td><a href="{BaseUrl}requests?rejected={LeaveId}">Zamietnuť</a></td>
            </tr>
        </table>
        <br />
        Môžete skontrolovať <a href="{BaseUrl}hr/counters/collaborators/{UserId}">zostatok</a> pred validáciou žiadosti.
        <hr>
        <h5>*** Toto je automaticky generovaná správa, neodpovedajte prosím na túto správu ***</h5>
    </body>
</html>
