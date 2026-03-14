<?php
/**
 * Email template.You can change the content of this template
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/jorani/jorani
 * @since         0.1.0
 */
?>
<html lang="pt">

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
    Por favor utilize estas credenciais para <a href="{BaseURL}">aceder ao sistema</a>:
    <table border="0">
        <tr>
            <td>Login</td>
            <td>{Login}</td>
        </tr>
        <tr>
            <td>Password</td>
            <td>{Password}</td>
        </tr>
    </table>
    Depois de entrar, pode alterar a password conforme explicado <a
        href="https://jorani.org/how-to-change-my-password.html" title="Link to documentation" target="_blank">aqui</a>.
    <hr>
    <h5>*** Esta mensagem foi gerada automaticamente, por favor não responda a esta mensagem ***</h5>
</body>

</html>