<h1>отпуск системы управления</h1>

<p>Добро пожаловать на Jorani. Если вы работник, вы можете:</p>
<ul>
    <li>Просмотреть <a href="<?php echo base_url();?>leaves/counters">ваше сальдо по отпускам</a>.</li>
    <li>Просмотреть <a href="<?php echo base_url();?>leaves">список отправленных вами заявлений на отпуск</a>.</li>
    <li>Запросить <a href="<?php echo base_url();?>leaves/create">новый отпуск</a>.</li>
</ul>

<br />

<p>Если вы руководитель подразделения, вы можете:</p>
<ul>
    <li>Утвердить  <a href="<?php echo base_url();?>requests">заявления на отпуск, представленные на ваше рассмотрение</a>.</li>
    <?php if ($this->config->item('disable_overtime') == FALSE) { ?>
    <li>Утвердить <a href="<?php echo base_url();?>overtime">запросы на сверхурочные, представленные на ваше рассмотрение</a>.</li>
    <?php } ?>
</ul>
