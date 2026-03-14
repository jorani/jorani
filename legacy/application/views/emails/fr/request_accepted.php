<?php
/**
 * Email template.You can change the content of this template
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/jorani/jorani
 * @since         0.1.0
 */
?>
<html lang="fr">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta charset="UTF-8">
    <style>
        table {
            width: 50%;
            margin: 5px;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 20px;
        }

        h5 {
            color: red;
        }
    </style>
</head>

<body>
    <h3>{Title}</h3>
    <p>Bonjour {Firstname} {Lastname},</p>
    <p>La demande d'absence que vous avez soumise a été acceptée. Voici les détails :</p>
    <table>
        <tr>
            <td>Du &nbsp;</td>
            <td>{StartDate}&nbsp;({StartDateType})</td>
        </tr>
        <tr>
            <td>Au &nbsp;</td>
            <td>{EndDate}&nbsp;({EndDateType})</td>
        </tr>
        <tr>
            <td>Type &nbsp;</td>
            <td>{Type}</td>
        </tr>
        <tr>
            <td>Cause &nbsp;</td>
            <td>{Cause}</td>
        </tr>
        <tr>
            <td>Dernier commentaire &nbsp;</td>
            <td>{Comments}</td>
        </tr>
    </table>
    <hr>
    <h5>*** Ceci est un message généré automatiquement, veuillez ne pas répondre à ce message ***</h5>
</body>

</html>