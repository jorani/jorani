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
        <p>{Firstname} {Lastname} einen antrag auf telearbeit bei Ihnen einreicht. Hier sind die einzelheiten :</p>
        <table>
            <tr>
                <td>Daten</td><td>{Dates}</td>
            </tr>
        </table>        
        <br />
        <a href="{BaseUrl}teleworkrequests/acceptall/{UserId}">Alle akzeptieren</a>
        <br />
        <a href="{BaseUrl}teleworkrequests/rejectall/{UserId}">Alle ablehnen</a>
        <p>Sie können prüfen <a href="{BaseUrl}teleworkrequests/campaignteleworks/requested/{UserId}">die liste der telearbeit</a> um diese anfrage zu validieren.</p>
        <hr>
        <h5>*** Dies ist eine automatisch generierte Nachricht, bitte antworten Sie nicht auf diese Nachricht ***</h5>
    </body>
</html>
