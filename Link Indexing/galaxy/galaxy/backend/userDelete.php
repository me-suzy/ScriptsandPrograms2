<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Galaxy Link Database</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../galaxy.css" rel="stylesheet" type="text/css" />
</head>
<body>

<?php
include('../functions.php');

print "$backHead";
print '<span class="topRow">Use this form to delete a user and all links and comments related with them</span><br />';
if(empty($_POST[jump])){
$sql = "select * from userData ";
$sqlRes = mysql_query($sql, $conn) or die (mysql_error());
if(mysql_num_rows($sqlRes)<1){Print "There are no users for this operation to be applied to";}
else{
print "<form action=\"userDelete.php\" method=\"post\">";
print '<select name="jump">';
while($sqlArray = mysql_fetch_array($sqlRes)){
        $id = $sqlArray['id'];
		$user = $sqlArray['userName'];
		$email = $sqlArray['userEmail'];
        print " <option value=\"$id\">$user</option>";
		}
		print'</select><br /><input name="submit" type="submit" value = "submit" />';
		print'</form>';
		}
		}
		else{
		$var = $_POST[jump];
		$getThreads = "select id from linkInformation where user = $var";
		$getThreadsRes = mysql_query($getThreads, $conn) or die (mysql_error());
		while($erase = mysql_fetch_array($getThreadsRes)){
		$num = $erase['id'];
		$eraseQuery = "delete from replyComments where threadId = $num ";
		$sqlRes2 = mysql_query($eraseQuery, $conn) or die ($eraseQuery . mysql_error());
		}
		$sql1 = "delete from userData where id = $var";
		$sql2 = "delete from replyComments where usrId = $var";
		$sql3 = "delete from linkInformation where user = $var";
		$sqlRes1 = mysql_query($sql1, $conn) or die ($sql1 . mysql_error());
		$sqlRes2 = mysql_query($sql3, $conn) or die ($sql2 . mysql_error());
		$sqlRes3 = mysql_query($sql3, $conn) or die ($sql3 . mysql_error());
		print "You have deleted this user and all links and comments associated with them<br />
		         Please choose another option from the left";
				}
		?>
</td></tr></table></body></html>