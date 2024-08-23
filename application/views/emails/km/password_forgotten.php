<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
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
        <a href="{BaseURL}">សូមប្រើប្រាស់ព័ត៌មានបញ្ជាក់អត្តសញ្ញាណទាំងនេះដើម្បីចូលទៅកាន់ប្រព័ន្ធ</a> :
        <table border="0">
            <tr>
                <td>ឈ្មោះ</td><td>{Login}</td>
            </tr>
            <tr>
                <td>លេខសំងាត់</td><td>{Password}</td>
            </tr>            
        </table>
        <a href="https://jorani.org/how-to-change-my-password.html" title="តំណភ្ជាប់ទៅឯកសារ" target="_blank">នៅពេលដែលបានភ្ជាប់អ្នកអាចផ្លាស់ប្តូរពាក្យសម្ងាត់របស់អ្នកជាការពន្យល់នៅទីនេះ</a>.
    </body>
</html>
