<?php
/*
 * tinyPageAnnotations.class.php
 *
 * Copyright 2013 derRaphael <software@itholic.org>
 *
 * Version 0.1.4
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
    class tinyPageAnnotations extends tinyObserver
    {
        public $TARGETS = array(
                    'tinyTpl\tiny::__init',
                    'tinyTpl\tiny::html',
                ),
               $CF = "";

        const VERSION = "0.1.4";

        private function __customConstruct( \tinyTpl\tiny $TINY )
        {

            if ( is_array( $_SESSION ) && array_key_exists('tinyadmin_is_logged_in',$_SESSION) && $_SESSION['tinyadmin_is_logged_in'] == true )
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

        public function __customDeactivate()
        {
        }

        public function __customActivate()
        {
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

                        $this->renderAnnotations( $TINY );
                    }
                    break;
            }

        }

        public function renderAnnotations( \tinyTpl\tiny $TINY )
        {
            if ( is_array( $_SESSION ) && array_key_exists('tinyadmin_is_logged_in',$_SESSION) && $_SESSION['tinyadmin_is_logged_in'] == true )
            {

                $URI = preg_replace( '_\?.*|/$|^/_', '', $_SERVER['REQUEST_URI'] );
                $URI = preg_replace( '_\W_', '-', $URI );

                if ( $URI == "" )
                {
                    $URI = $TINY->default_template;
                }

                $TINY->DATA['ANNOTATIONS'] = new \stdClass();
                $TINY->DATA['ANNOTATION']  = array(
                    'PAGEID' => base64_encode( $URI )
                );

                if ( isset( $this->CF ) && file_exists( $this->CF . "/$URI" ) && is_readable( $this->CF . "/$URI" ) )
                {
                    $TINY->DATA['ANNOTATIONS'] = $this->loadAnnotation( $this->CF . "/$URI" );
                }

                // Grab script from templates
                ob_start();
                include( $TINY->base . "/tpl/config_tpl/helper/js/anno_js.php" );
                $this->script = ob_get_contents();
                ob_end_clean();

                // Inject template into document stream, regardless of any trigger
                //
                // ATTENTION !!
                //
                // In order to work, the injected script expects jquery and jquery ui to be already loaded.
                // Allthough it checks their presence, it wont attempt to load missing parts by itself,
                // in such case, it simply refuses to render.
                //
                $html = $TINY->html;
                $html = preg_replace( '_</body>_i', "\n" . $this->script . "\n</body>", $html );
                $TINY->html = $html;
            }
        }

        private function loadAnnotation( $file )
        {
            $data = json_decode( file_get_contents( $file ), false );

            if ($data == null)
            {
                $data = new \stdClass();
            }

            return $data;
        }

        public function saveAnnotation( $DATA, $URI )
        {
            if ( is_array( $_SESSION )
              && array_key_exists('tinyadmin_is_logged_in',$_SESSION)
              && $_SESSION['tinyadmin_is_logged_in'] == true
              && isset( $this->CF )
              && file_exists( $this->CF )
              && is_writeable( $this->CF )
            ) {

                if ( $URI == "" )
                {
                    $URI = $TINY->default_template;
                }

                file_put_contents( $this->CF . "/$URI", json_encode( $DATA ) );
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
        0.1.4
    </span>

    <span class="x-licence">
        3-clause BSD
    </span>

    <span class="x-info">
        tinyTpl core hook
    </span>

**/

?>