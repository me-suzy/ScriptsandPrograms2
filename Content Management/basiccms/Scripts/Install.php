<?php
session_start();
include("../Includes/Database.php");


	if (!isset($_GET['action']))
	{
		$straction="Active";
	}
	else
	{
		$straction=QuerySafeString($_GET["action"]);
	}


$strServer =QuerySafeString($_REQUEST["txtServer"]);
$strAdmin =QuerySafeString($_REQUEST["txtAdmin"]);
$strAdminPassword =QuerySafeString($_REQUEST["txtAdminPassword"]);
$strNEWSDB =QuerySafeString($_REQUEST["txtNEWSDB"]);
$strNEWSDBUser =$strAdmin;//QuerySafeString($_REQUEST["txtNEWSDBUser"]);
$strNEWSDBPassword =$strAdminPassword;//QuerySafeString($_REQUEST["txtNEWSDBPassword"]);
//$strTitle =QuerySafeString($_REQUEST["txtTitle"]);
$strEMail ="javaxtr@hotmail.com";//QuerySafeString($_REQUEST["txtEMail"]);
chdir('..');

$strImageUploadPathRelative="../../Images/";
$strTemp=getcwd();
$strImageUploadPath=$strTemp."\Images";
//print $strImageUploadPath;
//if ($straction == "INSTALL") 
{

mysql_connect($strServer,$strAdmin,$strAdminPassword);
$strsql = "create database ".$strNEWSDB. ";";
mysql_query($strsql);
$strsql = "GRANT ALL PRIVILEGES ON ".$strNEWSDB.".* to ".$strNEWSDBUser." IDENTIFIED BY '".$strNEWSDBPassword."' with grant option;";
mysql_query($strsql);

@mysql_select_db($strNEWSDB) or die( "Unable to select database");


	$strsql = "CREATE TABLE pages_t_users (userid VARCHAR(20) NOT NULL , password VARCHAR(20), username VARCHAR(50), email VARCHAR(50),active CHAR(1),PRIMARY KEY (userid));";
	mysql_query($strsql);
	$strsql = "CREATE TABLE pages_t_details (id MEDIUMINT  UNSIGNED NOT NULL AUTO_INCREMENT,title VARCHAR(255), description MEDIUMTEXT , startpage CHAR(1), PRIMARY KEY (id));";
	mysql_query($strsql);
	$strsql = "INSERT INTO pages_t_users(userid,username,password,email,active) VALUES('ADMIN','admin','admin','admin','Y');";
	mysql_query($strsql);

$strSave="<?php \n define(\"HOSTM\", \"$strServer\");\n define(\"dbUser\", \"$strNEWSDBUser\");\n define(\"dbPassword\", \"$strNEWSDBPassword\");\n";
$strSave.="define(\"dbToUse\", \"$strNEWSDB\");\n define(\"FromEMail\", \"$strEMail\");\n";
//$strSave.="define(\"PortalTitle\",\"$strTitle\");\n";
$strSave.="define(\"ImageUploadPath\",\"".BackSlash($strImageUploadPath)."\\\");\n";
$strSave.="define(\"ImageUploadPathRelative\",\"".$strImageUploadPathRelative."\");\n";
$strSave.="?>";

SaveFile($strTemp."\Includes\PortalConection.php",$strSave,"E");

}
print "<HTML><HEAD><TITLE>INSTALL Completed";
print "</TITLE>";
include ("Includes/Styles.php");
?>

<SCRIPT LANGUAGE=javascript>
<!--

//-->
</SCRIPT>
</HEAD>
<BODY>

<H3> Installation is complete, <BR> Admin User ID is "ADMIN" and password is "admin". <BR>Please remove "Scripts" folder from the web server.
<BR> You should change the settings of Image upload related parameter at "Includes/PortalConnection.php"
<BR> <A HREF="../Logon.php"> Click Here to Login</A>
</H3>

<P>&nbsp;</P>

</BODY>
</HTML>