<?php
/*****************************************************************************
 *                                                                           *
 * Shop-Script FREE                                                          *
 * Copyright (c) 2005 WebAsyst LLC. All rights reserved.                     *
 *                                                                           *
 ****************************************************************************/

	//ADMIN :: products and categories view

	//define admin department
	$admin_dpt = array(
		"id" => "modules", //department ID
		"sort_order" => 40, //sort order (less `sort_order`s appear first)
		"name" => ADMIN_MODULES, //department name
		"sub_departments" => array
		(
			array("id"=>"news", "name"=>ADMIN_NEWS),
			array("id"=>"survey", "name"=>ADMIN_VOTING),
			array("id"=>"cc", "name"=>ADMIN_CC_PROCESSING)
		)
	);
	add_department($admin_dpt);


	//show new orders page if selected
	if ($dpt == "modules")
	{
		//assign admin main department template
		$smarty->assign("admin_main_content_template", $admin_dpt["id"].".tpl.html");
		//assign subdepts
		$smarty->assign("admin_sub_departments", $admin_dpt["sub_departments"]);
		//include selected sub-department
		$smarty->assign("admin_sub_dpt", "notavailable.tpl.html");
	}

?>