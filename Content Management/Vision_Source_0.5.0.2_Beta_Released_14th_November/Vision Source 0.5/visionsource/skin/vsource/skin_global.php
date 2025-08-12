<?php
/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 3rd March 2005								//
//															//
//----------------------------------------------------------//
//															//
//		Script: skin.php									//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class skin_global {

function mainarea() {
global $info, $cms;

echo <<<EOT

<table border="0" cellpadding="0" cellspacing="0" id="table">
  <tr>
    <td colspan="2" id="logo"><img src="{$info['base_url']}/skin/vsource/images/visionsource-logo.jpg" alt="{$info['title']}" width="300" height="90" /></td>
	<td rowspan="3" valign="top" class="nav"><p>
	
EOT;

$meh = '<p>
      <div class="title">Welcome, Ben </div>
      <div class="text"> Here you can change your details.<br>
        <br>
        <span class="style2">.:</span> Change Password<br>
        <span class="style2">.:</span> Change Details<br>
        <span class="style2">.:</span> Change Theme
      </div>
	  <div class="text_bottom">&nbsp;</div>
    </p>
		<p>
      <div class="title">Poll</div>
      <div class="text"> Do you like this website?<br>
        <br>
        <input name="radiobutton" type="radio" value="radiobutton">
        Yes, Its brillant!!<br>
        <input name="radiobutton" type="radio" value="radiobutton">
        Its alright <br>
        <input name="radiobutton" type="radio" value="radiobutton">
      No, I hate it<br>
      <br>
      <input type="submit" name="Submit" value="Submit">
      </div>
	  <div class="text_bottom">&nbsp;</div>
    </p>	
		<p>
      <div class="title">Did you know? </div>
      <div class="text"> Did you know that you can also report a problem with your computer you are sitting on now? <br>
        <br>
        <span class="style2">.:</span> Find out more by clicking here</div>
	  <div class="text_bottom">&nbsp;</div>
    </p>';


$slow =	  '<p>
      <a href="http://validator.w3.org/check?uri=referer"><img
          src="http://www.w3.org/Icons/valid-xhtml10"
          alt="Valid XHTML 1.0!" height="31" width="88" /></a>
	<br />
 	<a href="http://jigsaw.w3.org/css-validator/check/referer">
  	<img style="border:0;width:88px;height:31px"
       src="http://jigsaw.w3.org/css-validator/images/vcss"
       alt="Valid CSS!" />
 	</a>
	</p>';
	}

function nav() {
global $info;

echo <<<EOT
</p>
    </td>
  </tr>
  <tr>
    <td align="left" valign="top" class="topnav" colspan="2"><span class="topdot">.:</span> <a href="{$info['base_url']}/index.php">Home</a> <span class="topdot">.:</span> <a href="{$info['base_url']}/index.php?id=about">About</a> <span class="topdot">.:</span> <a href="{$info['base_url']}/index.php?id=links">Links</a> <span class="topdot">.:</span> <a href="{$info['base_url']}/index.php?id=custompage">Custom Pages</a> <span class="topdot">.:</span> <a href="{$info['base_url']}/index.php?id=contact">Contact</a> <span class="topdot">.:</span> <a href="{$info['base_url']}/index.php?id=News">News</a> <span class="topdot">.:</span> <a href="{$info['base_url']}/index.php?id=ucp">User CP</a></td>
  </tr>
  <tr>
    <td colspan="2" valign="top" class="main">
	
EOT;
}

	
function tophead($mod_title, $css) {
global $info, $skin;
echo <<<EOT
<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>{$info['title']}
EOT;
if ($mod_title == "")
{
echo <<<EOT
</title>

EOT;
}
else
{
echo <<<EOT
$mod_title</title>
 
EOT;
}

echo <<<EOT
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style media="all" type="text/css">
@import url($css);
</style>
<link rel="alternate" type="application/rss+xml" title="{$info['title']} News" href="{$info['base_url']}/rss.php" />
</head>
<body>
EOT;
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


function  footer($total_queries, $VSOURCE_VER) {
global $timer;

$time = $timer->stop();

echo <<<EOT
</td>
  </tr>
</table>
<div class="copyright">Site powered by <a href="http://www.visionsource.org">Vision Source</a> v$VSOURCE_VER &copy; 2005. All Rights Reserved. <br />
[ Page Executed In: {$time} | Queries Used: $total_queries ]
</div>
</body>
</html>

EOT;
 }

}
?>
