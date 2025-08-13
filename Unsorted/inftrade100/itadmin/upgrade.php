<?php
$newversion = "1.00";

require("../it/dbsettings.php");

printheader();

print <<<END
<table cellspacing="1" cellpadding="7" bgcolor="#000040" width="530">
<tr>
<td bgcolor="#000080" align="center">
<p class="hh">infTrade v$newversion Upgrade</p>
</td>
</tr>
<tr>
<td bgcolor="#FFFFFF" align="center">
<br><br><br>
END;

$link = mysql_connect($db_host, $db_user, $db_pw)
	or upgradefailed("Could not connect : " . mysql_error());
mysql_select_db($db_database) or upgradefailed(mysql_error());

$query = "SELECT itversion FROM settings";
$result = mysql_query($query) or upgradefailed(mysql_error());

$line = mysql_fetch_array($result, MYSQL_NUM);

if( $line[0] == $newversion ) {
	upgradefailed("You are already running the latest version.");
	}

if( $line[0] == '0.90' ) { 
	upgrade090(); 
	}

$query = "UPDATE settings SET itversion='$newversion'";
$result = mysql_query($query) or upgradefailed(mysql_error());

mysql_close($link);
print <<<END
UPGRADE COMPLETED SUCCESSFULLY
<br><br><br><br>
<a href="index.php">Click Here To Login</a>
<br><br><br><br>
</table>
END;
printfoot();

exit;

function upgrade090() {
$result = mysql_query("ALTER TABLE settings ADD rdnocookie TINYINT UNSIGNED NOT NULL") or upgradefailed(mysql_error());
$result = mysql_query("UPDATE settings SET rdnocookie='1'") or upgradefailed(mysql_error());
}

function upgradefailed($msg) {
print <<<END
<font color="Red">$msg</font>
<br><br><br><br>
</table>
END;
printfoot();
exit;
}

function printheader() {
print <<<END
<html>
<head>
<title>Upgrade</title>
<style>
body {font-family: Verdana, Arial, Helvetica, sans-serif; font-size : x-small; color : #000000; font-weight : normal; text-decoration : none;}
td {font-family: Verdana, Arial, Helvetica, sans-serif; font-size : x-small; color : #000000; font-weight : normal; text-decoration : none;}
td.small {font-family: Verdana, Arial, Helvetica, sans-serif; font-size : xx-small; color : #000000; font-weight : normal; text-decoration : none;}
a:link { text-decoration : none;}
a:visited { text-decoration : none;}
a:hover { text-decoration : underline;}
.but {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: x-small; color : #000000; font-weight: normal; background-color: #E8E8E8; border: 1px solid #000000; height: 21; cursor: hand; }
.butf {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: x-small; color : #000000; font-weight: normal; background-color: #E8E8E8; border: 1px solid #000000; height: 21; cursor: hand; width: 130; }
.inp {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: x-small; color : #000000; font-weight: normal; background-color: #FFFFFF; border: 1px solid #000000; }
.radio1 { color : #FFFFFF; background-color: #000040; cursor : hand; height:14}
.toplink {font-family: Verdana, Arial, Helvetica, sans-serif; font-size : x-small; color : #FFFFFF; font-weight : bold; text-decoration : underline;}
.hh {font-family: Verdana, Arial, Helvetica, sans-serif; font-size : small; color : #FFFFFF; font-weight : bold; text-decoration : none;}
.men1 {font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif; font-size : x-small; color : #000000; font-weight : normal; text-decoration : none;}
</style>
</head>
<body bgcolor="#FFFFFF" text="#000000" link="#0000FF" vlink="#0000FF" alink="#0000FF">
<div align="center">
END;
}

function printfoot() {
print <<<END
</div>
</body>
</html>
END;
}
?>