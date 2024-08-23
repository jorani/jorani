<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<html lang="km">
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
        <meta charset="UTF-8">
    </head>
    <body>
        <h3>{Title}</h3>
        {Firstname} {Lastname} <a href="{BaseUrl}leaves/requests/{LeaveId}">ស្នើសុំចាកចេញមួយខាងក្រោមសេចក្ដីលម្អិត</a> :
        <table border="0">
            <tr>
                <td>មកពី &nbsp;</td><td>{StartDate}&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td>ទៅកាន់ &nbsp;</td><td>{EndDate}&nbsp;({EndDateType})</td>
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
              <td>Last Comment &nbsp;</td><td>{Comments}</td>
            </tr>
            <tr>
                <td><a href="{BaseUrl}requests/accept/{LeaveId}">ទទួលយកបាន</a> &nbsp;</td>
                <td><a href="{BaseUrl}requests?rejected={LeaveId}">បដិសេធចោល</a></td>
            </tr>
        </table>
        <br />
        You can check the <a href="{BaseUrl}hr/counters/collaborators/{UserId}">leave balance</a> before validating the leave request.
    </body>
</html>
