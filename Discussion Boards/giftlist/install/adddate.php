<?php
include "header.php";
// require('db_connect.php');	// database connect script.
?>

<html>
<head>
<title>Add a gift</title>
</head>
<body>

<?php

$givedate = ("$dropyear-$dropmonth-$dropday");
$date=date("Y-m-d");

if ($givedate < $date ) {
		die('The date must be no earlier than todays date. Please click `back` and re-enter date.');
	}


if (!$_POST['giver']) {
		die('You must specify the name of the gift `giver`');
	}








$query="UPDATE gifts SET buyer=('$_SESSION[username]') WHERE gift_id='$fileId'";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query);

$query="UPDATE gifts SET give_date='$givedate', giver='$giver' WHERE gift_id='$fileId'";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query);

?>

Gift Added to your buy List
<br><br><br>





<?


$dbQuery = "SELECT DISTINCT username "; 

$dbQuery .= "FROM gifts WHERE username != ('$_SESSION[username]') AND del_gift='no' "; 

$dbQuery .= "ORDER BY username ASC";

$result = mysql_query($dbQuery) or die("Couldn't get file list");

?>


<table align="center" border="1" cellpadding="0" cellspacing="0" bordercolor="#111111" width="70%">

<tr>

<td width="20%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font size="2" face="Verdana" color="#FFFFFF"> 

Users With Gift Lists</font></b></td>


<td width="18%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font face="Verdana" size="2" color="#FFFFFF"> 

Wanted List</font></b></td>

 


<td width="18%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font face="Verdana" size="2" color="#FFFFFF"> 

Bought List</font></b></td>

</tr> 

<?php

while($row = mysql_fetch_array($result)) 

{ 

?>

<tr> 

<td width="20%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10; margin-right: 10"> 

<font face="Verdana" size="1"> 

<?php echo $row["username"]; ?> 

</font> 

</td> 






<td width="13%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10"><font face="Verdana" size="1"> 

<a href="showusergifts.php?fileId=<?php echo $row["username"]; ?>"> 

Wanted List 

</a></font> 

</td> 


<td width="18%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10"><font face="Verdana" size="1"> 

<a href="boughtlist.php?fileId=<?php echo $row["username"]; ?>"> 

Bought List 

</a></font> 

</td> 


</tr>

<?php 

}
echo "</table>"; 


?>



