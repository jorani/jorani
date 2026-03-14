<h1>Gestire richieste di ferie</h1>

<p>Benvenuti a Jorani. Se sei un dipendente, si potrebbe ora : </p>
<ul>
    <li>Vedere i vostri  <a href="<?php echo base_url();?>leaves/counters">equilibrio congedo</a>.</li>
    <li>Vedi le <a href="<?php echo base_url();?>leaves">elenco delle richieste di ferie che avete presentato</a>.</li>
    <li>Richiesta <a href="<?php echo base_url();?>leaves/create">nuova  congedo</a>.</li>
</ul>

<br />

<p>Se il vostro sono il line manager di altro dipendente(s ), si potrebbe ora :</p>
<ul>
    <li>Convalida <a href="<?php echo base_url();?>requests">lasciano richieste presentate a voi</a>.</li>
    <?php if ($this->config->item('disable_overtime') == FALSE) { ?>
    <li>Convalida <a href="<?php echo base_url();?>overtime">le richieste di lavoro straordinario presentate a voi</a>.</li>
    <?php } ?>
</ul>
