<?php
##################################################################
# \-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/ #
##################################################################
# AzDGDatingGold                Version 3.0.5                     #
# Status                        Paid                             #
# Writed by                     AzDG (support@azdg.com)          #
# Created 21/09/02              Last Modified 21/09/02           #
# Scripts Home:                 http://www.azdg.com              #
##################################################################
$sql = "SELECT id, user, password FROM ".$mysql_table." WHERE user = '".$username."'";
$result = mysql_query($sql);
$i = mysql_fetch_array($result);
if ($i == "") {
include "../templates/header.php";
$t = new Template;
$t->set_file("../templates/".$template_name."/error.html");
$t->set_var("ERROR", W_NO_USER);
$t->pparse("error");
include "../templates/footer.php";
die;
} elseif ($i != "") {
if ($password != md5(stripslashes($i[password]))) {
include "../templates/header.php";
$t = new Template;
$t->set_file("../templates/".$template_name."/error.html");
$t->set_var("ERROR", W_PASS_INC);
$t->pparse("error");
include "../templates/footer.php";
die;
}
   $checkid = $i[id];
   session_start();
   unset($s);
   if (!isset($_SESSION["s"]))  
   { 
      include "../templates/header.php";
      $t = new Template;
      $t->set_file("../templates/".$template_name."/error.html");
      $t->set_var("ERROR", W_SESS_EXP);
      $t->pparse("error");
      include "../templates/footer.php";
      session_destroy();
      unset($s); 
      die;
   }
}
?>