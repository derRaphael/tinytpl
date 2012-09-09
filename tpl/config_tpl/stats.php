<?php

    $fn = $this->base . "/cache/stats.data";

    if ( ( isset($_SESSION['tinyadmin_is_logged_in']) && $_SESSION['tinyadmin_is_logged_in'] === true )
      || file_exists( $fn )
    ) {
        /**
         * All good, we're either tinyadmin or configured to grant access to this file.
        **/
        // Handle POST requests to en- or disable public access for this file.
        if ( isset($_SESSION['tinyadmin_is_logged_in'])
          && $_SESSION['tinyadmin_is_logged_in'] === true
          && $this->method=="POST"
          && array_key_exists( "value", $_POST )
          && preg_match( '_^toggle$_', $_POST["value"] )
        ) {
            if ( file_exists( $fn ) && is_writable( $fn ) )
            {
                unlink( $fn );
                $msg = '["Global access revoked","You may configure stats to grant global access.","Click here, to grant access"]';
            } else {
                file_put_contents( $fn, "stats" );
                $msg = '["Global access granted","Currently stats has been configured to grant global access.","Click here, to revoke access"]';
            }
            die( $msg );
        }

    } else {
        /**
         * Since none of the mentioned conditions is met, we redirect to site's entry
        **/
        header('Location: /');
        die();
    }

    $loggingHookEnabled = false;

    foreach( $this->_store as $ObjectIndex => $Object )
    {
        if ( is_a( $Object, 'tinyTpl\hooks\tinySimplePageLog' ) )
        {
            $loggingHookEnabled = true;
            $logFacility = $this->_store->current();

            break;
        }
    }

    if ( $loggingHookEnabled == true
      && $this->method == "POST"
      && array_key_exists('value', $_POST)
      && array_key_exists('d', $_POST)
      && preg_match( '_\d+-\d+-\d+_', $_POST['d'] )
      && $_POST['value'] == "showDate"
    ) {

        $this->MASTER_TEMPLATE = null;

        $date = preg_replace( '_-_', '.', $_POST['d'] );

        if ( preg_match( '_^(\d+)-(\d+)-(\d+)$_', $_POST['d'], $m ) )
        {
            list(
                $stats_os, $stats_os_legend, $tmp_sessions,
                $stats_browser, $stats_browser_legend, $stats_browser_total,
                $stats_hits, $stats_data, $stats_url, $number_names,
                $stats_pi
            ) = $logFacility->buildDaylyStats($m[3], $m[2], $m[1]);

            // Buffer output to minify Javascript
            ob_start();
?>

    $("a.stats-select-date").text("<?=$date?>");

    var stats_os_values = <?=json_encode( array_values( $stats_os ) )?>,
        stats_os_legend = <?=json_encode( $stats_os_legend )?>,
        stats_os_info   = " <?=$date?> (from a total of <?=count($tmp_sessions)?> users)";
    var stats_browser_values = <?=json_encode( array_values( $stats_browser ) )?>,
        stats_browser_legend = <?=json_encode( $stats_browser_legend )?>,
        stats_browser_info   = " <?=$date?> (from a total of <?=$stats_browser_total?> users)";
    var stats_hits_values = <?=json_encode(array($stats_hits,$stats_pi))?>;
        stats_hits_total = " <?=$date?> (<?=count($stats_data)?> Hits and <?=count($tmp_sessions)?>  PIs)";
    var stats_url_values = <?=json_encode(array_values($stats_url))?>,
        stats_url_labels = <?=json_encode(array_keys($stats_url))?>,
        stats_url_labels2 = <?=json_encode(array_values($stats_url))?>,
        stats_url_info   = "Top <?=$number_names[count($stats_url)]?> visited URLs";

<?=tpl('helper/js/stats_raphael_js')?>
<?php
    $data = ob_get_contents();
    ob_end_clean();
?>
<?=mini_js( $data, array("use_cache"=>false) )?>
<?php
        }
        die();

    } else if ( $this->method == "POST" ) {

        $this->MASTER_TEMPLATE = null;
        die();
    }

    if ( isset($_SESSION['tinyadmin_is_logged_in']) && $_SESSION['tinyadmin_is_logged_in'] === true )
    {
        $this->data["HEADER"] = "AdminMode (".$_SERVER['SERVER_NAME'].")";
    } else {
        $this->data["HEADER"] = "Stats for ".$_SERVER['SERVER_NAME'];
    }

?>

<?php if ( $loggingHookEnabled != true ): ?>

<h2>tinySimplePageLog</h2>
<p>This module shows some very basic usage statistics</p>

<div class="tiny-extra-info error">
    <h2>We have a problem.</h2>
    <p>The <code style="color:#800">tinySimplePageLog</code> Hook is not enabled or missing. In order for this module to work, this hook needs to be enabled.</p>
    <p>Check <a style="color:#800" href="/tinyAdmin/admin/hooks">Hook Management</a> to enabled it.</p>
</div>

<?php else: ?>

<?php if( ! is_dir( $this->base . "/cache/" ) ): ?>

<h2>tinySimplePageLog</h2>
<p>This module shows some very basic usage statistics</p>

<div class="tiny-extra-info error">
    <h3>Fatal error.</h3>
    <p>The '$base/cache' folder does not exists. This module needs this folder writeable.</p>
</div>

<?php else: ?>

<h2>tinySimplePageLog for <a href="#" class="stats-select-date"><?=date('d.m.Y')?></a><input type="hidden" name="date" /></h2>
<p class="no-print">This module shows some very basic usage statistics. Click on the date to select a specific day.</p>

<?php if ( isset($_SESSION['tinyadmin_is_logged_in']) && $_SESSION['tinyadmin_is_logged_in'] === true && file_exists( $fn ) ): ?>
<p class="global-toggle no-print"><span>Currently stats has been configured to grant global access.</span> <a href="#" class="global-access-toggle">Click here, to revoke access</a></p>
<?php elseif ( isset($_SESSION['tinyadmin_is_logged_in']) && $_SESSION['tinyadmin_is_logged_in'] === true && ! file_exists( $fn ) ): ?>
<p class="global-toggle no-print"><span>You may configure stats to grant global access.</span> <a href="#" class="global-access-toggle">Click here, to grant access</a></p>
<?php endif; ?>

<div id="stats_holder"></div>

<div class="stats_menu no-print">
    <h3>Click to select a view</h3>
    <ul>
        <li><a href="#" class="stats-toggle enabled" data-name="hits">Hits/PIs for 24 Hours</a></li>
        <li><a href="#" class="stats-toggle disabled" data-name="urls">Topmost visited URLs</a></li>
        <li><a href="#" class="stats-toggle disabled" data-name="os">Operating Systems</a></li>
        <li><a href="#" class="stats-toggle disabled" data-name="browser">Browser Usage</a></li>
    </ul>
    <ul><li><a href="#" class="stats-show-all">Click to show all</a></li></ul>
</div>


<div class="clearfix"></div>

<div id="datepicker" class="no-print"></div>

<?php
    list(
        $stats_os, $stats_os_legend, $tmp_sessions,
        $stats_browser, $stats_browser_legend, $stats_browser_total,
        $stats_hits, $stats_data, $stats_url, $number_names, $stats_pi
    ) = $logFacility->buildDaylyStats();

?>
<script type="text/javascript">
    var stats_os_values = <?=json_encode( array_values( $stats_os ) )?>,
        stats_os_legend = <?=json_encode( $stats_os_legend )?>,
        stats_os_info   = " <?=date("d.m.Y")?> (from a total of <?=count($tmp_sessions)?> users)";
    var stats_browser_values = <?=json_encode( array_values( $stats_browser ) )?>,
        stats_browser_legend = <?=json_encode( $stats_browser_legend )?>,
        stats_browser_info   = " <?=date("d.m.Y")?> (from a total of <?=$stats_browser_total?> users)";
    var stats_hits_values = <?=json_encode(array($stats_hits,$stats_pi))?>;
        stats_hits_total = " <?=date("d.m.Y")?> (<?=count($stats_data)?> Hits and <?=count($tmp_sessions)?>  PIs)";
    var stats_url_values = <?=json_encode(array_values($stats_url))?>,
        stats_url_labels = <?=json_encode(array_keys($stats_url))?>,
        stats_url_labels2 = <?=json_encode(array_values($stats_url))?>,
        stats_url_info   = "Top <?=$number_names[count($stats_url)]?> visited URLs";
</script>

<?=trigger_tpl( "raphael_js_libs", "helper/js/raphael_js" )?>
<?=trigger_tpl( "js_stats", "helper/js/stats_action" )?>
<?php endif; ?>

<?php endif; ?>
