<?php

  	/*=====================================================================
	// $Id: expand_tree.php,v 1.4 2005/07/01 17:55:08 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/
die (__FILE__);
	if ($show_nodes <> "") {
		$expand_str = $show_nodes;
	}
	else {
		$user_res = mysql_query ("SELECT current_tree FROM ".TABLE_PREFIX."user_details WHERE user_id='$user_id'");
		logDBError (__FILE__, __LINE__, mysql_error());
		$user_row = mysql_fetch_array ($user_res);
		$expand_str = "|0|"; // default
		if (isset ($user_row['current_tree']) && $user_row['current_tree'] <> "") {
	   		$expand_str = $user_row['current_tree'];
		}
	}

	$open_node = explode ("|", $expand_str);
	for ($i=1; $i < count($open_node)-1; $i++)
		echo "expand_me(tree, ".$open_node[$i].");";

	echo "//*".$show_nodes."*".error_reporting()."*";
?>
