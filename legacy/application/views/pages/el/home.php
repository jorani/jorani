<h1>Leave and Overtime Management System</h1>

<p>Καλώς ήλθατε στο Jorani. Εάν είστε υπάλληλος, θα μπορούσατε τώρα:</p>
<ul>
    <li>Δείτε το <a href="<?php echo base_url();?>leaves/counters">υπόλοιπό αδεία σας</a>.</li>
    <li>Δείτε τη λίστα <a href="<?php echo base_url();?>leaves">των αιτημάτων άδειας που υποβάλλατε</a>.</li>
    <li>Αίτημα <a href="<?php echo base_url();?>leaves/create">μια νέα άδεια</a>.</li>
</ul>

<br />

<p>Εάν είστε ο διευθυντής άλλων εργαζομένου(ων), θα μπορούσατε τώρα:</p>
<ul>
    <li>Επικυρώστε <a href="<?php echo base_url();?>requests">τα αιτήματα αδείας που σας υπερβάλλανε</a>.</li>
    <?php if ($this->config->item('disable_overtime') == FALSE) { ?>
    <li>Επικυρώστε <a href="<?php echo base_url();?>overtime">τις αιτήσεις υπερωριών που σας υπερβάλλανε</a>.</li>
    <?php } ?>
</ul>
