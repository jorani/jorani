<?php
/**
 * This view displays a simplified authorization form for OAtuh2 authorization.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.6.0
 */
?>

<div class="container-fluid">
    <div class="row">
        <div class="span12">
            <h3><?php echo $title;?></h3>
        </div>
    </div>
    <div class="row">
        <div class="span12">
            <img width="100" src="<?php echo base_url();?>assets/images/logo_simple.png">
            &nbsp;<i class="mdi mdi-twitter-retweet"></i>&nbsp;
            <img width="100" src="<?php echo $iconPath;?>">
        </div>
    </div>
    <div class="row"><div class="span12">&nbsp;</div></div>
    <div class="row">
        <div class="span12">
            <form method="POST" action="<?php echo base_url();?>api/authorization/authorize">
                <button name="authorized" type="submit" value="yes" class="btn btn-primary"><?php echo lang('Yes');?></button>
                <button name="authorized" type="submit" value="no" class="btn btn-primary"><?php echo lang('No');?></button>
                <input name="state" type="hidden" value="<?php echo $state;?>">
                <input name="response_type" type="hidden" value="<?php echo $responseType;?>">
                <input name="redirect_uri" type="hidden" value="<?php echo $redirectUri;?>">
                <input name="client_id" type="hidden" value="<?php echo $clientId;?>">
            </form>
        </div>
    </div>
</div>
    </body>
</html>
