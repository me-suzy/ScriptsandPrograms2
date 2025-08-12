<?php

function GetCategoryPath ( $cat_id, &$catnav ) {
		
	global $prefix;
		
	if($cat_id!=0) {
	
		$tbl = mysql_query ("SELECT cat_id, cat_father_id, category FROM ".$prefix."store_category where cat_id='" . $cat_id ."'");
		while ( $res = mysql_fetch_array ( $tbl ) ) {
						
			$catnav[] = array ('cat_id'=>$res['cat_id'], 'category'=>$res['category'], 'cat_father_id'=>$res['cat_father_id'] );
			GetCategoryPath ($res['cat_father_id'], $catnav );
			
		}
	}
}

function GetCatName ( $cat_id ) {
		
	global $prefix;
		
	if($cat_id!=0) {
	
		$tbl = mysql_query ("SELECT category FROM ".$prefix."store_category where cat_id='" . $cat_id ."' LIMIT 1");
		while ( $res = mysql_fetch_array ( $tbl ) ) {
						
			return $res['category'];
						
		}
	}
}
	
?>