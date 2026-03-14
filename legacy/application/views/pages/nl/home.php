<h1>Afwezigheid en overuren registratie systeem</h1>

<p>Welkom bij Jorani. Als u een werknemer bent kunt u:</p>
<ul>
    <li>uw <a href="<?php echo base_url();?>leaves/counters">vakantiedagen saldo</a> zien.</li>
    <li>Het <a href="<?php echo base_url();?>leaves">overzicht</a> van door U ingediende afwezigheidsmeldingen.</li>
    <li>Een <a href="<?php echo base_url();?>leaves/create">nieuw afwezigheidsverzoek</a> indienen.</li>
</ul>

<br />

Als U een manager bent van andere medewerkers kunt u:
<ul>
  <li>Valideren van bij U ingediende <a href="<?php echo base_url();?>requests">afwezigheidsverzoeken</a>.</li>
  <?php if ($this->config->item('disable_overtime') == FALSE) { ?>
  <li>Valideren van bij U ingediende <a href="<?php echo base_url();?>overtime">overuren meldingen</a>.</li>
  <?php } ?>
</ul>
