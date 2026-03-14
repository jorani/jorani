<h1>Leave and Overtime Management System</h1>

<p>Witamy w Jorani. Jeżeli jesteś pracownikiem, możesz teraz :</p>
<ul>
    <li>Sprawdzić <a href="<?php echo base_url();?>leaves/counters">balans urlopu</a>.</li>
    <li>Zobaczyć <a href="<?php echo base_url();?>leaves">listę próśb o urlop które zgłosiłeś</a>.</li>
    <li>Zgłaszać <a href="<?php echo base_url();?>leaves/create">prośbę o urlop</a>.</li>
</ul>

<br />

<p>Jeżeli jesteś przełożonym innych pracowników, możesz:</p>
<ul>
    <li>Weryfikować <a href="<?php echo base_url();?>requests">przedstawione Ci wnioski urlopowe</a>.</li>
    <?php if ($this->config->item('disable_overtime') == FALSE) { ?>
    <li>Weryfikować <a href="<?php echo base_url();?>overtime">przedstawione Ci wnioski nadgodzin</a>.</li>
    <?php } ?>
</ul>
