<h1>Система управління відпустками</h1>

<p>Ласкаво просимо до Jorani. Якщо ви працівник, ви можете:</p>
<ul>
    <li>Переглянути своє <a href="<?php echo base_url();?>leaves/counters">сальдо до відпустках</a>.</li>
    <li>Переглянути <a href="<?php echo base_url();?>leaves">список відправлених вами заяв на відпустку</a>.</li>
    <li>Створити <a href="<?php echo base_url();?>leaves/create">нову заяву на відпустку</a>.</li>
</ul>

<br />

<p>Якщо ви керівник, ви можете:</p>
<ul>
    <li>Переглянути <a href="<?php echo base_url();?>requests">список заяв на відпустки</a>, що очікують вашого розгляду.</li>
    <?php if ($this->config->item('disable_overtime') == FALSE) { ?>
    <li>Переглянути <a href="<?php echo base_url();?>overtime">список заяв на понаднормові</a>, що очікують вашого розгляду.</li>
    <?php } ?>
</ul>
