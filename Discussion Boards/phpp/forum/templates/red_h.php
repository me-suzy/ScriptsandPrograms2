<? echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><? echo $pagetitle; ?></title>
<? include "script.php"; ?>
<link rel="StyleSheet" href="templates/styles.css" type="text/css" media="screen"/>
<link rel="StyleSheet" href="templates/<? echo $template; ?>.css" type="text/css" media="screen"/>
</head>
<body>
<div id="container">
<div id="header">
<table class="menu" cellspacing="0" align="right"><tr>
<td class="menustart"></td>
<? include "menucode.php"; ?>
</tr></table>
<? include "dropdown.php"; ?>
</div>
<div class="shadow"><img src="gfx/blank.gif" width="1" height="1" alt="*"/></div>
<div id="body">
<? include "loginline.php"; ?>