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
        Будь ласка використовуйте ці облікові дані для <a href="{BaseURL}">входу</a> в систему:
        <table border="0">
            <tr>
                <td>Ім'я користувача</td><td>{Login}</td>
            </tr>
            <tr>
                <td>Пароль</td><td>{Password}</td>
            </tr>            
        </table>
        Після входу ви маєте можливість змінити свій пароль відповідно <a href="https://jorani.org/how-to-change-my-password.html" title="Посилання на документацію" target="_blank">інструкції</a>.
        <hr>
        <h5>*** Це повідомлення створене автоматично, будь ласка не відповідайте на нього ***</h5>
    </body>
</html>
