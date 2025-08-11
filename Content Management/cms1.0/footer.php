<?php
include("connect.php");
$footer_query1 = "SELECT text from footer ";
$footer_result1=mysql_query($footer_query1) or die (mysql_error());
$footer_data1 = mysql_fetch_array($footer_result1);
?>
<!--Footer -->
<table width="88%" border="0"  align = "center">
  <tr class="table2"> 
    <td valign="top" class="footer"> 
		<?php echo $footer_data1["text"]; ?>
	</td>
  </tr>
</table>
<p class="text" style="text-align:center;">powered by <a href="http://content-management.us" target="_blank">content management</a></p>
</body>
</html>
