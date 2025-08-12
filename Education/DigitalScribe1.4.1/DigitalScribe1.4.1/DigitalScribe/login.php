<?
if(@$HTTP_GET_VARS['logout']==1) { //log out user
session_start();
$HTTP_SESSION_VARS = array();
session_destroy();
}


if(isset($HTTP_POST_VARS['submit']) ) {     // true if form has been submitted

   require("access.inc.php");
   mysql_connect("$host","$login","$pass") OR DIE
      ("There is a problem with the system.  Please notify your system administrator." .mysql_error());
   mysql_select_db("$db") OR DIE
      ("There is a problem with the system.  Please notify your system administrator." .mysql_error());
 
$user=sanitize_paranoid_string($HTTP_POST_VARS[username]);
$mdpass=MD5($HTTP_POST_VARS['pass1']);
$query = "SELECT ID,level FROM ".$conf['tbl']['teachers']." where pass='$mdpass' AND user=\"$user\"";

   $result = mysql_query($query);
   $query_data = mysql_fetch_row($result);

      IF (!$query_data[0]) { 
      $error = "You have submited an incorrect login and password combination.  Please try again.";
      }

   ELSE {

      session_start();

      $HTTP_SESSION_VARS['secure_id']=$query_data[0];            
      $HTTP_SESSION_VARS['secure_level']=$query_data[1];  

      IF($HTTP_SESSION_VARS['secure_level']==1) {
         header("location: admin/admin.php");
      }
      IF($HTTP_SESSION_VARS['secure_level']==2) {
         $error = "Your account has been de-activated.  Please contact the system administrator for details.";
      }
        IF($HTTP_SESSION_VARS['secure_level']==6) {
         $error = "Your account has been de-activated.  Please contact the system administrator for details.";
      }
      IF($HTTP_SESSION_VARS['secure_level']==0 || $HTTP_SESSION_VARS['secure_level']==5) {
         $error = "Your account has yet to be activated.  Please contact the system administrator for details.";  
      }
       IF($HTTP_SESSION_VARS['secure_level']==4) {
        header("location: teacher/announceadmin.php");  
      }
      IF ($HTTP_SESSION_VARS['secure_level']==3) {   
        header("location: teacher/teacheradmin.php");
      }
   }

}
      ?>

<HTML><HEAD><TITLE>Login Page</TITLE>
<LINK REL ="stylesheet" HREF="style.css" TYPE="text/css">
<?
include("header1.php");
echo '<span class=title>Login</span><P>';

IF (isset($HTTP_GET_VARS['error'])) { echo "<P><B>$HTTP_GET_VARS[error]</B>"; }

IF (isset($error)) { echo "<P><B>$error</B>"; }

//ELSE { echo "<P><B>$error</B>"; }

echo "<FORM METHOD=POST ACTION=login.php>";

?>
<P><TABLE><TR><TD>
User Name:</TD><TD><INPUT TYPE=TEXT NAME=username WIDTH=20></TD></TR>
<TR><TD>Password:</TD><TD>
<INPUT TYPE=password NAME=pass1 WIDTH=20></TD></TR>
<TR><TD COLSPAN=2>
<INPUT TYPE=SUBMIT VALUE=Login NAME=submit>
<BR><A HREF=forgot.php?Submit2=1&email=>Forgot your password</A>?
<BR><A HREF=register.php>Need to register</A>?
</TD></TR></TABLE>
</FORM>
<?
include("footer.php");

//function from http://www.owasp.org/software/labs/phpfilters.html
function sanitize_paranoid_string($string, $min='', $max='') 
{ 
$string = preg_replace("/[^a-zA-Z0-9]/", "", $string); 
$len = strlen($string); 
if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max))) 
return FALSE; 
return $string; 
} 
?>