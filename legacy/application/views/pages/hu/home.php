<h1>Szabadság és Túlóra kezelő rendszer</h1>

<p>Üdvözöl a Jorani. Ha munkavállaló vagy, most már lehetőséged van :</p>
<ul>
    <li>Megtekintheted a <a href="<?php echo base_url();?>leaves/counters">szabadnapok számát</a>.</li>
    <li>Megtekintheted a <a href="<?php echo base_url();?>leaves">beküldött szabadság kérelmeid listáját</a>.</li>
    <li>Kérelmezhetsz <a href="<?php echo base_url();?>leaves/create">új szabadnapot</a>.</li>
</ul>

<br />

<p>Ha más alkalmazottak vezetője vagy, akkor lehetőséged van :</p>
<ul>
    <li>Jóváhagyhatod a <a href="<?php echo base_url();?>requests">beküldött szabadnap kérelmeket</a>.</li>
    <?php if ($this->config->item('disable_overtime') == FALSE) { ?>
    <li>Jóváhagyhatod a  <a href="<?php echo base_url();?>overtime">beküldött túlóra kérelmeket</a>.</li>
    <?php } ?>
</ul>
