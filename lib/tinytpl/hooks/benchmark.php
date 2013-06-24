<?php
/*
 * benchmark.php
 *
 * Copyright 2013 derRaphael <software@itholic.org>
 *
 * Version 0.1.4
 *
 * benchmark shows how to enable hooks (observer pattern) with the
 * tinytpl singleton object.
 *
 * It pushes an info into the http stream indicating how log it took
 * to render the page. Thus it invokes at the very beginning of the
 * entire render process and at the very end of it. This means it hooks
 * two different functions with two different stages.
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
    class benchmark extends helper\observer_abstract
    {
        public $TARGETS = array(
                    'tinytpl\tiny::__init',
                    'tinytpl\tiny::html',
                );

        const VERSION = "0.1.4";

        public function trigger( $TINY, $STAGE, $TARGET )
        {
            switch ( $STAGE )
            {
                case 0:

                    if ( $TARGET == 'tinytpl\tiny::__init' )
                    {
                        $TINY->start_of_rendering = microtime( true );
                    }
                    break;

                case 255:

                    if ( $TARGET == 'tinytpl\tiny::html' )
                    {
                        // Dump performance info into http stream
                        $sec = sprintf("%9.7f", microtime(true) - $TINY->start_of_rendering );
                        header( "X-Render-Time: ".$sec." seconds");
                    }
                    break;
            }
        }
    }
}

/** ABSTRACT:

    <span class="x-synopsis">
        benchmark pushes an info in the http stream (via header info)
        about how long it took the page to render
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
        tiny core hook
    </span>

**/

?>