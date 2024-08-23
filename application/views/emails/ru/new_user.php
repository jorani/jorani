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
        
        <p>Добро пожаловать на Jorani {Firstname} {Lastname}. Пожалуйста, используйте эти данные <a href="{BaseURL}">для входа в систему</a>:</p>
        
        <table border="0">
            <tr>
                <td>Логин</td><td>{Login}</td>
            </tr>
            <tr>
                <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
                <td>Пароль</td><td>{Password}</td>
                <?php } else { ?>
                <td>Пароль</td><td><i>Пароль, который вы используете, начиная работу в операционной системе (Windows, Linus и т.д.)</i></td>
                <?php } ?>
            </tr>            
        </table>
        <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
        Вы можете изменить пароль согласно инструкции при входе в <a href="https://jorani.org/how-to-change-my-password.html" title="Link to documentation" target="_blank">систему</a>.
        <?php } ?>
        <hr>
        <h5>*** Это сообщение создано автоматически, пожалуйста, не отвечайте на него ***</h5>
    </body>
</html>
