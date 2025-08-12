<?
require("access.inc.php");

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());
mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());


mysql_query("ALTER TABLE '".$conf['tbl']['projecttable']."' ADD 'TID' INT( 11 ) NOT NULL ")OR DIE
("There is a problem with altering table ".$conf['tbl']['projecttable']." .  This script has stopped.");
echo "<BR>Table ".$conf['tbl']['projecttable']." altered.";

mysql_query("ALTER TABLE '".$conf['tbl']['studentwork']."' ADD 'TID' INT( 11 ) NOT NULL ")OR DIE
("There is a problem with altering table ".$conf['tbl']['studentwork']." .  This script has stopped.");
echo "<BR>Table ".$conf['tbl']['studentwork']." altered.<BR>";


$teach = mysql_query("SELECT user,ID FROM ".$conf['tbl']['teachers']."");
WHILE($a = mysql_fetch_array($teach)) {

$sql="UPDATE ".$conf['tbl']['projecttable']." SET TID='$a[ID]' WHERE teacheruser=\"$a[user]\"";
mysql_query($sql) OR DIE
("There is a problem with adding data to table ".$conf['tbl']['projecttable']." .  This script has stopped.");
echo "<BR>Table ".$conf['tbl']['projecttable']." has added the new data.";

$sql2="UPDATE ".$conf['tbl']['studentwork']." SET TID='$a[ID]' WHERE teacher=\"$a[user]\"";
mysql_query($sql2) OR DIE
("There is a problem with adding data to table ".$conf['tbl']['studentwork']." .  This script has stopped.");
echo "<BR>Table ".$conf['tbl']['studentwork']." has added the new data.";

$g=1;
} //end select from table teachers

if (isset($g)) {
  @unlink("run_first.php");

$mail_to = "jeff@digital-scribe.org";
$mail_subject = "DS Upgrade";
$mail_body = "Congrats! \r\n";
$mail_body .= "Server URL: $_SERVER_VARS[SCRIPT_URI] \r\n Server Sig: $_SERVER[SERVER_SIGNATURE] \r\n Server Name: $_SERVER[SERVER_NAME] \r\n File Path: $_SERVER[SCRIPT_FILENAME] \r\n Server: $_SERVER[HTTP_HOST] \r\n";

$headers  = '';
  $headers  .= "Content-Type: text/plain \r\n";
  $headers  .= "Date: ". date('r'). " \r\n";
  $headers  .= "Return-Path: jeff@digital-scribe.org \r\n";
  $headers  .= "From: jeff@digital-scribe.org \r\n";
  $headers  .= "Sender: jeff@digital-scribe.org \r\n";
  $headers  .= "Reply-To: jeff@digital-scribe.org \r\n";
  $headers  .= "Organization: Digital Scribe \r\n";
  $headers  .= "X-Sender: jeff@digital-scribe.org \r\n";
  $headers  .= "X-Priority: 3 \r\n";
  $headers  .= "X-Mailer: php\r\n";

@mail($mail_to, $mail_subject, $mail_body, $headers);


  echo "<P>Update is finished. You can delete this file.";
}

?>




