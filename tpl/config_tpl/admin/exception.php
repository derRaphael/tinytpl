<?php
    if ( count( $this->args ) >= 4 && $this->caching_available == true )
    {
        $this->MASTER_TEMPLATE == null;
        $matches = glob( $this->base . "/cache/exceptions/" . preg_replace( '_\.\.\._', '*', $this->args[3] ) . ".exception" );

        if ( is_array( $matches ) && count( $matches ) == 1 )
        {
            $fn = $matches[0];
            $x = preg_match( '_^'.preg_quote( $this->base . "/cache/exceptions/", "_" ).'[a-z0-9]{40}\.exception$_', $fn, $f );

            if ( $x && is_readable( $f[0] ) )
            {
                if ( $this->args[2] == "view" )
                {
                    $data = file_get_contents( $f[0] );
                    $data = preg_replace( '_(<h1.*?/h1>)_', 
                        '<div style="width:100%">'.
                            '<div class="right" style="width: 32px; heigth: 32px; margin: 0 .5em; float:right;">'.
                                '<a href="/tinyAdmin/admin/exception/delete/'.$this->args[3].'">'.
                                    '<img src="/tinyAdmin/special/img/32/edit-delete-2.png" style="width: 32px; height: 32px; margin: 0;" title="Delete this Exception" />'.
                                '</a>'.
                            '</div>'.
                            '\1'.
                        '</div>', $data );
                    $data = preg_replace( '_(<h3)_', '<h4 style="text-align:center;">- This Exception was recorded at '.date('F j, Y, h:i:s', filectime($f[0])).' -</h4><h3', $data );
                    echo $data;
                    // unlink( $matches[0] );
                } else if ( $this->args[2] == "delete" ) {
                    unlink( $f[0] );
                    header( 'Location: /tinyAdmin/admin/exception/list' );
                }
                die();
            }
        }
    } else if ( count( $this->args ) == 3 && $this->args[2] == "list" && $this->caching_available == true ) {
        $this->data["HEADER"] = "List of stored Exceptions";
        $list = glob( $this->base . "/cache/exceptions/*.exception" );
        if ( count($list) != 0 ) {
            foreach( $list as $filename )
            {
                $fn = basename( $filename, '.exception' );
                $pretty = substr($fn, 0, 5) . "..." . substr( $fn, -5 );
                $data = file_get_contents( $filename );
                $time = date('F j, Y, h:i:s', filectime($filename));
                if ( preg_match( '_<h3[^>]+>Exception: \'([^\']+)\'</h3><p>Msg: (.*?)</p>_', $data, $m ) )
                {
                    $exc = $m[1];
                    $title = $m[2];
                } else {
                    $exc = "Unknown Exception";
                    $title = "Unknown Title";
                }
?>
                <div style="clear:both; font.size: 0.9em;">
                    <div style="float:left; width: 9.5em; font-family: monospace; font-size: 1.2em;">
                        <a href="/tinyAdmin/admin/exception/view/<?=$pretty?>"><?=$pretty?></a>
                    </div>
                    <div style="float:left; overflow: hidden">
                        <a href="/tinyAdmin/admin/exception/view/<?=$pretty?>"><?=( strlen("$exc: $title") > 65 ? substr("$exc: $title", 0, 65) . "…" : "$exc: $title" )?></a>
                    </div>
                    <div class="right" style="width: 24px; heigth: 24px; margin: 0 .5em; float:right;">
                        <a href="/tinyAdmin/admin/exception/delete/<?=$pretty?>">
                            <img src="/tinyAdmin/special/img/24/edit-delete-2.png" style="width: 24px; height: 24px; margin: 0;" title="Delete this Exception" />
                        </a>
                    </div>
                    <div style="width:12em; float:right; margin:right: 1em;"><?=$time?></div>
                </div>
<?php
            }
        } else {
            echo "<p>Yay. There are no stored Exceptions available.</p>";
        }
    } else {
        $this->return_404();
        echo $this->html;
    }


?>