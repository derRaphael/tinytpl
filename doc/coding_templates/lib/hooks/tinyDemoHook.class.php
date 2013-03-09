<?php
/*
 * tinyDemoHook.class.php
 *
 * Copyright 2013 derRaphael <software@itholic.org>
 *
 * Version 0
 *
 * This class is a stub and only illustrates how to properly code
 * a hook which might be used from tinyTpl
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
    class tinyDemoHook extends tinyObserver
    {
        public $TARGETS = array( 'tinyTpl\tiny::html', 'tinyTpl\tiny::use_template' );

        const VERSION = "0";

        public function trigger( $TINY, $STAGE, $TARGET )
        {
            switch ( $STAGE )
            {
                case 0:
                    // Check for global access only when accessing the stats page of tinyAdmin
                    if ( 
                        $TARGET == 'tinyTpl\tiny::html'
                    ) {
                        // This overrides tiny's defined development state.
                        // Which allows to use this particular function from a
                        // potentially deactivated tinyAdmin menu.
                        $TINY->dev_state_override = \tinyTpl\tiny::DEV_STATE_OVERRIDE;
                    }

                    if ( $TARGET == 'tinyTpl\tiny::html' )
                    {
                        // Do some funky stuff here
                        $this->someFunkyFunction()
                    }

                    break;
                case 254:
                    if ( $TARGET == 'tinyTpl\tiny::use_template' )
                    {
                        // Add your custom fun into the use template function
                        $this->templateCustomFun()
                    }
                    break;
                case 255:
                    if ( $TARGET == 'tinyTpl\tiny::html' ) 
                    {
                        $this->otherFunkyStuff()
                    }
                    break;
            }
        }

        /*
         *
         * name: functionStubs
         *
         * These functions just illustrate possibilities
         *
         */
        public function someFunkyFunction()
        {
            // Do stuff here
        }
        public function templateCustomFun()
        {
            // Do other stuff here
        }
        public function otherFunkyStuff()
        {
            // Do even more stuff here
        }
    }
}

/**
 * The section below is somewhat html which is displayed at the hook page in tinyAdmin
 * It must retain this format, since it's stripped from each hook class by some regex.
**/

/** ABSTRACT:

    <span class="x-synopsis">
        This class is a stub and only illustrates how to properly code
        a hook which might be used from tinyTpl
     </span>

    <span class="x-author">
        derRaphael
    </span>

    <span class="x-version">
        0
    </span>

    <span class="x-licence">
        3-clause BSD
    </span>

    <span class="x-info">
        demo tinyTpl core hook
    </span>

**/
?>