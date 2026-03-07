<?php
/**
 * Email template.You can change the content of this template
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/jorani/jorani
 * @since         0.1.0
 */
?>
<html lang="tr">

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
    Sevgili {Firstname} {Lastname}, <br />
    <br />
    Gönderdiğiniz fazla mesai onaylandı.<br />
    <table border="0">
        <tr>
            <td>Tarih &nbsp;</td>
            <td>{Date}</td>
        </tr>
        <tr>
            <td>Süre &nbsp;</td>
            <td>{Duration}</td>
        </tr>
        <tr>
            <td>Neden &nbsp;</td>
            <td>{Cause}</td>
        </tr>
    </table>
    <hr>
    <h5>*** Bu otomatik olarak oluşturulmuş bir mesajdır, lütfen bu mesaja cevap vermeyin ***</h5>
</body>

</html>