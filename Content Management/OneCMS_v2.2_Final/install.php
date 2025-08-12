<?php
$z = "a";
$install = "yes";
include ("config.php");

$modfile = "mods/userreviews.php";

if ($_GET['step'] == "") {
echo install_header();
$var = "";
$var2 = "0";
echo "<div align='left'>";

echo "MySQL Connection Status successfull?: ";
if (@mysql_select_db($dbname, mysql_connect($dbhost, $dbuser, $dbpass)) == TRUE) {
echo "<font color='blue'>YES</font>";
$var .= "Step1=true,";
$var2 = $var2 + 1;
} else {
echo "<font color='red'>NO</font>";
$var .= "Step1=false,";
}

echo "<br>PHP >= 4.0: ";
if (@phpversion() >= "4.0") {
echo "<font color='blue'>YES</font>";
$var .= "Step2=true,";
$var2 = $var2 + 1;
} else {
echo "<font color='red'>NO</font>";
$var .= "Step2=false,";
}

echo "<br>MySQL Enabled: ";
if (@function_exists("mysql_connect")) {
echo "<font color='blue'>YES</font>";
$var .= "Step3=true,";
$var2 = $var2 + 1;
} else {
echo "<font color='red'>NO</font>";
$var .= "Step3=false,";
}

echo "<br>GD Enabled: ";
if (@function_exists("imagecreatefromjpeg")) {
echo "<font color='blue'>YES</font>";
$var .= "Step4=true,";
$var2 = $var2 + 1;
} else {
echo "<font color='red'>NO</font>";
$var .= "Step4=false,";
}

echo "<br>Magic Quotes GPC: ";
if (@get_magic_quotes_gpc()) {
echo "<font color='blue'>YES</font>";
$var .= "Step5=true,";
$var2 = $var2 + 1;
} else {
echo "<font color='red'>NO</font>";
$var .= "Step5=false,";
}

$ex = @explode(",", $var);

echo "</div>";
if ((($var2 >= "2") && ($ex[0] == "Step1=true") && ($ex[2] == "Step3=true"))) {
echo "&nbsp;&nbsp;&nbsp;&nbsp;Proceed to the next step - <a href='install.php?step=1'><b>Step 1</b></a><br><br>";
}

if ($ex[0] == "Step1=false") {
echo "Please check the database information in config.php<br><br>";
}

if ($ex[1] == "Step2=false") {
echo "Your PHP is only version <b>".@phpversion()."</b> which is out of date. If you are running anything below 3.7.0 you may have many problems running OneCMS<br><br>";
}

if ($ex[2] == "Step3=false") {
echo "Sorry but MySQL appears to not be enabled on your server, you cannot use OneCMS without MySQL. Please ask your host to add MySQL<br><br>";
}

if ($ex[3] == "Step4=false") {
echo "GD is not enabled but dont fret, you can use OneCMS except the thumbnailer<br><br>";
}

if ($ex[4] == "Step5=false") {
echo "Magic Quotes GPC is not enabled but it is not a problem, infact it does not matter if it is on or off<br><br>";
}
echo install_footer();
}

if ($_GET['step'] == "upgrade2") {
mysql_select_db($dbname, mysql_connect($dbhost, $dbuser, $dbpass));

        $file = fopen('install_upgrade2.sql','r');
        $contents ='';
        while (!@feof($file)) {
          $contents .= fread($file,1024); //
        }
        @fclose($file);

        $ex = explode("-------", $contents);
	    while (list(, $i) = each ($ex)) {
		mysql_query($i);
}

include ($modfile);

$sql = mysql_query("INSERT INTO onecms_mods VALUES ('null', '".$name."', '".$url."', 'Yes', '".$version."', '".$readme."', '".$url2."', '".$status."')");

$query = @mysql_num_rows(mysql_query("SELECT * FROM onecms_mods WHERE id = '12'"));

ss_type_check();
cfields_check1();
cfields_check2();
cfields_check3();
cfields_check4();

if ($query > "0") {
echo install_header();
echo "Upgrade from v2.1 to v2.2 Successful?: ";
echo "<font color='blue'>YES</font>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;Woah look at that, your already done!";
} else {
echo install_header();
echo "Upgrade Successful?: ";
echo "<font color='red'>NO</font>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;Please make sure the database information is correct and also make sure that all the tables are in the <b>".$dbname."</b> database and try again!";
}
echo install_footer();
}

if ($_GET['step'] == "upgrade1") {
mysql_select_db($dbname, mysql_connect($dbhost, $dbuser, $dbpass));

        $file = fopen('install_upgrade.sql','r');
        $contents ='';
        while (!@feof($file)) {
          $contents .= fread($file,1024); //
        }
        @fclose($file);

		$file2 = fopen('install_upgrade2.sql','r');
        $contents2 ='';
        while (!@feof($file2)) {
          $contents2 .= fread($file2,1024); //
        }
        @fclose($file2);

        $ex = explode("-------", $contents);
		$ex2 = explode("-------", $contents2);
	    while (list(, $i) = each ($ex)) {
		mysql_query($i);
}

	    while (list(, $i2) = each ($ex2)) {
		mysql_query($i2);
}

include ($modfile);

ss_type_check();
cfields_check1();
cfields_check2();
cfields_check3();
cfields_check4();

$sql = mysql_query("INSERT INTO onecms_mods VALUES ('null', '".$name."', '".$url."', 'Yes', '".$version."', '".$readme."', '".$url2."', '".$status."')");

$query = @mysql_num_rows(mysql_query("SELECT * FROM onecms_mods WHERE id = '12'"));

if ($query > "0") {
echo install_header();
echo "Upgrade from v2.0 to v2.2 Successful?: ";
echo "<font color='blue'>YES</font>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;Woah look at that, your already done!";
} else {
echo install_header();
echo "Upgrade Successful?: ";
echo "<font color='red'>NO</font>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;Please make sure the database information is correct and also make sure that all the tables are in the <b>".$dbname."</b> database and try again!";
}
echo install_footer();
}

if ($_GET['step'] == "1") {
mysql_select_db($dbname, mysql_connect($dbhost, $dbuser, $dbpass));

if (((table("af_manager") == TRUE) && (table("onecms_content") == TRUE) && (table("onecms_users") == TRUE))) {
echo install_header();
echo "Looks like you have OneCMS v2 installed on this database, you can upgrade the script:<br><br><center><b><a href='install.php?step=upgrade1'>Upgrade (from v2.0)</a> :: <a href='install.php?step=upgrade2'>Upgrade (from v2.1)</a></b></center>";
} else {
echo install_header();
echo "Looks like you don't have OneCMS v2 installed on this database, you only have the option to install:<br><br><center><b><a href='install.php?step=2'>Install</a></b></center>";
}
echo install_footer();
}

if ($_GET['step'] == "2") {
mysql_select_db($dbname, mysql_connect($dbhost, $dbuser, $dbpass));

        $file = fopen('install_structure.sql','r');
        $contents ='';
        while (!@feof($file)) {
          $contents .= fread($file,1024); //
        }
        @fclose($file);

		$file2 = fopen('install_data.sql','r');
        $contents2 ='';
        while (!@feof($file2)) {
          $contents2 .= fread($file2,1024); //
        }
        @fclose($file2);

		$file3 = fopen('install_upgrade.sql','r');
        $contents3 ='';
        while (!@feof($file3)) {
          $contents3 .= fread($file3,1024); //
        }
        @fclose($file3);

		$file4 = fopen('install_upgrade2.sql','r');
        $contents4 ='';
        while (!@feof($file4)) {
          $contents4 .= fread($file4,1024); //
        }
        @fclose($file4);

        $ex = explode("-------", $contents);
		$ex2 = explode("-------", $contents2);
		$ex3 = explode("-------", $contents3);
		$ex4 = explode("-------", $contents4);
	    while (list(, $i) = each ($ex)) {
		mysql_query($i);
}
        while (list(, $i2) = each ($ex2)) {
		mysql_query($i2);
}

        while (list(, $i3) = each ($ex3)) {
		mysql_query($i3);
}

        while (list(, $i4) = each ($ex4)) {
		mysql_query($i4);
}

include ($modfile);

$sql = mysql_query("INSERT INTO onecms_mods VALUES ('null', '".$name."', '".$url."', 'Yes', '".$version."', '".$readme."', '".$url2."', '".$status."')");

mysql_query("ALTER TABLE `onecms_content` ADD `games` TEXT NOT NULL");
mysql_query("ALTER TABLE `onecms_content` ADD `systems` TEXT NOT NULL");

$query = mysql_num_rows(mysql_query("SELECT * FROM onecms_templates"));

if ($query > "0") {
echo install_header();
echo "SQL Uploaded to Database?: ";
echo "<font color='blue'>YES</font>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;Proceed to the next step - <a href='install.php?step=3'><b>Step 3</b></a>";
} else {
echo install_header();
echo "SQL Uploaded to Database?: ";
echo "<font color='red'>NO</font>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;Please make sure the database information is correct and then try again";
}
echo install_footer();
}

if ($_GET['step'] == "3") {
echo install_header();
mysql_select_db($dbname, mysql_connect($dbhost, $dbuser, $dbpass));

$ex = explode(".", $HTTP_SERVER_VARS['HTTP_HOST']);
if ($ex[2]) {
$sitename = $ex[1];
} else {
$sitename = $ex[0];
}
$siteurl = "http://".$HTTP_SERVER_VARS['HTTP_HOST']."";
$online = "Yes";
$dformat = "M d - Y";
$warn = "5";
$images = "".$siteurl."/images";
$path = "".$HTTP_SERVER_VARS['DOCUMENT_ROOT']."/images";
$max_results = "30";
$width = "150";
$height = "90";

echo "<form action='install.php?step=3_1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><center><b>Global Settings</b></center></td></tr><tr><td>Site Name</td><td><input type=\"text\" name=\"sitename\" value=\"$sitename\"></td></tr><tr><td>Site URL (no trailing slash)</td><td><input type=\"text\" name=\"siteurl\" value=\"$siteurl\"></td></tr><tr><td>Site Online?</td><td><select name='online'><option value=\"$online\" selected>-- $online --</option><option value=\"Yes\">Yes</option><option value=\"No\">No</option></select></td></tr><tr><td>Date Format</td><td><input type=\"text\" name=\"dformat\" value=\"$dformat\"></td></tr><tr><td>Number of Warns allowed (before banned)</td><td><input type=\"text\" name=\"warn\" value=\"$warn\"></td></tr><tr><td>URL to images folder (no trailing slash)</td><td><input type=\"text\" name=\"images\" value=\"$images\"></td></tr><tr><td>Path to images folder (no trailing slash)</td><td><input type=\"text\" name=\"path\" value=\"$path\"></td></tr><tr><td>Amount of items to display (per page...recommended is 30)</td><td><input type=\"text\" name=\"max_results\" value=\"$max_results\"></td></tr><tr><td>Owners E-mail</td><td><input type=\"text\" name=\"email\" value=\"$email\"></td></tr><tr><td>Owners Name</td><td><input type=\"text\" name=\"name\" value=\"$name\"></td></tr><tr><td>Thumbnail Width</td><td><input type=\"text\" name=\"width\" value=\"$width\"></td></tr><tr><td>Thumbnail Height</td><td><input type=\"text\" name=\"height\" value=\"$height\"></td></tr><tr><td><input type=\"submit\" value=\"Next Step\"></td></tr></form></table>";

echo install_footer();
}

if ($_GET['step'] == "3_1") {
mysql_select_db($dbname, mysql_connect($dbhost, $dbuser, $dbpass));

$query = mysql_query("UPDATE onecms_settings SET sitename = '".$_POST["sitename"]."', siteurl = '".$_POST["siteurl"]."', online = '".$_POST["online"]."', dformat = '".$_POST["dformat"]."', warn = '".$_POST["warn"]."', images = '".$_POST["images"]."', path = '".$_POST["path"]."', max_results = '".$_POST["max_results"]."', email = '".$_POST["email"]."', name = '".$_POST["name"]."', height = '".$_POST["height"]."', width = '".$_POST["width"]."' WHERE id = '1'");

if ($query == TRUE) {
echo install_header();
echo "Global Settings added successfully?: ";
echo "<font color='blue'>YES</font>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;Proceed to the next step - <a href='install.php?step=4'><b>Step 4</b></a>";
} else {
echo install_header();
echo "Global Settings added successfully?: ";
echo "<font color='red'>NO</font>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;Please make sure the SQL is uploaded, database information is correct and then try again";
}
echo install_footer();
}

if ($_GET['step'] == "4") {
echo install_header();

echo "<form action='install.php?step=5' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Username</td><td><input type=\"text\" name='name'></td></tr><tr><td>Password</td><td><input type=\"password\" name='password1'></td></tr><tr><td>E-Mail</td><td><input type=\"text\" name='email'></td></tr><tr><td>User Level</td><td><select name='level' multiple size='5'>";
	
	mysql_select_db($dbname, mysql_connect($dbhost, $dbuser, $dbpass));

	$query="SELECT * FROM onecms_userlevels WHERE id = '1'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		echo "<option value=\"$row[name]\" selected>$row[name]</option>";
	}
echo "</td></tr><tr><td><input type=\"submit\" name=\"Add\" value=\"Last Step\"></td></tr></form></table>";

echo install_footer();
}

if ($_GET['step'] == "5") {
mysql_select_db($dbname, mysql_connect($dbhost, $dbuser, $dbpass));

   $sql[0] = mysql_query("INSERT INTO onecms_profile VALUES ('null', '".$_POST["name"]."', '', '', '', '', '', '', '')") or die(mysql_error());

   $sql[1] = mysql_query("INSERT INTO onecms_pm VALUES ('null', '1', 'Welcome ".$_POST["name"]."!', 'Welcome to OneCMS ".$_POST["name"].". Below you can find your user information, please keep record of this.<br><br>Username - ".$_POST["name"]."<br>Password - ".$_POST["password1"]."<br><br>Thanks!', '".$_POST["name"]."', '".$_POST["name"]."', '".time()."')") or die(mysql_error());

   $sql[2] = mysql_query("INSERT INTO onecms_permissions VALUES ('null', '".$_POST["name"]."', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes')") or die(mysql_error());
   
   $password222 = md5($_POST["password1"]);

   $zy = mysql_query("SELECT id FROM onecms_profile WHERE username = '".$_POST["name"]."'");
   $prof = mysql_fetch_row($zy);

   $uid = $prof[0];

   $sql[3] = mysql_query("INSERT INTO onecms_boardcp VALUES ('null', '".$uid."', '', '', 'admin')") or die(mysql_error());

   $sql[4] = mysql_query("INSERT INTO onecms_users VALUES ('null', '".$_POST["name"]."', '$password222', '".$_POST["email"]."', '".$_POST["level"]."', '0', 'no', 'no', '0', '1', '3', 'Yes')") or die(mysql_error());
if ($sql == TRUE) {
echo install_header();
echo "User account added successfully?: ";
echo "<font color='blue'>YES</font>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;You are now finished! You can now login - <a href='a_login.php'><b>Login here</b></a><br><br>Also do not forget to delete the following files:<br>install.php,install_data.sql,install_structure.sql,install_upgrade.sql,install_upgrade2.sql<br><br>Thank you and enjoy OneCMS ".$version."!";
} else {
echo install_header();
echo "User account added successfully?: ";
echo "<font color='red'>NO</font>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;Please make sure the SQL is uploaded, database information is correct and then try again";
}
echo install_footer();
}
?>