<html>
<head>
	<title>Admin Login/Control Panel</title>
	<?
	include("config.php");
?>
</head>

<body>
<?
include("header.php");
//Check if cookie is set
if (!isset($_COOKIE['cookie_info']))  
{  
echo $_COOKIE['cookie_info'];
?>
    <form method="POST" action="login.php">
    <center><h1>Login</h1>
      <Center>
        <table border="0" width="auto">
        <tr>
          <td width="33%">Login Name</td>
          <td width="33%"><input type="text" name="usr" size="20"></td>
          <td width="34%"> </td>
        </tr>
        <tr>
          <td width="33%">Password</td>
          <td width="33%"><input type="password" name="pass" size="20"></td>
        </tr>
      </table>
    <center>
    <p><input type="submit" value="Submit" name="sub">
    <input type="reset" value="Reset" name="res"></p>
    </center>
    </form>

		<br><br>

<?
}
else
{
  //Cookie is set and display the data
   $cookie_info = explode("-", $_COOKIE['cookie_info']);  //Extract the Data
   $user = $cookie_info[0];
   echo "<center>Logged in as $user.";  
   echo "<br><a href='logout.php'>Logout</a>.</center><br>";
?>

				<center>
				<font size="5"><u><b>Admin Control Panel</font></u></b>
				</center>
				<? 
				// UPDATER
$update = "http://www.dvondrake.com/dsn.html";
$exists = file_exists($update);
if($exists = "1") {
$utxt = file_get_contents("$update");
$currfile = "ver.html";
$curr = file_get_contents("$currfile");
if($utxt != $curr) {
echo "NEW VERSION OF DSNEWSLETTER AVALABLE!!<br>Download it now at <a href=http://dl.dvondrake.com/DSNewsletter.zip>http://dl.dvondrake.com/DSNewsletter.zip</a>.<br><br>";
} else {
echo "No new version avalable.<br><br>";
}
} else {
echo "ERROR! Cannot access remote update file.<br><br>";
}
				$month = date(m);
				// DELETER
$year = date(Y);
$filename = "year.html";
$str = file_get_contents("$filename");
if($year == $str) {
echo "No old articles deleted.<br><br>";
} else {
echo "Deleted all old articles:<br><br>";
$folder = "perm";
if ($handle = opendir($folder)) {
    while (false !== ($file = readdir($handle))) { 
        if (is_file("$folder/$file")) { 
            unlink("$folder/$file");
			echo "$folder/$file<br><br>";
        } 
    }
	}
    closedir($handle); 
	$fp = fopen($filename, 'w');
fwrite($fp, $year);
fclose($fp);
}
echo "<form action=publish.php method=POST><input name=qwertyuiop123 type=hidden value=asdfghjkl321><input name=pfix type=text value=$month> Date to publish <br> <input type=submit value=Publish></form><br><br>";
?>				
				<u>Pending Articles:</u><br>
<?php 
$folder = "pend";
if ($handle = opendir($folder)) {
    while (false !== ($file = readdir($handle))) { 
        if (is_file("$folder/$file")) { 
            $size = filesize("$folder/$file");
            echo "<a href=$folder/$file>$file</a> Size: $size <br>Controls: <form action=accept.php method=POST><input name=file type=hidden value=$file><input name=folder type=hidden value=$folder><input type=submit value=Accept></form> <form action=reject.php method=POST><input name=file type=hidden value=$file><input name=folder type=hidden value=$folder><input type=submit value=Reject></form> <form action=edit.php method=POST><input name=file type=hidden value=$file><input name=folder type=hidden value=$folder><input type=submit value=Edit></form><br>\n"; 
        } 
    }
    closedir($handle); 
}
?>
<u>Permanant Articles:</u><br>
<?php
$folder = "perm";
if ($handle = opendir($folder)) {
    while (false !== ($file = readdir($handle))) { 
        if (is_file("$folder/$file")) { 
            $size = filesize("$folder/$file");
            echo "<a href=$folder/$file>$file</a> Size: $size <br>Controls: <form action=reject.php method=POST><input name=file type=hidden value=$file><input name=folder type=hidden value=$folder><input type=submit value=Reject></form> <form action=edit.php method=POST><input name=file type=hidden value=$file><input name=folder type=hidden value=$folder><input type=submit value=Edit></form><br>\n"; 
        } 
    }
    closedir($handle); 
}
?>
<u>Logs:</u><br>
<?php
$folder = "logs";
if ($handle = opendir($folder)) {
    while (false !== ($file = readdir($handle))) { 
        if (is_file("$folder/$file")) { 
            $size = filesize("$folder/$file");
            echo "<a href=$folder/$file>$file</a><br>Controls: <form action=reject.php method=POST><input name=log type=hidden value=1><input name=file type=hidden value=$file><input name=folder type=hidden value=$folder><input type=submit value=Reject></form> <form action=edit.php method=POST><input name=file type=hidden value=$file><input name=folder type=hidden value=$folder><input type=submit value=Edit></form><br>\n"; 
        } 
    }
    closedir($handle); 
}
?>
<u>Emails:</u><br>
<?php
$folder = "emails";
if ($handle = opendir($folder)) {
    while (false !== ($file = readdir($handle))) { 
        if (is_file("$folder/$file")) { 
            $size = filesize("$folder/$file");
            echo "$file<br>Controls: <form action=reject.php method=POST><input name=file type=hidden value=$file><input name=folder type=hidden value=$folder><input type=submit value=Reject></form><br>\n"; 
        } 
    }
    closedir($handle); 
}
?>
				<?
				echo "\n\n";
				}
				?>

</body>
</html>
<?
include("footer.php");
?>
