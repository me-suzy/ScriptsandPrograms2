<?php
////////////////////////////////////////////
// Begin Editable Parameters
////////////////////////////////////////////
// MySQL connection variables
// Login user
$database_login="";
// Login password
$database_pass="";
// Server name that MySQL is on
$database_host="localhost";
// Name of the MySQL database
$database_name="";
////////////////////////////////////////////
// Website administration variables
// Admin email address to send notifications to
$admin_mail="";
// Email address to send submission verifications from
$noreply_mail="";
// Password to log into the administration tool
$admin_password="";
// Does each submission require admin approval?
// Set to "no" if admin approval is required
$admin_approved="no";
////////////////////////////////////////////
// Website layout and display variables
// Web site name
$site_title="dB Masters Links Directory 3.1.3";
// Number of columns displayed on front page
$front_cols="2";
// Width per column (pixel or percentage) on front page
// (if using pixel number, add "px" right after the number with no leading space)
$front_perc="50%";
// The last word in the name of the .css file you wish to use for your color scheme.
// Right after "style_" of the style sheet name
$style_name="dark";
// Do you wish to allow users to post images with their listing?
$image_enabled="yes";
////////////////////////////////////////////
// Result listing and ordering options
// field to list by (options are "name","id","clicks","rates","rating" or "date_added")
$list_by="name";
// Direction to list, ascending or decending (ASC or DESC)
$list_order="ASC";
// Number of records showed before paging reuslts over several pages
$category="10";
// Number of links to display on popular page
$popular="10";
// Number of links to display on new links page
$new="10";
// Number of links to display on search results page
$search="10";
////////////////////////////////////////////
// Error Messages to admin email and browser when MySQL connection and query
// errors are reported
$ConnError_Email="There was an error connecting with MySQL at $PHP_SELF";
$ConnError_Browser="There was an error connecting with MySQL";
$QueryError_Email="There was an error querying MySQL at $PHP_SELF";
$QueryError_Browser="There was an error querying MySQL";
// End Editable Parameters
////////////////////////////////////////////
// MySQL Connection and Query Functions
// Don't Edit Beyond This Point
////////////////////////////////////////////
	Function MySQLConnect($errmsg, $msg="")
	{
		$success=mysql_connect($GLOBALS["database_host"], $GLOBALS["database_login"], $GLOBALS["database_pass"]);
		if(!$success)
		{
			if($Email_Errors=="1")
			{
				mail($GLOBALS["Admin_Mail"], "MySQL Connect Failure at $Site_Name", $errmsg."\n\n".mysql_errno(). ":  ".mysql_error(), "From: $Alert_Email");
				die();
			}
		}
	}
	Function MySQLQuery($query, $errmsg, $msg="")
	{
		$success=mysql_db_query($GLOBALS["database_name"], $query);
		if(!$success)
		{
			if($Email_Errors=="1")
			{
				mail($GLOBALS["Admin_Mail"], "MySQL Query Failure at $Site_Name", $errmsg."\n\n".mysql_errno(). ":  ".mysql_error(), "From: $Alert_Email");
				echo $msg;
				die();
			}
		}
		return $success;
	}
$date_format="Y-m-d";
$display_format="M d, Y";
?>