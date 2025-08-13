<?

/*
 * $Id: menu.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

include("./config.php");
include("$include_path/functions.php");
include("$include_path/session.php");

if(!session_is_registered("admin"))
	header("Location: index.php");

include("$include_path/$table_file");
include("$include_path/common.php");

$styles = template("styles");
eval("\$styles = \"$styles\";");

if(session_is_registered("admin")){
$content = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
$styles
</head>
<body bgcolor="$page_bg_color">
<table cellpadding="5" cellspacing="0" border="0">
<tr>
<td class="regular">
<a href="$base_url/index.php" target="_blank">My pRated</a>
<br>
<br>
<a href="$base_url/admin/main.php?$sn=$sid" target="main">Main Page</a>
<br>
<a href="$base_url/admin/new_images.php?$sn=$sid" target="main">New Images</a>
<br>
<a href="$base_url/admin/settings.php?$sn=$sid" target="main">Site Settings</a>
<br>
<a href="$base_url/admin/templates.php?$sn=$sid" target="main">Templates</a>
<br>
<a href="$base_url/admin/users.php?$sn=$sid" target="main">Edit Users</a>
<br>
<a href="$base_url/admin/password.php?$sn=$sid" target="main">Password</a>
<br>
<br>
<a href="$base_url/admin/phpinfo.php?$sn=$sid" target="main">phpinfo()</a>
<br>
<br>
<a href="$base_url/admin/logout.php?$sn=$sid" target="_top">Logout</a>
<br>
<br>
<a href="http://destiney.com/prated/" target="_blank">pRated.com</a>
<br>
</td>
</tr>
</table>
</body>
</html>
EOF;
}

echo $content;

/*
 * $Id: menu.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>
