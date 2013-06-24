<?php
    $this->data["HEADER"] = "Internals";
?>
<h2>Extending the tinyTpl engine</h2>
<p>
    TinyTpl knows a few ways to be extended without altering the code directly.
</p>
<h3>Custom SessionHandling</h3>
<p>
    TinyTpl uses php's default session handling, but of course you may use your own
    cool functions which may save the user sessions in a database.<br>
    You may use your own SessionManager and simply set a variable containing it's
    classname and a flag to use it. See the sample below for how to do that.
</p>
<pre style="background: #4080ff; border: 1px solid #008;font-weight:bold;color:#eee;">

    &lt;?php
        namespace lib\tinytpl
        {
            class config
            {
                public static
                    $SessionHandler = &quot;\MyOwnCoolSessionHandler&quot;,
                    $use_session_engine = true;
            }
        }
    ?&gt;

</pre>
<p>
    By using these two variables, one sets the name of the SessionHandler, the other enables
    using it. In order to have your own session handler working, you need to set these two and
    have functions defined as used by the
    <a href="http://php.net/manual/function.session-set-save-handler.php" rel="nofollow">
    session_set_save_handler</a> Function.
</p>
<h3>Hooking core functions</h3>
<p>
    TinyTpl may also be extendended by writing hooks and have them intercept several core functions.<br>
    Read the <a href="/tinyAdmin/doc/internals/extend/hooks">hooking page</a>, for how to do that.
</p>
<h3>Using your own classes</h3>
<p>
    TinyTpl allows and mostly automagic integrates 3rd party classes and have them all play nicely together.<br>
    The <a href="/tinyAdmin/doc/internals/extend/classes">classes page</a> gives you an insight of common caveats.
</p>
