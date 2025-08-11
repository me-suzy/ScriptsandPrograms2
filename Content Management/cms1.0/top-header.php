<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
include("connect.php");
$header_query1 = "SELECT * FROM header ORDER by serial DESC LIMIT 1";
$header_result1 = mysql_query($header_query1) or die (mysql_error());
$header_data1 = mysql_fetch_array($header_result1);

$header_query2 = "SELECT * FROM stylesheet WHERE active = \"y\" LIMIT 1";
$header_result2 = mysql_query($header_query2) or die (mysql_error());
$header_data2 = mysql_fetch_array($header_result2);

$pageid = ($GET['id']);
$header_query3 = "SELECT name FROM pages where serial = '$id'";
//echo $header_query3;
$header_result3 = mysql_query($header_query3) or die (mysql_error());
$header_data3= mysql_fetch_array($header_result3);

?>
<html>
<head>
<title><?php echo $header_data1["company"] . " " . $header_data3["name"]; ?></title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="stylesheets/<?php echo $header_data2["sname"]; ?>.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="88%" border="0" align="center" cellspacing="0" background="images/<?php echo $header_data1["bimage"];?>">
  <tr class="header"> 
    <td > 
      <p> 
	  <a href="index.php"><span class = "company"> <?php echo $header_data1["company"] ; ?>  </span></a>
	   <span class="punchline"><?php echo $header_data1["punchline"]; ?></span>
	  </p>
  </td>
    <td>
	  <a href="index.php"><img src="images/<?php echo $header_data1["logo"];?>" border="0"></a>
	</td>
  </tr>
</table>
