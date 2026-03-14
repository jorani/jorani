<h1>Sistema de gestão de férias e licenças</h1>

<p>Bem vindo a Jorani. Se é um colaborador, pode:</p>
<ul>
    <li>Consulte o seu <a href="<?php echo base_url();?>leaves/counters">saldo de férias</a>.</li>
    <li>Consulte a <a href="<?php echo base_url();?>leaves">lista de pedidos de férias que submeteu</a>.</li>
    <li>Solicite um <a href="<?php echo base_url();?>leaves/create">novo pedido</a>.</li>
</ul>

<br />

<p>Se é um gestor de colaborador(es), pode:</p>
<ul>
    <li>Valide <a href="<?php echo base_url();?>requests">pedidos de férias dirigidos a si</a>.</li>
    <?php if ($this->config->item('disable_overtime') == FALSE) { ?>
    <li>Valide <a href="<?php echo base_url();?>overtime">pedidos de licenças dirigidos a si</a>.</li>
    <?php } ?>
</ul>
