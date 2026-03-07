<?php
/**
 * Email template.You can change the content of this template
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/jorani/jorani
 * @since         0.1.0
 */
?>
<html lang="ar">

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
    <p> عزيزي/عزيزت {Firstname} {Lastname},</p>
    <p>تم تغيير كلمة المرور الخاصة بنظام جوراني. اذا لم تقم بهذا الإجراء، يرجى "الإتصال بالمدير.</p>
    <hr>
    <h5>*** هذه الرسالة كتبت اوتوماتيكياً، يرجى عدم الإجابة عليها ***</h5>
</body>

</html>