<?php
/*
 * observer.php
 *
 * Copyright 2013 derRaphael <software@itholic.org>
 *
 * Version 0.1 // Modified in tinytpl v0.2.7
 *
 * Part of the tinytpl Distribution
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

/**
 * For better handling, all hooks for tinytpl should only exists
 * in their own namespace
**/
namespace tinytpl\hooks\helper
{
    /**
     * This class was added to support autoinit features of hooks in an
     * uninitialized cache folder. This way it may be prevented, that tiny
     * throws an Error if a MongoClass exists, yet no mongoDb is running.
     * 
     * This affects all classes as they now have to extend this base
     * Obeserver class, opposed to prior just implementing the interface.
     *
     * Added in tinytpl 0.2.6
     * Relocated in tinytpl 0.2.7
    **/
    abstract class observer_abstract implements observer_interface
    {

        const VERSION  = "0.1";

        /**
         * When writing own hooks, which might fail on
         * initialization from an unitialized cache folder
         * this constant should be overridden in the definition
         * and set to false.
         * see mongoinit for sample usage.
        **/
        const AUTOINIT = true;

        public function trigger( $TINY, $STAGE, $TARGET )
        {
            return false;
        }
    }

}
?>