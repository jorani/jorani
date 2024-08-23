<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<html lang="pt">
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
        <p>{Firstname} {Lastname} pretende cancelar um pedido. Veja <a href="{BaseUrl}leaves/{LeaveId}">detalhes</a> abaixo:</p>
        <table border="0">
            <tr>
                <td>De &nbsp;</td><td>{StartDate}&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td>A &nbsp;</td><td>{EndDate}&nbsp;({EndDateType})</td>
            </tr>
            <tr>
                <td>Tipo &nbsp;</td><td>{Type}</td>
            </tr>
            <tr>
                <td>Duração &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Saldo &nbsp;</td><td>{Balance}</td>
            </tr>
            <tr>
                <td>Motivo &nbsp;</td><td>{Reason}</td>
            </tr>
           <tr>
                <td>Último comentário &nbsp;</td><td>{Comments}</td>
            </tr>
            <tr>
                <td><a href="{BaseUrl}requests/cancellation/accept/{LeaveId}">Confirmar cancelamento</a> &nbsp;</td>
                <td><a href="{BaseUrl}requests?cancel_rejected={LeaveId}">Rejeitar cancelamento</a></td>
            </tr>
        </table>
        <br />
        <hr>
        <h5>*** Esta mensagem foi gerada automaticamente, por favor não responda a esta mensagem ***</h5>
    </body>
</html>
