<?
//////////////////////////////////////////////////////////////////////////////
// Program Name         : Domain Seller Pro                                 //
// Release Version      : 1.5.0                                             //
// Program Author       : Ronald James                                      //
// Install Script Author: CyKuH [WTN]                                       //
// Supplied by          : Scoons [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                       //
//////////////////////////////////////////////////////////////////////////////
// COPYRIGHT NOTICE                                                         //
// (c) 2002 Ronald James    All Rights Reserved.                            //
// Distributed under the licencing agreement located in wtn_release.nfo     //
//////////////////////////////////////////////////////////////////////////////
?>
<html>
<!--
<?php
// CHECK ENV
// determine if php is running
if(1==0)
{
	echo "-->You are not running PHP - Please contact your system administrator.<!--";
}else{
	echo "-->";
}
// /* <? */ Securety info
$programm =   'Domain Seller Pro';
$version	= '1.50';
$thisscript	= 'install.php';
if($HTTP_GET_VARS['step'])
{
	$step 	= $HTTP_GET_VARS['step'];
}else{
	$step 	= $HTTP_POST_VARS['step'];
}
$userip		= $HTTP_ENV_VARS['REMOTE_ADDR'];
$userphpself	= $HTTP_SERVER_VARS['PHP_SELF'];
$userpathtran	= $HTTP_SERVER_VARS['PATH_TRANSLATED'];
$usersafemode	= get_cfg_var("safe_mode");

//include('./define.inc.php');

error_reporting(E_ERROR | E_WARNING | E_PARSE);

if (function_exists("set_time_limit")==1 && get_cfg_var("safe_mode")==0)
{
	@set_time_limit(1200);
}

if (get_cfg_var("safe_mode") != 0)
{
	$installnote ="<p><b>Note:</b> In your server PHP configuration the <b>Safe Mode is active</b>, You will need edit your config.inc.php file to allow safe mode uploading!</p>";
	$safemode ="1";
}
$onvservers	= "0"; // set this to 1 if you're on Vservers and get disconnected after running an ALTER TABLE command

function dodb_queries()
{
	global $DB_site,$query,$explain,$onvservers;
	while (list($key,$val)=each($query))
	{
		echo "<p>$explain[$key]</p>\n";
		echo "<!-- ".htmlspecialchars($val)." -->\n\n";
		flush();
		if ($onvservers==1 and substr($val, 0, 5)=="ALTER")
		{
			$DB_site->reporterror=0;
		}
		$DB_site->query($val);
		if ($onvservers==1 and substr($val, 0, 5)=="ALTER")
		{
			$DB_site->connect();
			$DB_site->reporterror=1;
		}
	}
	unset ($query);
	unset ($explain);
}
function gotonext($extra="") {
	global $step,$thisscript;
	
	$nextstep = $step+1;
	echo "<div align=\"center\"><p><a href=\"$thisscript?step=$nextstep\"><b>Click here to continue &raquo;&raquo;</b></a> $extra</p>\n</div>";
echo <<<EOF
<p align="center"><!--CyKuH [WTN]-->&copy WTN Team `2000 - `2002</p>
EOF;
}
function stripslashesarray(&$arr) {
	while (list($key,$val) = each($arr))
	{
		if ((strtoupper($key)!=$key || "".intval($key)=="$key") && $key!="argc" && $key!="argv")
		{
			if(is_string($val))
			{
				$arr[$key] = stripslashes($val);
			}
			if(is_array($val))
			{
				$arr[$key] = stripslashesarray($val);
			}
		}
	}
	return $arr;
}
if (get_magic_quotes_gpc() and is_array($GLOBALS))
{
	$GLOBALS = stripslashesarray($GLOBALS);
}
set_magic_quotes_runtime(0);

if($HTTP_GET_VARS['see_phpinfo']==1)
{
	phpinfo();
	exit;
}
?>
<HTML>
	<HEAD>
	<title><? echo $programm ?> Install Script</title>
<STYLE TYPE="text/css">
	<!--
	A { text-decoration: none; }
	A:hover { text-decoration: underline; }
	H1 { font-family: arial,helvetica,sans-serif; font-size: 18pt; font-weight: bold;}
	H2 { font-family: arial,helvetica,sans-serif; font-size: 14pt; font-weight: bold;}
	BODY,TD,FORM,INPUT,TEXTAREA { font-family: arial,helvetica,sans-serif; font-size: 10pt; }
	TH { font-family: arial,helvetica,sans-serif; font-size: 11pt; font-weight: bold; }
	.textpre {font-family : "Courier New", Courier, monospace; font-size : 1px; font-weight : bold;}
	//-->
</STYLE>
	</HEAD>
<BODY onLoad="window.defaultStatus=' '" leftmargin="10" topmargin="10" marginwidth="10" marginheight="10">
<table width="600" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="Black">
	<tr valign="middle" bgcolor="#9999CC">
		<td align="left">
		<!--CyKuH [WTN]-->
		<H1><? echo $programm ?> Script</H1>
		<b>Installation: version <?php echo $version; ?></b></td>
	</TR>
</TABLE>
<BR>

<table width="600" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="Black">
<TR VALIGN="top" BGCOLOR="#CCCCCC"><TD ALIGN="left">
Note: Please be patient as some parts of this may take quite some time.<br>
Nullified by <b>CyKuH</b>
</TD></TR>
</TABLE><BR>

<?php
if ($step == "")
{
?>
<table width="600" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="Black">
<tr valign="top" bgcolor="#CCCCCC"><TD ALIGN="left">
<p>Welcome to <? echo $programm ?> Installation Script.</p>
<p>Running this script will do an install of <? echo $programm ?> database strucuctury and default data onto your server.</p>
</TD></TR>
</TABLE><BR>

	<table width="600" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#000000">
	<tr valign="baseline" bgcolor="#CCCCCC"><th bgcolor="#9999CC" colspan="3" align="center"><b> Database Check </b></th></tr>
	<tr valign="baseline" bgcolor="#CCCCCC" align="center">
		<td bgcolor="#CCCCFF"><b><? echo $programm ?> version <?php echo $version; ?></b></td>
		<td><b>System Requirements:</b></td>
		<td><b>Your System:</b></td>
	</tr>
	<tr valign="baseline" bgcolor="#CCCCCC">
		<td bgcolor="#CCCCFF"><b>PHP version</b></td>
		<td>PHP 4 >= 4.0.4</td>
		<td>Your PHP version: <b><?php echo phpversion();?></b></td>
	</tr>
	<tr valign="baseline" bgcolor="#CCCCCC">
		<td bgcolor="#CCCCFF"><b>MySQL version</b></td>
		<td>MySQL version 3.22 or higher</td>
		<td>See your MySQL version (<a href="install.php?see_phpinfo=1#module_mysql" target="_blank">check ver.</a>)</td>
	</tr>
	</table>
<?php
	$step = 1;
	gotonext();
}
if ($step == 2)
{
?>
<FORM action="?step=3" method=post>
<center>
<TABLE cellSpacing=0 cellPadding=3 border=0>
<TD>MySQL hostname:</TD>
<TD><INPUT maxLength=40 size=30 name="idbhost" value="localhost"></TD></TR>
<TR>
<TD>MySQL user:</TD>
<TD><INPUT  maxLength=40 size=30 name="idbuname" value="admin"></TD></TR>
<TR>
<TD>MySQL password:</TD>
<TD><INPUT  maxLength=40 size=30 name="idbpass" value="admin"></TD></TR>
<TR>
<TD>MySQL db name:</TD>
<TD><INPUT  maxLength=40 size=30 name="idbname" value="counter"></TD></TR>
<!--<TR>
<TD>MySQL table perfix:</TD>
<TD><INPUT  maxLength=40 size=30 name="ipre" value="pm_"></TD></TR>-->

<TR>
<TD>&nbsp;</TD>
<TD><INPUT type=submit value=Óñòàíîâèòü></FORM></TD></TR></TBODY></TABLE>
<?
echo <<<EOF
<p align="center"><!--CyKuH [WTN]-->&copy WTN Team `2000 - `2002</p>
EOF;
	exit;
}
if ($step >= 3)
{

}
if ($step == 3)
{
        $fp = fopen('tmp.php', 'w');
        $config = "\$dbhost = \"$idbhost\";\n";
        $config .= "\$dbuname = \"$idbuname\";\n";
        $config .= "\$dbpass = \"$idbpass\";\n";
        $config .= "\$dbname  = \"$idbname\";\n";
        fwrite($fp, "<?\n".$config."?>");
        fclose($fp);
echo "<center>";
include "tmp.php";
mysql_connect($dbhost, $dbuname, $dbpass) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());
echo <<<EOF
Setting install config - ok.<br>
Connect to server - ok.<br>
Connect to database - ok.
EOF;
	gotonext();
	exit;
}

if($step>=4)
{
}

if($step==4)
{
echo "<center>";
include "tmp.php";
mysql_connect($dbhost, $dbuname, $dbpass) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());
////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////
// Structura

mysql_query("CREATE TABLE dsp_buyers (  ID int(11) NOT NULL auto_increment,  email varchar(200) default NULL,  firstname varchar(30) default NULL,  lastname varchar(30) default NULL,  organization varchar(100) default NULL,  address varchar(100) default NULL,  city varchar(50) default NULL,  state varchar(50) default NULL,  postalcode varchar(10) default NULL,  country varchar(100) default NULL,  phone varchar(100) NOT NULL default '',  fax varchar(100) NOT NULL default '',  password varchar(20) default NULL,  data timestamp(14) NOT NULL,  PRIMARY KEY  (ID)) TYPE=MyISAM");
echo "Creating table byers  -  ok<br>";
mysql_query("CREATE TABLE dsp_cats (  ID int(11) NOT NULL auto_increment,  category varchar(100) default NULL,  PRIMARY KEY  (ID)) TYPE=MyISAM");
echo "Creating table cats  -  ok<br>";
mysql_query("CREATE TABLE dsp_counteroffers (  ID int(11) NOT NULL auto_increment,  offer int(11) NOT NULL default '0',  domain int(11) default NULL,  price double(11,2) default NULL,  email varchar(200) default NULL,  data timestamp(14) NOT NULL,  PRIMARY KEY  (ID)) TYPE=MyISAM");
echo "Creating table counteroffers  -  ok<br>";
mysql_query("CREATE TABLE dsp_domains (  ID int(11) NOT NULL auto_increment,  category varchar(200) NOT NULL default ' 1',  name varchar(200) default NULL,  description varchar(255) default NULL,  keywords varchar(255) default NULL,  logourl varchar(200) default NULL,  minimum double(11,2) default NULL,  buynow double(11,2) default NULL,  status int(4) default NULL,  listed timestamp(14) NOT NULL,  modified timestamp(14) NOT NULL,  PRIMARY KEY  (ID)) TYPE=MyISAM");
echo "Creating table domains  -  ok<br>";
mysql_query("CREATE TABLE dsp_offers (  ID int(11) NOT NULL auto_increment,  domain int(11) default NULL,  price double(11,2) default NULL,  email varchar(200) default NULL,  data timestamp(14) NOT NULL,  status tinyint(4) default NULL,  PRIMARY KEY  (ID)) TYPE=MyISAM");
echo "Creating table offers  -  ok<br>";
mysql_query("CREATE TABLE dsp_options (  label varchar(30) default NULL,  value text,  UNIQUE KEY label (label)) TYPE=MyISAM");
echo "Creating table options  -  ok<br>";
mysql_query("CREATE TABLE dsp_purchases (  ID int(11) NOT NULL auto_increment,  domain int(11) default NULL,  price double(11,2) default NULL,  user int(11) default NULL,  data timestamp(14) NOT NULL,  status int(11) NOT NULL default '1',  PRIMARY KEY  (ID)) TYPE=MyISAM");
echo "Creating  table purchases  -  ok<br>";

mysql_query("INSERT INTO dsp_cats VALUES (1,'Unclassified')");
mysql_query("INSERT INTO dsp_cats VALUES (2,'Sports')");
mysql_query("INSERT INTO dsp_cats VALUES (3,'Entertainment')");
mysql_query("INSERT INTO dsp_cats VALUES (4,'Shopping')");
mysql_query("INSERT INTO dsp_cats VALUES (5,'Science')");
mysql_query("INSERT INTO dsp_cats VALUES (6,'Computers')");
mysql_query("INSERT INTO dsp_cats VALUES (7,'Internet')");
mysql_query("INSERT INTO dsp_cats VALUES (8,'Resources')");
mysql_query("INSERT INTO dsp_cats VALUES (9,'Other')");
mysql_query("INSERT INTO dsp_cats VALUES (10,'Technology')");
mysql_query("INSERT INTO dsp_cats VALUES (11,'Portals')");
mysql_query("INSERT INTO dsp_cats VALUES (13,'Business')");
mysql_query("INSERT INTO dsp_cats VALUES (14,'Web Hosting')");
$novar=1;
function packx($x){ return $x==""?$x:md5($x*31+11); }
srand((double)microtime()*1000000);
mysql_query("INSERT INTO dsp_options VALUES ('adminpassword','".packx('admin')."')");
mysql_query("INSERT INTO dsp_options VALUES ('adminemail','needtoset@null')");


echo "<center><font color=ffffff>INSERT Data Complite<br>";

	gotonext();
	exit;
}

if ($step == 5)
{
	unlink('install.php');
	unlink('tmp.php');
	echo "<p><h2><b>You has completed the installation of ".$programm." successfully.</b></h2>"; 
	echo "<blockquote>	<p><b>Automatic delete file: install.php </b> for security reasons."; 
	echo "<blockquote>	<p><b>Please Edit config_int.php </b>"; 
	echo "<p>Go to control panel and <b>edit your default password access</b> code. Control Painel > MENU USERS</p></blockquote>";
	echo "	<p><a href=\"./\" target=\"blank\">The default content of $programm can be found here &raquo;&raquo;</a></p>";
	echo "	<p><a href=\"./admin.php\" target=\"blank\">Access your admin page here &raquo;&raquo;</a> (<b>Default pass: admin</b>)</p>";
	echo " $installnote";
	echo "	</body>";
	echo "	</html>";
}
?>