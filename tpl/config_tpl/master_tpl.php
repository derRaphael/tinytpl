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
    <div id="header" class="no-print">
        <div id="inner-header">&nbsp;</div>

<?=tpl('helper/small_info_top')?>

    </div>
    <div id="body">
        <div id="inner-body" class="box">
            <div style="background: url(/tinyAdmin/special/img/64/tinyTpl.png) no-repeat 5px -8px; min-height: 48px; *height: 48px; padding-left: 80px;">
                <h1>tinyTpl<?=(isset($this->data["HEADER"])?" - ".$this->data["HEADER"]:"");?></h1>
            </div>
            <hr />

{TINY_TPL_CONTENT}

<?=tpl('helper/msie_lt8')?>

        </div>
    </div>
    <div id="footer">
        <div id="inner-footer" class="no-print">&nbsp;</div>

<?=tpl('helper/small_info_bottom')?>

    </div>
</div>

<?=tpl('helper/main_js')?>

    </body>
</html>