<?php
    $this->data["HEADER"] = "Versioncheck";
?>
<h2>Check for latest Version of tinyTpl</h2>
<p>Please wait a moment, while connecting to tinyTpl's project server.</p>
<div class="version-check-results"></div>
<?=trigger_tpl('js_versioncheck', 'helper/js/versioncheck_action')?>