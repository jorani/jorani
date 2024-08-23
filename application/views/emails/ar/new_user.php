<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<html lang="ar">
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
        <p><a href="{BaseURL}">مرحباً {Firstname} {Lastname} بنظام جوراني. يرجى استخدام هذه المعلومات الغرض" الولوج الى النظام.</a></p>
        <table border="0">
            <tr>
                <td>الدخول</td><td>{Login}</td>
            </tr>
            <tr>
                <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
                <td>كلمة المرور</td><td>{Password}</td>
                <?php } else { ?>
                <td>كلمة المرور</td><td><i>كلمة المرور المستخدمة للولوج الى نظام التشغيل (ويندوز، لنكس، الخ).</i></td>
                <?php } ?>
            </tr>
        </table>
        <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
        <a href="https://jorani.org/how-to-change-my-password.html" title="Link to documentation" target="_blank">حال تأمين الإتصال، يمكنك تغيير كلمة المرور، وكما مبين هنا.</a>.
        <?php } ?>
        <hr>
        <h5>*** هذه الرسالة كتبت اوتوماتيكياً، يرجى عدم الإجابة عليها ***</h5>
    </body>
</html>
