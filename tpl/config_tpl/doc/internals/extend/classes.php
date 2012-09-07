<?php
    $this->data["HEADER"] = "Internals";
?>
<h2>Use your own custom classes</h2>
<p>
    TinyTpl is quite userfriendly to extend. Most of the classes which bring extended functionality just
    have to be copied into the <a href="/tinyAdmin/doc/internals/folderstructure">lib folder</a> of tinyTpl.
    But there are few things good to know.
</p>
<h3>Autoloader</h3>
<ul>
    <li>
        TinyTpl has its own autoloader. This means it doesn't know and care about other frameworks/functions.
        So if a later included framework has its own autoloader function it might destroy the buildin one.<br>
        TinyTpl extends the searchpath with all found subdirectories of its
        <a href="/tinyAdmin/doc/internals/folderstructure">lib folder</a>.<br>
        This should basically allow to simply disable any extra autoloader unless it's written to add itself
        stackwise. PHP simply stopps all autoloader, when the 1st one succeeded.<br>
        TinyTpl attempts to load only files ending with <code>.class.php</code>.
        Read <a href="/tinyAdmin/doc/internals/namingconventions"> naming conventions</a> to learn more about this.
    </li>
</ul>
<h3>Namespaces</h3>
<ul>
    <li>
        TinyTpl makes use of namespaces. By default the <a href="/tinyAdmin/doc/internals/helper">helper functions</a>
        are registered in a global namespace, whereas hooks and the main tinyTpl class itself use their own
        namespace.<br>
        If you ever have the need to address a tiny function you may do so by simply using its fully qualified
        name such as <code>\tinyTpl\tiny::sys()->SomeInternalCoolFunction()</code> or by stating an alias with
        <code>use \tinyTpl\tiny as tiny;</code> in your code, so that latter you simply may use the singleton
        as this <code>tiny::sys()->SomeInternalCoolFunction()</code>.
    </li>
</ul>
