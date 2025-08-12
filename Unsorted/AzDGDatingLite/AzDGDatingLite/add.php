<?php
##############################################################################
# \-\-\-\-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/-/-/-/ #
##############################################################################
# AzDGDatingLite                Version 1.1.0                                 #
# Writed by                     AzDG (support@azdg.com)                      #
# Created 25/05/02              Last Modified 12/09/02                       #
# Scripts Home:                 http://www.azdg.com                          #
##############################################################################
include "config.inc.php";
include "templates/secure.php";
include "templates/header.php";


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
$result = mysql_db_query($mysql_base, $sql, $mysql_link);
while ($i = mysql_fetch_array($result)) {
function set_lower($WoRd)
{
$WoRd = strtr($WoRd, "QWERTYUIOPASDFGHJKLZXCVBNMÉÖÓÊÅÍÃØÙÇÕÚÔÛÂÀÏÐÎËÄÆÝß×ÑÌÈÒÜÁÞ¨",
"qwertyuiopasdfghjklzxcvbnméöóêåíãøùçõúôûâàïðîëäæýÿ÷ñìèòüáþ¸");
return $WoRd;
}
if (set_lower($user) == set_lower($i[user])) {
echo $err_mes_top.$lang[4].$err_mes_bottom;
include "templates/footer.php";
die;
}
if ($use_unic_mail == "1") {
if (set_lower($email) == set_lower($i[email])) {
echo $err_mes_top.$lang[72].$err_mes_bottom;
include "templates/footer.php";
die;
}
}
}

if (empty($user) || $user == "") 
{
echo $err_mes_top.$lang[5].$err_mes_bottom;
include "templates/footer.php";
die;
}


if ((strlen($user) > $username_l)||(strlen($user) < $username_s))
{
echo $err_mes_top.$lang[54].$err_mes_bottom;
include "templates/footer.php";
die;
}

if (empty($pass) || $pass == "") {
echo $err_mes_top.$lang[6].$err_mes_bottom;
include "templates/footer.php";
die;
}

if ((strlen($pass) > $password_l)||(strlen($pass) < $password_s))
{
echo $err_mes_top.$lang[55].$err_mes_bottom;
include "templates/footer.php";
die;
}

if (empty($gender) || $gender == "") 
{
echo $err_mes_top.$lang[56].$err_mes_bottom;
include "templates/footer.php";
die;
}

if ( $gender != "1" && $gender != "2") 
{
echo $err_mes_top.$lang[57].$err_mes_bottom;
include "templates/footer.php";
die;
}

if (empty($email) || $email == "") {
echo $err_mes_top.$lang[7].$err_mes_bottom;
include "templates/footer.php";
die;
}

if (check_email_addr($email) == 0) 
{ 
echo $err_mes_top.$lang[58].$err_mes_bottom;
include "templates/footer.php";
die;
}

if (strlen($email) > $email_l)
{
echo $err_mes_top.$lang[60].$err_mes_bottom;
include "templates/footer.php";
die;
}

if (empty($country) || trim($country) == "") {
echo $err_mes_top.$lang[59].$err_mes_bottom;
include "templates/footer.php";
die;
}

if (empty($city) || $city == "") {
echo $err_mes_top.$lang[61].$err_mes_bottom;
include "templates/footer.php";
die;
}

if (strlen($city) > $city_l)
{
echo $err_mes_top.$lang[62].$err_mes_bottom;
include "templates/footer.php";
die;
}

if (empty($hobby) || $hobby == "") {
echo $err_mes_top.$lang[183].$err_mes_bottom;
include "templates/footer.php";
die;
}

 
if (strlen($hobby) > $hobby_l)
{
echo $err_mes_top.$lang[64].$err_mes_bottom;
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
echo $err_mes_top.$lang[65].$err_mes_bottom;
include "templates/footer.php";
die;
}

if (empty($Description) || $Description == "") {
echo $err_mes_top.$lang[184].$err_mes_bottom;
include "templates/footer.php";
die;
}


if (strlen($Description) > $desc_l)
{
echo $err_mes_top.$lang[66].$err_mes_bottom;
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
echo $err_mes_top.$lang[67].$err_mes_bottom;
include "templates/footer.php";
die;
}

if ((empty($weight)) || ($weight == "") || (empty($height)) || ($height == ""))
{
echo $err_mes_top.$lang[68].$err_mes_bottom;
include "templates/footer.php";
die;
}

if (empty($purposes) || $purposes == "") {
echo $err_mes_top.$lang[74].$err_mes_bottom;
include "templates/footer.php";
die;
}

if (!is_numeric($age))
{
echo $err_mes_top.$lang[73].$err_mes_bottom;
include "templates/footer.php";
die;
}

if (($age < $age_s)||($age > $age_b))
{
echo $err_mes_top.$lang[191].$err_mes_bottom;
include "templates/footer.php";
die;
}

if ($HTTP_POST_FILES['file1']['name'] != "")
{

      $time = time();
if (isset($HTTP_POST_FILES['file1']['name'])) $file1_name = $HTTP_POST_FILES['file1']['name'];
	else $file1_name = "";
if (isset($HTTP_POST_FILES['file1']['size'])) $file1_size = $HTTP_POST_FILES['file1']['size'];
	else $file1_size = "";
if (isset($HTTP_POST_FILES['file1']['tmp_name'])) $file1_tmp = $HTTP_POST_FILES['file1']['tmp_name'];
	else $file1_tmp = "";
    
if (($file1_name == "")||($file1_size == "")||($file1_tmp == "")) {
echo $err_mes_top.$lang[50].$err_mes_bottom;
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
 
        echo $err_mes_top.$lang[69].$err_mes_bottom;
        include "templates/footer.php";
        die;
}
        $MaxSize1000 	= $MaxSize*1000;

		if($file1_size > $MaxSize1000)
		{
        echo $err_mes_top.$lang[70].$err_mes_bottom;
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
mysql_db_query($mysql_base, $sql, $mysql_link);


$sql = "SELECT id, user FROM $mysql_table WHERE user = '$user'";
$result = mysql_db_query($mysql_base, $sql, $mysql_link);

while ($i = mysql_fetch_array($result)) {
echo $err_mes_top.$lang[71]."<br><a href=view.php?l=".$l."&id=".$i[id].">".$user."</a>".$suc_mes_bottom;
}
include "templates/footer.php";
die;

} else {
?>
<form action=add.php?l=<?php echo $l; ?>&page=add method=post enctype="multipart/form-data">
<center><span class=head><?php echo $lang[1]; ?></span><br>
<br>
<span class=dat><?php echo $lang[63]; ?></span>
<Table Border="1" CellSpacing="0" CellPadding="4" bordercolor=black bgcolor=<?php echo $color3; ?> width=400>
<tr><td width=200><span class=mes><?php echo $lang[9]; ?> (<?php echo $username_s; ?> - <?php echo $username_l." ".$lang[167]; ?>)</td><td width=200><input class=input type=text name=user maxlength=<?php echo $username_l; ?>></td></tr>
<tr><td><span class=mes><?php echo $lang[10]; ?> (<?php echo $password_s; ?> - <?php echo $password_l." ".$lang[167]; ?>)</td><td><input class=input type=password name=pass maxlength=<?php echo $password_l; ?>></td></tr>
<tr><td><span class=mes><?php echo $lang[11]; ?></td><td><select class=select name=gender>
<option value=""><?php echo $lang[28]; ?>
<OPTION value=1><?php echo $langgender[1]; ?>
<OPTION value=2><?php echo $langgender[2]; ?>
</select></td></tr>
<tr><td><span class=mes><?php echo $lang[12]; ?></td><td><input class=input type=text name=email maxlength=$email_l; ?></td></tr>
<tr><td><span class=mes><?php echo $lang[13]; ?></td><td>
<select class=select name="country">
<option><?php echo $lang[28]; ?>
<?php 
include "templates/countries.php";
?>
</td></tr>
<tr><td><span class=mes><?php echo $lang[14]; ?></td><td><select class=select name=purposes>
<option value=""><?php echo $lang[28]; ?>
<?php
$p = 1;
while ($langpurposes[$p]) 
{
echo "<OPTION value=".$p.">".$langpurposes[$p];
	$p++;
}
?>
</select></td></tr>
<tr><td><span class=mes><?php echo $lang[15]; ?></td><td><input class=input type=text name=city maxlength=$city_l; ?></td></tr>
<tr><td><span class=mes><?php echo $lang[16]; ?></td><td><textarea class=textarea cols=20 rows=4 name=hobby></textarea></td></tr>
<tr><td><span class=mes><?php echo $lang[17]; ?></td><td><textarea class=textarea cols=20 rows=8 name=Description></textarea></td></tr>
<tr><td><span class=mes><?php echo $lang[18]; ?></td><td><select class=select name=height><option value=""><?php echo $lang[28]; ?>
<?php
while ($min_height < $max_height) 
{
echo "<OPTION value=".$min_height.">".$min_height;
	$min_height+=$between;
}
echo "<OPTION value=".$min_height.">".$min_height;

echo "</select></td></tr>
<tr><td><span class=mes>".$lang[19]."</td><td><select class=select name=weight><option value=''>".$lang[28];
while ($min_weight < $max_weight) 
{
echo "<OPTION value=".$min_weight.">".$min_weight;
	$min_weight+=$between;
}
echo "<OPTION value=".$min_weight.">".$min_weight;

echo "</select></td></tr>
</td></tr>
<tr><td><span class=mes>".$lang[20]."</td><td><input class=input type=text name=age maxlength=3></td></tr>
<tr><td><span class=mes>".$lang[21]."</td><td><input class=input type=file name=file1></td></tr>
<tr><td colspan=2 align=right><input class=input type=submit value=".$lang[22].">
</table>
</form>";
}
include "templates/footer.php";
?>