<?php

    if ( $this->method == "POST"
      && $this->isAjax === true
      && array_key_exists( "value", $_POST )
      && trim( $_POST["value"] ) != ""
    ) {

        $this->MASTER_TEMPLATE = null;
        $fn = $this->base . "/cache/hooks";
        $hook = $_POST["value"];
        $result = null;

        header( 'Content-type: text/javascript; charset=utf-8', true );

        if ( file_exists( "$fn/$hook.class.php" ) ) {

            $result = rename( "$fn/$hook.class.php", "$fn/$hook.class.php.available" );

        } else if ( file_exists( "$fn/$hook.class.php.available" ) ) {

            $result = rename( "$fn/$hook.class.php.available", "$fn/$hook.class.php" );
        }

        if ( $result === true )
        {
            die( 'hookToggle("'.trim( $_POST["value"] ).'");' );

        } else if ( $result === false ) {

            die( '$("<div class=\"tiny-extra-info error\" style=\"display:none\"><h3>'.
                $hook.
                ' toggling error.</h3><p>The desired action did not take place. An er'.
                'ror occured. Most likey this happened due to wrong file permissions.'.
                '</p></div>").insertAfter("h2.topic");$(".tiny-extra-info").fadeIn()'
            );

        } else {

            die( '$("<div class=\"tiny-extra-info error\" style=\"display:none\"><h3>'.
                'Unknown error.</h3><p>An unknown error has happened. That\'s all we '.
                'know.</p></div>").insertAfter("h2.topic");$(".tiny-extra-info").fadeIn()'
            );

        }

    }

    $this->data["HEADER"] = "AdminMode";

    $fn = $this->base . "/cache/";
?>
<h2 class="topic">Toggle Hooks</h2>
<?php if( is_dir( $fn ) ): ?>
<?php

    $fn = $this->base . "/cache/";
    $info = array();

    if ( ! is_dir( $fn . "hooks" ) )
    {
        // Get cache folder fileperms
        $cFilePerms = fileperms( $fn );
        mkdir( "$fn/hooks", $cFilePerms, true );
        $info[] = "Created directory /cache/hooks";
    }

    // Link all found hooks into the cache-hooks-available folder
    $dir = new \RecursiveDirectoryIterator( $this->base . "/lib/hooks" );
    $ite = new \RecursiveIteratorIterator($dir);
    $reg = new \RegexIterator($ite, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

    foreach( $reg as $observerHook )
    {
        $basename = basename( $observerHook[0] );

        $targetHooknameEnabled   = $fn . "hooks/" . $basename . "";
        $targetHooknameAvailable = $fn . "hooks/" . $basename . ".available";

        if (
            ! file_exists( $targetHooknameEnabled ) && ! is_link( $targetHooknameEnabled )
            && ! file_exists( $targetHooknameAvailable ) && ! is_link( $targetHooknameAvailable )
        ) {
            symlink( $observerHook[0], $targetHooknameAvailable );
            $info[] = "Found <code>$basename</code> and linked it in /cache/hooks";
        }
    }

?>
<?php if ( count( $info ) != 0 ): ?>
<div class="tiny-extra-info">
    <h3>Additional Information</h3>
    <ul>
<?php foreach($info as $text): ?>
        <li><?=$text?></li>
<?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<?php
    // enlist all found hooks from /cache/hooks/available
    $dir = new \RecursiveDirectoryIterator( $this->base . "/cache/hooks" );
    $ite = new \RecursiveIteratorIterator($dir);
    $reg = new \RegexIterator($ite, '/^.+\.php(\.available)?$/i', \RecursiveRegexIterator::GET_MATCH);

    $hooks = array();

    foreach( $reg as $observerHook )
    {
        if ( file_exists( $observerHook[0] ) && is_readable( $observerHook[0] ) )
        {
            preg_match( '_(?<=/\*\* ABSTRACT:).*(?=\*\*/)_sm', file_get_contents( $observerHook[0] ), $match );

            $hooks[ basename( $observerHook[0] ) ] = array(
                "info" => $match[0],
                "enabled" => ( preg_match( '_\.php$_', basename( $observerHook[0] ) ) ? true : false )
            );
        }
    }

?>

<p>Click at a hook (the entire line in the table) to toggle the hook state.</p>

<table class="hookinfo">

    <thead>
        <tr>
            <th></th>
            <th scope="col">Info</th>
            <th scope="col">Version</th>
            <th scope="col">Author</th>
            <th scope="col">Licence</th>
            <th scope="col">Status</th>
            <th></th>
        </tr>
    </thead>

    <tfoot>
        <tr>
            <th></th>
            <th scope="col" colspan="5">Description</th>
        </tr>
    </tfoot>

    <tbody>

<?php foreach($hooks as $hookName => $hookData):?>

<?php $hookName = preg_replace('_\.class\.php(\.available)?$_','',$hookName); ?>

        <tr class="hook hook-<?=($hookData['enabled']==true?"en":"dis")?>abled hook-<?=$hookName?>" data-id="<?=$hookName?>">
            <th class="alpha" scope="row" rowspan="2"><?=$hookName?></th>
            <td class="info"></td>
            <td class="version"></td>
            <td class="author"></td>
            <td class="licence"></td>
            <td class="status"><?=($hookData['enabled']==true?"en":"dis")?>abled</td>
            <th class="omega" scope="row" rowspan="2"><div class="<?=($hookData['enabled']==true?"en":"dis")?>abled"></div></th>
        </tr>
        <tr class="hookinfo hook-<?=($hookData['enabled']==true?"en":"dis")?>abled hook-<?=$hookName?>" style="display:none;" data-id="<?=$hookName?>">
            <td colspan="5">
                <?=$hookData["info"]?>
            </td>
        </tr>

<?php endforeach;?>

    </tbody>

</table>

<?=trigger_tpl("js_hooks","helper/js/hook_action")?>
<?php else: ?>
<div class="tiny-extra-info error">
    <h3>Fatal error.</h3>
    <p>The '$base/cache' folder does not exists. This module needs this folder writeable.</p>
</div>
<?php endif; ?>
