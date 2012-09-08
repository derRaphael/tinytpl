<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="Internal Server Error">
	<meta name="author" content="tinyTpl">
	<meta name="viewport" content="width=device-width">
	<title>An Exception was thrown</title>
    <style type="text/css">
        html,body { margin:0;padding:0;height:100%; font-size: 14px; }
        body { background: #000; font-size: 1.1em; font-family: serif; color: #eee; }
        div.container { min-height:100%;position:relative; }
        div.bg { position:absolute; top: 0; left: 0; bottom: 0; right: 0; width: auto; height: auto;
            overflow: hidden; }
        div.bg div { position: absolute; bottom: -30%; right: 0; font-size: 45em; letter-spacing: -.09em;
            font-weight:bold; color: #222; color: rgba(0,0,0,.25); text-shadow: 0 0 1em #444; }
        div.box { position: absolute; left: 1em; right: 1em; top: 1em; bottom: 1em; width: auto; height: auto;
            background: rgba(64,16,16,.5); padding: 3em; margin: 3em; font-family: sans;
            -webkit-border-radius: 2em; -moz-border-radius: 2em; border-radius: 2em;}
        div.box h1 { color: #888; text-shadow: 0 0 .25em #000;  }
        div.box p { font-size: 1.2em; text-shadow: 0 0 1em #aaa; }
        div.box hr { background: #444; color: #444; border: 0; height: 2px;  }
        div.box a { color: #f80;  }
        /* Added Clearfix */
        .clearfix:after { content: "."; display: block; clear: both; visibility: hidden; line-height: 0; height: 0; }
        .clearfix { display: inline-block; }
        html[xmlns] .clearfix { display: block; }
        * html .clearfix { height: 1%; }
    </style>
</head>
<body>
<div class="container" role="main">
    <div class="bg"><div>Duh!</div></div>
    <div class="box">
        <h1>There was an unexpected error, sorry.</h1>
        <hr />
        <div style="position:absolute;bottom:3.5em;left:3.5em;right:3.5em;top:8.2em;width:auto;height:auto;">
            <div style="position:absolute;top:0;left:0;bottom:0;right:0;overflow:auto;overflow-x:hidden;">
{TINY_TPL_CONTENT}
            </div>
        </div>
    </div>
</div>
</body>
</html>