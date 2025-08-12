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
		"id" => "catalog", //department ID
		"sort_order" => 10, //sort order (less `sort_order`s appear first)
		"name" => ADMIN_CATALOG, //department name
		"sub_departments" => array
		(
			array("id"=>"products_categories", "name"=>"<b>".ADMIN_CATEGORIES_PRODUCTS."</b>"),
			array("id"=>"excel_import", "name"=>ADMIN_IMPORT_FROM_EXCEL),
			array("id"=>"dbsync", "name"=>ADMIN_SYNCHRONIZE_TOOLS),
			array("id"=>"extra", "name"=>ADMIN_PRODUCT_OPTIONS),
			array("id"=>"special", "name"=>"<b>".ADMIN_SPECIAL_OFFERS."</b>")
		)
	);
	add_department($admin_dpt);


	//show new orders page if selected
	if ($dpt == "catalog")
	{
		//set default sub department if required
		if (!isset($sub)) $sub = "products_categories";

		//assign admin main department template
		$smarty->assign("admin_main_content_template", $admin_dpt["id"].".tpl.html");
		//assign subdepts
		$smarty->assign("admin_sub_departments", $admin_dpt["sub_departments"]);
		//include selected sub-department
		if (!strcmp($sub, "excel_import") ||
			!strcmp($sub, "1c_import") ||
			!strcmp($sub, "dbsync") ||
			!strcmp($sub, "shipping") ||
			!strcmp($sub, "extra"))
		{
			//set sub-department template
			$smarty->assign("admin_sub_dpt", "notavailable.tpl.html");
		}
		else
		{
			if (file_exists("./includes/admin/sub/".$admin_dpt["id"]."_$sub.php")) //sub-department file exists
				include("./includes/admin/sub/".$admin_dpt["id"]."_$sub.php");
			else //no sub department found
				$smarty->assign("admin_main_content_template", "notfound.tpl.html");
		}

	}

?>