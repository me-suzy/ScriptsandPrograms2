<?php
//
// Project: Help Desk support system (Admin panel)
// Description: 
// 1. Summary page - Add/edit/delete ticket categories, priorities, status
//

require_once "includes/auth.php";
require_once "includes/db.php";
require_once "includes/tpl.php";
require_once "includes/funcs.php";
require_once "includes/const.php";

$page_title = "Admin panel - Ticket options";

// User authentication
if($hduser['user_level'] != RANK_ADMIN)
{
	// If user NOT logged in, show message & redirect to index
	dialog("Your clearance is not high enough to access this section.", $page_title);
}

#
# Ticket categories: Add new category -------------------------------------------------------------------------------
#
if(isset($_POST['btn_addcat']))
{
	if(empty($_POST['cat_name']))
		dialog("Category Name field is required.", $page_title);
	
	// Get max order by value + 1
	$r_maxorder = mysql_query("SELECT MAX(cat_orderby)+1 AS max_orderby FROM $TABLE_CATS") or
						error("Cannot get max category orderby.");
	$db_maxorder = mysql_fetch_object($r_maxorder);
	
	mysql_query("INSERT INTO $TABLE_CATS (cat_name, cat_orderby)
				 VALUES ('$_POST[cat_name]', $db_maxorder->max_orderby);") or
		error("Cannot insert new cat into the db.");
}
#
# Ticket categories: Delete existing categories ---------------------------------------------------------------------
elseif(isset($_POST['btn_delcats']))
{
	if(is_array($_POST['chk_delcat']))
	{
		foreach($_POST['chk_delcat'] as $cat_id=>$col_val)
		{
			mysql_query("DELETE FROM $TABLE_CATS WHERE cat_id=$cat_id") or
				error("Cannot delete ticekt category.");
			mysql_query("UPDATE $TABLE_TICKETS SET ticket_cat=1 WHERE ticket_cat=$cat_id") or
				error("Cannot change cat id.");
		}
	}
}
#
#
# Ticket categories: Update existing categories ---------------------------------------------------------------------
#
elseif(isset($_POST['btn_updcats']))
{
	if(is_array($_POST['cat_id']))
	{
		// Check all fields row by row and build error report
		foreach($_POST['cat_id'] as $cat_id=>$col_val)
		{
			if(empty($_POST['cat_name'][$cat_id]) && $cat_id!=CAT_OTHER)
				$errors .= "Category name " . $_POST['ai'][$cat_id] . "<br>";
		}
		
		// If errors occured, show them
		if(!empty($errors))
			dialog("Error. The following required fields are not filled:<br> $errors", $page_title);
	
		// Everything went fine, update db row by row
		foreach($_POST['cat_id'] as $cat_id=>$col_val)
		{
			$cat_name		= $_POST['cat_name'][$cat_id];
			$cat_orderby	= intval($_POST['cat_orderby'][$cat_id]);
			
			// If category is "Other" (predefined), update only orderby
			if($cat_id==CAT_OTHER)
			{
				$sql_updcats = "UPDATE $TABLE_CATS SET
								cat_orderby=$cat_orderby
								WHERE cat_id=$cat_id";
			}
			else
			{
				$sql_updcats = "UPDATE $TABLE_CATS SET
								cat_name='$cat_name',
								cat_orderby=$cat_orderby
								WHERE cat_id=$cat_id";
			}
							 
			mysql_query($sql_updcats) or
				error("Cannot update categories (cat_id=$cat_id).");
		}
	}
}
#
# Ticket priorities: Add new priority -------------------------------------------------------------------------------
#
elseif(isset($_POST['btn_addpriority']))
{
	if(empty($_POST['priority_name']))
		dialog("Priority Name field is required.", $page_title);
		
	// Get max order by value + 1
	$r_maxorder = mysql_query("SELECT MAX(priority_orderby)+1 AS max_orderby FROM $TABLE_PRIORITIES") or
						error("Cannot get max priority orderby.");
	$db_maxorder = mysql_fetch_object($r_maxorder);
	
	mysql_query("INSERT INTO $TABLE_PRIORITIES (priority_name, priority_orderby)
				 VALUES ('$_POST[priority_name]', $db_maxorder->max_orderby);") or
		error("Cannot insert new priority into the db.");
}
#
# Ticket priorities: Delete existing priorities ---------------------------------------------------------------------
#
elseif(isset($_POST['btn_delpriorities']))
{
	if(is_array($_POST['chk_delpriority']))
	{
		foreach($_POST['chk_delpriority'] as $priority_id=>$col_val)
		{
			mysql_query("DELETE FROM $TABLE_PRIORITIES WHERE priority_id=$priority_id") or
				error("Cannot delete ticket priority.");
			mysql_query("UPDATE $TABLE_TICKETS SET ticket_priority=3 WHERE ticket_priority=$priority_id") or
				error("Cannot change ticket priority.");
		}
	}
}
#
# Ticket priorities: Update existing priorities ---------------------------------------------------------------------
#
elseif(isset($_POST['btn_updpriorities']))
{
	if(is_array($_POST['priority_id']))
	{
		// Check all fields row by row and build error report
		foreach($_POST['priority_id'] as $priority_id=>$col_val)
		{
			if(empty($_POST['priority_name'][$priority_id]) && $priority_id > PRIORITY_LOW)
				$errors .= "Priority name " . $_POST['ai'][$priority_id] . "<br>";
		}
		
		// If errors occured, show them
		if(!empty($errors))
			dialog("Error. The following required fields are not filled:<br> $errors", $page_title);
			
		// Everything went fine, update db row by row
		foreach($_POST['priority_id'] as $priority_id=>$col_val)
		{
			$priority_name		= $_POST['priority_name'][$priority_id];
			$priority_orderby	= intval($_POST['priority_orderby'][$priority_id]);
			
			// If category is one of the predefined, update only orderby
			if($priority_id > PRIORITY_LOW)
			{
				$sql_updprs = "UPDATE $TABLE_PRIORITIES SET
							   priority_name	 = '$priority_name',
							   priority_orderby = $priority_orderby
							   WHERE priority_id	 = $priority_id";
			}
			else
			{
				$sql_updprs = "UPDATE $TABLE_PRIORITIES SET
							   priority_orderby=$priority_orderby
							   WHERE priority_id=$priority_id";
			}
							 
			mysql_query($sql_updprs) or
				error("Cannot update priorities (priority_id=$priority_id).");
		}
	}
}
#
# Ticket status: Add new status ------------------------------------------------------------------------------------
#
elseif(isset($_POST['btn_addstatus']))
{
	if(empty($_POST['status_name']))
		dialog("Status Name field is required.", $page_title);
	
	// Get max order by value + 1
	$r_maxorder = mysql_query("SELECT MAX(status_orderby)+1 AS max_orderby FROM $TABLE_STATUS") or
						error("Cannot get max status orderby.");
	$db_maxorder = mysql_fetch_object($r_maxorder);
	
	mysql_query("INSERT INTO $TABLE_STATUS (status_name, status_orderby)
				 VALUES ('$_POST[status_name]', $db_maxorder->max_orderby);") or
		error("Cannot insert new status into the db.");
}
#
# Ticket status: Delete existing status ----------------------------------------------------------------------------
#
elseif(isset($_POST['btn_delstatus']))
{
	if(is_array($_POST['chk_delstatus']))
	{
		foreach($_POST['chk_delstatus'] as $status_id=>$col_val)
		{
			mysql_query("DELETE FROM $TABLE_STATUS WHERE status_id=$status_id") or
				error("Cannot delete ticket status.");
			mysql_query("UPDATE $TABLE_TICKETS SET ticket_status=1 WHERE ticket_status=$status_id") or
				error("Cannot change ticket status.");
		}
	}
}
#
# Ticket status: Update existing status
#
elseif(isset($_POST['btn_updstatus']))
{
	if(is_array($_POST['status_id']))
	{
		// Check all fields row by row and build error report
		foreach($_POST['status_id'] as $status_id=>$col_val)
		{
			if(empty($_POST['status_name'][$status_id]) && $status_id > STATUS_RESOLVED)
				$errors .= "Status name " . $_POST['ai'][$status_id] . "<br>";
		}
		
		// If errors occured, show them
		if(!empty($errors))
			dialog("Error. The following required fields are not filled:<br> $errors", $page_title);
			
		// Everything went fine, update db row by row
		foreach($_POST['status_id'] as $status_id=>$col_val)
		{
			$status_name	= $_POST['status_name'][$status_id];
			$status_orderby	= intval($_POST['status_orderby'][$status_id]);
			
			// If category is one of the predefined, update only orderby
			if($status_id > STATUS_RESOLVED)
			{
				$sql_updstatus = "UPDATE $TABLE_STATUS SET
								  status_name	 	= '$status_name',
								  status_orderby 	= $status_orderby
								  WHERE status_id	= $status_id";
			}
			else
			{
				$sql_updstatus = "UPDATE $TABLE_STATUS SET
								  status_orderby	= $status_orderby
								  WHERE status_id	= $status_id";
			}
							 
			mysql_query($sql_updstatus) or
				error("Cannot update status (status_id=$status_id).");
		}
	}
}
#
# Ticket status: Delete existing status ----------------------------------------------------------------------------
#
#
# Display ticket options page --------------------------------------------------------------------------------------
#

$tpl_options = new tpl("tpl/admin_ticket_options.tpl");

// Get HTML code fragments
$html_row_cat			= fragment_get("row_cat", $tpl_options->template);
$html_row_predefcat 	= fragment_get("row_cat_predef", $tpl_options->template);
$html_row_prs			= fragment_get("row_pr", $tpl_options->template);
$html_row_predefprs 	= fragment_get("row_pr_predef", $tpl_options->template);
$html_row_status		= fragment_get("row_status", $tpl_options->template);
$html_row_predefstatus	= fragment_get("row_status_predef", $tpl_options->template);
fragment_delete("row_cat_predef", $tpl_options->template);
fragment_delete("row_pr_predef", $tpl_options->template);
fragment_delete("row_status_predef", $tpl_options->template);

//
// Load ticket categories
//
$r_cats = mysql_query("SELECT * FROM $TABLE_CATS ORDER BY cat_orderby") or
				error("Cannot load ticket categories.");

$ai = 0; // Auto incrementing counter
while($db_cats = mysql_fetch_object($r_cats))
{
	$ai++;
	
	$cat_row_tags = array( "ai" 			=> $ai,
						   "cat_id"			=> $db_cats->cat_id,
						   "cat_name"		=> $db_cats->cat_name,
						   "cat_orderby"	=> $db_cats->cat_orderby );
						   
	// If cat is predefined, use 2nd html template row
	if($db_cats->cat_id == CAT_OTHER)
		$html_cat_list .= replace_tags($cat_row_tags, $html_row_predefcat);
	else
		$html_cat_list .= replace_tags($cat_row_tags, $html_row_cat);
}

//
// Load ticket priorities
//
$r_prs = mysql_query("SELECT * FROM $TABLE_PRIORITIES ORDER BY priority_orderby") or
					error("Cannot load ticket priorities.");

$ai=0;
while($db_prs = mysql_fetch_object($r_prs))
{
	$ai++;

	$prs_row_tags = array( "ai" 				=> $ai,
						   "priority_id"		=> $db_prs->priority_id,
						   "priority_name"		=> $db_prs->priority_name,
						   "priority_orderby"	=> $db_prs->priority_orderby );
	
	// If priority is not one of the predefined, use normal html row
	if($db_prs->priority_id > PRIORITY_LOW)
		$html_prs_list .= replace_tags($prs_row_tags, $html_row_prs);
	else
		$html_prs_list .= replace_tags($prs_row_tags, $html_row_predefprs);
}

#
# Load ticket status
#
$r_status = mysql_query("SELECT * FROM $TABLE_STATUS ORDER BY status_orderby") or
					error("Cannot load ticket status.");
					
$ai=0;
while($db_status = mysql_fetch_object($r_status))
{
	$ai++;

	$status_row_tags = array( "ai" 				=> $ai,
						   	  "status_id"		=> $db_status->status_id,
							  "status_name"		=> $db_status->status_name,
							  "status_orderby"	=> $db_status->status_orderby );
	
	// If status is not one of the predefined, use normal html row
	if($db_status->status_id > STATUS_RESOLVED)
		$html_status_list .= replace_tags($status_row_tags, $html_row_status);
	else
		$html_status_list .= replace_tags($status_row_tags, $html_row_predefstatus);
}

// Replace code fragments
$tpl_options->template = fragment_replace("row_cat", $html_cat_list, $tpl_options->template);
$tpl_options->template = fragment_replace("row_pr", $html_prs_list, $tpl_options->template);
$tpl_options->template = fragment_replace("row_status", $html_status_list, $tpl_options->template);

echo build_page(content_box($tpl_options->template, $page_title, true), $page_title);
?>