<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
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
        <p>Χρησιμοποιήστε αυτά τα διαπιστευτήρια για <a href="{BaseURL}">σύνδεση στο σύστημα</a> :</p>
        <table border="0">
            <tr>
                <td>Σύνδεση</td><td>{Login}</td>
            </tr>
            <tr>
                <td>Κωδικός πρόσβασης</td><td>{Password}</td>
            </tr>            
        </table>
        <p>Μόλις συνδεθείτε, μπορείτε να αλλάξετε τον κωδικό πρόσβασης, όπως εξηγείται <a href="https://jorani.org/how-to-change-my-password.html" title="Link to documentation" target="_blank">εδώ</a>.</p>
        <hr>
        <h5>*** Αυτό είναι ένα μήνυμα που δημιουργήθηκε αυτόματα, παρακαλώ μην απαντήσετε σε αυτό το μήνυμα ***</h5>
    </body>
</html>
