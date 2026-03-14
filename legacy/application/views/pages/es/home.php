<h1>Sistema de Gestión de permisos y horas extras</h1>

<p>Bienvenido a Jorani. Si usted es un empleado, usted podra:</p>
<ul>
    <li>Consulte su <a href="<?php echo base_url();?>leaves/counters">balance de permisos</a>.</li>
    <li>Ver el <a href="<?php echo base_url();?>leaves">listado de solicitudes de permisos</a>.</li>
    <li>Pedir un <a href="<?php echo base_url();?>leaves/create">nuevo permiso</a>.</li>
</ul>

<br />

<p>Si usted es responsable de otros empleados, usted podra:</p>
<ul>
    <li>Validar <a href="<?php echo base_url();?>requests">permisos.</a>.</li>
    <?php if ($this->config->item('disable_overtime') == FALSE) { ?>
    <li>Validar <a href="<?php echo base_url();?>overtime">horas extras</a>.</li>
    <?php } ?>
</ul>
