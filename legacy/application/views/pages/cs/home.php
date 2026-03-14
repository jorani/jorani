<h1>Opustit Systém Přesčasů</h1>

<p>Vítejte v Jorani. Pokud jste zaměstnanec, nyní můžete:</p>
<ul>
    <li>Zobrazit váš <a href="<?php echo base_url();?>leaves/counters">zůstatek dovolené</a>.</li>
    <li>Zobrazit <a href="<?php echo base_url();?>leaves">seznam žádosti o dovolené, které jste již zadal(a)</a>.</li>
    <li>Požádat <a href="<?php echo base_url();?>leaves/create">nová žádost o dovolenou</a>.</li>
</ul>

<br />

<p>Pokud jste manažerem ostatních zaměstnanců, nyní můžete:</p>
<ul>
    <li>Schválit <a href="<?php echo base_url();?>requests">požadavek o dovolenou vám byl přiřazen</a>.</li>
    <?php if ($this->config->item('disable_overtime') == FALSE) { ?>
    <li>Schválit <a href="<?php echo base_url();?>overtime">požadavek o přesčas vám byl přiřazen</a>.</li>
    <?php } ?>
</ul>
