<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<html lang="nl">
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
        {Firstname} {Lastname} vraagt overuren aan. Hieronder de details :
        <table border="0">
            <tr>
                <td>Datum&nbsp;</td><td>{Date}</td>
            </tr>
            <tr>
                <td>Duur &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Reden &nbsp;</td><td>{Cause}</td>
            </tr>
        </table>
        <a href="{UrlAccept}">Accepteren</a>
        <a href="{UrlReject}">Afwijzen</a>
        <br />
        <hr>
        <h5>*** Dit is een automatisch gegenereerd bericht, antwoord alsjeblieft niet op dit bericht ***</h5>
    </body>
</html>
