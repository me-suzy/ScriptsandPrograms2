<?php
//load all the configuration and database settings
include_once '../page_header.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-Language" content="en-us" />
<meta name="robots" content="<?php echo $config['robots']; ?>" />
<meta name="author" content="<?php echo $config['siteAdmin']; ?>" />
<meta name="description" content="<?php echo $config['description']; ?>" />
<meta name="keywords" content="<?php echo $config['keywords']; ?>" />
<link href="../templates/css/basestyles.css" rel="stylesheet" type="text/css">
<?php
if ($config[topmenu] >= 1)
	{ echo "<link href=\"../templates/css/topmenu.css\" rel=\"stylesheet\" type=\"text/css\">"; }
else { echo "<link href=\"../templates/css/leftmenu.css\" rel=\"stylesheet\" type=\"text/css\">"; }
?>
<link rel="shortcut icon" type="image/ico" href="favicon.ico" />
<title><?php echo $config['name']; ?></title>

<!-- javascript for DHTML (Suckerfish) menu -->
<?php include("../inc/js/suckerfish.js"); ?>
</head>
<body id="bd">

<!-- Register, Login etc -->
<?php include("../searchandlogin.php"); ?>


<!-- Main Layout Table -->
<table align="center" cellpadding="20" cellspacing="0" id="mainTable">

	
<!-- Header -->
	<tr>
		<td colspan="3" id="header">&nbsp;</td>
	</tr>

<!-- Menu Bar -->
<?php
	if ($config[topmenu] >= 1)
	{
	echo "<tr>";
		echo "<td  id=\"topMenu\" colspan=\"2\">";
		 	include("../inc/functions/topMenu.php"); 
		echo "</td>";
	echo "</tr>";
	}
?>


<!-- end of Menubar -->

<!-- Sidebar -->
<tr>
	<td valign="top" >
		<table id="sidebar">
			<!-- sidebar vertical menu -->
    				<?php
						if (!$config[topmenu] >= 1)
						{	echo "<tr><td><table id=\"sidebarmenu\"><tr><td valign=\"top\">";
 							include("../inc/functions/leftMenu.php"); 
							echo "</td></tr></table></td></tr>";
     					}
					?>
			<tr>
				<td valign="top" id="sidebarbottom" >
			Sidebar Bottom				</td>
			</tr>
		</table>  	</td>

<!-- Main Content -->
<td valign="top" id="centercontent"><p><strong>3 Column Fixed Layout </strong></p>
  <ul>
    <li>Edit the css file (/templates/css/basestyles.css) to vary the width of the columns. </li>
    <li>Can be vertical or horizontal menu design. </li>
  </ul>  <p>&nbsp;</p></td>
<td valign="top" id="rightcontent">&nbsp;</td>
</tr>
<tr>

<!-- Footer -->
<td id="footer" colspan="3">
<?php include("../footer.php"); ?></td>
</tr>
</table>
<!-- end Main Layout Table -->	
</body>
</html>