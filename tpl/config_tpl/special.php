<?php
    $this->MASTER_TEMPLATE = null;

    $this->args[0] = $this->template_dir . $this->placeholder_dir . "assets";
    $filename = implode( '/', $this->args );

    if ( is_readable( $filename ) )
    {
        if ( preg_match( '_\.js_i', $filename ) )
        {
            $cType = "text/javascript; charset=utf-8";
            $data = mini_js( file_get_contents( $filename ) );
        }
        else if ( preg_match( '_\.css$_i', $filename ) )
        {
            $cType = "text/css; charset=utf-8";
            $data = mini_css( file_get_contents( $filename ) );
        }
        else
        {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $cType = finfo_file($finfo, $filename);
            $data = file_get_contents( $filename );
        }

        header( 'Content-type: ' . $cType, true );
        die( $data );
    }
?>