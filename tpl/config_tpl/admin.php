<?php

    if ( count( $this->args ) >= 2 )
    {
        // This function is called whenever the login button is clicked
        if ( $this->args[1] == "login" && $this->method == "POST")
        {
            $fn = $this->base . "/cache/01.data";

            if (
                array_key_exists('value',$_POST) && $_POST['value'] == "enter"
            ) {
                ob_start();
?>

    <div class="login-dlg" title="Enter your password">

<?php if ( ! ( file_exists( $fn ) && is_readable( $fn ) ) ): ?>

        <small>If you've never seen this dialog before, this password will be your new default password for tinyTpl's AdminMode</small><br>

<?php endif; ?>

        <input type="password" name="pw" placeholder="Your password" style=""/>
    </div>

<?php
                $dlg_html = ob_get_contents();
                ob_end_clean();

                $dlg_html = preg_replace('_^\s+|\s+$_ms', '', $dlg_html );

                ob_start();
?>

if ( $('.login-dlg').length != 0 ) {
    $('.login-dlg').dialog('close').remove();
};

$('body').append(unescape('<?=rawurlencode($dlg_html)?>'));

$('.login-dlg').dialog({
    buttons: {
        Submit: function(){
            if ( $.trim( $('.login-dlg input[name=pw]').val() ) != "" )
            {
                $.ajax({
                    url: "/tinyAdmin/admin/login",
                    type: "POST",
                    dataType: "script",
                    data: {
                        value: "process",
                        pw: $.trim( $('.login-dlg input[name=pw]').val() )
                    }
                });
            }
            $(this).dialog("close");
        }
    },
    resizable: false,
    width: 400
});

$('.login-dlg input').bind('keyup',function(event){
    if ( event.keyCode == $.ui.keyCode.ENTER ) {
        $('.ui-dialog :button:contains("Submit")').click();
    }
});

<?php
                $dlg_js = ob_get_contents();
                ob_end_clean();

                $this->MASTER_TEMPLATE = null;

                echo mini_js($dlg_js);
            } else if (
                   array_key_exists('value',$_POST) && $_POST['value'] == "process"
                && array_key_exists('pw',$_POST) && trim($_POST['pw']) != ""
            ) {

                $_SESSION['tinyadmin_is_logged_in'] = false;

                if ( $this->caching_available == true && ! ( file_exists( $fn ) || is_readable( $fn ) ) )
                {

                    file_put_contents($fn, sha1( $this->template_dir . $_POST['pw'] ));

                } else if ( $this->caching_available == false ) {

                    // Added in 0.2.6
                    // Avoid error, when cache is not writeable
                    ob_start();
?>

    <div class="login-dlg" title="Adminmode">

        <p style="color:#800;">
            Do you have a cache folder with proper permissions set?<br />
            TinyTpl was unable to store your supplied credentials. <br/>
        </p>
        <p style="font-weight:bold;color:#f00;">You are NOT logged in.</p><br>

    </div>

<?php
                    $dlg_html = ob_get_contents();
                    ob_end_clean();

                    $dlg_html = preg_replace('_^\s+|\s+$_ms', '', $dlg_html );

                    ob_start();
?>

if ( $('.login-dlg').length != 0 ) {
    $('.login-dlg').dialog('close').remove();
};

$('body').append(unescape('<?=rawurlencode($dlg_html)?>'));
$('.login-dlg').dialog({
    resizable: false,
    width: 400
});

<?php
                    $dlg_js = ob_get_contents();
                    ob_end_clean();

                    $this->MASTER_TEMPLATE = null;

                    echo mini_js($dlg_js);

                    die();

                }

                if ( file_exists( $fn ) && is_readable( $fn ) && sha1( $this->template_dir . $_POST['pw'] ) == file_get_contents($fn) ) {

                    $_SESSION['tinyadmin_is_logged_in'] = true;

                    // changed to default to tinyAdmin base folder instead of project's root
                    // Changed in 0.2.6
                    $dlg_js = 'document.location.href="/tinyAdmin";';

                } else {
                    ob_start();
?>

    <div class="login-dlg" title="Adminmode">

        <p style="color:#800;">The supplied credentials were wrong.</p>
        <p style="color:#f00;font-weight:bold;">You are NOT logged in.</p>

    </div>

<?php
                    $dlg_html = ob_get_contents();
                    ob_end_clean();

                    $dlg_html = preg_replace('_^\s+|\s+$_ms', '', $dlg_html );

                    ob_start();
?>

if ( $('.login-dlg').length != 0 ) {
    $('.login-dlg').dialog('close').remove();
};

$('body').append(unescape('<?=rawurlencode($dlg_html)?>'));
$('.login-dlg').dialog({
    resizable: false,
    width: 400
});

<?php
                    $dlg_js = ob_get_contents();
                    ob_end_clean();
                }

                $this->MASTER_TEMPLATE = null;

                echo mini_js($dlg_js);

                die();

            }

        } else if ( $this->args[1] == "logout" && $this->method == "POST") {

            $this->MASTER_TEMPLATE = null;

            $_SESSION['tinyadmin_is_logged_in'] = false;

            if ( isset( $_SESSION[ 'tinyadmin' ] ) )
            {
                unset( $_SESSION[ 'tinyadmin' ] );
            }
            echo 'document.location.href="/";';

        } else {

            // Since we arrived here, neither login nor logout has been processed.
            // do a sanitycheck and delegate if it makes sense

            if ( isset($_SESSION['tinyadmin_is_logged_in']) && $_SESSION['tinyadmin_is_logged_in'] === true )
            {
                if ( preg_match( '_^(cache|checks|hooks|pass|source)$_', $this->args[1] ) )
                {

                    echo tpl( implode( "/", $this->args ) );

                } else if ( $this->args[1] == "exception" ) {

                    echo tpl( "admin/exception" );

                } else {

                    header('Location: /' );
                }

            } else {

                header('Location: /'  );

            }
        }
    }
?>