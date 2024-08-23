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
        {Firstname} {Lastname} cancelled a requested time off. See the <a href="{BaseUrl}leaves/{LeaveId}">details</a> below:<br />
        <table border="0">
            <tr>
                <td>From &nbsp;</td><td>{StartDate}&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td>To &nbsp;</td><td>{EndDate}&nbsp;({EndDateType})</td>
            </tr>
            <tr>
                <td>Type &nbsp;</td><td>{Type}</td>
            </tr>
            <tr>
                <td>Duration &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Balance &nbsp;</td><td>{Balance}</td>
            </tr>
            <tr>
                <td>Reason &nbsp;</td><td>{Reason}</td>
            </tr>
            <tr>
                <td>Last comment &nbsp;</td><td>{Comments}</td>
            </tr>
            <tr>
                <td><a href="{BaseUrl}requests/cancellation/accept/{LeaveId}">Confirm cancellation</a> &nbsp;</td><td><a href="{BaseUrl}requests?cancel_rejected={LeaveId}">Reject cancellation</a></td>
            </tr>
        </table>
        <br />
        You can check the <a href="{BaseUrl}hr/counters/collaborators/{UserId}">leave balance</a> before validating the leave request.
        <hr>
        <h5>*** This is an automatically generated message, please do not reply to this message ***</h5>
    </body>
</html>
