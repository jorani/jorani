<h1>Geen toegang</h1>

<p>Het is niet toegestaan om deze actie uit te voeren.</p>

<?php if($this->session->flashdata('msg')){ ?>
<p>  
  <?php echo $this->session->flashdata('msg'); ?>
</p>
<?php } ?>
