<?php
    $this->data["HEADER"] = "AdminMode";

    $fn = $this->base . "/cache/01.data";

    if ( array_key_exists('action',$_POST) && $_POST["action"] == "purge" )
    {
        if ( file_exists( $fn ) && is_readable( $fn ) )
        {
            unlink( $fn );

            $_SESSION['tinyadmin_is_logged_in'] = false;

            if ( isset( $_SESSION[ 'tinyadmin' ] ) )
            {
                unset( $_SESSION[ 'tinyadmin' ] );
            }

        }

?>
<h2>Reset Password</h2>
<p>Your Password has been resetted. Relogin to set your new password.</p>
<?php

    } else {

?>
<h2>Reset Password</h2>

<?php if( ! is_dir( $this->base . "/cache/" ) ): ?>

<div class="tiny-extra-info error">
    <h3>Fatal error.</h3>
    <p>The '$base/cache' folder does not exists. This module needs this folder writeable.</p>
</div>

<?php else: ?>


<p><form action="/tinyAdmin/admin/pass" method="post"><input type="hidden" name="action" value="purge" /><button type="submit">Click here</button>, in order to reset your password.</form></p>

<?php endif; ?>

<?php

    }

?>