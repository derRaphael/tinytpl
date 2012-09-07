<?php
    $this->data["HEADER"] = "Internals";
?>
<h2>tinyTpl's folderstructure</h2>
<p>
    The used folderstructure is quite straight. Here is a brief overview of all folders
    in the default configuration.
</p>
<h3>/web</h3>
<p>
    The <code>/web</code> folder contains public accessible documents. Most of important, here
    is the index.php located which loads anything else into tiny
</p>
<h3>/tpl</h3>
<p>
    The <code>/tpl</code> folder contains tiny's templating collection anything related to
    templates goes in here.
</p>
<h3>/tpl/master_tpl</h3>
<p>
    In <code>/tpl/master_tpl</code> master templates, the fiels which are loaded at the very
    final of the rendering process should be located. The master template can be set at runtime,
    so there is no limit on how many templates you want to use.
</p>
<h3>/tpl/config_tpl</h3>
<p>
    <code>/tpl/config_tpl</code> contains all the templates you're currently watching.
</p>
<h3>/tpl/error_tpl</h3>
<p>
    <code>/tpl/error_tpl</code> contains the buildin error templates for showing custom error pages
</p>
<h3>/lib</h3>
<p>
    <code>/lib</code> holds beside a collection of subfolder the main tiny class. Tiny's autoloader
    will try including anything with <code>.class.php</code>-namepart found in here. All your
    custom libraries belong either directly in here or in a subfolder locatedc in here.
</p>
<h3>/lib/hooks</h3>
<p>
    As name suggest, <code>/lib/hooks</code> contains the basic hooks for use with tinyTpl.
</p>
<h3>/lib/misc</h3>
<p>
    <code>/lib/misc</code> contains the javascript minifier library. This is a good place for
    3rd party classes.
</p>
<h3>/cache</h3>
<p>
    In <code>/cache</code> minified css and javscript versions are stored.
    If the caching module is in use, the cached documents will be stored in here, too.
</p>
<h3>/cache/hook</h3>
<p>
    <code>/cache/hooks</code> takes precedence over the <code>/lib/hooks</code> folder. This enables
    safely to enable/disable hooks at runtime, without exposing any core folders.
</p>


