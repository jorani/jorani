<h1>Systém na správu dovoleniek, nadčasov a iných typov pracovného voľna.</h1>

<p>Vitajte v Jorani (<i class="mdi mdi-help-circle-outline"></i>)</p>

<p>Ak ste zamestnanec, teraz môžete:</p>
<ul>
    <li>Zobraziť <a href="<?php echo base_url();?>leaves/counters">zostatok voľna</a>.</li>
    <li>Zobraziť <a href="<?php echo base_url();?>leaves">zoznam už zadaných žiadostí o pracovné voľno</a>.</li>
    <li>Žiadosť o <a href="<?php echo base_url();?>leaves/create">nové pracovné voľno</a>.</li>
</ul>

<br />

<p>Ak ste schvaľovateľ pre iných zamestnancov, teraz môžete:</p>
<ul>
    <li>Schvaľovanie <a href="<?php echo base_url();?>requests">žiadostí o voľno Vám bolo postúpené</a>.</li>
    <?php if ($this->config->item('disable_overtime') == FALSE) { ?>
    <li>Schvaľovanie <a href="<?php echo base_url();?>overtime">žiadostí o nadčasy Vám bolo postúpené</a>.</li>
    <?php } ?>
</ul>
