<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<html lang="pt">
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
        Caro(a) {Firstname} {Lastname}, <br />
        <br />
        Infelizmente o seu pedido foi rejeitado. Por favor contacte o seu gestor para mais informações.<br />
        <table border="0">
            <tr>
                <td>Data &nbsp;</td><td>{Date}</td>
            </tr>
            <tr>
                <td>Duração &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>Motivo &nbsp;</td><td>{Cause}</td>
            </tr>
        </table>
        <hr>
        <h5>*** Esta mensagem foi gerada automaticamente, por favor não responda a esta mensagem ***</h5>
    </body>
</html>
