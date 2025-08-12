<?php
include "header.php";





$dbQuery = "SELECT * "; 

$dbQuery .= "FROM admin "; 

$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row = mysql_fetch_array($result))


{
$admin_message = $row["admin_message"];
$title_message = $row["title_message"];


?> 


<HTML>
<HEAD>
<TITLE>Main Page</TITLE>
</HEAD>
<BODY>
<br>



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
<H3><center><?php echo "<font color='#$col_text'>"; echo "$title_message" ?></H3>

<p align="center"><?php echo "<font color='#$col_text'>"; echo nl2br ($admin_message) ?>
</p>
</td>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>

<br><br>

<?php
}
?>

<?php


$dbQuery = "SELECT * "; 
$dbQuery .= "FROM news ORDER BY news_id DESC LIMIT 0,10";
$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row = mysql_fetch_array($result))


{
$news = $row["news"];
$news_date = $row["news_date"];
$news_username = $row["news_username"];
$topic_image = $row["topic_image"];
$hyperlink_text1 = $row["hyperlink_text1"];
$hyperlink_text2 = $row["hyperlink_text2"];
$hyperlink_url1 = $row["hyperlink_url1"];
$hyperlink_url2 = $row["hyperlink_url2"];
$date = $row["news_date"];
$news_title = $row["news_title"];
$news_image = $row["news_image"];


$year = substr("$date", 0, 4);
$month = substr("$date", 5,-3);
$day = substr("$date", 8);

?> 



<center>
<table border="0" width="80%" height="30" cellpadding="0" cellspacing="0">
<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornertopleft.png" width="5" height="25" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbacktop.png" width="100%" height="25" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornertopright.png" width="5" height="25" alt=""></td>
</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxleft.png" width="5" height="100%" alt=""></td>
<td width="50%"><font color="#<?php echo "$col_text" ?>"  size="2"><P align="right"> Posted by <?php echo (ucfirst("$news_username")); ?> on <?php echo "$day"."-"."$month"."-"."$year" ?></font>
<?php
session_start();
if (($_SESSION['perm'] >= "3"))  {
?>	
<a href="newsimg.php?fileId=<?php echo $row["news_id"]; ?>"><img border="0" title="Add/Del Image" src="images/theme/<?php echo "$theme_col" ?>/img.gif"</a>

<a href="deletenews.php?fileId=<?php echo $row["news_id"]; ?>"><img border="0" title="Delete News Post" src="images/theme/<?php echo "$theme_col" ?>/delete.gif"</a>
<a href="editnews.php?fileId=<?php echo $row["news_id"]; ?>"><img border="0" title="Edit News Post" src="images/theme/<?php echo "$theme_col" ?>/edit.gif"</a>
<?php
}
?>

</td></p>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="25" alt=""></td>
</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxleft.png" width="5" height="100%" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="25" alt=""></td>
</tr>
<tr bgcolor="#cc00cc">
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxleft.png" width="5" height="100%" alt=""></td>
<td width="100%" bgcolor="#<?php echo "$col_back"; ?>" align="center">
    <img border="0" src="images/topic/<?php echo "$topic_image"; ?>" align="right" >

<p align="center"><font color="#<?php echo "$col_text" ?>"  size="3" ><u><b><?php echo "$news_title"; ?></font></b></u></p>

<p align="left"><font color="#<?php echo "$col_text" ?>"  size="2" ><?php echo nl2br ($news) ?></font></p>

<?php
if ($news_image != 'none') {
?>    <img border="0" src="images/news/<?php echo "$news_image"; ?>" align="center" >
<?php
}
?>
<p align="left"><a target="_blank" href="http://<?php echo $row["hyperlink_url1"]; ?>"><font color="#<?php echo "$col_link" ?>"><?php echo $row["hyperlink_text1"]; ?>

<p align="left"><a target="_blank" href="http://<?php echo $row["hyperlink_url2"]; ?>"><font color="#<?php echo "$col_link" ?>">
<?php echo $row["hyperlink_text2"]; ?>


</td>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>
<br>



</BODY> 
</HTML>

<br>
<?php

}
?>