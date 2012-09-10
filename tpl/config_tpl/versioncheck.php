<?php if ( $this->isAjax && $this->method=="POST" ) :?>

<?php
    if ( array_key_exists( 'value', $_POST ) )
    {
        if ( $_POST['value'] == "version" )
        {
            header('Content-type: application/json');
            die( file_get_contents('https://raw.github.com/derRaphael/tinytpl/master/doc/version.json' ) );

        } else if ( $_POST['value'] == "history"
                 && array_key_exists( 'maj', $_POST )
                 && array_key_exists( 'min', $_POST )
                 && array_key_exists( 'sub', $_POST )
        ) {

            $data = json_decode( file_get_contents('https://raw.github.com/derRaphael/tinytpl/master/doc/changelog.json' ), true );


            if ( isset( $data['current'] ) )
            {
                unset( $data['current'] );
            }

            $maj = $_POST['maj'];
            $min = $_POST['min'];
            $sub = $_POST['sub'];

            $res = array();

            foreach( $data as $key => $value )
            {
                if ( preg_match( '_([^\-]+)\-([^\-]+)\-([^\-]+)_', $key, $m ) )
                {
                    if ( (int) $maj < (int) $m[1] )
                    {
                        $res[$key] = $value;
                    }
                    else if ( (int) $maj <= (int) $m[1] && (int) $min < (int) $m[2] )
                    {
                        $res[$key] = $value;
                    }
                    else if ( (int) $maj <= (int) $m[1] && (int) $min <= (int) $m[2] && (int) $sub < (int) $m[3] )
                    {
                        $res[$key] = $value;
                    }
                }
            }

            ksort($res);
            $res = array_reverse($res);

            header('Content-type: application/json');
            die(json_encode($res));

        }
    }
?>

<?php else : ?>

<?php
    $this->data["HEADER"] = "Versioncheck";
?>

<h2 class="version-head">Check for latest Version of tinyTpl</h2>
<p class="version-text">Please wait a moment, while connecting to tinyTpl's project server.</p>
<div class="version-check-results"></div>
<?=trigger_tpl('js_versioncheck', 'helper/js/versioncheck_action')?>

<?php endif; ?>