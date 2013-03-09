<?php
    $this->data["HEADER"] = "Internals";
?>
<h2>Write your own hooks</h2>
<p>
    Writing your own hooks is straight forward. Simply register it in the <code>\tinyTpl\hooks</code> namespace,
    and extend the <code>tinyObserver</code>-Class which implements the <code>tinyObserver</code>-Interface and 
    put it into the <a href="/tinyAdmin/doc/internals/folderstructure">/lib/hooks</a> folder.
</p>
<p>
    A hook (or observer) must know about which function it hooks, and which state the function is. The
    latter is called stage, the former is called target.
    Currently (tinyTpl v0.2.2) no internal priorisation is possible for hooks. This means that hooks
    are registered in the order found on the harddrive and executed this way, too.
</p>
<p>
    If your observer is sensitive on system configurations, such as a running database, you may use a constant
    <code>AUTOINIT</code> in your class definition, in order to reflect that when the cache folder is not
    initialized, which is the case when tinytpl is being called the very 1st time. Set the value to <code>false</code>.
</p>
<p>
    This way, you'll avoid an exception, which might lead to an complete unresponsive webserver.
<p>
<h3>Which functions may be hooked?</h3>
<p>Below is a list of hookable functions with their stages.</p>
<ul style="list-style-type: none;">
    <li>
        <h4><code>tinyTpl\tiny::__alt_construct</code></h4>
        Stage <strong>0</strong> is on the functions entry<br>
        Stage <strong>255</strong> is on the functions exit
    </li>
    <li>
        <h4><code>tinyTpl\tiny::__init</code></h4>
        Stage <strong>0</strong> is on the functions entry<br>
        Stage <strong>254</strong> is after defining the args list<br>
        Stage <strong>255</strong> is on the functions exit
    </li>
    <li>
        <h4><code>tinyTpl\tiny::html</code></h4>
        Stage <strong>0</strong> is on the functions entry<br>
        Stage <strong>255</strong> is on the functions exit before the return of the rendered string
    </li>
    <li>
        <h4><code>tinyTpl\tiny::render_template</code></h4>
        Stage <strong>0</strong> is on the functions entry<br>
        Stage <strong>50</strong> is triggered only when template has been readable after it has been read<br>
        Stage <strong>255</strong> is on the functions exit before the return of the tiny object
    </li>
    <li>
        <h4><code>tinyTpl\tiny::read_template</code></h4>
        Stage <strong>0</strong> is on the functions entry<br>
        Stage <strong>255</strong> is on the functions exit
    </li>
    <li>
        <h4><code>tinyTpl\tiny::use_template</code></h4>
        Stage <strong>0</strong> is on the functions entry<br>
        Stage <strong>254</strong> is before actions are rebuild<br>
        Stage <strong>255</strong> is on the functions exit before return  of the tiny object
    </li>
</ul>
<h3>Sample</h3>
<p>
    Have a look either at <code>\tinyTpl\hooks\tinyBenchmark</code> class or
    <code>\tinyTpl\hooks\tinyHeaderInfo</code> for how to hook one or more functions.
</p>
<p>
    For an observer which won't be initialized on firstrun, study the <code>\tinyTpl\hooks\tinyMongoInit</code> 
    class. 
</p>

