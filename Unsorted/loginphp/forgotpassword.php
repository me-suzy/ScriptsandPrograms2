<?php
//include the header
require("top.php");
echo "<br><center>";
echo "Forgot your password? Just enter your email into the field below, and we will email you the password.<br><br>";
echo "<form method=post action=forgotpassword.php?action=email><input type=text name=Email>&nbsp;&nbsp;&nbsp;<input type=submit value=Continue></form>";
if($_GET['action'] == 'email')
{
$result = mysql_query("SELECT * FROM loginphp
WHERE Email='{$_POST['Email']}'") or die(mysql_error()); 
$row = mysql_fetch_array( $result );
// The message
$message = "Your password is : " . $row['Pword'] . ".";
// Send
mail($_POST['Email'], 'Forgot password', $message);
echo "<b>Check your email for your password</b>";
}
?>
