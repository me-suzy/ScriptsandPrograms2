<?php
session_start();
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?><?php require_once('Connections/poll.php'); ?>
<?php
mysql_select_db($database_poll, $poll);
$query_Recordset1 = "SELECT * FROM flash_poll";

$Recordset1 = mysql_query($query_Recordset1, $poll) or die(mysql_error());



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Poll List.::.Here you can edit,modify or delete polls</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {color: #FF0000}
-->
</style>
</head>

<body>
<p><strong>Flash Poll System</strong></p>
<p>&nbsp;</p>
<p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Poll List </strong></p>
<p>&nbsp; </p><table  border="0" align="center" bgcolor="#666666">
  <tr><td><table width="100%" height="100%"  cellpadding="1" cellspacing="1">
<?php 
if (mysql_num_rows($Recordset1) > 0) { 
    while($row = mysql_fetch_row($Recordset1)) {echo "<tr>";
        echo  "<td" ?> bgcolor="#DFECF7"><?php if($row[19]==1){?> <span class="style1"><?php echo $row[1]."</span>"."</td>"; } else {echo $row[1]."</td>";};
		        echo  "<td" ?> bgcolor="#DFECF7"><?php if ($row[19]==1){?><a href="close.php?idf=<?php echo $row[0];?>">Close</a><?php ; echo "<br>"; }
		else {?><a href="open.php?idf=<?php echo $row[0];?>">Open</a><?php ; echo "</td>";}
		        echo  "<td" ?> bgcolor="#DFECF7"><a href="epoll.php?idf=<?php echo $row[0];?>">Edit</a><?php ; echo "</td>";
			        echo  "<td" ?> bgcolor="#DFECF7"><a href="view.php?idf=<?php echo $row[0];?>">View</a><?php ; echo "</td>";
        echo  "<td" ?> bgcolor="#DFECF7"><a href="delete.php?idf=<?php echo $row[0];?>">Delete</a><?php ; echo "</td>";			

    echo "</tr>";} 
} 

?>
      </table></td></tr></table>


</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
