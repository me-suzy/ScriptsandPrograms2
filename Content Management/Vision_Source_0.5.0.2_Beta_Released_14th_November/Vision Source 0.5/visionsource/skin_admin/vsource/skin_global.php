<?php

if ( ! defined( 'ACP' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class skin_global
{

function frame($title, $sesid)
{
global $info;
	
$CMSHTML = '';
	
$CMSHTML .= <<<EOT
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>{$title}</title>
</head>

<frameset cols="150,*" frameborder="NO" border="0" framespacing="0">
  <frame src="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&amp;id=menu" name="menu" scrolling="YES" noresize>
  <frame src="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&amp;id=main" name="main">
</frameset>
<noframes><body>
</body></noframes>
</html>
EOT;

return $CMSHTML;

}

function login()
{
global $info;

$CMSHTML = '';

$CMSHTML .= <<<EOT
<form name="login" id="login" method="post" action="{$info['base_url']}/admin.php?do=2">
username: <input type="textfield" name="username" /> <br />
password: <input type="password" name="password" /> <br />
<input type="submit" value="Submit" />
</form>
EOT;

return $CMSHTML;
}

function custerror($errormsg) {

$CMSHTML = <<<EOT
Im sorry, there was an error, the error was: $errormsg <br />
<a href="javascript:history.go(-1)">Go back</a>.
EOT;

return $CMSHTML;
}

function redirect($txt, $url, $css)
{

$CMSHTML = <<<EOT
<html>
<head>
<title>Please wait while we redirect you</title>
<meta http-equiv="refresh" content="3; url=$url" />
<style media="all" type="text/css">
@import url($css);
</style>
</head>
<body>
<div class='redirect'>Please wait while we redirect you...
<p>$txt</p>
Please click <a href="$url">here</a> if you do not wish to wait.
</div>
</body>
</html>
EOT;

return $CMSHTML;
}


}
?>