<?php
/**
 * This partial view is included into views when we want to display a flash message.
 * 
 * @license    http://opensource.org/licenses/MIT MIT
 * @link       https://github.com/jorani/jorani
 * @since      0.1.0
 */
?>
<?php if ($this->session->flashdata('msg')) { ?>
  <div class="alert fade in" id="flashbox">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php echo $this->session->flashdata('msg'); ?>
  </div>

  <script type="text/javascript">
    //Flash message
    $(document).ready(function () {
      $("#flashbox").alert();
    });
  </script>
<?php } ?>