<h1>Přístup zamítnut</h1>

<p>Nejste oprávněn provést tuto akci.</p>

<?php if($this->session->flashdata('msg')){ ?>
<p>  
  <?php echo $this->session->flashdata('msg'); ?>
</p>
<?php } ?>
