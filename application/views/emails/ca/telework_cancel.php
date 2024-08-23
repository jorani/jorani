<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2019 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<html lang="fr">
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
        <p>{Firstname} {Lastname} would like to cancel his or her telework request. Here are the <a href="{BaseUrl}teleworks/teleworks/{TeleworkId}">details</a> :</p>
        <table>
            <tr>
                <td>From &nbsp;</td><td>{StartDate}&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td>To &nbsp;</td><td>{EndDate}&nbsp;({EndDateType})</td>
            </tr>
            <tr>
                <td>Duration &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Reason &nbsp;</td><td>{Reason}</td>
            </tr>
            <tr>
                <td>Last comment &nbsp;</td><td>{Comments}</td>
            </tr>
            <tr>
                <td><a href="{BaseUrl}teleworkrequests/cancellation/accept/{TeleworkId}">Confirm the cancellation</a> &nbsp;</td><td><a href="{BaseUrl}teleworkrequests?cancel_rejected={TeleworkId}">Refuse the cancellation</a></td>
            </tr>
        </table>
        <br />
        <p>You can check <a href="{BaseUrl}teleworkrequests/teleworks/{UserId}">the telework status</a> before validating this request.</p>
        <hr>
        <h5>*** This is an automatically generated message, please do not reply to this message ***</h5>
    </body>
</html>
