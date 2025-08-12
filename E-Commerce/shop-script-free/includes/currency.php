<?php
/*****************************************************************************
 *                                                                           *
 * Shop-Script FREE                                                          *
 * Copyright (c) 2005 WebAsyst LLC. All rights reserved.                     *
 *                                                                           *
 ****************************************************************************/

	// currency selection form

	if (isset($_GET["currency"])) //show currency type selection form
	{
		$smarty->assign("main_content_template", "notavailable.tpl.html");
	}

?>