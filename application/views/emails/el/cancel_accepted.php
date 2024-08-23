<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.6.1
 */
?>
<html lang="el">
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
        Αγαπητέ / η {Firstname} {Lastname}, <br />
        <br />
        <p>Το αίτημά σας ακύρωσης έγινε αποδεκτό και η αίτηση άδειας ακυρώθηκε.</p>
        <table border="0">
            <tr>
                <td>Από &nbsp;</td><td>{StartDate}&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td>Προς &nbsp;</td><td>{EndDate}&nbsp;({EndDateType})</td>
            </tr>
            <tr>
                <td>Πληκτρολογήστε &nbsp;</td><td>{Type}</td>
            </tr>
            <tr>
                <td>Αιτία &nbsp;</td><td>{Cause}</td>
            </tr>
            <tr>
                <td>Τελευταίο σχόλιο &nbsp;</td><td>{Comments}</td>
            </tr>
        </table>
        <hr>
        <h5>*** Αυτό είναι ένα μήνυμα που δημιουργήθηκε αυτόματα, παρακαλώ μην απαντήσετε σε αυτό το μήνυμα ***</h5>
    </body>
</html>
