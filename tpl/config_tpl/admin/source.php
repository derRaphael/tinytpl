<?php
    $this->data["HEADER"] = "AdminMode";

    if ( $this->method == "POST"
      && array_key_exists( 'value', $_POST )
      && $_POST['value'] == "source"
      && array_key_exists( 'n', $_POST )
      && trim($_POST['n']) != ""
      && ! preg_match( '_[^/0-9a-zA-Z\_.\-]_', $_POST['n'], $m)
      && file_exists( $this->base . $_POST['n'] )
      && is_readable( $this->base . $_POST['n'] )
    ) {
        $this->MASTER_TEMPLATE = null;

        $source = highlight_file( $this->base . $_POST['n'], true );

        die($source);

    } else if ( $this->method == "POST" ){

        die( '<code><span style="color:#000;"><span style="color:#f00;">404</span> File not found.</span></code>' );

    }

    $base = $this->base;

    $dir = new \RecursiveDirectoryIterator( $base,\FilesystemIterator::SKIP_DOTS );
    $ite = new \RecursiveIteratorIterator($dir,\RecursiveIteratorIterator::CHILD_FIRST);

    $files = array();

    foreach($ite as $path){
        if ( preg_match( '_\.(php|js|tpl|css|xml|txt)$_', $path->getPathname() )
          && ! preg_match( '_/cache/_', $path->getPathname() )
        ) {
            $files[] = preg_replace( '/'.preg_quote($base,'/').'/', '', $path->getPathname() )."\n";
        }
    }

?>
<h2>View Sourcecode: <span class="source-name"></span></h2>
<div class="source-container">
    <a href="#" class="source-hide-source">Hide Sourcecode</a>
    <div class="source"></div>
</div>
<div class="source-file-filter">Filter this list: <input type="text" name="filter" /></div>
<ul class="file-list" style="list-style-type:none;">
<?php foreach( $files as $filename): ?>
    <li><code><a href="#"><?=$filename?></a></code></li>
<?php endforeach; ?>
</ul>
<?=trigger_tpl('js_source','helper/js/source_action')?>