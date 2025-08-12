<?php
/*****************************************************************************
 *                                                                           *
 * Shop-Script FREE                                                          *
 * Copyright (c) 2005 WebAsyst LLC. All rights reserved.                     *
 *                                                                           *
 ****************************************************************************/

	// show whole price list

	if (isset($_GET["show_price"])) //show pricelist
	{
		$smarty->assign("main_content_template", "notavailable.tpl.html");
	}

?>