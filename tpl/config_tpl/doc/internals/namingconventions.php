<?php
    $this->data["HEADER"] = "Internals";
?>
<h2>Naming conventions of tinyTpl</h2>
<h3>Namespaces</h3>
<p>
    tinyTpl use its own namespace <code>lib\tinytpl</code>. If you haven't worked with
    namespaces yet, have a look at <a href="http://php.net/manual/language.namespaces.php">
    namespaces</a> in the php documentation.
</p>
<p>
    Configuration resides in the <code>\lib\tinytpl</code> namespace as a class named
    <code>config</code> and will be only read from static defined variables
    in this class.
</p>
<p>
    Observer/Hooks are defined in <code>\lib\tinytpl\hooks</code>. The interface for <code>observer
    </code> and it's abtract class is also defined in there.
</p>
<h3>Classes</h3>
<p>
    Tiny's hookloader will only load classes ending with <code>.php</code> from the 
    <code>/lib/tinytpl/hooks</code> folder.
</p>
<h3>Compatibility</h3>
<p>Tiny has an option to keep using the all greedy autoloader from versions prior v0.2.7</p>
<p>
    This autoloading mechanism will scann the entire lib folder, and add it to global searchpath
    - this is done in favour to enable lazy autoloading for classes put into that folder.
</p>
<p>
    However, as this behaviour was considered undesired in order to favour proper namespaces
    and the resulting ease for a simpler autoloader, the lazy autoloader was disabled by default
    and needs to be explicitly enabled in order to work.
</p>