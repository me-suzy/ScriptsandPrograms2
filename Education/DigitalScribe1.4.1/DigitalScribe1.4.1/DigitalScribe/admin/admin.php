<?
require("checkpass2.php");
require("../teacher/checkpass.php");

require("../access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

if ($HTTP_GET_VARS['mode']==delete) { //delete unapproved teacher account
   mysql_query("DELETE FROM ".$conf['tbl']['teachers']." WHERE ID=$HTTP_GET_VARS[ID]");
}
if ($HTTP_GET_VARS['mode']==approve) { //approve new teacher
  $sql="UPDATE ".$conf['tbl']['teachers']." SET level=3 WHERE ID=$HTTP_GET_VARS[ID]";
 mysql_query($sql);

   $show = mysql_query("SELECT name,user,email from ".$conf['tbl']['teachers']." WHERE ID=$HTTP_GET_VARS[ID]");
   WHILE($teachers = mysql_fetch_array($show)) {
     $mail_to = "$teachers[email]";
     $mail_subject = "Your School Account";
     $mail_body = "Dear ".deslash($teachers['name']).",\n";
     $mail_body .= "Your account has been approved.  Your login name is ".deslash($teachers['user']).".\n";
     mail($mail_to, $mail_subject, $mail_body);
   }
}//end approve teacher login

if ($HTTP_GET_VARS['mode']==anouncements) { //approve announcements login
  $sql="UPDATE ".$conf['tbl']['teachers']." SET level=4 WHERE ID=$HTTP_GET_VARS[ID]";
 mysql_query($sql);

   $show = mysql_query("SELECT name,user,email from ".$conf['tbl']['teachers']." WHERE ID=$HTTP_GET_VARS[ID]");
   WHILE($teachers = mysql_fetch_array($show)) {
     $mail_to = "$teachers[email]";
     $mail_subject = "Your School Account";
     $mail_body = "Dear ".deslash($teachers['name']).",\n";
     $mail_body .= "Your account has been approved.  Your login name is ".deslash($teachers['user']).".\n";
     mail($mail_to, $mail_subject, $mail_body);
   }
}//end approve announcements login

if ($HTTP_GET_VARS['mode']==password) { //create new teacher password

$newmd=substr(md5(microtime()), 0, 6);
$newpass = md5($newmd);
  $sql="UPDATE ".$conf['tbl']['teachers']." SET pass='$newpass' WHERE ID=$HTTP_GET_VARS[ID]";
 mysql_query($sql);
echo "<P>Hi! $newmd";

$amail = mysql_query("SELECT email,ID FROM ".$conf['tbl']['teachers']." WHERE ID=1");
WHILE($a = mysql_fetch_array($amail)) {
$rp    = $a['email'];
}

   $show = mysql_query("SELECT name,user,email from ".$conf['tbl']['teachers']." WHERE ID=$HTTP_GET_VARS[ID]");
   WHILE($teachers = mysql_fetch_array($show)) {

$mail_to = "$teachers[email]";
     $mail_subject = "Your School Account";
     $mail_body = "Dear ".deslash($teachers['name']).",\r\n";
     $mail_body .= "Your login name is ".deslash($teachers['user'])." and your new password is $newmd.  Due to the insecure nature of e-mail, it's suggested that you login and change your password.\r\n";

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
  $headers  .= "X-Mailer: php \r\n";

mail($mail_to, $mail_subject, $mail_body, $headers);
   }
}

IF ($HTTP_GET_VARS[activate]==1) { //activate a teacher
    mysql_query("UPDATE ".$conf['tbl']['teachers']." SET level='3' WHERE ID=$HTTP_GET_VARS[ID]");
}

IF ($HTTP_GET_VARS[activate]==4) { //activate a announcer
    mysql_query("UPDATE ".$conf['tbl']['teachers']." SET level='4' WHERE ID=$HTTP_GET_VARS[ID]");
}
IF ($HTTP_GET_VARS[activate]==6) { //de-activate announcer
    mysql_query("UPDATE ".$conf['tbl']['teachers']." SET level='6' WHERE ID=$HTTP_GET_VARS[ID]");
}
IF ($HTTP_GET_VARS[activate]==2) { //de-activate a teacher
    mysql_query("UPDATE ".$conf['tbl']['teachers']." SET level='2' WHERE ID=$HTTP_GET_VARS[ID]");
}

?>
<HTML><HEAD><TITLE>Administration</TITLE>
<LINK REL ="stylesheet" HREF="../style.css" TYPE="text/css">

<?
include("../header1.php");

echo '<span class=title>Administration</span><P>';

echo "<P><A HREF=files.php>Edit the Templates</A> - <A HREF=adminarchive.php>Archive a Project</A> - <A HREF=changegrades.php>Change Grade Levels in School</A><BR><A HREF=changepass.php>Change Your Password & E-mail Address</A> - <A HREF=../login.php?logout=1>Logout<A> ";


if ($HTTP_GET_VARS['mode']==password) {
echo "<P><CENTER><span class=title>Password E-mailed to Teacher</span></CENTER>";
}



echo "<P><TABLE BORDER=1><TR><TD COLSPAN=6 ALIGN=CENTER><B>Users Awaiting Approval</B></TD></TR>";
echo "<TR ALIGN=CENTER><TD>Approve</TD><TD>Name</TD><TD>User name</TD><TD>E-Mail</TD><td>Account Type</td><TD>Delete</TD></TR>";

$admin = mysql_query("SELECT * from ".$conf['tbl']['teachers']." WHERE level=5 OR level=0");
WHILE ($newteach = mysql_fetch_array($admin)) {

IF($newteach['level']==0) {$mode=approve; $atype=Teacher;}
ELSE {$mode=anouncements; $atype=Announcement;}


echo "<TR ALIGN=CENTER><TD><A HREF=admin.php?ID=$newteach[ID]&mode=$mode>Yes</A></TD><TD>".deslash($newteach['name'])."</TD><TD>".deslash($newteach['user'])."</TD><TD>$newteach[email]</TD><td>$atype</td><TD><A HREF=admin.php?ID=$newteach[ID]&mode=delete>Delete</A></TD></TR>";

}
echo "</TABLE>";

echo "<P><TABLE BORDER=1><TR><TD COLSPAN=7 ALIGN=CENTER><B>Approved Users</B></TD></TR>";
echo "<TR ALIGN=CENTER><TD>GHOST</TD><TD>Name</TD><TD>User Name</TD><TD>E-Mail</TD><TD>Password</TD><td>Account Type</td><TD>De-Activate</TD></TR>";

$list = mysql_query("SELECT * from ".$conf['tbl']['teachers']." WHERE level=3 OR level=4");
WHILE ($tlist = mysql_fetch_array($list)) {

IF($tlist['level']==4) { $activate=6; } //de-activate announcer
IF($tlist['level']==3) { $activate=2; } //de-activate teacher

IF($tlist['level']==4) {$announce=1; $atype=Announcement;}
ELSE { $announce=0; $atype=Teacher;}

echo "<TR ALIGN=CENTER><TD><A HREF=ghost.php?ID=$tlist[ID]&announce=$announce target='_blank'>GHOST</A></TD><TD>".deslash($tlist['name'])."</TD><TD>".deslash($tlist['user'])."</TD><TD>$tlist[email]</TD><TD><A HREF=admin.php?mode=password&ID=$tlist[ID]>Create New</A></TD><td>$atype</td><TD><A HREF=admin.php?ID=$tlist[ID]&activate=$activate>Yes</A></TD></TR>";


}
echo "</TABLE>";


echo "<P><TABLE BORDER=1><TR><TD COLSPAN=7 ALIGN=CENTER><B>De-Activated Users</B></TD></TR>";
echo "<TR ALIGN=CENTER><TD>Name</TD><TD>User Name</TD><TD>E-Mail</TD><TD>Activate</TD></TR>";

$list = mysql_query("SELECT * from ".$conf['tbl']['teachers']." WHERE level=2 || level=6");
WHILE ($tlist = mysql_fetch_array($list)) {

IF($tlist[level]==2) { $active="<A HREF=admin.php?ID=$tlist[ID]&activate=1>Yes"; }
ELSE { $active="<A HREF=admin.php?ID=$tlist[ID]&activate=4>Yes"; }

echo "<TR ALIGN=CENTER><TD>".deslash($tlist['name'])."</TD><TD>".deslash($tlist['user'])."</TD><TD>$tlist[email]</TD><TD>$active</A></TD></TR>";


}
echo "</TABLE>";


include("../footer.php");
?>