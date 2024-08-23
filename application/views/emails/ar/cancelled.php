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
        <p><a href="{BaseUrl}leaves/leaves/{LeaveId}">{Firstname} {Lastname} قد الغى طلب الإجازة. يرجى معاينة التفاصيل أدناه:</a></p><br />
        <table border="0">
            <tr>
                <td>من &nbsp;</td><td>{StartDate}&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td>لغاية &nbsp;</td><td>{EndDate}&nbsp;({EndDateType})</td>
            </tr>
            <tr>
                <td>النوع &nbsp;</td><td>{Type}</td>
            </tr>
            <tr>
                <td>المدة &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>الرصيد &nbsp;</td><td>{Balance}</td>
            </tr>
            <tr>
                <td>السبب &nbsp;</td><td>{Reason}</td>
            </tr>
        </table>
        <br />
        <hr>
        <h5>*** هذه الرسالة كتبت اوتوماتيكياً، يرجى عدم الإجابة عليها ***</h5>
    </body>
</html>
