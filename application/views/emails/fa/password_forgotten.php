<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2015 Benjamin BALET
 * @license    http://opensource.org/licenses/MIT MIT
 * @link       https://github.com/jorani/jorani
 * @since      0.4.5
 */
?>
<html lang="fa">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta charset="UTF-8">
    <style>
        table {
            width: 50%;
            margin: 5px;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 20px;
        }

        h5 {
            color: red;
        }
    </style>
</head>

<body>
    <h3>{Title}</h3>
    لطفا این مشخصات را <a href="{BaseURL}">برای ورود به سیستم وارد نمایید</a> :
    <table border="0">
        <tr>
            <td>ورود</td>
            <td>{Login}</td>
        </tr>
        <tr>
            <td>رمز</td>
            <td>{Password}</td>
        </tr>
    </table>
    پس از اتصال میتوانید رمزتان را تغییر دهید، طوریکه توضیح داده شده در <a
        href="https://jorani.org/how-to-change-my-password.html" title="اتصال به راهنما" target="_blank">اینجا</a>.
    <hr>
    <h5>*** این یک پیام اتوماتیک کامپیوتری است، لطفاً به این ایمیل پاسخ ندهید ***</h5>
</body>

</html>