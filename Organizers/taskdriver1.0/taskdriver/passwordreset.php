<?php
include 'config.php';
include 'format.css';
$step = $_GET['step'];
$headers .= "MIME-Version: 1.0 \n";
$headers .= "Content-type: text/html; charset=iso-8859-1 \n";
$headers .= "from:AccountRecovery@$domain\r\nCc:\r\nBcc:";

if(strlen($step) < 1){
header ("Location: passwordreset.php?step=1");
die;
}

echo "<br><br><br><br><br><br><br><br><br><br><br>";
echo "<table cellpadding=\"3\" class=\"boldb\" width=\"100%\" align=\"center\"><tr><td align=\"right\"><img align=\"absmiddle\" src=\"../images/taskdriverlogo.jpg\"> </td><td width=\"42%\" bgcolor=\"#E6F0FF\"><b>Password Reset</b></td><td bgcolor=\"#EBF3FE\"></td><td bgcolor=\"#F1F6FD\"></td><td bgcolor=\"#F4F8FD\"></td><td bgcolor=\"#F6F9FD\"></td><td bgcolor=\"#F9FBFD\"></td><td bgcolor=\"#FDFDFD\"></td></tr></table>";

if($step == 1){
echo "<table class=\"black\" align=\"center\"><tr><td>";
echo "<br>Please enter the email address you used when you initially created your account.";
echo "<br><br><br></td></tr>";
echo "<tr><td align=\"center\"><form method=\"POST\" action=\"passwordreset.php?step=2\">";
echo "<b>Your email:</b> <input type=\"text\" name=\"email\" size=\"20\"></td></tr>";
echo "<tr><td align=\"center\"><br><input type=\"submit\" value=\"Send Confirmation Code\" name=\"B1\"></form></td></tr></table>";
}

if($step == 2){
$ticket = md5(uniqid(rand(), true));
$email = $_POST['mail'];

$connection = mysql_connect($hostname, $user, $pass)
or die(mysql_error());
$db = mysql_select_db($database, $connection)
        or die(mysql_error());


$sql = "SELECT email FROM $userstable
        WHERE email = '$_POST[email]'";

$result = mysql_query($sql)
        or die ("Couldn't execute query.");

$sql2 = "SELECT vcode FROM $userstable
        WHERE email = '$_POST[email]'";

$result2 = mysql_query($sql2)
        or die ("Couldn't execute query.");


$num = mysql_num_rows($result);

        if ($num == 1) {
            $tmpcode = mysql_result($result2,0);
                       if(strlen($tmpcode) < 1){
                       $query = "UPDATE $userstable SET vcode = '$ticket'
                                 WHERE email = '$_POST[email]'";
                       $resultB = mysql_query($query,$connection) or die ("Coundn't execute query.");
					   $msg = "<font face=\"arial\">A password reset was initiated for your email address. If you did not initiate this change please disregard this email. DO NOT proceed with the instructions below.<br><br><br> If you did initiate this change, please copy and paste the confirmation code you see below into the form provided on the Password Reset page.<br>";
                       mail($_POST['email'],"TaskDriver Respository: Password Confirmation.","<font face=\"arial\"><b>This email was auto generated. PLEASE DO NOT REPLY</b></font><br><br>$msg<br><br><b>Confirmation Code:</b> " . "\n" . $ticket,$headers);
					   
					   echo "<table class=\"black\" align=\"center\"><tr><td>";
                       echo "<br><br></td></tr>";
                       echo "<tr><td align=\"center\"><font color=\"red\"><b>DO NOT CLOSE THIS WINDOW!</b></font></td></tr>";
                       echo "<tr><td align=\"center\">An email has been sent to this address with the confirmation code.<br></td></tr></table>";
					   
					   } else {
					  $msg = "<font face=\"arial\">A password reset was initiated for your email address. If you did not initiate this change please disregard this email. DO NOT proceed with the instructions below.<br><br><br> If you did initiate this change, please copy and paste the confirmation code you see below into the form provided on the Password Reset page.<br>";
                       mail($_POST['email'],"TaskDriver Respository: Password Confirmation.","<font face=\"arial\"><b>This email was auto generated. PLEASE DO NOT REPLY</b></font><br><br>$msg<br><br><b>Confirmation Code:</b> " . "\n" . $tmpcode,$headers);
					   echo "<table class=\"black\" align=\"center\"><tr><td>";
                       echo "<br><br></td></tr>";
                       echo "<tr><td align=\"center\">An email has been sent to this address with the confirmation code.</td></tr>";
                       echo "<tr><td align=\"center\"><br><font color=\"red\"><b>DO NOT CLOSE THIS WINDOW!</b></font></td></tr></table>";
                       }
					   echo "<table class=\"black\" align=\"center\"><tr><td>";
                       echo "<br>Please paste the confirmation code that you recieved in email in the box below.";
                       echo "<tr><td align=\"center\"><form method=\"POST\" action=\"passwordreset.php?step=3\"></td></tr>";
                       echo "<tr><td align=\"center\"><input type=\"text\" name=\"vcode\" size=\"30\"></td></tr>";
                       echo "<tr><td align=\"center\"><input type=\"submit\" value=\"Submit Code\" name=\"B1\">";
                       echo "</form></td></tr>";
        } else {
echo "<table class=\"black\" align=\"center\"><tr><td>";
echo "<br>Please enter the email address you used when you initially created your account.";
echo "<br><br><br></td></tr>";
echo "<tr><td align=\"center\"><form method=\"POST\" action=\"passwordreset.php?step=2\">";
echo "<b>Your email:</b> <input type=\"text\" name=\"email\" size=\"20\"><br><br></td></tr>";
echo "<tr><td align=\"center\"><b>Error: <font color=\"red\">That email address was not found.</b></font></td></tr>";
echo "<tr><td align=\"center\"><br><br><input type=\"submit\" value=\"Send Confirmation Code\" name=\"B1\"></form></td></tr></table>";

               }
}

if($step == 3){
$vcode = $_POST['vcode'];

$connection = mysql_connect($hostname, $user, $pass)
              or die(mysql_error());
$db = mysql_select_db($database, $connection)
      or die(mysql_error());

$sql = "SELECT vcode FROM $userstable
        WHERE vcode = '$vcode'";

$result = mysql_query($sql)
        or die ("Couldn't execute query.");

$num = mysql_num_rows($result);
       if($num == 1){
	           echo "<table class=\"black\" align=\"center\">";
	           echo "<form method=\"POST\" action=\"passwordreset.php?step=4\">";
		       echo "<tr><td colspan=\"2\" align=\"center\"><br>Please enter your new password<br><br></td></tr>";
			   echo "<tr><td align=\"right\"><b>Enter New Password:</b></td><td><input type=\"password\" name=\"pword\" size=\"20\"></td></tr>";
			   echo "<tr><td align=\"right\"><b>Confirm Password:</b></td><td><input type=\"password\" name=\"cpword\" size=\"20\"><input type=\"hidden\" name=\"vcode\" size=\"20\" value=\"$vcode\"></td></tr>";
               echo "<tr><td colspan=\"2\" align=\"center\"><br><br><input type=\"submit\" value=\"Reset Password\" name=\"B1\"></form></td></tr></table>";

       } else {
	           echo "<table class=\"black\" align=\"center\">";
	           echo "<tr><td align=\"center\"><br><br><form action=\"javascript:history.go(-1)\" method=\"POST\"></td></tr>";
               echo "<tr><td align=\"center\"><b>Error: <font color=red>The confirmation code you submitted is incorrect. Please try again.</font><br><br><input type=\"submit\" value=\"<< Go Back\"></form></td></tr></table>";
               }
}

if($step == 4){
$vcode = $_POST['vcode'];
$pword = $_POST['pword'];
$cpword = $_POST['cpword'];

$connection = mysql_connect($hostname, $user, $pass)
              or die(mysql_error());
$db = mysql_select_db($database, $connection) or die(mysql_error());

$sql = "SELECT password FROM $userstable WHERE vcode = '$vcode'";

$result = mysql_query($sql)
        or die ("Couldn't execute query.");

$num = mysql_num_rows($result);
       if($num == 1){
          $tmpcode = mysql_result($result,0);
                  
if($pword == $cpword){
$query = "UPDATE $userstable SET password = md5('$pword') WHERE vcode = '$vcode'";
$result2 = mysql_query($query) or die ("Couldn't execute query.");
} else {
echo "<table class=\"black\" align=\"center\">";
echo "<tr><td align=\"center\"><br><br></td></tr>";
echo "<tr><td align=\"center\"><b>Error: <font color=red> Passwords don't match. Please try again.</font><br></td></tr></table>";
die;
}
$query3 = "UPDATE $userstable SET vcode = '' WHERE vcode = '$vcode'";
$result3 = mysql_query($query3) or die ("Couldn't execute query.");

	           echo "<table class=\"black\" align=\"center\">";
	           echo "<form method=\"POST\" action=\"index.php\">";
		       echo "<tr><td colspan=\"2\" align=\"center\"><br><br></td></tr>";
			   echo "<tr><td align=\"right\"><b>Your password has been successfully changed.</b></td></tr>";
			   echo "<tr><td align=\"right\">You may now login with your new password.</td></tr>";
               echo "<tr><td colspan=\"2\" align=\"center\"><br><br><input type=\"submit\" value=\"Go to Login\" name=\"B1\"></form></td></tr></table>";


        } else {
		
		header ('Location: passwordreset.php?step=1');
       }
}
?>