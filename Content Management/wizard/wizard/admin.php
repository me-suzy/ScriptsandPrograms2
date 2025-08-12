<?php 
/* 
    Administration Panel
    (c) 2005 Philip Shaddock, www.wizardinteractive.com
	This file is part of the Wizard Site Framework.

    This file is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    It is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with the Wizard Site Framework; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
   
	   
// main configuration file
	include_once 'inc/config_cms/configuration.php';
// database class
	include_once 'inc/db/db.php';
// language translation
	include_once 'inc/languages/' . $language . '.admin.php';
// authentication
	include_once 'inc/functions/user.php';


// configuration parameters
	$db = new DB();
	$db->query("SELECT * FROM ". DB_PREPEND . "config");
	$config = $db->next_record();
	$db->close();

//page id
	$id = 2;
//used at various points to flush the headers
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" />
<html xmlns="http://www.w3.org/1999/xhtml" />
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-Language" content="en-us" />
<meta name="robots" content="NONE" />
<meta http-equiv="expires" content="-1" />
<meta http-equiv= "pragma" content="no-cache" />
<meta name="author" content="Rage Pictures" />
<link rel="stylesheet" href="inc/css/nn4.css" type="text/css" media="screen" />
<!--link before import prevents Flash Of Unstyled Content in ie6pc -->
<style type="text/css" media="screen">@import url("templates/css/adminmenu.css");</style>
<style type="text/css" media="screen">@import url("templates/css/admin.css");</style>
<style type="text/css" media="screen">@import url("templates/css/topmenu.css");</style>
<!-- javascript for top menu -->
<?php include("inc/js/suckerfish.js"); ?>
</head>
<body >
<div id="main" width="799px" align="center">
    <!-- Main site table -->
    <table border="0" class="table" cellspacing="0" cellpadding="0" style="border-collapse: collapse"   bordercolor="Red" align="center">
        <tr><td>
   
    <?php 
		   echo "<div id=\"adminhomelink\" >";
	   			include "admin/adminhomelink.php";
	   echo "</div>";
	   ?>
       <div id="header" style="margin=0px; padding=0px;" align="center">
	   		<table cellspacing="0" cellpadding="0" width="90%" border="0">
	   			<tbody>
	   			<tr><td><img height="31" alt="" src="<?php echo CMS_WWW; ?>/admin/images/adminbanner.gif" width="799px" border="0" /></td></tr>
	   			</tbody>
	   		</table>
	   </div>
	   </td></tr>
	   <tr><td>
	   <!-- center containing table -->
	   <table border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse"   width="100%" id="centerTable">
	   <tr>
    <?php 

	            
	  			echo "<td valign=\"top\" align=\"left\" width=\"100%\">";
				echo "<div id=\"content\">";	
	   				include "admin/admin.php";
					
				echo "</div>";
				echo "</td>";
?>
	  <!--center table ends-->
	  </tr></table></td></tr>
	  <tr><td>
		<div id="footer" align="center">

	   <table height="3" cellspacing="1" cellpadding="1" width="100%" border="0">
	   <tbody>
	   <tr>
	   	<td><p align="center"><font class="footerText"><? echo $config[copyright]; ?></font></p></td>
		</tr>
		</tbody>
		</table>
	
	   </div>

	 </td></tr>
	<!-- Main site table ends -->
<!-- main div ends here -->
</table>
</div>
</body>
</html>