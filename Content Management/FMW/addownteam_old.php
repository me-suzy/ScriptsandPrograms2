<?php
include "header.php";
session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}



	if ($_POST['submit'] == 'submit') {
		$own_team_name = $_POST['own_team_name'];
		$ground_name = $_POST['ground_name'];
		$contact_name = $_POST['contact_name'];
		$contact_email = $_POST['contact_email'];
		$contact_email_2 = $_POST['contact_email_2'];
		$contact_email_3 = $_POST['contact_email_3'];
		$contact_tel = $_POST['contact_tel'];
		$contact_tel_2 = $_POST['contact_tel_2'];
		$contact_tel_3 = $_POST['contact_tel_3'];
		$contact_address = $_POST['contact_address'];
		$contact_name_2 = $_POST['contact_name_2'];
		$contact_name_3 = $_POST['contact_name_3'];
		$contact_role = $_POST['contact_role'];
		$contact_role_2 = $_POST['contact_role_2'];
		$contact_role_3 = $_POST['contact_role_3'];
		$home_strip = $_POST['home_strip'];
		$away_strip = $_POST['away_strip'];

$query="INSERT INTO teams (own_team_name, ground_name, contact_name, contact_email, contact_tel, contact_tel_2, contact_tel_3, contact_address, home_strip, away_strip, contact_name_2, contact_name_3,
contact_role, contact_role_2, contact_role_3, contact_email_2, contact_email_3) 

VALUES ('$own_team_name', '$ground_name', '$contact_name', '$contact_email', '$contact_tel', '$contact_tel_2', '$contact_tel_3', '$contact_address', '$home_strip', '$away_strip', '$contact_name_2', '$contact_name_3', '$contact_role', '$contact_role_2', '$contact_role_3', '$contact_email_2', '$contact_email_3') ";
mysql_query($query); 

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
Yor Team Name<br>
<input id="own_team_name" size="50" name="own_team_name" value="<?php echo "$own_team_name" ?>"><br>
<br>
Ground Name<br>
<input id="ground_name" size="50" name="ground_name"><br>
<br><BR>
Contact Name<br>
<input id="contact_name" size="50" name="contact_name"><br>
Contacts Position<br>
<input id="contact_role" size="50" name="contact_role"><br>
Email Address<br>
<input id="contact_email" size="50" name="contact_email"><br>
Telephone<br>
<input id="contact_tel" size="30" name="contact_tel"><br>
<br><br>


Contact Name 2<br>
<input id="contact_name_2" size="50" name="contact_name_2"><br>
Contact Two's Position<br>
<input id="contact_role_2" size="50" name="contact_role_2"><br>
Contact Two's Email Address<br>
<input id="contact_email_2" size="50" name="contact_email_2"><br>
Telephone<br>
<input id="contact_tel_2" size="30" name="contact_tel_2"><br>
<br><br>


Contact Name 3<br>
<input id="contact_name_3" size="50" name="contact_name_3"><br>
Contact Three's Position<br>
<input id="contact_role_3" size="50" name="contact_role_3"><br>
Contact Three's Email Address<br>
<input id="contact_email_3" size="50" name="contact_email_3"><br>
Telephone<br>
<input id="contact_tel_3" size="30" name="contact_tel_3"><br>
<br><br>



Address<br>
<textarea rows=5 cols=40 name="contact_address"></textarea><br>
<br>
Home Strip<br>
<input id="home_strip" size="50" name="home_strip"><br>
<br>
Away Strip<br>
<input id="away_strip" size="50" name="away_strip"><br>
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

