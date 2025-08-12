<?php
##################################################################
# \-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/ #
##################################################################
# AzDGDatingGold                Version 3.0.5                    #
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
          "add"=>"templates/".$template_name."/add.html",
          "success"=>"templates/".$template_name."/success.html"
          )
);

if ($page == "add") {

// checking for bad symbols 
$user = check_bad_chars($user);
$user = str_replace(" ","_","$user");
$pass = check_bad_chars($pass);
$email = check_bad_chars($email);
$city = check_bad_chars($city);
$country = check_bad_chars($country);
$hobby = check_bad_chars($hobby);
$Description = check_bad_chars($Description);

$sql = "SELECT user, email FROM $mysql_table WHERE user = '$user' or email = '$email'";
$result = mysql_query($sql);
while ($i = mysql_fetch_array($result)) {
function set_lower($WoRd)
{
$WoRd = strtr($WoRd, "QWERTYUIOPASDFGHJKLZXCVBNMÉÖÓÊÅÍÃØÙÇÕÚÔÛÂÀÏÐÎËÄÆÝß×ÑÌÈÒÜÁÞ¨",
"qwertyuiopasdfghjklzxcvbnméöóêåíãøùçõúôûâàïðîëäæýÿ÷ñìèòüáþ¸");
return $WoRd;
}

if (set_lower($user) == set_lower($i[user])) {
$t->set_var("ERROR", W_USE);
$t->pparse("error");
include "templates/footer.php";
die;
}
if ($use_unic_mail == "1") {
if (set_lower($email) == set_lower($i[email])) {
$t->set_var("ERROR", W_USEDMAIL);
$t->pparse("error");
include "templates/footer.php";
die;
}
}
}

if (empty($user) || $user == "" || !ereg("^[A-Za-z0-9_]{1,16}$",$user)) 
{
$t->set_var("ERROR", W_BADNAME);
$t->pparse("error");
include "templates/footer.php";
die;
}


if ((strlen($user) > $username_l)||(strlen($user) < $username_s))
{
$t->set_var("ERROR", W_BADULEN);
$t->pparse("error");
include "templates/footer.php";
die;
}

if (empty($pass) || $pass == "") {
$t->set_var("ERROR", W_BADPASS);
$t->pparse("error");
include "templates/footer.php";
die;
}

if ((strlen($pass) > $password_l)||(strlen($pass) < $password_s))
{
$t->set_var("ERROR", W_BADPLEN);
$t->pparse("error");
include "templates/footer.php";
die;
}

if (empty($gender) || $gender == "") 
{
$t->set_var("ERROR", W_BADGEN);
$t->pparse("error");
include "templates/footer.php";
die;
}

if ( $gender != "1" && $gender != "2") 
{
$t->set_var("ERROR", W_BADGEN2);
$t->pparse("error");
include "templates/footer.php";
die;
}

if (empty($email) || $email == "") {
$t->set_var("ERROR", W_BADMAIL);
$t->pparse("error");
include "templates/footer.php";
die;
}

if (check_email_addr($email) == 0) 
{ 
$t->set_var("ERROR", W_BADMAIL2);
$t->pparse("error");
include "templates/footer.php";
die;
}

if (strlen($email) > $email_l)
{
$t->set_var("ERROR", W_BADMAILLEN);
$t->pparse("error");
include "templates/footer.php";
die;
}

if (empty($country) || trim($country) == "") {
$t->set_var("ERROR", W_BADCOUNTRY);
$t->pparse("error");
include "templates/footer.php";
die;
}

if (empty($city) || $city == "") {
$t->set_var("ERROR", W_BADCITY);
$t->pparse("error");
include "templates/footer.php";
die;
}

if (strlen($city) > $city_l)
{
$t->set_var("ERROR", W_BADCITYLEN);
$t->pparse("error");
include "templates/footer.php";
die;
}

if (empty($hobby) || $hobby == "") {
$t->set_var("ERROR", W_BADHOBBY);
$t->pparse("error");
include "templates/footer.php";
die;
}

 
if (strlen($hobby) > $hobby_l)
{
$t->set_var("ERROR", W_BADHOBBYS);
$t->pparse("error");
include "templates/footer.php";
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
include "templates/footer.php";
die;
}

if (empty($Description) || $Description == "") {
$t->set_var("ERROR", W_BADDESC);
$t->pparse("error");
include "templates/footer.php";
die;
}


if (strlen($Description) > $desc_l)
{
$t->set_var("ERROR", W_BADDESCS);
$t->pparse("error");
include "templates/footer.php";
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
include "templates/footer.php";
die;
}

if ((empty($weight)) || ($weight == "") || (empty($height)) || ($height == ""))
{
$t->set_var("ERROR", W_BADWH);
$t->pparse("error");
include "templates/footer.php";
die;
}

if (empty($purposes) || $purposes == "") {
$t->set_var("ERROR", W_BADCAT);
$t->pparse("error");
include "templates/footer.php";
die;
}

if (!is_numeric($age))
{
$t->set_var("ERROR", W_BADAGE);
$t->pparse("error");
include "templates/footer.php";
die;
}

if (($age < $age_s)||($age > $age_b))
{
echo $err_mes_top.W_AGE_MUST_BE.$err_mes_bottom;
include "templates/footer.php";
die;
}


// If file uploaded
$time = time();
if ($HTTP_POST_FILES['file1']['name'] != "")
{
if (isset($HTTP_POST_FILES['file1']['name'])) $file1_name = $HTTP_POST_FILES['file1']['name'];
	else $file1_name = "";
if (isset($HTTP_POST_FILES['file1']['size'])) $file1_size = $HTTP_POST_FILES['file1']['size'];
	else $file1_size = "";
if (isset($HTTP_POST_FILES['file1']['tmp_name'])) $file1_tmp = $HTTP_POST_FILES['file1']['tmp_name'];
	else $file1_tmp = "";
    
if (($file1_name == "")||($file1_size == "")||($file1_tmp == "")) {
$t->set_var("ERROR", W_BADPHOTO);
$t->pparse("error");
include "templates/footer.php";
die;
}
      function getextension($filename)
      {
      	$filename 	= strtolower($filename);
	    $extension 	= split("[/\\.]", $filename);
	    $n 		= count($extension)-1;
	    $extension 	= $extension[$n];
	    return $extension;
        }

		$file_type 	= getextension($file1_name);
   		if( $file_type!="gif" && $file_type!="jpg" ){
        $t->set_var("ERROR", W_BADPHOTOEXT);
        $t->pparse("error");
        include "templates/footer.php";
        die;
        }
        $MaxSize1000 	= $MaxSize*1000;

		if($file1_size > $MaxSize1000)
		{
        $t->set_var("ERROR", W_BADPHOTOS);
        $t->pparse("error");
        include "templates/footer.php";
        die;
        }

$dir = date("mY", $time);
if (!is_dir($int_path.'/members/uploads/'.$dir))
{
umask(0);
mkdir ("members/uploads/".$dir, 0777);
}
$fileb = date("dHis", $time);
$filee = rand(0, 999);
$fn = $fileb."-".$filee;

$pic = $url."/members/uploads/".$dir."/".$fn.".".$file_type;
$intpic = $dir."/".$fn.".".$file_type;
if(function_exists("is_uploaded_file"))
  {
  if(is_uploaded_file($HTTP_POST_FILES['file1']['tmp_name']))
	{
	if(move_uploaded_file($HTTP_POST_FILES['file1']['tmp_name'], $int_path."/members/uploads/".$intpic))
		{
		}
	}
}

}


$sql = "INSERT INTO $mysql_table (id, user, password, gender, email, city, country, purposes, hobby, height, weight, age, pic, Description, imgname, imgtime) VALUES ('', '$user', '$pass', '$gender', '$email', '$city', '$country', '$purposes', '$hobby', '$height', '$weight', '$age', '$pic', '$Description', '$intpic', '$time')";
mysql_query($sql);

// Optimize and repair all tables
//////////////////////////////

// Optimizing database $mysql_table
$sql = "OPTIMIZE TABLE ".$mysql_table;
$result = mysql_query($sql);

// Repairing database $mysql_table
$sql = "REPAIR TABLE ".$mysql_table;
$result = mysql_query($sql);

// Optimizing database $mysql_hits
$sql = "OPTIMIZE TABLE ".$mysql_hits;
$result = mysql_query($sql);

// Repairing database $mysql_hits
$sql = "REPAIR TABLE ".$mysql_hits;
$result = mysql_query($sql);

// Optimizing database $mysql_messages
$sql = "OPTIMIZE TABLE ".$mysql_messages;
$result = mysql_query($sql);

// Repairing database $mysql_messages
$sql = "REPAIR TABLE ".$mysql_messages;
$result = mysql_query($sql);

///////////////////////////////////////////////////////////

$sql = "SELECT id, user FROM ".$mysql_table." WHERE user = '".$user."'";
$result = mysql_query($sql);

while ($i = mysql_fetch_array($result)) {
        $succ = W_SUCADD."<br><a href=view.php?l=".$l."&id=".$i[id].">".$user."</a>";
        $t->set_var("MESSAGE", $succ);
        $t->pparse("success");
        include "templates/footer.php";
        die;
}
include "templates/footer.php";
die;

} 
else 
{
/////// Form for add profiles!
$t->set_var("LANGUAGE", $l);
$t->set_var("W_ADD_USER", W_ADD_USER);
$t->set_var("W_SYMB", W_SYMB);
$t->set_var("W_BE_ACCURATE", W_BE_ACCURATE);
$t->set_var("W_USERNAME", W_USERNAME);
$t->set_var("C_USERNAME_S", $username_s);
$t->set_var("C_USERNAME_L", $username_l);
$t->set_var("W_PASSWORD", W_PASSWORD);
$t->set_var("C_PASSWORD_S", $password_s);
$t->set_var("C_PASSWORD_L", $password_l);
$t->set_var("W_GENDER", W_GENDER);
$t->set_var("W_SELECT", W_SELECT);
$t->set_var("W_LANGGENDER1", $langgender[1]);
$t->set_var("W_LANGGENDER2", $langgender[2]);
$t->set_var("W_MAIL", W_MAIL);
$t->set_var("C_MAIL_L", $email_l);
$t->set_var("W_COUNTRY", W_COUNTRY);
$t->set_var("W_CATEGORY", W_CATEGORY);
$t->set_var("W_AGE", W_AGE);
$t->set_var("W_PHOTO", W_PHOTO);
$t->set_var("W_REGISTER", W_REGISTER);
$t->set_var("W_CITY", W_CITY);
$t->set_var("C_CITY_L", $city_l);
$t->set_var("W_HOBBY", W_HOBBY);
$t->set_var("W_DESCR", W_DESCR);
$t->set_var("W_HEIGHT", W_HEIGHT);
$t->set_var("W_WEIGHT", W_WEIGHT);
//$t->parse("out","add");

$p = 1;
while ($langpurposes[$p]) 
{
$t->set_var("CAT_NUM", $p);
$t->set_var("CATEGORIES", $langpurposes[$p]);
$t->parse("category_cycle");
$p++;
}
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

$t->pparse("out","add");
}
include "templates/footer.php";
?>