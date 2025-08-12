<?php
if (file_exists("install.php")) {
	echo "You must delete install.php";
	exit();
}
session_start();
require_once('functions.php');
require('config.php');
if ($_GET['session'] == "start") {
	if ($admin['user'] == $_POST['user'] && $admin['password'] == $_POST['password']) {
		$_SESSION['admin'] = "logged in";
	}
}
if ($_GET['session'] == "stop") {
	$_SESSION = array();
	if (isset($_COOKIE[session_name()])) {
	   setcookie(session_name(), '', time()-42000, '/');
	}
	session_destroy();
}
$location = "admin.php";
if (isset($_GET['action'])) {
	if (isset($_GET['page'])) {
		$location .= "?page={$_GET['page']}";
		if (isset($_GET['show'])) {
			$location .= "&show={$_GET['show']}";
		}
	}
	header("Location: $location");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="author" content="Chris Warren" />
<meta name="copyright" content="2005 Chris Warren" />
<title>DS Plugger Administration</title>
<style type="text/css">
body {
	background-color: #457698;
	color: #fff;
}
h1 {
	text-align: center;
	font-size: 16px;
	font-weight: bold;
}
h2 {
	text-align: center;
	font-size: 14px;
	font-weight: bold;
}
h3 {
	text-align: center;
	font-size: 12px;
	font-weight: normal;
	font-style: italic;
}
td {
	width: 50%;
}
img {
	border: 0;
}
#container {
	background-color: #fff;
	color: #000;
	width: 780px;
	margin-left: auto;
	margin-right: auto;
	padding: 10px;
}
#container a {
	color: #457698;
}
#nav ul,li {
	display: inline;
	list-type: none;
}
#nav a {
	background-color: #457698;
	color: #fff;
	text-decoration: none;
	padding: 3px 10px;
	font-size: 12px;
}
#nav a:hover {
	background-color: #4a677b;
}
#code {
	width: 700px;
	text-align: left;
	background-color: #c1d7e7;
	padding: 6px;
	border: 1px solid #457698;
}
</style>
<?php
if (!isset($_SESSION['admin'])) {
	echo "<div align=\"center\">\n<div style=\"color: #000; background-color: #eee; border: 1px solid #000; padding: 60px; margin-top: 40px; width: 400px\"><strong>DS Plugger Administration</strong><br /><br />\n";
	echo "<form action=\"admin.php?session=start\" method=\"post\">\n";
	echo "<strong>Username</strong>: <input type=\"text\" name=\"user\" /><br />\n";
	echo "<strong>Password</strong>:&nbsp; <input type=\"password\" name=\"password\" /><br /><br />\n";
	echo "<input type=\"submit\" name=\"submit\" value=\"Submit\" />\n";
	echo "</form>\n</div>\n</div>\n</body>\n</html>";
	exit();
}
if (isset($_GET['action'])) {
	if ($_GET['action'] == "update") {
		if ($_GET['page'] == "settings") {
			$result = set_params("dsp_settings",$_POST);
		}
		elseif ($_GET['page'] == "style") {
			foreach ($_POST as $key=>$value) {
				if (preg_match("/^form/i",$key)) {
					$key = str_replace("form-","",$key);
					$form[$key] = $value;
				} else {
					$body[$key] = $value;
				}
			}
			$result = set_params("dsp_style_body",$body);
			$result1 = set_params("dsp_style_form",$form);
		}
	} elseif ($_GET['action'] == "delete") {
		$result = delete_plug($_POST);
	} elseif ($_GET['action'] == "ban") {
		$result = ban_ip($_POST);
	}
}
?>
</head>
<body>
<div align="center">
<div id="container">
<div style="text-align: right"><a href="admin.php?session=stop">Logout</a></div>
<h1>DS Plugger Administration</h1>
<ul id="nav">
<li><a href="?page=settings">Settings</a></li>
<li><a href="?page=style">Style</a></li>
<li><a href="?page=list">List</a></li>
<li><a href="?page=ban">Ban IP</a></li>
</ul>
<?php if (!isset($_GET['page']) || $_GET['page'] == "settings") { 
	$setting = get_params("dsp_settings");
	$body = get_params("dsp_style_body"); ?>
<form action="?page=settings&amp;action=update" method="post">
<table>
<tr>
<td>Button Width</td>
<td><input type="text" name="width" value="<?php echo $setting['width']; ?>" /></td>
</tr>
<tr>
<td>Button Height</td>
<td><input type="text" name="height" value="<?php echo $setting['height']; ?>" /></td>
</tr>
<tr>
<td>Columns of Buttons</td>
<td><input type="text" name="columns" value="<?php echo $setting['columns']; ?>" /></td>
</tr>
<tr>
<td>Rows of Buttons</td>
<td><input type="text" name="rows" value="<?php echo $setting['rows']; ?>" /></td>
</tr>
<tr>
<td>Link Target</td>
<td><input type="text" name="target" value="<?php echo $setting['target']; ?>" /></td>
</tr>
<tr>
<td></td>
<td><input type="submit" name="submit" value="Update" /></td>
</tr>
</table>
</form>
<div id="code">
<h2>Insert the following code into your web page</h2>
<h3>If you've made changes, don't forget to update your iframe code</h3>
<?php
$url = $_SERVER['PHP_SELF'];
$url = str_replace("admin.php","dsplugger.php",$url);
$vars = strstr($url,"?");
$url = str_replace($vars,"",$url);
$height = $setting['height'] + 3;
$total_height = $height * $setting['rows'] + 100 + $body['font-size'];
$width = $setting['width'] + 3;
$total_width = $width * $setting['columns'] + 10;
if ($body['border-color'] != "") {
	$border = " border: 1px solid {$body['border-color']}";
} else {
	$border = "";
}
?>
<code>&lt;iframe src="<?php echo "http://{$_SERVER['HTTP_HOST']}$url"; ?>" frameborder="0" scrolling="no" style="height: <?php echo $total_height; ?>px; width: <?php echo $total_width; ?>px;<?php echo $border; ?>"&gt;&lt;/iframe&gt;</code>
</div>
<?php } elseif ($_GET['page'] == "style") {
	$body = get_params("dsp_style_body");
	$form = get_params("dsp_style_form");
	$setting = get_params("dsp_settings"); ?>
<form action="?page=style&amp;action=update" method="post">
<table>
<tr>
<td>Border Color</td>
<td><input type="text" name="border-color" value="<?php echo $body['border-color']; ?>" /></td>
</tr>
<tr>
<td>Background Color</td>
<td><input type="text" name="background-color" value="<?php echo $body['background-color']; ?>" /></td>
</tr>
<tr>
<td>Font</td>
<td><input type="text" name="font-family" value="<?php echo $body['font-family']; ?>" /></td>
</tr>
<tr>
<td>Font Color</td>
<td><input type="text" name="color" value="<?php echo $body['color']; ?>" /></td>
</tr>
<tr>
<td>Font Size</td>
<td><input type="text" name="font-size" value="<?php echo $body['font-size']; ?>" /></td>
</tr>
<tr>
<td>Form Field Color</td>
<td><input type="text" name="form-background-color" value="<?php echo $form['background-color']; ?>" /></td>
</tr>
<tr>
<td>Form Font</td>
<td><input type="text" name="form-font-family" value="<?php echo $form['font-family']; ?>" /></td>
</tr>
<tr>
<td>Form Font Color</td>
<td><input type="text" name="form-color" value="<?php echo $form['color']; ?>" /></td>
</tr>
<tr>
<td>Form Font Size</td>
<td><input type="text" name="form-font-size" value="<?php echo $form['font-size']; ?>" /></td>
</tr>
<tr>
<td></td>
<td><input type="submit" name="submit" value="Update" /></td>
</tr>
</table>
</form>
<div id="code">
<h2>Insert the following code into your web page</h2>
<h3>If you've made changes, don't forget to update your iframe code</h3>
<?php
$url = $_SERVER['PHP_SELF'];
$url = str_replace("admin.php","dsplugger.php",$url);
$vars = strstr($url,"?");
$url = str_replace($vars,"",$url);
$height = $setting['height'] + 3;
$total_height = $height * $setting['rows'] + 100 + $body['font-size'];
$width = $setting['width'] + 3;
$total_width = $width * $setting['columns'] + 10;
if ($body['border-color'] != "") {
	$border = " border: 1px solid {$body['border-color']}";
} else {
	$border = "";
}
?>
<code>&lt;iframe src="<?php echo "http://{$_SERVER['HTTP_HOST']}$url"; ?>" frameborder="0" scrolling="no" style="height: <?php echo $total_height; ?>px; width: <?php echo $total_width; ?>px;<?php echo $border; ?>"&gt;&lt;/iframe&gt;</code>
</div>
<?php } elseif ($_GET['page'] == "list") {
	$setting = get_params("dsp_settings");
	$num = $setting['columns'] * $setting['rows']; ?>
<form action="?page=list&amp;action=delete" method="post">
<input type="submit" name="delete_all" value="Delete All" /> 
<input type="submit" name="delete_all_but" value="Delete All But <?php echo $num; ?>" />
</form>
<?php
	$plug = get_plugs();
	$n = 0;
	if ($plug) {
		foreach ($plug as $key=>$value) {
			if (isset($_GET['show'])) { 
				$vars = "page=list&amp;show=all";
			} else {
				$vars = "page=list";
			}
			echo "<form action=\"?{$vars}&amp;action=delete\" method=\"post\">\n";
			echo "{$value['ip']} <a target=\"{$setting['target']}\" href=\"{$value['url']}\"><img src=\"{$value['image']}\" width=\"{$setting['width']}px\" height=\"{$setting['height']}px\" alt=\"{$value['url']}\" /></a>\n";
			echo "<input type=\"hidden\" name=\"id\" value=\"{$value['id']}\" /><input type=\"submit\" name=\"delete\" value=\"Delete\" />\n</form>\n";
			if (!isset($_GET['show']) || $_GET['show'] != "all") {
				if ($n == "19") {
					echo "<a href=\"?page=list&amp;show=all\">Show All</a>\n";
					break;
				} else {
					$n++;
				}
			}
		}
	} else {
		echo "No plugs yet.\n";
	}
} elseif ($_GET['page'] == "ban") {
?>
<form action="?page=ban&amp;action=ban" method="post">
<input type="submit" name="unban_all" value="Unban All" />
</form>
<form action="?page=ban&amp;action=ban" method="post">
IP:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="ip" /><br />
Comment: <input type="text" name="comment" /><br />
<input type="submit" name="ban" value="Ban IP" /> <input type="submit" name="ban_delete" value="Ban IP and Delete all plugs by IP" />
</form>
<?php
	$setting = get_params("dsp_settings");
	$banned = get_banned();
	$n = 0;
	if ($banned) {
		foreach ($banned as $key=>$value) {
			if (isset($_GET['show'])) {
				$vars = "page=ban&amp;show=all";
			} else {
				$vars = "page=ban";
			}
			echo "<form action=\"?{$vars}&amp;action=ban\" method=\"post\">\n";
			echo "{$value['ip']} - {$value['comment']} \n<input type=\"hidden\" name=\"ip\" value=\"{$value['ip']}\" />\n<input type=\"submit\" name=\"unban\" value=\"Unban IP\" />\n";
			echo "</form>\n";
			if (!isset($_GET['show']) || $_GET['show'] != "all") {
				if ($n == "19") {
					echo "<a href=\"?page=list&amp;show=all\">Show All</a>\n";
					break;
				} else {
					$n++;
				}
			}
		}
	} else {
		echo "No IPs banned yet.\n";
	}
}
?>
</div>
<br />
Powered by <a style="color: #fff" href="http://www.dawgiestyle.com">DS Plugger</a> Version <?php echo $setting['version']; ?>
</div>
</body>
</html>