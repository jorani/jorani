<h1>دسترسی غیر مجاز</h1>

<p>شما مجاز به انجام این عمل نیستید.</p>

<?php if($this->session->flashdata('msg')){ ?>
<p>  
  <?php echo $this->session->flashdata('msg'); ?>
</p>
<?php } ?>
