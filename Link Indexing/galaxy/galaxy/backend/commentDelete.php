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
print '<span class="topRow">Use this form to delete a comment.</span><br />';
if(empty($_POST[jump])){
$sql = "select * from linkInformation ";
$sqlRes = mysql_query($sql, $conn) or die (mysql_error());
if(mysql_num_rows($sqlRes)<1){Print "There are no links for this operation to be applied to";}
else{
print "<p>What link does the comment pertain to?</p>
<form action=\"commentDelete.php\" method=\"post\">";
print '<select name="jump">';
while($sqlArray = mysql_fetch_array($sqlRes)){
        $id = $sqlArray['id'];
		$user = $sqlArray['user'];
		$link = $sqlArray['link'];
		$pageName = $sqlArray['pageName'];
		$description = $sqlArray['description'];
		$switch = $sqlArray['switch'];
        print " <option value=\"$id\">$link</option>";
		}
		print'</select><br /><input name="submit" type="submit" value="submit" />';
		print'</form>';
		}
		}


else{if (empty($_GET[status])){
$comId = $_POST[jump];
$sql = "select * from replyComments where threadId = $comId ";
$sqlRes = mysql_query($sql, $conn) or die (mysql_error());
if(mysql_num_rows($sqlRes)<1){Print "There are no users for this operation to be applied to";}
else{
print "<div align=\"center\"Choose a comment to delete ><br></div>
<form action=\"commentDelete.php?status=1\" method=\"post\">";
print '<div align="center"><br>';
while($sqlArray = mysql_fetch_array($sqlRes)){
        $id = $sqlArray['id'];
		$user = $sqlArray['userId'];
		$thread =$sqlArray['thread'];
		$comment = $sqlArray['comment'];
		        print "<input type=\"radio\" name=\"jump\" value=\"$id\">$comment<br><hr><br>";
		}
		print'<input name="submit" type="submit" value="submit" /></div>';
		print'</form>';
		}
		}
		else{
		$varp = $_POST[jump];
		$eraseQuery = "delete from replyComments where id = $varp ";
		$sqlRes2 = mysql_query($eraseQuery, $conn) or die ($eraseQuery . mysql_error());
		print "You have deleted this user and all links and comments associated with them<br>
		         <a href = \"loadTarget.htm\">Index</a><br>$varp";
		}
		}
		?>
</td></tr></table></body></html>
		
		