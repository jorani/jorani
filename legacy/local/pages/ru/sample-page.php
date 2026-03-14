<?php
//This is a sample page showing how to create a custom content
//As you can see, we can access to loaded helpers as in other view

//If you want to overload the default homepage, simply rename this page "home.php"
//Don't forget to create a page for all the locales you are using into your company
?>
<h2><?php echo lang('Leave Management System');?></h2>

<p>This page is a sample.</p>

<!--We cannot use the POST method, but we can pass parameters by the URL//-->
<form action="<?php echo base_url();?>action" method="GET">
    <input type="text" name="txtContent" />
    <input type="submit" />
</form>
<br />
<a href="<?php echo base_url();?>custom-report">Try the report</a>
