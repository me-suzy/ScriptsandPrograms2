<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>|| Img Upload Installation ||</title>
  <meta http-equiv="Content-Type"
 content="text/html; charset=iso-8859-1">
  <style type="text/css">
<!--
body,td,th {
	color: #333333;
	font-family: Lucida Sans;
	font-size: 12px;
}
a:link {
	color: #2757AF;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #2757AF;
}
a:hover {
	text-decoration: underline;
	color: #7B9FE1;
}
a:active {
	text-decoration: none;
	color: #2757AF;
}
body {
	background-image: url(images/bg.gif);
	margin-left: 0px;
	margin-right: 0px;
	margin-top: 50px;
	margin-bottom: 50px;
}	
input {
    background-color: #F2F2F2; 
    color: #2554AA;
    font-family: Verdana;
    font-size: 11;
}
select {
    background-color: #F2F2F2; 
    color: #2554AA;
    font-family: Verdana;
    font-size: 11;
}
textarea{
    background-color: #F2F2F2; 
    color: #2554AA;
    font-family: Verdana;
    font-size: 11;
}
}
.style2 {font-size: 9px}
.style3 {font-size: 12px}
.style5 {color: #2757AF}
-->
 </style>
</head>
<body>
<table align="center" border="0" cellpadding="0" cellspacing="0"
 width="580">
  <tbody>
    <tr>
      <td colspan="3" align="left" background="images/index_01.gif"
 height="137" valign="bottom">
      <p class="style3">  <br>
      </p>
      </td>
    </tr>
    <tr>
      <td colspan="3"> <img src="images/index_02.gif" alt=""
 height="15" width="580"></td>
    </tr>
    <tr>
      <td rowspan="5" align="left" background="images/index_03.gif"
 valign="top"> <img src="images/index_03.gif" alt="" height="174"
 width="21"></td>
      <td align="left" background="images/index_04.gif" valign="middle"><center>
		</center>
      <br>
      </span></strong> </td>
      <td rowspan="5" align="left" background="images/index_05.gif"
 valign="top" width="24">&nbsp;</td>
    </tr>
    <tr>
      <td> </td>
    </tr>
    <tr>
      <td align="left" bgcolor="#ffffff" height="130" valign="top"
 width="535">
      <p><a href="#"></a>&nbsp;</p>
      <center>
<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL & ~ E_NOTICE);
	
	include "functions.php";
	$exitp = new functions();
	
	class install
	{
		function chmod_check()
		{
			// Check chmod
			$getpath = dirname(__FILE__);
			$dirpath = str_replace("\\", "/", $getpath)."/";

			if(is_writable("mysql_data.db.php")) { $mysqlwrite = True; } else { $mysqlwrite = False; }
			if(is_writable($dirpath)) { $dirwrite = True; } else { $dirwrite = False; }
			if($mysqlwrite == False) { echo "<b>mysql_data.db.php</b> - Is not writable, please chmod this file to 0777<br />"; }
			if($dirwrite == False) { echo "<b>" . $dirpath . "</b> - Is not writable, please chmod this directory to 0777"; }

			if($mysqlwrite && $dirwrite == True)
			{
				echo "All required files and folders are writable. Please choose your installation method<br />(alternative should only be used if normal fails):<br /><br />";
				echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
					<select name="install_type">
					<option name="normal">Normal</option>
					<option name="alternative">Alternative</option>
					</select>
					<br /><br />
					<input type="submit" name="begin_install" value="Begin installation" /></form>';
			} else {
				echo "<p>Then click " . '<a href="' . $_SERVER['PHP_SELF'] . '">here</a>' . " to refresh the page.</p>";
			}
		}
		
		function generate_mysql()
		{
			echo '<p align="left"><u>MySQL information:</u></p>';
			
			if(isset ($_POST['mysql_info']))
			{
				if(@mysql_connect($_POST['mysqlhost'], $_POST['mysqluser'], $_POST['mysqlpass']))
				{
					if(@mysql_select_db($_POST['mysqldb']))
					{
						$opendbfile = fopen('mysql_data.db.php', 'w+');
						$dbwritefile = fwrite($opendbfile, '<?php
$mysqlhost = "' . $_POST['mysqlhost'] . '";
$mysqluser = "' . $_POST['mysqluser'] . '";
$mysqlpass = "' . $_POST['mysqlpass'] . '";
$mysqldb = "' . $_POST['mysqldb'] . '";
?>');
						echo "MySQL information was set succesfully!<br />";
						echo 'Click <a href="' . $_SERVER['PHP_SELF'] . '?step=createinfo">here</a> to continue';
						$exitp = new functions();
						$exitp->exitp_install();
					} else {
						echo "We could not select the database you specified.<br />";
					}
				} else {
					echo "We could not connect to the mysql server with the information you provided.<br />";
				}
			}
	
			echo '<p><form action="' . $_SERVER['PHP_SELF'] . '?step=mysqlinfo" method="post">
				  MySQL host: <input type="text" name="mysqlhost" value="localhost" /><br />
				  MySQL user: <input type="text" name="mysqluser" /><br />
				  MySQL pass: <input type="text" name="mysqlpass" /><br />
				  MySQL database: <input type="text" name="mysqldb" /><br /><br />
				  <input type="submit" name="mysql_info" value="Continue installation" /></p>';
		}
		
		function tables_user($alternative)
		{
			echo '<p align="left"><u>Admin information:</u></p>';
			if($alternative == True)
			{
				echo "<p>To you use alternative, open <b>mysql_data.db.php</b> in a text editor, fill<br /> out the information as required, and then re-upload the file.</p>";
			}
			include "mysql_data.db.php";
			if(@mysql_connect($mysqlhost, $mysqluser, $mysqlpass))
			{
				if(isset ($_POST['tables_data']))
				{
					if(!empty ($_POST['adminuser']) && !empty($_POST['adminemail']) && !empty ($_POST['adminpass']))
					{
						mysql_select_db($mysqldb);
						
						mysql_query("CREATE TABLE imgup_users(
						id int unsigned NOT NULL auto_increment primary key,
						name text NOT NULL,
						pass text NOT NULL,
						email text NOT NULL,
						user_group text NOT NULL)");
						
						mysql_query("CREATE TABLE imgup_config (
						id int unsigned NOT NULL auto_increment primary key,
						admin_email text NOT NULL,
						guest_custom_message text NOT NULL,
						display_login text NOT NULL,
						allow_register text NOT NULL,
						global_message text NOT NULL,
						display_guest text NOT NULL,
						display_global text NOT NULL,
						directory_limit text NOT NULL,
						max_upload text NOT NULL,
						allow_edit text NOT NULL,
						allowed_img text NOT NULL,
						allowed_ext text NOT NULL,
						useext text NOT NULL,
						final_global_message text NOT NULL,
						final_guest_message text NOT NULL,
						first_login text NOT NULL)");
						
						mysql_query("INSERT INTO imgup_users(
						id, name, pass, email, user_group) VALUES (1, '" . $_POST['adminuser'] . "', '" . $_POST['adminpass'] . "', '" . $_POST['adminemail'] . "', 'admin')");
						
						mysql_query("INSERT INTO imgup_config(
						admin_email, guest_custom_message, display_login, allow_register, global_message,
						display_guest, display_global, directory_limit, max_upload, allow_edit, allowed_img,
						allowed_ext, useext, final_global_message, final_guest_message, first_login) VALUES 
						('" . $_POST['adminemail'] . "', '', 'no', 'no', '', 'no', 'no', '5:MB', '1:MB', 'no', 'IMAGETYPE_GIF:invalid:gif,IMAGETYPE_JPEG:invalid:jpeg,IMAGETYPE_BMP:invalid:bmp,IMAGETYPE_PNG:invalid:png,IMAGETYPE_PSD:invalid:psd,IMAGETYPE_SWF:invalid:swf.', '', 'no', '', '', 'yes')");
						
						umask(0);
						mkdir($_POST['adminuser'], 0777);
						
						echo "Tables and information were created succesfully<br />";
						echo 'Click <a href="' . $_SERVER['PHP_SELF'] . '?step=finish">here</a> to continue';
						$exitp = new functions();
						$exitp->exitp_install();
					} else {
						echo "You didn't fill out a required field.";
					}
				} 
				
				echo '<p><form action="' . $_SERVER['PHP_SELF'] . '?step=createinfo" method="post">
					  Admin user: <input type="text" name="adminuser" /><br />
					  Admin e-mail: <input type="text" name="adminemail" /><br />
					  Admin password: <input type="password" name="adminpass" /><br /><br />
					  <input type="submit" name="tables_data" value="Continue installation" /></form></p>';
			} else {
				echo "We could not connect to the mysql server with the information on file. Please make sure all the information is valid.";
				$exitp = new functions();
				$exitp->exitp_install();
			}
			
		}
		
		function delete_install()
		{
			echo '<p align="left"><u>Installation complete:</u></p>';
			$exitp = new functions();
			if(isset ($_POST['delete_install']))
			{
				if(@unlink("install.php"))
				{
					echo "The installer has been deleted. Click " . '<a href="index.php?user=login">here</a>' . " to login.";
				} else {
					echo "The installer could not delete itself, please delete the installer by ftp.";
				}
				$exitp->exitp_install();
			}
			
			echo "Installation is now complete. For security purposes, you must delete the installer.";
			echo '<p><form action="' . $_SERVER['PHP_SELF'] . '?step=finish" method="post">
				  <input type="submit" name="delete_install" value="Delete installer" /></form></p>';
		}
	}
	
	$install = new install();

if($_GET['step'])
{	
	switch($_GET['step'])
	{
		case mysqlinfo:
			$install->generate_mysql();
			$exitp->exitp_install();
		break;
		case createinfo:
			$alternative = False;
			$install->tables_user($alternative);
			$exitp->exitp_install();
		break;
		case finish:
			$install->delete_install();
			$exitp->exitp_install();
		break;
		default:
			echo "This is not a valid step.";
			$exitp->exitp_install();
		break;
	}
}
	
	if(isset ($_POST['begin_install']))
	{
		switch($_POST['install_type'])
		{
			case Normal:
				$install->generate_mysql();
				$exitp->exitp_install();
			break;
			case Alternative:
				$alternative = True;
				$install->tables_user($alternative);
				$exitp->exitp_install();
			break;
		}
	}
	
	$install->chmod_check();
	
	$footer = new functions();
	$footer->footer_install();
?>