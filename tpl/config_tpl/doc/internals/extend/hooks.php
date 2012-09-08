<?php
    $this->data["HEADER"] = "Internals";
?>
<h2>Write your own hooks</h2>
<p>
    Writing your own hooks is straight forward. Simple register it in the <code>\tinyTpl\hooks</code> namespace,
    implement the <code>tinyObserver</code>-Interface and put it into the <a href="/tinyAdmin/doc/internals/folderstructure">
    /lib/hooks</a> folder.
</p>
<p>
    A hook (or observer) must know about which function it hooks, and which state the function is. The
    latter is called stage, the former is called target.
    Currently (tinyTpl v0.2.2) no internal priorisation is possible for hooks. This means that hooks
    are registered in the order found on the harddrive and executed this way, too.
</p>
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
