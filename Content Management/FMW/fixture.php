<?php
include "header.php";

$fileId = $_GET['fileId'];

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

?>

<br>

<html>

<head>

<title>Fixture</title>
</head>
<BODY>

	<a href="matchsignup.php?fileId=<?php echo "$fix_id"; ?>"><font color="#<?php echo "$col_link" ?>"> 'Click' To Signup For Match </a><BR><BR> 


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
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>Match Fixture<br><br></b>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>


<div align="center">
  <center>
  <table border="1" bgcolor="#<?php echo "$col_back" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" >
    <tr>

 <td width="39%"  bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >&nbsp; Team Opposition</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;

<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$opp_team"; ?></font>
    </tr>
</td>    
</tr>



<td width="39%"  bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >&nbsp; Ground Playing At</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php
	if ($ground == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" >Not Available</font>
</tr>
<?php
}
else{
?>
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$ground"; ?></font>
    </tr>
<?php
} ?>
</td>    
</tr>



<td width="39%"  bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >&nbsp; Match Type</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;

<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$match_type"; ?></font>
    </tr>
</td>    
</tr>


<td width="39%"  bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >&nbsp; Fixture Date</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$fix_date"; ?></font>
    </tr>

</td>    
</tr>


<td width="39%"  bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >&nbsp; Kick Off Time</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$fix_time"; ?></font>
    </tr>
</td>    
</tr>


<td width="39%"  bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >&nbsp; Home or Away</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php
	if ($home_away == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" >Not Available</font>
</tr>
<?php
}
else{
?>
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$home_away"; ?></font>
    </tr>
<?php
} ?>
</td>    
</tr>
     

<div align="center">
  <center>
  <table border="1" bgcolor="#<?php echo "$col_back" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="60%" height="111">
    <tr>

      <td width="60%" height="5"  align="center" bgcolor="#<?php echo "$col_table_header" ?>">
<center><font color="#<?php echo "$col_table_header_text" ?>">Additional Match Notes<br><textarea rows=6 cols=67 name="notes"><? echo  "$notes" ?></textarea>
</td>
   </tr>

  </table>



<?php

if ($logged_in == 1) { 
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
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>Players Signed Up<br><br></b>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>


<?php
$query="SELECT * FROM match_signup WHERE match_id = '$fileId' AND playing = 'yes'";
$result=mysql_query($query);
$num=mysql_numrows($result);
?>
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

?> 
    </tr>
  </table>






<?php
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


?>






<?php
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

<?php
}
}
}

?>
</table>



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
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>Current Squad Selection<br><br></b>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>




<?php
	if ($logged_in == 0 OR $_SESSION[username] == '') {
?>
Match Squad Details Only Available To Members<BR>
(If you are a member, please log in)
<?php 	
}
else {
?>

<table border="1" cellspacing="1" bgcolor="#<?php echo "$col_table_header" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="60%" id="AutoNumber1">
    <tr>
      
<tr>
      <td width="5%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">No</td>
      <td width="25%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Player Name</td>
      <td width="5%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Position</td>


    </tr>

<?php
$number ='1';

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




    </tr>
  
<?php
++$number;
}
}
?>


  </center>
</div>
</body>

</html>