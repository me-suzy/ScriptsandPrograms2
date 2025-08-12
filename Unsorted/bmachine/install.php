<?php

/*
********************************************

BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com

********************************************
*/



include "config.php";

if(!$_POST[install]) {
echo <<<EOF
<html>
<head>
<title>boastMachine $ver installation</title>
<link rel="stylesheet" href="style.css">
</head>
<body bgcolor="white" text="black" link="blue" vlink="purple" alink="red">
<p align="center"><span class="t_small"><b>boastMachine $ver installation</b></span></p>
<p align="center"><span class="t_small">Please check whether the following configuration is correct<br>
[ Edit the variables in config.php ]</span></p>
EOF;

if($db == "text") {
?>
<p align="center"><span class="t_small"><font color="red">bMachine is currently running in text&nbsp;mode!<br>This 
installation is only for MySQL mode.<br>Please change the $db variable in config.php to mysql<br>to 
use bMachine in mysql mode</font></span></p>
<?
}

echo <<<EOF
<form method="POST" action="install.php">
<input type="hidden" name="install" value="true">
<table align="center" border="0" cellpadding="2" cellspacing="0" width="50%">
<tr><td width="50%">
<span class="t_small">MySQL Host</span>
</td><td>
<input type="text" onClick="this.blur();" name="db_host" class="search" value="$my_host">
</td></tr><tr><td width="50%">
<span class="t_small" class="search">MySQL Username</span>
</td><td>
<input type="text" onClick="this.blur();" name="db_user" class="search" value="$my_user">
</td></tr><tr>
<td width="50%">
<span class="t_small">MySQL Password</span>
</td><td>
<input type="text" onClick="this.blur();" name="db_pass" class="search" value="$my_pass">
</td></tr><tr><td width="50%" valign="top">
<span class="t_small">MySQL Database name</span></td>
<td>
<input type="text" onClick="this.blur();" name="db_name" class="search" value="$my_db"><br>
</td></tr>
<tr><td width="50%" valign="top">
<span class="t_small">Overwrite existing tables?</span></td>
<td>
<input type="checkbox" name="ow" class="search">
<span class="t_small"><font color="red">
WARNING! - This will overwrite all existing data!
</font></span><br><br>
<input  class="search" type="submit" value="Continue">
</td></tr>
</table>
</form><br><br>
<p align="center"><span class="t_small"><a href="http://boastology.com">bMachine $ver</a></span></p>
</body></html>
EOF;
exit();
}

// Tables
$tbname=$my_prefix."posts";

$dat=<<<EOF
CREATE TABLE $tbname (
  id INT NOT NULL AUTO_INCREMENT,
  auth_name text NOT NULL default '',
  auth_email text NOT NULL default '',
  auth_url text NOT NULL default '',
  title text NOT NULL default '',
  date text NOT NULL default '',
  file text NOT NULL default '',
  format text NOT NULL default '',
  keyws text NOT NULL default '',
  summary text NOT NULL default '',
  data text NOT NULL default '',
  ext1 text NOT NULL default '',
  ext2 text NOT NULL default '',
  PRIMARY KEY  (id)
);
EOF;

$tbname2=$my_prefix."comments";

$dat2=<<<EOF
CREATE TABLE $tbname2 (
  id INT NOT NULL AUTO_INCREMENT,
  auth_name text NOT NULL default '',
  auth_email text NOT NULL default '',
  auth_url text NOT NULL default '',
  date text NOT NULL default '',
  data text NOT NULL default '',
  ext1 text NOT NULL default '',
  ext2 text NOT NULL default '',
  PRIMARY KEY  (id)
);
EOF;

$tbname3=$my_prefix."m_data";

$dat3=<<<EOF
CREATE TABLE $tbname3 (
  id INT NOT NULL AUTO_INCREMENT,
  cat text NOT NULL default '',
  arch text NOT NULL default '',
  ext1 text NOT NULL default '',
  ext2 text NOT NULL default '',
  ext3 text NOT NULL default '',
  ext4 text NOT NULL default '',
  PRIMARY KEY  (id)
);
EOF;


	@mysql_connect($my_host, $my_user, $my_pass) or errd("Error!","Cant connect to mysql host \"$my_host\"");
	@mysql_select_db($_POST[db_name]) or errd("Error!","Cant access database \"$my_db\" from host \"$my_host\"");

	// Delete the tables if Overwriting is set
	if ($_POST[ow])
	{
		@mysql_query("DROP TABLE `$tbname`");
		@mysql_query("DROP TABLE `$tbname2`");
		@mysql_query("DROP TABLE `$tbname3`");
	}

// Create the tables
@mysql_query($dat) or errd("Error!","Error creating table $tbname<br><br> [ ".mysql_error()." ]");
@mysql_query($dat2) or errd("Error!","Error creating table $tbname2<br><br> [ ".mysql_error()." ]");
@mysql_query($dat3) or errd("Error!","Error creating table $tbname2<br><br> [ ".mysql_error()." ]");

mysql_close();

	// Delete install.php after installation
	@chmod("install.php", 0777);
	if(@unlink("install.php")) { $flg="true"; }

?>

<html><head>
<title>Installation completed successfully!</title>
<link rel="stylesheet" href="style.css">
</head>
<body bgcolor="white" text="black" link="blue" vlink="purple" alink="red">
<p align="center"><span class="title"><b>Installation completed successfully!</b></span><br><br>
<span class="t_small">bMachine <? echo $ver; ?> [mysql]&nbsp;installation has completed successfully!<br>Please 
goto <a href="<? echo $c_urls; ?>/admin.php"><? echo $c_urls; ?>/admin.php</a> and start 
Posting!</span></p> <br><br><br>
<table align="center" border="1" cellpadding="4" cellspacing="0" width="339" bordercolordark="white" bordercolorlight="black">
<tr>
<td width="335">
<span class="title"><font color="red">
<?
if($flg != "true") {
?>
Warning! install.php could not be deleted!<br>Please delete install.php manually 
before starting to<br>use bMachine
<? } else { ?>
install.php was automatically deleted after installation due to security reasons!
<? } ?>
</font></span></td>
</tr>
</table><br><br><br>
<p align="center"><span class="t_small">boastMachine's official website: <a href="http://boastology.com">boastology.com</a></span></p>

</html>
