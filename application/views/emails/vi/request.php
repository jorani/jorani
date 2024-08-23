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
        {Firstname} {Lastname} đã yêu cầu nghỉ phép. <a href="{BaseUrl}leaves/requests/{LeaveId}">Xem chi tiết</a> bên dưới:<br />
        <table border="0">
            <tr>
                <td>Từ</td><td>{StartDate}&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td>Tới</td><td>{EndDate}&nbsp;({EndDateType})</td>
            </tr>
            <tr>
                <td>Loại</td><td>{Type}</td>
            </tr>
            <tr>
                <td>Khoảng thời gian</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Số ngày còn lại</td><td>{Balance}</td>
            </tr>
            <tr>
                <td>Lý do</td><td>{Reason}</td>
            </tr>
            <tr>
              <td>Last Comment &nbsp;</td><td>{Comments}</td>
            </tr>
            <tr>
                <td><a href="{BaseUrl}requests/accept/{LeaveId}">Chấp nhận</a> &nbsp;</td>
                <td><a href="{BaseUrl}requests?rejected={LeaveId}">Từ chối</a></td>
            </tr>
        </table>
        <br />
        Bạn có thể kiểm tra <a href="{BaseUrl}hr/counters/collaborators/{UserId}">số ngày nghỉ còn lại</a>  trước khi xác nhận yêu cầu nghỉ phép.
        <hr>
        <h5>*** Đây là tin nhắn được tạo tự động, xin đừng trả lời tin nhắn này. ***</h5>
    </body>
</html>
