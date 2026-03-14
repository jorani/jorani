<h1>Доступ заборонений</h1>

<p>Ви не маєте прав для виконання цієї операції.</p>

<?php if($this->session->flashdata('msg')){ ?>
<p>  
  <?php echo $this->session->flashdata('msg'); ?>
</p>
<?php } ?>
