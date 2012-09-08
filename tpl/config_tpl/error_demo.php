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
<p>This pages features a linklist to provoke special php errortypes to demonstrate tiny's errorhandling.</p>
<ul>
    <li><a href="/tinyAdmin/error_demo/exception_error">Throw an Exception</a></li>
    <li><a href="/tinyAdmin/error_demo/code_error">Uninitialised variable usage</a></li>
    <li><a href="/tinyAdmin/error_demo/syntax_error">Syntax error (missing semicolon)</a></li>
    <li><a href="/tinyAdmin/error_demo/notfound_error">Invoking nonexistant template</a></li>
    <li><a href="/tinyAdmin/error_demo/real_notfound_error">Linking nonexistant page</a></li>
</ul>
<?php endif; ?>