<h1>Sistem de gestionare a timpului liber și a orelor suplimentare</h1>

<p>Bine ai venit la Jorani. Dacă ești angajat, ai putea acum să:</p>
<ul>
    <li>Vezi <a href="<?php echo base_url();?>leaves/counters">evidența plecărilor</a>.</li>
    <li>Vezi <a href="<?php echo base_url();?>leaves">lista solicitărilor de timp liber trimise</a>.</li>
    <li>Solicită a <a href="<?php echo base_url();?>leaves/create">timp liber</a>.</li>
</ul>

<br />

<p>Dacă ești managerul altor angajați, ai putea acum să:</p>
<ul>
    <li>Validezi <a href="<?php echo base_url();?>requests">solicitările de timp liber trimise către tine</a>.</li>
    <?php if ($this->config->item('disable_overtime') == FALSE) { ?>
    <li>Validezi <a href="<?php echo base_url();?>overtime">solicitările de ore suplimentare trimise către tine</a>.</li>
    <?php } ?>
</ul>
