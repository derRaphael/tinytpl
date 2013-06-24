<?php
/*
 * linkbeau.php
 *
 * Copyright 2013 derRaphael <software@itholic.org>
 *
 * Version 0.1.5
 *
 * linkbeau shows how to enable hooks (observer pattern) with the
 * tinytpl singleton object.
 *
 * linkbeau aims to replace all links which are relative in the
 * final html document to have 'em a nice ".html" ending which makes
 * 'em look more friendly.
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
    class linkbeau extends helper\observer_abstract
    {
        public $TARGETS = array( 'tinytpl\tiny::html' );

        const VERSION = "0.1.5";

        public function trigger( $TINY, $STAGE, $TARGET )
        {
            switch ( $STAGE )
            {
                case 255:

                    // Grab HTML
                    $html = $TINY->html;

                    // Beautify link
                    $html = preg_replace(
                        '_(?<=\<a href\=")(/[^"#?.]*[^/"#?.])(?!\.[^?#]+])([?#][^"]*)?(?=")_i',
                        '\1.html\2',
                        $html
                    );

                    // store html
                    $TINY->html = $html;

                break;
            }
        }
    }
}

/** ABSTRACT:

    <span class="x-synopsis">
        linkbeau transforms any internal links in a SEO friendly manner.
        By means, it adds <code>.html</code> to the link URL.
    </span>

    <span class="x-author">
        derRaphael
    </span>

    <span class="x-version">
        0.1.5
    </span>

    <span class="x-licence">
        3-clause BSD
    </span>

    <span class="x-info">
        tiny core hook
    </span>

**/


?>