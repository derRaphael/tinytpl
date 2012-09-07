<?php

    if ( $this->args[0] == "doc" )
    {
        if ( preg_match( '_^(misc|internals)$_', $this->args[1] ) )
        {
            echo tpl( implode( "/", $this->args ) );
        } else {
            header('Location: /' . $this->tpl404 );
        }
    } else {
        header('Location: /' . $this->tpl404 );
    }

?>