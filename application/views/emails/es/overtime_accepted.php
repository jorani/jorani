<?php
/**
 * Email template.You can change the content of this template
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/jorani/jorani
 * @since         0.1.0
 */
?>
<html lang="es">

<body>
    <h3>{Title}</h3>
    {Firstname} {Lastname}, <br />
    <br />
    Las horas extras que usted ha solicitado han sido aceptadas. A continuación, los detalles:
    <table border="0">
        <tr>
            <td>Fecha &nbsp;</td>
            <td>{Date}</td>
        </tr>
        <tr>
            <td>Duración &nbsp;</td>
            <td>{Duration}</td>
        </tr>
        <tr>
            <td>Motivo &nbsp;</td>
            <td>{Cause}</td>
        </tr>
    </table>
</body>

</html>