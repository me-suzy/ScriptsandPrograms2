<?

/* *****************************************************
CMS + service configuration script
****************************************************** */

if ($password!=$password1 || $cms_password!=$cms_password1 || $email!=$email1) {Header("Location: index.php?action=1");}

else {
/* Write the DB info to the db.php file */

$db_file="../db.php";
$db_file_cont="<? \n";
$db_file_cont.="/* Information about the db */\n\n";
$db_file_cont.=chr(36);
$db_file_cont.="DBHost=\"$host_name\";\n";
$db_file_cont.=chr(36);
$db_file_cont.="DBUser=\"$user_name\";\n";
$db_file_cont.=chr(36);
$db_file_cont.="DBPass=\"$password\";\n";
$db_file_cont.=chr(36);
$db_file_cont.="DBName=\"$db_name\";\n\n";
$db_file_cont.="/* Service configuration data */\n\n";
$db_file_cont.=chr(36);
$db_file_cont.="thisurl=\"$this_url\";\n";
$db_file_cont.=chr(36);
$db_file_cont.="title=\"\";\n";
$db_file_cont.=chr(36);
$db_file_cont.="keywords=\"\";\n";
$db_file_cont.=chr(36);
$db_file_cont.="description=\"\";\n\n";
$db_file_cont.="/* Basic style configuration */\n\n";
$db_file_cont.=chr(36);
$db_file_cont.="width=\"800px\";\n";
$db_file_cont.=chr(36);
$db_file_cont.="logoname=\"\";\n";
$db_file_cont.=chr(36);
$db_file_cont.="textlogo=\"Your logo\";\n";
$db_file_cont.=chr(36);
$db_file_cont.="model=\"hv\";\n";
$db_file_cont.=chr(36);
$db_file_cont.="bodyposition=\"center\";\n\n\n";

$db_file_cont.="function DBInfo () {global ".chr(36)."BDHost, ".chr(36)."DBUser, ".chr(36)."DBPass, ".chr(36)."DBName, ".chr(36)."title, ".chr(36)."width, ".chr(36)."keywords, ".chr(36)."description, ".chr(36)."logoname, ".chr(36)."model, ".chr(36)."bodyposition, ".chr(36)."thisurl, ".chr(36)."textlogo; } ?>";


$handle=fopen($db_file,"w");
fwrite($handle,$db_file_cont);
fclose($handle);


/* Rename the cms dir */

if ($cms_dir_name!="cms") rename("../cms/","../$cms_dir_name/");


/* Create the DB */

mysql_connect("$host_name","$user_name","$password") or die("Something went wrong:".mysql_error());
mysql_select_db("$db_name") or die("Something went wrong - connect:".mysql_error());


mysql_query("DROP TABLE IF EXISTS `mycmsadmin`");

mysql_query("CREATE TABLE `mycmsadmin` (
  `AdminId` int(1) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL default '',
  `password` varchar(255) NOT NULL default '',
  UNIQUE KEY `AdminId` (`AdminId`)
) TYPE=MyISAM AUTO_INCREMENT=2 ") or die("Something went wrong (addtable):".mysql_error());

mysql_query("INSERT INTO `mycmsadmin` VALUES ('1', 'admin', '".sha1($cms_password)."')") or die("Something went wrong (insertadmin):".mysql_error());



mysql_query("DROP TABLE IF EXISTS `pages`");

mysql_query("CREATE TABLE IF NOT EXISTS `pages` (
  `PageId` int(3) NOT NULL auto_increment,
  `RefId` int(2) NOT NULL default '0',
  `Name` varchar(255) NOT NULL default '',
  `Description` text NOT NULL,
  `Keywords` text NOT NULL,
  `Alias` varchar(100) NOT NULL default '',
  `Active` int(1) NOT NULL default '0',
  `Content` text NOT NULL,
  UNIQUE KEY `PageId` (`PageId`)
) TYPE=MyISAM AUTO_INCREMENT=1 ") or die("Something went wrong (addpages):".mysql_error());

mysql_query("DROP TABLE IF EXISTS `style`");

mysql_query("CREATE TABLE IF NOT EXISTS `style` (
  `StyleId` int(3) NOT NULL auto_increment,
  `Name` varchar(100) NOT NULL default '',
  `bgcol` varchar(30) NOT NULL default '',
  `bgim` varchar(100) NOT NULL default '',
  `bgrep` varchar(50) NOT NULL default '',
  `bgpos` varchar(15) NOT NULL default '',
  `font` varchar(150) NOT NULL default '',
  `fsize` int(10) NOT NULL default '0',
  `fcol` varchar(30) NOT NULL default '',
  `hsize` int(10) NOT NULL default '0',
  `hcol` varchar(30) NOT NULL default '',
  `hbgcol` varchar(30) NOT NULL default '',
  `hbgim` varchar(100) NOT NULL default '',
  `hbgrep` varchar(50) NOT NULL default '',
  `hbgpos` varchar(15) NOT NULL default '',
  `docbgcol` varchar(30) NOT NULL default '',
  `docbgim` varchar(100) NOT NULL default '',
  `docbgrep` varchar(50) NOT NULL default '',
  `docbgpos` varchar(15) NOT NULL default '',
  `docborstyle` varchar(20) NOT NULL default '',
  `docborw` varchar(10) NOT NULL default '',
  `docborcol` varchar(30) NOT NULL default '',
  `mmbgcol` varchar(30) NOT NULL default '',
  `mmborstyle` varchar(20) NOT NULL default '',
  `mmborw` varchar(10) NOT NULL default '',
  `mmborcol` varchar(30) NOT NULL default '',
  `mmfcol` varchar(30) NOT NULL default '',
  `smbgcol` varchar(30) NOT NULL default '',
  `smborstyle` varchar(20) NOT NULL default '',
  `smborw` varchar(10) NOT NULL default '',
  `smborcol` varchar(30) NOT NULL default '',
  `smfcol` varchar(30) NOT NULL default '',
  `ah` varchar(30) NOT NULL default '',
  `al` varchar(30) NOT NULL default '',
  `av` varchar(30) NOT NULL default '',
  `active` int(1) NOT NULL default '0',
  UNIQUE KEY `StyleId` (`StyleId`)
) TYPE=MyISAM AUTO_INCREMENT=2") or die("Something went wrong (addstyle):".mysql_error());


mysql_query("INSERT INTO style VALUES ('1', 'Netious', '#ffffff', '', 'repeat', '0% 0%', 'helvetica, arial, sans-serif', '95', '#000000', 220, '#000000', '#ffffff', '', 'repeat', '50% 50%', '#ffffff', '', 'no-repeat', '50% 50%', 'solid', '1px', '#bdbdbd', '#7e0000', 'solid', '1px', '#fc3f00', '#ffffff', '#003f00', 'dotted', '1px', '#7efc3f', '#fcfcfc', '#4d79d1', '#003fbd', '#002c84', '1')") or die("Something went wrong (insertstyle):".mysql_error());

if (isset($incl_contact))
{mysql_query("ALTER TABLE `mycmsadmin` ADD `adminMail` VARCHAR( 255 ) NOT NULL");

mysql_query("UPDATE mycmsadmin SET adminMail='$email' WHERE AdminId='1'");

}



$result=mysql_query("SELECT * FROM style WHERE active='1' limit 0,1");
$row=mysql_fetch_row($result);

$bgcol=$row[2];
$bgim=$row[3];
$bgrep=$row[4];
$bgpos=$row[5];
$font=$row[6];
$fsize=$row[7];
$fcol=$row[8];
$hsize=$row[9];
$hcol=$row[10];
$hbgcol=$row[11];
$hbgim=$row[12];
$hbgrep=$row[13];
$hbgpos=$row[14];
$docbgcol=$row[15];
$docbgim=$row[16];
$docbgrep=$row[17];
$docbgpos=$row[18];
$docborstyle=$row[19];
$docborw=$row[20];
$docborcol=$row[21];
$mmbgcol=$row[22];
$mmborstyle=$row[23];
$mmborw=$row[24];
$mmborcol=$row[25];
$mmfcol=$row[26];
$smbgcol=$row[27];
$smborstyle=$row[28];
$smborw=$row[29];
$smborcol=$row[30];
$smfcol=$row[31];
$ah=$row[32];
$al=$row[33];
$av=$row[34];

/* Define the style sheet elements */

$body="body {margin:5px; background-color:$bgcol; ";
if ($bgim!="") {$body.="background-image:url('$bgim'); background-repeat:$bgrep; background-position:$bgpos;";}
$body.="vertical-align:top}";

$table="table {border-collapse:collapse}";

$td="td {font-family: $font; font-size:$fsize%; color:$fcol; border-width:0px}";

$head="#head td {font-size:$hsize%; color:$hcol; background-color: $hbgcol;";
if ($hbgim!="") $head.="background-image:url('$hbgim'); background-repeat:$hbgrep; background-position: $hbgpos;";
$head.="}";

$head.="\n #head a {color:$hcol;}";
$head.="\n #head a:hover {color:$hcol; text-decoration:none}";
$head.="\n #head a:link {color:$hcol; text-decoration:none}";
$head.="\n #head a:visited {color:$hcol; text-decoration:none}";

$doctable=".indocument {border: $docborstyle $docborw $docborcol; background-color:$docbgcol;";
if ($docbgim!="") $doctable.="background-image:url('$docbgim'); background-repeat:$docbgrep; background-position: $docbgpos;";
$doctable.="}";

$doctd=".document {border: $docborstyle $docborw $docborcol}";



$mmenutd="#mainmenu td {background-color: $mmbgcol; border: $mmborstyle $mmborw $mmborcol}";

$mmenua="#mainmenu a {color: $mmfcol}";

$mmenuact="#mainmenu .active {font-weight:bold}";

$smenutd="#sidemenu td {background-color: $smbgcol; border: $smborstyle $smborw $smborcol}";

$smenua="#sidemenu a {color: $smfcol}";
$smenuact="#sidemenu .active {font-weight:bold}";

$a="a {font-family:$font; font-size:100%; text-decoration:none}";
$ahe="a:hover {color:$ah; text-decoration:underline}";
$ale="a:link {color:$al}";
$ave="a:visited {color:$av}";


$thesheet="$body \n $table \n $td \n $head \n $doctable \n $doctd \n $mmenutd \n $mmenua \n $mmenuact \n $smenutd \n $smenua \n $smenuact \n $a \n $ahe \n $ale \n $ave";

$handle=fopen("../style.css","w");
fwrite($handle,$thesheet);
fclose($handle);


/* Create a new styleedit file - for edition in the Content Management */

$body="body {margin:5px; background-color:$docbgcol; ";
if ($docbgim!="") {$docbgim=str_replace("./","../",$docbgim); 
	$body.="background-image:url('$docbgim'); background-repeat:$docbgrep; background-position:$docbgpos;";}
$body.="vertical-align:top; font-family: $font; font-size: $fsize%}";

$table="table {border-collapse: collapse}";

$a="a {font-family:$font; font-size:100%; text-decoration:none; color:$al}";

$theeditsheet="$body \n $table \n $a \n $ahe \n $ale \n $ave";



$handle=fopen("../$cms_dir_name/styleedit.css","w");
fwrite($handle,$theeditsheet);
fclose($handle);



/* Communicate the success */


	$optional_subject="Successful configuration of your service";

 	$header="MIME-Version: 1.0\r\n";
	$header.="Content-type: text/html; charset=utf-8\r\n";


	$body_email = "<html>
	<body bgColor=white>
	<table width=\"90%\" style=\"border-collapse: collapse; font-family: Arial\" border=1 cellspacing=5 cellpadding=5>
<tr>
<td bgColor=#eeeeee>
This e-mail has been automatically composed by the configuration software.<br> This means that your service can be now run and adjusted to your own needs.<br /><br />

<p>
The path to the CMS:<br />
$this_url/$cms_dir_name/ <br />
Username: <b>admin</b>
Password: as given <br /><br />
<b>Remember to remove the \"config\" directory!</b>
</p>

</td>
</tr>
</table>
</body>
</html>
";
        $results = mail ($email, $optional_subject, $body_email, $header);


if (isset($regme)) mail ("webmaster@netious.com","New user of the netious-soft!","URL: $this_url, \n Email:$email", "FROM:$email");


echo "
<html>
<title>Configuration script</title>
</head>
<body>
<center>
<h2>The service has been properly installed.</h2><br />
<p>
The path to the CMS:<br />
$this_url/$cms_dir_name <br />
Username: <b>admin</b>
Password: as given <br /><br />
Remember to remove the \"config\" directory!
</p>

</center>
</body>
</html>
";
}

?>