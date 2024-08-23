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
        <p>Veuillez utiliser ces identifiants pour <a href="{BaseURL}">vous connecter à l'application</a> :</p>
        <table>
            <tr>
                <td>Identifiant</td><td>{Login}</td>
            </tr>
            <tr>
                <td>Mot de passe</td><td>{Password}</td>
            </tr>            
        </table>
        <p>Une fois connecté, vous pouvez modifier votre mot de passe comme expliqué <a href="https://jorani.org/how-to-change-my-password.html" title="Lien vers la documentation" target="_blank">dans cet article (en anglais)</a>.</p>
        <hr>
        <h5>*** Ceci est un message généré automatiquement, veuillez ne pas répondre à ce message ***</h5>
    </body>
</html>
