<?php
/*****************************************************************************
 *                                                                           *
 * Shop-Script FREE                                                          *
 * Copyright (c) 2005 WebAsyst LLC. All rights reserved.                     *
 *                                                                           *
 ****************************************************************************/

	//ADMIN :: configuration

	//define admin department
	$admin_dpt = array(
		"id" => "conf", //department ID
		"sort_order" => 30, //sort order (less `sort_order`s appear first)
		"name" => ADMIN_SETTINGS, //department name
		"sub_departments" => array
		 (
			array("id"=>"general", "name"=>"<b>".ADMIN_SETTINGS_GENERAL."</b>"),
			array("id"=>"appearence", "name"=>"<b>".ADMIN_SETTINGS_APPEARENCE."</b>"),
			array("id"=>"login_pass", "name"=>"<b>".ADMIN_LOGIN_PASSWORD."</b>"),
			array("id"=>"currencies", "name"=>ADMIN_CURRENCY_TYPES),
			array("id"=>"shipping", "name"=>STRING_SHIPPING_TYPE),
			array("id"=>"payment", "name"=>STRING_PAYMENT_TYPE),
			array("id"=>"aux", "name"=>"<b>".ADMIN_AUX_INFO."</b>")
		 )
	);
	add_department($admin_dpt);


	//show department if it is being selected
	if ($dpt == "conf")
	{
		//set default sub department if required
		if (!isset($sub)) $sub = "general";

		//assign admin main department template
		$smarty->assign("admin_main_content_template", $admin_dpt["id"].".tpl.html");
		//assign subdepts
		$smarty->assign("admin_sub_departments", $admin_dpt["sub_departments"]);
		//include selected sub-department

		if (!strcmp($sub, "payment") ||
			!strcmp($sub, "currencies") ||
			!strcmp($sub, "shipping"))
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