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
    array(
          "error"=>"templates/".$template_name."/error.html",
          "email"=>"templates/".$template_name."/email.html",
          "success"=>"templates/".$template_name."/success.html"
          )
);
if ($action == feedback) {
mail($adminmail, "Feedback from $name", $message,
     "From: $mail\nReply-To: $mail\nX-Mailer: PHP/" . phpversion());
$t->set_var("MESSAGE", W_SENTWEB);
$t->pparse("success");
include "templates/footer.php";
die;
}

$t->set_var("LANGUAGE", $l);
$t->set_var("W_FEEDBACK", W_FEEDBACK);
$t->set_var("W_YOUR_NAME", W_YOUR_NAME);
$t->set_var("W_YOUR_MAIL", W_YOUR_MAIL);
$t->set_var("W_YOUR_MES", W_YOUR_MES);
$t->set_var("W_SEND_MES", W_SEND_MES);
$t->pparse("email");
include "templates/footer.php";
?>