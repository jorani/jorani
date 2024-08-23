<?php 
/**
 * This view displays the not found error when the broken link is related to a wrong business object.
 * For example, when the user tries to display a leave request that has been deleted.
 * In opposition to a missing route, we should display the menu and footer.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.4
 */
?>

<div class="row-fluid">
    <div class="span12">
        <center>
            <span style="font-size: 86px; line-height: 2em; color: #bd362f;">
                <i class="mdi mdi-alert"></i>
            </span>
            <strong><?php echo lang('global_msg_not_found'); ?></strong>
        </center>
    </div>
</div>
