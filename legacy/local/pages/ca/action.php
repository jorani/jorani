<?php
//This is a sample page showing how to create a custom content
?>
<h2><?php echo lang('Leave Management System');?></h2>

<p>This page is the action page.</p>

Content passed by the form:
<?php 
//We can get access to all the framework, so you can do anything with the instance of the current controller
echo $this->input->get('txtContent');
?>
<br />

<!--We can link to any page, just use the base_url methos for safer URLs//-->
<a href="<?php echo base_url();?>sample-page">Back to the form</a><br />
<a href="<?php echo base_url();?>custom-report">Try the report</a>
