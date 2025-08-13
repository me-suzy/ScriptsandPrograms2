<?php
require("dbsettings.php");

$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());

if( $_POST["addsite"] ) {  
	printheader("infTrade v1.00 - Webmaster Signup");
	$blacklisted = 0;
	$dexist = 0;

	$result = mysql_query("SELECT wmform,review,defratio,siteurl,pratio FROM settings");
	$line = mysql_fetch_array($result, MYSQL_NUM);

	if (!get_magic_quotes_gpc()) {
		$siteurl = addslashes($_POST["siteurl"]);
		$sitename = addslashes($_POST["sitename"]);
		$sitedesc = addslashes($_POST["sitedesc"]);
		$wmemail = addslashes($_POST["wmemail"]);
		$wmicq = addslashes($_POST["wmicq"]);
		}
	else {
		$siteurl = $_POST["siteurl"];
		$sitename = $_POST["sitename"];
		$sitedesc = $_POST["sitedesc"];
		$wmemail = $_POST["wmemail"];
		$wmicq = $_POST["wmicq"];	
	}
	

	$refa = explode("/",$siteurl);
	preg_match("/(www\.)*(.*)/",$refa[2],$refd);
	$sitedomain = $refd[2];

	$result = mysql_query("SELECT bid FROM blacklist WHERE domain='$sitedomain'");
	if( mysql_fetch_array($result, MYSQL_NUM) ) {
		$blacklisted = 1;
		}
	$result = mysql_query("SELECT siteid FROM sites WHERE sitedomain='$sitedomain'");
	if( mysql_fetch_array($result, MYSQL_NUM) ) {
		$dexist = 1;
		}
print <<<END
<table cellspacing="1" cellpadding="7" bgcolor="#000040">
<tr>
<td bgcolor="#000080" align="center">
<p class="hh">Powered by infTrade v1.00 - Free Traffic Trading Script</p>
<a href="http://www.inftrade.com/" target="_blank" class="toplink">more info at www.inftrade.com - click here</a>
</td>
</tr>
<tr>
<td bgcolor="#FFFFFF" align="center">
<iframe src="http://www.inftrade.com/ad1/" width="530" height="75" marginheight="0" marginwidth="0" scrolling="no" frameborder="0"></iframe>
</td>
</tr>
<tr>
<td bgcolor="#FFFFFF" align="center">
<br><br><br>
END;

	if( !$line[0] ) {
		print "Webmaster Signup Form Closed.";
		}
	elseif( $blacklisted ) {
		print "Domain $sitedomain Is Blacklisted";
		}
	elseif( !strstr($wmemail, '@') ) {
		print "You must enter a valid e-mail address.";
		}
	elseif( !strstr($siteurl, "http://") || $siteurl == "http://") {
		print "You must enter a website URL.";
		}
	elseif( $sitename == "" ) {
		print "You must enter a site name";
		}	
	elseif( $dexist ) {
		print "Domain $sitedomain Already Exist";
		}
	else {

		if( $line[1] ) { $status = 6; }
		else { $status = 0; }

		$result = mysql_query("INSERT INTO sites (sitedomain,siteurl,sitename,sitedesc,wmemail,wmicq,ratio,status,pratio) VALUES ('$sitedomain','$siteurl','$sitename','$sitedesc','$wmemail','$wmicq','{$line[2]}','$status','{$line[4]}')");

print <<<END

<strong>$sitedomain added.</strong>
<br><br>Send Traffic To: <a href="{$line[3]}" target="_blank">{$line[3]}</a>

END;

		}
print <<<END
<br><br><br><br>
</td>
</tr>
<tr>
<td bgcolor="#E8E8E8" align="center" class="small">
Copyright &copy 2003 by infTrade.com. All Rights Reserved.
</td>
</tr>
</table>
END;
	printfoot();
	}
else {
	printheader("infTrade v1.00 - Webmaster Signup");

	$rulestxt = file_get_contents("rules.html");

	$result = mysql_query("SELECT wmform,siteurl,wmemail,wmicq FROM settings");
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
print <<<END
<table cellspacing="1" cellpadding="7" bgcolor="#000040">
<tr>
<td bgcolor="#000080" align="center">
<p class="hh">Powered by infTrade v1.00 - Free Traffic Trading Script</p>
<a href="http://www.inftrade.com/" target="_blank" class="toplink">more info at www.inftrade.com - click here</a>
</td>
</tr>
<tr>
<td bgcolor="#FFFFFF" align="center">
<iframe src="http://www.inftrade.com/ad1/" width="530" height="75" marginheight="0" marginwidth="0" scrolling="no" frameborder="0"></iframe>
</td>
</tr>
<tr>
<td bgcolor="#E8E8E8" align="center">
$rulestxt
<br>Send Traffic To: <a href="{$line['siteurl']}" target="_blank">{$line['siteurl']}</a><br><br>
<strong>Contact Info</strong><br>
E-mail: {$line['wmemail']}<br>
ICQ: {$line['wmicq']}
</td>
</tr>
<tr>
<td bgcolor="#FFFFFF" align="center">
END;
//#E8E8E8


	if( $line['wmform'] ) {
print <<<END
<table><form action="webmaster.php" method="post">
<tr><td>Site URL</td><td><input type="text" name="siteurl" size="35" maxlength="100" value="http://" class="inp"></td></tr>
<tr><td>Site Name</td><td><input type="text" name="sitename" size="35" maxlength="50"  class="inp"></td></tr>
<tr><td>Site Description</td><td><input type="text" name="sitedesc" size="35" maxlength="100"  class="inp"></td></tr>
<tr><td>E-mail</td><td><input type="text" name="wmemail" size="35" maxlength="50"  class="inp"></td></tr>
<tr><td>ICQ UIN</td><td><input type="text" name="wmicq" size="35" maxlength="15"  class="inp"></td></tr>
<tr><td colspan="2" align="center"><br><input type="submit" name="addsite" value="Add Site" class="butf"></td></tr></form>
</table>
END;
		}
	else {
		print "Webmaster Signup Form Closed.";
		}


print <<<END
</td>
</tr>
<tr>
<td bgcolor="#E8E8E8" align="center" class="small">
Copyright &copy 2003 by infTrade.com. All Rights Reserved.
</td>
</tr>
</table>
END;

	printfoot();
	}

mysql_close($link);
exit;


function printheader($title) {
global $msg;
print <<<END
<html>
<head>
<title>$title</title>
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
