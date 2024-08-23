<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<html lang="it">
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
        {Firstname} {Lastname} richiede un congedo. Qui di seguito <a href="{BaseUrl}leaves/requests/{LeaveId}">i dettagli</a>:
        <table border="0">
            <tr>
                <td>Da &nbsp;</td><td>{StartDate}&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td>Per &nbsp;</td><td>{EndDate}&nbsp;({EndDateType})</td>
            </tr>
            <tr>
                <td>Tipo &nbsp;</td><td>{Type}</td>
            </tr>
            <tr>
                <td>Durata &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Equilibrio &nbsp;</td><td>{Balance}</td>
            </tr>
            <tr>
                <td>Motivo &nbsp;</td><td>{Reason}</td>
            </tr>
            <tr>
              <td>Last Comment &nbsp;</td><td>{Comments}</td>
            </tr>
            <tr>
                <td><a href="{BaseUrl}requests/accept/{LeaveId}">Accetta</a> &nbsp;</td>
                <td><a href="{BaseUrl}requests?rejected={LeaveId}">Rifiuta</a></td>
            </tr>
        </table>
        <br />
        È possibile controllare il <a href="{BaseUrl}hr/counters/collaborators/{UserId}">bilanciamento del congedo</a> prima di convalidare la richiesta di congedo.
        <hr>
        <h5>*** Questo è un messaggio generato automaticamente, si prega di non rispondere a questo messaggio ***</h5>
    </body>
</html>
