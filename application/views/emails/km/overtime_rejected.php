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
        សូមជំរាបមកដល់ {Firstname} {Lastname},<br />
        <br />
        បញ្ជីសំណើបន្ថែមម៉ោងរបស់លោកអ្នកត្រូវបានបដិសេធ។ ខាងក្រោមជាពត៏មានលម្អិត:
        <table border="0">
            <tr>
                <td>កាលបរិច្ឆេទ &nbsp;</td><td>{Date}</td>
            </tr>
            <tr>
                <td>រយៈពេល &nbsp;</td><td>{Duration}</td>
            </tr>
            <tr>
                <td>មូលហេតុ &nbsp;</td><td>{Cause}</td>
            </tr>
        </table>
    </body>
</html>
