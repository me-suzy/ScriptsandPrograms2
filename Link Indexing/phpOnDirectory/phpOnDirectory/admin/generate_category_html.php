<?php
# choose a banner

include_once('../includes/db_connect.php');
include("$CONST_INCLUDE_ROOT/Templates/maintemplate.header.without.menu.inc.php");
check_admin();
?>
	<?php
		# directory menu

		$categories=mysql_query("SELECT DISTINCT(cat_parent) FROM dir_categories",$link);
		while ($directory_menu_top=mysql_fetch_object($categories)) {
			print("<div  class='sideNavHead'><img src='$CONST_LINK_ROOT/images/arrow.gif' alt=''>$directory_menu_top->cat_parent</div>");
			$sub_categories=mysql_query("SELECT cat_child, cat_id FROM dir_categories WHERE cat_parent = '$directory_menu_top->cat_parent'",$link);

			while ($directory_menu=mysql_fetch_object($sub_categories)) {
				$directory_menu->cat_child=str_replace(" ","_",$directory_menu->cat_child);
				$directory_menu->cat_child=str_replace("/","_",$directory_menu->cat_child);
				$sub_category.="<a href='$CONST_LINK_ROOT/dating_sites/$directory_menu->cat_child.html'>$directory_menu->cat_child</a><br>";
			}
			print("<div class='sideNavBody'>$sub_category</div>");
			$sub_category="";
		}
	?>

<?include("$CONST_INCLUDE_ROOT/Templates/maintemplate.footer.inc.php");?>
