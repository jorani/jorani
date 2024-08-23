<?php
/**
 * Email template.You can change the content of this template
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<html lang="es">
    <body>
        <h3>{Title}</h3>
        {Firstname} {Lastname}, <br />
        <br />
        El permiso que usted ha solicitado ha sido aceptado. A continuación, el detalle :
        <table border="0">
            <tr>
                <td>Desde &nbsp;</td><td>{StartDate}</td>
            </tr>
            <tr>
                <td>Hasta &nbsp;</td><td>{EndDate}</td>
            </tr>
            <tr>
                <td>Tipo &nbsp;</td><td>{Type}</td>
            </tr>
            <tr>
                <td>Motivo &nbsp;</td><td>{Cause}</td>
            </tr>
            <tr>
                <td>Last comment &nbsp;</td><td>{Comments}</td>
            </tr>
        </table>
    </body>
</html>
