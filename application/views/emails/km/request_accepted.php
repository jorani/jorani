<?php
/**
 * Email template.You can change the content of this template
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/jorani/jorani
 * @since         0.1.0
 */
?>
<html lang="km">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta charset="UTF-8">
</head>

<body>
    <h3>{Title}</h3>
    សូមជំរាបមកដល់ {Firstname} {Lastname},<br />
    <br />
    ការចាកចេញនេះអ្នកបានស្នើសុំត្រូវបានទទួលយកខាងក្រោមសេចក្ដីលម្អិត:
    <table border="0">
        <tr>
            <td>មកពី &nbsp;</td>
            <td>{StartDate}</td>
        </tr>
        <tr>
            <td>ទៅកាន់ &nbsp;</td>
            <td>{EndDate}</td>
        </tr>
        <tr>
            <td>Type &nbsp;</td>
            <td>{Type}</td>
        </tr>
        <tr>
            <td>Reason &nbsp;</td>
            <td>{Cause}</td>
        </tr>
        <tr>
            <td>Last comment &nbsp;</td>
            <td>{Comments}</td>
        </tr>
    </table>
</body>

</html>