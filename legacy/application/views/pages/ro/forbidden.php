<h1>Acces restricționat</h1>

<p>Nu aveți dreptul necesar pentru această acțiune.</p>

<?php if($this->session->flashdata('msg')){ ?>
<p>
  <?php echo $this->session->flashdata('msg'); ?>
</p>
<?php } ?>
