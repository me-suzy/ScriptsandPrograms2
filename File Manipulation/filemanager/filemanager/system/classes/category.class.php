<?php

class Category {
	
	/*
	   +----------------------------------------------------------------
	   | get next category level										
	   +----------------------------------------------------------------
	*/
	public static function getNextLevel($category_id, $level) {
		
		$level++;
		
		$sql = "SELECT * FROM `user_category` WHERE `category_subof` = '$category_id' ORDER BY `category_name`";
		$result = mysql_query($sql, Config::getDbLink());
		while($data = mysql_fetch_array($result)) {
			
			$bgcolor = (isset($_REQUEST['category_id']) && $_REQUEST['category_id'] != "" && $_REQUEST['category_id'] == $data['category_id']) ? "#E9EBF4" : "#FFFFFF";
			
			echo "
			<tr>
				<td bgcolor=\"$bgcolor\">
					<img src=\"system/resources/images/0.gif\" width=\"".($level * 5)."\" height=\"1\" border=\"0\" alt=\"\">
					<a href=\"javascript:reloadWindows('$data[category_id]');\" target=\"cat_files\">$data[category_name]</a> (".Utilities::countFiles($data['category_id']).") 
					<a href=\"javascript:openWindow('popup.php?action=category.edit&category_id=$data[category_id]','EditCategory','400','400','no');\"><img src=\"system/resources/images/symbol_edit.gif\" border=\"0\" alt=\"\"></a>
					";
					if(Category::getSubCategory($data['category_id']) == false) {
						echo " | <a href=\"javascript:confirm_delete('process.php?action=category.delete&category_id=$data[category_id]','$data[category_name]');\"><img src=\"system/resources/images/symbol_trash.gif\" border=\"0\" alt=\"\"></a>";
					}
					echo "
				</td>
			</tr>
			";
			
			Category::getNextLevel($data['category_id'],$level);
		}
	}
	
	/*
	   +----------------------------------------------------------------
	   | get next category level										
	   +----------------------------------------------------------------
	*/
	public static function getNextNavigationLevel($category_id, $level) {
		
		$level++;
		global $category_tree;
		
		$sql = "SELECT * FROM `user_category` WHERE `category_subof` = '$category_id' ORDER BY `category_name`";
		$result = mysql_query($sql, Config::getDbLink());
		while($data = mysql_fetch_array($result)) {
			
			$class = (isset($_REQUEST['category_id']) && $_REQUEST['category_id'] != "" && $_REQUEST['category_id'] == $data['category_id']) ? "navigationSelected" : "navigation";
			
			$category_tree = array();
			Category::getMainCategorys($data['category_id'],$category_tree);
			$category_tree = array_reverse($category_tree);
			$main_id = $category_tree[0];
			
			echo "
			<tr>
				<td>
					<a class=\"$class\" href=\"index.php?action=content.display&category_id=$data[category_id]\"><img src=\"system/resources/images/0.gif\" width=\"".($level * 5)."\" height=\"1\" border=\"0\" alt=\"\">&nbsp;&raquo;&nbsp;$data[category_name] (".Utilities::countFiles($data['category_id']).")</a>
				</td>
			</tr>
			";
			
			Category::getNextNavigationLevel($data['category_id'],$level);
		}
	}
	
	/*
	   +----------------------------------------------------------------
	   | check if category has a sub category							
	   +----------------------------------------------------------------
	*/
	public static function getSubCategory($category_id) {
		
		$sql = "SELECT * FROM `user_category` WHERE `category_subof` = '$category_id'";
		$result = mysql_query($sql, Config::getDbLink());
		if($data = mysql_fetch_array($result)) {
			
			return true;
		}
		else {
			
			return false;
		}
	}
	
	/*
	   +----------------------------------------------------------------
	   | returns all subcategorys of a category							
	   +----------------------------------------------------------------
	*/
	public static function getSubCategorys($category_id,$category_tree) {
		
		global $category_tree;
		
		$sql = "SELECT * FROM `user_category` WHERE `category_subof` = '$category_id'";
		$result = mysql_query($sql, Config::getDbLink());
		while($data = mysql_fetch_array($result)) {
			
			$category_tree[] = $data['category_id'];
			Category::getSubCategorys($data['category_id'],$category_tree);
		}
	}
	
	/*
	   +----------------------------------------------------------------
	   | returns all main categorys of a category						
	   +----------------------------------------------------------------
	*/
	public static function getMainCategorys($category_id,$category_tree) {
		
		global $category_tree;
		
		$sql = "SELECT * FROM `user_category` WHERE `category_id` = '$category_id'";
		$result = mysql_query($sql, Config::getDbLink());
		if($data = mysql_fetch_array($result)) {
			
			$category_tree[] = $data['category_id'];
			
			if($data['category_subof'] != "0") {
				Category::getMainCategorys($data['category_subof'],$category_tree);
			}
		}
	}
	
	/*
	   +----------------------------------------------------------------
	   | returns the name of the category								
	   +----------------------------------------------------------------
	*/
	public static function getCategoryName($category_id) {
		
		$sql = "SELECT `category_name` FROM `user_category` WHERE `category_id` = '$category_id'";
		$result = mysql_query($sql, Config::getDbLink());
		if($data = mysql_fetch_array($result)) {
			
			return $data['category_name'];
		}
	}
}

?>