<?php
/*
Copyright 2005 VUBB
*/

// Get the members file
get_template('members');

// get the start of the table from the template
table_start();

// if no view is set or if group view is set show groups
if (!isset($_GET['view']) || $_GET['view'] == 'groups')
{
	// Get the groups using pagination
	pagination_start("groups", "WHERE `id` != '1'", "ORDER BY `id` ASC", "10", "index.php?act=members&view=groups");
	while ($display_groups = mysql_fetch_array($pagination_query))
	{
		// strip slashes for correct viewing
		$display_groups['name'] = stripslashes($display_groups['name']);
		
		// get the members row from template
		group_row();
	}
}

// show members
else if (isset($_GET['view']) && $_GET['view'] == 'members')
{
	// Get the members using pagination
	pagination_start("members", "WHERE `id` != '-1' AND `group` = '" . $_GET['g'] . "'", "ORDER BY `user` ASC", "10", "index.php?act=members&view=members");
	while ($display_members = mysql_fetch_array($pagination_query))
	{
		// strip slashes for correct viewing
		$display_members['user'] = stripslashes($display_members['user']);
		
		// get the members row from template
		member_row();
	}
}

// Get the table_end template
table_end();
?>