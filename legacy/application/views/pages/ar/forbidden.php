<h1>الولوج محجو</h1>

<p>انت غير مصرح لإتخاذ هذا الإجراء.</p>

<?php if($this->session->flashdata('msg')){ ?>
<p>
  <?php echo $this->session->flashdata('msg'); ?>
</p>
<?php } ?>
