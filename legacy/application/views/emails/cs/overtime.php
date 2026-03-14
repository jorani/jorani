<?php
/**
 * Email template.You can change the content of this template
 * 
 * @license    http://opensource.org/licenses/MIT MIT
 * @link       https://github.com/jorani/jorani
 * @since      0.6.0
 */
?>
<html lang="cs">

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
    {Firstname} {Lastname} přesčas předložen. Prosím podívejte se na detaily níže:<br />
    <table border="0">
        <tr>
            <td>Datum &nbsp;</td>
            <td>{Date}</td>
        </tr>
        <tr>
            <td>Počet dnů &nbsp;</td>
            <td>{Duration}</td>
        </tr>
        <tr>
            <td>Účel &nbsp;</td>
            <td>{Cause}</td>
        </tr>
    </table>
    <a href="{UrlAccept}">Přijmout</a>
    <a href="{UrlReject}">Odmítnout</a>
    <hr>
    <h5>*** Toto je náhodně vygenerována zpráva, prosím neodpovídejte na tuto zprávu ***</h5>
</body>

</html>