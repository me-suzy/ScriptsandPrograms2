<?php
include("connect.php");
$index = "index";
$main_query1 = "SELECT heading,text FROM pages order by pageorder limit 1;";
$main_result1 = mysql_query($main_query1) or die (mysql_error());
$main_data1 = mysql_fetch_array($main_result1);
?>

<html>
<!-- Main Body -->
<table width="88%" border="0" class="table2" align="center">
  <tr> 
    <td width="140" height="100%" valign="top"> 
		<?php include ("menu.php") ?>
    </td>
    <td valign="top">
	<p class="text-heading"><?php echo $main_data1["heading"] ; ?></p>
	<span class="text"><?php echo $main_data1["text"] ; ?></span>
    </td>
  </tr>
</table>
<!-- Table Ends -->
</html>
