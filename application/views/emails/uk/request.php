<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<html lang="en">
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
        {Firstname} {Lastname} відправив заяву на відпустку. <a href="{BaseUrl}leaves/requests/{LeaveId}">Подробиці</a> нижче:
        <table border="0">
            <tr>
                <td>Від &nbsp;</td><td>{StartDate}&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td>До &nbsp;</td><td>{EndDate}&nbsp;({EndDateType})</td>
            </tr>
            <tr>
                <td>Тип &nbsp;</td><td>{Type}</td>
            </tr>
            <tr>
                <td>Тривалість &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Сальдо &nbsp;</td><td>{Balance}</td>
            </tr>
            <tr>
                <td>Причина &nbsp;</td><td>{Reason}</td>
            </tr>
            <tr>
              <td>Last Comment &nbsp;</td><td>{Comments}</td>
            </tr>
            <tr>
                <td><a href="{BaseUrl}requests/accept/{LeaveId}">Accept</a> &nbsp;</td>
                <td><a href="{BaseUrl}requests?rejected={LeaveId}">Reject</a></td>
            </tr>
        </table>
        <br />
        Ви можете перевірити <a href="{BaseUrl}hr/counters/collaborators/{UserId}">сальдо по відпустках</a> перед затвердженням заяви.
        <hr>
        <h5>*** Це повідомлення створене автоматично, будь ласка не відповідайте на нього ***</h5>
    </body>
</html>
