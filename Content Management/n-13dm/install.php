<html>
<head>
<title>Network-13 Download Manager Installation</title>
</head>
<body>
<font face="Tahoma" size="2">
<?php
if($_POST['username'] == ""){
echo "<p><b><font face=\"Arial\" size=\"2\">Network-13 Download Manager installation</font></b></p>\n";
echo "<font face=\"Arial\" size=\"2\" color=\"FF0000\">Be sure to CHMOD the installation directory to 777</font> \n";
echo "<br>";
echo "<form method=\"POST\" action=\"install.php\">\n";
echo "  <b><font face=\"Arial\" size=\"2\">MySQL Database</font></b><table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" bordercolordark=\"#000000\" bordercolorlight=\"#000000\">\n";
echo "    <tr>\n";
echo "      <td width=\"22%\"><font face=\"Arial\" size=\"2\">Hostname: </font></td>\n";
echo "      <td width=\"78%\">\n";
echo "  <font face=\"Arial\">\n";
echo "  <input type=\"text\" name=\"hostname\" size=\"20\" value=\"localhost\"> </font>\n";
echo "  <font face=\"Arial\" size=\"2\" color=\"#FF0000\">(Usually localhost)</font></td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "      <td width=\"22%\"><font face=\"Arial\" size=\"2\">Username:</font></td>\n";
echo "      <td width=\"78%\">\n";
echo "  <font face=\"Arial\">\n";
echo "  <input type=\"text\" name=\"username\" size=\"20\" value=\"username\">\n";
echo "  <font color=\"#FF0000\" size=\"2\">(Username for database)</font></font></td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "      <td width=\"22%\"><font face=\"Arial\" size=\"2\">Password:</font></td>\n";
echo "      <td width=\"78%\">\n";
echo "  <font face=\"Arial\">\n";
echo "  <input type=\"password\" name=\"password\" size=\"20\" value=\"password\">\n";
echo "  <font color=\"#FF0000\" size=\"2\">(Password for database)</font></font></td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "      <td width=\"22%\"><font face=\"Arial\" size=\"2\">Database:</font></td>\n";
echo "      <td width=\"78%\">\n";
echo "  <font face=\"Arial\">\n";
echo "  <input type=\"text\" name=\"database\" size=\"20\" value=\"database\">\n";
echo "  <font color=\"#FF0000\" size=\"2\">(Database name)</font></font></td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "      <td width=\"22%\" bordercolorlight=\"#000000\" bordercolordark=\"#000000\">\n";
echo "      <font face=\"Arial\" size=\"2\">Admin table:</font></td>\n";
echo "      <td width=\"78%\" bordercolorlight=\"#000000\" bordercolordark=\"#000000\">\n";
echo "  <font face=\"Arial\">\n";
echo "  <input type=\"text\" name=\"admintable\" size=\"20\" value=\"admin\">\n";
echo "  <font color=\"#FF0000\" size=\"2\">(Name of the Admin table which will be created\n";
echo "  in the database)</font></font></td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "      <td width=\"22%\" bordercolorlight=\"#000000\" bordercolordark=\"#000000\">&nbsp;</td>\n";
echo "      <td width=\"78%\" bordercolorlight=\"#000000\" bordercolordark=\"#000000\">\n";
echo "</td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "      <td width=\"22%\" bordercolor=\"#FFFFFF\" bordercolorlight=\"#000000\" bordercolordark=\"#000000\">\n";
echo "      <b><font face=\"Arial\" size=\"2\">Administration area</font></b></td>\n";
echo "      <td width=\"78%\" bordercolordark=\"#000000\" bordercolorlight=\"#000000\">\n";
echo "</td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "      <td width=\"22%\" bordercolorlight=\"#000000\" bordercolordark=\"#000000\">\n";
echo "      <font face=\"Arial\" size=\"2\">Username</font></td>\n";
echo "      <td width=\"78%\" bordercolorlight=\"#000000\" bordercolordark=\"#000000\">\n";
echo "  <font face=\"Arial\">\n";
echo "  <input type=\"text\" name=\"adminusername\" size=\"20\" value=\"admin\">\n";
echo "  <font color=\"#FF0000\" size=\"2\">(Username for Administration area)</font></font></td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "      <td width=\"22%\" bordercolorlight=\"#000000\" bordercolordark=\"#000000\">\n";
echo "      <font face=\"Arial\" size=\"2\">Password</font></td>\n";
echo "      <td width=\"78%\" bordercolorlight=\"#000000\" bordercolordark=\"#000000\">\n";
echo "  <font face=\"Arial\">\n";
echo "  <input type=\"password\" name=\"adminpassword\" size=\"20\" value=\"password\">\n";
echo "  <font color=\"#FF0000\" size=\"2\">(Password for Administration area)</font></font></td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "      <td width=\"22%\" bordercolorlight=\"#000000\" bordercolordark=\"#000000\" bordercolor=\"#FFFFFF\">&nbsp;</td>\n";
echo "      <td width=\"78%\" bordercolorlight=\"#000000\" bordercolordark=\"#000000\">\n";
echo "</td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "      <td width=\"22%\" bordercolorlight=\"#000000\" bordercolordark=\"#000000\" bordercolor=\"#FFFFFF\">\n";
echo "      <b><font face=\"Arial\" size=\"2\">Configuration</font></b></td>\n";
echo "      <td width=\"78%\" bordercolorlight=\"#000000\" bordercolordark=\"#000000\">\n";
echo "</td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "      <td width=\"22%\" bordercolorlight=\"#000000\" bordercolordark=\"#000000\">\n";
echo "      <font face=\"Arial\" size=\"2\">Domain</font></td>\n";
echo "      <td width=\"78%\" bordercolorlight=\"#000000\" bordercolordark=\"#000000\">\n";
echo "  <font face=\"Arial\">\n";
echo "  <input type=\"text\" name=\"domain\" size=\"20\" value=\"YourDomain.com\">\n";
echo "  <font size=\"2\" color=\"#FF0000\">(Do NOT enter http:// or www.)</font></font></td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "      <td width=\"22%\"><font face=\"Arial\" size=\"2\">Installation directory</font></td>\n";
echo "      <td width=\"78%\">\n";
echo "  <font face=\"Arial\">\n";
echo "  <input type=\"text\" name=\"directory\" size=\"20\" value=\"/votingpoll/\">\n";
echo "  <font color=\"#FF0000\" size=\"2\">(Directory script is installed, Be sure to add\n";
echo "  a beginning and ending slash)</font></font></td>\n";
echo "    </tr>\n";
echo "  </table>\n";
echo "\n";
echo "  <table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\">\n";
echo "    <tr>\n";
echo "      <td width=\"22%\">&nbsp;</td>\n";
echo "      <td width=\"78%\">\n";
echo "  <input type=\"submit\" value=\"Install\" name=\"B1\"></td>\n";
echo "    </tr>\n";
echo "  </table>\n";
echo "&nbsp;</p>\n";
echo "  </form>\n";
echo "<p>&nbsp;</p>\n";
echo "<p align=\"center\"><a href=\"http://network-13.com/\"><font size=\"2\" face=\"Arial\">\n";
echo "Network-13</font></a></p>\n";
} else {

$hostname = $_POST['hostname'];
$username = $_POST['username'];
$password = $_POST['password'];
$database = $_POST['database'];
$admintable = $_POST['admintable'];
$adminusername = $_POST['adminusername'];
$adminpassword = $_POST['adminpassword'];
$domain = $_POST['domain'];
$directory = $_POST['directory'];

echo "<p><b><font face=\"Arial\" size=\"2\">Network-13 Voting Poll installation</font></b></p>\n";
if($hostname == ""){ echo "<font color=\"FF0000\">Error <b>hostname</b> must be filled out</font>\n"; die; }
if($username == ""){ echo "<font color=\"FF0000\">Error <b>username</b> must be filled out</font>\n"; die; }
if($password == ""){ echo "<font color=\"FF0000\">Error <b>password</b> must be filled out</font>\n"; die; }
if($database == ""){ echo "<font color=\"FF0000\">Error <b>database</b> must be filled out</font>\n"; die; }
if($admintable == ""){ echo "<font color=\"FF0000\">Error <b>admin table</b> must be filled out</font>\n"; die; }
if($adminusername == ""){ echo "<font color=\"FF0000\">Error <b>admin useranme</b> must be filled out</font>\n"; die; }
if($adminpassword == ""){ echo "<font color=\"FF0000\">Error <b>admin password</b> must be filled out</font>\n"; die; }
if($domain == ""){ echo "<font color=\"FF0000\">Error <b>domain</b> must be filled out</font>\n"; die; }
if($directory == ""){ echo "<font color=\"FF0000\">Error <b>installation directory</b> must be filled out</font>\n"; die; }
echo "Installing.......<br>\n";


$data .= "<?php\n";
$data .= "$" . "hostname = \"$hostname\";\n";
$data .= "$" . "user = \"$username\";\n";
$data .= "$" . "pass = \"$password\";\n";
$data .= "$" . "database = \"$database\";\n";
$data .= "$" . "admintable = \"$admintable\";\n";
$data .= "$" . "domain = \"$domain\";\n";
$data .= "$" . "directory = \"$directory\";\n";
$data .= "$" . "connection = mysql_connect($" . "hostname, $" . "user, $" . "pass)\n";
$data .= "or die(mysql_error());\n";
$data .= "$" . "db = mysql_select_db($" . "database, $" . "connection)\n";
$data .= "or die(mysql_error());\n";
$data .= "?>";

$file = "config.php";
echo "Creating config.php...";
if (file_exists("$file") == 1){ echo "$file already exists, Delete this file and install again."; die; }
if (!$file_handle = fopen($file,"a")) { echo "Cannot open file"; }
if (!fwrite($file_handle, $data)) { echo "Cannot write to $file";}
echo "<font color=\"005500\"><b> OK!</b></font><br>\n";
fclose($file_handle);
echo "Creating administration table in database....";

$connection = mysql_connect($hostname, $username, $password)
or die(mysql_error());
$db = mysql_select_db($database, $connection)
or die(mysql_error());





$query = "CREATE TABLE $admintable (user VARCHAR(255),pass VARCHAR(255))";
if(mysql_query($query)){
echo "<font color=\"005500\"><b> OK!</b></font><br>\n";
} else {
echo "<font color=\"FF0000\"> Error, table has not been created.</font><br>";
}
echo "Inserting admin name and pass into database...";
$query = "INSERT INTO `$admintable` (users,pass)
        VALUES ('$adminusername','$adminpassword')";
if(mysql_query($query)){
echo "<font color=\"005500\"><b> OK!</b></font><br>\n";
} else {
echo "Error, name and pass has NOT been inserted into database<br>\n";
}
echo "<br><font size=\"4\" color=\"FF0000\"><b>REMOVE/DELETE INSTALL.PHP FROM THE SERVER!</font>";
echo "<br><br><a href=\"admin.php\">Click here to goto the admin area.</a>";
}
?>
</font>
</body>
</html>