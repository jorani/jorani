<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<html lang="tr">
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
        Jorani'ye hoşgeldiniz {Firstname} {Lastname}. Lütfen <a href="{BaseURL}">sisteme giriş yapmak</a> için bu kimlik bilgilerini kullanın:
        <table border="0">
            <tr>
                <td>Oturum aç</td><td>{Login}</td>
            </tr>
            <tr>
                <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
                <td>Şifre</td><td>{Password}</td>
                <?php } else { ?>
                <td>Şifre</td><td><i>İşletim sisteminizde bir oturum açmak için kullandığınız şifre (Windows, Linux, vb.).</i></td>
                <?php } ?>
            </tr>            
        </table>
        <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
        Bağlandıktan sonra, <a href="https://jorani.org/how-to-change-my-password.html" title="Link to documentation" target="_blank">burada</a> açıklandığı şekilde şifrenizi değiştirebilirsiniz.
        <?php } ?>
        <hr>
        <h5>*** Bu otomatik olarak oluşturulmuş bir mesajdır, lütfen bu mesaja cevap vermeyin ***</h5>
    </body>
</html>
