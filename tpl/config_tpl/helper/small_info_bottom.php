        <div class="small-info bottom">
            <div class="left"><a style="color:#123;font-weight:bold;" href="https://github.com/derRaphael/tinytpl">tinyTpl <?=self::VERSION;?></a></div>
            <div class="right">Html5Boilerplate 4</div>
            <div class="right">jQuery <span class="jq-version"></span></div>
            <div class="right">jQuery UI <span class="jq-ui-version"></span></div>
<?php if( has_trigger_set( 'raphael_js_libs' ) ): ?>
            <div class="right">raphaelJs <span class="raphael-version"></span></div>
            <div class="right">g.raphaelJs <span class="g-raphael-version"></span></div>
<?php endif; ?>
        </div>
