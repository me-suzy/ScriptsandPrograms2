<?php
include "header.php";

session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}
$fileId = $_GET['fileId'];




$query="SELECT * FROM users WHERE id = '$fileId'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
{
$user = $row["username"];
$displayname = $row["displayname"];
$email = $row["email"];
$nickname = $row["nickname"];
$msn = $row["msn"];
$icq = $row["icq"];
$aim = $row["aim"];
$tel = $row["tel"];
$position = $row["position"];
$age = $row["age"];
$role = $row["role"];
$avatar = $row["avatar"];
$nickname = $row["nickname"];
$yim = $row["yim"];
$interests = $row["interests"];
$joindate = $row["joindate"];
$profile = $row["profile"];
$clubs = $row["clubs"];
$rights = $row["rights"];

}


if ($_POST['submit'] == 'submit') {


if(!$_POST['displayname']) {
	?><font color="#<?php echo "$col_text" ?>"><?php die('You must supply a display name.');
	}



$position = $_POST['position'];
$age = $_POST['age'];
$email = $_POST['email'];
$icq = $_POST['icq'];
$msn = $_POST['msn'];
$aim = $_POST['aim'];
$yim = $_POST['yim'];
$tel = $_POST['tel'];
$displayname = $_POST['displayname'];
$nickname = $_POST['nickname'];
$joindate = $_POST['joindate'];
$clubs = $_POST['clubs'];
$profile = $_POST['profile'];
$interests = $_POST['interests'];
$role = $_POST['role_title'];
$rights = $_POST['rights'];
$player = $_POST['player'];

$query="UPDATE users SET position = '$position', displayname='$displayname', age='$age', email='$email', icq='$icq', msn='$msn', nickname='$nickname', yim='$yim', joindate='$joindate', clubs='$clubs', profile='$profile', interests='$interests',  
aim='$aim', tel='$tel', role='$role', rights='$rights', player='$player' WHERE id = $fileId ";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query);

?>
<meta HTTP-EQUIV="Refresh" CONTENT="0; URL=memberedit.php">
<?php
}
?>

<HTML>
<HEAD>
<TITLE>Edit Profile</TITLE>
</HEAD>
<BODY>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];echo "?fileId="; echo "$fileId";?>">

<center>
<table border="0" width="80%" height="30" cellpadding="0" cellspacing="0">
<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornertopleft.png" width="5" height="25" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbacktop.png" width="100%" height="25" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornertopright.png" width="5" height="25" alt=""></td>
</tr>



<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxleft.png" width="5" height="100%" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="25" alt=""></td>
</tr>



<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxleft.png" width="5" height="100%" alt=""></td>
<td width="100%"  bgcolor="#<?php echo "$col_back"; ?>" align="center">
<H4><?php echo "<font color='#$col_text'>"; ?>EDIT PROFILE<BR><br>
<br>
<p align="center"><img src="images/avatar/<?php echo "$avatar" ?>">
<br>

<a href="adminavupload.php?fileId=<?php echo "$fileId"; ?>"><font color="#<?php echo $col_link ?>">Click To Upload Avatar</font></a>
<br><br>

Username<br>
(Cannot be changed)<br>
<input readonly id="username" size="30" name="username" value="<?php echo "$user" ?>"><br>
<br><br>
Display Name<br>
<input id="displayname" size="30" name="displayname" value="<?php echo "$displayname" ?>"><br>
Nick Name<br>
<input id="nickname" size="30" name="nickname" value="<?php echo "$nickname" ?>"><br>
<br>
Position/Role<br>
(i.e for players 'Forward, 'Defender' etc For others, 'Manager', 'Coach' etc)<bR>
<input id="position" size="30" name="position" value="<?php echo "$position" ?>"><br>
Date Joined Club<br>
<input id="joindate" size="8" name="joindate" value="<?php echo "$joindate" ?>"><br>

Age<br>
<input id="age" size="3" name="age" value="<?php echo "$age" ?>"><br>
<br>
<u>Contact Information:</u>
<br>
Email<br>
<input id="email" size="30" name="email" value="<?php echo "$email" ?>"><br>

ICQ<br>
<input id="icq" size="10" name="icq" value="<?php echo "$icq" ?>"><br>

MSN<br>
<input id="msn" size="30" name="msn" value="<?php echo "$msn" ?>"><br>

AIM<br>
<input id="aim" size="30" name="aim" value="<?php echo "$aim" ?>"><br>
YIM<br>
<input id="yim" size="30" name="yim" value="<?php echo "$yim" ?>"><br>


TEL<br>
(only visible to logged in members)<br>
<input id="tel" size="30" name="tel" value="<?php echo "$tel" ?>"><br>
<br><br>
<u>Information about yourself:</u>
<br><br>
Your Profile<br>
(Anything you would like to say about yourself)
<br>
<textarea rows=4 cols=40 name="profile"><? echo $profile ?></textarea>
<br>
Other Interests
<br>
<textarea rows=4 cols=40 name="interests"><? echo $interests ?></textarea>
<br>
Previous Clubs
<br>
<textarea rows=3 cols=40 name="clubs"><? echo $clubs ?></textarea>
<br>



<br>
Member Rights<br>
<br>
*Member - Access general pages, but can not post news<br>
*Super Member - Post and edit all news<br>
*Admin - Full access<br>
<?php
// echo '<select name="rights">'; 
// echo '<option value="0">Member</option>'; 
// echo '<option value="3">Super Member</option>'; 
// echo '<option value="5">Admin</option>'; 
?>

<?php
echo '<select name="rights">'; 
echo '<option value="0"' . ($rights == '0' ? ' selected' : '') . '>Member</option>'; 
echo '<option value="3"' . ($rights == '3' ? ' selected' : '') . '>Super Member</option>'; 
echo '<option value="5"' . ($rights == '5' ? ' selected' : '') . '>Admin</option>'; 
?>


</select> 
<BR><BR>
Select Role Of Player*
<br>

<?php
$dbQuery = "SELECT * "; 

$dbQuery .= "FROM roles"; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
$num=mysql_numrows($result);
?>


<SELECT NAME="role_title">
<?php
while($row = mysql_fetch_array($result))
print "<OPTION VALUE=\"$row[1]\">$row[1]</OPTION>\n";

?>
</select>


<br><br>
Is Member a Player In The Team ?<br>
(If Yes They Will Be Available On Team Selection)<br>

<?php
echo '<select name="player">'; 
echo '<option value="yes">Yes</option>';  
echo '<option value="no">No</option>'; 
?>

</select>



</td>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>
</tr>


<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>

<br>

<input type="Submit" name="submit" value="submit">
</form>
</font>
</BODY> 
</HTML>

