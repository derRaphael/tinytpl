<?php
/*
 * tinyPageAnnotations.class.php
 *
 * Copyright 2012 derRaphael <software@itholic.org>
 *
 * Version 0.1
 *
 * tinyPageAnnotations shows how to enable hooks (observer pattern) with the
 * tinyTpl singleton object.
 *
 * tinyPageAnnotations allows to create annotations on a per page basis.
 * they will be only accessible when adminMode is enabled
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are
 * met:
 *
 * * Redistributions of source code must retain the above copyright
 *   notice, this list of conditions and the following disclaimer.
 * * Redistributions in binary form must reproduce the above
 *   copyright notice, this list of conditions and the following disclaimer
 *   in the documentation and/or other materials provided with the
 *   distribution.
 * * Neither the name of the  nor the names of its
 *   contributors may be used to endorse or promote products derived from
 *   this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

namespace tinyTpl\hooks
{
    class tinyPageAnnotations implements tinyObserver
    {
        public $TARGETS = array(
                    'tinyTpl\tiny::__init',
                    'tinyTpl\tiny::html',
                );

        const VERSION = "0.1";

        private function __customConstruct( \tinyTpl\tiny $TINY )
        {

            if ( array_key_exists('tinyadmin_is_logged_in',$_SESSION) && $_SESSION['tinyadmin_is_logged_in'] == true )
            {
                $CF  = $TINY->base . "/cache";
                $this->CF  = "$CF/annotations";

                if ( is_dir( $CF ) && is_writable( $CF ) && ! is_dir( $this->CF ) )
                {
                    $cFilePerms = fileperms( $CF );
                    mkdir( $this->CF, $cFilePerms, true );
                }

                if ( is_dir( $CF ) && is_writable( $CF ) && ! is_dir( "$CF/annotations" ) ) {
                    throw new Exception("No Annotation Folder");
                }
            }

        }

        public function trigger( $TINY, $STAGE, $TARGET )
        {

            switch ( $STAGE )
            {
                case 255:

                    if ( $TARGET == 'tinyTpl\tiny::__init' )
                    {
                        $this->__customConstruct( $TINY );
                    }
                    else if ( $TARGET == 'tinyTpl\tiny::html' )
                    {
                        // $this->renderAnnotations( $TINY );
                    }
                    break;
            }

        }

        public function renderAnnotations( \tinyTpl\tiny $TINY )
        {
            if ( array_key_exists('tinyadmin_is_logged_in',$_SESSION) && $_SESSION['tinyadmin_is_logged_in'] == true )
            {
                $URI = preg_replace( '_\?.*_', '', $_SERVER['REQUEST_URI'] );
                $TINY->DATA['ANNOTATIONS'] = array();


                if ( file_exists( $this->CF . "/$URI" ) && is_readable( $this->CF . "/$URI" ) )
                {
                    $TINY->DATA['ANNOTATIONS'] = $this->loadAnnotation( $this->CF . "/$URI" );
                }

                $this->script = $TINY->read_template( $TINY->base . "/tpl/config_tpl/helper/js/anno_js.php", false );
                $TINY->html = preg_replace( '_</head>_i', "\n\t\t<link rel=\"stylesheet\" href=\"/tinyAdmin/special/css/jquery.stickynotes.css\">\n</head>", $TINY->html );
                $TINY->html = preg_replace( '_</body>_i', "\n" . $this->script . "\n</body>", $TINY->html );
            }
        }

        private function loadAnnotation( $file )
        {
            return json_decode( file_get_contents( $file ), true );
        }

        public function saveAnnotation( $anno )
        {
            if ( array_key_exists('tinyadmin_is_logged_in',$_SESSION)
              && $_SESSION['tinyadmin_is_logged_in'] == true
              && file_exists( $this->CF )
              && is_writeable( $this->CF )
            ) {
                $URI = preg_replace( '_\?.*_', '', $_SERVER['REQUEST_URI'] );
                $DATA = json_encode( $anno );
                file_put_contents( $this->CF . "/$URI", $DATA );
            }
        }
    }
}

/** ABSTRACT:

    <span class="x-synopsis">
        tinyPageAnnotations allows to create annotations on a per page basis.
    </span>

    <span class="x-author">
        derRaphael
    </span>

    <span class="x-version">
        0.1 Alpha (Not usable)
    </span>

    <span class="x-licence">
        3-clause BSD
    </span>

    <span class="x-info">
        tinyTpl core hook
    </span>

**/

?>