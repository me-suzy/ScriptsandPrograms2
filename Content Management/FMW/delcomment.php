<?php
include "header.php";

session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}


$commentId = $_GET['commentId'];


$query="SELECT * FROM match_comment WHERE comment_id = '$commentId' ";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
$match_id = $row["match_id"];
							}


if ($_POST['Delete'] == 'Delete') {
$comment_id = $_POST['comment_id'];
$match_id = $_POST['match_id'];


mysql_query("DELETE FROM match_comment WHERE comment_id='$comment_id'")
or die(mysql_error());

?><meta HTTP-EQUIV="Refresh" CONTENT="0; URL=matchcomment.php<?php echo "?fileId="; echo "$match_id";?>"><?php 

}
?>




<HTML>
<HEAD>
<TITLE>Delete comment Post</TITLE>
</HEAD>
<BODY>
<h3><?php echo "<font color='#$col_text'>"; ?>Delete Comment ?</h3>


<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">

<input type="hidden" name="comment_id" value="<? echo $commentId; ?>">
<input type="hidden" name="match_id" value="<? echo $match_id; ?>">


Confirm Delete Record ?<br>
<input type="Submit" name="Delete" value="Delete">


</BODY> 
</HTML>