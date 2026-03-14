<h1>Prístup zamietnutý</h1>

<p>Nemáte oprávnenie na vykonanie tejto akcie.</p>

<?php if($this->session->flashdata('msg')){ ?>
<p>  
  <?php echo $this->session->flashdata('msg'); ?>
</p>
<?php } ?>
