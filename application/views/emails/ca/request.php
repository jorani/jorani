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
        {Firstname} {Lastname} ha demanat una absència. Consulta els <a href="{BaseUrl}leaves/requests/{LeaveId}">detalls</a>a sota:<br />
        <table border="0">
            <tr>
                <td>Des de &nbsp;</td><td>{StartDate}&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td>Fins &nbsp;</td><td>{EndDate}&nbsp;({EndDateType})</td>
            </tr>
            <tr>
                <td>Tipus &nbsp;</td><td>{Type}</td>
            </tr>
            <tr>
                <td>Duració &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Saldo &nbsp;</td><td>{Balance}</td>
            </tr>
            <tr>
                <td>Motiu &nbsp;</td><td>{Reason}</td>
            </tr>
            <tr>
              <td>Darrer comentari &nbsp;</td><td>{Comments}</td>
            </tr>
            <tr>
                <td><a href="{BaseUrl}requests/accept/{LeaveId}">Acceptar</a> &nbsp;</td>
                <td><a href="{BaseUrl}requests?rejected={LeaveId}">Rebutjar</a></td>
            </tr>
        </table>
        <br />
        Pots revisar el <a href="{BaseUrl}hr/counters/collaborators/{UserId}">saldo de permisos</a>  abans de validar la petició d'absència.
        <hr>
        <h5>*** Això és um missatge generat automàticament, si us plau no responguis a aquest missage ***</h5>
    </body>
</html>
