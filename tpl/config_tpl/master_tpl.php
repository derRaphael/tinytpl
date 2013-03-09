<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>tinyTpl<?=(isset($this->data["HEADER"])?" - ".$this->data["HEADER"]:"");?></title>
        <meta name="description" content="h5bp adapted to tinyTpl">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="/tinyAdmin/special/css/normalize.css">
        <link rel="stylesheet" href="/tinyAdmin/special/css/redmond/jquery-ui-1.8.23.custom.css">
        <link rel="stylesheet" href="/tinyAdmin/special/css/main.css">
        <script src="/tinyAdmin/special/js/vendor/modernizr-2.6.1.min.js"></script>
    </head>
    <body>
<div id="container">
    <dic id="inner-container">
        <div id="body">
            <div id="inner-body" class="box">
                <div style="background: url(/tinyAdmin/special/img/64/tinyTpl.png) no-repeat 5px -8px; min-height: 48px; *height: 48px; padding-left: 80px;">
                    <h1>tinyTpl<?=(isset($this->data["HEADER"])?" - ".$this->data["HEADER"]:"");?></h1>
                </div>
                <hr />

    <?php if ( $this->caching_available == false ): /* Added in v0.2.6 */ ?>
                <div class="tiny-extra-info error">
                    <h3>Attention: Adminmode is disabled!</h3>
                    <p>The '$base/cache' folder does not exists or is not accessible by php. You need to create a cache folder in tinyTpl's base folder</p>
                    <p>You may also execute the script <code style="color:#800;">$base/bin/inittinytpl.sh</code> from your server's terminal as superuser to fix that.</p>
                    <p>Read more about <a href="/tinyAdmin/doc/internals/folderstructure">filestructures</a> used in tinyTpl.</p>
                </div>
    <?php endif; ?>
    <?php if ( isset( $_SESSION['tinyAdmin_hookErrors'] ) && is_array( $_SESSION['tinyAdmin_hookErrors'] ) ): ?>
        <?php foreach( $_SESSION['tinyAdmin_hookErrors'] as $err ): ?>
            <div class="tiny-extra-info error">
                <h3>An error with a hook has happened!</h3>
                <p><?=$err?></p>
            </div>
        <?php endforeach; ?>
        <?php unset($_SESSION['tiny_tpl_hookErrors']) ?>
    <?php endif; ?>

{TINY_TPL_CONTENT}

<?=tpl('helper/msie_lt8')?>

            </div>
        </div>
        <div id="footer">
            <div id="inner-footer-wrap" class="no-print">
                <div id="inner-footer" class="no-print">&nbsp;</div>
<?=tpl('helper/small_info_bottom')?>
            </div>
        </div>
    </div>
</div>
    <div id="header" class="no-print">
        <div id="inner-header-wrap">
            <div id="inner-header" class="no-print">&nbsp;</div>
<?=tpl('helper/small_info_top')?>
        </div>
    </div>

<?=tpl('helper/main_js')?>

    </body>
</html>