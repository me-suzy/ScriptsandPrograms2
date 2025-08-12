<?php
include "header.php";

$fileId = $_GET['fileId'];


// $fileId = '47';
$number = '1';

?><font color="#<?php echo $col_text ?>"><?php


session_start();
if (($_SESSION['perm'] < "5")){
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}

$query="SELECT fix_id, opp_team, match_type FROM fixtures WHERE fix_id = '$fileId'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
$opposition = $row["opp_team"];
$match_type = $row["match_type"];
							}

$query="SELECT * FROM match_signup WHERE playing = 'no' AND match_id = '$fileId'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
$notplaying = $row["name"];


$query="UPDATE users SET selected='yes' WHERE displayname='$notplaying'"; 
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 
							}

$query="SELECT username, player FROM users WHERE player = 'no' ";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
$nonplayer = $row["username"];


$query="UPDATE users SET selected='yes' WHERE username='$nonplayer'"; 
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 
							}



$query="SELECT * FROM match_squad WHERE match_id = '$fileId'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
$playing = $row["playername"];
							
$query="UPDATE users SET selected='yes' WHERE displayname='$playing'"; 
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 
						}


if (isset($_POST['email'])) { 

/* additional headers */
$headers .= "From: $admin_email\r\n";

$query="SELECT opp_team, ground, fix_date, fix_time, home_away, match_type  FROM fixtures WHERE fix_id = '$fileId' ";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
$opp_team = $row["opp_team"];
$ground = $row["ground"];
$date = $row["fix_date"];
$fix_time = $row["fix_time"];
$home_away = $row["home_away"];
$match_type = $row["match_type"];
							}

$year = substr("$date", 0, 4);
$month = substr("$date", 5,-3);
$day = substr("$date", 8);

$query="SELECT email FROM users ";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
$email = $row["email"];


/* recipients */

$to = "$email";

/* subject */
$subject = 'Fixture Details For Match Against '.$opp_team.'';

/* message */
$message = 'You are receiving this email from '.$site_url.' as you are a member of the club.


Details Of Match Fixture:

Opposition: '.$opp_team.'
Date Of Match: '.$day.'-'.$month.'-'.$year.'
KickOff: '.$fix_time.'
Home or Away: '.$home_away.'
Ground Playing At: '.$ground.'
Match Type: '.$match_type.'

You can view the full squad details by going to: '.$site_url.'/fixture.php?fileId='.$fileId.'

If you have any questions, please email: '.$admin_email.'
';			

/* and now mail it */
mail($to, $subject, $message, $headers);
				}
				}


if (isset($_POST['submit'])) { 

$player = $_POST['player'];
$position =$_POST['position'];

if ($player == '') {
	die ('You have allocated all your players') ;
			} 

$query="UPDATE users SET selected='yes' WHERE displayname='$player'"; 
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 


$query="INSERT INTO match_squad (match_id, position, playername) 
VALUES ('$fileId', '$position', '$player') ";
mysql_query($query); 


}

if (isset($_POST['reset'])) { 

$query="UPDATE users SET selected='no' "; 
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 

mysql_query("DELETE FROM match_squad WHERE match_id='$fileId'")
or die(mysql_error());


?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=selectsquad.php<?php echo "?fileId="; echo "$fileId";?>">
<?php

					}

?>
<html>
<head>
<title>Squad Select</title>
</head>
<H3><?php echo "$match_type "; ?> Match Against <?php echo " $opposition"; ?> </H3>

<p>Below is a summary of player response to above match</p>

<?php
$query="SELECT * FROM match_signup WHERE match_id = '$fileId' AND playing = 'yes'";
$result=mysql_query($query);
$num=mysql_numrows($result);
?>

<center>
<table border="0" width="60%" height="30" cellpadding="0" cellspacing="0">
<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornertopleft.png" width="5" height="25" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbacktop.png" width="100%" height="25" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornertopright.png" width="5" height="25" alt=""></td>
</tr>


<tr bgcolor="#<?php echo "$col_table_header"; ?>">
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxleft.png" width="5" height="100%" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="25" alt=""></td>
</tr>



<tr bgcolor="#<?php echo "$col_table_header"; ?>">
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxleft.png" width="5" height="100%" alt=""></td>
<td width="100%" bgcolor="#<?php echo "$col_table_header"; ?>" align="center">
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>Squad Management<br><br></b>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>



<table border="1" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" cellspacing="1" width="60%" id="signup">
    <tr bgcolor="#<?php echo "$col_table_header"; ?>">
      <td width="100%" colspan="2"><font color="#<?php echo "$col_table_header_text" ?>">Players That Can Play <br>
<font color="#<?php echo "$col_table_header_text" ?>"  size="2">  There are <?php echo " $num " ?> Player(s) signed up to play
</td>
    </tr>

<?php
while($row = mysql_fetch_array($result))
{

	if ($bgcolor === "$col_table_row")
	{
   $bgcolor = "$col_table_row2";
	} else {
   $bgcolor = "$col_table_row";
	} 



$name = $row["name"];
$playing = $row["playing"];
$comment = $row["comment"];

	if ($num !='') {
	?>
   	 <tr>
      <td width="50%" bgcolor="#<?php echo "$bgcolor"; ?>"><font color="#<?php echo 	"$col_table_row_text" ?>">



 		<?php echo (ucfirst ("$name")); ?></font></td>
    
 <td width="50%" bgcolor="#<?php echo "$bgcolor"; ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "&nbsp; $comment" ?></td>

<?php
}
}


$query="SELECT * FROM match_signup WHERE match_id = '$fileId' AND playing = 'maybe'";
$result=mysql_query($query);
$num=mysql_numrows($result);
?>
<table border="1" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" cellspacing="1" width="60%" id="signup">
    <tr bgcolor="#<?php echo "$col_table_header"; ?>">
      <td width="100%" colspan="2"><font color="#<?php echo "$col_table_header_text" ?>">Players That Are Unsure<br>
<font color="#<?php echo "$col_table_header_text" ?>"  size="2">  There are <?php echo " $num " ?> Unsure Player(s) 
</td>
    </tr>

<?php
while($row = mysql_fetch_array($result))
{

	if ($bgcolor === "$col_table_row")
	{
   $bgcolor = "$col_table_row2";
	} else {
   $bgcolor = "$col_table_row";
	} 

$name = $row["name"];
$playing = $row["playing"];
$comment = $row["comment"];

	if ($num !='') {
	?>
   	 <tr>
      <td width="50%" bgcolor="#<?php echo "$bgcolor"; ?>"><font color="#<?php echo 	"$col_table_row_text" ?>">

 		<?php echo (ucfirst ("$name")); ?></font></td>
    
 <td width="50%" bgcolor="#<?php echo "$bgcolor"; ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "&nbsp; $comment" ?></td>

<?php
}
}


$query="SELECT * FROM match_signup WHERE match_id = '$fileId' AND playing = 'no'";
$result=mysql_query($query);
$num=mysql_numrows($result);
?>
<table border="1" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" cellspacing="1" width="60%" id="signup">
    <tr bgcolor="#<?php echo "$col_table_header"; ?>">
      <td width="100%" colspan="2"><font color="#<?php echo "$col_table_header_text" ?>">Players That Can't Play <br>
<font color="#<?php echo "$col_table_header_text" ?>"  size="2">  There are <?php echo " $num " ?> Player(s) Unable To Play
</td>
    </tr>

<?php
while($row = mysql_fetch_array($result))
{

	if ($bgcolor === "$col_table_row")
	{
   $bgcolor = "$col_table_row2";
	} else {
   $bgcolor = "$col_table_row";
		} 

$name = $row["name"];
$playing = $row["playing"];
$comment = $row["comment"];

	if ($num !='') {
	?>
   	 <tr>
      <td width="50%" bgcolor="#<?php echo "$bgcolor"; ?>"><font color="#<?php echo 	"$col_table_row_text" ?>">

 		<?php echo (ucfirst ("$name")); ?></font></td>
    
 <td width="50%" bgcolor="#<?php echo "$bgcolor"; ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "&nbsp; $comment" ?></td>


</tr>
</div>

<?php
	}
	}

?>

</table>
<BR>

<body>

Add players to your team and allocate playing position

<center>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'];echo "?fileId="; echo "$fileId";?>">

<?php
$dbQuery = "SELECT  displayname "; 

$dbQuery .= "FROM users WHERE selected != 'yes'  "; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
$num=mysql_numrows($result);

if ($num > '0') {


echo '<select name="position">'; 
echo '<option value="1GoalKeeper">Goalkeeper</option>'; 
echo '<option value="2Defender">Defender</option>';  
echo '<option value="3Midfield">Midfield</option>';  
echo '<option value="4Forward">Forward</option>';  
echo '<option value="5Substitute">Substitute</option>';  
echo '<option value="6Not Specified">Not Specified</option>';  
?>
</select>

<SELECT NAME="player">

<?php
while($row = mysql_fetch_array($result))
print "<OPTION VALUE=\"$row[0]\">$row[0]</OPTION>\n";
?>




<input type="hidden" size="40" name="name" value="<?php echo "$name" ?>">





<BR><BR>
<input type="submit" name="submit" value="Add To Team">
<br><br><BR>
<input type="submit" name="reset" value="Reset List">





<?php }

ELSE {

 ?>

You have allocated all your players

<?php } ?>

<table border="1" cellspacing="1" bgcolor="#<?php echo "$col_table_header" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="55%" id="AutoNumber1">
    <tr>
      
<tr>
      <td width="5%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">No</td>
      <td width="25%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Player Name</td>
      <td width="5%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Position</td>
      <td width="17%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Remove</td>

    </tr>

    

<?php

$query="SELECT * FROM match_squad WHERE match_id = '$fileId' ORDER BY position ASC";
$result=mysql_query($query);
$name = $row["playername"];



while($row = mysql_fetch_array($result))
{

if ($bgcolor === "$col_table_row")
{
   $bgcolor = "$col_table_row2";
} else {
   $bgcolor = "$col_table_row";
} 


$position = $row["position"];

$shortpos = substr("$position", 1);

?>
    <tr>
</td>
      <td width="5%"  bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$number"; ?></td>
      <td width="25%"  bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo $row["playername"]; ?></td>
      <td width="17%"  bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$shortpos"; ?></td>
	<td width="19%" bgcolor="#<?php echo "$bgcolor" ?>"><a href="delfromsquad.php?squad_id=<?php echo $row["squad_id"]; ?>"><font color="#<?php echo "$col_link" ?>"><center>Remove</td>



    </tr>
  
<?php
++$number;
}

echo "</table>";
?>
<BR>
Click Below To Send Email To All Club Members Detailing Match Info.
<BR>
(Note: will send email to all members with a valid email address in their profile)
<BR>
<input type="submit" name="email" value="Email Team">

</form>
  </center>



</body>
</html>