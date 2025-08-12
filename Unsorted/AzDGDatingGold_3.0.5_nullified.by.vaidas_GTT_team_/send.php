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
          "send"=>"templates/".$template_name."/send.html",
          "success"=>"templates/".$template_name."/success.html")
);

if (isset($id)) {
if ($page == send) {


$sql = "SELECT id, user, password FROM ".$mysql_table." WHERE user = '".$login."'";
$result = mysql_query($sql);
$i = mysql_fetch_array($result);

if ($i == "") {
$t->set_var("ERROR", W_NO_USER);
$t->pparse("error");
include "templates/footer.php";
die;
} elseif ($i != "") {
if ($password != $i[password]) {
$t->set_var("ERROR", W_PASS_INC);
$t->pparse("error");
include "templates/footer.php";
die;
}
}

// Check for fill

$subject = check_bad_chars($subject);
$message = check_bad_chars($message);

if (empty($subject) || $subject == "") 
{
$t->set_var("ERROR", W_ENTER_SUB);
$t->pparse("error");
include "templates/footer.php";
die;
}

if (empty($message) || $message == "") 
{
$t->set_var("ERROR", W_ENTER_MES);
$t->pparse("error");
include "templates/footer.php";
die;
}
if ($confirm == "on") $confirm = "1";
else $confirm = "0";
$readed = "0";
$time = time();

$sql = "INSERT INTO ".$mysql_messages." (mid, fromid, fromuser, toid, touser, subject, message, sendtime, confirm, readed) VALUES ('', '".$i[id]."', '".$login."', '".$id."', '".$touser."', '".$subject."', '".$message."', '".$time."', '".$confirm."', '".$readed."')";
$result = mysql_query($sql);

// Popularity section begin
/////////////////////////////////////////
if ($popcheck == "2")
{
   $sql = "SELECT * FROM ".$mysql_table." WHERE id = '".$id."'";
   $result = mysql_query($sql);
   while ($i = mysql_fetch_array($result)) 
   {
       // move to perem
       $gender = $i[gender];
       $city = $i[city];
       $purposes = $i[purposes];
       $country = $i[country];
       $age = $i[age];
       $user = $i[user];
       $pic = $i[pic];
   }
       // add hits to hits table
       $sql = "SELECT COUNT(*) as total FROM ".$mysql_hits." WHERE id = '".$id."'";
       $result = mysql_query($sql);
$trows = mysql_fetch_array($result);
$hits = $trows[total];
if ($hits == "0")
   {
   $hits = 1;
   $sql = "INSERT INTO ".$mysql_hits." (id, ip, user, gender, city, country, purposes, age, pic, hits) VALUES ('".$id."', INET_ATON('".ip()."'), '".$user."', '".$gender."', '".$city."', '".$country."', '".$purposes."', '".$age."', '".$pic."', '".$hits."')";
   mysql_query($sql);
   }
   else
   {
//////////////
   $sql = "SELECT * FROM ".$mysql_hits." WHERE id = '".$id."'";
   if ($for_all_ip != "1")
      {
      $sql .= " AND ip != INET_ATON('".ip()."')";
      }
   $result = mysql_query($sql);
   while ($i = mysql_fetch_array($result)) 
      {
         $hits = $i[hits] + 1;
         $sql = "UPDATE ".$mysql_hits." SET hits='".$hits."', ip=INET_ATON('".ip()."') WHERE id = '".$id."'";
         mysql_query($sql);
      }
    }
}      
///////////////////////////////////////////
// Popularity section end


        $t->set_var("MESSAGE", W_MES_SENT);
        $t->pparse("success");
        include "templates/footer.php";
        die;
} else {
$t->set_var("LANGUAGE", $l);
$t->set_var("ID", $id);
$t->set_var("TOUSER", $user);
$t->set_var("W_SENT_MES_TO", W_SENT_MES_TO);
$t->set_var("W_SUBJECT", W_SUBJECT);
$t->set_var("W_YOUR_MES", W_YOUR_MES);
$t->set_var("W_YOUR_LOGIN", W_YOUR_LOGIN);
$t->set_var("W_YOUR_PASS", W_YOUR_PASS);
$t->set_var("W_MUST_REG", W_MUST_REG);
$t->set_var("W_READ_MES", W_READ_MES);
$t->set_var("W_NOTIFY_", W_NOTIFY_);
$t->set_var("W_SEND_MES", W_SEND_MES);
$t->pparse("send");
}
} 
include "templates/footer.php";
?>