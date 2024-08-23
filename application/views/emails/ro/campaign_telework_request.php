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
        <p>{Firstname} {Lastname} submits a telework request to you. Here are the details :</p>
        <table>
            <tr>
                <td>Dates</td><td>{Dates}</td>
            </tr>
        </table>        
        <br />
        <a href="{BaseUrl}teleworkrequests/acceptall/{UserId}">Accept all</a>
        <br />
        <a href="{BaseUrl}teleworkrequests/rejectall/{UserId}">Reject all</a>
        <p>You can check <a href="{BaseUrl}teleworkrequests/campaignteleworks/requested/{UserId}">the telework list</a> to validate this request.</p>
        <hr>
        <h5>*** This is an automatically generated message, please do not reply to this message ***</h5>
    </body>
</html>
