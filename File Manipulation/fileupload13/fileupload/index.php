<?php

#---------------------------------------------#
# file upload manager 1.3
# 13.10.2003
# 
# written by thepeak (adam medici)
# copyright (c) 2003 thepeak of mtnpeak.net
#
# www: http://webdev.mtnpeak.net
# www: http://www.xd3v.com
# email: thepeak@gmx.net
#
# A simple, powerful tool to upload and manage 
# files using your web browser.
# 
# This program is free software; you can redistribute 
# it and/or modify it under the terms of the GNU General 
# Public License as published by the Free Software 
# Foundation; either version 2 of the License, or 
# (at your option) any later version, as long as the 
# copyright info and links stay intact. You may not sell
# this program under any circumstance without written 
# permission from the author. Full license is in the 
# included ZIP/GZ package this script was downloaded in.
#
# *please send me feedback - and enjoy!
#---------------------------------------------#

################## configurations ####################

# header & title of this file
$title = "File Upload Manager";

# individual file size limit - in bytes (102400 bytes = 100KB)
$file_size_ind = "102400";

# the upload store directory (chmod 777)
$dir_store= "store";

# the images directory
$dir_img= "img";

# the style-sheet file to use (located in the "img" directory, excluding .css)
$style = "style-def";

# the file type extensions allowed to be uploaded
$file_ext_allow = array("gif","jpg","jpeg","png","txt","nfo","doc","rtf","htm","dmg","zip","rar","gz","exe");

# option to display the file list
# to enable/disable, enter '1' to ENABLE or '0' to DISABLE (without quotes)
$file_list_allow = 1;

# option to allow file deletion
# to enable/disable, enter '1' to ENABLE or '0' to DISABLE (without quotes)
$file_del_allow = 1;

# option to password-protect this script [-part1]
# to enable/disable, enter '1' to ENABLE or '0' to DISABLE (without quotes)
$auth_ReqPass = 0;

# option to password-protect this script [-part2]
# if "$auth_ReqPass" is enabled you must set the username and password
$auth_usern = "username";
$auth_passw = "password";

################ end of configurations ###############


# DO NOT ALTER OR EDIT BELOW THIS LINE UNLESS YOU ARE AN ADVANCED PHP PROGRAMMER

?>
<?
if (@phpversion() < '4.1.0') {
    $_FILE = $HTTP_POST_FILES;
    $_GET = $HTTP_GET_VARS;
    $_POST = $HTTP_POST_VARS;
}
clearstatcache();
error_reporting(E_ALL & ~E_NOTICE);
$fum_vers = "1.3"; # do not edit this line, the script will not work!!!
$fum_info_full = "File Upload Manager v$fum_vers";

function authDo($auth_userToCheck, $auth_passToCheck) 
{
	global $auth_usern, $auth_passw;
	$auth_encodedPass = md5($auth_passw);
	
	if ($auth_userToCheck == $auth_usern && $auth_passToCheck == $auth_encodedPass) {
	$auth_check = TRUE;
	} else {
	$auth_check = FALSE;
	} 
	return $auth_check;
	}
	
	if (isset($logout)) {
	setcookie ('fum_user', "",time()-3600); 
	setcookie ('fum_pass', "",time()-3600);
	}
		
	if (isset($login)) {
	$auth_password_en = md5($auth_formPass); 
	$auth_username_en = $auth_formUser;

	if (authDo($auth_username_en, $auth_password_en)) { 
	setcookie ('fum_user', $auth_username_en,time()+3600); 
	setcookie ('fum_pass', $auth_password_en,time()+3600); 
	$auth_msg = "<b>Authentication successful!</b> The cookies have been set.<br><br>".
	$auth_msg . "Your password (MD5 encrypted) is: $auth_password_en";
	} else { 
	$auth_msg = "<b>Authentication error!</b>";
	}
}

if (($_GET[act]=="dl")&&$_GET[file]) 
{
	if ($auth_ReqPass != 1 || ($auth_ReqPass == 1 && isset($fum_user) && !isset($logout))) { 
	if ($auth_ReqPass != 1 || ($auth_ReqPass == 1 && authDo($fum_user, $fum_pass))) {

	$value_de=base64_decode($_GET[file]);
	$dl_full=$dir_store."/".$value_de;
	$dl_name=$value_de;

	if (!file_exists($dl_full))
	{ 
	echo"ERROR: Cannot download file, it does not exist.<br>»<a href=\"$_SERVER[PHP_SELF]\">back</a>";  
	exit();
	} 
	
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$dl_name");
	header("Content-Length: ".filesize($dl_full));
	header("Accept-Ranges: bytes");
	header("Pragma: no-cache");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-transfer-encoding: binary");
			
	@readfile($dl_full);
	
	exit();

	}
	}
}

function getlast($toget)
{
	$pos=strrpos($toget,".");
	$lastext=substr($toget,$pos+1);

	return $lastext;
}

function replace($o)
{
	$o=str_replace("/","",$o);
	$o=str_replace("\\","",$o);
	$o=str_replace(":","",$o);
	$o=str_replace("*","",$o);
	$o=str_replace("?","",$o);
	$o=str_replace("<","",$o);
	$o=str_replace(">","",$o);
	$o=str_replace("\"","",$o);
	$o=str_replace("|","",$o);
	
	return $o;
}

?>
<!-- <?=$fum_info_full?> -->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><? echo ($title) ? ($title) : ("File Upload Manager"); ?></title>
<link rel="stylesheet" href="<?=$dir_img?>/<?=$style?>.css" type="text/css">
<?
	if ($auth_ReqPass == 1) 
	{ 
		if (isset($login) || isset($logout)) {
			echo("<meta http-equiv='refresh' content='2;url=$_SERVER[PHP_SELF]'>");
		}
	}
?>
</head>
<body bgcolor="#F7F7F7"><br><br>
<center>
<?	
	if ($auth_ReqPass != 1 || ($auth_ReqPass == 1 && isset($fum_user) && !isset($logout))) { 
	if ($auth_ReqPass != 1 || ($auth_ReqPass == 1 && authDo($fum_user, $fum_pass))) {
?>
<table width="560" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td><font size="3"><b><i><? echo ($title) ? ($title) : ("File Upload Manager"); ?></i></b></font>&nbsp;<font style="text-decoration: bold; font-size: 9px;">v<?=$fum_vers?></font>&nbsp;
<? 
	#--Please do not remove my link/copyright as it is unfair and a breach of the license--#
	echo"<a href=\"http://www.mtnpeak.net\" style=\"text-decoration: none; color: #C0C0C0; font-size: 9px; cursor: default\";>&copy; thepeak</a>"; 
?>
    </td>
   </tr>
</table>
<?
	if (!eregi("777",decoct(fileperms($dir_store))))
	{
		echo"<br><br><b><h4><font color=\"FF0000\">ERROR: cannot access the upload store file directory. please chmod the \"$dir_store\" directory with value 0777 (xrw-xrw-xrw)!</h4></font></b><br>»<a href=\"$_SERVER[PHP_SELF]\">refresh</a>";
	}
	else
	{
		if (!$_FILES[fileupload])
		{
?>
<table width="560" cellspacing="0" cellpadding="0" border="0" class="table_decoration" style="padding-top:5px;padding-left=5px;padding-bottom:5px;padding-right:5px">
  <form method="post" enctype="multipart/form-data">
  <tr>
    <td>file:</td><td><input type="file" name="fileupload" class="textfield" size="30"></td>
  </tr>
  <tr>
    <td>rename to:</td><td><input type="text" name="rename" class="textfield" size="46"></td>
  </tr>
  <tr>
    <td>file types allowed:</td><td>
	<?
	for($i=0;$i<count($file_ext_allow);$i++)
	{
		if (($i<>count($file_ext_allow)-1))$commas=", ";else $commas="";
		list($key,$value)=each($file_ext_allow);
		echo $value.$commas;
	}
	?>
    </td>
  </tr>
  <tr>
    <td>file size limit:</td>
	<td>
		<b><?
			if ($file_size_ind >= 1048576) 
			{
				$file_size_ind_rnd = round(($file_size_ind/1024000),3) . " MB";
			} 
			elseif ($file_size_ind >= 1024) 
			{	
				$file_size_ind_rnd = round(($file_size_ind/1024),2) . " KB";
			} 
			elseif ($file_size_ind >= 0) 
			{
				$file_size_ind_rnd = $file_size_ind . " bytes";
			} 
			else 
			{
				$file_size_ind_rnd = "0 bytes";
			}
			
			echo "$file_size_ind_rnd";
		?></b>
	</td>
  </tr>
  <tr>
    <td colspan="2"><input type="submit" value="upload" class="button">&nbsp;<input type="reset" value="clear" class="button"></td>
  </tr>
  </form>
</table>
<?
		if ((!$_GET[act]||!$_GET[file])&&$_GET[act]!="delall")
		{
			$opendir = @opendir($dir_store);

			while ($readdir = @readdir($opendir))
			{
				if ($readdir<>"." && $readdir<>".." && $readdir != "index.html")
				{
					$filearr[] = $readdir;
				}
				$sort=array();
				for($i=1;$i<=count($filearr);$i++)
				{
					$key = sizeof($filearr)-$i;
					$file = $filearr[$key];

					$sort[$i]=$file;
				}
				asort($sort);
			}
?>
<br>
<table width="560" cellspacing="0" cellpadding="0" border="0" class="table_decoration" style="padding-left:5px">
  <tr>
    <td><b>admin tools:</b>
<? 
	if ($file_del_allow != 1 && $auth_ReqPass != 1)
	{
		echo"<i>none</i>";
	}

	if ($file_del_allow == 1 && $file_list_allow == 1 && (count($filearr) >= 1)) 
	{ 
		echo"<a href=\"javascript:;\" onClick=\"cf=confirm('Are you sure you want to delete ALL FILES?');if (cf)window.location='?act=delall'; return false;\" style=\"font-size: 9px;\">&lt;delete all files&gt;</a>";
	}
	
	if ($auth_ReqPass == 1) 
	{ 
		echo"&nbsp;<a href=\"$_SERVER[PHP_SELF]?logout=1\" style=\"font-size: 9px;\">&lt;log-out&gt;<a>";
	}
?>
    </td>
  </tr>
</table>
<br>
<?	
			if ($file_list_allow == 1 && (count($filearr) >= 1)) 
			{
?>
<table width="560" cellspacing="0" cellpadding="0" border="0" class="table_decoration" style="padding-left:6px">
  <tr bgcolor="#DBDBDB">
    <td align="left" width="46%">FILE NAME</td>
    <td align="center" width="12%">FILE TYPE</td>
    <td align="center" width="12%">FILE SIZE</td>
    <td align="center" width="30%">FUNCTIONS</td>
  </tr>
<?
				for($i=1;$i<=count($sort);$i++)
				{
					list($key,$value)=each($sort);

					if ($value)
					{
						$value_en = base64_encode($value);
						$value_view=$value;
						
							if (strlen($value) >= 48) 
							{ 
								$value_view = substr($value_view, 0, 45) . '...';
							}
?>
<tr>
    <td width="30%"><?="<a href=\"?act=view&file=$value_en\">$value_view</a>"?></td>
    <td align="center" width="5%"><? echo strtoupper(getlast($value)); ?></td>
    <td align="center" width="5%"><?

    	$value_full = $dir_store."/".$value;
    	$file_size = filesize($value_full);
		
		if ($file_size >= 1048576) 
		{
			$show_filesize = number_format(($file_size / 1048576),2) . " MB";
		} 
		elseif ($file_size >= 1024) 
		{
			$show_filesize = number_format(($file_size / 1024),2) . " KB";
		} 
		elseif ($file_size >= 0) 
		{
			$show_filesize = $file_size . " bytes";
		} 
		else 
		{
			$show_filesize = "0 bytes";
		}

		echo "$show_filesize";
		
?></td>
    <td align="center" width="5%"><?="<a title=\"View File\" href=\"?act=view&file=$value_en\">&lt;view&gt;</a>"?> | 
<?
	if ($file_del_allow == 1) 
	{ 
		echo"<a title=\"Download file\" href=\"?act=dl&file=$value_en\">&lt;dl&gt;</a>";
 	} 
	else 
	{ 
		echo"<a title=\"Download file\" href=\"?act=dl&file=$value_en\">&lt;download&gt;</a>"; 
	} 

	if ($file_del_allow == 1) 
	{ 
		echo"&nbsp;|&nbsp;<a title=\"Delete file\" href=\"javascript:;\" onClick=\"cf=confirm('Are you sure you want to delete this file?');if (cf)window.location='?act=del&file=$value_en'; return false;\">&lt;delete&gt;</a>";
	} 
	else 
	{ 
		echo"&nbsp;"; 
	} 
?>
    </td>
</tr>
<?
				}
				else
				{
					echo"<br>";
				}
				}
?>
</table></center>
<?
			}
		}
		elseif (($_GET[act]=="view")&&$_GET[file])
		{
			$value_de = base64_decode($_GET[file]);
			echo"<script language=\"javascript\">\nViewPopup = window.open(\"$dir_store/$value_de\", \"fum_viewfile\", \"toolbar=no,status=no,menubar=no,scrollbars=yes,resizable=yes,location=no,width=640,height=480\")\nViewPopup.document.bgColor=\"#F7F7F7\"\nViewPopup.document.close()\n</script>";
			echo"<br><img src=\"$dir_img/info.gif\" width=\"15\" height=\"15\">&nbsp;<b><font size=\"2\">file opened!</font></b><br>»<a href=\"$_SERVER[PHP_SELF]\">back</a><br><br><br>If the file did not display, you must <b>disable</b> your popup manager, or enable javascript in your browser.";
		}
		elseif (($_GET[act]=="del")&&$_GET[file])
		{
			$value_de = base64_decode($_GET[file]);
			@unlink($dir_store."/$value_de");
			echo"<br><img src=\"$dir_img/info.gif\" width=\"15\" height=\"15\">&nbsp;<b><font size=\"2\">file has been deleted!</font></b><br>»<a href=\"$_SERVER[PHP_SELF]\">back</a>";
		}
		if ($_GET[act]=="delall")
		{
			$handle = opendir($dir_store);
			while($file=readdir($handle))
			if (($file != ".")&&($file != ".."))
			@unlink($dir_store."/".$file);
			closedir($handle);

			echo"<br><img src=\"$dir_img/info.gif\" width=\"15\" height=\"15\">&nbsp;<b><font size=\"2\">all files have been deleted!</font></b><br>»<a href=\"$_SERVER[PHP_SELF]\">back</a>";
		}

	}
	else
	{
		echo"<br><br>";
		$uploadpath=$dir_store."/";
		$source=$_FILES[fileupload][tmp_name];
		$fileupload_name=$_FILES[fileupload][name];
		$weight=$_FILES[fileupload][size];

		for($i=0;$i<count($file_ext_allow);$i++)
		{
			if (getlast($fileupload_name)!=$file_ext_allow[$i])
				$test.="~~";
		}
		$exp=explode("~~",$test);

		if (count($exp)==(count($file_ext_allow)+1))
		{
			echo"<br><img src=\"$dir_img/error.gif\" width=\"15\" height=\"15\">&nbsp;<b><font size=\"2\">ERROR: your file type is not allowed (".getlast($fileupload_name).")</font>, or you didn't specify a file to upload.</b><br>»<a href=\"$_SERVER[PHP_SELF]\">back</a>";
		}
		else
		{

			if ($weight>$file_size_ind)
			{
				echo"<br><img src=\"$dir_img/error.gif\" width=\"15\" height=\"15\">&nbsp;<b><font size=\"2\">ERROR: please get the file size less than ".$file_size_ind." BYTES  (".round(($file_size_ind/1024),2)." KB)</font></b><br>»<a href=\"$_SERVER[PHP_SELF]\">back</a>";
			}
			else
			{

				foreach($_FILES[fileupload] as $key=>$value)
				{
					echo"<font color=\"#3399FF\">$key</font> : $value <br>";
				}

				echo "<br>";

				$dest = ''; 

				if (($source != 'none') && ($source != '' ))
				{
					$dest=$uploadpath.$fileupload_name;
					if ($dest != '')
					{
						if (file_exists($uploadpath.$fileupload_name))
						{
							echo"<br><img src=\"$dir_img/error.gif\" width=\"15\" height=\"15\">&nbsp;<b><font size=\"2\">ERROR: that file has already been uploaded before, please choose another file</font></b><br>»<a href=\"$_SERVER[PHP_SELF]\">back</a>";
						}
						else
						{
							if (copy($source,$dest))
							{
								if ($_POST[rename])
								{
									$_POST[rename]=replace($_POST[rename]);
									$exfile=explode(".",$fileupload_name);
									
									if (@rename("$dir_store/$fileupload_name","$dir_store/$_POST[rename].".getlast($fileupload_name))) 
									{
										echo"<br><img src=\"$dir_img/info.gif\" width=\"15\" height=\"15\">&nbsp;<b><font size=\"2\">file has been renamed to $_POST[rename].".getlast($fileupload_name)."!</font></b></font><br>";
									}
								}
								echo"<br><img src=\"$dir_img/info.gif\" width=\"15\" height=\"15\">&nbsp;<b><font size=\"2\">file has been uploaded!</font></b><br>»<a href=\"$_SERVER[PHP_SELF]\">back</a>";
							}
							else
							{
								echo"<br><img src=\"$dir_img/error.gif\" width=\"15\" height=\"15\">&nbsp;<b><font size=\"2\">ERROR: cannot upload, please chmod the dir to 777</font></b><br>»<a href=\"$_SERVER[PHP_SELF]\">back</a>";
							}
						}
					}
				}
			}
		}
	}
}

#/# end of main script, start authentication code IF user not logged in IF $auth_ReqPass is enabled

	} 
	else 
	{
		echo("<p><img src=\"$dir_img/error.gif\" width=\"15\" height=\"15\">&nbsp;Authentication error</p>" .
"<p><a href='$_SERVER[PHP_SELF]?logout=1'>Delete cookies and login again<a></p>");
	}
	} 
	else 
	{

	if (!isset($login) || isset($relogin)) {
?>
<font size="3"><b><i><? echo ($title) ? ($title) : ("File Upload Manager"); ?></i> - Authentication</b></font><br><br>
<table class="table_auth"><tr><td><center>
Please enter the username and password to enter the restricted area.<br>
You must have cookies enabled in your browser to continue.
</center></td></tr></table>
<form action="<?=$_SERVER[PHP_SELF]?>?login=1" method="POST"><p>
Username: <input type="text" name="auth_formUser" size="20"><br>
Password: <input type="password" name="auth_formPass" size="20">
<p><input type="submit" name="submit" class="button" value="Log-In"></p>
</form></center>
<?
	} 
	elseif (isset($login)) 
	{
		echo("<p>$auth_msg</p>" . "<p>You'll be redirected in 2 seconds!</p>");
	}
	}
?>
</body>
</html>
<!-- Copyright (c) 2003 thepeak. Get your own copy of this free PHP script from www.mtnpeak.net -->