<HTML><HEAD><TITLE>Register</TITLE>
<LINK REL ="stylesheet" HREF="style.css" TYPE="text/css">

<?
include("header1.php");

echo '<span class=title>Register</span><P>';

function type() {
?><P>
I would like a:
<ul>

<li><A HREF=register.php?action=5&type=t>Teacher Account</A>
<li><A HREF=register.php?action=5&type=a>Announcement Account</A>
</ul>
<?
include("footer.php");
}

function register_form() {
global $user, $email, $last, $title, $passuno, $passuno2, $HTTP_POST_VARS, $HTTP_GET_VARS;
include("access.inc.php");
?>
<FORM METHOD=POST ACTION=register.php>
<table><tr><td align=right>
Title:</td><td><INPUT TYPE=TEXT NAME=title VALUE="<? echo (deslash($HTTP_POST_VARS['title'])); ?>" SIZE=4> (Mr., Mrs., etc)
</td></tr>
<tr><td align=right>Last Name:</td><td><INPUT TYPE=TEXT NAME=last VALUE="<? echo (deslash($HTTP_POST_VARS['last'])); ?>"></td></tr>
<tr><td align=right>Password:</td><td><INPUT TYPE=PASSWORD NAME=passuno></td></tr>
<tr><td>
Password Again:</td><td><INPUT TYPE=passWORD NAME=passuno2></td></tr>
<tr><td align=right>
E-Mail:</td><td><INPUT TYPE=TEXT NAME=email SIZE=34 VALUE=<? echo $HTTP_POST_VARS['email'] ?>></td>
</tr><tr><td>
<?
IF($HTTP_GET_VARS['type']=='a' OR $HTTP_POST_VARS['announcements']==4) {
echo '<INPUT TYPE=HIDDEN NAME=announcements VALUE=4>';
}


?>
<P>
<INPUT TYPE=HIDDEN NAME=action VALUE=4>
</td><td>
<INPUT TYPE=SUBMIT NAME=Submit VALUE=Register></FORM>
</td></tr>
</table>

<?
include("footer.php");
}

function error_message($msg) {
echo "<B>$msg</B>";
register_form();
exit;
                                }

function create_account() {
global $user, $last, $title, $email, $passuno, $passuno2, $HTTP_POST_VARS;
IF (empty($HTTP_POST_VARS['title'])) error_message("Enter your title.");
IF (empty($HTTP_POST_VARS['last'])) error_message("Enter your last name.");
IF (empty($HTTP_POST_VARS['email'])) error_message("Enter your e-mail address.");
IF (empty($HTTP_POST_VARS['passuno'])) error_message("Enter your desired Password.");
IF (strlen($HTTP_POST_VARS['passuno']) < 4 ) error_message("Your Password must be at least 4
characters long");
IF (empty($HTTP_POST_VARS['passuno2'])) error_message("You must retype your password for
verification.");
IF ($HTTP_POST_VARS['passuno'] != $HTTP_POST_VARS['passuno2']) error_message("Your desired password and retyped password don't match.");

$last = $HTTP_POST_VARS['last'];
$name = "$HTTP_POST_VARS[title] $HTTP_POST_VARS[last]";

include("access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator. " .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator. " .mysql_error());


$i=0;
$true=0;
$orig_last = $last;
do {
    $sql = "SELECT COUNT(*) FROM ".$conf['tbl']['teachers']." WHERE user = '".addslash($last)."'";
    $result = mysql_query($sql);
      if (@mysql_result($result,0,0)>0) {
	$i++;
        $last = "$orig_last".$i;
      }
	
	else {
	  $true=1;
	  //echo "Username is now $last.<BR>";
	}
} while (!$true==1);


$HTTP_POST_VARS['passuno'] = MD5($HTTP_POST_VARS['passuno']);

if (isset($HTTP_POST_VARS['announcements'])) {
mysql_query("INSERT INTO ".$conf['tbl']['teachers']." (user, pass, name, email,level) VALUES
('".addslash($last)."','$HTTP_POST_VARS[passuno]','".addslash($name)."','$HTTP_POST_VARS[email]','5')");
}

else {
mysql_query("INSERT INTO ".$conf['tbl']['teachers']." (user, pass, name, email,level) VALUES
('".addslash($last)."','$HTTP_POST_VARS[passuno]','".addslash($name)."','$HTTP_POST_VARS[email]','0')");
}

echo "Hi ".deslash($name).", thank you for registering.";
echo "<BR>Your account is now awaiting administrative approval.";
echo "<BR>You will be e-mailed with your user name (usualy your last name) once you are approved.";
include("footer.php");

//send e-mail to admin
$amail = mysql_query("SELECT email,ID FROM ".$conf['tbl']['teachers']." WHERE ID=1");
WHILE($a = mysql_fetch_array($amail)) {
$rp    = $a['email'];
}

$mail_to = "$rp";
$mail_subject = "Digital Scribe Account Requesting Approval";
$mail_body = "Someone has requested an account in the Digital Scribe.\r\nYou must login to approve or deny the request. \r\n";

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


} //end func creat account


switch(@$_REQUEST['action']) {
    case 4:
        create_account();
    break;
    case 5:
	register_form();
    break;
    default:
        type();
                }

?>