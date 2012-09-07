<?php
    $this->data["HEADER"] = "Internals";
?>
<h2>Naming conventions of tinyTpl</h2>
<h3>Namespaces</h3>
<p>
    tinyTpl use its own namespace <code>tinyTpl</code>. If you haven't worked with
    namespaces yet, have a look at <a href="http://php.net/manual/language.namespaces.php">
    namespaces</a> in the php documentation.
</p>
<p>
    Configuration resides in the <code>\tinyTpl</code> namespace as a class named
    <code>config</code> and will be only read from static defined variables
    in this class.
</p>
<p>
    Observer/Hooks are defined in <code>\tinyTpl\hooks</code>. The interface <code>tinyHook
    </code> is also defined in there.
</p>
<h3>Classes</h3>
<p>TinyTpl's autoloader will only load classes ending with <code>.class.php</code>.</p>
<p>TinyTpl's hookloader will only load classes ending with <code>.php</code> from the <code>/lib/hooks</code> folder.</p>