<h1>Dostęp zabroniony</h1>

<p>Nie masz pozwolenia na wykonanie tego działania.</p>

<?php if($this->session->flashdata('msg')){ ?>
<p>  
  <?php echo $this->session->flashdata('msg'); ?>
</p>
<?php } ?>
