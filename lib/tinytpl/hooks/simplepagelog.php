<?php
/*
 * simplepagelog.class.php
 *
 * Copyright 2013 derRaphael <software@itholic.org>
 *
 * Version 0.1.7
 *
 * tinyPageLog shows how to enable hooks (observer pattern) with the
 * tinytpl singleton object.
 *
 * simplepagelog is a simple file logging facility which
 * gives a generic overview of your webapps' usage.
 * It inlucedes the following features:
 *
 *  * Timestamp
 *  * Userclient
 *  * SessionID if applicable
 *  * Page - comma seperated template list in order of loading
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
    class simplepagelog extends helper\observer_abstract
    {
        public $TARGETS = array( 'tinytpl\tiny::html', 'tinytpl\tiny::use_template' );

        const VERSION = "0.1.8";

        public function trigger( $TINY, $STAGE, $TARGET )
        {
            // We don't want to cache favicon requests
            // Added in v0.1.7
            if ( ! preg_match( '_(favicon\.ico)_', $_SERVER['REQUEST_URI'] ) )
            {
                // premature exit
                return;
            }

            switch ( $STAGE )
            {
                case 0:
                    // Check for global access only when accessing the stats page of tinyAdmin
                    if ( $TARGET == 'tinytpl\tiny::html'
                      && count( $TINY->args ) == 2
                      && $TINY->args[0] == "tinyAdmin"
                      && $TINY->args[1] == "stats"
                      && $TINY->dev_state == "stable"
                      && file_exists( $TINY->base . "/cache/stats.data" )
                    ) {
                        // This overrides tiny's defined development state.
                        // Which allows to use this particular function from a
                        // potentially deactivated tinyAdmin menu.
                        $TINY->dev_state_override = tinytpl\tiny::DEV_STATE_OVERRIDE;

                    }

                    if ( $TARGET == 'tinytpl\tiny::html' )
                    {
                        $TINY->SIMPLELOG['REQUEST'] = $_SERVER["REQUEST_URI"];
                        $TINY->SIMPLELOG['REQUEST_TIME'] = $_SERVER["REQUEST_TIME"];
                        $TINY->SIMPLELOG['ACTION']  = $TINY->action;
                        $TINY->SIMPLELOG['ARGS']    = $TINY->args;
                        $TINY->SIMPLELOG['UA']      = $this->getBrowser();
                        $TINY->SIMPLELOG['UA-IP']   = $_SERVER["REMOTE_ADDR"];
                        $TINY->SIMPLELOG['SID']     = ( $TINY->use_sessions === true ? session_id() : "none" );
                        $TINY->SIMPLELOG['TYPE']    = $TINY->method;
                        $TINY->SIMPLELOG['TPL']     = array();
                    }

                    break;
                case 254:
                    if ( $TARGET == 'tinytpl\tiny::use_template' )
                    {
                        $TINY->SIMPLELOG['TPL'][] = $TINY->action;
                    }
                    break;
                case 255:
                    if ( $TARGET == 'tinytpl\tiny::html'
                    ) {
                        // Set the used master template
                        $TINY->SIMPLELOG['MASTER']  = $TINY->MASTER_TEMPLATE;

                        // Create Directories
                        $cache = $TINY->base . "/cache";

                        if ( is_dir($cache) && is_writable($cache) )
                        {
                            // Get Permissions
                            $cFilePerms = fileperms( $cache );

                            $day = date("d", $TINY->SIMPLELOG['REQUEST_TIME']);
                            $mon = date("m", $TINY->SIMPLELOG['REQUEST_TIME']);
                            $yr  = date("Y", $TINY->SIMPLELOG['REQUEST_TIME']);

                            $logDir = "$cache/log/$yr/$mon/$day";

                            // Create base log folder if neccessary
                            if ( !file_exists( $logDir ) && ! is_dir( $logDir ) )
                            {
                                mkdir( $logDir, $cFilePerms, true );
                            }

                            // now, we have a folder we can write our information
                            $line = array(
                                date("H:i:s", $TINY->SIMPLELOG['REQUEST_TIME']),
                                $TINY->SIMPLELOG['REQUEST_TIME'],
                                $TINY->SIMPLELOG['TYPE'],
                                $TINY->SIMPLELOG['REQUEST'],
                                $TINY->SIMPLELOG['UA']["platform"],
                                $TINY->SIMPLELOG['UA']["name"],
                                $TINY->SIMPLELOG['UA']["version"],
                                $TINY->SIMPLELOG['UA-IP'],
                                $TINY->SIMPLELOG['SID'],
                                $TINY->SIMPLELOG['MASTER'],
                                implode("|",$TINY->SIMPLELOG['TPL'])
                            );

                            $fp = fopen("$logDir/data", 'a');
                            fputcsv( $fp, $line );
                            fclose($fp);

                        }
                    }
                    break;
            }
        }

        /*
         *
         * name: getValidDaysFolderList
         *
         * This function enlists all available subfolder containing stats data
         *
         * @param $basedir
         *
         * @return array
         *
         */
        public function getValidDaysFolderList( $basedir = null )
        {
            if ( $basedir == null )
            {
                $basedir = date("Y") . "/" . date("m");
            }
            $basedir = tinytpl\tiny::sys()->base ."/cache/log/";

            if ( is_dir( $basedir ) )
            {
                $dir = new \RecursiveDirectoryIterator( $basedir,\FilesystemIterator::SKIP_DOTS );
                $ite = new \RecursiveIteratorIterator($dir,\RecursiveIteratorIterator::CHILD_FIRST);
                $log = array();

                foreach($ite as $path){
                    if ( $path->isDir() && preg_match( '_(?<=\/log/)(\d+)\/(\d+)\/(\d+)_', $path->getPathname(), $m ) ) {
                        $log[] =  preg_replace( '_(.*)?(?<=\/log/)(\d+)\/0?(\d+)\/0?(\d+)_', "\\4-\\3-\\2", $path->getPathname() );
                    }
                }
                return $log;
            }
            return array();
        }

        /*
         *
         * name: buildDaylyStats
         *
         * This function builds reads a log set by a given date
         *
         * @param $given_year
         * @param $given_month
         * @param $given_day
         *
         * @return array
         *
         */
        public function readDataByDate( $given_year = null, $given_month = null, $given_day = null )
        {
            // cache directory
            $cache = tinytpl\tiny::sys()->base . "/cache";

            $day = is_null( $given_day) ? date("d") : $given_day;
            $mon = is_null( $given_month) ? date("m") : $given_month;
            $yr = is_null( $given_year) ? date("Y") : $given_year;

            $logDir = "$cache/log/$yr/$mon/$day";

            // OutData
            $rows = array();

            if ( file_exists( "$logDir/data" ) && is_readable( "$logDir/data" ) )
            {
                if (($fp = fopen("$logDir/data", "r")) !== false ) {
                    while ( ( $data = fgetcsv( $fp ) ) !== false ) {
                        $rows[] = $data;
                    }
                    fclose( $fp );
                }
            }

            return $rows;

        }

        /*
         *
         * name: buildDaylyStats
         *
         * This function builds stats by a given date
         *
         * @param $given_year
         * @param $given_month
         * @param $given_day
         *
         * @return array
         *
         */
        public function buildDaylyStats( $given_year = null, $given_month = null, $given_day = null )
        {
            $day = is_null( $given_day) ? date("d") : $given_day;
            $mon = is_null( $given_month) ? date("m") : $given_month;
            $yr = is_null( $given_year) ? date("Y") : $given_year;

            // Get current stats
            $stats_data = $this->readDataByDate( $yr, $mon, $day );

            // Sort by OS
            $stats_os = array();

            // Sort by OS
            $stats_browser = array();

            // Sort by hits / hour
            $stats_pi = array();

            // Sort by hits / hour
            $stats_hits = array();

            // Sort by urls
            $stats_url = array();

            for($i=0;$i<24;$i++)
            {
                $stats_hits[$i] = 0;
                $stats_pi[$i] = array();
            }

            // This will be a total count of our users
            $tmp_sessions = array();

            foreach( $stats_data as $set )
            {
                list( $TIME, $TS, $METHOD, $URL,
                      $OS, $BROWSER, $BROWSER_VERSION, $BROWSER_IP,
                      $SID, $MASTER_TPL, $TPL_ORDER ) = $set;

                // Check if we have counted in our sessionId yet
                if ( ! array_key_exists( $SID, $tmp_sessions ) )
                {
                    $tmp_sessions[ $SID ] = array( $URL );

                    if ( ! array_key_exists( "$BROWSER/$BROWSER_VERSION", $stats_browser ) )
                    {
                        $stats_browser[ "$BROWSER/$BROWSER_VERSION" ] = 1;

                    } else {

                        $stats_browser[ "$BROWSER/$BROWSER_VERSION" ]++;
                    }

                    if ( ! array_key_exists( $OS, $stats_os ) )
                    {
                        $stats_os[ $OS ] = 1;
                    } else {
                        $stats_os[ $OS ]++;
                    }

                } else {

                    $tmp_sessions[ $SID ][] = $URL;
                }

                // Sort by URL
                $tTime = (int) preg_replace('_:.*$_','',$TIME);
                $stats_hits[ $tTime ]++;

                if ( ! in_array( $SID, $stats_pi[$tTime] ) )
                {
                    $stats_pi[$tTime][] = $SID;
                }

                if ( ! array_key_exists( $URL, $stats_url ) )
                {
                    $stats_url[$URL] = 1;
                } else {
                    $stats_url[$URL]++;
                }


            }

            $stats_os_legend = array();

            foreach( $stats_os as $key => $value )
            {
                $stats_os_legend[] = ucfirst($key) . " ($value)";
            }

            foreach( $stats_pi as $key => $value )
            {
                $stats_pi[$key] = count($value);
            }

            $stats_browser_legend = array();
            $stats_browser_total = 0;

            foreach( $stats_browser as $key => $value )
            {
                $stats_browser_legend[] = ucfirst($key) . " ($value)";
                $stats_browser_total+=$value;
            }

            asort( $stats_url, SORT_NUMERIC );
            $stats_url = array_reverse( $stats_url );

            if ( count($stats_url) > 10)
            {
                $stats_url = array_slice($stats_url,0,10,true);
            }

            $number_names = array("zero", "one","two","three","four","five","six","seven","eight","nine","ten");

            return array(
                $stats_os, $stats_os_legend, $tmp_sessions,
                $stats_browser, $stats_browser_legend, $stats_browser_total,
                $stats_hits, $stats_data, $stats_url, $number_names,
                $stats_pi
            );
        }

        /*
         *
         * name: detectOS
         *
         * Taken from http://www.geekpedia.com/code47_Detect-operating-system-from-user-agent-string.html
         * by Kevin F. on Friday, February 11th 2011 at 05:57 PM
         *
         * RegexSimplifications by derRaphael
         *
         * @param
         * @return string
         *
         */
        public function detectOS()
        {
            // Oslist is from
            // by brickmasterj on Wednesday, May 2nd 2012 at 01:57 AM
            $osList = array
            (
                // Mircrosoft Windows Operating Systems
                'Windows 3.11' => '(Win16)',
                'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)',
                'Windows 98' => '(Windows 98)|(Win98)',
                'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
                'Windows 2000 Service Pack 1' => '(Windows NT 5.01)',
                'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
                'Windows Server 2003' => '(Windows NT 5.2)',
                'Windows Vista' => '(Windows NT 6.0)|(Windows Vista)',
                'Windows 7' => '(Windows NT 6.1)|(Windows 7)',
                'Windows 8' => '(Windows NT 6.2)|(Windows 8)',
                'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
                'Windows ME' => '(Windows ME)|(Windows 98; Win 9x 4.90 )',
                'Windows CE' => '(Windows CE)',
                // UNIX Like Operating Systems
                'Mac OS X Kodiak (beta)' => '(Mac OS X beta)',
                'Mac OS X Cheetah' => '(Mac OS X 10.0)',
                'Mac OS X Puma' => '(Mac OS X 10.1)',
                'Mac OS X Jaguar' => '(Mac OS X 10.2)',
                'Mac OS X Panther' => '(Mac OS X 10.3)',
                'Mac OS X Tiger' => '(Mac OS X 10.4)',
                'Mac OS X Leopard' => '(Mac OS X 10.5)',
                'Mac OS X Snow Leopard' => '(Mac OS X 10.6)',
                'Mac OS X Lion' => '(Mac OS X 10.7)',
                'Mac OS X' => '(Mac OS X)',
                'Mac OS' => '(Mac_PowerPC)|(PowerPC)|(Macintosh)',
                'Open BSD' => '(OpenBSD)',
                'SunOS' => '(SunOS)',
                'Solaris %s' => 'Solaris[ /](9|10|11)',
                'CentOS' => '(CentOS)',
                'QNX' => '(QNX)',
                // Kernels
                'UNIX' => '(UNIX)',
                // Linux Operating Systems
                'Ubuntu 9.10' => '(Ubuntu/9.10)|(Ubuntu 9.10)',
                'Ubuntu 9.04' => '(Ubuntu/9.04)|(Ubuntu 9.04)',
                'Ubuntu 8.10' => '(Ubuntu/8.10)|(Ubuntu 8.10)',
                'Ubuntu 8.04 LTS' => '(Ubuntu/8.04)|(Ubuntu 8.04)',
                'Ubuntu 6.06 LTS' => '(Ubuntu/6.06)|(Ubuntu 6.06)',
                'Ubuntu %s.%s' => 'Ubuntu[/ ](\d\d)\.(\d\d)',
                'Ubuntu (Unknown Version)' => 'Ubuntu',
                'Red Hat Enterprise Linux' => '(Red Hat Enterprise)',
                'Red Hat Linux' => '(Red Hat)',
                'Fedora %s' => 'Fedora[/ ](/\d+)',
                'Chromium OS' => '(ChromiumOS)',
                'Google Chrome OS' => '(ChromeOS)',
                // Kernel
                'Linux' => '(Linux)|(X11)',
                // BSD Operating Systems
                'OpenBSD' => '(OpenBSD)',
                'FreeBSD' => '(FreeBSD)',
                'NetBSD' => '(NetBSD)',
                // Mobile Devices
                'Andriod' => '(Android)',
                'iPod' => '(iPod)',
                'iPhone' => '(iPhone)',
                'iPad' => '(iPad)',
                //DEC Operating Systems
                'OS/8' => '(OS/8)|(OS8)',
                'Older DEC OS' => '(DEC)|(RSTS)|(RSTS/E)',
                'WPS-8' => '(WPS-8)|(WPS8)',
                // BeOS Like Operating Systems
                'BeOS' => '(BeOS)|(BeOS r5)',
                'BeIA' => '(BeIA)',
                // OS/2 Operating Systems
                'OS/2 2.0' => '(OS/220)|(OS/2 2.0)',
                'OS/2' => '(OS/2)|(OS2)',
                // Search engines
                'Search engine or robot (%s)' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(msnbot)|(Ask Jeeves/Teoma)|(ia_archiver)'
            );

            $useragent = $_SERVER['HTTP_USER_AGENT'];
            $useragent = strtolower($useragent);

            foreach($osList as $os=>$match) {
                if (preg_match('~' . $match . '~i', $useragent, $match)) {

                    // Changed due to RegexSimplifications
                    if ( preg_match( '_%s_', $os ) && count($match) > 1 )
                    {
                        array_shift($match);
                        $os = vsprintf( $os, $match );

                        // Assuming LTS versions become available at *.04 edition of an even yr
                        if ( stripos( $os, "Ubuntu" ) !== false && $match[1] == "04" && (int) $match[0]%2 == 0 )
                        {
                            $os .= " LTS";
                        }
                    }

                    break;
                } else {
                    $os = "Unknown OS";
                }
            }

            return $os;

        }

        /*
         *
         * name: getBrowser
         *
         * Taken from http://www.php.net/manual/de/function.get-browser.php#101125
         * by ruudrp at live dot nl
         *
         * @param
         * @return array
         *
         */
        public function getBrowser()
        {
            $u_agent = $_SERVER['HTTP_USER_AGENT'];
            $bname = 'Unknown';
            $version= "";
            $ub = "Other";

            //First get the platform?
            $platform = $this->detectOS();

            // Next get the name of the useragent yes seperately and for good reason
            if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
            {
                $bname = 'Internet Explorer';
                $ub = "MSIE";
            }
            elseif(preg_match('/Firefox/i',$u_agent))
            {
                $bname = 'Mozilla Firefox';
                $ub = "Firefox";
            }
            elseif(preg_match('/Chromium/i',$u_agent))
            {
                $bname = 'Chromium';
                $ub = "Chromium";
            }
            elseif(preg_match('/Chrome/i',$u_agent))
            {
                $bname = 'Google Chrome';
                $ub = "Chrome";
            }
            elseif(preg_match('/Safari/i',$u_agent))
            {
                $bname = 'Apple Safari';
                $ub = "Safari";
            }
            elseif(preg_match('/Opera/i',$u_agent))
            {
                $bname = 'Opera';
                $ub = "Opera";
            }
            elseif(preg_match('/Netscape/i',$u_agent))
            {
                $bname = 'Netscape';
                $ub = "Netscape";
            }

            // finally get the correct version number
            $known = array('Version', $ub, 'other');
            $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
            if (!preg_match_all($pattern, $u_agent, $matches)) {
                // we have no matching number just continue
            }

            // see how many we have
            $i = count($matches['browser']);
            if ($i != 1) {
                //we will have two since we are not using 'other' argument yet
                //see if version is before or after the name
                if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                    $version= $matches['version'][0];
                }
                else if ( count( $matches['version'] ) >= 2 ){
                    $version= $matches['version'][1];
                }
            }
            else {
                $version= $matches['version'][0];
            }

            // check if we have a number
            if ($version==null || $version=="") {$version="?";}

            return array(
                // 'userAgent' => $u_agent,
                'name'      => $bname,
                'version'   => $version,
                'platform'  => $platform,
                // 'pattern'    => $pattern
            );
        }
    }
}

/** ABSTRACT:

    <span class="x-synopsis">
        simplepagelog is a simple file logging facility which
        gives a generic overview of your webapps' usage.
     </span>

    <span class="x-author">
        derRaphael
    </span>

    <span class="x-version">
        0.1.8
    </span>

    <span class="x-licence">
        3-clause BSD
    </span>

    <span class="x-info">
        tiny core hook
    </span>

**/


?>