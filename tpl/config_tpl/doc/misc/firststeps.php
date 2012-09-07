<?php
    $this->data["HEADER"] = "First steps";
?>
<h2>First steps in a tiny world</h2>
<p>
    tinyTpl is a robust templating engine written in php5. It aims to be yet simple but
    also extensible without exposing major design flaws - hopefully.
</p>
<p>
    Besides reading the <a href="/tinyAdmin/doc/internals">documentation</a> which cover the main
    topics of tinyTpl, you may use the internal interface, you're watching at the moment,
    to control some settings and configuration issues for tinyTpl. To achive this, simply
    login by pressing the login link located at the top of each page.
</p>
<p>
    Using the buildin admin mode, allows you to control which hooks are loaded, to view
    essential usage statistics of your page and to check the health of the tinyTpl
    installation.
</p>
<p>
    The admin mode may only be accessed when tinyTpl is configured in 'dev'-mode and
    <a href="/tinyAdmin">//<?=$_SERVER['HTTP_HOST']?>/tinyAdmin</a> URL is used.<br/>
</p>
<p style="font-size:1.4em;font-weight:bold;">
    <span style="color:#faa">
        <span style="color:#f00">Caution:</span> You need to have Javascript enabled in order to work with admin mode.
    </span>
</p>
<p style="text-align:center;">
    - OR -
</p>
<p>
    The admin mode might be accessed when tinyTpl is configured in 'dev'-mode and no master_tpl or
    no default action is defined.
</p>
<p>
    However when you're accessing the admin mode, keep in mind, that it might break your existing
    <code>$_SESSION</code> setup thus destroying the functionality of your web-app.
</p>
