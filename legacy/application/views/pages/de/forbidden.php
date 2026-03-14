<h1>Zugang verweigert</h1>

<p>Die gewünschte Operation konnte nicht ausgeführt werden.</p>

<?php if($this->session->flashdata('msg')){ ?>
<p>  
  <?php echo $this->session->flashdata('msg'); ?>
</p>
<?php } ?>
