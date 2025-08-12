<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################## //ADMIN PANEL LOGS\\ ################### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// define a few variables
$fileAction = "Logs";
$permissions = "logs_stats";

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");


// ##### DO ADMINISTRATOR LOG ##### \\
if($_GET['do'] == "admin") {
	// make sure user can view admin log...
	if(!canViewAdminLog($_COOKIE['wtcBB_adminUserid'])) {
		construct_error("Sorry, you cannot view this administrative log. If you feel you should be able to, then change the <strong>\$can_view_adminlog</strong> variable located inside the <strong>config.php</strong> file inside the <strong>includes</strong> directory.");
		exit;
	}

	// make sure form is submitted...
	if($_REQUEST['admin_log']['set_form']) {
		// get current page
		$currentPage = $_REQUEST['admin_log']['page_num'];

		$end = $_REQUEST['admin_log']['entries_per_page'];

		// ASC or DESC?
		if($_REQUEST['admin_log']['orderby'] == "username") {
			$ascDesc = "ASC";
		} else {
			$ascDesc = "DESC";
		}

		// time to form select query...
		if($_REQUEST['admin_log']['madeby'] == "all") {
			if($_REQUEST['admin_log']['script'] == "all") {
				$adminlog_select = "SELECT * FROM log_admin ORDER BY ".$_REQUEST['admin_log']['orderby']." ".$ascDesc." LIMIT ".$_REQUEST['start'].", ".$end;
				$_REQUEST['admin_log']['total_entries'] = "SELECT * FROM log_admin";
			} else {
				$adminlog_select = "SELECT * FROM log_admin WHERE file_action = '".$_REQUEST['admin_log']['script']."' ORDER BY ".$_REQUEST['admin_log']['orderby']." ".$ascDesc." LIMIT ".$_REQUEST['start'].", ".$end;
				$_REQUEST['admin_log']['total_entries'] = "SELECT * FROM log_admin WHERE file_action = '".$_REQUEST['admin_log']['script']."'";
			}
		} else {
			if($_REQUEST['admin_log']['script'] == "all") {
				$adminlog_select = "SELECT * FROM log_admin WHERE username = '".$_REQUEST['admin_log']['madeby']."' ORDER BY ".$_REQUEST['admin_log']['orderby']." ".$ascDesc." LIMIT ".$_REQUEST['start'].", ".$end;
				$_REQUEST['admin_log']['total_entries'] = "SELECT * FROM log_admin WHERE username = '".$_REQUEST['admin_log']['madeby']."'";
			} else {
				$adminlog_select = "SELECT * FROM log_admin WHERE file_action = '".$_REQUEST['admin_log']['script']."' AND username = '".$_REQUEST['admin_log']['madeby']."' ORDER BY ".$_REQUEST['admin_log']['orderby']." ".$ascDesc." LIMIT ".$_REQUEST['start'].", ".$end;
				$_REQUEST['admin_log']['total_entries'] = "SELECT * FROM log_admin WHERE file_action = '".$_REQUEST['admin_log']['script']."' AND username = '".$_REQUEST['admin_log']['madeby']."'";
			}
		}

		// run query
		$adminlog_query = query($adminlog_select);

		//print($adminlog_select."<br /><br />Start: ".$_REQUEST['start']."   End: ".$end."<br />".$_REQUEST['admin_log']['orderby']);

		$_REQUEST['admin_log']['total_entries'] = mysql_num_rows(query($_REQUEST['admin_log']['total_entries']));

		// nothin found...
		if($_REQUEST['admin_log']['total_entries'] == 0) {
			construct_error("Sorry, no entry logs were found matching the criteria given.");
			exit;
		}

		// find total pages...
		if($_REQUEST['admin_log']['total_entries'] <= $_REQUEST['admin_log']['entries_per_page']) {
			$_REQUEST['admin_log']['total_pages'] = 1;
		} else {
			// divide to find pages
			$_REQUEST['admin_log']['total_pages'] = $_REQUEST['admin_log']['total_entries'] / $_REQUEST['admin_log']['entries_per_page'];

			// make INT
			settype($_REQUEST['admin_log']['total_pages'],int);
			
			$modulus = $_REQUEST['admin_log']['total_entries'] % $_REQUEST['admin_log']['entries_per_page'];

			// if modulus is greater than zero.. add a page...
			if($modulus > 0) {
				$_REQUEST['admin_log']['total_pages']++;
			}
		}

		// do header
		admin_header("wtcBB Admin Panel - Logs - Administrator Log");

		construct_title("Administrator Log");

		construct_table("options","userinfo_form","userinfo_submit",1);
		construct_header("Page: ".$_REQUEST['admin_log']['page_num']." of ".$_REQUEST['admin_log']['total_pages']." (Total Log Entries: ".$_REQUEST['admin_log']['total_entries'].")",6);

		print("\n\n\t<tr>\n");

		print("\t\t<td class=\"cat\">\n");
		print("\t\t\tLog ID\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tUsername\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tDate\n");
		print("\t\t</td>\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tFile Path\n");
		print("\t\t</td>\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tFile Action\n");
		print("\t\t</td>\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tIP Address\n");
		print("\t\t</td>\n");

		print("\t</tr>\n\n");

		while($adminlog = mysql_fetch_array($adminlog_query)) {
			print("\t<tr>\n");

				print("\t\t<td class=\"desc1\" style=\"text-align: center; white-space: nowrap; width: 15%; padding: 5px;\">\n");
					print("\t\t\t".$adminlog['logid']."\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					// get user ID so we can link to profile
					$userinfo = mysql_fetch_array(query("SELECT userid FROM user_info WHERE username = '".$adminlog['username']."' LIMIT 1"));

					print("\t\t\t<a href=\"user.php?do=edit&id=".$userinfo['userid']."\">".$adminlog['username']."</a>\n");
				print("\t\t</td>\n");

				// get join date...
				$adminlog['action_date'] = date("m-d-y \a\\t g:i A",$adminlog['action_date']);
				print("\t\t<td class=\"desc1\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t".$adminlog['action_date']."\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t".$adminlog['filepath']."\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc1\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t".$adminlog['file_action']."\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t".$adminlog['ip_address']."\n");
				print("\t\t</td>\n");

			print("\t</tr>\n\n");
		}

		$_REQUEST['admin_log']['page_num']++;

		print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"6\">\n");
			// only one page
			if($_REQUEST['admin_log']['total_pages'] == 1) {
				print("&nbsp;");
			}

			// first page
			else if($currentPage == 1) {
				print("<button type=\"button\" onClick=\"location.href='log.php?do=admin&admin_log%5Bset_form%5D=1&admin_log%5Bpage_num%5D=".$_REQUEST['admin_log']['page_num']."&admin_log%5Btotal_pages%5D=".$_REQUEST['admin_log']['total_pages']."&admin_log%5Borderby%5D=".$_REQUEST['admin_log']['orderby']."&admin_log%5Bmadeby%5D=".$_REQUEST['admin_log']['madeby']."&admin_log%5Bentries_per_page%5D=".$_REQUEST['admin_log']['entries_per_page']."&start=".($_REQUEST['start']+$_REQUEST['admin_log']['entries_per_page'])."&admin_log%5Bscript%5D=".$_REQUEST['admin_log']['script']."';\" ".$submitbg.">Next Page &#62</button> &nbsp;&nbsp;&nbsp; <button type=\"button\" onClick=\"location.href='log.php?do=admin&admin_log%5Bset_form%5D=1&admin_log%5Bpage_num%5D=".$_REQUEST['admin_log']['total_pages']."&admin_log%5Btotal_pages%5D=".$_REQUEST['admin_log']['total_pages']."&admin_log%5Borderby%5D=".$_REQUEST['admin_log']['orderby']."&admin_log%5Bmadeby%5D=".$_REQUEST['admin_log']['madeby']."&admin_log%5Bentries_per_page%5D=".$_REQUEST['admin_log']['entries_per_page']."&start=".(($_REQUEST['admin_log']['entries_per_page'])*($_REQUEST['admin_log']['total_pages']-1))."&admin_log%5Bscript%5D=".$_REQUEST['admin_log']['script']."';\" ".$submitbg.">Last Page &#62&#62</button>\n");
			}

			// last page
			else if($currentPage == $_REQUEST['admin_log']['total_pages']) {
				print("<button type=\"button\" onClick=\"location.href='log.php?do=admin&admin_log%5Bset_form%5D=1&admin_log%5Bpage_num%5D=1&admin_log%5Btotal_pages%5D=".$_REQUEST['admin_log']['total_pages']."&admin_log%5Borderby%5D=".$_REQUEST['admin_log']['orderby']."&admin_log%5Bmadeby%5D=".$_REQUEST['admin_log']['madeby']."&admin_log%5Bentries_per_page%5D=".$_REQUEST['admin_log']['entries_per_page']."&start=0&admin_log%5Bscript%5D=".$_REQUEST['admin_log']['script']."';\" ".$submitbg.">&#60&#60 First Page</button> &nbsp;&nbsp;&nbsp; <button type=\"button\" onClick=\"location.href='log.php?do=admin&admin_log%5Bset_form%5D=1&admin_log%5Bpage_num%5D=".($_REQUEST['admin_log']['page_num']-2)."&admin_log%5Btotal_pages%5D=".$_REQUEST['admin_log']['total_pages']."&admin_log%5Borderby%5D=".$_REQUEST['admin_log']['orderby']."&admin_log%5Bmadeby%5D=".$_REQUEST['admin_log']['madeby']."&admin_log%5Bentries_per_page%5D=".$_REQUEST['admin_log']['entries_per_page']."&start=".($_REQUEST['start']-$_REQUEST['admin_log']['entries_per_page'])."&admin_log%5Bscript%5D=".$_REQUEST['admin_log']['script']."';\" ".$submitbg.">&#60 Previous Page</button>\n");
			}

			// page inbetween
			else {
				print("<button type=\"button\" onClick=\"location.href='log.php?do=admin&admin_log%5Bset_form%5D=1&admin_log%5Bpage_num%5D=1&admin_log%5Btotal_pages%5D=".$_REQUEST['admin_log']['total_pages']."&admin_log%5Borderby%5D=".$_REQUEST['admin_log']['orderby']."&admin_log%5Bmadeby%5D=".$_REQUEST['admin_log']['madeby']."&admin_log%5Bentries_per_page%5D=".$_REQUEST['admin_log']['entries_per_page']."&start=0&admin_log%5Bscript%5D=".$_REQUEST['admin_log']['script']."';\" ".$submitbg.">&#60&#60 First Page</button> &nbsp;&nbsp;&nbsp; <button type=\"button\" onClick=\"location.href='log.php?do=admin&admin_log%5Bset_form%5D=1&admin_log%5Bpage_num%5D=".($_REQUEST['admin_log']['page_num']-2)."&admin_log%5Btotal_pages%5D=".$_REQUEST['admin_log']['total_pages']."&admin_log%5Borderby%5D=".$_REQUEST['admin_log']['orderby']."&admin_log%5Bmadeby%5D=".$_REQUEST['admin_log']['madeby']."&admin_log%5Bentries_per_page%5D=".$_REQUEST['admin_log']['entries_per_page']."&start=".($_REQUEST['start']-$_REQUEST['admin_log']['entries_per_page'])."&admin_log%5Bscript%5D=".$_REQUEST['admin_log']['script']."';\" ".$submitbg.">&#60 Previous Page</button> &nbsp;&nbsp;&nbsp; <button type=\"button\" onClick=\"location.href='log.php?do=admin&admin_log%5Bset_form%5D=1&admin_log%5Bpage_num%5D=".$_REQUEST['admin_log']['page_num']."&admin_log%5Btotal_pages%5D=".$_REQUEST['admin_log']['total_pages']."&admin_log%5Borderby%5D=".$_REQUEST['admin_log']['orderby']."&admin_log%5Bmadeby%5D=".$_REQUEST['admin_log']['madeby']."&admin_log%5Bentries_per_page%5D=".$_REQUEST['admin_log']['entries_per_page']."&start=".($_REQUEST['start']+$_REQUEST['admin_log']['entries_per_page'])."&admin_log%5Bscript%5D=".$_REQUEST['admin_log']['script']."';\" ".$submitbg.">Next Page &#62</button> &nbsp;&nbsp;&nbsp; <button type=\"button\" onClick=\"location.href='log.php?do=admin&admin_log%5Bset_form%5D=1&admin_log%5Bpage_num%5D=".$_REQUEST['admin_log']['total_pages']."&admin_log%5Btotal_pages%5D=".$_REQUEST['admin_log']['total_pages']."&admin_log%5Borderby%5D=".$_REQUEST['admin_log']['orderby']."&admin_log%5Bmadeby%5D=".$_REQUEST['admin_log']['madeby']."&admin_log%5Bentries_per_page%5D=".$_REQUEST['admin_log']['entries_per_page']."&start=".(($_REQUEST['admin_log']['entries_per_page'])*($_REQUEST['admin_log']['total_pages']-1))."&admin_log%5Bscript%5D=".$_REQUEST['admin_log']['script']."';\" ".$submitbg.">Last Page &#62&#62</button>\n");
			}
		print("</td></tr>\n");
		construct_table_END(1);

		// do footer
		admin_footer();

		exit;
	}
	
	// do prune admin log
	if($_REQUEST['pruneAdmin_log']['set_form']) {
		// make sure user can prune admin log...
		if(!canPruneAdminLog($_COOKIE['wtcBB_adminUserid'])) {
			construct_error("Sorry, you cannot prune this administrative log. If you feel you should be able to, than change the <strong>\$can_prune_adminlog</strong> variable located inside the <strong>config.php</strong> file inside the <strong>includes</strong> directory.");
			exit;
		}

		// get timestamp from the days they specified
		$_REQUEST['pruneAdmin_log']['older_than'] = mktime(0,0,0,$_REQUEST['month'],$_REQUEST['day'],$_REQUEST['year']);

		// error?
		if($_REQUEST['pruneAdmin_log']['older_than'] == -1 AND $_REQUEST['pruneAdmin_log']['madeby'] == "all" AND $_REQUEST['pruneAdmin_log']['script'] == "all") {
			construct_error("Sorry, you must enter in information to delete Administrator Log entries. <a href=\"javascript:history.back();\">Go back.</a>");
			exit;
		}

		// scripts and users and date
		if($_REQUEST['pruneAdmin_log']['older_than'] != -1) {
			$first = " action_date < ".$_REQUEST['pruneAdmin_log']['older_than'];
		} else {
			$first = "";
		}

		if($_REQUEST['pruneAdmin_log']['madeby'] != "all") {
			if($_REQUEST['pruneAdmin_log']['older_than'] != -1) {
				$annnd = "AND ";
			} else {
				$annnd = "";
			}

			$and1 = " ".$annnd."username = '".$_REQUEST['pruneAdmin_log']['madeby']."'";
		} else {
			$and1 = "";
		}

		if($_REQUEST['pruneAdmin_log']['script'] != "all") {
			if($_REQUEST['pruneAdmin_log']['older_than'] == -1 AND $_REQUEST['pruneAdmin_log']['madeby'] == "all") {
				$annnd = "";
			} else {
				$annnd = "AND ";
			}

			$and2 = " ".$annnd."file_action = '".$_REQUEST['pruneAdmin_log']['script']."'";
		} else {
			$and2 = "";
		}

		// form query to delete...
		$delete_query = "DELETE FROM log_admin WHERE".$first.$and1.$and2;

		//print($delete_query);

		// run query
		query($delete_query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for delete Administrator Log entries. You will now be redirected to the Administrator Log page.&uri=log.php?do=admin");
	}

	// do header
	admin_header("wtcBB Admin Panel - Logs - Adminstrator Log");

	construct_title("Administrator Log");

	construct_table("options","admin_log","admin_submit",1);

	construct_header("Administrator Log",2);

	construct_select_begin(1,"Log entries per page","","admin_log","entries_per_page");

		print("<option value=\"10\">10</option>\n");
		print("<option value=\"15\" selected=\"selected\">15</option>\n");
		print("<option value=\"20\">20</option>\n");
		print("<option value=\"25\">25</option>\n");
		print("<option value=\"30\">30</option>\n");
		print("<option value=\"40\">40</option>\n");
		print("<option value=\"50\">50</option>\n");
		print("<option value=\"60\">60</option>\n");
		print("<option value=\"75\">75</option>\n");
		print("<option value=\"85\">85</option>\n");
		print("<option value=\"100\">100</option>\n");

	construct_select_end(1);

	
	construct_select_begin(2,"Show only entries made by","","admin_log","madeby");

		// run query to find users
		$adminlogUsers_query = query("SELECT * FROM log_admin GROUP BY username ORDER BY username");

		print("<option value=\"all\" selected=\"selected\">All Users</option>\n");

		// loop through query
		while($adminlogUsers = mysql_fetch_array($adminlogUsers_query)) {
			print("<option value=\"".$adminlogUsers['username']."\">".$adminlogUsers['username']."</option>\n");
		}

	construct_select_end(2);


	construct_select_begin(1,"Show only entries made by this script","","admin_log","script");

		print("<option value=\"all\" selected=\"selected\">All Scripts</option>\n");
			// get scripts
			$getScripts = query("SELECT * FROM log_admin GROUP BY file_action");

			// loop
			while($scripts = mysql_fetch_array($getScripts)) {
				print("<option value=\"".$scripts['file_action']."\">".$scripts['file_action']."</option>\n");
			}

		print("</select>\n\n");

	construct_select_end(1);


	construct_select_begin(2,"Order By:","","admin_log","orderby",1);

		print("<option value=\"action_date\" selected=\"selected\">date</option>\n");
		print("<option value=\"username\">username</option>\n");
		print("<option value=\"ip_address\">IP Address</option>\n");
		print("<option value=\"logid\">ID</option>\n");
		print("</select>\n\n");
		print("<input type=\"hidden\" name=\"admin_log[page_num]\" value=\"1\" />\n");
		print("<input type=\"hidden\" name=\"start\" value=\"0\" />\n");

	construct_select_end(2,1);

	construct_footer(2,"admin_submit");

	construct_table_END(1);

	
	print("\n\n<br /><br />\n\n");

	
	construct_table("options","pruneAdmin_log","admin_delete_submit",1);

	construct_header("Prune Administrator Log",2);


	construct_select_begin(1,"Delete entries older than","","pruneAdmin_log","older_than",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month\">\n");
		construct_select_months(0,0,0,1);
		print("</select>\n\n");

		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");

		print("<input type=\"text\" name=\"day\" class=\"text\" value=\"\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");

		print("<input type=\"text\" name=\"year\" size=\"2\" class=\"text\" value=\"\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n\n");

	construct_select_end(1,1);


	construct_select_begin(2,"Only delete entries made by","","pruneAdmin_log","madeby");

		// run query to find users
		$adminlogUsers_query = query("SELECT * FROM log_admin GROUP BY username ORDER BY username");

		print("<option value=\"all\" selected=\"selected\">All Users</option>\n");

		// loop through query
		while($adminlogUsers = mysql_fetch_array($adminlogUsers_query)) {
			print("<option value=\"".$adminlogUsers['username']."\">".$adminlogUsers['username']."</option>\n");
		}

	construct_select_end(2);


	construct_select_begin(1,"Only delete entries made by this script","","pruneAdmin_log","script",1);

		print("<option value=\"all\" selected=\"selected\">All Scripts</option>\n");
			// get scripts
			$getScripts = query("SELECT * FROM log_admin GROUP BY file_action");

			// loop
			while($scripts = mysql_fetch_array($getScripts)) {
				print("<option value=\"".$scripts['file_action']."\">".$scripts['file_action']."</option>\n");
			}

		print("</select>\n\n");

	construct_select_end(1,1);


	construct_footer(2,"admin_delete_submit");

	construct_table_END(1);


	// do footer
	admin_footer();
}

// ##### DO MODERATOR LOG ##### \\
else if($_GET['do'] == "mod") {
	// make sure form is submitted...
	if($_REQUEST['mod_log']['set_form']) {
		// get current page
		$currentPage = $_REQUEST['mod_log']['page_num'];

		$end = $_REQUEST['mod_log']['entries_per_page'];

		// ASC or DESC?
		if($_REQUEST['mod_log']['orderby'] == "username") {
			$ascDesc = "ASC";
		} else {
			$ascDesc = "DESC";
		}

		// time to form select query...
		if($_REQUEST['mod_log']['madeby'] == "all") {
			if($_REQUEST['mod_log']['script'] == "all") {
				$modlog_select = "SELECT * FROM log_moderator ORDER BY ".$_REQUEST['mod_log']['orderby']." ".$ascDesc." LIMIT ".$_REQUEST['start'].", ".$end;
				$_REQUEST['mod_log']['total_entries'] = "SELECT * FROM log_moderator";
			} else {
				$modlog_select = "SELECT * FROM log_moderator WHERE filepath = '".$_REQUEST['mod_log']['script']."' ORDER BY ".$_REQUEST['mod_log']['orderby']." ".$ascDesc." LIMIT ".$_REQUEST['start'].", ".$end;
				$_REQUEST['mod_log']['total_entries'] = "SELECT * FROM log_moderator WHERE filepath = '".$_REQUEST['mod_log']['script']."'";
			}
		} else {
			if($_REQUEST['mod_log']['script'] == "all") {
				$modlog_select = "SELECT * FROM log_moderator WHERE username = '".$_REQUEST['mod_log']['madeby']."' ORDER BY ".$_REQUEST['mod_log']['orderby']." ".$ascDesc." LIMIT ".$_REQUEST['start'].", ".$end;
				$_REQUEST['mod_log']['total_entries'] = "SELECT * FROM log_moderator WHERE username = '".$_REQUEST['mod_log']['madeby']."'";
			} else {
				$modlog_select = "SELECT * FROM log_moderator WHERE filepath = '".$_REQUEST['mod_log']['script']."' AND username = '".$_REQUEST['mod_log']['madeby']."' ORDER BY ".$_REQUEST['mod_log']['orderby']." ".$ascDesc." LIMIT ".$_REQUEST['start'].", ".$end;
				$_REQUEST['mod_log']['total_entries'] = "SELECT * FROM log_moderator WHERE filepath = '".$_REQUEST['mod_log']['script']."' AND username = '".$_REQUEST['mod_log']['madeby']."'";
			}
		}

		// run query
		$modlog_query = query($modlog_select);

		//print($modlog_select."<br /><br />Start: ".$_REQUEST['start']."   End: ".$end."<br />".$_REQUEST['mod_log']['orderby']);

		$_REQUEST['mod_log']['total_entries'] = mysql_num_rows(query($_REQUEST['mod_log']['total_entries']));

		// nothin found...
		if($_REQUEST['mod_log']['total_entries'] == 0) {
			construct_error("Sorry, no entry logs were found matching the criteria given.");
			exit;
		}

		// find total pages...
		if($_REQUEST['mod_log']['total_entries'] <= $_REQUEST['mod_log']['entries_per_page']) {
			$_REQUEST['mod_log']['total_pages'] = 1;
		} else {
			// divide to find pages
			$_REQUEST['mod_log']['total_pages'] = $_REQUEST['mod_log']['total_entries'] / $_REQUEST['mod_log']['entries_per_page'];

			// make INT
			settype($_REQUEST['mod_log']['total_pages'],int);
			
			$modulus = $_REQUEST['mod_log']['total_entries'] % $_REQUEST['mod_log']['entries_per_page'];

			// if modulus is greater than zero.. add a page...
			if($modulus > 0) {
				$_REQUEST['mod_log']['total_pages']++;
			}
		}

		// do header
		admin_header("wtcBB Admin Panel - Logs - Moderator Log");

		construct_title("Moderator Log");

		construct_table("options","userinfo_form","userinfo_submit",1);
		construct_header("Page: ".$_REQUEST['mod_log']['page_num']." of ".$_REQUEST['mod_log']['total_pages']." (Total Log Entries: ".$_REQUEST['mod_log']['total_entries'].")",6);

		print("\n\n\t<tr>\n");

		print("\t\t<td class=\"cat\">\n");
		print("\t\t\tLog ID\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tUsername\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tDate\n");
		print("\t\t</td>\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tFile Path\n");
		print("\t\t</td>\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tFile Action\n");
		print("\t\t</td>\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tIP Address\n");
		print("\t\t</td>\n");

		print("\t</tr>\n\n");

		while($modlog = mysql_fetch_array($modlog_query)) {
			print("\t<tr>\n");

				print("\t\t<td class=\"desc1\" style=\"text-align: center; white-space: nowrap; width: 15%; padding: 5px;\">\n");
					print("\t\t\t".$modlog['logid']."\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					// get user ID so we can link to profile
					$userinfo = query("SELECT userid FROM user_info WHERE username = '".$modlog['username']."' LIMIT 1",1);

					print("\t\t\t<a href=\"user.php?do=edit&id=".$userinfo['userid']."\">".$modlog['username']."</a>\n");
				print("\t\t</td>\n");

				// get join date...
				$modlog['action_date'] = date("m-d-y \a\\t g:i A",$modlog['action_date']);
				print("\t\t<td class=\"desc1\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t".$modlog['action_date']."\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t".$modlog['filepath']."\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc1\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t".$modlog['file_action']."\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t".$modlog['ip_address']."\n");
				print("\t\t</td>\n");

			print("\t</tr>\n\n");
		}

		$_REQUEST['mod_log']['page_num']++;

		print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"6\">\n");
			// only one page
			if($_REQUEST['mod_log']['total_pages'] == 1) {
				print("&nbsp;");
			}

			// first page
			else if($currentPage == 1) {
				print("<button type=\"button\" onClick=\"location.href='log.php?do=mod&mod_log%5Bset_form%5D=1&mod_log%5Bpage_num%5D=".$_REQUEST['mod_log']['page_num']."&mod_log%5Btotal_pages%5D=".$_REQUEST['mod_log']['total_pages']."&mod_log%5Borderby%5D=".$_REQUEST['mod_log']['orderby']."&mod_log%5Bmadeby%5D=".$_REQUEST['mod_log']['madeby']."&mod_log%5Bentries_per_page%5D=".$_REQUEST['mod_log']['entries_per_page']."&start=".($_REQUEST['start']+$_REQUEST['mod_log']['entries_per_page'])."&mod_log%5Bscript%5D=".$_REQUEST['mod_log']['script']."';\" ".$submitbg.">Next Page &gt;</button> &nbsp;&nbsp;&nbsp; <button type=\"button\" onClick=\"location.href='log.php?do=mod&mod_log%5Bset_form%5D=1&mod_log%5Bpage_num%5D=".$_REQUEST['mod_log']['total_pages']."&mod_log%5Btotal_pages%5D=".$_REQUEST['mod_log']['total_pages']."&mod_log%5Borderby%5D=".$_REQUEST['mod_log']['orderby']."&mod_log%5Bmadeby%5D=".$_REQUEST['mod_log']['madeby']."&mod_log%5Bentries_per_page%5D=".$_REQUEST['mod_log']['entries_per_page']."&start=".(($_REQUEST['mod_log']['entries_per_page'])*($_REQUEST['mod_log']['total_pages']-1))."&mod_log%5Bscript%5D=".$_REQUEST['mod_log']['script']."';\" ".$submitbg.">Last Page &gt;&gt;</button>\n");
			}

			// last page
			else if($currentPage == $_REQUEST['mod_log']['total_pages']) {
				print("<button type=\"button\" onClick=\"location.href='log.php?do=mod&mod_log%5Bset_form%5D=1&mod_log%5Bpage_num%5D=1&mod_log%5Btotal_pages%5D=".$_REQUEST['mod_log']['total_pages']."&mod_log%5Borderby%5D=".$_REQUEST['mod_log']['orderby']."&mod_log%5Bmadeby%5D=".$_REQUEST['mod_log']['madeby']."&mod_log%5Bentries_per_page%5D=".$_REQUEST['mod_log']['entries_per_page']."&start=0&mod_log%5Bscript%5D=".$_REQUEST['mod_log']['script']."';\" ".$submitbg.">&#60&#60 First Page</button> &nbsp;&nbsp;&nbsp; <button type=\"button\" onClick=\"location.href='log.php?do=mod&mod_log%5Bset_form%5D=1&mod_log%5Bpage_num%5D=".($_REQUEST['mod_log']['page_num']-2)."&mod_log%5Btotal_pages%5D=".$_REQUEST['mod_log']['total_pages']."&mod_log%5Borderby%5D=".$_REQUEST['mod_log']['orderby']."&mod_log%5Bmadeby%5D=".$_REQUEST['mod_log']['madeby']."&mod_log%5Bentries_per_page%5D=".$_REQUEST['mod_log']['entries_per_page']."&start=".($_REQUEST['start']-$_REQUEST['mod_log']['entries_per_page'])."&mod_log%5Bscript%5D=".$_REQUEST['mod_log']['script']."';\" ".$submitbg.">&#60 Previous Page</button>\n");
			}

			// page inbetween
			else {
				print("<button type=\"button\" onClick=\"location.href='log.php?do=mod&mod_log%5Bset_form%5D=1&mod_log%5Bpage_num%5D=1&mod_log%5Btotal_pages%5D=".$_REQUEST['mod_log']['total_pages']."&mod_log%5Borderby%5D=".$_REQUEST['mod_log']['orderby']."&mod_log%5Bmadeby%5D=".$_REQUEST['mod_log']['madeby']."&mod_log%5Bentries_per_page%5D=".$_REQUEST['mod_log']['entries_per_page']."&start=0&mod_log%5Bscript%5D=".$_REQUEST['mod_log']['script']."';\" ".$submitbg.">&#60&#60 First Page</button> &nbsp;&nbsp;&nbsp; <button type=\"button\" onClick=\"location.href='log.php?do=mod&mod_log%5Bset_form%5D=1&mod_log%5Bpage_num%5D=".($_REQUEST['mod_log']['page_num']-2)."&mod_log%5Btotal_pages%5D=".$_REQUEST['mod_log']['total_pages']."&mod_log%5Borderby%5D=".$_REQUEST['mod_log']['orderby']."&mod_log%5Bmadeby%5D=".$_REQUEST['mod_log']['madeby']."&mod_log%5Bentries_per_page%5D=".$_REQUEST['mod_log']['entries_per_page']."&start=".($_REQUEST['start']-$_REQUEST['mod_log']['entries_per_page'])."&mod_log%5Bscript%5D=".$_REQUEST['mod_log']['script']."';\" ".$submitbg.">&#60 Previous Page</button> &nbsp;&nbsp;&nbsp; <button type=\"button\" onClick=\"location.href='log.php?do=mod&mod_log%5Bset_form%5D=1&mod_log%5Bpage_num%5D=".$_REQUEST['mod_log']['page_num']."&mod_log%5Btotal_pages%5D=".$_REQUEST['mod_log']['total_pages']."&mod_log%5Borderby%5D=".$_REQUEST['mod_log']['orderby']."&mod_log%5Bmadeby%5D=".$_REQUEST['mod_log']['madeby']."&mod_log%5Bentries_per_page%5D=".$_REQUEST['mod_log']['entries_per_page']."&start=".($_REQUEST['start']+$_REQUEST['mod_log']['entries_per_page'])."&mod_log%5Bscript%5D=".$_REQUEST['mod_log']['script']."';\" ".$submitbg.">Next Page &gt;</button> &nbsp;&nbsp;&nbsp; <button type=\"button\" onClick=\"location.href='log.php?do=mod&mod_log%5Bset_form%5D=1&mod_log%5Bpage_num%5D=".$_REQUEST['mod_log']['total_pages']."&mod_log%5Btotal_pages%5D=".$_REQUEST['mod_log']['total_pages']."&mod_log%5Borderby%5D=".$_REQUEST['mod_log']['orderby']."&mod_log%5Bmadeby%5D=".$_REQUEST['mod_log']['madeby']."&mod_log%5Bentries_per_page%5D=".$_REQUEST['mod_log']['entries_per_page']."&start=".(($_REQUEST['mod_log']['entries_per_page'])*($_REQUEST['mod_log']['total_pages']-1))."&mod_log%5Bscript%5D=".$_REQUEST['mod_log']['script']."';\" ".$submitbg.">Last Page &gt;&gt;</button>\n");
			}
		print("</td></tr>\n");
		construct_table_END(1);

		// do footer
		admin_footer();

		exit;
	}
	
	// do prune mod log
	if($_REQUEST['pruneMod_log']['set_form']) {
		// get timestamp from the days they specified
		$_REQUEST['pruneMod_log']['older_than'] = mktime(0,0,0,$_REQUEST['month'],$_REQUEST['day'],$_REQUEST['year']);

		// error?
		if($_REQUEST['pruneMod_log']['older_than'] == -1 AND $_REQUEST['pruneMod_log']['madeby'] == "all" AND $_REQUEST['pruneMod_log']['script'] == "all") {
			construct_error("Sorry, you must enter in information to delete Moderator Log entries. <a href=\"javascript:history.back();\">Go back.</a>");
			exit;
		}

		// scripts and users and date
		if($_REQUEST['pruneMod_log']['older_than'] != -1) {
			$first = " action_date < ".$_REQUEST['pruneMod_log']['older_than'];
		} else {
			$first = "";
		}

		if($_REQUEST['pruneMod_log']['madeby'] != "all") {
			if($_REQUEST['pruneMod_log']['older_than'] != -1) {
				$annnd = "AND ";
			} else {
				$annnd = "";
			}

			$and1 = " ".$annnd."username = '".$_REQUEST['pruneMod_log']['madeby']."'";
		} else {
			$and1 = "";
		}

		if($_REQUEST['pruneMod_log']['script'] != "all") {
			if($_REQUEST['pruneMod_log']['older_than'] == -1 AND $_REQUEST['pruneMod_log']['madeby'] == "all") {
				$annnd = "";
			} else {
				$annnd = "AND ";
			}

			$and2 = " ".$annnd."filepath = '".$_REQUEST['pruneMod_log']['script']."'";
		} else {
			$and2 = "";
		}

		// form query to delete...
		$delete_query = "DELETE FROM log_moderator WHERE".$first.$and1.$and2;

		//print($delete_query);

		// run query
		query($delete_query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for deleting Moderator Log entries. You will now be redirected to the Moderator Log page.&uri=log.php?do=mod");
	}

	// do header
	admin_header("wtcBB Admin Panel - Logs - Moderator Log");

	construct_title("Moderator Log");

	construct_table("options","mod_log","mod_submit",1);

	construct_header("Moderator Log",2);

	construct_select_begin(1,"Log entries per page","","mod_log","entries_per_page");

		print("<option value=\"10\">10</option>\n");
		print("<option value=\"15\" selected=\"selected\">15</option>\n");
		print("<option value=\"20\">20</option>\n");
		print("<option value=\"25\">25</option>\n");
		print("<option value=\"30\">30</option>\n");
		print("<option value=\"40\">40</option>\n");
		print("<option value=\"50\">50</option>\n");
		print("<option value=\"60\">60</option>\n");
		print("<option value=\"75\">75</option>\n");
		print("<option value=\"85\">85</option>\n");
		print("<option value=\"100\">100</option>\n");

	construct_select_end(1);

	
	construct_select_begin(2,"Show only entries made by","","mod_log","madeby");

		// run query to find users
		$modlogUsers_query = query("SELECT * FROM log_moderator GROUP BY username ORDER BY username");

		print("<option value=\"all\" selected=\"selected\">All Users</option>\n");

		// loop through query
		while($modlogUsers = mysql_fetch_array($modlogUsers_query)) {
			print("<option value=\"".$modlogUsers['username']."\">".$modlogUsers['username']."</option>\n");
		}

	construct_select_end(2);


	construct_select_begin(1,"Show only entries made by this script","","mod_log","script");

		print("<option value=\"all\" selected=\"selected\">All Scripts</option>\n");
			// get scripts
			$getScripts = query("SELECT * FROM log_moderator GROUP BY filepath");

			// loop
			while($scripts = mysql_fetch_array($getScripts)) {
				print("<option value=\"".$scripts['filepath']."\">".$scripts['filepath']."</option>\n");
			}

		print("</select>\n\n");

	construct_select_end(1);


	construct_select_begin(2,"Order By:","","mod_log","orderby",1);

		print("<option value=\"action_date\" selected=\"selected\">date</option>\n");
		print("<option value=\"username\">username</option>\n");
		print("<option value=\"ip_address\">IP Address</option>\n");
		print("<option value=\"logid\">ID</option>\n");
		print("</select>\n\n");
		print("<input type=\"hidden\" name=\"mod_log[page_num]\" value=\"1\" />\n");
		print("<input type=\"hidden\" name=\"start\" value=\"0\" />\n");

	construct_select_end(2,1);

	construct_footer(2,"mod_submit");

	construct_table_END(1);

	
	print("\n\n<br /><br />\n\n");

	
	construct_table("options","pruneMod_log","mod_delete_submit",1);

	construct_header("Prune Moderator Log",2);


	construct_select_begin(1,"Delete entries older than","","pruneMod_log","older_than",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month\">\n");
		construct_select_months(0,0,0,1);
		print("</select>\n\n");

		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");

		print("<input type=\"text\" name=\"day\" class=\"text\" value=\"\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");

		print("<input type=\"text\" name=\"year\" size=\"2\" class=\"text\" value=\"\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n\n");

	construct_select_end(1,1);


	construct_select_begin(2,"Only delete entries made by","","pruneMod_log","madeby");

		// run query to find users
		$modlogUsers_query = query("SELECT * FROM log_moderator GROUP BY username ORDER BY username");

		print("<option value=\"all\" selected=\"selected\">All Users</option>\n");

		// loop through query
		while($modlogUsers = mysql_fetch_array($modlogUsers_query)) {
			print("<option value=\"".$modlogUsers['username']."\">".$modlogUsers['username']."</option>\n");
		}

	construct_select_end(2);


	construct_select_begin(1,"Only delete entries made by this script","","pruneMod_log","script",1);

		print("<option value=\"all\" selected=\"selected\">All Scripts</option>\n");
			// get scripts
			$getScripts = query("SELECT * FROM log_moderator GROUP BY filepath");

			// loop
			while($scripts = mysql_fetch_array($getScripts)) {
				print("<option value=\"".$scripts['filepath']."\">".$scripts['filepath']."</option>\n");
			}

		print("</select>\n\n");

	construct_select_end(1,1);


	construct_footer(2,"mod_delete_submit");

	construct_table_END(1);


	// do footer
	admin_footer();
}

// otherwise we have an error on our hands.. invalid page...
else {
	construct_error("Invalid page");
}

?>