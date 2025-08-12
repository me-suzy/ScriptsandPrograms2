<?php

include 'config.php';
$step = $_GET['step'];

    $headers .= "MIME-Version: 1.0 \n";
    $headers .= "Content-type: text/html; charset=iso-8859-1 \n";
    $headers .= "from:AccountRecover@$domain\r\nCc:\r\nBcc:";

if(strlen($step) < 1){
        header ("Location: passwordreset.php?step=1");
        die;
}

if($step == 1){
echo "Please enter your email address you used when creating your account.";
echo "<br><br>";
echo "Your email:";
echo "<br>";
echo "<form method=\"POST\" action=\"passwordreset.php?step=2\">";
echo "<input type=\"text\" name=\"email\" size=\"20\">";
echo "<br>";
echo "<input type=\"submit\" value=\"Submit\" name=\"B1\"></p>";
echo "</form>";
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
                       mail($_POST['email'],"Password Confirmation.","This is the confirmation code copy and paste it into the form provided" . "\n" . $ticket,$headers);
                       echo "An email has been sent with the confirmation code.";
                       } else {
                       mail($_POST['email'],"Password Confirmation.","This is the confirmation code copy and paste it into the form provided" . "\n" . $tmpcode,$headers);
                       echo "An email has been sent with the confirmation code.";
                       }
                       echo "<br>Paste the confirmation code in the box below.";
                       echo "<form method=\"POST\" action=\"passwordreset.php?step=3\">";
                       echo "<input type=\"text\" name=\"vcode\" size=\"20\">";
                       echo "<br>";
                       echo "<input type=\"submit\" value=\"Submit\" name=\"B1\"></p>";
                       echo "</form>";
        } else {
               Echo "Error, Email address was not found.";
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
               echo "Please enter your new password";
               echo "<form method=\"POST\" action=\"passwordreset.php?step=4\">";
               echo "Old Password:";
               echo "<br>";
               echo "<input type=\"password\" name=\"oldpword\" size=\"20\">";
               echo "<br>";
               echo "Enter New Password:";
               echo "<br>";
               echo "<input type=\"password\" name=\"pword\" size=\"20\">";
               echo "<br>";
               echo "Confirm Password:";
               echo "<br>";
               echo "<input type=\"password\" name=\"cpword\" size=\"20\">";
               echo "<br><input type=\"hidden\" name=\"vcode\" size=\"20\" value=\"$vcode\">";
               echo "<br>";
               echo "<input type=\"submit\" value=\"Submit\" name=\"B1\"></p>";
               echo "</form>";
       } else {
               echo "The confirmation code you supplied is incorrect.<br>";
               }
}

if($step == 4){
$vcode = $_POST['vcode'];
$oldpword = $_POST['oldpword'];
$pword = $_POST['pword'];
$cpword = $_POST['cpword'];

$connection = mysql_connect($hostname, $user, $pass)
              or die(mysql_error());
$db = mysql_select_db($database, $connection)
      or die(mysql_error());

$sql = "SELECT password FROM $userstable
        WHERE vcode = '$vcode'";

$result = mysql_query($sql)
        or die ("Couldn't execute query.");

$num = mysql_num_rows($result);
       if($num == 1){
          $tmpcode = mysql_result($result,0);
                    if($oldpword == $tmpcode){
                                     if($pword == $cpword){
                                        $query = "UPDATE $userstable SET password = '$pword'
                                                  WHERE vcode = '$vcode'";
                                        $result2 = mysql_query($query)
                                                   or die ("Couldn't execute query.");
                                      } else {
                                        echo "Error, passwords don't match.";
                                        die;
                                      }
                                      $query3 = "UPDATE $userstable SET vcode = ''
                                                 WHERE vcode = '$vcode'";
                                      $result3 = mysql_query($query3)
                                                 or die ("Couldn't execute query.");
                                      echo "Success, Your password has been changed.";
                                      echo "<br><a href=\"login.php\">Back to login area</a>";
                    } else {
                      echo "Error, old password is inncorrect.";
                    }

        } else {
          header ('Location: passwordreset.php?step=1');
        }
}
?>