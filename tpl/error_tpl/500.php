<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="Internal Server Error">
	<meta name="author" content="tinyTpl">
	<meta name="viewport" content="width=device-width">
	<title>Internal Server Error</title>
    <style type="text/css">
        html,body { margin:0;padding:0;height:100%; font-size: 14px; }
        body { background: #efefef; font-size: 1.1em; font-family: serif; color: #888; }
        div.container { min-height:100%;position:relative; }
        div.bg { position:absolute; top: 0; left: 0; bottom: 0; right: 0; width: auto; height: auto;
            overflow: hidden; }
        div.bg div { position: absolute; bottom: -30%; right: 0; font-size: 45em; letter-spacing: -.09em;
            font-weight:bold; color: #222; color: rgba(0,0,0,.25); 
            text-shadow: 0 2px 0 #aaa, 0 -2px 0 #aaa, 2px 0 0 #aaa, -2px 0 0 #aaa; color:#efefef;        }
        div.box { position: absolute; left: 1em; right: 1em; top: 1em; bottom: 1em; width: auto; height: auto;
            background: rgba(255,255,255,.75); padding: 3em; margin: 3em; font-family: sans; border: 1px solid #aaa;
            -webkit-border-radius: 2em; -moz-border-radius: 2em; border-radius: 2em; }
        div.box h1 { color: #888; text-shadow: 0 0 .25em #bbb;  }
        div.box p { font-size: 1.2em; text-shadow: 0 0 1em #aaa; }
        div.box hr { background: #444; color: #444; border: 0; height: 2px;  }
        div.box a { color: #f80;  }
        div.footer { position: absolute; bottom: .5em; height: 1.5em; text-align: center; width: auto; left: 0; right: 0; font-size: 0.8em; }
        div.footer a:link, div.footer a:active, div.footer a:visited { text-decoration: none; color: #888; }
        div.footer a:hover { text-decoration: udnerline; color: #123; }
    </style>
</head>
<body>
<div class="container" role="main">
    <div class="bg"><div>500</div></div>
    <div class="box">
        <h1>There is an error here, sorry.</h1>
        <hr />
        <p>You're seeing this page, because our server is currently expiriencing some difficulties.</p>
        <p>We apologise for any inconvenience.</p>
<!--[if lt IE 8]>
        <hr />
        <p class=chromeframe>
            Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade
            to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">
            install Google Chrome Frame</a> to experience this site.
        </p>
<![endif]-->
        <div class="footer">
            <a href="https://github.com/derRaphael/tinytpl">powered by tinyTpl. &copy; derRaphael</a>
        </div>
    </div>
</div>
</body>
</html>