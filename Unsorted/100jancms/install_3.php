<html>
<head>
<title>100janCMS Articles Control: Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="cms_style.css" rel="stylesheet" type="text/css">
<link REL = "SHORTCUT ICON" href="images/app/icon.ico">

<script language="JavaScript" type="text/JavaScript">
function step2_go()

{
	this.location="install_2.php";
}
function step4_go()

{
	this.location="index.php";
}
</script>
</head>

<body leftmargin="20" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="maintext" scroll="auto">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
        
  <tr> 
          
    <td class="titletext0"><span class="maintext"><br>
      <img src="images/app/logo_login.jpg" width="128" height="44"><br>
      <br>
            </span>Installation: <span class="titletext0blue">Step 3</span></td>
  </tr>
      
</table>
<br>
<br>
<?php 
//receive posted data
$db_host=$_POST["db_host"];
$db_table_prefix=$_POST["db_table_prefix"];
$db_database=$_POST["db_database"];
$db_username=$_POST["db_username"];
$db_password=$_POST["db_password"];
$app_url=$_POST["app_url"];
$encoding=$_POST["encoding"];
$admin_fname=$_POST["admin_fname"];
$admin_username=$_POST["admin_username"];
$admin_pass=$_POST["admin_pass"];

//fix
$admin_pass_enc=md5($admin_pass);
$encoding=str_replace("'", "\"", $encoding);
$encoding=addslashes($encoding);
if (substr($app_url, -1)<>"/") {$app_url=$app_url."/";} else {$app_url=$app_url;}
if (substr($db_table_prefix, -1)<>"_") {$db_table_prefix=$db_table_prefix."_";} else {$db_table_prefix=$db_table_prefix;}

//checking database connectivity
if( !$conn = mysql_connect( $db_host, $db_username, $db_password ) ) {
  $error1=1;
} else {
  if( !mysql_select_db( $db_database, $conn ) ) {
    $error2=1;
  }
}


if ($error1==1 OR $error2==1) {

if ($error1==1) {echo '<span class="red">Error:</span> &nbsp;Could not connect to database! <img src="images/app/no_good.jpg" width="16" height="16" align="absmiddle"><br>';}
if ($error2==1) {echo '<span class="red">Error:</span> &nbsp;Could not select database! <img src="images/app/no_good.jpg" width="16" height="16" align="absmiddle"><br>';}
echo '
<br>
Copy this error message for your reference.<br>
Check your database host/name/username/password data, and go back and try again. <br>
Contact your hosting administrator for your database connection data.<br>

<br>
<br>

<span class="red">Error:</span> &nbsp;The application was NOT installed! <img src="images/app/no_good.jpg" width="16" height="16" align="absmiddle"><br>

<br>
<br>
<br>
<br>

<input name="back" type="button" class="formfields2" id="back" style="width: 75px; height: 30px;" onClick="step2_go();" value="&lt;- Back" align="absmiddle"><br>

<br>
<br>
<br>

</body>
</html>
';
die;

}

else {
// Set what will be written to file 
$to_write= "<?php\n";
$to_write.="//database connection\n";
$to_write.="\$db_username=\"".$db_username."\";";
$to_write.="\n";
$to_write.="\$db_password=\"".$db_password."\";\n";
$to_write.="\$db_database=\"".$db_database."\";\n";
$to_write.="\$db_host=\"".$db_host."\";\n";
$to_write.="\$db_table_prefix=\"".$db_table_prefix."\";\n\n";
$to_write.="\tmysql_connect(\$db_host,\$db_username,\$db_password);\n";
$to_write.="\t@mysql_select_db(\$db_database) or die( \"Unable to connect to database.\");\n\n";
$to_write.="?>";

// Set file for opening 
$fp = fopen("config_connection.php", 'w'); 
// Check if file is writable
if (!$fp)  { 
echo '<span class="red">Error:</span> &nbsp;Could not write the configuration file! <img src="images/app/no_good.jpg" width="16" height="16" align="absmiddle"><br>
<br>
Copy this error message for your reference.<br>
Make sure to allow 666 access to "config_connection.php" file.<br>

<br>
<br>

<span class="red">Error:</span> &nbsp;The application was NOT installed! <img src="images/app/no_good.jpg" width="16" height="16" align="absmiddle"><br>

<br>
<br>
<br>
<br>

<input name="back" type="button" class="formfields2" id="back" style="width: 75px; height: 30px;" onClick="step2_go();" value="&lt;- Back" align="absmiddle"><br>

<br>
<br>
<br>

</body>
</html>
';
die;
} 
// Finally, write to file 
fwrite($fp, $to_write); 
// Close the written file 
fclose($fp);

}



//create database structure

#
# Table structure for table 'db_table_prefix_articles_category'
#

$query = "
CREATE TABLE `".$db_table_prefix."articles_category` (
  `idCat` int(20) NOT NULL auto_increment,
  `category` varchar(255) default NULL,
  PRIMARY KEY  (`idCat`)
) TYPE=MyISAM
";

mysql_query($query);

#
# Table structure for table 'db_table_prefix_articles_items'
#

$query = "
CREATE TABLE `".$db_table_prefix."articles_items` (
  `idArtc` int(20) NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `marker` varchar(255) default NULL,
  `status` varchar(255) default NULL,
  `category` varchar(255) default NULL,
  `image` varchar(255) default NULL,
  `position` varchar(100) default NULL,
  `alt` varchar(255) default NULL,
  `source` varchar(255) default NULL,
  `location` varchar(255) default NULL,
  `keywords` varchar(255) default NULL,
  `expire` varchar(255) default NULL,
  `date` int(11) default NULL,
  `text` blob,
  `text2` blob,
  `priority` int(1) default '0',
  `flag` int(1) default NULL,
  `added_by` varchar(255) default NULL,
  `edited_by` varchar(255) default NULL,
  `comments_allow` int(1) default NULL,
  `comments_registered` int(1) default NULL,
  `comments_approve` int(1) default NULL,
  `visits` int(20) default NULL,
  `rate` int(20) default '0',
  PRIMARY KEY  (`idArtc`)
) TYPE=MyISAM
";

mysql_query($query);

#
# Table structure for table 'db_table_prefix_articles_marker'
#

$query = "CREATE TABLE `".$db_table_prefix."articles_marker` (
  `idMark` int(10) NOT NULL auto_increment,
  `marker` varchar(255) default NULL,
  `comment` varchar(255) default NULL,
  PRIMARY KEY  (`idMark`)
) TYPE=MyISAM
";

mysql_query($query);

#
# Table structure for table 'db_table_prefix_comments'
#

$query = "
CREATE TABLE `".$db_table_prefix."comments` (
  `idComm` int(10) NOT NULL auto_increment,
  `text` blob,
  `date` varchar(255) default NULL,
  `added_by` varchar(255) default NULL,
  `section` varchar(255) default NULL,
  `marker` varchar(255) default NULL,
  `CID` varchar(255) default NULL,
  `approval` varchar(255) default NULL,
  PRIMARY KEY  (`idComm`)
) TYPE=MyISAM
";

mysql_query($query);

#
# Table structure for table 'db_table_prefix_config'
#

$query = "CREATE TABLE `".$db_table_prefix."config` (
  `config_name` varchar(255) default NULL,
  `config_value` varchar(255) default NULL
) TYPE=MyISAM
";

mysql_query($query);

#
# Dumping data for table 'db_table_prefix_config'
#

$query = "INSERT INTO `".$db_table_prefix."config` (`config_name`, `config_value`) VALUES(\"articles_editor_add1\", \"1\")";
mysql_query($query);
$query = "INSERT INTO `".$db_table_prefix."config` (`config_name`, `config_value`) VALUES(\"articles_editor_add2\", \"1\")";
mysql_query($query);
$query = "INSERT INTO `".$db_table_prefix."config` (`config_name`, `config_value`) VALUES(\"articles_editor_edit1\", \"1\")";
mysql_query($query);
$query = "INSERT INTO `".$db_table_prefix."config` (`config_name`, `config_value`) VALUES(\"articles_editor_edit2\", \"1\")";
mysql_query($query);
$query = "INSERT INTO `".$db_table_prefix."config` (`config_name`, `config_value`) VALUES(\"encoding\", \"".$encoding."\")";
mysql_query($query);
$query = "INSERT INTO `".$db_table_prefix."config` (`config_name`, `config_value`) VALUES(\"version\", \"1.0\")";
mysql_query($query);
$query = "INSERT INTO `".$db_table_prefix."config` (`config_name`, `config_value`) VALUES(\"articles_editor_image_preview\", \"full\")";
mysql_query($query);
$query = "INSERT INTO `".$db_table_prefix."config` (`config_name`, `config_value`) VALUES(\"app_url\", \"".$app_url."\")";
mysql_query($query);
$query = "INSERT INTO `".$db_table_prefix."config` (`config_name`, `config_value`) VALUES(\"articles_image_filesize\", \"300\")";
mysql_query($query);
$query = "INSERT INTO `".$db_table_prefix."config` (`config_name`, `config_value`) VALUES(\"modules\", \"articles,\")";
mysql_query($query);
$query = "INSERT INTO `".$db_table_prefix."config` (`config_name`, `config_value`) VALUES(\"error_level\", \"E_ALL ^ E_NOTICE\")";
mysql_query($query);


#
# Table structure for table 'db_table_prefix_users'
#

$query = "CREATE TABLE `".$db_table_prefix."users` (
  `idUsers` int(10) NOT NULL auto_increment,
  `full_name` varchar(255) default NULL,
  `username` varchar(255) default NULL,
  `password` varchar(255) default NULL,
  `email` varchar(255) default NULL,  
  `comment` blob,
  `user_privileges` blob,
  `last_login` varchar(255) default NULL,
  `menu_state` blob,
  `lord` int(1) default NULL,
  PRIMARY KEY  (`idUsers`)
) TYPE=MyISAM
";

mysql_query($query);

#
# Dumping data for table 'db_table_prefix_users'
#

$date_time=time();
$query = "INSERT INTO `".$db_table_prefix."users` (`idUsers`, `full_name`, `username`, `password`, `email`, `comment`, `user_privileges`, `last_login`, `menu_state`, `lord`) VALUES(\"1\", \"".$admin_fname."\", \"".$admin_username."\", \"".$admin_pass_enc."\", \"\", \"\", \"ADMIN, \", \"".$date_time."\", \"articles=collapse, comments=collapse, visitors=collapse, users=collapse, help=collapse, admin=collapse,\", \"1\")";
mysql_query($query);

#
# Table structure for table 'db_table_prefix_visitors'
#

$query = "CREATE TABLE `".$db_table_prefix."visitors` (
  `idVis` int(10) NOT NULL auto_increment,
  `username` varchar(255) default NULL,
  `password` varchar(255) default NULL,
  `full_name` varchar(255) default NULL,
  `email` varchar(255) default NULL,
  `last_login` varchar(255) default NULL,
  PRIMARY KEY  (`idVis`)
) TYPE=MyISAM
";

mysql_query($query);

echo '<span class="maintext"><strong>Status:</strong> The application was installed successfully!</span> &nbsp;<img src="images/app/all_good.jpg" width="16" height="16" align="absbottom"><br>
<br>
&#8226; Make sure to delete the following files from application folder for security:<br>
<br>
<i>[files]:</i><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- http://www.yourdomain.com/100jancms/install.php<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- http://www.yourdomain.com/100jancms/install_2.php<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- http://www.yourdomain.com/100jancms/install_3.php<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- http://www.yourdomain.com/100jancms/install_eula.php<br>
<br>
&#8226; Make sure to apply read only attribut (CHMOD 644) to the following files for security:<br>
<br>
<i>[files]:</i><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- http://www.yourdomain.com/100jancms/config_connection.php<br>
<br>
&#8226; Now you can login in to application using master administrator username and password, that you specified during installation.<br>
&#8226; Your master administrator login data is (without quotes):<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Username: "'.$admin_username.'"<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Password: "'.$admin_pass.'"<br>
';

$install_ok=1;

echo '
<br>
<br>
<br>
<br>
<input name="next" type="submit" class="formfields2" id="next" style="width: 75px; height: 30px;" onClick="step4_go()" value="Next -&gt;" align="absmiddle">
<br>
<br>
';



?>

<br>
<br>
<br>

</body>
</html>
