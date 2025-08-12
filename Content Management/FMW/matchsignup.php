<?php
include "header.php";

?><font color="#<?php echo $col_text ?>"> <?php
	if ($logged_in == 0 OR $_SESSION[username] == '') { 
	die('You are not logged in, please login first');
		}

$fileId = $_GET['fileId'];

$query="SELECT username, displayname FROM users WHERE username = '$_SESSION[username]'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
{
$displayname = $row["displayname"];
}

$query="SELECT * FROM fixtures WHERE fix_id = '$fileId'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
{
$opp_team = $row["opp_team"];
$ground = $row["ground"];
$fix_date = $row["fix_date"];
$fix_time = $row["fix_time"];
$home_away = $row["home_away"];
$match_type = $row["match_type"];
$notes = $row["notes"];
$fix_id = $row["fix_id"];

}

	if ($_POST['submit'] == 'submit') {

$signup_comment = $_POST['signup_comment'];
$available = $_POST['available'];
$displayname = $_POST['displayname'];

	$dbQuery = "SELECT * "; 
	$dbQuery .= "FROM match_signup WHERE name = '$displayname' AND match_id = '$fileId' "; 
	$result2 = mysql_query($dbQuery) or die("Couldn't get file list");
	$num=mysql_numrows($result2);

		if ($num > '0') {
			$query="UPDATE match_signup SET playing = '$available', comment='$signup_comment'
	 		WHERE name= '$displayname' AND match_id = '$fileId' ";
			@mysql_select_db($db_name) or die( "Unable to select database");
			mysql_query($query);
		}
ELSE {
	$query="INSERT INTO match_signup (match_id, name, playing, comment) 
	VALUES ($fileId, '$displayname', '$available', '$signup_comment') ";
	mysql_query($query); 
		}

?>
<meta HTTP-EQUIV="Refresh" CONTENT="0; URL=fixture.php<?php echo "?fileId="; echo "$fileId";?>">
<?php

	}
?>

<br>

<html>

<head>

<title>Match Signup</title>
</head>
<BODY>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];echo "?fileId="; echo "$fileId";?>">


<center>
<table border="0" width="40%" height="30" cellpadding="0" cellspacing="0">
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
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>Match signup<br><br></b>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>

<table border="1" width="40%" height="54">
<tr bgcolor="#<?php echo "$col_table_row2" ?>">
    <td width="100%" colspan="2" height="37"><font color="#<?php echo "$col_table_row_text" ?>">
      <p align="center" >Select Your Availability Below</td>
  </tr>

  <tr bgcolor="#<?php echo "$col_table_row" ?>">
    <td width="100%" colspan="2" height="37">
      <p align="center" >

<?php
echo '<select name="available">'; 
echo '<option value="yes">Available To Play</option>'; 
echo '<option value="no">Not Available To Play</option>';  
echo '<option value="maybe">Not Sure Yet</option>';  

?>


</td>
  </tr>
  <tr bgcolor="#<?php echo "$col_table_row2"?>">
    <td width="23%" height="38" ><font color="#<?php echo "$col_table_row_text" ?>">Comments:</td>
    <td width="77%" height="38"><input id="comment" size="40" name="signup_comment"><br>
<input type="hidden" name="displayname" value="<?php echo "$displayname"; ?>"><br>

</td>
  </tr>
</table>
  </center>
</div>
<BR>
<input type="Submit" name="submit" value="submit">
</form>
</body>

</html>