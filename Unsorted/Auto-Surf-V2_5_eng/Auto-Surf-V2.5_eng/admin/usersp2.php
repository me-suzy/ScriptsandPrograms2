<?
include("header.inc.php");
require('../prepend.inc.php');
?>
<?
$email_gesperrt_title="Your site has been locked";
$email_gesperrt="Dear user\n\nthe website you used at $seitenname was not according to our rules.\nYour account is now in safe mode and you can add a new URL anytime you want.\n\nYours $seitenname";
$email_header="From: $seitenname < $emailadresse >";

global $email;
        $ask= "UPDATE `demo_a_accounts` SET `url` = 'sparen', savepoints=1, showup=1 WHERE email='$email'";
        $result = mysql_query($ask) or die(mysql_error());

mail($email, $email_gesperrt_title, $email_gesperrt, $email_header);
?>
<?
include("../templates/admin-header.txt");
?>
<center><font size=3>Website of User with e-mail <php echo $email; ?> was locked</center>
