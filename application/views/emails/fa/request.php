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
    {Firstname} {Lastname} درخواست مرخصی داده است. جزئیات در <a href="{BaseUrl}leaves/requests/{LeaveId}">اینجا</a> :
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
            <td>مدت زمان &nbsp;</td>
            <td>{Duration}</td>
        </tr>
        <tr>
            <td>باقیمانده &nbsp;</td>
            <td>{Balance}</td>
        </tr>
        <tr>
            <td>دلیل &nbsp;</td>
            <td>{Reason}</td>
        </tr>
        <tr>
            <td>Last Comment &nbsp;</td>
            <td>{Comments}</td>
        </tr>
        <tr>
            <td><a href="{BaseUrl}requests/accept/{LeaveId}">پذیرفتن</a> &nbsp;</td>
            <td><a href="{BaseUrl}requests?rejected={LeaveId}">رد</a></td>
        </tr>
    </table>
    <br />
    شما میتوانید <a href="{BaseUrl}hr/counters/collaborators/{UserId}">باقیمانده مرخصی ها</a> را قبل از پذیرفتن چک کنید.
    <hr>
    <h5>*** این یک پیام اتوماتیک کامپیوتری است، لطفاً به این ایمیل پاسخ ندهید ***</h5>
</body>

</html>