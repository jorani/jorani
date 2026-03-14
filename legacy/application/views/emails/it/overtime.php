<?php
/**
 * Email template.You can change the content of this template
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/jorani/jorani
 * @since         0.1.0
 */
?>
<html lang="it">

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
    {Firstname} {Lastname} richiede un lavoro straordinario. Qui di seguito, i dettagli:
    <table border="0">
        <tr>
            <td>Data &nbsp;</td>
            <td>{Date}</td>
        </tr>
        <tr>
            <td>Durata &nbsp;</td>
            <td>{Duration}</td>
        </tr>
        <tr>
            <td>Motivo &nbsp;</td>
            <td>{Cause}</td>
        </tr>
    </table>
    <a href="{UrlAccept}">Accetta</a>
    <a href="{UrlReject}">Rifiuta</a>
    <hr>
    <h5>*** Questo è un messaggio generato automaticamente, si prega di non rispondere a questo messaggio ***</h5>
</body>

</html>