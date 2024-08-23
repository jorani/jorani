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
        Beste {Firstname} {Lastname}, <br />
        <br />
        Het afwezigheidsverzoek dat u heeft ingediend is geaccepteerd. Hieronder vindt u de details :
        <table border="0">
            <tr>
                <td>Van &nbsp;</td><td>{StartDate}&nbsp;({StartDateType})</td>
            </tr>
            <tr>
                <td>Tot &nbsp;</td><td>{EndDate}&nbsp;({EndDateType})</td>
            </tr>
            <tr>
                <td>Type &nbsp;</td><td>{Type}</td>
            </tr>
            <tr>
                <td>Reden &nbsp;</td><td>{Cause}</td>
            </tr>
            <tr>
                <td>Laatste opmerking &nbsp;</td><td>{Comments}</td>
            </tr>
        </table>
        <hr>
        <h5>*** Dit is een automatisch gegenereerd bericht, antwoord alsjeblieft niet op dit bericht ***</h5>
    </body>
</html>
