<?php
/*
 * tinyMongoInit.class.php
 *
 * Copyright 2012 derRaphael <software@itholic.org>
 *
 * Version 0.1
 *
 * tinyMongoInit initialises mongoDb for usage with tinyTpl.
 *
 * After being triggered, it provides a new variable holding the tinyMongo
 * ref in tinyTpl's master class.
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
    class tinyMongoInit implements tinyObserver
    {
        public $TARGETS = array(
                    'tinyTpl\tiny::__init',
                );

        const VERSION = "0.1";

        public function trigger( $TINY, $STAGE, $TARGET )
        {
            switch ( $STAGE )
            {
                case 0:

                    if ( isset( \tinyTpl\config::$db_options ) )
                    {
                        $db_options = \tinyTpl\config::$db_options;
                    } else {
                        $db_options = array();
                    }

                    if ( $TARGET == 'tinyTpl\tiny::__init' )
                    {
                        \tinyTpl\tiny::sys()->mongo = new \tinyTpl\db\tinyMongo( $db_options );
                    }
                    break;
            }
        }
    }
}

/** ABSTRACT:

    <span class="x-synopsis">
        tinyMongoInit initialises mongoDb for usage with tinyTpl.
        After being triggered, it provides a new variable <code>$mongo</code>
        holding the tinyMongo ref in tinyTpl's master class. This hook may
        be configured by using tiny's config class.
    </span>

    <span class="x-author">
        derRaphael
    </span>

    <span class="x-version">
        0.1
    </span>

    <span class="x-licence">
        3-clause BSD
    </span>

    <span class="x-info">
        tinyTpl database hook
    </span>

**/

?>