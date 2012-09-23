#!/usr/bin/env php
<?php
$AUTOCONFIG_FQNAME  = "Autonginx";
$AUTOCONFIG_VERSION = "0.2";

$VERSION = <<<EO_VERSION

  $AUTOCONFIG_FQNAME Version $AUTOCONFIG_VERSION
  (c) 2012 by derRaphael


EO_VERSION;

$USAGE = <<<EO_USAGE

  Usage:
    {$_SERVER['PHP_SELF']} -s="SERVERNAME" [-?vtqd] [-o=FILE [-l=PATH [-r=PATH [-d=NAME]]]]


EO_USAGE;

$SHORTHINT = <<<EO_SHORTHINT

  Hint:
    Type {$_SERVER['PHP_SELF']} -? or {$_SERVER['PHP_SELF']} --help to see
    a list of available options.


EO_SHORTHINT;

$OPTIONS = <<<EO_OPTIONS

  Available options:
        -?          --help          This screen

        -v          --version       Prints version and exist

        -s="name"   --server="name" Sets the Servername, nginx
                                    will listen to

        -d="name"   --doc="name"    Sets default document root folder name
                                    !! This is not a path !!
                                    Default: basename(\$CWD),
                                    eg. the folder name of \$CWD

        -o="file"   --save="file"   Saves Config to "file"
                                    Default: \$SERVER_NAME

        -l="path"   --log="path"    Nginx server log path
                                    Default: ..\..\log

        -r="path"   --root="path"   Nginx project root files path
                                    Default: dirname(\$CWD)

        -t, -T      --test          Prints all autoguessed variables

        -q          --quiet         Quiet output

                    --dump          Dumps the generated config file
                                    Use with -q to skip header info

                    --licence       Prints licence info


    Notes:
        \$CWD will be taked from current working directory.
        When overriding any default parameters, be sure to use full
        path names, otherwise it might break config.


EO_OPTIONS;

$LICENCE = <<<EO_LICENCE

    Licence:
        Redistribution and use in source and binary forms, with or
        without modification, are permitted provided that the following
        conditions are met:

        * Redistributions of source code must retain the above copyright
          notice, this list of conditions and the following disclaimer.
        * Redistributions in binary form must reproduce the above
          copyright notice, this list of conditions and the following
          disclaimer in the documentation and/or other materials provided
          with the distribution.
        * Neither the name of the  nor the names of its contributors may
          be used to endorse or promote products derived from this
          software without specific prior written permission.

        THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND
        CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
        INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
        MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
        DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS
        BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
        EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
        TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
        DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
        ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
        LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING
        IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF
        THE POSSIBILITY OF SUCH DAMAGE.


EO_LICENCE;

function parse_argv( $argv )
{
    if ( $argv[0] == $_SERVER['PHP_SELF'] )
    {
        array_shift( $argv );
    }

    $_PARAMS = array();

    foreach( $argv as $arg)
    {
        $regex = '_^((--(?P<LKEY>[^=]+)|-(?P<SKEY>[^-]))'.
                   '(?:=(?P<VALUE>[^=]*)$)?'.
                   '|-(?P<MKEYS>[^-]+)'.
                   '|(?P<PLAIN>[^-]+))$_';

        if ( preg_match( $regex, $arg, $m ) )
        {
            if ( ( array_key_exists( 'SKEY', $m ) && $m['SKEY'] != ""
                || array_key_exists( 'LKEY', $m ) && $m['LKEY'] != "" )
              && array_key_exists( 'VALUE', $m ) && $m['VALUE'] != ""
            ) {
                $_PARAMS[@$m['SKEY'].@$m['LKEY']] = $m['VALUE'];
            }
            else if ( array_key_exists( 'SKEY', $m ) && $m['SKEY'] != ""
                || array_key_exists( 'LKEY', $m ) && $m['LKEY'] != ""
            ) {
                $_PARAMS[@$m['SKEY'].@$m['LKEY']] = true;
            }
            else if ( array_key_exists( 'MKEYS', $m ) && $m['MKEYS'] != "" )
            {
                foreach( str_split($m['MKEYS']) as $MKEY )
                {
                    $_PARAMS[ $MKEY ] = true;
                }
            }
            else if ( array_key_exists( 'PLAIN', $m ) && $m['PLAIN'] != "" )
            {
                $_PARAMS[$m['PLAIN']] = true;
            }
        }
    }
    return $_PARAMS;

}

$_PARAMS = parse_argv( $argv );

// Check if we're in quietmode
if ( ( count( $_PARAMS ) >= 1
  && ! array_key_exists('q', $_PARAMS)
  && ! array_key_exists('quiet', $_PARAMS) )
  || count( $_PARAMS ) == 0
) {
echo $VERSION;
}

// Sanitize parameter
if ( count( $_PARAMS ) == 0
  || array_key_exists('?', $_PARAMS)
  || array_key_exists('help', $_PARAMS)
) {
die( $USAGE . $OPTIONS );
}

if ( array_key_exists('v', $_PARAMS)
  || array_key_exists('version', $_PARAMS)
) {
die();
}

if ( array_key_exists('licence', $_PARAMS)
) {
die( $LICENCE );
}

if ( ! array_key_exists( 's', $_PARAMS ) && ! array_key_exists( 'server', $_PARAMS ) )
{
die( '  Missing server name. Exiting.' . "\n" . $USAGE . $SHORTHINT );
}


// Get SERVER NAME OUT OF PARAMS OR DIE.
if ( array_key_exists( 's', $_PARAMS ) && ! array_key_exists( 'server', $_PARAMS ) && trim( $_PARAMS['s'] )  != "" )
{
$NGINX_SERVER = $_PARAMS['s'];
}
else if ( ! array_key_exists( 's', $_PARAMS ) && array_key_exists( 'server', $_PARAMS ) && trim( $_PARAMS['server'] )  != ""  )
{
$NGINX_SERVER = $_PARAMS['server'];
}
else if ( ! array_key_exists( 's', $_PARAMS ) && ! array_key_exists( 'server', $_PARAMS ) )
{
die( '  Missing server name. Exiting.' . "\n" . $USAGE . $SHORTHINT );
}
else if ( array_key_exists( 's', $_PARAMS ) && array_key_exists( 'server', $_PARAMS )  && trim( $_PARAMS['s'] )  != ""   && trim( $_PARAMS['server'] )  != "" )
{
die( '  Duplicate Servername in parameter. Please specify only once. Exiting.' . "\n" . $USAGE . $SHORTHINT );
}

$CWD = exec('pwd');

$DOC_ROOT = basename( $CWD );
$WWW_ROOT = dirname( $CWD );
$LOG_ROOT = dirname( dirname( $CWD ) ) . "/log";

// Override defaults with given names
// Log Root
foreach( array('l', 'log') as $PARAM )
{
    if ( array_key_exists( $PARAM, $_PARAMS )
      && trim( $_PARAMS[ $PARAM ] )  != ""
      && is_string( $_PARAMS[ $PARAM ] )
    ) {
        $LOG_ROOT = $_PARAMS[ $PARAM ];
    }
}

// Doc Root
foreach( array('d', 'doc') as $PARAM )
{
    if ( array_key_exists( $PARAM, $_PARAMS )
      && trim( $_PARAMS[ $PARAM ] )  != ""
      && is_string( $_PARAMS[ $PARAM ] )
    ) {
        $DOC_ROOT = $_PARAMS[ $PARAM ];
    }
}

// www Root
foreach( array('r', 'root') as $PARAM )
{
    if ( array_key_exists( $PARAM, $_PARAMS )
      && trim( $_PARAMS[ $PARAM ] )  != ""
      && is_string( $_PARAMS[ $PARAM ] )
    ) {
        $DOC_ROOT = $_PARAMS[ $PARAM ];
    }
}

// Prepare Config
$CONFIG=<<<EO_CONFIG
#
# $NGINX_SERVER config
#
# Automatic generated by $AUTOCONFIG_FQNAME Version $AUTOCONFIG_VERSION
#

server {

        server_name      $NGINX_SERVER;

        # set proper log files
        access_log       $LOG_ROOT/$NGINX_SERVER.access.log;
        error_log        $LOG_ROOT/$NGINX_SERVER.error.log;

        root             $WWW_ROOT/$DOC_ROOT/web;

        charset          utf-8;
        source_charset   utf-8;

        # tinyAdmin special behaviour
        # This rule gives speed
        # instead of using the internal parser
        #
        location ~ /tinyAdmin/special/(.*)\$ {
                alias $WWW_ROOT/$DOC_ROOT/tpl/config_tpl/assets/\$1;
        }

        # Alias for favicon.ico - so it can be located
        # in assets folder
        #
        location = /favicon.ico {
                alias   $WWW_ROOT/$DOC_ROOT/web/assets/favicon.ico;
        }

        # Basic Assets rule
        #
        # Pushes anything which is directly in the
        # document root's asset folder
        #
        location ~* /assets* {
                try_files \$uri /404;
        }

        # Default redirects for tinyTpl
        #
        location / {
                # base index
                index  index.php;

                # anything else to index.php
                rewrite ^(?!/(index\.php)).* /index.php?\$request_uri last;
        }

        # pass the PHP scripts to FastCGI server listening on local socket
        # which is a prefferable behaviour over localhost:9001
        #
        location ~* (^/index.php.*) {
                try_files \$uri \$uri/ index.php 404;
                fastcgi_split_path_info ^(.?+\.php)(.*)$;
                fastcgi_pass   unix:/tmp/php.socket;
                fastcgi_index  index.php;
                fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
                include fastcgi_params;
        }

}

EO_CONFIG;

if ( array_key_exists('dump', $_PARAMS)
) {
die( $CONFIG );
}

if ( array_key_exists('t', $_PARAMS)
  || array_key_exists('T', $_PARAMS)
  || array_key_exists('test', $_PARAMS)
) {
die( <<<EO_TEST
  Testdump for Parameter

  Document Root: $DOC_ROOT
  WWW-Root:      $WWW_ROOT
  Log-Root:      $LOG_ROOT


EO_TEST
);
}

if ( array_key_exists('o', $_PARAMS)
) {
    $OUTNAME = $_PARAMS['o'];
}
else if ( array_key_exists('save', $_PARAMS)
) {
    $OUTNAME = $_PARAMS['save'];
} else {
    $OUTNAME = $NGINX_SERVER;
}

file_put_contents( $OUTNAME, $CONFIG );
if ( ! array_key_exists('q', $_PARAMS)
  && ! array_key_exists('quiet', $_PARAMS)
) {
echo "  Configuration was written to '$OUTNAME'.\n\n";
}

die();
?>