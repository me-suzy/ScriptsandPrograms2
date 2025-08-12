<?
include("conn.php");
require_once("cms.php");


$position = cmenupos2($change,$position,$menu);
//echo $position;

//$name = strtolower($name);
$name  = ucfirst($name);


$sql = "UPDATE category SET name ='$name', placement ='$placement',position = '$position' WHERE id='$id'";
$query = mysql_query($sql) or die("Cannot update record.<br>" . mysql_error());


// update main menu items
$sql2 = "SELECT * FROM menu WHERE cat ='$nold'";
$query2 = mysql_query($sql2) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query2)) {
$id = stripslashes($result["id"]);

$sql4 = "UPDATE menu SET cat ='$name' WHERE id='$id'";
$query4 = mysql_query($sql4) or die("Cannot update record.<br>" . mysql_error());

$sql5 = "UPDATE menu SET catposition ='$position' WHERE id='$id'";
$query5 = mysql_query($sql5) or die("Cannot update record.<br>" . mysql_error());

 $sql7 = "UPDATE menu SET placement ='$placement' WHERE id='$id'";
$query7 = mysql_query($sql7) or die("Cannot update record.<br>" . mysql_error());


}



?>

<HTML>
<HEAD>
<TITLE>Content Management </TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body onunload="opener.location=('catitems.php')">
<center>

<table width='90%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
 <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Category Name</td>
 <td width='75%' align = 'left'><?=$name?></td>
  </tr>

  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Category Placement</td>
        <td width='75%' align = 'left' bgcolor='#EAFDEB'><?=$placement?></td>
  </tr>

 <tr bgcolor='FFFFFF'>
 <td width='100%' colspan = '2' align = 'center'>
  <form method='post'>
    <div align='center' valign = 'middle'>
      <input type='button' value='Close Window' onclick='window.close()'>
    </div>
  </form>


 </td>
  </tr>

</table>



</center>

<br>
</body>
</html>
