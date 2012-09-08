<?php
    $this->data["HEADER"] = "AdminMode";
?>
<h2>Manage Cache</h2>

<?php if( ! is_dir( $this->base . "/cache/" ) ): ?>

<p>This module shows some very basic usage statistics</p>

<div class="tiny-extra-info error">
    <h3>Fatal error.</h3>
    <p>The '$base/cache' folder does not exists. This module needs this folder writeable.</p>
</div>

<?php else: ?>

<?php

    $fn = $this->base . "/cache/";

    if ( array_key_exists('action',$_POST) && $_POST["action"] == "purge" )
    {

        $dir = new \DirectoryIterator( $fn );
        $ite = new \IteratorIterator($dir);
        $reg = new \RegexIterator($ite, '/^.+\.(php|css|js)$/i', \RegexIterator::GET_MATCH);

        foreach( $reg as $file )
        {
            if ( file_exists( $fn . $file[0] ) )
            {
                unlink( $fn . $file[0] );
            }
        }

?>
<p>All cached js, css, and php files were removed.</p>
<?php
    }
    else if ( array_key_exists('action',$_POST) && $_POST["action"] == "flush" )
    {

        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($fn),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($it as $file) {
            if (in_array($file->getBasename(), array('.', '..'))) {
                continue;
            } elseif ($file->isDir()) {
                rmdir($file->getPathname());
            } elseif ($file->isFile() || $file->isLink()) {
                unlink($file->getPathname());
            }
        }

?>
<p>The entire cache directory have been flushed. Any saved settings are gone.</p>
<?php

    } else {

?>
<p><form action="/tinyAdmin/admin/cache" method="post"><input type="hidden" name="action" value="purge" />Press <button type="submit">cleanup</button>, in order to flush all cached css, js and php files.</form></p>
<p><form action="/tinyAdmin/admin/cache" method="post"><input type="hidden" name="action" value="flush" />Press <button type="submit">flush all</button>, in order to flush everything - including hook settings and any other form of saved data.</form></p>
<?php

    }

?>
<?php endif; ?>