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
include "config.inc.php";
include "templates/secure.php";
include "templates/header.php";

$t = new Template;

$t->set_file(
    array("error"=>"templates/".$template_name."/error.html",
          "remind"=>"templates/".$template_name."/remind.html",
          "success"=>"templates/".$template_name."/success.html")
);
if ($page == "remind")
{
   if ($username == "" && $email == "") 
      {
      $t->set_var("ERROR", W_FILL);
      $t->pparse("error");
      include "templates/footer.php";
      die;
      }
   elseif ($username != "")
      {
      $sql = "SELECT user, password, email FROM $mysql_table WHERE user = '$username'";
      $result = mysql_query($sql);
         while ($i = mysql_fetch_array($result)) {
         $headers="Content-Type: text/html; charset=".$langcharset."\n";
         $headers.="From: $from_mail\nX-Mailer: AzDGDatingGold v3.0.5";
         $body = $body1.$i[user].$body2.$i[password].$body3;
         mail($i[email],$newm,$body,$headers);

         $t->set_var("MESSAGE", W_PASS_SEND);
         $t->pparse("success");
         include "templates/footer.php";
         die;
         }
         $t->set_var("ERROR", W_NO_USER);
         $t->pparse("error");
         include "templates/footer.php";
         die;
       } 
   elseif ($email != "")
      {
      $sql = "SELECT user, password, email FROM $mysql_table WHERE email = '$email'";
      $result = mysql_query($sql);
         while ($i = mysql_fetch_array($result)) {
         $headers="Content-Type: text/html; charset=".$langcharset."\n";
         $headers.="From: $from_mail\nX-Mailer: AzDGDatingGold v3.0.5";
         $body = $body1.$i[user].$body2.$i[password].$body3;
         mail($i[email],$newm,$body,$headers);

         $t->set_var("MESSAGE", W_PASS_SEND);
         $t->pparse("success");
         include "templates/footer.php";
         die;
         }
         $t->set_var("ERROR", W_NO_MAIL);
         $t->pparse("error");
         include "templates/footer.php";
         die;
       } 
}
else 
{
$t->set_var("W_USERNAME", W_USERNAME);
$t->set_var("W_ENTER", W_ENTER);
$t->set_var("W_FORGET", W_FORGET);
$t->set_var("W_FILL", W_FILL);
$t->set_var("W_OR_MAIL", W_OR_MAIL);
$t->set_var("LANGUAGE", $l);
$t->pparse("remind");

include "templates/footer.php";
}
?>