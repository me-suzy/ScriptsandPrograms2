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
          "view"=>"templates/".$template_name."/view.html")
);

if (isset($id) || $id != "") {
$sql = "SELECT * FROM ".$mysql_table." WHERE id = '".$id."'";
$result = mysql_query($sql);
while ($i = mysql_fetch_array($result)) {
if ($i[pic] != "") $picav = "<a href=\"".$i[pic]."\" target=\"_blank\"><img src=\"".$i[pic]."\" border=0 width=150></a>";
else $picav = "";



$t->set_var("PICAV", $picav);
$t->set_var("W_PROFILE", W_PROFILE);
$t->set_var("USER", $i[user]);
$t->set_var("W_USERNAME", W_USERNAME);
$t->set_var("COUNTRY", $i[country]);
$t->set_var("W_COUNTRY", W_COUNTRY);
$t->set_var("CITY", $i[city]);
$t->set_var("W_CITY", W_CITY);
$t->set_var("GENDER", $langgender[$i[gender]]);
$t->set_var("PURPOSES", $langpurposes[$i[purposes]]);
$t->set_var("W_CATEGORY", W_CATEGORY);
$t->set_var("HEIGHT", $i[height]);
$t->set_var("W_HEIGHT", W_HEIGHT);
$t->set_var("WEIGHT", $i[weight]);
$t->set_var("W_WEIGHT", W_WEIGHT);
$t->set_var("AGE", $i[age]);
$t->set_var("W_AGE", W_AGE);
$t->set_var("HOBBY", $i[hobby]);
$t->set_var("W_HOBBY", W_HOBBY);
$t->set_var("DESCR", $i[Description]);
$t->set_var("W_DESCR", W_DESCR);
$t->set_var("W_MAIL", W_MAIL);
$t->set_var("LANGUAGE", $l);
$t->set_var("ID", $i[id]);
$t->set_var("W_SENT_MES_TO", W_SENT_MES_TO);

if ($popcheck == "1")
{
// move to perem
$gender = $i[gender];
$city = $i[city];
$purposes = $i[purposes];
$country = $i[country];
$age = $i[age];
$user = $i[user];
$pic = $i[pic];

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
//////////////
    }
}      

   $sql = "SELECT COUNT(*) as total FROM ".$mysql_hits." WHERE id = '".$id."'";
   $result = mysql_query($sql);
   $trows = mysql_fetch_array($result);
   $nohits = $trows[total];
   $sql = "SELECT hits FROM ".$mysql_hits." WHERE id = '".$id."'";
   $result = mysql_query($sql);
            if ($nohits == "0")
              {
              $hits = 0;
              }
              else
              {
                    while ($i = mysql_fetch_array($result)) 
                    {
                    $hits = $i[hits];
                    }
              }       
$t->set_var("HITS", $hits);
$t->set_var("W_POPULARITY", W_POPULARITY);
$t->pparse("view");

              
}
} else {
$t->set_var("ERROR", W_NO_ID_SET);
$t->pparse("error");
include "templates/footer.php";
die;
}
include "templates/footer.php";
?>