<h1>Accés no permès</h1>

<p>No ten permís per realitzar aquesta acció.</p>

<?php if($this->session->flashdata('msg')){ ?>
<p>
  <?php echo $this->session->flashdata('msg'); ?>
</p>
<?php } ?>
