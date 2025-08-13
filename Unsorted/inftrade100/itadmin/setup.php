<?php
if ( $_POST["setupscript"] ) { setupscript(); }

printheader();
print <<<END
<table cellspacing="1" cellpadding="7" bgcolor="#000040" width="530">
<tr>
<td bgcolor="#000080" align="center">
<p class="hh">infTrade v1.00 Setup</p>
</td>
</tr>
<tr>
<td bgcolor="#FFFFFF" align="center">
<br><br><br>
Important! Only run this script once.<br>
After successfull installation you can delete setup.php
<br><br>
<table><form action="setup.php" method="post">
<tr><td>MySQL Host</td><td><input type="text" name="db_host" size="35" maxlength="50" value="localhost" class="inp"></td></tr>
<tr><td>MySQL Username</td><td><input type="text" name="db_user" size="35" maxlength="50"  class="inp"></td></tr>
<tr><td>MySQL Password</td><td><input type="text" name="db_pw" size="35" maxlength="50"  class="inp"></td></tr>
<tr><td>MySQL Database</td><td><input type="text" name="db_database" size="35" maxlength="50"  class="inp"></td></tr>
<tr><td>Admin Login</td><td><input type="text" name="admin_user" size="35" maxlength="50"  class="inp"></td></tr>
<tr><td>Admin Password</td><td><input type="text" name="admin_pw" size="35" maxlength="50"  class="inp"></td></tr>
<tr><td colspan="2" align="center"><br><input type="submit" name="setupscript" value="Setup Script" class="but"></td></tr></form>
</td>
</tr>
</table>
<br><br><br><br>
</table>
END;
printfoot();
exit;

function setupscript() {

$db_host = $_POST['db_host'];
$db_user = $_POST['db_user'];
$db_pw = $_POST['db_pw'];
$db_database = $_POST['db_database'];
$htuser = $_POST['admin_user'];
$htpass = $_POST['admin_pw'];

$scriptpath = $_SERVER["SCRIPT_FILENAME"];
$scriptpath = str_replace("setup.php","",$scriptpath);

$htp = $scriptpath.".htpasswd";

printheader();
print <<<END
<table cellspacing="1" cellpadding="7" bgcolor="#000040" width="530">
<tr>
<td bgcolor="#000080" align="center">
<p class="hh">infTrade v1.00 Setup</p>
</td>
</tr>
<tr>
<td bgcolor="#FFFFFF" align="center">
<br><br><br>
END;

if( $db_host=="" || $db_user=="" || $db_pw=="" || $db_database=="" || $htuser=="" || $htpass=="") {
	setupfailed("You must fill out all fields");
	}

$htpassc = crypt($htpass);

$fh = fopen(".htpasswd","w") or setupfailed("Writing itadmin/.htpasswd failed.<br>Chmod directory <strong>itadmin</strong> 777");
fputs($fh, "$htuser:$htpassc\n");
fclose($fh);

$fh = fopen("../it/dbsettings.php","w") or setupfailed("Writing it/dbsettings.php failed.<br>Chmod file <strong>dbsettings.php</strong> 777");
fputs($fh, "<?php\n\$db_host=\"$db_host\";\n\$db_user=\"$db_user\";\n\$db_pw=\"$db_pw\";\n\$db_database=\"$db_database\";\n?>\n");
fclose($fh);

$link = mysql_connect($db_host, $db_user, $db_pw) or setupfailed("MYSQL Error - Could not connect: " . mysql_error());
mysql_select_db($db_database) or setupfailed(mysql_error());

$query = <<<EOD
CREATE TABLE sites (siteid INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,sitedomain CHAR(50) NOT NULL,siteurl CHAR(100) NOT NULL,sitename CHAR(50) NOT NULL,sitedesc CHAR(100) NOT NULL,wmemail CHAR(50) NOT NULL,wmicq CHAR(15) NOT NULL,pratio TINYINT NOT NULL,ratio SMALLINT UNSIGNED NOT NULL,status TINYINT NOT NULL,
in0 SMALLINT UNSIGNED NOT NULL,out0 SMALLINT UNSIGNED NOT NULL,clk0 SMALLINT UNSIGNED NOT NULL,force0 SMALLINT UNSIGNED NOT NULL,in1 SMALLINT UNSIGNED NOT NULL,out1 SMALLINT UNSIGNED NOT NULL,clk1 SMALLINT UNSIGNED NOT NULL,force1 SMALLINT UNSIGNED NOT NULL,in2 SMALLINT UNSIGNED NOT NULL,out2 SMALLINT UNSIGNED NOT NULL,clk2 SMALLINT UNSIGNED NOT NULL,force2 SMALLINT UNSIGNED NOT NULL,in3 SMALLINT UNSIGNED NOT NULL,out3 SMALLINT UNSIGNED NOT NULL,clk3 SMALLINT UNSIGNED NOT NULL,force3 SMALLINT UNSIGNED NOT NULL,
in4 SMALLINT UNSIGNED NOT NULL,out4 SMALLINT UNSIGNED NOT NULL,clk4 SMALLINT UNSIGNED NOT NULL,force4 SMALLINT UNSIGNED NOT NULL,in5 SMALLINT UNSIGNED NOT NULL,out5 SMALLINT UNSIGNED NOT NULL,clk5 SMALLINT UNSIGNED NOT NULL,force5 SMALLINT UNSIGNED NOT NULL,in6 SMALLINT UNSIGNED NOT NULL,out6 SMALLINT UNSIGNED NOT NULL,clk6 SMALLINT UNSIGNED NOT NULL,force6 SMALLINT UNSIGNED NOT NULL,in7 SMALLINT UNSIGNED NOT NULL,out7 SMALLINT UNSIGNED NOT NULL,clk7 SMALLINT UNSIGNED NOT NULL,force7 SMALLINT UNSIGNED NOT NULL,
in8 SMALLINT UNSIGNED NOT NULL,out8 SMALLINT UNSIGNED NOT NULL,clk8 SMALLINT UNSIGNED NOT NULL,force8 SMALLINT UNSIGNED NOT NULL,in9 SMALLINT UNSIGNED NOT NULL,out9 SMALLINT UNSIGNED NOT NULL,clk9 SMALLINT UNSIGNED NOT NULL,force9 SMALLINT UNSIGNED NOT NULL,in10 SMALLINT UNSIGNED NOT NULL,out10 SMALLINT UNSIGNED NOT NULL,clk10 SMALLINT UNSIGNED NOT NULL,force10 SMALLINT UNSIGNED NOT NULL,in11 SMALLINT UNSIGNED NOT NULL,out11 SMALLINT UNSIGNED NOT NULL,clk11 SMALLINT UNSIGNED NOT NULL,force11 SMALLINT UNSIGNED NOT NULL,
in12 SMALLINT UNSIGNED NOT NULL,out12 SMALLINT UNSIGNED NOT NULL,clk12 SMALLINT UNSIGNED NOT NULL,force12 SMALLINT UNSIGNED NOT NULL,in13 SMALLINT UNSIGNED NOT NULL,out13 SMALLINT UNSIGNED NOT NULL,clk13 SMALLINT UNSIGNED NOT NULL,force13 SMALLINT UNSIGNED NOT NULL,in14 SMALLINT UNSIGNED NOT NULL,out14 SMALLINT UNSIGNED NOT NULL,clk14 SMALLINT UNSIGNED NOT NULL,force14 SMALLINT UNSIGNED NOT NULL,in15 SMALLINT UNSIGNED NOT NULL,out15 SMALLINT UNSIGNED NOT NULL,clk15 SMALLINT UNSIGNED NOT NULL,force15 SMALLINT UNSIGNED NOT NULL,
in16 SMALLINT UNSIGNED NOT NULL,out16 SMALLINT UNSIGNED NOT NULL,clk16 SMALLINT UNSIGNED NOT NULL,force16 SMALLINT UNSIGNED NOT NULL,in17 SMALLINT UNSIGNED NOT NULL,out17 SMALLINT UNSIGNED NOT NULL,clk17 SMALLINT UNSIGNED NOT NULL,force17 SMALLINT UNSIGNED NOT NULL,in18 SMALLINT UNSIGNED NOT NULL,out18 SMALLINT UNSIGNED NOT NULL,clk18 SMALLINT UNSIGNED NOT NULL,force18 SMALLINT UNSIGNED NOT NULL,in19 SMALLINT UNSIGNED NOT NULL,out19 SMALLINT UNSIGNED NOT NULL,clk19 SMALLINT UNSIGNED NOT NULL,force19 SMALLINT UNSIGNED NOT NULL,
in20 SMALLINT UNSIGNED NOT NULL,out20 SMALLINT UNSIGNED NOT NULL,clk20 SMALLINT UNSIGNED NOT NULL,force20 SMALLINT UNSIGNED NOT NULL,in21 SMALLINT UNSIGNED NOT NULL,out21 SMALLINT UNSIGNED NOT NULL,clk21 SMALLINT UNSIGNED NOT NULL,force21 SMALLINT UNSIGNED NOT NULL,in22 SMALLINT UNSIGNED NOT NULL,out22 SMALLINT UNSIGNED NOT NULL,clk22 SMALLINT UNSIGNED NOT NULL,force22 SMALLINT UNSIGNED NOT NULL,in23 SMALLINT UNSIGNED NOT NULL,out23 SMALLINT UNSIGNED NOT NULL,clk23 SMALLINT UNSIGNED NOT NULL,force23 SMALLINT UNSIGNED NOT NULL)
EOD;
$result = mysql_query($query) or setupfailed(mysql_error());
$query = "CREATE TABLE settings (itversion DECIMAL(5,2) NOT NULL, sitename CHAR(50) NOT NULL, siteurl CHAR(100) NOT NULL, wmemail CHAR(50) NOT NULL, wmicq CHAR(15) NOT NULL,pratio TINYINT NOT NULL,defratio SMALLINT UNSIGNED NOT NULL DEFAULT '130', defurl CHAR(100) NOT NULL, rdnocookie TINYINT UNSIGNED NOT NULL, minprod SMALLINT UNSIGNED NOT NULL DEFAULT '20', minin SMALLINT UNSIGNED NOT NULL, review TINYINT NOT NULL, wmform TINYINT NOT NULL, minprodact TINYINT NOT NULL, mininact TINYINT NOT NULL)";
$result = mysql_query($query) or setupfailed(mysql_error());
$query = "CREATE TABLE blacklist (bid INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT, domain CHAR(50) NOT NULL)";
$result = mysql_query($query) or setupfailed(mysql_error());
$query = "CREATE TABLE visitlog (siteid INT UNSIGNED NOT NULL, ip CHAR(15) NOT NULL, referer CHAR(100) NOT NULL, tid TIMESTAMP)";
$result = mysql_query($query) or setupfailed(mysql_error());
$query = "CREATE TABLE history (hid INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT, datum CHAR(11) NOT NULL, hitsin INT UNSIGNED NOT NULL, hitsout INT UNSIGNED NOT NULL, clicks INT UNSIGNED NOT NULL)";
$result = mysql_query($query) or setupfailed(mysql_error());
$query = "CREATE TABLE updateinfo (lastupdate INT UNSIGNED NOT NULL)";
$result = mysql_query($query) or setupfailed(mysql_error());
$query = "CREATE TABLE links (linkname CHAR(30) PRIMARY KEY NOT NULL,clk0 SMALLINT UNSIGNED NOT NULL,clk1 SMALLINT UNSIGNED NOT NULL,clk2 SMALLINT UNSIGNED NOT NULL,clk3 SMALLINT UNSIGNED NOT NULL,clk4 SMALLINT UNSIGNED NOT NULL,clk5 SMALLINT UNSIGNED NOT NULL,clk6 SMALLINT UNSIGNED NOT NULL,clk7 SMALLINT UNSIGNED NOT NULL,clk8 SMALLINT UNSIGNED NOT NULL,clk9 SMALLINT UNSIGNED NOT NULL,clk10 SMALLINT UNSIGNED NOT NULL,clk11 SMALLINT UNSIGNED NOT NULL,clk12 SMALLINT UNSIGNED NOT NULL,clk13 SMALLINT UNSIGNED NOT NULL,clk14 SMALLINT UNSIGNED NOT NULL,clk15 SMALLINT UNSIGNED NOT NULL,clk16 SMALLINT UNSIGNED NOT NULL,clk17 SMALLINT UNSIGNED NOT NULL,clk18 SMALLINT UNSIGNED NOT NULL,clk19 SMALLINT UNSIGNED NOT NULL,clk20 SMALLINT UNSIGNED NOT NULL,clk21 SMALLINT UNSIGNED NOT NULL,clk22 SMALLINT UNSIGNED NOT NULL,clk23 SMALLINT UNSIGNED NOT NULL)";
$result = mysql_query($query) or setupfailed(mysql_error());
$query = "INSERT INTO sites (sitename) VALUES ('NoRef/DefUrl')";
$result = mysql_query($query) or setupfailed(mysql_error());
$query = "INSERT INTO settings (itversion,defurl,rdnocookie,defratio,pratio) VALUES ('1.00','http://','1','100','1')";
$result = mysql_query($query) or setupfailed(mysql_error());
$query = "INSERT INTO updateinfo (lastupdate) VALUES ('0')";
$result = mysql_query($query) or setupfailed(mysql_error());

mysql_close($link);

$fh = fopen(".htaccess","w") or setupfailed("Writing itadmin/.htaccess failed.<br>Chmod directory <strong>itadmin</strong> 777");
fputs($fh, "AuthName \"infTrade Admin\"\nAuthUserFile $htp\nAuthType Basic\n\n<Limit GET>\nrequire valid-user\n</Limit>");
fclose($fh);

print <<<END
SETUP COMPLETED SUCCESSFULLY
<br><br><br><br>
<a href="index.php">Click Here To Login</a>
<br><br><br><br>
</table>
END;
printfoot();

exit;
}

function setupfailed($msg) {
print <<<END
<font color="Red">$msg</font>
<br><br><br><br>
<a href="setup.php">Click Here</a>
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
<title>Setup</title>
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

