<?php
##############################################################################
# \-\-\-\-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/-/-/-/ #
##############################################################################
# AzDGDatingLite                Version 1.1.0                                 #
# Writed by                     AzDG (support@azdg.com)                      #
# Created 25/05/02              Last Modified 12/09/02                       #
# Scripts Home:                 http://www.azdg.com                          #
##############################################################################
include "../config.inc.php";
include "../templates/secure.php";
include "user.php";

include "../templates/header.php";
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
echo $err_mes_top.$lang[56].$err_mes_bottom;
include "../templates/footer.php";
die;
}

if ( $gender != "1" && $gender != "2") 
{
echo $err_mes_top.$lang[57].$err_mes_bottom;
include "../templates/footer.php";
die;
}

if (empty($email) || $email == "") {
echo $err_mes_top.$lang[7].$err_mes_bottom;
include "../templates/footer.php";
die;
}

if (check_email_addr($email) == 0) 
{ 
echo $err_mes_top.$lang[58].$err_mes_bottom;
include "../templates/footer.php";
die;
}

if (strlen($email) > $email_l)
{
echo $err_mes_top.$lang[60].$err_mes_bottom;
include "../templates/footer.php";
die;
}

if (empty($country) || $country == "") {
echo $err_mes_top.$lang[59].$err_mes_bottom;
include "../templates/footer.php";
die;
}

if (empty($city) || $city == "") {
echo $err_mes_top.$lang[61].$err_mes_bottom;
include "../templates/footer.php";
die;
}

if (strlen($city) > $city_l)
{
echo $err_mes_top.$lang[62].$err_mes_bottom;
include "../templates/footer.php";
die;
}
 
if (strlen($hobby) > $hobby_l)
{
echo $err_mes_top.$lang[64].$err_mes_bottom;
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
echo $err_mes_top.$lang[65].$err_mes_bottom;
include "../templates/footer.php";
die;
}

if (strlen($Description) > $desc_l)
{
echo $err_mes_top.$lang[66].$err_mes_bottom;
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
echo $err_mes_top.$lang[67].$err_mes_bottom;
include "../templates/footer.php";
die;
}

if ((empty($weight)) || ($weight == "") || (empty($height)) || ($height == ""))
{
echo $err_mes_top.$lang[68].$err_mes_bottom;
include "../templates/footer.php";
die;
}

if (empty($purposes) || $purposes == "") {
echo $err_mes_top.$lang[74].$err_mes_bottom;
include "../templates/footer.php";
die;
}

if (!is_numeric($age))
{
echo $err_mes_top.$lang[73].$err_mes_bottom;
include "../templates/footer.php";
die;
}

if (($age < $age_s)||($age > $age_b))
{
echo $err_mes_top.$lang[191].$err_mes_bottom;
include "../templates/footer.php";
die;
}


$sql = "UPDATE $mysql_table SET gender='$gender', email='$email', country='$country', purposes='$purposes', city='$city', hobby='$hobby', Description='$Description', height='$height', weight='$weight', age='$age' WHERE user = '$username'";
mysql_db_query($mysql_base, $sql, $mysql_link);


echo $err_mes_top.$lang[35]."<br><br><input type=\"button\" value=\"".$lang[141]."\" class=input OnClick=\"location.href='index.php?l=".$l."&username=".$username."&password=".$password."'\">".$suc_mes_bottom;
include "../templates/footer.php";
die;
} 
elseif ($page == "remove") {

// Remove profile from database
/////////////////////////////
/////////////////////////////

if ($confdel == "yes")
{
$sql = "SELECT * FROM $mysql_table WHERE user = '$username'";
$result = mysql_db_query($mysql_base, $sql, $mysql_link);
while ($i = mysql_fetch_array($result)) {
if (!empty($i[imgname]))
{
// Delete file
unlink ($int_path."/members/uploads/".$i[imgname]);
}
}


$sql = "DELETE FROM $mysql_table WHERE user = '$username'";
mysql_db_query($mysql_base, $sql, $mysql_link);
echo $err_mes_top.$username." ".$lang[91].$suc_mes_bottom;
include "../templates/footer.php";
die;
}
else
{
echo "<form action=index.php?l=".$l."&username=".$username."&password=".$password."&page=remove&confdel=yes method=post enctype=\"post\">";
echo $err_mes_top.$lang[161]."<br><br><br><center><input class=input type=submit value=\"".$lang[162]."\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class=input type=button value=\"".$lang[163]."\" OnClick='history.back()'></center>".$suc_mes_bottom;
echo "</form>";
}
} 
elseif ($page == "edit") 
{

// Edit Info page
//////////////////////////////
//////////////////////////////

$sql = "SELECT * FROM $mysql_table WHERE user = '$username'";
$result = mysql_db_query($mysql_base, $sql, $mysql_link);
while ($i = mysql_fetch_array($result)) {

?>
<form action=index.php?l=<?php echo $l; ?>&username=<?php echo $username; ?>&password=<?php echo $password; ?>&page=update method=post enctype="post">
<center><span class=head><?php echo $lang[32];?></span></center><Table Border="1" CellSpacing="0" CellPadding="4" bordercolor=black><tr class=desc><td width=100><?php echo $lang[11]; ?></td><td><select class=select name=gender>
<option value="<?php echo $i[gender]; ?>"><?php echo $langgender[$i[gender]]; ?>
<OPTION value=1><?php echo $langgender[1]; ?>
<OPTION value=2><?php echo $langgender[2]; ?>
</select></td></tr>
<tr class=desc><td width=100><?php echo $lang[14]; ?></td><td><select class=select name=purposes>
<option value="<?php echo $i[purposes]; ?>"><?php echo $langpurposes[$i[purposes]]; ?>
<?php
$p = 1;
while ($langpurposes[$p]) 
{
echo "<OPTION value=".$p.">".$langpurposes[$p];
	$p++;
}
?>
</select></td></tr>
<tr class=desc><td width=100><?php echo $lang[12]; ?></td><td><input class=input type=text name=email maxlength="70" value="<?php echo $i[email]; ?>"></td></tr>
<tr class=desc><td width=100><span class=mes><?php echo $lang[13]; ?></td><td><select class=select name="country">
<option><?php echo $i[country]; ?>
<?php 
include "../templates/countries.php";
?>
</td></tr>
<tr><td><span class=mes><?php echo $lang[15]; ?></td><td><input class=input type=text name=city maxlength=<?php echo $city_l; ?> value=<?php echo $i[city]; ?>></td></tr>
<tr><td><span class=mes><?php echo $lang[16]; ?></td><td><textarea class=textarea cols=20 rows=3 name=hobby><?php echo $i[hobby]; ?></textarea></td></tr>
<tr><td><span class=mes><?php echo $lang[17]; ?></td><td><textarea class=textarea cols=20 rows=4 name=Description><?php echo $i[Description]; ?></textarea></td></tr>
<tr><td><span class=mes><?php echo $lang[18]; ?></td><td><select class=select name=height><option value="<?php echo $i[height]; ?>"><?php echo $i[height]; ?>
<?php
while ($min_height < $max_height) 
{
echo "<OPTION value=".$min_height.">".$min_height;
	$min_height+=$between;
}
echo "<OPTION value=".$min_height.">".$min_height;

echo "</select></td></tr>
<tr><td><span class=mes>".$lang[19]."</td><td><select class=select name=weight><option value=".$i[weight].">".$i[weight];
while ($min_weight < $max_weight) 
{
echo "<OPTION value=".$min_weight.">".$min_weight;
	$min_weight+=$between;
}
echo "<OPTION value=".$min_weight.">".$min_weight;

echo "</select></td></tr>
</td></tr>
<tr><td><span class=mes>".$lang[20]."</td><td><input class=input type=text name=age maxlength=3 value=".$i[age]."></td></tr>";
?>
<tr><td align=right colspan=2><input class=input type=submit value="<?php echo $lang[32]; ?>"></td></tr>
<?php 
if ($i[pic] != "")
{
?>
<tr><td align=center colspan=2><a href="pic.php?l=<?php echo $l; ?>&username=<?php echo $username; ?>&password=<?php echo $password; ?>"><?php echo $lang[92]; ?></a></td></tr>
<?php
}
else
{
?>
<tr><td align=center colspan=2><a href="pic.php?l=<?php echo $l; ?>&username=<?php echo $username; ?>&password=<?php echo $password; ?>"><?php echo $lang[93]; ?></a></td></tr>
<?php
}
?>
</table>
</form>
<?php
echo "<center><input type=\"button\" value=\"".$lang[141]."\" class=input OnClick=\"location.href='index.php?l=".$l."&username=".$username."&password=".$password."'\"></center><br>";
}
}
else {
?>
<Table CellSpacing="0" CellPadding="4">
<td><form action=index.php?l=<?php echo $l; ?>&username=<?php echo $username; ?>&password=<?php echo $password; ?>&page=edit method=post enctype="post">
<center><span class=head><?php echo $lang[165]; ?></span><Table Border="1" CellSpacing="0" CellPadding="4" bordercolor=black>
<tr><td align=center colspan=2><input class=input type=submit value="<?php echo $lang[165]; ?>"></td></tr></table></form>
</td>

<?php
if ($allow_remove_profile == "1")
{
?>
<td><form action=index.php?l=<?php echo $l; ?>&username=<?php echo $username; ?>&password=<?php echo $password; ?>&page=remove method=post enctype="post">
<center><span class=head><?php echo $lang[94]; ?></span><Table Border="1" CellSpacing="0" CellPadding="4" bordercolor=black>
<tr><td align=center colspan=2><input class=input type=submit value="<?php echo $lang[94]; ?>"></td></tr></table></form></td>
<?php
}
?>
</tr>
</table>
<?php
}
include "../templates/footer.php";
?>