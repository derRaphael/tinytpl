<?php
/*
 * tinyHeaderInfo.class.php
 *
 * Copyright 2013 derRaphael <software@itholic.org>
 *
 * Version 0.1.4
 *
 * tinyHeaderInfo shows how to enable hooks (observer pattern) with the
 * tinyTpl singleton object.
 *
 * It pushes a system info into the http stream about the tinyTpl
 * version in the form "X-Powered-By: tinyTpl vX.X.X"
 * This effectively masks any given php version information which possibly
 * might have leaked into a security flaw otherwise.
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
    class tinyHeaderInfo extends tinyObserver
    {
        public $TARGETS = array( 'tinyTpl\tiny::__alt_construct' );

        const VERSION = "0.1.4";

        public function trigger( $TINY, $STAGE, $TARGET )
        {
            switch ( $STAGE )
            {
                case 255:
                    // Dump Header info
                    header( "X-Powered-By: tinyTpl v".$TINY::VERSION);
                    header( "Server: " . $_SERVER['HTTP_HOST'] );
                break;
            }
        }
    }
}

/** ABSTRACT:

    <span class="x-synopsis">
        tinyHeaderInfo pushes an info in the http stream (via header info)
        masking the php version and the server info.
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