<?php
    $this->data["HEADER"] = "Internals";
?>
<h2>Configuration of tinyTpl</h2>
<p>
    Configuration is pretty simple. You won't need to alter any of the tiny core files, because
    tiny's behaviour can be controlled through a set of variables, which are defined in a seperate
    class. Below is a sample configuration with a brief explanation of the used default values.
</p>
<pre style="background: #4080ff; border: 1px solid #008;">

    <span style="font-weight:bold;color:#eee;">&lt;?php</span>
        <span style="font-weight:bold;color:#eee;">namespace tinyTpl</span>
        <span style="font-weight:bold;color:#eee;">{</span>
            <span style="font-weight:bold;color:#eee;">class config</span>
            <span style="font-weight:bold;color:#eee;">{</span>
                <span style="font-weight:bold;color:#eee;">public static</span>

                    /**
                     * This is the default extension of the templates
                     * in the template folder.
                    **/
                    <span style="font-weight:bold;color:#eee;">$tplExt = '.php',</span>

                    /**
                     * This is the default file not found error template.
                    **/
                    <span style="font-weight:bold;color:#eee;">$tpl404 = '404',</span>

                    /**
                     * This is the default internal error template.
                    **/
                    <span style="font-weight:bold;color:#eee;">$tpl500 = '500',</span>

                    /**
                     * This is the default custom error template.
                    **/
                    <span style="font-weight:bold;color:#eee;">$tplXXX = 'xxx',</span>

                    /**
                     * This is the template folder holding all templates.
                    **/
                    <span style="font-weight:bold;color:#eee;">$default_tpl_dir = '/tpl/',</span>

                    /**
                     * This is a subfolder in template folder containing
                     * master_template files.
                    **/
                    <span style="font-weight:bold;color:#eee;">$default_master_tpl_dir = 'master_tpl/',</span>

                    /**
                     * This is a subfolder in template folder containing
                     * error_template files.
                    **/
                    <span style="font-weight:bold;color:#eee;">$default_error_tpl_dir = 'error_tpl/',</span>

                    /**
                     * This is the default template which will be loaded
                     * when no url is given.
                    **/
                    <span style="font-weight:bold;color:#eee;">$default_template = 'default',</span>

                    /**
                     * The following variable contains the needle for
                     * master templates which will be replaced by the
                     * proper template content.
                    **/
                    <span style="font-weight:bold;color:#eee;">$TINY_TPL_CONTENT_PLACEHOLDER = '{TINY_TPL_CONTENT}',</span>

                    /**
                     * When use_sessions is set to true, tinyTpl
                     * will used sessions. This setting does not affect
                     * which settings engine is to be used.
                    **/
                    <span style="font-weight:bold;color:#eee;">$use_sessions = true,</span>

                    /**
                     * When use_session_engine is set to true, a
                     * custom session engine named in SessionHandler
                     * will be used. Keep in mind, that
                     * you custom engine will need to have a set of
                     * functions defined.
                    **/
                    <span style="font-weight:bold;color:#eee;">$use_session_engine = false,</span>

                    /**
                     * dev_state affects the way tinyTpl treats error
                     * handling. It may either contain 'dev' (default)
                     * or 'stable'. While set to 'dev' tiny will show
                     * detailed error message and in 'stable'-mode only
                     * some generic information that an error happened.
                    **/
                    <span style="font-weight:bold;color:#eee;">$dev_state = 'dev',</span>

                    /**
                     * When SESSION_NAME equals null, tinyTpl
                     * attempts to set it to the server name and have any
                     * chars other that a-z replaced by dashes.
                    **/
                    <span style="font-weight:bold;color:#eee;">$SESSION_NAME = null,</span>

                    /**
                     * SessionHandler contains a fully qualified
                     * name of your own custom session handler which
                     * will be invoked if use_session_engine is set
                     * to true.
                    **/
                    <span style="font-weight:bold;color:#eee;">$SessionHandler = 'tinySession',</span>
                <span style="font-weight:bold;color:#eee;">);</span>
            <span style="font-weight:bold;color:#eee;">}</span>
        <span style="font-weight:bold;color:#eee;">}</span>
    <span style="font-weight:bold;color:#eee;">?&gt;</span>

</pre>
