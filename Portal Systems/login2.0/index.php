<?php
include 'config.php';
$tmp = $_GET['action'];
if($tmp == "signout"){
$cookie_name = "auth";
$cookie_value = "";
$cookie_expire = "0";
$cookie_domain = $domain;
setcookie($cookie_name, $cookie_value, $cookie_expire, "/", $cookie_domain, 0);
header ("Location: http://" . $domain . $directory . "login.php");
}

$connection = mysql_connect($hostname, $user, $pass)
or die(mysql_error());
$db = mysql_select_db($database, $connection)
        or die(mysql_error());

list($cookie, $tmpname) =
   split("!", $_COOKIE[auth], 2);

if($cookie == "fook"){
        $sqlcheckaccount = "SELECT username FROM $userstable
        WHERE username = '$tmpname'";

        $resultcheckaccount = mysql_query($sqlcheckaccount)
        or die ("Couldn't execute query.");
        $numcheckaccount = mysql_num_rows($resultcheckaccount);
        if($numcheckaccount == 0){
                                  echo "Error, Username doesnt exist.";
                                  die;
                                  }

echo "<font face=\"Arial\" size=\"2\">Welcome <b>$tmpname</b>, to the members section </font>";
echo "<br><a href=\"index.php?action=signout\"><font face=\"Arial\" size=\"2\">Sign Out.</a> | <a href=\"members.php\">View all members.</a> | <a href=\"profile.php\">Edit your profile.</a></font><br><br>";
}else{
header ("Location: http://" . $domain . $directory . "login.php");
exit;
}
?>


<html>
<head>
</head>
<body>


<br>  <br>
<font face="Arial">Put all your content here!</font>
<br>
<font face="Arial">
<a href="http://network-13.com" target="_NEW">Network-13.com</a></font></p>

</body></html>