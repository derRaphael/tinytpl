
<?php if ( file_exists( $this->template_dir . $this->default_master_tpl_dir . $this->MASTER_TEMPLATE . $this->tplExt ) ): ?>

<p>
    This page is tinyTpl behind the scenes. It's called Admin mode or tinyAdmin.<br/> 
    However, in order to continue You'll need to enter a password.
</p>

<?php else: ?>

<p>Congrats. Since you're watching this page, it means, that your tinyTpl was installed successfully.</p>
<p>
    This page is shown, because the configuration of tinyTpl hasn't been completet yet. <br />
    Usually this means you have no master template defined.
</p>

<p style="text-align:right;"><a href="/tinyAdmin/doc/misc/firststeps">Read more about how to continue.</a></p>

<?php endif; ?>
