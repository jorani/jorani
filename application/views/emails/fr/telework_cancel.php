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
        <p>{Firstname} {Lastname} souhaiterait annuler sa demande de télétravail. Voici les <a href="{BaseUrl}teleworks/teleworks/{TeleworkId}">détails</a> :</p>
        <table>
            <tr>
                <td>Du &nbsp;</td><td>{StartDate}&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td>Au &nbsp;</td><td>{EndDate}&nbsp;({EndDateType})</td>
            </tr>
            <tr>
                <td>Durée &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Cause &nbsp;</td><td>{Reason}</td>
            </tr>
            <tr>
                <td>Dernier Commentaire &nbsp;</td><td>{Comments}</td>
            </tr>
            <tr>
                <td><a href="{BaseUrl}teleworkrequests/cancellation/accept/{TeleworkId}">Confirmer l'annulation</a> &nbsp;</td><td><a href="{BaseUrl}teleworkrequests?cancel_rejected={TeleworkId}">Refuser l'annulation</a></td>
            </tr>
        </table>
        <br />
        <p>Vous pouvez vérifier <a href="{BaseUrl}teleworkrequests/teleworks/{UserId}">l'état des télétravail</a> avant de valider cette demande.</p>
        <hr>
        <h5>*** Ceci est un message généré automatiquement, veuillez ne pas répondre à ce message ***</h5>
    </body>
</html>
