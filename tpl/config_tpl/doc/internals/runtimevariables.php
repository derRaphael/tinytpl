<?php
    $this->data["HEADER"] = "Internals";
?>
<h2>Runtime variables</h2>
<p>tinyTpl knows several variables at runtime, which might come in handy.</p>
<p>
    All these variables are properties of the tiny singleton and can be accessed object wise.
    Either as written here from within a template or by using the full qualified name of the singleton
    from everywhere else.
</p>
<p>(array) <code>$this->args</code> contains a list of arguments derived from path info.</p>
<p>(bool) <code>$this->isAjax</code> determines if a request has been made through ajax calls or not.</p>
<p>(string) <code>$this->action</code> contains the name if the current executed base template (outmost).</p>
<p>(string) <code>$this->base</code> contains a full path to the tinyTpl base which includes all other folders.</p>
<p>(string) <code>$this->template_dir</code> contains a full path to the templates.</p>
<p>(string) <code>$this->method</code> contains either "<code>GET</code>" or "<code>POST</code>".</p>
<p>(string) <code>$this->MASTER_TEMPLATE</code> when set to <code>null</code> it will avoid rendering the master template,
otherwise it contains the given master template or overrides with a new one.</p>
<p>(array) <code>$this->template_trigger_collection</code> contains an assoc array with keys as triggers and values as templates.</p>
<p>In global namespace the variable <code>$tiny</code> is registered which is a shorthand for <code>\tinyTpl\tiny::sys()</code>.</p>