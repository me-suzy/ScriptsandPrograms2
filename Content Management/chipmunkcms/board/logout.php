<?PHP
session_start();
include "admin/var.php";
$cookie1="[0]";
$cookie2="[1]";
$cookie3="$cookiename$cookie1";
$cookie4="$cookiename$cookie2";
setcookie("$cookie3","$memberid",time()-7776000) ;
setcookie("$cookie4","$passkey",time()-7776000) ;
session_destroy();
?>
<?PHP
  print "<center>";
  print "<link rel='stylesheet' href='style.css' type='text/css'>";
  print "<table class='maintable'>";
  print "<tr class='headline'><td><center>Logging In</center></td></tr>";
  print "<tr class='forumrow'><td><center>";           
  print "Logged out successfully";
  print "</td></tr></table></center>";
  

?>