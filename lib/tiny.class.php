<?php
/*
 * tiny.class.php
 *
 * Copyright 2012 derRaphael <software@itholic.org>
 *
 * Version 0.2.3
 *
 * Tiny aims to be a small fast and reliable templating engine.
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
 *
 * The tinyTpl engine and it's related interface definition resides only
 * with the tinyTpl namespace.
 *
**/
namespace tinyTpl
{
    class tiny
    {
        /*
         * Public vars
         */

        // Creates a ref to template directory
        public $tplDir  = ""

        // Default template extension
           ,   $tplExt  = ".php"

        // Default Notfound Error handler
           ,   $tpl404  = "404"

        // Default Internal Error handler
           ,   $tpl500  = "500"

        // Default Detailed Error handler
           ,   $tplXXX  = "xxx"

        // Default template dir
           ,   $default_tpl_dir  = "/tpl/"

        // Default template logic dir
           ,   $default_tpl_logic_dir  = "/tplLogic/"

        // Default master template dir
           ,   $default_master_tpl_dir  = "master_tpl/"

        // Default error template dir
           ,   $default_error_tpl_dir  = "error_tpl/"

        // Default index template
           ,   $default_template  = "default"

        // Default index template
           ,   $TINY_TPL_CONTENT_PLACEHOLDER  = "{TINY_TPL_CONTENT}"

        // Container for rendered html
           ,   $html    = null

        // Container for passed arguments
           ,   $args    = null

        // Container for current action
           ,   $action  = null

        // Container for uri
           ,   $uri     = null

        // Check for ajax calls
           ,   $isAjax  = false

        // Flag for session usage
           ,   $use_sessions  = true

        // Flag for custom session engine usage
           ,   $use_session_engine  = false

        // Override dev state
        // It's a very very bad idea to change this variable from a tpl
        //
        // DO NOT RELY ON THIS AS THIS IS SUBJECT TO CHANGE IN FUTURE VERSIONS
        // ALSO DO NOT USE THIS, AS THIS MAY BE A POTENTIAL SECURITY BREACH
        //
        // UNLESS YOU KNOW WHAT THIS MAY CAUSE: D O   N O T   C H A N G E !!!
        // YOU HAVE BEEN WARNED!
        //
           ,   $dev_state_override  = null

        // Flag for development state - possible values:
        //      dev, stable
           ,   $dev_state  = "dev"
        ;

        /*
         * Class Constants
         */
        const VERSION = "0.2.3";

        // Standard Master Template
        const MASTER_TEMPLATE = "master_tpl";

        // Undefined
        const UNDEFINED = "TINY_UNDEFINED_STATE";

        // Safemode for Filename translations
        const TPL_NAME_SAFEMODE = true;

        // Used when base templates are rendered
        const TINY_TEMPLATE_MODE = true;

        // Used, when internal template calls are made
        const TINY_IN_TEMPLATE_MODE = null;

        // Used, when internal template calls are made
        const DEV_STATE_OVERRIDE = -1;

        /*
         * Public static vars
         */
        public static
              // $sys holds a reference to the singleton object
              $sys

              // If Session handling is enabled, $SESSION_NAME
              // will hold the Server name
            , $SESSION_NAME = null

              // This Variable holds the Session handler used by
              // tinyTpl.
            , $SessionHandler = "tinySession";

        /*
         * Private vars
         */
        private
               // $_store keeps a collection of objects for the observer
               // pattern which allows hooking virtually any function in
               // the tinyTpl engine.
               // Each Observer Object should implement the interface
               $_store;

        /*******************************************************************
         *                    Singleton Construction
         *
         */

        /*
         *
         * name: sys
         *
         * Singleton Constructor
         *
         * Added in v0.1
         *
         * @param
         * @return singleton
         *
         */
        final public static function sys()
        {
            if ( self::$sys == null )
            {
                self::$sys = new self();

                /**
                 * At this point the singleton is created
                 * Get the configuration variables, to override any
                 * preset defaults if neccessary.
                 *
                 * This process will be repeated at a later stage again
                 * allowing to change configuration at runtime
                 *
                 * Added functionality in v0.2.2
                **/
                self::$sys->check_config_namespace();


                // Register Paths as early as possible
                $base = dirname( $_SERVER["DOCUMENT_ROOT"] );
                self::$sys->base = $base;

                $include_dir = array( get_include_path() );

                // Include all directories found in /lib
                $lib_iterator = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator(
                            $base . "/lib",
                            \FilesystemIterator::SKIP_DOTS
                        ),
                        \RecursiveIteratorIterator::SELF_FIRST
                    );


                /**
                 * The following needs to be in in a try catch block,
                 * because under certain conditions such a lost+found
                 * directories in linux it may throw an exception
                **/

                try {
                    foreach( $lib_iterator as $data => $object )
                    {
                        if ( $object->isDir()  )
                        {
                            $include_dir[] = $object->getPathname();
                        }
                    }
                } catch (UnexpectedValueException $e) {}

                // Extend include path
                set_include_path( join( PATH_SEPARATOR, $include_dir ) );

                // Register default extension
                spl_autoload_extensions('.php');

                // Register new autoloader
                spl_autoload_register( array(self::$sys,"__autoload") );

                // Register ObserverStorage
                self::$sys->_store = new \SplObjectStorage();

                /*/
                 *
                 * Earliest possible place to register all hooks.
                 *
                 * If the cache directory exists and has a hook directory in
                 * it, the hooks should be read from this directory.
                 * This allows enabling/disabling hooks at runtime as a
                 * tinyAdmin.
                 * Otherwise it will enable all found hooks in the lib/hook
                 * directory of the tinyTpl engine.
                 *
                 * This behaviour was added in v0.2.1
                 *
                **/

                if ( is_dir( $base . "/cache" )
                  && is_dir( $base . "/cache/hooks" )
                  && is_writeable( $base . "/cache/hooks" )
                ) {
                    $dir = new \RecursiveDirectoryIterator( $base . "/cache/hooks" );
                } else {
                    $dir = new \RecursiveDirectoryIterator( $base . "/lib/hooks" );
                }

                $ite = new \RecursiveIteratorIterator($dir);
                $reg = new \RegexIterator($ite, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

                foreach( $reg as $observerHook )
                {
                    // Load observer
                    require_once $observerHook[0];

                    /**
                     * Cleanup name and register observer into tinyTpl
                     *
                     * This implies that all observer are named exactly the same
                     * way as the file the reside in.
                     * Otherwise the autoloader tries to load a class named
                     * as the file which may lead into errorprone behaviour.
                     *
                    **/
                    $observer = '\\tinyTpl\\hooks\\' . preg_replace('_.*/|\..*$_','',$observerHook[0]);

                    self::$sys->attach_observer( new $observer );

                }

                // hand over to __init which might be extended by hooks.
                self::$sys->__init();

            }
            return self::$sys;
        }

        /*******************************************************************
         *                   Internal Class Functions
         *
         */

        /*
         *
         * name: check_config_namespace
         *
         * This function has been declared private, to avoid having it
         * called from any public accessible routines.
         * It enables parsing the \tinyTpl\config namespace and replace
         * any preset variables.
         *
         * Added in v0.2.2
         *
         * @param
         * @return
         *
         */
        private function check_config_namespace()
        {

            if ( class_exists("\\tinyTpl\\config", false) )
            {
                /**
                 * List of variable names as array
                 * This list also shows which class properties
                 * may be changed in order to affect tinyTpl's
                 * behaviour.
                **/
                $internal_props = array(
                   'tplExt',
                   'tpl404',
                   'tpl500',
                   'tplXXX',
                   'default_tpl_dir',
                   'default_tpl_logic_dir',
                   'default_master_tpl_dir',
                   'default_error_tpl_dir',
                   'default_template',
                   'TINY_TPL_CONTENT_PLACEHOLDER',
                   'use_sessions',
                   'use_session_engine',
                   'dev_state',
                );

                foreach( $internal_props as $property )
                {
                    if ( isset( \tinyTpl\config::${$property} ) )
                    {
                        $this->$property = \tinyTpl\config::${$property};
                    }
                }

                /**
                 * List of variable names as array
                 * This list also shows which class properties
                 * may be changed in order to affect tinyTpl's
                 * behaviour.
                **/
                $static_props = array(
                    'SESSION_NAME',
                    'SessionHandler',
                );

                foreach( $static_props as $property )
                {
                    if ( isset( \tinyTpl\config::${$property} ) )
                    {
                        self::${$property} = \tinyTpl\config::${$property};
                    }
                }
            }

            // Define ErrorReporting behaviour
            if ( $this->dev_state === "dev" )
            {
                ini_set('error_reporting', -1);
                ini_set('display_errors', "off");

            } else {

                ini_set('error_reporting', -1);
                // ini_set('error_reporting', 0); // def
                // ini_set('display_errors', "off"); // def
                ini_set('display_errors', "on");
            }

        }

        /*
         *
         * name: __construct
         *
         * Unused class constructor
         * Added in v0.1
         *
         * @param
         * @return
         *
         */
        public function __construct(){}

        /*
         *
         * name: __alt_construct
         *
         * Alternate constructor: sets base dir and extends include path
         * Added in v0.1
         *
         * @param
         * @return
         *
         */

        public function __alt_construct()
        {
            $this->notify_observer( __METHOD__, 0 );

            // The register shutdown catches unrecoverable error such as
            // calling non existing functions
            register_shutdown_function(  '\tinyTpl\tiny::handle_shutdown' );

            // The error_handler covers errors, such as undefined variables
            set_error_handler( '\tinyTpl\tiny::handle_error' );

            // This handles exceptions
            set_exception_handler(  '\tinyTpl\tiny::handle_exception' );

            $this->notify_observer( __METHOD__, 255 );

        }

        /*
         *
         * name: __autoload
         *
         * RT Autoloader for Classes
         * Added in v0.1
         *
         * @param $classname
         * @return
         *
         */
        public function __autoload( $class )
        {
            require_once $class . ".class.php";
        }

        /*
         *
         * name: __init
         *
         * Init triggers the template generation
         *
         * Added in v0.1
         *
         * @param
         * @return
         *
         */

        public function __init()
        {
            $this->notify_observer( __METHOD__, 0 );

            // Start the alternate constructor
            $this->__alt_construct();

            // BUILD Session name depending on Server Name
            if ( self::$SESSION_NAME === null )
            {
                self::$SESSION_NAME = strtoupper( preg_replace('_[^a-z0-9]_i','-',$_SERVER["HTTP_HOST"]) );
            }

            // Instatinate session handling
            if ( $this->use_sessions === true )
            {
                $this->start_session();
            }

            $this->translation_array = array();
            $this->trigger_templates = array();

            $this->placeholder_dir = '';

            $this->template_dir  = dirname( $_SERVER["DOCUMENT_ROOT"] ) . $this->default_tpl_dir;
            $this->tpl_logic_dir = dirname( $_SERVER["DOCUMENT_ROOT"] ) . $this->default_tpl_logic_dir;

            // Set the proper method for this requets
            $this->method  = ( $_SERVER["REQUEST_METHOD"] != "" ? $_SERVER["REQUEST_METHOD"] : "GET" );

            // Sets flag if current request is ajax
            $this->isAjax  =  (
                                (
                                        isset( $_SERVER[ "X_REQUESTED_WITH" ] )
                                     && $_SERVER[ "X_REQUESTED_WITH" ] == "XMLHttpRequest"
                                 ) || (
                                        isset( $_SERVER[ "HTTP_X_REQUESTED_WITH" ] )
                                     && $_SERVER[ "HTTP_X_REQUESTED_WITH" ] == "XMLHttpRequest"
                                 )
                             ) ? true : false;

            // Remove any SEO suffixes
            $_SERVER[ "REQUEST_URI" ] = preg_replace( ';\.html\b;', '', $_SERVER[ "REQUEST_URI" ] );

            // Build arguments for qualifying the current action
            $this->args = explode( '/', preg_replace( '/^\/(index\.php(\?|\/))?|\/$/i', "", $_SERVER["REQUEST_URI"] ) );

            $this->notify_observer( __METHOD__, 254 );

            $this->build_action();

            $this->notify_observer( __METHOD__, 255 );

        }

        /*
         *
         * name: build_action
         *
         * Picks current action
         *
         * Added in v0.1
         *
         * @param
         * @return
         *
         */
        final protected function build_action()
        {
            // Grab action either from 1st passed argument or 1st $_GET param
            if ( count( $this->args ) ) {
                $this->action = preg_replace( '_\?.*|&.*_', '', $this->args[0] );
            } else {
                $this->action  = array_shift( array_keys( $_GET ) );
            }
            $this->action  = $this->action == "" ? $this->default_template : $this->action;
            $this->action  = preg_replace( '/^\/|\/.*/i', "", $this->action);
        }


        /*******************************************************************
         *                      Render Functions
         *
         */


        /*
         *
         * name: html
         *
         * This function effectively renders the final output.
         *
         * Added in v0.1
         *
         * @param string $MASTER_TEMPLATE
         * @return
         *
         */
        final public function html( $MASTER_TEMPLATE = self::UNDEFINED )
        {
            $this->notify_observer( __METHOD__, 0 );

            // Defauls master template to predefined constant
            if ( $MASTER_TEMPLATE === self::UNDEFINED )
            {
                $MASTER_TEMPLATE = self::MASTER_TEMPLATE;
            }

            // Set new global template
            $this->MASTER_TEMPLATE = $MASTER_TEMPLATE;
            $MASTER_TEMPLATE_FILENAME = $this->default_master_tpl_dir . $this->MASTER_TEMPLATE;

            if ( ( $this->dev_state === "dev" || self::DEV_STATE_OVERRIDE === $this->dev_state_override ) &&
                ( ! file_exists( $this->template_dir . $MASTER_TEMPLATE_FILENAME . $this->tplExt )
                || $this->args[0] == "tinyAdmin" )
            ) {
                if ( $this->args[0] != "tinyAdmin" )
                {
                    header('Location: /tinyAdmin/default.html');
                    die();
                }

                if ( $this->args[0] == "tinyAdmin" )
                {
                    array_shift( $this->args );
                    if ( count($this->args) == 0 )
                    {
                        $this->args[0] = "default";
                    }
                    // Set defaults
                    $defaults = array(
                        "tplExt" => ".php",
                        "tpl404" => "404",
                        "tpl500" => "500",
                        "tplXXX" => "xxx",
                        "default_tpl_logic_dir" => "/tplLogic/",
                        "default_master_tpl_dir" => "master_tpl/",
                        "default_error_tpl_dir" => "error_tpl/",
                        "default_template" => "default",
                        "TINY_TPL_CONTENT_PLACEHOLDER" => "{TINY_TPL_CONTENT}"
                    );

                    foreach( $defaults as $name => $value )
                    {
                        $this->$name = $value;
                    }

                    // Rebuild action for proper rendering
                    $this->build_action();
                }

                $this->placeholder_dir = 'config_tpl/';

                // reset placeholder directory if it is non existent
                if ( ! is_dir( $this->template_dir . $this->placeholder_dir ) )
                {
                    $this->placeholder_dir = '';
                }
                $MASTER_TEMPLATE_FILENAME = $this->MASTER_TEMPLATE;

            } else {
                $this->placeholder_dir = '';
            }

            // Render data
            $TINY_DATA = $this->render_template( self::TINY_TEMPLATE_MODE )->html;

            if ( $this->MASTER_TEMPLATE != null )
            {
                // Set new Action & render MASTERTEMPLATE
                $this->action = $MASTER_TEMPLATE_FILENAME;
                $TINY_TEMPLATE = $this->render_template( null, null )->html;

            } else {

                // If our template is null - it is disabled - just set a blank
                $TINY_TEMPLATE = $this->TINY_TPL_CONTENT_PLACEHOLDER;
            }

            // Set data
            $TINY_TEMPLATE = str_replace( $this->TINY_TPL_CONTENT_PLACEHOLDER, $TINY_DATA, $TINY_TEMPLATE );

            $this->html = $TINY_TEMPLATE;

            $this->notify_observer( __METHOD__, 255 );

            return $this->html;
        }

        /*
         *
         * name: render_template
         *
         * This function renders the templates.
         *
         * Added in v0.1
         *
         * @param string $MASTER_TEMPLATE
         * @return
         *
         */
        final public function render_template( $TEMPLATE_MODE = null, $TRANSFORM_FILENAME = self::TPL_NAME_SAFEMODE )
        {
            $this->notify_observer( __METHOD__, 0 );

            // Check if filename needs transformation
            if ( $TRANSFORM_FILENAME == self::TPL_NAME_SAFEMODE )
            {
                $TEMPLATE_FILENAME = $this->template_dir . $this->placeholder_dir . preg_replace( '/\W/','',$this->action) . $this->tplExt;
            } else {
                $TEMPLATE_FILENAME = $this->template_dir . $this->placeholder_dir . preg_replace( ':[^-/a-z0-9_]:i','',$this->action) . $this->tplExt;
            }

            // Do the template magic
            if ( is_readable ( $TEMPLATE_FILENAME  ) ) {

                $this->read_template( $TEMPLATE_FILENAME );

                $this->notify_observer( __METHOD__, 50 );

            } else if( ! is_readable ( $TEMPLATE_FILENAME  ) && $TEMPLATE_MODE === self::TINY_TEMPLATE_MODE ) {

                // File is not readable and we're not in template mode - build the 404 filename
                $TEMPLATE_FILENAME = $this->template_dir . $this->default_error_tpl_dir . $this->tpl404 . $this->tplExt;

                $this->MASTER_TEMPLATE = null;

                // Check if we have a valid 404 template
                if ( is_readable ( $TEMPLATE_FILENAME  ) ) {

                    $this->read_template( $TEMPLATE_FILENAME );

                } else {

                    $STR = "<html><head><title>404 - Not Found</title></head>"
                        . '<body style="color:#888;background:#000;"><div style="width:960px;margin:0 auto;margin-top:2em;"><h1>Not Found</h2>'
                        . "<p>The requested URL was not found on this server.";

                    if ( isset( $this->tpl404 ) && ! is_readable ( $TEMPLATE_FILENAME  ) )
                    {
                        $STR .= " Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request.";
                    }
                    $STR .= "</p>"
                        . '<hr size=1><p style="text-align: center;">tinyTpl v'.self::VERSION."</p></div></body></html>";

                    $this->html = $STR;

                }

            }

            $this->notify_observer( __METHOD__, 255 );

            return $this;
        }

        /*
         *
         * name: read_template
         *
         * This function reads template and executes it
         *
         * Added in v0.1
         *
         * @param string $TEMPLATE_FILENAME
         * @return
         *
         */
        final public function read_template( $TEMPLATE_FILENAME, $SET_HTML = true )
        {
            $this->notify_observer( __METHOD__, 0 );

            ob_start();
            include( $TEMPLATE_FILENAME );
            if ( $SET_HTML == true )
            {
                $this->html = ob_get_contents();
                ob_end_clean();

            } else if ( $SET_HTML == false ) {

                $DATA = ob_get_contents();
                ob_end_clean();

                return $DATA;
            }

            $this->notify_observer( __METHOD__, 255 );
        }

        /*
         *
         * name: use_template
         *
         * This function allows nesting templates
         *
         * Added in v0.1
         *
         * @param string $action
         * @return
         *
         */
        final public function use_template( $action, $safemode = false )
        {
            $this->notify_observer( __METHOD__, 0 );

            $this->action = $action;
            $this->render_template( self::TINY_IN_TEMPLATE_MODE, $safemode );

            $this->notify_observer( __METHOD__, 254 );

            $this->build_action();

            $this->notify_observer( __METHOD__, 255 );
            return $this;
        }

        /*
         *
         * name: add_template_trigger
         *
         * Register a given trigger with a template
         *
         * Added in v0.1
         *
         * @param string $action
         * @return
         *
         */
        final public function add_template_trigger( $TINY_TRIGGER, $TINY_TEMPLATE )
        {
            $this->template_trigger_collection[ $TINY_TRIGGER ] = $TINY_TEMPLATE;
        }

        /*
         *
         * name: chk_tpl_trigger
         *
         * Checks for a registered trigger with a substitute default template
         *
         * Added in v0.1
         *
         * @param string $TINY_TRIGGER
         * @param string $DEFAULT
         * @return
         *
         */
        final public function chk_tpl_trigger( $TINY_TRIGGER, $DEFAULT = null )
        {
            if ( is_array( $this->template_trigger_collection ) && array_key_exists( $TINY_TRIGGER, $this->template_trigger_collection ) )
            {
                return $this->use_template( $this->template_trigger_collection[$TINY_TRIGGER] )->html;

            } else if ( $DEFAULT != null ) {

                return $this->use_template( $DEFAULT )->html;
            }
        }

        /*******************************************************************
         *                  Error handling Functions
         *
         */

        /*
         *
         * name: failsafe_render
         *
         * This function renders the standard XXX (custom) error page
         * Added in v0.1
         *
         * @param string $TEMPLATE_FILENAME
         * @return
         *
         */
        public function failsafe_render( $TINY_DATA = "" )
        {
            $this->MASTER_TEMPLATE = null;

            if ( $TINY_DATA == "" )
            {
                $TINY_DATA = "<p>There's something broken. That's all we know.</p>\n";
            }

            $template_dir  = dirname( $_SERVER["DOCUMENT_ROOT"] ) . $this->default_tpl_dir;

            $TEMPLATE_FILENAME = $template_dir . $this->default_error_tpl_dir . $this->tplXXX . $this->tplExt;

            // Check if we have a valid XXX template
            if ( is_readable ( $TEMPLATE_FILENAME  ) ) {

                $STR = str_replace( $this->TINY_TPL_CONTENT_PLACEHOLDER, $TINY_DATA, file_get_contents( $TEMPLATE_FILENAME ) );

            } else {

                $STR = "<html><head><title>An error occured</title></head>"
                    . '<body style="color:#888;background:#000;"><div style="width:960px;margin:0 auto;;margin-top:2em;"><h1>An error occured</h2>'
                    . $TINY_DATA;

                if ( isset( $this->tplXXX ) && ! is_readable ( $TEMPLATE_FILENAME  ) )
                {
                    $STR .= " Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request.";
                }
                $STR .= "</p>"
                    . '<hr size=1><p style="text-align: center;">tinyTpl v'.self::VERSION."</p></div></body></html>";

            }

            die( $STR );
        }

        /*
         *
         * name: handle_shutdown
         *
         * Custom Shutdown Handler in case of a syntax error.
         * Added in v0.2.2
         *
         * @param $CODE
         * @param $MSG
         * @param $FILE
         * @param $LINE
         * @return
         *
         */
        public static function handle_shutdown()
        {
            if ( self::sys()->dev_state === "dev" )
            {
                $error = error_get_last();

                if($error !== NULL){

                    $MSG = preg_replace( '/\s*?\[<.*?>\]\:/', ':<br/>&nbsp;&nbsp;&nbsp;<b style="color:#fff;margin:0;padding:0;margin-left: 2em;">', $error['message'] ) . "</b>";
                    $FILE = basename( $error['file'] );
                    $LINE = $error['line'];
                    $RES =  "<h3 style=\"color:#f00;\">Unrecoverable Code Error</h3>".
                            "<p>$MSG</p>".
                            "<p>File: <b style=\"color:white\">$FILE</b> in line <b style=\"color:white\">$LINE</b></p>";

                    die ( self::sys()->failsafe_render( $RES, true ) );
                }
            }
        }


        /*
         *
         * name: handle_error
         *
         * Custom Error Handler
         * Added in v0.1
         *
         * @param $CODE
         * @param $MSG
         * @param $FILE
         * @param $LINE
         * @return
         *
         */
        public static function handle_error( $CODE, $MSG, $FILE, $LINE )
        {
            if ( self::sys()->dev_state == "stable" && isset( self::sys()->isAjax ) && self::sys()->isAjax !== true )
            {
                $template_dir  = dirname( $_SERVER["DOCUMENT_ROOT"] ) . self::sys()->default_tpl_dir;

                $TEMPLATE_FILENAME = $template_dir . self::sys()->default_error_tpl_dir . self::sys()->tpl500 . self::sys()->tplExt;

                // Check if we have a valid XXX template
                if ( is_readable ( $TEMPLATE_FILENAME  ) ) {

                    $STR = file_get_contents( $TEMPLATE_FILENAME );

                } else {

                    $STR = "<html><head><title>An error happened</title></head>"
                        . '<body style="color:#888;background:#000;"><div style="width:960px;margin:0 auto;;margin-top:2em;"><h1>An error occured</h2>'
                        . "<p>That's all we know.</p>";

                    if ( isset( self::sys()->tpl500 ) && ! is_readable ( $TEMPLATE_FILENAME  ) )
                    {
                        $STR .= " Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request.";
                    }
                    $STR .= "</p>"
                        . '<hr size=1><p style="text-align: center;">tinyTpl v'.self::VERSION."</p></div></body></html>";

                }

                die( $STR );

            }

            if ( isset( self::sys()->isAjax ) && self::sys()->isAjax == true )
            {
                // We're responding to an ajax call. Do something.
                if ( self::sys()->dev_state == "stable" )
                {
                    // Only set header in stable mode, otherwise debug information wont be displayed properly
                    // under some curcumstances
                    $header_prefix = self::get_proper_header();
                    header( $header_prefix . " 500 Internal Server Error.", true, 500 );
                    $result = array("A server error occured. That's all we know.");

                } else {
                    $result = array(
                        "file" => basename( $FILE ),
                        "line" => $LINE,
                        "msg" => $MSG,
                        "code" => $CODE
                    );
                }
                // dump the error message
                self::dump_ajax_error( $result );
                die();
            }

            $MSG = preg_replace( '/\s*?\[<.*?>\]\:/', ':<br/>&nbsp;&nbsp;&nbsp;<b style="color:#fff;margin:0;padding:0;margin-left: 2em;">', $MSG ) . "</b>";
            $FILE = basename( $FILE );
            $RES =  "<h3 style=\"color:#f00;\">Code $CODE Error</h3>".
                    "<p>$MSG</p>".
                    "<p>File: <b style=\"color:white\">$FILE</b> in line <b style=\"color:white\">$LINE</b></p>";

            die ( self::sys()->failsafe_render( $RES, true ) );

        }

        /*
         *
         * name: dump_ajax_error
         *
         * Dumps an ajax error and makes differences between json and pure javascript
         * When in javascript mode it tries to make use of console.log and alert otherwise
         *
         * Added in v0.2.2
         *
         * @return string
         *
         */
        public static function dump_ajax_error( $results = array( "An error occured. That's all we know." ) )
        {
            if ( isset( self::sys()->isAjax ) && self::sys()->isAjax == true )
            {
                // We're ajax and JSON dump given result in proper format.
                if ( array_key_exists('HTTP_ACCEPT', $_SERVER) && preg_match( '_json_i', $_SERVER['HTTP_ACCEPT'] ) )
                {
                    header("Content-type: application/json", true);
                    die( json_encode( $results ) );
                }

                header("Content-type: text/javascript", true);
                die( 'eval( ( window.console && console.log ? "console.log" : "alert" ) + "('.addslashes( json_encode($results) ).');" )' );
            }
        }

        /*
         *
         * name: handle_exception
         *
         * Custom Exception handler
         * Added in v0.1
         *
         * @param $ex Exception
         * @return
         *
         */
        public static function handle_exception( $ex )
        {
            // FCGI Check for proper Header
            $header_prefix = self::get_proper_header();
            header( $header_prefix . " 500 Internal Server Error.", true, 500 );

            try
            {
                $data  = self::sys()->trace_dump_exception( $ex );

                $FILE  = basename( $data["file"] );
                $LINE  = $data["line"];
                $MSG   = $data["msg"];
                $TRACE = "";

                foreach( $data["trace"] as $set )
                {
                    $col="#557";

                    // Remove any path-type indication
                    if ( isset( $set["args"] ) )
                    {
                        // Get last base pathtype;
                        $base = basename( dirname( $_SERVER['DOCUMENT_ROOT'] ) ) . "/";
                        $set["args"] = preg_replace( '_(?<=color\:#884;\"\>\').*?'.preg_quote($base,"_").'_', '.../', $set["args"] );
                    }

                    $a  = ( isset( $set["args"] ) ) ? '( <span class="tiny-exception-args" style="color:#fff">'.$set["args"]."</span> );" : "";
                    $f  = ( isset( $set["file"] ) ) ? basename( $set["file"] ) : "";
                    $l  = ( isset( $set["line"] ) ) ? $set["line"] : "";
                    $t  = ( isset( $set["type"] ) && $set["type"] != "type ( undefined )")
                                ? '<span class="tiny-exception-type" style="color:'.($col=$set["type"]=="->"?"#755":"#575").'">'.$set["type"] ."</span>"
                                : "";
                    $fn = ( isset( $set["function"] ) )
                                ? "<span class=\"tiny-exception-function\" style=\"color:$col\">".$set["function"] ."</span>"
                                : "";
                    $c  = ( isset( $set["class"] ) && $set["class"] != "class ( undefined )")
                                ? "<span class=\"tiny-exception-class\" style=\"color:$col\">".$set["class"] ."</span>"
                                : "";

                    $TRACE .= "<div style=\"width:95%;margin:0;padding:0;margin-left:2em;\">".
                              "<div style=\"float:left;margin:0;padding:0;color:#666\">$f</div>".
                              "<div style=\"float:right;margin:0;padding:0;color:#444\">Line $l</div></div>".
                              "<div style=\"width:100%;margin:0;padding:0;margin-bottom:.3em;margin-left:2.5em;clear:both;\">$c$t$fn $a</div>";
                }

                $res = "<h3 style=\"color:#f00;\">Exception: '". $data["exc"] ."'</h3>".
                    "<p>Msg: $MSG</p>".
                    "<p>File: <b style=\"color:white\">$FILE</b> in line <b style=\"color:white\">$LINE</b></p>" .
                    "$TRACE";

                die ( self::sys()->failsafe_render( $res, true ) );

            }
            catch (Exception $e)
            {
                print get_class($e)." thrown within the exception handler. Message: ".$e->getMessage()." on line ".$e->getLine();
            }
        }

        /*
         *
         * name: trace_dump_exception
         *
         * Dumps trace od Exception
         * Added in v0.1
         *
         * @param mixed var
         * @return string
         *
         */
        public function trace_dump_exception( $e )
        {
            $data["exc"]   = get_class($e);
            $data["msg"]   = $e->getMessage();
            $data["file"]  = $e->getFile();
            $data["line"]  = $e->getLine();
            $data["trace"] = array();

            $TRACE = $e->getTrace();

            if ( count($TRACE) )
            {
                foreach( $TRACE as $K => $D )
                {
                    $idx = count( $data["trace"] );

                    foreach( array( "file","line","function","class","type" ) as $key )
                    {
                        $data["trace"][$idx][$key] = array_key_exists( $key, $D ) ? $D[ $key ] : "$key ( undefined )";
                    }

                    if ( array_key_exists( "args", $D ) )
                    {
                        $arg0 = ( count( $D["args"] ) ) ? $D["args"][0] : false;
                        $type = self::sys()->get_type_of_var( $arg0 );

                        if ( $type == "object" ) {

                            $arg0 = "'". get_class( $arg0 ) ."'";

                        } else if ( $type == "NULL" && $arg0 === NULL ) {

                            $arg0 = "";

                        } else if ( $type == "bool" ) {

                            $arg0 = "<span style=\"color:#844;\">". ( $arg0 ? "true" : "false" ) ."</span>";

                        } else if ( $type == "string" ) {

                            $arg0 = "<span style=\"color:#884;\">'$arg0'</span>";

                        } else {
                            $arg0 = "'". $arg0 ."'";
                        }
                    } else {
                        $type = "NONE";
                        $arg0 = "";
                    }

                    $data["trace"][$idx]["args"] = "<span style=\"color:#244;\">($type)</span> " . $arg0 . "";
                }
            }

            return $data;
        }

        /*
         *
         * name: get_type_of_var
         *
         * Starts a session
         * Added in v0.1
         *
         * @param mixed var
         * @return string
         *
         */
        public function get_type_of_var( $var )
        {
            if (is_bool($var)) {
                $type='bool';
            } else if (is_int($var)) {
                $type='integer';
            } else if (is_float($var)) {
                $type='float';
            } else if (is_string($var)) {
                $type='string';
            } else if (is_array($var)) {
                $type='array';
            } else if (is_object($var)) {
                $type='object';
            } else if (is_resource($var)) {
                $type='resource';
            } else if (is_null($var)) {
                $type='NULL';
            } else {
                $type='unknown type';
            }

            return $type;
        }

        /*
         *
         * name: get_proper_header
         *
         * Returns neccessary header info for error response
         * Added in v0.2.2
         *
         * @return string
         *
         */
        public static function get_proper_header()
        {
            // FCGI Check for proper Header
            if ( any_array_key_exists( array( "FCGI_ROLE","PHP_FCGI_CHILDREN","PHP_FCGI_MAX_REQUESTS" ), $_SERVER ) )
            {
                $header_prefix = "Status:";

            } else if ( array_key_exists('SERVER_PROTOCOL', $_SERVER) ) {

                $header_prefix = $_SERVER["SERVER_PROTOCOL"];

            } else {

                $header_prefix = "HTTP/1.0";
            }
            return $header_prefix;
        }

        /*******************************************************************
         *                  Session Helper Functions
         *
         */

        /*
         *
         * name: start_session
         *
         * Starts a session
         * Added in v0.1
         *
         * @param
         * @return
         *
         */
        public function start_session()
        {
            // init db Sessionhandling
            if ( $this->use_session_engine == true )
            {
                $this->init_session_engine();
            }

            session_name( self::$SESSION_NAME );
            session_start();
        }

        /*
         *
         * name: rewind_session
         *
         * Destroys and restarts a session
         * Added in v0.1
         *
         * @param
         * @return
         *
         */
        public function rewind_session()
        {

            session_destroy();

            $this->start_session();

            $_SESSION = array();

        }

        /*
         *
         * name: init_session_engine
         *
         * Passes session functions to custom defined class
         * Added in 0.1
         * Changed name in 0.2.2
         *
         * @param
         * @return
         *
         */
        public function init_session_engine()
        {
            $SESSION_HANDLER = self::$SessionHandler;

            $this->session = new $SESSION_HANDLER();

            session_set_save_handler(array($this->session, 'open'),
                                    array($this->session, 'close'),
                                    array($this->session, 'read'),
                                    array($this->session, 'write'),
                                    array($this->session, 'destroy'),
                                    array($this->session, 'gc'));

        }

        /*******************************************************************
         *                  Class Helper Functions
         *
         */

        /*
         *
         * name: noop
         *
         * No Operation
         * Added in v0.1
         *
         * @param
         * @return
         *
         */
        public function noop(){}

        /*
         *
         * name: formatDayHourMinuteSecond
         *
         * Formats microtime timestamp to a string
         * Added in v0.1
         *
         * @param float $microtime
         * @return string
         *
         */
        public function formatDayHourMinuteSecond( $microtime ){
            list( $day,$hr,$min,$sec ) = microtime2DayHourMinuteSecond( $microtime );
            return ( $day != 0 ? ( "$day day" .($day!=1?"s ":" ") ) : "" ) . "$hr:$min:$sec";
        }

        /*
         *
         * name: formatDayHourMinuteSecond
         *
         * Translates microtime timestamp to a string
         * Added in v0.1
         *
         * @param float $microtime
         * @return array
         *
         */
        public function microtime2DayHourMinuteSecond( $microtime )
        {
            $seconds_a_day    = 24*60*60;
            $seconds_an_hour  = 60*60;
            $seconds_a_minute = 60;

            $days    = floor( $microtime / $seconds_a_day );
            $hours   = sprintf("%02d",floor( ($microtime-($days*$seconds_a_day))/$seconds_an_hour ));
            $minutes = sprintf("%02d",floor( ($microtime-($days*$seconds_a_day)-($hours*$seconds_an_hour))/$seconds_a_minute ));
            $seconds = $microtime-($days*$seconds_a_day)-($hours*$seconds_an_hour)-($minutes*$seconds_a_minute);
            $seconds = sprintf("%08.5f",$seconds);

            return array( $days,$hours,$minutes,$seconds );
        }


        /*******************************************************************
         *                    Hook Handler Functions
         *
         */

        /*
         *
         * name: attach_observer
         *
         * Register a tinyObserver
         * Added in v0.1
         *
         * @param tinyObserver $OBSERVER
         * @return
         *
         */
        public function attach_observer( hooks\tinyObserver $OBSERVER )
        {
            $this->_store->attach( $OBSERVER );
        }

        /*
         *
         * name: detach_observer
         *
         * Deregister a tinyObserver
         * Added in v0.1
         *
         * @param tinyObserver $OBSERVER
         * @return
         *
         */
        public function detach_observer( hooks\tinyObserver $OBSERVER )
        {
            $this->_store->detach( $OBSERVER );
        }

        /*
         *
         * name: notify_observer
         *
         * Notifies all registered tinyObserver
         * Added in v0.1
         *
         * @param tinyObserver $OBSERVER
         * @return
         *
         */
        public function notify_observer( $TARGET, $STAGE )
        {
            foreach( $this->_store as $Observer )
            {
                if ( in_array( $TARGET, $Observer->TARGETS ) )
                {
                    // echo "<!-- \n\tTARGET:   " . $TARGET . "\n\tSTAGE:    " . $STAGE . "\n\tOBSERVER: " . get_class($Observer) . "\n-->\n";
                    $Observer->trigger( $this, $STAGE, $TARGET );
                }
            }
        }
    }
}

/**
 * For better handling, all hooks for tinyTpl should only exists
 * in their own namespace
**/
namespace tinyTpl\hooks
{
    interface tinyObserver
    {
        /**
         *
         * name: trigger
         *
         * This defines the trigger function used by all hooks in the tinyTpl
         * engine. The function must accept 3 variables.
         *
         * @param $TINY   a reference to the tinyTpl Object Intance
         * @param $STAGE  a reference to the current stage (0-255)
         * @param $TARGET a reference to the function which triggered
         * @return
         *
        **/
        public function trigger( $TINY, $STAGE, $TARGET );
    }

}

/***********************************************************************
 *                         Start the fun
 *
 */

/**
 *
 * The tinyTpl helper functions are defined to be used in any document,
 * thus they have to be defined in the global namespace.
 *
**/

// Clean up namespace
namespace
{

    // Alias tiny to tinyTpl namespace
    use \tinyTpl\tiny as tiny;

    $tiny = tiny::sys();
    $tiny->noop();

    /***********************************************************************
     *                  Syntax Sugar Helper Functions
     *
     */


    /*
     *
     * name: tpl
     * Includes a simple template
     * Added in v0.1
     *
     * @param $TINY_TEMPLATE
     * @return string
     *
     */
    function tpl( $TINY_TEMPLATE )
    {
        return tiny::sys()->use_template( $TINY_TEMPLATE )->html;
    }

    /*
     *
     * name: tpl_on_state
     * Includes a simple template when a given condition is met
     * Added in v0.1
     *
     * @param $TINY_TEMPLATE
     * @param $CONDITION
     * @param $COMPARE_VALUE
     * @return string
     *
     */
    function tpl_on_state( $TINY_TEMPLATE, $CONDITION, $COMPARE_VALUE = true )
    {
        return ( $CONDITION == $COMPARE_VALUE ? tiny::sys()->use_template( $TINY_TEMPLATE )->html : '' );
    }

    /*
     *
     * name: tpls_on_state
     * Includes 1st template when a given condition is met, 2nd otherwise
     * Added in v0.2.2
     *
     * @param $TINY_TEMPLATE_1
     * @param $TINY_TEMPLATE_2
     * @param $CONDITION
     * @param $COMPARE_VALUE
     * @return string
     *
     */
    function tpls_on_state( $TINY_TEMPLATE_1, $TINY_TEMPLATE_2, $CONDITION, $COMPARE_VALUE = true )
    {
        return ( $CONDITION == $COMPARE_VALUE
            ? tiny::sys()->use_template( $TINY_TEMPLATE_1 )->html
            : tiny::sys()->use_template( $TINY_TEMPLATE_2 )->html );
    }

    /*
     *
     * name: trigger_tpl
     * Registers a trigger with a template
     * Added in v0.1
     *
     * @param $TINY_TRIGGER
     * @param $TINY_TEMPLATE
     * @return string
     *
     */
    function trigger_tpl( $TINY_TRIGGER, $TINY_TEMPLATE )
    {
        tiny::sys()->add_template_trigger( $TINY_TRIGGER, $TINY_TEMPLATE );
    }


    /*
     *
     * name: chk_tpl_trigger
     * Checks for a set trigger to include a specified template
     * Added in v0.1
     *
     * @param $TINY_TRIGGER
     * @param $DEFAULT
     * @return string
     *
     */
    function chk_tpl_trigger( $TINY_TRIGGER, $DEFAULT = null )
    {
        echo tiny::sys()->chk_tpl_trigger( $TINY_TRIGGER, $DEFAULT );
    }

    /*
     *
     * name: trigger_tpl_on_state
     * Triggers a simple template when a given condition is met
     * Added in v0.1
     *
     * @param $TINY_TRIGGER
     * @param $TINY_TEMPLATE
     * @param $CONDITION
     * @param $COMPARE_VALUE
     * @return string
     *
     */
    function trigger_tpl_on_state( $TINY_TRIGGER, $TINY_TEMPLATE, $CONDITION, $COMPARE_VALUE = true )
    {
        if ( $CONDITION === $COMPARE_VALUE )
        {
            trigger_tpl( $TINY_TRIGGER, $TINY_TEMPLATE );
        }
    }

    /*
     *
     * name: has_trigger_set
     * Checks if a trigger is set
     * Added in v0.2.2
     *
     * @param $TINY_TRIGGER
     * @return bool
     *
     */
    function has_trigger_set( $TINY_TRIGGER = null ){
        if ( is_array( tiny::sys()->template_trigger_collection )
          && array_key_exists( $TINY_TRIGGER, tiny::sys()->template_trigger_collection )
        ) {
            return true;
        }
        return false;
    }

    /*
     *
     * name: any_array_key_exists
     * Checks if one key of a given array collection is in a different array
     * Added in v0.1
     *
     * @param $keys
     * @param $array
     * @return bool
     *
     */
    function any_array_key_exists( $keys = array(), $array )
    {
        foreach( $keys as $key )
        {
            if ( array_key_exists( $key, $array ) )
            {
                return true;
            }
        }
        return false;
    }


    /***********************************************************************
     *                    Compressor primitives
     *
     */


    /*
     *
     * name: mini_css
     * Minifies a given CSS with rather non advanced str_replace's
     * Also Works for JSONified output. Just set the Flag.
     * Added in v0.2
     *
     * @param $css
     * @param $options
     * @return string
     *
     */
    function mini_css( $data = "", $options = array() ) {

        // Kick in cached versions, but only if we are not serving json
        $defaults = array(
                    "use_cache" => true,
                    "is_json" => false,
                    "show_stats" => true
                );

        if ( is_array( $options ) )
        {
            $options = array_merge( $defaults, $options );

        } else {

            $options = $defaults;
        }

        foreach( $defaults as $key => $value )
        {
            ${$key} = $options[ $key ];
        }

        if ( $is_json === false && $use_cache == true )
        {

            $cache = tiny::sys()->base . "/cache/";
            $sha1 = sha1( $data );
            $fn = $cache . $sha1 . ".css";

            if ( file_exists( $fn ) ) {
                return file_get_contents( $fn );
            }

        }

        $min = preg_replace( '#\s+#', ' ', $data );
        $min = preg_replace( '#/\*.*?\*'.'/#s', '', $min );
        $min = str_replace( array( "\r", "\n", "\t" ), "", $min );
        $min = str_replace( '; ', ';', $min );
        $min = str_replace( ': ', ':', $min );
        $min = str_replace( ' {', '{', $min );
        $min = str_replace( '{ ', '{', $min );
        $min = str_replace( ', ', ',', $min );
        $min = str_replace( '} ', '}', $min );
        $min = str_replace( ';}', '}', $min );
        $min = trim( $min );

        if ( $is_json == false )
        {
            $min = preg_replace( '_(.{70,90}[,;{} ])_', "$1\r\n", $min );
        } else {
            $min = preg_replace( '_(.{50,100}("[:,}]|[{]))_', "$1\r\n", $min );
        }

        if ( $show_stats == true )
        {

            $l1 = strlen($data);
            $l2 = strlen($min);
            $l3 = $l1-$l2;

            $p = round(100-($l2/$l1*100),2);

            $min = "/* $l1:$l2:$l3 ($p%) *"."/\r\n". $min;
        }

        // Cache versions, but only if we are not serving json
        if ( $is_json === false && $use_cache == true )
        {
            file_put_contents( $fn, $min );
        }

        return $min;
    }


    /*
     *
     * name: mini_js
     * Uses an external library if availble
     * Added in v0.2
     *
     * @param $js
     * @param $options
     * @return string
     *
     */
    function mini_js( $data = "", $options = array() )
    {
        $defaults = array(
                    "use_cache" => true,
                    "show_stats" => true
                );

        if ( is_array( $options ) )
        {
            $options = array_merge( $defaults, $options );

        } else {

            $options = $defaults;
        }

        foreach( $defaults as $key => $value )
        {
            ${$key} = $options[ $key ];
        }

        if ( $use_cache == true )
        {
            $cache = tiny::sys()->base . "/cache/";
            $sha1 = sha1( $data );
            $fn = $cache . $sha1 . ".js";

            if ( file_exists( $fn ) ) {
                return file_get_contents( $fn );
            }
        }

        $min = Minifier::minify($data, array('flaggedComments' => false));


        if ( $show_stats == true )
        {

            $l1 = strlen($data);
            $l2 = strlen($min);
            $l3 = $l1-$l2;

            $p = round(100-($l2/$l1*100),2);

            $min = "/* $l1:$l2:$l3 ($p%) *"."/\r\n". $min;
        }

        if ( $use_cache == true )
        {
            file_put_contents( $fn, $min );
        }

        return $min;
    }

    /***********************************************************************
     *                         Misc fixups
     *
     */

    // Added in v0.1
    date_default_timezone_set('Europe/Berlin');

}
?>