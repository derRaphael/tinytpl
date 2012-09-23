#!/bin/bash
#
#  inittinytpl.sh
#  v0.1
#
#  Copyright 2012 derRaphael <software@itholic.org>
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

# Set the user which is the common user who holds the webspace
# make sure, this user is in group www-data, so both can share
# same files. This has the advantage that uploads may be done
# with a different user as the webservices runs.
WWW_USR="www-service"

# Either all files become world-readable or worse, world-writable
# it's imho better to add the upload-user to www-data group which
# is also used by the web server itself.
WWW_SVR="www-data"

# If we have parameters, assume these are username and group
# 1st parameter is taken as username
if [ "$1" != "" ]; then

    # Check for users existence
    egrep -i "^$1:" /etc/passwd &>/dev/null

    if [ $? -eq 0 ]; then

        WWW_USR=$1

    else

        echo
        echo "  Error."
        echo "  User $1 does not exist."
        echo
        exit 1

    fi;

fi;

# 2nd parameter is taken as groupname
if [ "$2" != "" ]; then

    # Check for groups existence
    egrep -i "^$2:" /etc/group &>/dev/null

    if [ $? -eq 0 ]; then

        WWW_SVR=$2

    else

        echo
        echo "  Error."
        echo "  Group $2 does not exist."
        echo
        exit 1

    fi;
fi

# Make sure only root can run our script
if [ "$(id -u)" != "0" ]; then
    echo
    echo "  Error."
    echo "  This script must be run as root." 1>&2
    echo
    echo "  Try 'sudo $0'." 1>&2
    echo
    exit 1
fi

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
if [ ! -d "$CWD/tpl" -o ! -d "$CWD/web" -o ! -d "$CWD/lib"  ]; then
    echo
    echo "  Error."
    echo "  Some core folder of tinyTpl are missing."
    echo "  This script requires the followin folder to exist:"
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
    echo
    echo "  Please make sure you have a valid copy of tinyTpl."
    echo
    exit 1
fi

# Test for cache folder an create it if neccessary
if [ ! -d "$CWD/cache" ]; then
    mkdir -p "$CWD/cache"
fi

# Set proper rights on standard folders
# Usually rights used are "0750" for all but cache and "770" for cache
# as the webserver needs write access in that folder and that folder only.

# Set proper ownerships
chown -R $WWW_USR:$WWW_SVR "$CWD"

# Set proper ownerships
chown -R $WWW_SVR:$WWW_SVR "$CWD/cache"

# First we set global rights for current folder
find ./ -type d -exec chmod u+rwx,g+rs,g-w,o-rwx {} \;

# Second we set global rights for cache folder
find ./cache -type d -exec chmod g+rws {} \;

# Add our user to our group.
if [[ "$(id $WWW_USR)" != *"$WWW_SVR"* ]]; then
    adduser --quiet $WWW_USR $WWW_SVR
fi
