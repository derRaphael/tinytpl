<?php
/*
 * mongoinit.php
 *
 * Copyright 2012 derRaphael <software@itholic.org>
 *
 * Version 0.2.1
 *
 * mongoinit initialises mongoDb for usage with tinytpl.
 *
 * After being triggered, it provides a new variable holding the tinyMongo
 * ref in tinytpl's master class.
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

namespace tinytpl\hooks
{
    class mongoinit extends helper\observer_abstract
    {
        public $TARGETS = array(
                    'tinytpl\tiny::__init',
                );

        const VERSION  = "0.2.1";

        // We do not want tiny to init on base run
        const AUTOINIT = false;

        public function trigger( $TINY, $STAGE, $TARGET )
        {
            switch ( $STAGE )
            {
                case 0:

                    if ( isset( \tinytpl\tiny::$db_options ) )
                    {
                        $db_options = \tinytpl\tiny::$db_options;
                    } else {
                        $db_options = array();
                    }

                    if ( $TARGET == 'tinytpl\tiny::__init' )
                    {
                        /**
                         * Added in v0.2
                         *
                         * Wrap Mongo Class initialisation in try catch block
                         * in order to avoid an Exceptional drop out, prior to
                         * tinytpls Exception handling.
                         *
                        **/
                        try 
                        {
                            \tinytpl\tiny::sys()->mongo = new \tinytpl\db\mongodriver( $db_options );
                        }
                        catch ( \MongoConnectionException $MongoException ) 
                        {
                            // Since we arrived here, avoid further including of this hook and disable it
                            $fn = \tinytpl\tiny::sys()->base . "/cache/hooks";
                            $result = rename( "$fn/mongoinit.php", "$fn/mongoinit.php.available" );

                            if ( isset( $_SESSION ) && is_array( $_SESSION ) )
                            {
                                if ( ! isset( $_SESSION['tinyAdmin_hookErrors'] ) || ! is_array( $_SESSION['tinyAdmin_hookErrors'] ) )
                                {
                                    $_SESSION['tinyAdmin_hookErrors'] = array();
                                }

                                $_SESSION['tinyAdmin_hookErrors'][] = "mongoinit was due to failure autodeactivated.<br/>Full message was " .$MongoException->message;
                            }


                            // generate an exception message, by using tiny's exception handler
                            \tinytpl\tiny::sys()->handle_exception( $MongoException );
                        }
                    }
                    break;
            }
        }
    }
}

/** ABSTRACT:

    <span class="x-synopsis">
        mongoinit initialises mongoDb for usage with tinytpl.
        After being triggered, it provides a new variable <code>$mongo</code>
        holding the tinyMongo ref in tinytpl's master class. This hook may
        be configured by using tiny's config class.
    </span>

    <span class="x-author">
        derRaphael
    </span>

    <span class="x-version">
        0.2.1
    </span>

    <span class="x-licence">
        3-clause BSD
    </span>

    <span class="x-info">
        tiny database hook
    </span>

**/

?>