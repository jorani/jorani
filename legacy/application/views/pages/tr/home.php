<h1>Leave and Overtime Management System</h1>

<p>Welcome in Jorani. If you are an employee, you could now:</p>
<ul>
    <li>See your <a href="<?php echo base_url();?>leaves/counters">leave balance</a>.</li>
    <li>See the <a href="<?php echo base_url();?>leaves">list of the leave requests you have submitted</a>.</li>
    <li>Request a <a href="<?php echo base_url();?>leaves/create">new leave</a>.</li>
</ul>

<br />

<p>If you are the line manager of other employee(s), you could now:</p>
<ul>
    <li>Validate <a href="<?php echo base_url();?>requests">leave requests submitted to you</a>.</li>
    <?php if ($this->config->item('disable_overtime') == FALSE) { ?>
    <li>Validate <a href="<?php echo base_url();?>overtime">overtime requests submitted to you</a>.</li>
    <?php } ?>
</ul>
