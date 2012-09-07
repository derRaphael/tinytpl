<?php
    $this->data["HEADER"] = "Internals";
?>
<h2>tinyTpl's list of helper functions</h2>
<p>There is a small set of globally available functions which make life using tinyTpl a lot easier.</p>
<ul>
    <li>
        <h4><code>function tpl( $TINY_TEMPLATE )</code></h4>
        <p>tpl() includes any template from the tiny's template directory</p>
    </li>
    <li>
        <h4><code>function tpl_on_state( $TINY_TEMPLATE, $CONDITION, $COMPARE_VALUE = true )</code></h4>
        <p>
            This function works same as <code>tpl()</code> with the difference, that it only triggers
            when <code>$CONDITION</code> equals <code>$COMPARE_VALUE</code>.
        </p>
    </li>
    <li>
        <h4><code>function tpls_on_state( $TINY_TEMPLATE_1, $TINY_TEMPLATE_2, $CONDITION, $COMPARE_VALUE = true )</code></h4>
        <p>
            This function works same as <code>tpl_on_state()</code> with the difference,
            when <code>$CONDITION</code> equals <code>$COMPARE_VALUE</code> it triggers <code>$TINY_TEMPLATE_1</code>,
            and <code>$TINY_TEMPLATE_2</code> otherwise.
        </p>
    </li>
    <li>
        <h4><code>function trigger_tpl( $TINY_TRIGGER, $TINY_TEMPLATE )</code></h4>
        <p>
            As name suggest, <code>trigger_tpl()</code> triggers a template. It is used in combination
            with the function below. This function registers a template connected to a trigger.
        </p>
    </li>
    <li>
        <h4><code>function chk_tpl_trigger( $TINY_TRIGGER, $DEFAULT = null )</code></h4>
        <p>
            This is the counterpart of the <code>trigger_tpl()</code>. It checks if a certain trigger
            has been defined and includes it's connected template. Otherwise one may also define a
            default template which will be included, if the trigger doesn't exist.
        </p>
    </li>
    <li>
        <h4><code>function trigger_tpl_on_state( $TINY_TRIGGER, $TINY_TEMPLATE, $CONDITION, $COMPARE_VALUE = true )</code></h4>
        <p>
            This function works same as <code>trigger_tpl()</code> with the difference, that it only triggers
            when <code>$CONDITION</code> equals <code>$COMPARE_VALUE</code>.
        </p>
    </li>
    <li>
        <h4><code>function has_trigger_set( $TINY_TRIGGER )</code></h4>
        <p>
            This function return a bool if the specified trigger has been defined.
        </p>
    </li>
    <li>
        <h4><code>function any_array_key_exists( $keys = array(), $array )</code></h4>
        <p>This generic purpose function checks for any of the defined array_keys in an array.</p>
    </li>
    <li>
        <h4><code>function mini_css( $data, $options = array() )</code></h4>
        <p>
            This function minifies either css or json, if the json flag is set. The result will be cached in the
            global cache directory.
        </p>
    </li>
    <li>
        <h4><code>function mini_js( $data, $options = array() )</code></h4>
        <p>
            This function minifies javascript and stores the minified version in a global cache.
        </p>
    </li>
</ul>