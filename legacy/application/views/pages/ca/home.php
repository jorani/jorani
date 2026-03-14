<h1>Sistema de gestió d'absències i hores extra</h1>

<p>Benvingut a Jorani. Si ets un empleat, pots:</p>
<ul>
    <li>Mira el teu <a href="<?php echo base_url();?>leaves/counters">balanç d'absències</a>.</li>
    <li>Mira la <a href="<?php echo base_url();?>leaves">llista de peticions d'absència que has enviat</a>.</li>
    <li>Sol·licita una <a href="<?php echo base_url();?>leaves/create">nova absència</a>.</li>
</ul>

<br />

<p>Si ets el responsable directe de altres empleats, pots:</p>
<ul>
    <li>Validar <a href="<?php echo base_url();?>requests">peticions d'absència que has rebut</a>.</li>
    <?php if ($this->config->item('disable_overtime') == FALSE) { ?>
    <li>Validar <a href="<?php echo base_url();?>overtime">peticions d'hores extra que has rebut</a>.</li>
    <?php } ?>
</ul>
