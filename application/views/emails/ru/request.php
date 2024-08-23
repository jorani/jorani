<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<html lang="ru">
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
        {Firstname} {Lastname} отправил заявление на отпуск. <a href="{BaseUrl}leaves/requests/{LeaveId}">Детали</a> ниже:
        <table border="0">
            <tr>
                <td>От &nbsp;</td><td>{StartDate}&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td>До &nbsp;</td><td>{EndDate}&nbsp;({EndDateType})</td>
            </tr>
            <tr>
                <td>Тип &nbsp;</td><td>{Type}</td>
            </tr>
            <tr>
                <td>Продолжительность &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>сальдо по отпускам &nbsp;</td><td>{Balance}</td>
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
        Вы можете <a href="{BaseUrl}hr/counters/collaborators/{UserId}">проверить сальдо по отпускам прежде</a>, чем утверждать заявление на отпуск..
        <hr>
        <h5>*** Это сообщение создано автоматически, пожалуйста, не отвечайте на него ***</h5>
    </body>
</html>
