<?php
session_start();
if(!$_SESSION["passcode"])
{
include("relogin.html");
exit();
}?>
<link href="stylesheets/admin-panel.css" rel="stylesheet" type="text/css">
</head>
<body>
<br>
<table border="0" align="center">
  <tr >
	<td class="text-design2">Page Order&nbsp;</td>
    <td class="text-design2">Page Name&nbsp;</td>
    <td class="text-design2">Page Heading&nbsp;</td>
	<td class="text-design2">Edit</td>
	<td class="text-design2">Delete</td>
  </tr>
  <?php 
  include ("connect.php");
  $result = mysql_query (" SELECT name,heading,serial,pageorder FROM pages ORDER By pageorder") or die(mysql_error());
  while($row=mysql_fetch_array($result)){

//  $result1 = mysql_query ("SELECT parent from links where page='$row[0]'");
// $row2 = mysql_fetch_row($result1);
 
?>
  <tr>
  <td class="text-design1" align="center"><?php echo $row[3];?></td> 
  <td class="text-design1"><?php echo $row[0];?></td>
  <td class="text-design1"><?php echo $row[1];?></td>

<? //<td class="text-design1"><?php echo $row2[0];*/?><?//</td>?>

  
  <td width="100" class="text-design1" align="center"><a href="editpage.php?var=<?php echo $row[0] ?>">Edit Page</a></td>
  <?php
  if($row[0] == "index")
  {
  ?>
  <td>&nbsp;</td>
  <?php
  }
  else
  {
  ?>
  <td width="100" class="text-design1" align="center"><a href="delpage.php?var=<?php echo $row[0] ?>">Delete Page</a></td>
  <?php
  }
  ?>
  </tr>
  <?php 
  }//End of While
  ?>
</table>

<p class="headingcenter"><a href="options.php">Back to Options</a></p>

