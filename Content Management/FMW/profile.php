<?php
include "header.php";

$fileId = $_GET['fileId'];
$dbQuery = "SELECT * "; 

$dbQuery .= "FROM users WHERE id = '$fileId'"; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row = mysql_fetch_array($result)) {

$avatar = $row["avatar"];
$user = $row["username"];
$email = $row["email"];
$msn = $row["msn"];
$icq = $row["icq"];
$aim = $row["aim"];
$tel = $row["tel"];
$position = $row["position"];
$age = $row["age"];
$yim = $row["yim"];
$joindate = $row["joindate"];
$clubs = $row["clubs"];
$profile = $row["profile"];
$nickname = $row["nickname"];
$displayname = $row["displayname"];
$role = $row["role"];				
$interests = $row["interests"];
}

?>

<br>

<html>

<head>

<title>Player Profile</title>
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
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>Team Member Profile For<br><?php echo "$displayname" ?><br><br></b>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>



<div align="center">
  <center>
  <table border="1" bgcolor="#<?php echo "$col_back" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="60%" height="160">
    <tr>

      <td width="100%" height="154">
<p align="center"><font color="#<?php echo "$col_text" ?>"><p align="center"><img src="images/avatar/<?php echo "$avatar" ?>"> </font></td>
</td>
    </tr>
  </table>
  </center>
</div>
<div align="center">
  <center>
  <table border="1" bgcolor="#<?php echo "$col_back" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>"< width="50%" >
    <tr>







 <td width="39%"  bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >&nbsp; Nick Name</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php
	if ($nickname == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" >Not Available</font>
</tr>
<?php
}
else{
?>
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$nickname"; ?></font>
    </tr>
<?php
} ?>
</td>    
</tr>

       <td width="39%" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>"size="2" >&nbsp; Position / Role</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php
	if ($position == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" >Not Available</font>
</tr>
<?php
}
else{
?>
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$position"; ?></font>
    </tr>
<?php
} ?>
</td>    
</tr>


 <td width="39%" bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>"size="2" >&nbsp; Date Joined Club</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php
	if ($joindate == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" >Not Available</font>
</tr>
<?php
}
else{
?>
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$joindate"; ?></font>
    </tr>
<?php
} ?>
</td>    
</tr>




    <tr>
      <td width="39%" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>"size="2" >&nbsp; Age</td>      
		<td width="61%" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php
	if ($age == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" >Not Available</font>
</tr>
<?php
}
else{
?>
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$age"; ?></font>
    </tr>
<?php
} ?>
</td>
    </tr>
    <tr>
      <td width="39%" bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >&nbsp;&nbsp;Email:</td>
      <td width="61%" bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php
	if ($email == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" <img src="images/email3.gif"  border ="0" >&nbsp;Not Available</font>
</tr>
<?php
}
else{
?><font color="#<?php echo "$col_link" ?>" size="2" ><a href="mailto:<?php echo "$email" ?>"><font color="#<?php echo "$col_link" ?>" size="2" ><img src="images/email3.gif" border="0" >&nbsp;<?php echo "$email" ?></font></a>
    </tr>
<?php
} ?>
</td>
    </tr>
    <tr>
      <td width="39%" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >&nbsp;&nbsp;ICQ:</td>
      <td width="61%" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php
	if ($icq == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" <img src="images/im_icq.gif"  >Not Available</font>
</tr>
<?php
}
else{
?>
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><img src="images/im_icq.gif" ><?php echo "$icq"; ?></font>
    </tr>
<?php
} ?>
</td>
    </tr>
    <tr>
      <td width="39%" bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >&nbsp;&nbsp;MSN:</td>
      <td width="61%" bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php
	if ($msn == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" <img src="images/im_msn.gif" >Not Available</font>
</tr>
<?php
}
else{
?>
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><img src="images/im_msn.gif" ><?php echo "$msn"; ?></font>
    </tr>
<?php
} ?>
</td>
    </tr>
    <tr>
      <td width="39%" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2"  >&nbsp; AIM:</td>
      <td width="61%" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php
	if ($aim == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" >Not Available</font>
</tr>
<?php
}
else{
?>
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$aim"; ?></font>
    </tr>
<?php
} ?>
</td>
    </tr>




 <td width="39%" bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >&nbsp; YIM</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php
	if ($yim == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" >Not Available</font>
</tr>
<?php
}
else{
?>
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$yim"; ?></font>
    </tr>
<?php
} ?>
</td>    
</tr>








    <tr>
      <td width="39%" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >&nbsp; TEL:</td>
      <td width="61%" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php
	if ($logged_in == 0 OR $_SESSION[username] == '' OR $tel == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" >Not Available</font>
</tr>
<?php
}
else{
?>
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$tel"; ?></font>
    </tr>
<?php
} ?>
</td>
    </tr>
  </table>
  </center>
</div>
<div align="center">
  <center>
  <table border="1" bgcolor="#<?php echo "$col_back" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="675" height="111">
    <tr>

      <td width="90%" height="5"  align="center" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Profile</td>

    </tr>
    <tr>
      <td width="90%" height="40" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$profile" ?></td>

    </tr>


<table border="1" bgcolor="#<?php echo "$col_back" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="675" height="111">
    <tr>
      <td width="90%" height="5" align="center" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Interests</td>

    </tr>
    <tr>
      <td width="90%" height="40" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$interests" ?></td>

    </tr>

<table border="1" bgcolor="#<?php echo "$col_back" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="675" height="111">
    <tr>
      <td width="90%" height="5" align="center" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Previous Clubs</td>

    </tr>
    <tr>
      <td width="90%" height="40" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?Php echo "$clubs" ?></td>

    </tr>





  </table>
  </center>
</div>
</body>

</html>