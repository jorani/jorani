<h1>Доступ запрещен</h1>

<p>Вы не можете совершать данные действия.</p>

<?php if($this->session->flashdata('msg')){ ?>
<p>  
  <?php echo $this->session->flashdata('msg'); ?>
</p>
<?php } ?>
