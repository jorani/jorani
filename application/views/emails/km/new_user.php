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
    សូមស្វាគមន៍មកកាន់ Jorani {Firstname} {Lastname} <a
        href="{BaseURL}">សូមប្រើប្រាស់ព័ត៌មានបញ្ជាក់អត្តសញ្ញាណទាំងនេះដើម្បីចូលទៅកាន់ប្រព័ន្ធ</a> :
    <table border="0">
        <tr>
            <td>ឈ្មោះ</td>
            <td>{Login}</td>
        </tr>
        <tr>
            <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
                <td>លេខសំងាត់</td>
                <td>{Password}</td>
            <?php } else { ?>
                <td>លេខសំងាត់</td>
                <td><i>The password you use in order to open a session on your operating system (Windows, Linux, etc.).</i>
                </td>
            <?php } ?>
        </tr>
    </table>
    <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
        <a href="https://jorani.org/how-to-change-my-password.html" title="តំណភ្ជាប់ទៅឯកសារ"
            target="_blank">នៅពេលដែលបានភ្ជាប់អ្នកអាចផ្លាស់ប្តូរពាក្យសម្ងាត់របស់អ្នកជាការពន្យល់នៅទីនេះ</a>.
    <?php } ?>
</body>

</html>