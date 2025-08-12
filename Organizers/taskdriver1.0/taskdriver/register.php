<?php

include 'config.php';

function is_alphachar($text) {

    for ($i = 0; $i < strlen($text); $i++) {

            if (!ereg("[A-Za-z0-9]", $text[$i])) {
                    return 1;
    }
    }
    }
function  checkEmail($email) {
 if (!preg_match("/^( [a-zA-Z0-9] )+( [a-zA-Z0-9\._-] )*@( [a-zA-Z0-9_-] )+( [a-zA-Z0-9\._-] +)+$/" , $email)) {
  return false;
 }
 return true;
}
?>
<script language="JavaScript">
function validate(form) {
  var elem = form.elements;
  if(elem.password.value != elem.confirm.value) {
    alert("Please confirm passwords, They do not match");
    return false;
  }
  return true;
}
</script>
<?php
include 'format.css';
$form .= "<br><br><br><br><br><br><br><br><br><br><br>";
$form .= "<table cellpadding=\"3\" class=\"boldb\" width=\"100%\" align=\"center\"><tr><td align=\"right\"><img align=\"absmiddle\" src=\"../images/taskdriverlogo.jpg\"> </td><td width=\"42%\" bgcolor=\"#E6F0FF\"><b><i>Account Creation</i></b></td><td bgcolor=\"#EBF3FE\"></td><td bgcolor=\"#F1F6FD\"></td><td bgcolor=\"#F4F8FD\"></td><td bgcolor=\"#F6F9FD\"></td><td bgcolor=\"#F9FBFD\"></td><td bgcolor=\"#FDFDFD\"></td></tr></table>";
$form .= "<table align=\"center\" class=\"black\" width=\"100%\">";
$form .= "<tr><td align=\"center\" colspan=\"3\">Please enter a valid email as it will be used to recover your account in the event that you forget your password.<br></td></tr>";
$form .= "<tr><td><br></td></tr>";
$form .= "<form action=\"./register.php\" method=\"POST\" onsubmit=\"return validate(this);\">";
$form .= "<tr><td width=\"40%\" align=\"right\"><b>* Username:</b></td><td width=\"10%\"><input type=\"text\" name=\"username\" value=\"$_POST[username]\"></td><td><font size=\"1\">Choose desired username</font></td></tr>";
$form .= "<tr><td width=\"40%\" align=\"right\"><b>* Realname:</b></td><td width=\"10%\"><input type=\"text\" name=\"realname\" value=\"$_POST[realname]\"></td><td><font size=\"1\">Your full first and last name</font></td></tr>";
$form .= "<tr><td width=\"40%\" align=\"right\"><b>* Location:</b></td><td width=\"10%\"><input type=\"text\" name=\"location\" value=\"$_POST[location]\"></td><td><font size=\"1\">City where your company resides</font></td></tr>";

$form .= "<tr><td align=\"right\" valign=\"absbottom\"> <b>Department:</b></td>";
$form .= "<td><select name=\"department\"><option value=\"$tmpdepartment\">$tmpdepartment</option>";
include './includes/cateconfront.php';
$form .= "</select></td><td><font size=\"1\">Name of your corporate department</font></td></tr>";


$form .= "<tr><td width=\"40%\" align=\"right\"><b>* WorkPhone:</b></td><td width=\"10%\"><input type=\"text\" name=\"workphone\" value=\"$_POST[workphone]\"></td><td><font size=\"1\">Your work phone number</font></td></tr>";
$form .= "<tr><td width=\"40%\" align=\"right\"><b>* Email:</b></td><td><input type=\"text\" name=\"email\" value=\"$_POST[email]\"></td><td><font size=\"1\">A valid email address</font></td></tr>";
$form .= "<tr><td width=\"40%\" align=\"right\"><b>* Password:</b></td><td><input type=\"password\" name=\"password\" value=\"$_POST[password]\"></td><td><font size=\"1\">Password must contain 6 or more characters.</font></td></tr>";
$form .= "<tr><td width=\"40%\" align=\"right\"><b>* Confirm Password:</b></td><td><input type=\"password\" name=\"confirm\" value=\"$_POST[password]\"></td><td></td></tr>";
$form .= "<tr><td width=\"40%\"></td><td><br><input type=\"submit\" value=\"Create Account\"></td><td></td></tr>";
$form .= "</form>";
$form .= "</table>";


if($_POST[username] == ""){
echo $form;
} elseif(strlen($_POST[password]) < 6){
echo $form;
echo "<table class=\"black\" align=\"center\"><tr><td align=\"center\"><br><b>Error: <font color=red>Password must be 6 characters or more</b></font></td></tr></table>";


} else {


$connection = mysql_connect($hostname, $user, $pass)
or die(mysql_error());
$db = mysql_select_db($database, $connection)
        or die(mysql_error());


$sql = "SELECT username FROM $userstable
        WHERE username = '$_POST[username]'";

$sql2 = "SELECT email FROM $userstable
        WHERE email = '$_POST[email]'";

$result = mysql_query($sql)
        or die ("Couldn't execute query.");

$result2 = mysql_query($sql2)
        or die ("Couldn't execute query.");

$num = mysql_num_rows($result);
$num2 = mysql_num_rows($result2);

if (is_alphachar($_POST[username]) == 1) {
echo $form;
echo "<table class=\"black\" align=\"center\"><tr><td align=\"center\"><b>Error: <font color=red>Invalid Username. Only numbers and/or letters are allowed.</b></font></td></tr></table>";
die;
}
if ($num == 1) {

echo "<br><br><br><br><br><br><br><br><br><br><br>";
echo "<table cellpadding=\"3\" class=\"boldb\" width=\"100%\" align=\"center\"><tr><td align=\"right\"><img align=\"absmiddle\" src=\"../images/taskdriverlogo.jpg\"> </td><td width=\"42%\" bgcolor=\"#E6F0FF\"><b><i>Account Creation</i></b></td><td bgcolor=\"#EBF3FE\"></td><td bgcolor=\"#F1F6FD\"></td><td bgcolor=\"#F4F8FD\"></td><td bgcolor=\"#F6F9FD\"></td><td bgcolor=\"#F9FBFD\"></td><td bgcolor=\"#FDFDFD\"></td></tr></table>";
echo "<table class=\"black\" align=\"center\"><tr><td align=\"center\"><br><br><br><b>Error: <font color=red>That username already exists. Please make another selection.</b></font></td></tr></table>";
echo "<table align=\"center\" class=\"black\" width=\"100%\">";
echo "<tr><td align=\"center\" colspan=\"3\"></td></tr>";
echo "<form action=\"javascript:history.go(-1)\" method=\"POST\">";
echo "<table class=\"black\" align=\"center\"><tr><td width=\"40%\"></td><td><br><input type=\"submit\" value=\"<< Go Back\"></td></tr>";
echo "</form>";
echo "</table>";

} elseif ($num2 == 1) {

echo "<br><br><br><br><br><br><br><br><br><br><br>";
echo "<table cellpadding=\"3\" class=\"boldb\" width=\"100%\" align=\"center\"><tr><td align=\"right\"><img align=\"absmiddle\" src=\"../images/taskdriverlogo.jpg\"> </td><td width=\"42%\" bgcolor=\"#E6F0FF\"><b><i>Account Creation</i></b></td><td bgcolor=\"#EBF3FE\"></td><td bgcolor=\"#F1F6FD\"></td><td bgcolor=\"#F4F8FD\"></td><td bgcolor=\"#F6F9FD\"></td><td bgcolor=\"#F9FBFD\"></td><td bgcolor=\"#FDFDFD\"></td></tr></table>";

echo "<table class=\"black\" align=\"center\"><tr><td align=\"center\"><br><br><br><b>Error: <font color=red>That email address has already been registered. Please select a different one.</b></font></td></tr></table>";

echo "<table align=\"center\" class=\"black\" width=\"100%\">";
echo "<tr><td align=\"center\" colspan=\"3\"></td></tr>";
echo "<form action=\"javascript:history.go(-1)\" method=\"POST\">";
echo "<table class=\"black\" align=\"center\"><tr><td width=\"40%\"></td><td><br><input type=\"submit\" value=\"<< Go Back\"></td></tr>";
echo "</form>";
echo "</table>";
} else {

$query = "INSERT INTO $userstable (realname,username,password,email,vcode,workphone,location,department)
VALUES ('$_POST[realname]','$_POST[username]',md5('$_POST[password]'),'$_POST[email]','','$_POST[workphone]','$_POST[location]','$_POST[department]')";
$resultB = mysql_query($query,$connection) or die ("Couldn't execute query.");

//$cookie_name = "auth";
//$cookie_value = "fook!$_POST[username]";
//$cookie_expire = "0";
//$cookie_domain = $domain;
//setcookie($cookie_name, $cookie_value, $cookie_expire, "/", $cookie_domain, 0);

echo "<br><br><br><br><br><br><br><br><br><br><br>";
echo "<table cellpadding=\"3\" class=\"boldb\" width=\"100%\" align=\"center\"><tr><td align=\"right\"><img align=\"absmiddle\" src=\"../images/taskdriverlogo.jpg\"> </td><td width=\"42%\" bgcolor=\"#E6F0FF\"><b><i>Account Creation</i></b></td><td bgcolor=\"#EBF3FE\"></td><td bgcolor=\"#F1F6FD\"></td><td bgcolor=\"#F4F8FD\"></td><td bgcolor=\"#F6F9FD\"></td><td bgcolor=\"#F9FBFD\"></td><td bgcolor=\"#FDFDFD\"></td></tr></table>";
echo "<table class=\"black\" width=\"40%\" align=\"center\"><tr><td><br><br><br><b>Congratulations $_POST[realname]. Your account has been successfully created.<br><br>Click <a href=\"index.php\">here</a> to login to TaskDriver</td></tr></table>";

$new = $_POST[email];
$emailsql = "SELECT email FROM $userstable WHERE userlevel = 'A'";
$resultsql = mysql_query($emailsql); 
$alert = "<font face=\"arial\"><b><i>Email Alert from TaskDriver</i></b><br><br>";
$break = '<br>';
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
$footer = "This email was auto generated. PLEASE DO NOT REPLY";
$subject = "TaskDriver: New Account Created";
while(list($email) = mysql_fetch_array( $resultsql )) {
$subjects = array("$email","$new");
foreach($subjects as $send){
mail($send,$subject,$alert . " " . $break .
"<b>A TaskDriver account was created for:  </b>" .$_POST['realname'] . " " . $break . 
"<br><b>Your username is:  </b> " . $_POST['username'] . " " . $break . 
"<br><br>Thank you for using TaskDriver!</font>" . " " . $break . 
"<br><br><br><font size=1>" . $footer,$headers);
}
}

}
}
?>