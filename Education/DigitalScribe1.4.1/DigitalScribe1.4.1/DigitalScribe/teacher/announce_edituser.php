<?
require("checkpass.php");
IF (isset($HTTP_GET_VARS['ID'])) {$ID = $HTTP_GET_VARS['ID'];}
ELSE {$ID = $HTTP_POST_VARS['ID'];}
?>
<HTML><HEAD><TITLE>Edit User</TITLE>
<LINK REL ="stylesheet" HREF="../style.css" TYPE="text/css">
 
<?
include("../header1.php");

require("../access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

$test = mysql_query("SELECT email, name, pass FROM ".$conf['tbl']['teachers']." WHERE ID = '$ID'");
WHILE($teachers = mysql_fetch_array($test)) {
$email = "$teachers[email]";
$name = "$teachers[name]";
$pass1 = "$teachers[pass]";
break;
                                            }
function update() {
global $newlast, $ID, $newemail, $newpass, $newpass2, $teacher, $email, $pass1, $name, $conf;
echo "<FORM METHOD=POST ACTION=announce_edituser.php>";
echo "You can change any of the information below:";

echo "<BR>Name: <INPUT TYPE=TEXT NAME=newlast VALUE=\"$name\" SIZE=30>";
echo "<BR>E-mail: <INPUT TYPE=TEXT NAME=newemail VALUE='$email' SIZE=30>";
echo "<BR>Password: <INPUT TYPE=PASSWORD NAME=newpass SIZE=10>";
echo "<BR>Password Again: <INPUT TYPE=PASSWORD NAME=newpass2 SIZE=10>";

echo "<INPUT TYPE=HIDDEN NAME=user VALUE=$user>";
echo "<INPUT TYPE=HIDDEN NAME=ID VALUE=$ID>";
echo "<INPUT TYPE=HIDDEN NAME=action VALUE=register>";
echo "<BR><INPUT TYPE=SUBMIT NAME=Submit VALUE='Update User Info'></FORM>";
                                            }

function error_message($msg) {
echo "<B>Error: $msg</B>";
update();
exit;
                             }

function edit_account() {
global $HTTP_POST_VARS, $newlast, $newemail, $newpass, $newpass2, $pass, $name, $email, $ID, $conf;


$newlast = $HTTP_POST_VARS['newlast'];
$newemail = $HTTP_POST_VARS['newemail'];
$newpass = $HTTP_POST_VARS['newpass'];
$newpass2 = $HTTP_POST_VARS['newpass2'];

IF (empty($newlast)) error_message("Enter your name.");
IF (empty($newemail)) error_message("Enter your e-mail address.");
IF (empty($newpass)) error_message("Enter your desired/current password.");
IF (strlen($newpass) < 4 ) error_message("Your password must be at least 4 characters long");
IF (empty($newpass2)) error_message("You must retype your password for verification.");
IF ($newpass != $newpass2) error_message("Your desired password and retyped password don't match.");


$newpass = md5($newpass);

$sql="UPDATE ".$conf['tbl']['teachers']." SET pass='$newpass', name='".addslash($newlast)."', email='$newemail' WHERE ID='$ID'";

mysql_query($sql);

echo "Hi ".deslash($newlast).", you have updated your account.";
echo "<BR>You may <A HREF=announceadmin.php>Continue on to the Announcement Admin
page</A>.";

}

switch($HTTP_POST_VARS['action']) {
    case register;
        edit_account();
    break;
    default;
        update();
    break;
                }
include("../footer.php");
?>
