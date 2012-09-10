<?php

    $annotationHookEnabled = false;

    foreach( $this->_store as $ObjectIndex => $Object )
    {
        if ( is_a( $Object, 'tinyTpl\hooks\tinyPageAnnotations' ) )
        {
            $annotationHookEnabled = true;
            $annotationFacility = $this->_store->current();

            break;
        }
    }

    //
    if ( isset($_SESSION['tinyadmin_is_logged_in'])
      && $_SESSION['tinyadmin_is_logged_in'] === true
      && $annotationHookEnabled == true
      && $this->isAjax
      && $this->method == "POST"
      && array_key_exists( 'value', $_POST )
      && array_key_exists( 'data', $_POST )
      && array_key_exists( 'page', $_POST )
      && $_POST["value"] == "update"
      && trim( $_POST["data"] ) != ""
      && json_decode( $_POST["data"] ) != null
      && trim( $_POST["page"] ) != ""
      && ! preg_match( '_(\.\.|\?)_', base64_decode( $_POST["page"] ) )
    ) {
        $annotationFacility->saveAnnotation( json_decode( $_POST["data"] ), base64_decode( $_POST["page"] ) );
        die();
    }
?>