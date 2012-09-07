
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/tinyAdmin/special/js/vendor/jquery-1.8.0.min.js"><\/script>')</script>

<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
<script>window.jQuery.ui || document.write('<script src="/tinyAdmin/special/js/vendor/jquery-ui-1.8.23.custom.min.js"><\/script>')</script>

<?=chk_tpl_trigger( 'raphael_js_libs' )?>

<script src="/tinyAdmin/special/js/plugins.js"></script>
<script src="/tinyAdmin/special/js/main.js"></script>

<script type="text/javascript">

$(document).ready(function(){

<?php
    // Buffer output to minify Javascript
    ob_start();
?>

// Add version info
$('.jq-version').text( window.jQuery ? jQuery.fn.jquery : "(Unknown Version)" )
$('.jq-ui-version').text( window.jQuery && window.jQuery.ui ? jQuery.ui.version : "(Unknown Version)" )

<?php if( has_trigger_set( 'raphael_js_libs' ) ): ?>
$('.raphael-version').text( window.Raphael ? Raphael.version : "(Unknown Version)");
$('.g-raphael-version').text('0.51');
<?php endif; ?>


<?=chk_tpl_trigger( 'js_adminmode_btn' )?>

<?=chk_tpl_trigger( 'js_hooks' )?>

<?=chk_tpl_trigger( 'js_stats' )?>

<?=chk_tpl_trigger( 'js_cache' )?>

<?=chk_tpl_trigger( 'js_source' )?>

<?php
    $data = ob_get_contents();
    ob_end_clean();
?>
<?=mini_js( $data, array("use_cache"=>false) )?>

});
</script>
