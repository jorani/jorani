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
    Dear {Firstname} {Lastname}, <br />
    <br />
    درخواست مرخصی شما رد شده است. مشخصات طبق ذیل میباشد :
    <table border="0">
        <tr>
            <td>از &nbsp;</td>
            <td>{StartDate}&nbsp;({StartDateType})</td>
        </tr>
        <tr>
            <td>تا &nbsp;</td>
            <td>{EndDate}&nbsp;({EndDateType})</td>
        </tr>
        <tr>
            <td>نوع &nbsp;</td>
            <td>{Type}</td>
        </tr>
        <tr>
            <td>دلیل &nbsp;</td>
            <td>{Cause}</td>
        </tr>
        <tr>
            <td>Last comment &nbsp;</td>
            <td>{Comments}</td>
        </tr>
    </table>
    <hr>
    <h5>*** این یک پیام اتوماتیک کامپیوتری است، لطفاً به این ایمیل پاسخ ندهید ***</h5>
</body>

</html>