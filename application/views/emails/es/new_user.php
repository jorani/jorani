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
    Bienvenido a Jorani {Firstname} {Lastname}. Por favor, use estas credenciales para <a href="{BaseURL}">acceder al
        sistema</a> :
    <table border="0">
        <tr>
            <td>Usuario</td>
            <td>{Login}</td>
        </tr>
        <tr>
            <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
                <td>Contraseña</td>
                <td>{Password}</td>
            <?php } else { ?>
                <td>Contraseña</td>
                <td><i>La contraseña que utiliza para abrir una sesión en su sistema operativo (Windows, Linux, etc.).</i>
                </td>
            <?php } ?>
        </tr>
    </table>
    <?php if ($this->config->item('ldap_enabled') == FALSE) { ?>
        Una vez conectado, puede cambiar su contraseña, pulsando <a href="https://jorani.org/how-to-change-my-password.html"
            title="Link to documentation" target="_blank">aqui</a>.
    <?php } ?>
</body>

</html>