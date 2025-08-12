<?php
include "header.php";
session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}
$fileId = $_GET['fileId'];



$query="SELECT * FROM teams WHERE team_id = '$fileId'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
{
$team_name = $row["team_name"];
$contact_name = $row["contact_name"];
$contact_email = $row["contact_email"];
$contact_tel = $row["contact_tel"];
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

$query="UPDATE fixtures SET fix_oldname='$oldname' 
WHERE opp_team = '$oldname' ";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 



	if ($_POST['submit'] == 'submit') {
	
$team_name = $_POST['team_name'];
$ground_name = $_POST['ground_name'];
$contact_name = $_POST['contact_name'];
$contact_email = $_POST['contact_email'];
$contact_tel = $_POST['contact_tel'];
$contact_address = $_POST['contact_address'];
$home_strip = $_POST['home_strip'];
$away_strip = $_POST['away_strip'];


$query="UPDATE teams SET team_name='$team_name', ground_name='$ground_name', contact_name='$contact_name', contact_email='$contact_email', contact_tel='$contact_tel', contact_address='$contact_address', home_strip='$home_strip', away_strip='$away_strip' 
WHERE team_id = '$fileId' ";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 
  

//----------------------------------
// updates league table all old names with new team name
//----------------------------------
$query="UPDATE league_table SET team_name='$team_name' 
WHERE oldname = '$oldname' ";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 

$query="UPDATE fixtures SET opp_team='$team_name' 
WHERE fix_oldname = '$oldname' ";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 
	 

?><meta HTTP-EQUIV="Refresh" CONTENT="0; URL=teamlist.php"><?php
}

?>

<HTML>
<HEAD>
<TITLE>Edit Team</TITLE>
</HEAD>
<BODY>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];echo "?fileId="; echo "$fileId";?>">
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

<H4>Enter Team Details</H4>
<BR><BR>
Your Team Name<br>
<input id="team_name" size="50" name="team_name" value="<?php echo "$team_name" ?>"><br>
<br>
Ground Name<br>
<input id="ground_name" size="50" name="ground_name" value="<?php echo "$ground_name" ?>"><br>
<br><BR>
Contact Name<br>
<input id="contact_name" size="50" name="contact_name" value="<?php echo "$contact_name" ?>"><br>
Email Address<br>
<input id="contact_email" size="50" name="contact_email" value="<?php echo "$contact_email" ?>"><br>

Telephone<br>
<input id="contact_tel" size="30" name="contact_tel" value="<?php echo "$contact_tel" ?>"><br>

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
