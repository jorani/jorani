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
        <p>{Firstname} {Lastname} voldria cancel·lar una petició d'absència. 
          Consulta <a href="{BaseUrl}leaves/{LeaveId}">detalls</a> a sota:</p>
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
                <td><a href="{BaseUrl}requests/cancellation/accept/{LeaveId}">Confirmar cancel·lació</a> &nbsp;</td>
                <td><a href="{BaseUrl}requests?cancel_rejected={LeaveId}">Refusar cancel·lació</a></td>
            </tr>
        </table>
        <br />
        <hr>
        <h5>*** Això és um missatge generat automàticament, si us plau no responguis a aquest missage ***</h5>
    </body>
</html>
