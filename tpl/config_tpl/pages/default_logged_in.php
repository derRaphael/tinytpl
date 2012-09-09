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
<?php foreach( $functions as $title => $data ): ?>
<div style="float:left;width: 150px; heigth: 160px; background: rgba(128,128,160,.25); border: 1px solid #666; padding: 10px; margin: .5em;">
    <a href="/tinyAdmin/<?=$data[1]?>"><img src="/tinyAdmin/special/img/128/<?=$data[0]?>" style="width: 128px; height: 128px; margin: 10px 11px; "/></a>
    <div style="border: 1px solid #888; background: #ccc; text-align: center;margin-top:10px;"><a href="/tinyAdmin/<?=$data[1]?>" style="color:#222;"><?=$title?></a></div>
</div>
<?php endforeach; ?>
</div>
<div class="clearfix"></div>
