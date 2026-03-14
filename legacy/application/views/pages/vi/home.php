<h1>Hệ thống quản lý nghỉ phép và làm thêm giờ</h1>

<p>Chào mừng đến với Jorani. Nếu bạn là nhân viên, ngay bây giờ bạn có thể:</p>
<ul>
    <li>Xem <a href="<?php echo base_url();?>leaves/counters">số ngày phép còn lại</a> của bạn.</li>
    <li>Xem <a href="<?php echo base_url();?>leaves">danh sách đề nghị nghỉ phép bạn đã gửi</a>.</li>
    <li>Yêu cầu <a href="<?php echo base_url();?>leaves/create">nghỉ phép mới</a>.</li>
</ul>

<br />

<p>Nếu bạn là quản lý trực tiếp của những nhân viên khác, ngay bây giờ bạn có thể:</p>
<ul>
    <li>Duyệt các <a href="<?php echo base_url();?>requests">đề nghị nghỉ phép</a> đã gửi đến bạn.</li>
    <?php if ($this->config->item('disable_overtime') == FALSE) { ?>
    <li>Duyệt <a href="<?php echo base_url();?>overtime">đề nghị làm thêm giờ đã gửi đến bạn</a>.</li>
    <?php } ?>
</ul>
