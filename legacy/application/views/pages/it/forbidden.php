<h1>Accesso vietato</h1>

<p>Non sei autorizzato ad eseguire questa azione.</p>

<?php if($this->session->flashdata('msg')){ ?>
<p>  
  <?php echo $this->session->flashdata('msg'); ?>
</p>
<?php } ?>
