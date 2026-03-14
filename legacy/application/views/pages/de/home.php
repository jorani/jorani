<h1>Urlaubs Management System</h1>

<p>Willkommen bei Jorani. Als Angestellter können Sie :</p>
<ul>
    <li>ihre <a href="<?php echo base_url();?>leaves/counters">Urlaubsstatistik</a> anzeigen lassen.</li>
    <li>eine <a href="<?php echo base_url();?>leaves">Liste der übermittlelten Abwesenheitsanfragen</a> sehen.</li>
    <li>einen <a href="<?php echo base_url();?>leaves/create">neuen Urlaubsantrag</a> stellen.</li>
</ul>

<br />

<p>Als eingetragener Vorgesetzter können Sie :</p>
<ul>
    <li><a href="<?php echo base_url();?>requests">Beantragte Urlaube ihrer Mitarbeiter</a> evaluieren.</li>
    <?php if ($this->config->item('disable_overtime') == FALSE) { ?>
    <li><a href="<?php echo base_url();?>overtime">Überstundenanträge ihrer Mitarbeiter</a> anzeigen lassen.</li>
    <?php } ?>
</ul>
