<h1>سیستم مدیریت مرخصی و اضافه کاری</h1>

<p>به جورانی خوش آمدید. اگر شما یک کارمند استید، باید به این امکانات دسترسی داشته باشید:</p>
<ul>
    <li>دیدن <a href="<?php echo base_url();?>leaves/counters">باقیمانده مرخصی ها</a>.</li>
    <li>دیدن <a href="<?php echo base_url();?>leaves">لیست تمام درخواستهای مرخصی های تان</a>.</li>
    <li>درخواست یک <a href="<?php echo base_url();?>leaves/create">مرخصی جدید</a>.</li>
</ul>

<br />

<p>اگر شما مدیر کارمند(کارمندان) استید، باید به این امکانات دسترسی داشته باشید:</p>
<ul>
    <li>پذیرض/رد <a href="<?php echo base_url();?>requests">درخواست های مرخصی داده شده به شما</a>.</li>
    <?php if ($this->config->item('disable_overtime') == FALSE) { ?>
    <li>پذیرش/رد <a href="<?php echo base_url();?>overtime">درخواست های اضافه کاری داده شده به شما</a>.</li>
    <?php } ?>
</ul>
