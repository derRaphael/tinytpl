#!/bin/bash
#
#  startwebserver.sh
#  v0.1
#
#  Copyright 2013 derRaphael <software@itholic.org>
#
#  This file instatinates a tinyTpl directory. By means, it creates a
#  cache folder and sets proper rights on all files.
#  Additionally it tries to fix the WWW_USR to WWW_SVR group
#  if neccessary.
#
#  Redistribution and use in source and binary forms, with or without
#  modification, are permitted provided that the following conditions are
#  met:
#
#  * Redistributions of source code must retain the above copyright
#    notice, this list of conditions and the following disclaimer.
#  * Redistributions in binary form must reproduce the above
#    copyright notice, this list of conditions and the following disclaimer
#    in the documentation and/or other materials provided with the
#    distribution.
#  * Neither the name of the  nor the names of its
#    contributors may be used to endorse or promote products derived from
#    this software without specific prior written permission.
#
#  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
#  "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
#  LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
#  A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
#  OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
#  SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
#  LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
#  DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
#  THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
#  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
#  OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
#

# Check for proper directory
CWD=$(pwd)

if [ "${CWD##*/}" == "bin" ]; then
    echo
    echo "  Error."
    echo "  This script must be from tiny's basefolder." 1>&2
    echo
    exit 1
fi

# Ok, we're not in bin folder. Check for other folder existence.
if [ ! -d "$CWD/tpl" -o ! -d "$CWD/web" -o ! -d "$CWD/lib" -o ! -f "$CWD/web/index.php"  ]; then
    echo
    echo "  Error."
    echo "  Some core folder of tinyTpl are missing."
    echo "  This script requires the following folder and files to exist:"
    echo
    if [ ! -d "$CWD/lib" ]; then
        echo "   - $CWD/lib"
    fi;
    if [ ! -d "$CWD/tpl" ]; then
        echo "   - $CWD/tpl"
    fi;
    if [ ! -d "$CWD/web" ]; then
        echo "   - $CWD/web"
    fi;
    if [ ! -f "$CWD/web/index.php" ]; then
        echo "   - $CWD/web/index.php"
    fi;
    echo
    echo "  Please make sure you have a valid copy of tinyTpl."
    echo
    exit 1
fi

# Since we didnt exit yet, all must be fine
#Check php version, as only php5.4 or younger is able to act as a local webserver
PHP_BIN=$(which php)
if [ -z $PHP_BIN ]; then
	echo "Sorry, No PHP Found. Aborting."
	exit 1
fi

PHP=$($PHP_BIN -r 'echo (version_compare(PHP_VERSION, "5.4.0", "<")) ? "FAIL" : "OK";')
if [[ "$PHP" == "FAIL" ]]; then
	echo "Sorry, you have PHP, but it doesn't have a builtin webserver. Aborting."
    exit 1
fi

# Ok, we came here - start the fun.
echo "Starting the PHP's builtin webserver. We'll be listening at port 8080."
echo "Connect your Prowser to http://localhost:8080/ to start viewing tinyTpl."
echo "Enjoy!"

$PHP_BIN -S localhost:8080 -t web web/index.php

