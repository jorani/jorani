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
        <p>{Firstname} {Lastname} vous soumet une demande de télétravail fixe. Voici les détails :</p>
        <table>
            <tr>
                <td>Dates</td><td>{Dates}</td>
            </tr>
        </table>        
        <br />
        <a href="{BaseUrl}teleworkrequests/acceptall/{UserId}">Accepter tout</a>
        <br />
        <a href="{BaseUrl}teleworkrequests/rejectall/{UserId}">Refuser tout</a>
        <p>Vous pouvez vérifier <a href="{BaseUrl}teleworkrequests/campaignteleworks/requested/{UserId}">la liste de télétravail</a> afin de valider cette demande.</p>
        <hr>
        <h5>*** Ceci est un message généré automatiquement, veuillez ne pas répondre à ce message ***</h5>
    </body>
</html>
