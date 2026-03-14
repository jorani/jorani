<h1>Access forbidden</h1>

<p>You are not allowed to perform this action.</p>

<?php if($this->session->flashdata('msg')){ ?>
<p>  
  <?php echo $this->session->flashdata('msg'); ?>
</p>
<?php } ?>
