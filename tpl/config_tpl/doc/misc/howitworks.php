<?php
    $this->data["HEADER"] = "How it works";
?>
<h2>About</h2>
<p>
    This page aims to explain how tiny works internally and how show some best use
    practices to unleash its full potential.
</p>
<h2>Structure</h2>
<p>
    TinyTpl has been written as a singleton object. This means it's only instatinated once.
</p>
<p>
    TinyTpl has a mastertemplate defined, which contains a string - typically that's
    <code>{TINY_TPL_CONTENT}</code>. This code is replaced at runtime with the template
    read from the template folder.
</p>
<h2>Sample</h2>
<p>
    So when the webserver gets a request to <code>/someUrl/param/blah</code>, tinyTpl splits
    up the request string using the slashes. The result is stored in a globally available array
    called args. It may be accessed by using <code>$this->args[index]</code> or
    <code>\tinyTpl\tiny::sys()->args[index]</code>.
</p>
<h2>Internal parsing</h2>
<p>
    Basically what happens next is that tinyTpl tries to load <code>someUrl</code>.php (args[0]) from the template
    directory. If it finds any nested templates using the tpl shorthand command, it will load these
    aswell. Each loaded templated is directly interpreted by using output buffering.
</p>
<p>
    The buffered output will be handed back once it doesn't find any more commands to execute.
    The buffer will be replaced with the magic string in the master template and the result will
    be written back to the stream.
</p>
<p>
    Usually the outmost template (the actual page content) has its name escaped, while
    inner templates have less strict escaping rules set. This allows inner templates being stored in
    subfolders, while all outmost templates are stored in the root template folder.
</p>
<h2>Error handling</h2>
<p>
    While nested templates, which cannot be satisfied, won't produce a 404 the 1st accessed one will.
</p>
<p>
    In each template you have the possibility to access the singleton, this allows to pass either variables
    or to generate page dependent different markups.
</p>
<h2>Further samples</h2>
<p>
    Take a look at the source of these pages in the <code>config_tpl/</code> directory as most
    commands and their usage is demonstrated here.
</p>