<?
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 
include "header.php";
$pagetitle = "$sitename $split $txt_pmsend ($user)";

include "template.php";
include "timeconvert.php";

echo "<font class=\"header\">$txt_pmsend ($user)</font><p/>";

echo "<div id=\"central\"><div class=\"boxes\"><div class=\"boxbot\"><p/>";
if(isset($msgbody)) {
if($msgfrom == "") $err = 1;
if($msgsubj == "") $err = 1;
if($msgbody == "") $err = 1;
}
if(isset($err) || !isset($msgbody)) {
if(isset($err)) echo "$txt_complete<p/>";
echo "<form method=\"post\" action=\"mail.php?user=$user\"><table>";
echo "<tr><td>$txt_fromemail:</td><td><input type=\"text\" name=\"msgfrom\" value=\"$msgfrom\"/></td></tr>
<tr><td>$txt_subject:</td><td><input type=\"text\" name=\"msgsubj\" value=\"$msgsubj\"/></td></tr>
<tr><td valign=\"top\">$txt_message:</td><td><textarea cols=\"40\" rows=\"10\" name=\"msgbody\">$msgbody</textarea></td></tr>
<tr><td>&nbsp;</td><td><input type=\"submit\" value=\"$txt_pmsend\"/></td></tr>
</table></form>";
}
else {
$userq = mysql_query("SELECT useremail FROM ${table_prefix}users WHERE userid='$user' AND dispemail='1'");
$useremail = mysql_result($userq, 0, 0);
$msgfrom = stripslashes($msgfrom);
$msgsubj = stripslashes($msgsubj);
$msgbody = stripslashes($msgbody);

if(mysql_num_rows($userq) > 0) {
if(!@mail($useremail, $msgsubj, $msgbody, "From: $msgfrom")) echo "$txt_errsending<p/>";
else echo "$txt_pmsent<p/> $txt_cont";
}
else echo "$txt_errsending<p/>";
}
echo "</div></div></div>";

include "footer.php";
?>