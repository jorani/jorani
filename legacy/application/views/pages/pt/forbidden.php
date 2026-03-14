<h1>Acesso não autorizado</h1>

<p>Não está autorizado a realizar esta ação.</p>

<?php if($this->session->flashdata('msg')){ ?>
<p>
  <?php echo $this->session->flashdata('msg'); ?>
</p>
<?php } ?>
