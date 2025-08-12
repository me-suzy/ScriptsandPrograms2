<?php
include "header.php";

session_start();
if (($_SESSION['perm'] < "3"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}

$fileId = $_GET['fileId'];
$newsdate = date('Y-m-d');

$query="SELECT * FROM news WHERE news_id = '$fileId'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
{
$news_title = $row["news_title"];
$news = $row["news"];
$hyperlink_text1 = $row["hyperlink_text1"];
$hyperlink_text2 = $row["hyperlink_text2"];
$hyperlink_url1 = $row["hyperlink_url1"];
$hyperlink_url2 = $row["hyperlink_url2"];
$topic_image = $row["topic"];





?>

<HTML>
<HEAD>
<TITLE>Edit News Entry</TITLE>
</HEAD>
<BODY>
<form action="newsupdated.php" method="post">
<input type="hidden" name="news_id" value="<? echo $fileId; ?>">
<center>
<table border="0" width="80%" height="30" cellpadding="0" cellspacing="0">
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
<td width="100%"  bgcolor="#<?php echo "$col_back"; ?>" align="center">
<H4><?php echo "<font color='#$col_text'>"; ?>EDIT NEWS ENTRY<BR><br>
Enter title of your news post (max 50 characters)<br>
<input id="news_title" size="50" name="news_title" value="<?php echo "$news_title" ?>"><br>

Type your news post in the box below  <br>
<textarea rows=6 cols=60 name="news"><? echo $news ?></textarea>
<br><br>
Enter hyperlinks to appear in news post.<br><br>

Enter text to be displayed for link in below box:<br>
<input id="hyperlink_text1" size="80" name="hyperlink_text1" value="<?php echo "$hyperlink_text1" ?>"><br>
Enter URL for link. i.e www.example.com (do not enter http://)<br>
<input id="hyperlink_url1" size="80" name="hyperlink_url1" value="<?php echo "$hyperlink_url1" ?>"><br>
<BR><BR>

Enter text to be displayed for link in below box:<br>
<input id="hyperlink_text2" size="80" name="hyperlink_text2" value="<?php echo "$hyperlink_text2" ?>"><br>
Enter URL for link. i.e www.example.com (do not enter http://)<br>
<input id="hyperlink_url2" size="80" name="hyperlink_url2" value="<?php echo "$hyperlink_url2" ?>"><br>
<br><br>

</td>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>
</H4>

<H4>Select Topic To Post story Under
<br>
<?php
$dbQuery = "SELECT  topic_image, topic_image_name ";
$dbQuery .= "FROM topic ";

$result = mysql_query($dbQuery) or die("Couldn't get file list");
$num=mysql_numrows($result);
if ($_POST['submit'] == 'submit') {


}?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<SELECT NAME="topic">

<?php
while($row = mysql_fetch_array($result))

print "<OPTION VALUE=\"$row[0]\">$row[1]</OPTION>\n";
?>
</select> 
<input type="Submit" name="submit" value="Update Entry">
</form>
</H4>
<a target="_blank" href="topicimage.php"><font color="#<?php echo $col_link ?>">Click To View Topic Images</font></a>
</font>
</BODY> 
</HTML>

<?php
}
?>