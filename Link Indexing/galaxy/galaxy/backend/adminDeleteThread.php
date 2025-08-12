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
print '<span class="topRow">Use this form to delete links and all related comments</span><br />';
if(empty($_POST[jump])){
$sql = "select * from linkInformation ";
$sqlRes = mysql_query($sql, $conn) or die (mysql_error());
if(mysql_num_rows($sqlRes)<1){Print "There are no links for this operation to be applied to";}
else{
print "<form action=\"adminDeleteThread.php\" method=\"post\">";
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
		print'</select><br /><input name="submit" type="submit"value ="submit" />';
		print'</form>';
		}
		}
		else{
		$var = $_POST[jump];
		$sql1 = "delete from linkInformation where id = $var";
        $sql2 = "delete from replyComments where threadId = $var";
		$sqlRes1 = mysql_query($sql1, $conn) or die ($sql1 . mysql_error());
		$sqlRes2 = mysql_query($sql2, $conn) or die ($sql2 . mysql_error());
		print "You have deleted this thread<br />Please choose another option from the left.";
				}
		?>
</td></tr></table></body></html>