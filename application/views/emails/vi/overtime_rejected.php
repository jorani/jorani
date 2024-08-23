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
        Gửi {Firstname} {Lastname}, <br />
        <br />
        Rất tiếc, thời gian làm thêm giờ của bạn đã bị từ chối. Vui lòng liên hệ với người quản lý của bạn để thảo luận vấn đề này.<br />
        <table border="0">
            <tr>
                <td>Ngày</td><td>{Date}</td>
            </tr>
            <tr>
                <td>Khoảng thời gian</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Lý do</td><td>{Cause}</td>
            </tr>
        </table>
        <hr>
        <h5>*** Đây là tin nhắn được tạo tự động, xin đừng trả lời tin nhắn này. ***</h5>
    </body>
</html>
