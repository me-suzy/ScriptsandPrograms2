<!-- Menu Begins -->
     <table width="100%" border="0">
<?php
include("connect.php");
$index ="index";
//$menu_query1 = "SELECT * FROM links WHERE parent = '$index'";
$menu_query1 = "SELECT * FROM pages order by pageorder,name";
$menu_result1 = mysql_query($menu_query1) or die (mysql_error());

while($menu_data1 = mysql_fetch_array($menu_result1))
{
?>
        <tr>
          <td class="menu">
		  <a href="inside.php?id=<?php echo $menu_data1["serial"]; ?>" title="<?php echo $menu_data1["heading"]; ?>">
		   	  <?php echo $menu_data1["name"]; ?>
		  </a>
		  </td>
<?php
}
?>
        </tr>
      </table>
<!-- Menu Ends -->