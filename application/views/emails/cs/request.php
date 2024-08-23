<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<html lang="cs">
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
        {Firstname} {Lastname} požadované volno. Více <a href="{BaseUrl}leaves/requests/{LeaveId}">detailů</a> níže:<br />
        <table border="0">
            <tr>
                <td>Od &nbsp;</td><td>{StartDate}&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td>Komu &nbsp;</td><td>{EndDate}&nbsp;({EndDateType})</td>
            </tr>
            <tr>
                <td>Typ &nbsp;</td><td>{Type}</td>
            </tr>
            <tr>
                <td>Počet dnů &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Bilance &nbsp;</td><td>{Balance}</td>
            </tr>
            <tr>
                <td>Účel &nbsp;</td><td>{Reason}</td>
            </tr>
            <tr>
              <td>Last Comment &nbsp;</td><td>{Comments}</td>
            </tr>
            <tr>
                <td><a href="{BaseUrl}requests/accept/{LeaveId}">Přijmout</a> &nbsp;</td>
                <td><a href="{BaseUrl}requests?rejected={LeaveId}">Odmítnout</a></td>
            </tr>
        </table>
        <br />
        Můžete zkontrolovat <a href="{BaseUrl}hr/counters/collaborators/{UserId}">zůstatek dovolené</a> předtím odesláním ke schválení.
        <hr>
        <h5>*** Toto je náhodně vygenerována zpráva, prosím neodpovídejte na tuto zprávu ***</h5>
    </body>
</html>
