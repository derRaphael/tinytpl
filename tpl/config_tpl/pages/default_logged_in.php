<?php
    $functions = array(
        "Go Home" => array( "go-home-4.png", "../" ),
        "Show Stats" => array( "office-chart-area-stacked.png", "stats" ),
        "Manage Cache" => array( "edit-delete-3.png", "admin/cache" ),
        "Check Sanity" => array( "mail-mark-task.png", "admin/checks" ),
        "Manage Hooks" => array( "jigsaw_piece_t.png", "admin/hooks" ),
        "View Sourcecode" => array( "text-x-changelog.png", "admin/source" ),
        "Reset Password" => array( "document-decrypt.png", "admin/pass" ),
    );
?>
<div>
<?php foreach( $functions as $title => $data ): ?>
<div class="tinyadmin-default-box" style="">
    <a href="/tinyAdmin/<?=$data[1]?>"><img src="/tinyAdmin/special/img/128/<?=$data[0]?>" style="width: 128px; height: 128px; margin: 10px 11px; "/></a>
    <div class="tinyadmin-default-box-text" style=""><a href="/tinyAdmin/<?=$data[1]?>" style="color:#222;"><?=$title?></a></div>
</div>
<?php endforeach; ?>
<?php if ( $this->caching_available == true ): ?>
<div class="tinyadmin-default-box" style="">
    <a href="/tinyAdmin/admin/exception/list"><img src="/tinyAdmin/special/img/128/document-close-4.png" style="width: 128px; height: 128px; margin: 10px 11px; "/></a>
    <div class="tinyadmin-default-box-text" style=""><a href="/tinyAdmin/admin/exception/list" style="color:#222;">View Exceptions</a></div>
</div>
<?php endif; ?>
</div>
<div class="clearfix"></div>
