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
    array("faq"=>"templates/".$template_name."/faq.html",
          "success"=>"templates/".$template_name."/success.html")
);

   $sql = "SELECT COUNT(*) as total FROM ".$mysql_faq;
   $result = mysql_query($sql);
   $trows = mysql_fetch_array($result);
   $faqnum = $trows[total];
   $sql = "SELECT * FROM ".$mysql_faq." order by question ASC";
   if ($faqnum == "0")
   {
   $mess=W_FAQ_UN."<a href=".$url."/email.php?l=".$l.">".W_SEND_Q."</a>";
   $t->set_var("MESSAGE", $mess);
   $t->pparse("success");
   include "templates/footer.php";
   die;
   }
   $result = mysql_query($sql);
   while ($i = mysql_fetch_array($result)) 
   {
   $t->set_var("FID", $i[fid]);
   $t->set_var("W_QUESTION", W_QUESTION);
   $t->set_var("W_ANSWER", W_ANSWER);
   $t->set_var("QUESTION", $i[question]);
   $t->set_var("ANSWER", $i[answer]);
   $t->parse("faq_cycle");
   }       
   $t->set_var("W_IF_DONT", W_IF_DONT);
   $t->set_var("FAQ", FAQ);
   $t->set_var("W_SEND_Q", W_SEND_Q);
   $t->set_var("C_URL", $url);
   $t->set_var("LANGUAGE", $l);
   $t->pparse("faq");
include "templates/footer.php";
?>