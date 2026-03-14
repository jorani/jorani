<h1>Accès interdit</h1>

<p>Vous n'êtes pas autorisé à effectuer cette action.</p>

<?php if($this->session->flashdata('msg')){ ?>
<p>  
  <?php echo $this->session->flashdata('msg'); ?>
</p>
<?php } ?>
