        <div class="small-info top">

<?php if( isset($_SESSION['tinyadmin_is_logged_in']) && $_SESSION['tinyadmin_is_logged_in'] === true ): ?>

            <div class="left"><button name="logout-btn">Exit Adminmode</button></div>
<?php
    $functions = array(
        "Show Stats" => array( "office-chart-area-stacked.png", "stats" ),
        "Manage Cache" => array( "edit-delete-3.png", "admin/cache" ),
        "Check Sanity" => array( "mail-mark-task.png", "admin/checks" ),
        "Manage Hooks" => array( "jigsaw_piece_t.png", "admin/hooks" ),
        "View Sourcecode" => array( "text-x-changelog.png", "admin/source" ),
        "Reset Password" => array( "document-decrypt.png", "admin/pass" ),
    );
?>
<div>
<?php foreach( array_reverse($functions) as $title => $data ): ?>
            <div class="right" style="width: 24px; heigth: 24px; margin: 0 .5em;">
                <a href="/tinyAdmin/<?=$data[1]?>"><img src="/tinyAdmin/special/img/24/<?=$data[0]?>" style="width: 24px; height: 24px; margin: 0; " title="<?=$title?>"/></a>
            </div>
<?php endforeach; ?>
            <div class="right" style="width: 24px; heigth: 24px; margin: 0 .5em;">
                <a href="/tinyAdmin/default"><img src="/tinyAdmin/special/img/24/tinyTpl.png" style="width: 24px; height: 24px; margin: 0; " title="Main Menu"/></a>
            </div>
</div>
<div class="clearfix"></div>

<?=trigger_tpl( "js_adminmode_btn", "helper/js/logged_in" )?>

<?php elseif( $this->dev_state === "dev" ): ?>
            <div class="left"><button name="login-btn">Enable Adminmode</button></div>

<?=trigger_tpl( "js_adminmode_btn", "helper/js/not_logged_in" )?>

<?php endif; ?>

        </div>
