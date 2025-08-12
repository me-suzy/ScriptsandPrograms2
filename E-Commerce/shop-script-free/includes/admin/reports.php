<?php
/*****************************************************************************
 *                                                                           *
 * Shop-Script FREE                                                          *
 * Copyright (c) 2005 WebAsyst LLC. All rights reserved.                     *
 *                                                                           *
 ****************************************************************************/

	//ADMIN :: new orders managment

	//define a new admin department
	$admin_dpt = array(
		"id" => "reports", //department ID
		"sort_order" => 50, //sort order (less `sort_order`s appear first)
		"name" => ADMIN_REPORTS, //department name
		"sub_departments" => array
		 (
			array("id"=>"bestsellers", "name"=>ADMIN_SALABLE_PRODUCTS),
			array("id"=>"rating", "name"=>ADMIN_POPULAR_PRODUCTS),
			array("id"=>"outofstock", "name"=>ADMIN_OUT_OF_STOCK)
		 )
	);
	add_department($admin_dpt);

	//show department if it is being selected
	if ($dpt == "reports")
	{
		//assign admin main department template
		$smarty->assign("admin_main_content_template", $admin_dpt["id"].".tpl.html");
		//assign subdepts
		$smarty->assign("admin_sub_departments", $admin_dpt["sub_departments"]);
		//include selected sub-department
		$smarty->assign("admin_sub_dpt", "notavailable.tpl.html");
	}

?>