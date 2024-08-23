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
        Por favor utiliza estas credenciales para <a href="{BaseURL}">acceder al sistema</a> :
        <table border="0">
            <tr>
                <td>Usuario</td><td>{Login}</td>
            </tr>
            <tr>
                <td>Contraseña</td><td>{Password}</td>
            </tr>            
        </table>
        na vez conectado, puede cambiar su contraseña, pulsando <a href="https://jorani.org/how-to-change-my-password.html" title="Link to documentation" target="_blank">aqui</a>.
    </body>
</html>
