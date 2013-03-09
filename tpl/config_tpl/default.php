<?php
    $this->data["HEADER"] = "Welcome";

    $this->data['adminMode_trigger'] = (isset($_SESSION['tinyadmin_is_logged_in']) && $_SESSION['tinyadmin_is_logged_in'] === true);

?>

<?=tpls_on_state('pages/default_logged_in','pages/default_not_logged_in',$this->data['adminMode_trigger'] )?>

<div style="margin:0;">
    <div style="float:left;margin:1em;">
        <h2>Misc</h2>
        <ul>
            <li><a href="/tinyAdmin/doc/misc/firststeps">First steps in a tiny world</a></li>
            <li><a href="/tinyAdmin/doc/misc/howitworks">How tiny works</a></li>
            <li><a href="/tinyAdmin/doc/misc/credits">Credits</a></li>
            <li><a href="/tinyAdmin/versioncheck">Check for new version</a></li>
        </ul>
    </div>

    <div style="float:left;margin:1em;">
        <h2>Documentation</h2>
        <ul>
            <li><a href="/tinyAdmin/doc/internals">Internal workings</a></li>
            <li><a href="/tinyAdmin/doc/internals/configuration">Configuration</a></li>
            <li><a href="/tinyAdmin/doc/internals/extend/tiny">Extend tinyTpl</a></li>
        </ul>
    </div>

    <div style="float:left;margin:1em;">
        <h2>Teststuff</h2>
        <ul>
            <li><a href="/tinyAdmin/error_demo">tiny's error handling</a></li>
        </ul>
    </div>
</div>
<div class="clearfix"></div>
