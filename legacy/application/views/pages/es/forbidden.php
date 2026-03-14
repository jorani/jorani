<h1>Acceso prohibido</h1>

<p>Usted no está autorizado para realizar esta acción.</p>

<?php if($this->session->flashdata('msg')){ ?>
<p>  
  <?php echo $this->session->flashdata('msg'); ?>
</p>
<?php } ?>
