<?php
include "header.php";

$fileId = $_GET['fileId'];

$query="SELECT * FROM teams WHERE team_name = '$fileId'";
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
$own_team = $row["own_team"];

}



if ($own_team == 'yes') {
?><meta HTTP-EQUIV="Refresh" CONTENT="0; URL=ownteamprofile.php"><?php
}


?>

<br>

<html>

<head>

<title>Team Profile</title>
</head>



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
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>Team Profile For<br><?php echo "$team_name" ?><br><br></b>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>


<div align="center">
  <center>
  <table border="1" bgcolor="#<?php echo "$col_back" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>"< width="50%" >
    <tr>

 <td width="39%"  bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >&nbsp; Contact Name</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php



	if ($contact_name == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" >Not Available</font>
</tr>
<?php
}
else{
?>
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$contact_name"; ?></font>
    </tr>
<?php
} ?>
</td>    
</tr>



<td width="39%"  bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >&nbsp; Contact Email</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php
	if ($contact_email == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" >Not Available</font>
</tr>
<?php
}
else{
?>
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><a href="mailto:<?php echo "$contact_email" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$contact_email"; ?></font>
    </tr>
<?php
} ?>
</td>    
</tr>



<td width="39%"  bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >&nbsp; Contact Tel</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php
	if ($contact_tel == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" >Not Available</font>
</tr>
<?php
}
else{
?>
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$contact_tel"; ?></font>
    </tr>
<?php
} ?>
</td>    
</tr>


<td width="39%"  bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >&nbsp; Ground Name</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php
	if ($ground_name == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" >Not Available</font>
</tr>
<?php
}
else{
?>
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$ground_name"; ?></font>
    </tr>
<?php
} ?>
</td>    
</tr>


<td width="39%"  bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >&nbsp; Home Strip</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php
	if ($home_strip == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" >Not Available</font>
</tr>
<?php
}
else{
?>
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$home_strip"; ?></font>
    </tr>
<?php
} ?>
</td>    
</tr>


<td width="39%"  bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >&nbsp; Away_strip</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php
	if ($away_strip == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" >Not Available</font>
</tr>
<?php
}
else{
?>
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$away_strip"; ?></font>
    </tr>
<?php
} ?>
</td>    
</tr>










     
<div align="center">
  <center>
  <table border="1" bgcolor="#<?php echo "$col_back" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="50%" height="111">
    <tr>

      <td width="50%" height="5"  align="center" bgcolor="#<?php echo "$col_table_header" ?>">
<center><font color="#<?php echo "$col_table_header_text" ?>">Club Contact Address<br><textarea rows=6 cols=60 name="news"><? echo  "$contact_address" ?></textarea>
</td>
   </tr>

  </table>
  </center>
</div>
</body>

</html>