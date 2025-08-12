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
include "../config.inc.php";
include "../templates/secure.php";
include "user.php";
include "../templates/header.php";
$t = new Template;
$t->set_file(
    array("error"=>"../templates/".$template_name."/error.html",
          "members_menu"=>"../templates/".$template_name."/members_menu.html",
"members_edit"=>"../templates/".$template_name."/members_edit.html",
"message"=>"../templates/".$template_name."/message.html",
"messages"=>"../templates/".$template_name."/messages.html",
          "success"=>"../templates/".$template_name."/success.html")
);

if ($page == "update") {

//   Page updating
//////////////////////////
//////////////////////////

// checking for bad symbols 

$email = check_bad_chars($email);
$city = check_bad_chars($city);
$country = check_bad_chars($country);
$hobby = check_bad_chars($hobby);
$Description = check_bad_chars($Description);

if (empty($gender) || $gender == "") 
{
$t->set_var("ERROR", W_BADGEN);
$t->pparse("error");
include "../templates/footer.php";
die;
}

if ( $gender != "1" && $gender != "2") 
{
$t->set_var("ERROR", W_BADGEN2);
$t->pparse("error");
include "../templates/footer.php";
die;
}

if (empty($email) || $email == "") {
$t->set_var("ERROR", W_BADMAIL);
$t->pparse("error");
include "../templates/footer.php";
die;
}

if (check_email_addr($email) == 0) 
{ 
$t->set_var("ERROR", W_BADMAIL2);
$t->pparse("error");
include "../templates/footer.php";
die;
}

if (strlen($email) > $email_l)
{
$t->set_var("ERROR", W_BADMAILLEN);
$t->pparse("error");
include "../templates/footer.php";
die;
}

if (empty($country) || $country == "") {
$t->set_var("ERROR", W_BADCOUNTRY);
$t->pparse("error");
include "../templates/footer.php";
die;
}

if (empty($city) || $city == "") {
$t->set_var("ERROR", W_BADCITY);
$t->pparse("error");
include "../templates/footer.php";
die;
}

if (strlen($city) > $city_l)
{
$t->set_var("ERROR", W_BADCITYLEN);
$t->pparse("error");
include "../templates/footer.php";
die;
}
 
if (empty($hobby) || $hobby == "") {
$t->set_var("ERROR", W_BADHOBBY);
$t->pparse("error");
include "../templates/footer.php";
die;
}

if (strlen($hobby) > $hobby_l)
{
$t->set_var("ERROR", W_BADHOBBYS);
$t->pparse("error");
include "../templates/footer.php";
die;
}

$e = explode(" ",$hobby);
for ($a = 0; $a < sizeof($e); $a++)
{
$o = strlen($e[$a]);
}
if ($o > $hobby_w)
{
$t->set_var("ERROR", W_BADHOBBYW);
$t->pparse("error");
die;
}

if (empty($Description) || $Description == "") {
$t->set_var("ERROR", W_BADDESC);
$t->pparse("error");
include "../templates/footer.php";
die;
}

if (strlen($Description) > $desc_l)
{
$t->set_var("ERROR", W_BADDESCS);
$t->pparse("error");
include "../templates/footer.php";
die;
}

$e = explode(" ",$Description);
for ($a = 0; $a < sizeof($e); $a++)
{
$o = strlen($e[$a]);
}
if ($o > $desc_w)
{
$t->set_var("ERROR", W_BADDESCW);
$t->pparse("error");
include "../templates/footer.php";
die;
}

if ((empty($weight)) || ($weight == "") || (empty($height)) || ($height == ""))
{
$t->set_var("ERROR", W_BADWH);
$t->pparse("error");
include "../templates/footer.php";
die;
}

if (empty($purposes) || $purposes == "") {
$t->set_var("ERROR", W_BADCAT);
$t->pparse("error");
include "../templates/footer.php";
die;
}

if (!is_numeric($age))
{
$t->set_var("ERROR", W_BADAGE);
$t->pparse("error");
include "../templates/footer.php";
die;
}

$sql = "UPDATE ".$mysql_table." SET gender='".$gender."', email='".$email."', country='".$country."', purposes='".$purposes."', city='".$city."', hobby='".$hobby."', Description='".$Description."', height='".$height."', weight='".$weight."', age='".$age."' WHERE user = '".$username."'";
mysql_query($sql);


$t->set_var("MESSAGE", W_UPDATE_SUC."<br><br><input type=\"button\" value=\"".W_BACK_EDIT."\" class=input OnClick=\"location.href='index.php?l=".$l."&username=".$username."&password=".$password."'\">");
$t->pparse("success");
include "../templates/footer.php";
die;
} elseif ($page == "view") {

// View messages page
/////////////////////////////
/////////////////////////////

if ($vaction == "viewid")
{

// View message part
//////////////////////////////

$sql = "SELECT * FROM $mysql_messages WHERE toid = '$checkid' and mid = '$mid'";
$result = mysql_query($sql);

while ($i = mysql_fetch_array($result)) {
$data=date("H:i:s d/m/Y", $i[sendtime] + $date_diff*60*60);

$t->set_var('W_MES_FROM',W_MES_FROM);
$t->set_var('FROMUSER',$i[fromuser]);
$t->set_var('W_FROM',W_FROM);
$t->set_var('LANGUAGE',$l);
$t->set_var('FROMID',$i[fromid]);
$t->set_var('W_DATE',W_DATE);
$t->set_var('DATE',$data);
$t->set_var('W_SUBJECT',W_SUBJECT);
$t->set_var('SUBJECT',$i[subject]);
$t->set_var('W_MESSAGE',W_MESSAGE);
$t->set_var('MESSAGE',$i[message]);
$t->set_var('USERNAME',$username);
$t->set_var('PASSWORD',$password);
$t->set_var('W_USE_FOR_RE',W_USE_FOR_RE);
$t->set_var('W_RE',W_RE);
$t->set_var('W_YOUR_MES',W_YOUR_MES);
$t->set_var('W_READ_MES',W_READ_MES);
$t->set_var('W_NOTIFY_',W_NOTIFY_);
$t->set_var('TOUSER',$i[touser]);
$t->set_var('W_BACK_MES',W_BACK_MES);
$t->set_var('W_SEND_RE',W_SEND_RE);
$t->pparse("message");
// Checking for confirm and confirm user if it required
///////////////////////
$toid = $i[toid];
$touser = $i[touser];
$fromid = $i[fromid];
$fromuser = $i[fromuser];
$confirm = $i[confirm];
$readed = $i[readed];
$message = $i[message];
}
if ($readed == "0")
{
$dsql = "UPDATE $mysql_messages SET readed='1' WHERE mid = '$mid'";
mysql_query($dsql);
}

if (($confirm == "1") && ($readed == "0"))
{
$time = time();
$data=date("H:i:s d/m/Y", $time + $date_diff*60*60);

$message = W_MESR2."<br><br><i>".$message."</i><br><br>".W_MESR3." ".$touser." ".W_MESR4." ".$data;
$sql = "INSERT INTO ".$mysql_messages." (mid, fromid, fromuser, toid, touser, subject, message, sendtime, confirm, readed) VALUES ('', '".$toid."', '".$touser."', '".$fromid."', '".$fromuser."', '".W_MESR1."', '".$message."', '".$time."', '9', '0')";
$result = mysql_query($sql);}



}

elseif ($vaction == "sendid")

{

// Send reply message part
////////////////////////////////

$subject = check_bad_chars($subject);
$message = check_bad_chars($message);

if (empty($subject) || $subject == "") 
{
$t->set_var("ERROR", W_ENTER_SUB);
$t->pparse("error");
include "../templates/footer.php";
die;
}

if (empty($message) || $message == "") 
{
$t->set_var("ERROR", W_ENTER_MES);
$t->pparse("error");
include "../templates/footer.php";
die;
}


if ($confirm == "on") $confirm = "1";
else $confirm = "0";
$time = time();
$sql = "INSERT INTO ".$mysql_messages." (mid, fromid, fromuser, toid, touser, subject, message, sendtime, confirm, readed) VALUES ('', '".$checkid."', '".$login."', '".$toid."', '".$touser."', '".$subject."', '".$message."', '".$time."', '".$confirm."', '0')";
$result = mysql_query($sql);



// Popularity section begin
/////////////////////////////////////////


if ($popcheck == "2")
{
$id = $toid;
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
//////////////

    }
}      



///////////////////////////////////////////
// Popularity section end



$t->set_var("MESSAGE", W_MES_SENT."<br><br><input type=\"button\" value=\"".W_BACK_MES."\" class=input OnClick=\"location.href='index.php?l=".$l."&username=".$username."&password=".$password."&page=view'\"> <input type=\"button\" value=\"".W_BACK_EDIT."\" class=input OnClick=\"location.href='index.php?l=".$l."&username=".$username."&password=".$password."'\">");
$t->pparse("success");
include "../templates/footer.php";
die;
}
else
{

if ($vaction == "delmes")
{
// Delete messages part
////////////////////////////

$cnt=0;
for ($x = 1; $x < $count+1; $x++)
{
if ($c[$x] != "")
{
$cnt++;
$sql = "DELETE FROM ".$mysql_messages." WHERE mid = '".$c[$x]."'";
mysql_query($sql);
}
}
$count = 0;
$t->set_var("MESSAGE", $cnt." ".W_MES_HBDEL);
$t->pparse("success");
}

// View messages
///////////////////////////////////////

$tsql = "SELECT count(*) as total FROM ".$mysql_messages." WHERE toid = '".$checkid."'";
$tresult = mysql_query($tsql);
$trows = mysql_fetch_array($tresult);
$mesnum = $trows[total];
if ($mesnum != "0")
{
if (!$t_step) {$t_step = 10;}
if (!$from) {$from = 0;}

$sql = "SELECT mid, fromid, fromuser, subject, sendtime, readed FROM ".$mysql_messages." WHERE toid = '".$checkid."' order by sendtime DESC limit ".$from.",".$t_step;
$result = mysql_query($sql);


$t->set_var('LANGUAGE',$l);
$t->set_var('USERNAME',$username);
$t->set_var('PASSWORD',$password);
$t->set_var('W_FROM',W_FROM);
$t->set_var('W_SUBJECT',W_SUBJECT);
$t->set_var('W_DATE',W_DATE);
$t->set_var('W_DELETE',W_DELETE);

$count+=$from;
while ($i = mysql_fetch_array($result)) {
$count++;
$data=date("H:i:s d/m/Y", $i[sendtime] + $date_diff*60*60);
if ($i[readed] == "0")
{
$t->set_var('COUNT',$count);
$t->set_var('FROMID',$i[fromid]);
$t->set_var('FROMUSER',$i[fromuser]);
$t->set_var('MID',$i[mid]);
$t->set_var('SUBJECT',$i[subject]);
$t->set_var('DATE',$data);
$t->parse("readed_messages");
}
elseif ($i[readed] == "1")
{
$t->set_var('COUNT',$count);
$t->set_var('FROMID',$i[fromid]);
$t->set_var('FROMUSER',$i[fromuser]);
$t->set_var('MID',$i[mid]);
$t->set_var('SUBJECT',$i[subject]);
$t->set_var('DATE',$data);
$t->parse("unreaded_messages");
}
}



// Page generating
////////////////////////////////
if ($t_step < $mesnum)
{
$t->set_var('W_PAGE',W_PAGE);
$t->parse("if_pages");
$x = 0;
$step = $t_step;
$sstep = 0;
while ($sstep < $mesnum) {
       $page = $x + 1;
       if ($from == $sstep) $page = " <b>[".$page."]</b>";
	   else $page = " [<a href=\"index.php?l=".$l."&username=".$username."&password=".$password."&page=view&from=".$sstep."&t_step=".$t_step."\">".$page."</a>] ";
       $sstep = $sstep + $step;
	   $x++;
       $t->set_var('PAGES',$page);
       $t->parse("if_cycle");
	   }
}
// end page generating

$t->set_var('COUNT',$count);
$t->set_var('W_DEL_SEL_MES',W_DEL_SEL_MES);
$t->set_var('W_BACK_EDIT',W_BACK_EDIT);
$t->parse("end");
$t->pparse("messages");
}
else
{
$t->set_var("MESSAGE", W_NO_MES."<br><br><input type=\"button\" value=\"".W_BACK_EDIT."\" class=input OnClick=\"location.href='index.php?l=".$l."&username=".$username."&password=".$password."'\">");
$t->pparse("success");
include "../templates/footer.php";
die;
}

}
//////////////////////////////////////////
} elseif ($page == "remove") {

// Remove profile from database
/////////////////////////////
/////////////////////////////

if ($confdel == "yes")
{
$sql = "SELECT * FROM ".$mysql_table." WHERE user = '".$username."'";
$result = mysql_query($sql);
while ($i = mysql_fetch_array($result)) {
if (!empty($i[imgname]))
{
// Delete file
unlink ($int_path."/members/uploads/".$i[imgname]);
}
}


$sql = "DELETE FROM ".$mysql_table." WHERE user = '".$username."'";
mysql_query($sql);
$sql = "DELETE FROM ".$mysql_hits." WHERE user = '".$username."'";
mysql_query($sql);
$sql = "DELETE FROM ".$mysql_messages." WHERE fromuser = '".$username."' OR touser = '".$username."'";
mysql_query($sql);

$t->set_var("MESSAGE", $username." ".W_HAS_BEEN_DEL);
$t->pparse("success");
include "../templates/footer.php";
die;
}
else
{
$t->set_var("MESSAGE", "<form action=index.php?l=".$l."&username=".$username."&password=".$password."&page=remove&confdel=yes method=post enctype=\"post\">".W_ARE_YOU_SURE."<br><br><br><center><input class=input type=submit value=\"".W_YES."\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class=input type=button value=\"".W_CANCEL."\" OnClick='history.back()'></center>");
$t->pparse("success");
include "../templates/footer.php";
die;


}
} 
elseif ($page == "edit") 
{

// Edit Info page
//////////////////////////////
//////////////////////////////

$sql = "SELECT * FROM ".$mysql_table." WHERE user = '".$username."'";
$result = mysql_query($sql);
while ($i = mysql_fetch_array($result)) {

$t->set_var('LANGUAGE',$l);
$t->set_var('USERNAME',$username);
$t->set_var('PASSWORD',$password);
$t->set_var('W_CHANGE_INFO',W_CHANGE_INFO);
$t->set_var("W_GENDER", W_GENDER);
$t->set_var("GENDERNUM", $i[gender]);
$t->set_var("GENDER", $langgender[$i[gender]]);
$t->set_var("W_LANGGENDER1", $langgender[1]);
$t->set_var("W_LANGGENDER2", $langgender[2]);
$t->set_var("W_CATEGORY", W_CATEGORY);
$t->set_var("CATNUM", $i[purposes]);
$t->set_var("CATEGORY", $langpurposes[$i[purposes]]);
$p = 1;
while ($langpurposes[$p]) 
{
$t->set_var("CAT_NUM", $p);
$t->set_var("CATEGORIES", $langpurposes[$p]);
$t->parse("category_cycle");
$p++;
}
$t->set_var("W_MAIL", W_MAIL);
$t->set_var("C_MAIL_L", $email_l);
$t->set_var("R_MAIL", $i[email]);
$t->set_var("W_COUNTRY", W_COUNTRY);
$t->set_var("COUNTRY", $i[country]);
$t->set_var("W_CITY", W_CITY);
$t->set_var("C_CITY_L", $city_l);
$t->set_var("R_CITY", $i[city]);
$t->set_var("W_HOBBY", W_HOBBY);
$t->set_var("W_DESCR", W_DESCR);
$t->set_var("W_HEIGHT", W_HEIGHT);
$t->set_var("W_WEIGHT", W_WEIGHT);
$t->set_var("R_HOBBY", $i[hobby]);
$t->set_var("R_DESCR", $i[Description]);
$t->set_var("R_HEIGHT", $i[height]);
$t->set_var("R_WEIGHT", $i[weight]);
while ($min_height <= $max_height) 
{
$t->set_var("HEIGHT", $min_height);
$t->parse("height_cycle");
$min_height+=$between;
}
while ($min_weight <= $max_weight) 
{
$t->set_var("WEIGHT", $min_weight);
$t->parse("weight_cycle");
$min_weight+=$between;
}
$t->set_var("W_AGE", W_AGE);
$t->set_var("R_AGE", $i[age]);
$t->set_var("R_WEIGHT", $i[weight]);
$t->set_var('W_BACK_EDIT',W_BACK_EDIT);


if ($i[pic] != "") $t->set_var("PICAV", W_EDIT_PIC);
else $t->set_var("PICAV", W_NO_PIC_UP);
$t->pparse('members_edit');
}
}
else {
$t->set_var('LANGUAGE',$l);
$t->set_var('USERNAME',$username);
$t->set_var('PASSWORD',$password);
$t->set_var('W_VIEW_MES',W_VIEW_MES);
$t->set_var('W_EDIT_INFO',W_EDIT_INFO);

if ($allow_remove_profile == "1") 
{
$t->set_var('W_REMOVE_PROF',W_REMOVE_PROF);
$t->parse("if_del");
}
$t->pparse("members_menu");

}
include "../templates/footer.php";
?>