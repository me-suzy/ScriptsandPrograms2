<?
require("../db.php");
require("include.php");
DBinfo();

mysql_connect("$DBHost","$DBUser","$DBPass");
mysql_select_db("$DBName");



$SUID=f_ip2dec($REMOTE_ADDR);
if (!session_id($SUID))
session_start();

$username=$_SESSION['uname'];
$password=$_SESSION['pass'];

$result=mysql_query("SELECT AdminId FROM mycmsadmin WHERE username='$username' and password='".sha1($password)."'");
$row=mysql_fetch_row($result);
$num_rows = mysql_num_rows($result);
$id=$row[0];



if ($_SESSION['signed_in']!='indeed' || $num_rows!=1 || $id!=1){
Header( "Location: index.php?action=2");

}else{


/* Step 0 - write to the DB */

$date=date("r");

mysql_query("INSERT INTO news VALUES ('','$refid','$author','$ntitle','$nsummary','$date','0','')");

/* Step 1 - create the 'news' dir if it does not exists */

if (!file_exists("../news")) 
	{mkdir("../news",0775);
	chmod("../news",0775);
	}

/* Step 2 - read out the newsid for further reference */

$result=mysql_query("SELECT NewsId FROM news WHERE RefId='$refid' AND Author='$author' AND Title='$ntitle' AND Summary='$nsummary' limit 0,1");
$row=mysql_fetch_row($result);
$newsid=$row[0];

/* Step 3 - create the directory for the item */

if (!file_exists("../news/$newsid"))
	{mkdir("../news/$newsid",0775);
	chmod("../news/$newsid",0775);

	mkdir("../news/$newsid/images",0775);
	chmod("../news/$newsid/images",0775);

	mkdir("../news/$newsid/files",0775);
	chmod("../news/$newsid/files",0775);

	}

/* Step 4 - redirect further */

Header("Location: newsFiles.php?newsid=$newsid&chnlid=$chnlid");

}

?>
