<?php
require('db_connect.php');
$dbQuery = "SELECT rights "; 

$dbQuery .= "FROM users WHERE username = ('$_SESSION[username]')"; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row = mysql_fetch_array($result))

{ 
$permission = "$row[rights]";      // get access level
    $_SESSION["perm"] = "$permission";      // make session variables 


}
session_start();
if (($_SESSION['perm'] < "3")) {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}

$dbQuery = "SELECT * "; 

$dbQuery .= "FROM admin "; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row = mysql_fetch_array($result))

{ 



$col_back = $row["col_back"];
$col_text = $row["col_text"];
$col_link = $row["col_link"];
$col_table_row = $row["col_table_row"];
$col_table_row2 = $row["col_table_row2"];
$col_table_header = $row["col_table_header"];
$col_table_border = $row["col_table_border"];
$col_table_border_2 = $row["col_table_border_2"];
$col_table_row_text= $row["col_table_row_text"];
$col_table_header_text = $row["col_table_header_text"];
$currency = $row["currency"];
$logo_pos = $row["logo_pos"];
$texture = $row["texture"];
$admin_message = $row["admin_message"];
$title_message = $row["title_message"];
$theme_col = $row["theme_col"];

}

?>

<html>
<head>
<body  background="textures/<?php echo "$texture" ?>.jpg">
<body bgcolor="#<?php echo "$col_back"; ?>">

<br><br>
<center>
<H4>

<?php

$dbQuery = "SELECT * "; 
$dbQuery .= "FROM topic ORDER BY topic_image_name";
$result = mysql_query($dbQuery) or die("Couldn't get file list");


?>

<HTML>
<HEAD>
<TITLE>Topic Images</TITLE>
</HEAD>
<BODY>

<center>
<table border="0" width="90%" height="30" cellpadding="0" cellspacing="0">
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
<H3><center><?php echo "<font color='#$col_text'>"; echo "Topic Images" ?></H3>
<?php
while($row = mysql_fetch_array($result))


{
$topic_image = $row["topic_image"];
$topic_image_name = $row["topic_image_name"];
?>

<br><br>
<img src="images/topic/<?php echo "$topic_image"; ?>"><br>
<?php echo "$topic_image_name"; 

}
?>
</td>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>


</font>

</BODY> 
</HTML>

