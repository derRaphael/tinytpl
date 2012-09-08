<?php
    /**
     * The check below is a demonstrated 'best use'-practise for tiny. It checks the count of passed arguments
     * and verifies by using regular expressions, that the arguments are known and spelled the same way
     * as intended.
    **/
    if ( count( $this->args )==2 && preg_match( '_^(code|syntax|exception|notfound)\_error$_', $this->args[1] ) ):
?>
<?=tpl( 'pages/' . implode( "/", $this->args ) );?>
<?php

    // Since the real 404 needs to point to a real nonexistant page, it is handled seperately
    elseif ( count( $this->args )==2 && $this->args[1] == "real_notfound_error" ):

?>
<?php

    // By generating a unique name each time we ensure that this page wont exists, ever
    header('Location: /guaranteed_not_found_page_' . sha1( microtime(true) ) );

?>
<?php

    // The set below shows the standard links to the cheackable error cases.
    else:
?>
<?php
    $this->data["HEADER"] = "Error testing";
?>
<h2>Error testing</h2>
<p>
    This pages features a linklist to provoke special php errortypes to demonstrate tiny's
    errorhandling.
</p>
<p>
    As long as the app is in the "dev" state, tinyTpl is happy to help you with as many
    detailed message as possible. Being put into "stable" mode, tiny avoids exposing any
    messages at all. Having php configured to log message in the server's "error"-file
    is a good idea.
</p>
<ul>
    <li>
        <h3><a href="/tinyAdmin/error_demo/exception_error">Throw an Exception</a></h3>
        <p>
            Exceptions can be thrown in either your own routines, or by 3rd party libraries
            or even by php. However, catching these is generally a good idea.
            This page shows a custom trace, so you may find the lines throwing exceptions.
            Any given paths apart from tinyTpl's own root folder will be masked,
            so any internal server file structures won't be exposed.
        </p>
    </li>
    <li>
        <h3><a href="/tinyAdmin/error_demo/code_error">Uninitialised variable usage</a></h3>
        <p>
            Accessing variables, without having them defined somewhere is a bad idea, this
            page illustrates these kind of errors.
        </p>
    </li>
    <li>
        <h3><a href="/tinyAdmin/error_demo/syntax_error">Syntax error</a></h3>
        <p>
            Somethign as simple as a missing semicolon may break your page's functionality.
            This is a so called 'syntax error' is in php terms a shutdown, meaning the
            interpreter will stop further processing and die with an error message.
            Tiny catches this case and shows a somewhat more userfriendly page.
        </p>
    </li>
    <li>
        <h3><a href="/tinyAdmin/error_demo/notfound_error">Invoking nonexistant template</a></h3>
        <p>
            Non existant inline templates won't be rendered and wont show an error page. So by
            clicking above's link, you will see a blank page by intention - this is usefull when
            you made unintentionally a typo, where a template should have been accessed and
            it cannot be rendered. So instead of showing an error page, you will simply see nothing.
        </p>
    </li>
    <li>
        <h3><a href="/tinyAdmin/error_demo/real_notfound_error">Linking nonexistant page</a></h3>
        <p>
            You will be forwarded to a guaranteed non existant page to provoke a 404 Document
            not found Error
        </p>
    </li>
</ul>
<?php endif; ?>