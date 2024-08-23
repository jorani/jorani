<?php
/**
 * This view allows users to view a leave request in read-only mode
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>
<h2><?php echo lang('leaves_view_title');?><?php echo $leave['id']; if ($name != "") {?>&nbsp;<span class="muted">(<?php echo $name; ?>)</span><?php } ?></h2>

<div class="row">
  <div class="span6">

<div class="row-fluid">
    <div class="span12">

    <label for="startdate"><?php echo lang('leaves_view_field_start');?></label>
    <input type="text" name="startdate" value="<?php $date = new DateTime($leave['startdate']); echo $date->format(lang('global_date_format'));?>" readonly />
    <select name="startdatetype" readonly>
        <option selected><?php echo lang($leave['startdatetype']); ?></option>
    </select><br />

    <label for="enddate"><?php echo lang('leaves_view_field_end');?></label>
    <input type="text" name="enddate"  value="<?php $date = new DateTime($leave['enddate']); echo $date->format(lang('global_date_format'));?>" readonly />
    <select name="enddatetype" readonly>
        <option selected><?php echo lang($leave['enddatetype']); ?></option>
    </select><br />

    <label for="duration"><?php echo lang('leaves_view_field_duration');?></label>
    <input type="text" name="duration"  value="<?php echo $leave['duration']; ?>" readonly />

    <label for="type"><?php echo lang('leaves_view_field_type');?></label>
    <select name="type" readonly>
        <option selected><?php echo $leave['type_name']; ?></option>
    </select><br />

    <label for="cause"><?php echo lang('leaves_view_field_cause');?></label>
    <textarea name="cause" readonly><?php echo $leave['cause']; ?></textarea>

<?php $style= "dropdown-rejected";
switch ($leave['status']) {
    case LMS_PLANNED: $style= "dropdown-planned"; break;
    case LMS_REQUESTED: $style= "dropdown-requested"; break;
    case LMS_ACCEPTED: $style= "dropdown-accepted"; break;
    default: $style= "dropdown-rejected"; break;
} ?>
    <label for="status"><?php echo lang('leaves_view_field_status');?></label>
    <select name="status" class="<?php echo $style; ?>" readonly>
        <option selected><?php echo lang($leave['status_name']); ?></option>
    </select><br />
    <?php if($leave['status'] == LMS_PLANNED){ ?>
      <a href="<?php echo base_url();?>leaves/request/<?php echo $leave['id'] ?>/" class="btn btn-primary "><i class="mdi mdi-check"></i>&nbsp;<?php echo lang('Requested');?></a>
      <br/><br/>
    <?php } ?>
    <?php if ($leave['status'] == LMS_ACCEPTED) { ?>
      <a href="<?php echo base_url();?>leaves/cancellation/<?php echo $leave['id'] ?>" class="btn btn-primary"><i class="mdi mdi-undo"></i>&nbsp;<?php echo lang('Cancellation');?></a>
      <br/><br/>
    <?php } ?>
    <?php if ($leave['status'] == LMS_REQUESTED) { ?>
      <a href="<?php echo base_url();?>leaves/reminder/<?php echo $leave['id']; ?>" title="<?php echo lang('leaves_button_send_reminder');?>" class="btn btn-primary"><i class="mdi mdi-email"></i>&nbsp;<?php echo lang('leaves_button_send_reminder');?></a>
      <br/><br/>
    <?php } ?>

    <?php if (($leave['status'] == LMS_PLANNED) || ($is_hr)) { ?>
    <a href="<?php echo base_url();?>leaves/edit/<?php echo $leave['id'] ?>" class="btn btn-primary"><i class="mdi mdi-pencil"></i>&nbsp;<?php echo lang('leaves_view_button_edit');?></a>
    &nbsp;
    <?php } ?>
    <a href="<?php echo base_url() . $source; ?>" class="btn btn-primary"><i class="mdi mdi-arrow-left-bold"></i>&nbsp;<?php echo lang('leaves_view_button_back_list');?></a>

    </div>
</div>
</div>
<div class="span6">

  <h4><?php echo lang('leaves_comment_title');?></h4>
  <?php
  if(isset($leave["comments"])){

    echo "<div class='accordion' id='accordion'>";
    $i=1;
    foreach ($leave["comments"]->comments as $comments_item) {
      $date=new DateTime($comments_item->date);
      $dateFormat=$date->format(lang('global_date_format'));

      if($comments_item->type == "comment"){
        echo "<div class='accordion-group'>";
        echo "  <div class='accordion-heading'>";
        echo "    <a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion' href='#collapse$i'>";
        echo "      $dateFormat : $comments_item->author" . lang('leaves_comment_author_saying');
        echo "    </a>";
        echo "  </div>";
        echo "  <div id='collapse$i' class=\"accordion-body collapse $comments_item->in\">";
        echo "    <div class='accordion-inner'>";
        echo "      $comments_item->value";
        echo "    </div>";
        echo "  </div>";
        echo "</div>";
      }else if ($comments_item->type == "change"){
        echo "<div class='accordion-group'>";
        echo "  <div class='accordion-heading'>";
        echo "    <h6 class='accordion-toggle' data-toggle='collapse' data-parent='#accordion'>";
        echo "      $dateFormat : " . lang('leaves_comment_status_changed');
        switch ($comments_item->status_number) {
          case 1: echo "<span class='label'>" . lang($comments_item->status) . "</span>"; break;
          case 2: echo "<span class='label label-warning'>" . lang($comments_item->status) . "</span>"; break;
          case 3: echo "<span class='label label-success'>" . lang($comments_item->status) . "</span>"; break;
          default: echo "<span class='label label-important' style='background-color: #ff0000;'>" . lang($comments_item->status) . "</span>"; break;
        }
        echo "    </h6>";
        echo "  </div>";
        echo "</div>";
      }
      $i++;
    }
    echo " </div>";
  }
   ?>
   <?php
   $attributes = array('id' => 'frmLeaveNewCommentForm');
   if (isset($_GET['source'])) {
       echo form_open('leaves/' . $leave['id'] . '/comments/add?source=' . $_GET['source'], $attributes);
   } else {
       echo form_open('leaves/' . $leave['id'] . '/comments/add', $attributes);
   }
   ?>
   <form method="post"
   <label for="comment"><?php echo lang('leaves_comment_new_comment');?></label>
   <textarea name="comment" class="form-control" rows="5" style="min-width: 100%"></textarea>
   <button type="submit" class="btn btn-primary"><i class="mdi mdi-comment-plus-outline"></i>&nbsp;<?php echo lang('leaves_comment_send_comment');?></button>
   &nbsp;
 </form>
</div>
</div>
