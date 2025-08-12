<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ############### //ADMIN PANEL ANNOUNCEMENT\\ ############## \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// define a few variables
$fileAction = "Announcements";
$permissions = "announcements";

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");

// make sure we know it's announcement...
$is_announcement = true;

// do add announcement
if($_GET['do'] == "add") {
	// translate to unix timestamp with mktime
	$_POST['announce']['start_date'] = mktime(0,0,0,$_POST['month'],$_POST['day'],$_POST['year']);
	$_POST['announce']['end_date'] = mktime(0,0,0,$_POST['month_end'],$_POST['day_end'],$_POST['year_end']);

	// going to update information...
	if($_POST['announce']['set_form']) {
		print("<br /><br />");

		// set counter
		$i = 0;

		// intialize the $query var
		$query = "INSERT INTO announcements (username,userid,date_addedUpdated,";

		foreach($_POST['announce'] as $option_key => $option_value) {
			if($option_key != "set_form") {
				// look for comma
				if($i) {
					$comma = ",";
				} else {
					$comma = "";
					$i++;
				}

				$query .= $comma.$option_key;
			}
		}

		$query .= ") VALUES ('".$_COOKIE['wtcBB_adminUsername']."','".$_COOKIE['wtcBB_adminUserid']."','".time()."',";

		// reset counter...
		$i = 0;

		foreach($_POST['announce'] as $option_key => $option_value) {
			if($option_key != "set_form") {
				// look for comma
				if($i) {
					$comma = ",";
				} else {
					$comma = "";
					$i++;
				}

				$query .= $comma."'".htmlspecialchars(addslashes($option_value))."'";
			}
		}

		$query .= ")";

		// update the DB
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for adding an announcement. You will now be redirected back.&uri=announcement.php?do=edit");

		/*print("<br /><br />");

		print($query);

		print("<br /><br />");*/
	}

	// do header
	admin_header("wtcBB Admin Panel - Add Announcement");

	construct_title("Add Announcement");

	construct_table("options","announce","announce_submit",1);
	construct_header("Add Announcement",2);

	construct_select(1,"Forum","Select here the forum you wish for the announcement to be in. Select \"All Forums\" for it to be a global announcement.","announce","forum","",0,0,0,2);

	construct_text(2,"Title","Input here the title of your announcement.","announce","title","");

	construct_select_begin(1,"Start date","Input here the start date in which you want the announcement to be displayed.","announce","start_date",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month\">\n");
		construct_select_months();
		print("</select>\n\n");

		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");

		// get current date.. 
		$current_date = date("d");

		print("<input type=\"text\" name=\"day\" class=\"text\" value=\"".$current_date."\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");

		// get full year...
		$full_year = date("Y");

		print("<input type=\"text\" name=\"year\" size=\"2\" class=\"text\" value=\"".$full_year."\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n\n");

	construct_select_end(1,1);

	construct_select_begin(2,"End date","Input here the date in which the announcement will be deleted, and no longer in effect.","announce","end_date",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month_end\">\n");
		construct_select_months(1);
		print("</select>\n\n");

		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");

		// current month.. plus one
		$current_month = date("n");
		$current_month++;
	
		// get today
		$today = date("d");

		// get current date.. 
		$current_date = date("d",mktime(0,0,0,$current_month,$today,2003));

		print("<input type=\"text\" name=\"day_end\" class=\"text\" value=\"".$current_date."\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");

		// get full year...
		$full_year = date("Y");

		print("<input type=\"text\" name=\"year_end\" size=\"2\" class=\"text\" value=\"".$full_year."\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n\n");

	construct_select_end(2,1);

	construct_input(1,"Parse BB Code?","","announce","parse_bbcode",0,1);

	construct_input(2,"Parse Smilies?","","announce","parse_smilies",1,1);

	?>
	
	<tr>
		<td class="desc1_bottom" colspan="2" style="border-top: 1px solid #000000;">
			<b>Text:</b> <br /><br />
				
				<div align="center">
					<textarea name="announce[message]" cols="60" rows="13"></textarea>
				</div>

		</td>
	</tr>

	<?php

	construct_footer(2,"announce_submit");
	construct_table_END(1);

	// do footer
	admin_footer();
}

elseif($_GET['do'] == "edit") {
	if($_GET['id']) {
		// run the query using the ID...
		$check_and_run = mysql_query("SELECT * FROM announcements WHERE announcementid = '".$_GET['id']."'");

		// make sure it's valid...
		if(mysql_num_rows($check_and_run) < 1) {
			construct_error("Invalid announcement ID");
		}

		// otherwise we are good to go...
		else {
			// let's see if we want to delete an announcement...
			if($_GET['action'] == "delete") {

				// make sure form is set..
				if(isset($_POST['confirm']['set_form'])) {
					// yes...
					if($_POST['confirm']['yes_no']) {
						query("DELETE FROM announcements WHERE announcementid = '".$_GET['id']."' LIMIT 1");

						redirect("thankyou.php?message=Thank you for deleting the announcement. You will now be redirected back.&uri=announcement.php?do=edit");
					}

					// no...
					else {
						redirect("announcement.php?do=edit");
					}
				}
				
				// do a confirm page...
				construct_confirm();
			}

			// otherwise we are just editing it...
			else {

				// fetch the results of the previous query.. avoid running two
				$announce_stuff = mysql_fetch_array($check_and_run);

				// translate to unix timestamp with mktime
				$_POST['announce']['start_date'] = mktime(0,0,0,$_POST['month2'],$_POST['day2'],$_POST['year2']);
				$_POST['announce']['end_date'] = mktime(0,0,0,$_POST['month_end2'],$_POST['day_end2'],$_POST['year_end2']);

				// only do the below if the form is set...
				if($_POST['announce']['set_form']) {

					print("<br /><br />");

					// set counter
					$i = 0;

					// intialize the $query var
					$query = "UPDATE announcements SET ";

					foreach($_POST['announce'] as $option_key => $option_value) {
						// check to make sure we don't input the "set_form"
						if($option_key != "set_form") {
							// should we use comma?
							if(!$i) {
								$comma = "";
							} else {
								$comma = ", ";
							}

							// form the update query...
							$query .= $comma;
							$query .= $option_key." = '".htmlspecialchars(addslashes($option_value))."'";

							// increment $i
							$i++;
						}
					} 

					$query .= " , username = '".$_COOKIE['wtcBB_adminUsername']."' , date_addedUpdated = '".time()."' , userid = '".$_COOKIE['wtcBB_adminUserid']."' WHERE announcementid = '".$_GET['id']."'";

					// update the DB
					query($query);

					// redirect to thankyou page...
					redirect("thankyou.php?message=Thank you for editing <b>".$announce_stuff['title']."</b>. You will now be redirected back.&uri=announcement.php?do=edit&amp;id=".$announce_stuff['announcementid']);

					/* print("<br /><br />");

					print($query);

					print("<br /><br />"); */
				}

				// alright now use the "add" interface for the edit...

				// do header
				admin_header("wtcBB Admin Panel - Edit Announcement");

				construct_title("Edit Announcement");

				print("\n\n<br />\n\n");

				construct_table("options","announce","announce_submit",1);
				construct_header("Add Announcement",2);

				construct_select(1,"Forum","Select here the forum you wish for the announcement to be in. Select \"All Forums\" for it to be a global announcement.","announce","forum","",0,0,0,2);

				construct_text(2,"Title","Input here the title of your announcement.","announce","title",$announce_stuff['title']);

				construct_select_begin(1,"Start date","Input here the start date in which you want the announcement to be displayed.","announce","start_date",0,1);

					print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

					print("<td style=\"text-align: left; font-size: 8pt;\">\n");
					print("Month<br />\n");

					print("<select name=\"month2\">\n");
					construct_select_months(0,1,1);
					print("</select>\n\n");

					print("</td>\n");


					print("<td style=\"text-align: left; font-size: 8pt;\">\n");
					print("Day<br />\n");

					// get start date.. 
					$current_date = date("d",$announce_stuff['start_date']);

					print("<input type=\"text\" name=\"day2\" class=\"text\" value=\"".$current_date."\" style=\"padding: 1px; width: 30px;\" />\n");

					print("</td>\n");


					print("<td style=\"text-align: left; font-size: 8pt;\">\n");
					print("Year<br />\n");

					// get full year...
					$full_year = date("Y",$announce_stuff['start_date']);

					print("<input type=\"text\" name=\"year2\" size=\"2\" class=\"text\" value=\"".$full_year."\" style=\"padding: 1px; width: 30px;\" />\n");

					print("</td></tr></table>\n\n");

				construct_select_end(1,1);


				construct_select_begin(2,"End date","Input here the date in which the announcement will be deleted, and no longer in effect.","announce","end_date",0,1);

					print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

					print("<td style=\"text-align: left; font-size: 8pt;\">\n");
					print("Month<br />\n");

					print("<select name=\"month_end2\">\n");
					construct_select_months(1,1,2);
					print("</select>\n\n");

					print("</td>\n");


					print("<td style=\"text-align: left; font-size: 8pt;\">\n");
					print("Day<br />\n");

					// current month.. plus one
					$current_month = date("n");
					$current_month++;

					// get end date.. 
					$current_date = date("d",$announce_stuff['end_date']);

					print("<input type=\"text\" name=\"day_end2\" class=\"text\" value=\"".$current_date."\" style=\"padding: 1px; width: 30px;\" />\n");

					print("</td>\n");


					print("<td style=\"text-align: left; font-size: 8pt;\">\n");
					print("Year<br />\n");

					// get full year...
					$full_year = date("Y",$announce_stuff['end_date']);

					print("<input type=\"text\" name=\"year_end2\" size=\"2\" class=\"text\" value=\"".$full_year."\" style=\"padding: 1px; width: 30px;\" />\n");

					print("</td></tr></table>\n\n");

				construct_select_end(2,1);

				construct_input(1,"Parse BB Code?","","announce","parse_bbcode",0,0,$announce_stuff);

				construct_input(2,"Parse Smilies?","","announce","parse_smilies",1,0,$announce_stuff);

				?>
				
				<tr>
					<td class="desc1_bottom" colspan="2" style="border-top: 1px solid #000000;">
						<b>Text:</b> <br /><br />
							
							<div align="center">
								<textarea name="announce[message]" cols="60" rows="13"><?php print($announce_stuff['message']); ?></textarea>
							</div>

					</td>
				</tr>

				<?php

				construct_footer(2,"announce_submit");
				construct_table_END(1);

				// do footer
				admin_footer();
			}
		}
	}

	// otherwise we are displaying the announcements to edit...
	else {
		// do header
		admin_header("wtcBB Admin Panel - Edit Announcements");

		construct_title("Edit Announcements");

		// run the query..
		$run_query = mysql_query("SELECT * FROM announcements ORDER BY announcementid");

		// make sure we have announcements.. if not just return a message...
		if(mysql_num_rows($run_query) < 1) {
			print("<blockquote style=\"width: 90%; text-align: left;\">\n");
			print("<br />No announcements found in the database.");
			print("\n</blockquote>");
		}

		// otherwise we HAVE lift off!!!
		else {

			print("\n<br />\n");
	
			// run query to check to see if there are any global announcements...
			$global_announce_query = mysql_query("SELECT * FROM announcements WHERE forum = '-1' ORDER BY title");

			if(mysql_num_rows($global_announce_query)) {
				construct_table("options","announce","announce_submit");
				construct_header("Global Announcements",3);

				print("\t<tr>\n");

				print("\t\t<td class=\"desc1\" style=\"width: 30%; padding: 7px;\">\n");
				print("\t\t\t<strong>Global Announcements</strong>\n");
				print("\t\t</td>\n\n");

				print("\t\t<td class=\"desc2\" style=\"border-left: none; width: 70%; padding: 7px;\">\n");
				print("\t\t\t");

				print("<ul style=\"margin-bottom: 0px; margin-left: 20px;\">\n");

				while($announcement = mysql_fetch_array($global_announce_query)) {
					// get date...
					$start_date = date("m-d-y",$announcement['start_date']);
					$end_date = date("m-d-y",$announcement['end_date']);

					print("<li><strong>".$announcement['title']."</strong> <form method=\"post\" action=\"\" style=\"margin: 0px; display: inline;\"><button type=\"button\" onClick=\"location.href='announcement.php?do=edit&id=".$announcement['announcementid']."';\" style=\"margin-right: 5px; margin-bottom: 5px;\" ".$submitbg.">Edit</button> <button type=\"button\" onClick=\"location.href='announcement.php?do=edit&id=".$announcement['announcementid']."&action=delete';\" style=\"margin-bottom: 5px;\" ".$submitbg.">Delete</button></form> <span class=\"small\"> &nbsp;&nbsp;(".$announcement['username'].") (".$start_date." to ".$end_date.")</span></li>\n");
				}

				print("</ul>\n\n");

				print("\n");

				print("\t\t</td>\n\n");


				print("\t\t<td class=\"desc1\" style=\"border-left: none; width: 0%; padding: 7px;\">\n");
				print("\t\t\t<form style=\"margin: 0px;\" method=\"post\" action=\"\">\n");
				print("\t\t\t\t<button type=\"button\" onClick=\"location.href='announcement.php?do=add';\" ".$submitbg.">NEW</button>\n");
				print("\t\t\t</form>\n");
				print("\t\t</td>\n\n");

				print("\t</tr>\n\n\n");

				print("\t<tr><td class=\"footer\" colspan=\"3\" style=\"border-top: none;\">&nbsp;</td></tr>\n");
				construct_table_END();

				print("\n\n<br /><br />\n\n");
			}

			construct_table("options","announce","announce_submit");
			construct_header("Forum Specific Announcements",3);

			loop_announcements();

			print("\t<tr><td class=\"footer\" colspan=\"3\" style=\"border-top: none;\">&nbsp;</td></tr>\n");
			construct_table_END();
		}

		// do footer
		admin_footer();
	}
}

// otherwise we have an error on our hands.. invalid page...
else {
	construct_error("Invalid page");
}

?>