<?php
include "header.php";
session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}

$query="SELECT * FROM matchtypes WHERE match_cat = 'league'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
{
$matchtype = $row["matchtype"];
$division = $row["division"];
}
$query="SELECT * FROM teams WHERE own_team = 'yes'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
{
$team_name = $row["team_name"];
$contact_name = $row["contact_name"];
$contact_role = $row["contact_role"];
$contact_name_2 = $row["contact_name_2"];
$contact_role_2 = $row["contact_role_2"];
$contact_name_3 = $row["contact_name_3"];
$contact_role_3 = $row["contact_role_3"];
$contact_email = $row["contact_email"];
$contact_email_2 = $row["contact_email_2"];
$contact_email_3 = $row["contact_email_3"];
$contact_tel = $row["contact_tel"];
$contact_tel_2 = $row["contact_tel_2"];
$contact_tel_3 = $row["contact_tel_3"];
$contact_address = $row["contact_address"];
$ground_name = $row["ground_name"];
$home_strip = $row["home_strip"];
$away_strip = $row["away_strip"];

}

//-------------------------------------------------------------
// routine to record old team name, ready for replacing with new name
//-------------------------------------------------------------
$query="SELECT * FROM league_table WHERE team_name = '$team_name' ";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
{
$oldname = $row["team_name"];

}



$query="UPDATE league_table SET oldname='$oldname' 
WHERE team_name = '$oldname' ";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 





	if ($_POST['submit'] == 'submit') {
	
$team_name = $_POST['team_name'];
$ground_name = $_POST['ground_name'];
$division = $_POST['division'];
$league_name = $_POST['league_name'];
$contact_name = $_POST['contact_name'];
$contact_role = $_POST['contact_role'];
$contact_name_2 = $_POST['contact_name_2'];
$contact_role_2 = $_POST['contact_role_2'];
$contact_name_3 = $_POST['contact_name_3'];
$contact_role_3 = $_POST['contact_role_3'];
$contact_email = $_POST['contact_email'];
$contact_email_2 = $_POST['contact_email_2'];
$contact_email_3 = $_POST['contact_email_3'];
$contact_tel = $_POST['contact_tel'];
$contact_tel_2 = $_POST['contact_tel_2'];
$contact_tel_3 = $_POST['contact_tel_3'];
$contact_address = $_POST['contact_address'];
$home_strip = $_POST['home_strip'];
$away_strip = $_POST['away_strip'];
$matchtype = $_POST['matchtype'];
$division = $_POST['division'];


$query="UPDATE teams SET team_name='$team_name', ground_name='$ground_name', contact_name='$contact_name', contact_email='$contact_email', contact_email_2='$contact_email_2', contact_email_3='$contact_email_3', contact_tel='$contact_tel', contact_tel_2='$contact_tel_2', contact_tel_3='$contact_tel_3', contact_address='$contact_address', contact_name_2='$contact_name_2', contact_name_3='$contact_name_3', contact_role='$contact_role', contact_role_2='$contact_role_2', contact_role_3='$contact_role_3', home_strip='$home_strip', away_strip='$away_strip' 
WHERE own_team = 'yes' ";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 
  
$query="UPDATE matchtypes SET matchtype='$matchtype', division='$division' 
WHERE match_cat = 'league' ";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 

//----------------------------------
// updates league table all old names with new team name
//----------------------------------
$query="UPDATE league_table SET team_name='$team_name' 
WHERE oldname = '$oldname' ";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 

?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=admin.php"> <?php


	} 



?>

<HTML>
<HEAD>
<TITLE>Add Team</TITLE>
</HEAD>
<BODY>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<center>
<table border="0" width="60%" height="30" cellpadding="0" cellspacing="0">
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
<td width="100%" bgcolor="#<?php echo "$col_back"; ?>" align="center">
<?php echo "<font color='#$col_text'>"; ?>

<H4>Enter Your Own Team Details</H4>
<BR><BR>
Your Team Name<br>
<input id="team_name" size="50" name="team_name" value="<?php echo "$team_name" ?>"><br>
<br>
Enter League Name<br>
<input id="matchtype" size="50" name="matchtype" value="<?php echo "$matchtype" ?>"><br>
<br>
Enter Division Currently In<br>
<input id="division" size="50" name="division" value="<?php echo "$division" ?>"><br>
<br>
Ground Name<br>
<input id="ground_name" size="50" name="ground_name" value="<?php echo "$ground_name" ?>"><br>
<br><BR>
Contact Name<br>
<input id="contact_name" size="50" name="contact_name" value="<?php echo "$contact_name" ?>"><br>

Contacts Position<br>
<input id="contact_role" size="50" name="contact_role" value="<?php echo "$contact_role" ?>"><br>

Email Address<br>
<input id="contact_email" size="50" name="contact_email" value="<?php echo "$contact_email" ?>"><br>

Telephone<br>
<input id="contact_tel" size="30" name="contact_tel" value="<?php echo "$contact_tel" ?>"><br>

<br><br>


Contact Name 2<br>
<input id="contact_name_2" size="50" name="contact_name_2" value="<?php echo "$contact_name_2" ?>"><br>

Contact Two's Position<br>
<input id="contact_role_2" size="50" name="contact_role_2" value="<?php echo "$contact_role_2" ?>"><br>

Contact Two's Email Address<br>
<input id="contact_email_2" size="50" name="contact_email_2" value="<?php echo "$contact_email_2" ?>"><br>

Telephone<br>
<input id="contact_tel_2" size="30" name="contact_tel_2" value="<?php echo "$contact_tel_2" ?>"><br>

<br><br>


Contact Name 3<br>
<input id="contact_name_3" size="50" name="contact_name_3" value="<?php echo "$contact_name_3" ?>"><br>

Contact Three's Position<br>
<input id="contact_role_3" size="50" name="contact_role_3" value="<?php echo "$contact_role_3" ?>"><br>

Contact Three's Email Address<br>
<input id="contact_email_3" size="50" name="contact_email_3" value="<?php echo "$contact_email_3" ?>"><br>

Telephone<br>
<input id="contact_tel_3" size="30" name="contact_tel_3" value="<?php echo "$contact_tel_3" ?>"><br>

<br><br>



Address<br>
<textarea rows=5 cols=40 name="contact_address"><?php echo "$contact_address" ?></textarea>
<br>
Home Strip<br>
<input id="home_strip" size="50" name="home_strip" value="<?php echo "$home_strip" ?>"><br>

<br>
Away Strip<br>
<input id="away_strip" size="50" name="away_strip" value="<?php echo "$away_strip" ?>"><br>

<br>

<br><br>

</td>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>
</font>
<br>
<input type="Submit" name="submit" value="submit">
</form>
</H4>

</BODY> 
</HTML>
