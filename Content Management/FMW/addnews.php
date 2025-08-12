<?php
include "header.php";
session_start();
if (($_SESSION['perm'] < "3"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}





$newsdate = date('Y-m-d');

$query="SELECT * FROM news";
$result=mysql_query($query);
$num=mysql_numrows($result); 


if ($_POST['submit'] == 'submit') {
$add_news = $_POST['add_news'];
$hyperlink_text1 = $_POST['hyperlink_text1'];
$hyperlink_text2 = $_POST['hyperlink_text2'];
$hyperlink_url1 = $_POST['hyperlink_url1'];
$hyperlink_url2 = $_POST['hyperlink_url2'];
$topic_image = $_POST['topic'];
$news_title = $_POST['news_title'];
$upload = $_POST['upload'];


if (!$_POST['add_news'] | !$_POST['news_title']) {
		die('You must supply a news title and news entry text');
	}



$query="INSERT INTO news (news_username, news, news_date, hyperlink_url1, hyperlink_url2, hyperlink_text1, hyperlink_text2, topic_image, news_title) 
VALUES ('$_SESSION[username]', '$add_news', '$newsdate', '$hyperlink_url1', '$hyperlink_url2', '$hyperlink_text1', '$hyperlink_text2', '$topic_image', '$news_title') ";
mysql_query($query); 

if ($upload == 'no') {
?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=main.php"> <?php
	}
Else {

?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=addnewsimg.php"> <?php

	}
} 



?>

<HTML>
<HEAD>
<TITLE>News entry</TITLE>
</HEAD>
<BODY>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
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
<td width="100%" bgcolor="#<?php echo "$col_back"; ?>" align="center">
<?php echo "<font color='#$col_text'>"; ?>
Enter title of your news post (max 50 characters)<br>
<input id="news_title" size="50" name="news_title"><br>



<H4>Type your news post in the box below  <br>
<textarea rows=8 cols=80 name="add_news"></textarea><br>
Enter hyperlinks to appear in news post.<br><br>

Enter text to be displayed for link in below box:<br>
<input id="hyperlink_text1" size="80" name="hyperlink_text1"><br>
Enter URL for link. i.e www.example.com (do not enter http://)<br>
<input id="hyperlink_url1" size="80" name="hyperlink_url1"><br>
<BR><BR>

Enter text to be displayed for link in below box:<br>
<input id="hyperlink_text2" size="80" name="hyperlink_text2"><br>
Enter URL for link. i.e www.example.com (do not enter http://)<br>
<input id="hyperlink_url2" size="80" name="hyperlink_url2"><br>
<br>
Add image to news post ?<br>
(image upload will be on next screen)<br>

<?php
echo '<select name="upload">'; 
echo '<option value="no">No</option>'; 
echo '<option value="yes">Yes</option>';  
?>
<br>
</select>


</td>

<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>


<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>
</H4>
<?php echo "<font color='#$col_text'>"; ?>
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
<input type="Submit" name="submit" value="submit">
</form>
</H4>
<a target="_blank" href="topicimage.php"><font color="#<?php echo $col_link ?>">Click To View Topic Images</font></a>
</font>
</BODY> 
</HTML>

