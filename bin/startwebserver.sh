#!/bin/bash
#
#  startwebserver.sh
#  v0.2
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

# Some basic Variables
# Check for proper directory
CWD=$(pwd)
PHP_BIN=$(which php)
HOST="0.0.0.0"
PORT="8080"

usage()
{
    echo
    echo " Usage:"
    echo 
    echo "   $0 [-h][-p PORT][-l HOST]"
    echo
    echo -e "\t-p PORT         A Port to listen to. Default 8080"
    echo -e "\t                For Ports below 1024 You need to be root"
    echo -e "\t-l HOST         Set the HOST to a your FQDN, Localhost or"
    echo -e "\t                an numeric IP. Defaults to 0.0.0.0"
    echo -e "\t-h              This screen"
    echo
    exit 0
}

check_working_dir()
{
    if [ "${CWD##*/}" == "bin" ]; then
        echo
        echo "  Error."
        echo "  This script must be from tiny's basefolder." 1>&2
        echo
        exit 1
    fi
}

check_tiny_folder()
{
    # Check for other folder existence.
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
}

check_php_binary()
{
    #Check for php binary
    if [ -z $PHP_BIN ]; then
        echo 
        echo "  Error."
        echo "  Sorry, No PHP-CLI found. Aborting."
        echo
        exit 1
    fi
}

check_php_version()
{
    PHP=$($PHP_BIN -r 'echo (version_compare(PHP_VERSION, "5.4.0", "<")) ? "FAIL" : "OK";')
    if [[ "$PHP" == "FAIL" ]]; then
        echo
        echo "  Error."
        echo "  Sorry, you have PHP, but it doesn't have a builtin webserver. Aborting."
        echo
        exit 1
    fi
}

check_args()
{
    # check for bash parameter
    while getopts "p:l:h" options; do
        case $options in

            p)  # check various port options
                if [ ! -z "${OPTARG##*[!0-9]*}" ]; then
                    if [ $OPTARG -lt 1024 ]; then
                        if [ "$(id -u)" != "0" ]; then
                            echo
                            echo "  Error."
                            echo "  You must be root to set a low port. Aborting."
                            echo 
                            exit 1
                        fi
                        PORT=$OPTARG
                    elif [[ $OPTARG -gt 1023 && $OPTARG -lt 65536 ]]; then
                        PORT=$OPTARG
                    else
                        echo
                        echo "  Error."
                        echo "  Invalid PORT specified. Aborting."
                        echo
                        exit 1
                    fi
                else 
                    echo
                    echo "  Error."
                    echo "  PORT must be a Number. Aborting."
                    echo 
                    exit 1
                fi
                ;;

            l)  # check that hostname is valid
                if [ "$(ping -q -c1 $OPTARG)" ]; then
                    HOST="$OPTARG"
                else
                    echo 
                    echo "  Error."
                    echo "  Invalid Host '$OPTARG' specified. Aborting."
                    echo
                    exit 1
                fi
                ;;

            h)  # Show usage and exit
                usage
                ;;

            \?) # Unknown Parameter Handling
                echo 
                echo "  Error."
                echo "  Unknown parameter. Aborting."
                echo
                usage
                ;;
        esac
    done
}

main()
{
    check_working_dir
    check_tiny_folder
    check_php_binary
    check_php_version

    check_args $@

    # Ok, we came here - start the fun.
    echo
    echo "  Starting the PHP's builtin webserver. We'll be listening at port 8080."
    echo "  Connect your Browser to http://$HOST:$PORT/ to start viewing tinyTpl."
    echo "  Enjoy!"
    echo
    $PHP_BIN -S $HOST:$PORT -t web web/index.php
    exit 0
}

main $@

exit 0





