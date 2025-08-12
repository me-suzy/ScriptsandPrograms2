<HTML><HEAD><TITLE>Forgot Your Password</TITLE>
<LINK REL ="stylesheet" HREF="style.css" TYPE="text/css">

<?
include("header1.php");
echo '<span class=title>Forgot Your Password</span><P>';
function sendemail() {
?>
<FORM TYPE=GET ACTION=forgot.php>
<P>Your password will be e-mailed to you.

<BR>E-Mail: <INPUT TYPE=TEXT NAME=email SIZE=35>
<INPUT TYPE=HIDDEN NAME=Submit2 VALUE=1>
<BR><INPUT TYPE=SUBMIT NAME=Submit VALUE='Retrieve Password'></FORM>
<?

            }


IF (!$HTTP_GET_VARS[Submit2]) {
sendemail();
                }
ELSEIF (!$HTTP_GET_VARS[email] AND $HTTP_GET_VARS[Submit2] == 1) {
echo "Please enter your e-mail address.";
sendemail();
                    }

ELSE {
require("access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());


$newmd5=substr(md5(microtime()), 0, 6);
$newpass = md5($newmd5);
  $sql="UPDATE ".$conf['tbl']['teachers']." SET pass='$newpass' WHERE email='$HTTP_GET_VARS[email]'";
 mysql_query($sql);

$amail = mysql_query("SELECT email,ID FROM ".$conf['tbl']['teachers']." WHERE ID=1");
WHILE($a = mysql_fetch_array($amail)) {
$rp    = $a['email'];
}


$show = mysql_query("SELECT name,user,email from ".$conf['tbl']['teachers']." where email = '$HTTP_GET_VARS[email]'");

echo "No luck?  Please <A HREF=forgot.php>try again</A>.";

WHILE($teachers = mysql_fetch_array($show)) {
$teachers['name'] = deslash($teachers['name']);
$teachers['user'] = deslash($teachers['user']);

$mail_to = "$teachers[email]";
$mail_subject = "Your School Password";
$mail_body = "Dear $teachers[name],\r\n";
$mail_body .= "Your login name is $teachers[user] and your password is now $newmd5.\r\n It is recommended that you login and change it.";

$headers  = '';
  $headers  .= "Content-Type: text/plain \r\n";
  $headers  .= "Date: ". date('r'). " \r\n";
  $headers  .= "Return-Path: $rp \r\n";
  $headers  .= "From: $rp \r\n";
  $headers  .= "Sender: $rp \r\n";
  $headers  .= "Reply-To: $rp \r\n";
  $headers  .= "Organization: Digital Scribe \r\n";
  $headers  .= "X-Sender: $rp \r\n";
  $headers  .= "X-Priority: 3 \r\n";
  $headers  .= "X-Mailer: php\r\n";


mail($mail_to, $mail_subject, $mail_body, $headers);

echo "<P><B>Don't worry $teachers[name], your password has been sent to you.</B>";
}    }       
include("footer.php");
?>