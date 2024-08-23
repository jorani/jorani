<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.5
 */
?>
<html lang="vi">
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
        Chào mừng đến với Jorani {Firstname} {Lastname}. <a href="{BaseURL}">Vui lòng sử dụng những thông tin này để đăng nhập vào hệ thống:</a><br />
        <table border="0">
            <tr>
                <td>Đăng nhập</td><td>{Login}</td>
            </tr>
            <tr>
                <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
                <td>Mật khẩu</td><td>{Password}</td>
                <?php } else { ?>
                <td>Mật khẩu</td><td><i>Mật khẩu bạn sử dụng để mở phiên làm việc trên hệ điều hành của bạn (Windows, Linux…).</i></td>
                <?php } ?>
            </tr>            
        </table>
        <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
        Khi bạn đã kết nối, bạn có thể thay đổi mật khẩu của mình như được giải thích <a href="https://jorani.org/how-to-change-my-password.html" title="Link to documentation" target="_blank">tại đây</a>.
        <?php } ?>
        <hr>
        <h5>*** Đây là tin nhắn được tạo tự động, xin đừng trả lời tin nhắn này. ***</h5>
    </body>
</html>
