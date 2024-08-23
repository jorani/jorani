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
        {Firstname} {Lastname} отправил заявление на сверхурочные. Детали ниже:
        <table border="0">
            <tr>
                <td>Дата &nbsp;</td><td>{Date}</td>
            </tr>
            <tr>
                <td>Продолжительность &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Причина &nbsp;</td><td>{Cause}</td>
            </tr>
        </table>
        <a href="{UrlAccept}">Accept</a>
        <a href="{UrlReject}">Reject</a>
        <hr>
        <h5>*** Это сообщение создано автоматически, пожалуйста, не отвечайте на него ***</h5>
    </body>
</html>
