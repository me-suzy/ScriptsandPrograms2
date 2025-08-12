<?php
include 'config.php';

    $headers .= "MIME-Version: 1.0 \n";
    $headers .= "Content-type: text/html; charset=iso-8859-1 \n";
    $headers .= "from:MailingList@$domain\r\nCc:\r\nBcc:";

$tmp = $_GET['action'];
if($tmp == "signout"){
$cookie_name = "adminauth";
$cookie_value = "";
$cookie_expire = "0";
$cookie_domain = $domain;
setcookie($cookie_name, $cookie_value, $cookie_expire, "/", $cookie_domain, 0);
header ("Location: http://" . $domain . $directory . "admin.php");
}
$connection = @mysql_connect($hostname, $user, $pass)
or die(mysql_error());
$dbs = @mysql_select_db($database, $connection) or
die(mysql_error());

$sql = "SELECT * FROM $admintable WHERE username = '$_POST[username]' AND password = '$_POST[password]'";
$result = @mysql_query($sql,$connection) or die(mysql_error());
$num = @mysql_num_rows($result);

if ($num != 0) {
$cookie_name = "adminauth";
$cookie_value = "adminfook!$_POST[username]";
$cookie_expire = "0";
$cookie_domain = $domain;
setcookie($cookie_name, $cookie_value, $cookie_expire, "/", $cookie_domain, 0);
}
list($cookie, $tmpname) =
   split("!", $_COOKIE[adminauth], 2);

if($cookie == "adminfook"){
echo "<font face=\"Arial\" size=\"2\">Logged in!<br><a href=\"admin.php\">Accounts</a> | <a href=\"admin.php?action=createnew\">Insert new account.</a> | <a href=\"maillist.php\">Mailing List</a> | <a href=\"admin.php?action=signout\">Sign Out</a><br><br></font>";
$sql2 =  "SELECT * FROM $userstable ORDER BY username";
$result2 = mysql_query($sql2)
        or die ("Couldn't execute query.");
$subject = $_POST['subject'];
$message = $_POST['message'];

$form .= "<font face=\"Arial\" size=\"2\">Send an email to everyone which has registered to be a member. </font><br><br>";
$form .= "<form method=\"POST\" action=\"maillist.php\">";
$form .= "<font face=\"Times New Roman\"> </font><font face=\"Arial\" size=\"2\">Subject </font>";
$form .= "<br>";
$form .= "<input type=\"text\" name=\"subject\" size=\"20\">";
$form .= "<br>";
$form .= "<font face=\"Times New Roman\"> </font><font face=\"Arial\" size=\"2\">Message:</font><br>";
$form .= "<textarea rows=\"15\" name=\"message\" cols=\"44\"></textarea><input type=\"submit\" value=\"Send\" name=\"B1\"></p>";
$form .= "</form>";

if($subject == "" AND $message == ""){
echo $form;
} else {
while($row = mysql_fetch_array($result2)) {
$message = ereg_replace(13,"<br>",$message); 
mail ($row['email'], $subject, $message, $headers);
}
echo "All messages have been sent!.";
}

                      } else {
header ("Location: http://" . $domain . $directory . "admin.php");

}
?>