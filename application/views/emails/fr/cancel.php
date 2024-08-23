<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
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
        <p>{Firstname} {Lastname} souhaiterait annuler sa demande d'absence. Voici les <a href="{BaseUrl}leaves/{LeaveId}">détails</a> :</p>
        <table>
            <tr>
                <td>Du &nbsp;</td><td>{StartDate}&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td>Au &nbsp;</td><td>{EndDate}&nbsp;({EndDateType})</td>
            </tr>
            <tr>
                <td>Type &nbsp;</td><td>{Type}</td>
            </tr>
            <tr>
                <td>Durée &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Crédit &nbsp;</td><td>{Balance}</td>
            </tr>
            <tr>
                <td>Cause &nbsp;</td><td>{Reason}</td>
            </tr>
            <tr>
                <td>Dernier commentaire &nbsp;</td><td>{Comments}</td>
            </tr>
            <tr>
                <td><a href="{BaseUrl}requests/cancellation/accept/{LeaveId}">Confirmer l'annulation</a> &nbsp;</td><td><a href="{BaseUrl}requests?cancel_rejected={LeaveId}">Refuser l'annulation</a></td>
            </tr>
        </table>
        <br />
        <p>Vous pouvez vérifier <a href="{BaseUrl}hr/counters/collaborators/{UserId}">l'état des congés</a> avant de valider cette demande.</p>
        <hr>
        <h5>*** Ceci est un message généré automatiquement, veuillez ne pas répondre à ce message ***</h5>
    </body>
</html>
