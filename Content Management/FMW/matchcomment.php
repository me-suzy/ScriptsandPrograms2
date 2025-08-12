<?php
include "header.php";
?><font color="#<?php echo $col_text ?>"><?php


$fileId = $_GET['fileId'];


$query="SELECT * FROM league_table WHERE match_id = '$fileId' AND match_tag = 'yes' ";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))  {
$match_report = $row["match_report"];
							}

$query="SELECT displayname FROM users WHERE username = '$_SESSION[username]' ";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))  {
$displayname = $row["displayname"];
							}

						
						
		if ($_POST['submit'] == 'submit') {
		$name = $_POST['name'];
		$comment = $_POST['comment'];
	

			if (!$_POST['name'] | !$_POST['comment']) {
			die('You must supply a name and comment');
										}



		$query="INSERT INTO match_comment (name, comment, match_id) 
		VALUES ('$name', '$comment', '$fileId') ";
		mysql_query($query); 
								}

?>
<HTML>
<HEAD>
<TITLE>News entry</TITLE>
</HEAD>
<BODY>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];echo "?fileId="; echo "$fileId";?>">
<center>
<table border="0" width="502" height="30" cellpadding="0" cellspacing="0">
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
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>Match Report</b>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>
<div align="center">
  <center>

<textarea rows=6 cols=60 name="news"><? echo $match_report ?></textarea>

<?php
$query="SELECT * FROM match_comment WHERE match_id = '$fileId' ORDER BY comment_id DESC ";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
$name = $row["name"];
$comment = $row["comment"];
							
?>

<table border="1" cellspacing="1" bgcolor="#<?php echo "$col_table_header" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="502" id="AutoNumber1">
    <tr>
      
<tr>

<td width="17%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Posted by <?php echo "$name" ?>

<?php
session_start();
if (($_SESSION['perm'] == "5"))  {
?>
<BR>
<a href="delcomment.php?commentId=<?php echo $row["comment_id"]; ?>"><font color="#<?php echo $col_link ?>">Delete</font>
<?php
}
?>



</td>

    </tr>

    <tr>
</td>
      <td width="17%"  bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$comment" ?></td>
</tr>
</table>

<?php } ?>

<BR>
Enter Your Comment<BR>
<input id="comment" size="90" name="comment"><br><BR>
Enter Your Name<BR>
<input id="name" size="30" name="name" value="<?php echo "$displayname"; ?>" ><br>
<input type="hidden" name="match_id" value="<? echo $fileId; ?>">


<input type="Submit" name="submit" value="submit">


</BODY> 
</HTML>

