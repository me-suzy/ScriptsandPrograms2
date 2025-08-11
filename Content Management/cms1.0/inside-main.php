<?php
include("connect.php");
$id = $_REQUEST["id"];
$in_main_query1 = "SELECT * FROM pages WHERE serial ='$id' ";
$in_main_result1 = mysql_query($in_main_query1) or die (mysql_error());
$in_main_data1 = mysql_fetch_array($in_main_result1);
$name = $in_main_data1["name"];
?>

<html>
<!-- Main Body -->
<table width="88%" border="0" class="table2" align = "center">
  <tr> 
    <td width="140" height="100%" valign="top"> 
		<?php include ("menu.php") ?>
    </td>
    <td>
	<p class="text-heading"><?php echo $in_main_data1["heading"] ; ?></p>
	<div class="text"><?php echo $in_main_data1["text"] ; ?></div>
    </td>
  </tr>
</table>
<!-- Table Ends -->
</html>
