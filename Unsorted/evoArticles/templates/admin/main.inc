<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html; charset=windows-1252" http-equiv="Content-Type" />
<meta http-equiv="MSThemeCompatible" content="Yes" />
<title>$settings[sitename] - $site[title]</title>
<link rel="stylesheet" href="$settings[admin_css]" />
$layout[head]
</head>
<body $layout[bodyadditional]>
<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="height:100%">
<tr>
<td colspan="2" style="background-image: url($settings[imgfolder]admin_bg.gif)"><img src="$settings[imgfolder]admin_logo.gif" alt="" /></td>
</tr>
<tr style="height:100%">
<td width="175" id="leftnav" valign="top" align="center">
$nav
</td>
<td id="content" valign="top" width="90%">
<div id="topbar">
  <div style="float:left">
    <b>$settings[sitename]</b>
  </div>
<b>$userinfo[username]</b> (<a href="index.php?do=clear">Log out</a>)
</div>
<table border="0" cellpadding="5" cellspacing="0" align="center" width="100%">
<tr><td>
$content
</td></tr>
</table>
<br style="clear:both" />
</td>
</tr>
<tr>
<td colspan="2" id="footer">
{timer}
</td>
</tr>
</table>

</body>
</html>