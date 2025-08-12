<?php
/* 
	CascadianFAQ v4.1 - Last Updated: November 2003
	Summer S. Wilson, Eclectic Designs, http://eclectic-designs.com
	cfaq@eclectic-designs.com
*/

// Include configuration and database files
include ("config.php");
include ("functions.php");

if (!isset($dbtype) )
	die("You must run the <a href=\"upgrade.php\">updater</a> script before CascadianFAQ v4.1 will work correctly.");

session_start();

if (isset($_GET['result']))
	$result = stripslashes($_GET['result']);

// Fix for register_globals being off
if ( isset($_GET['page'])) 
	$page = $_GET['page'];
elseif ( isset($_POST['page'])) 
	$page = $_POST['page'];

// Check for login and give form if user isn't
if (!isset($_SESSION['addymin'])) {
	if (! isset ($_POST['uservalue']) ) {
		include ("header.php");

		if (isset($_GET['error']))
			$error = $_GET['error'];
		else
			$error = "";
			
		print "
			<p>Please login to access the CascadianFAQ administration area. All entries are case sensitive!</p>

			<p><strong>$error</strong></p>
			
			<form action=\"$PHP_SELF\" enctype=\"multipart/form-data\" method=\"post\">
			<table width=\"300\" align=\"center\">
			<tr>
				<td><strong>Username</strong></td>
				<td><input type=\"Text\" name=\"uservalue\" width=\"15\" maxlength=\"15\"></td>
			</tr>
			<tr>
				<td><strong>Password</strong></td>
				<td><input type=\"password\" name=\"password\" width=\"15\" maxlength=\"15\"></td>
			</tr>
			<tr>
				<td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"Log Me In\"></td>
			</tr>
			</table>
			</form>
		";
		adminfooter();
		include ("footer.php");
	}
	// If form is being submitted, check values to see if the login is good.  If it is then
	// set session variables and pass to main screen, else kick back to form with error.
	else {
	
		ConnectToDatabase();
		
		$uservalue = $_POST['uservalue'];
		$password = $_POST['password'];
		
		$checkquery = "Select username, firstname, lastname, email, accesslevel FROM cfaq_admin WHERE username = '$uservalue' AND password = '$password'";
		$results = run_my_query( $checkquery, "Error checking username against database");
		$num_rows = get_num_rows( $results );
		
		if ( $num_rows != 1 ) {
			header( "Location: $PHP_SELF?error=Invalid%20Login" );	
		}
		else {
		
			$userinfo = fetch_my_array( $results );
		
			$_SESSION['username'] = $userinfo['username'];
			$_SESSION['name'] = "$userinfo[firstname] $userinfo[lastname]";
			$_SESSION['email'] = $userinfo['email'];
			$_SESSION['addymin'] = "Yes";
			$_SESSION['accesslevel'] = $userinfo['accesslevel'];
		
			header( "Location: $PHP_SELF" );	
		}
	}
}
else {	
	$email = $_SESSION['email'];
	$username = $_SESSION['username'];
	$name = $_SESSION['name'];
	$addymin = $_SESSION['addymin'];
	$accesslevel = $_SESSION['accesslevel'];
	
	// Display main menu
	if ( !isset($_GET['page'] )) {
		include ("header.php");
		ConnectToDatabase();
		print "
		<p align=\"center\"><strong>$name, welcome to the CascadianFAQ Administration area.</strong></p>
		<table width=\"600\" border=\"2\" align=\"center\" bordercolor=\"#000000\" cellspacing=\"0\" cellpadding=\"5\">
			<tr bordercolor=\"$background\"> 
				<td><strong><a href=\"$PHP_SELF?page=categories\">Manage Categories</a></strong></td>
				<td>Allows you to add/edit/delete categories</td>
			</tr>
			<tr bordercolor=\"$background\"> 
				<td><strong><a href=\"$PHP_SELF?page=questions\">Manage Questions</a></strong></td>
				<td>Add/edit/delete questions in the FAQ</td>
			</tr>";
		
		if ($usersubmit == 1) {
			$questionquery = "Select submissionid FROM cfaq_submissions";
			$submittedq = run_my_query( $questionquery, "Error pulling submitted question count from database");
			$num_rows = get_num_rows( $submittedq );
			
			if ($num_rows > 0)
				print "<tr bordercolor=\"$background\"> 
						<td nowrap><strong><a href=\"$PHP_SELF?page=submittedq\">User Submitted Questions</a></strong></td>
						<td>Accept or reject questions submitted by users</td>
					</tr>";
		}

		$questionquery = "Select qid FROM cfaq_qandas WHERE answer IS NULL or answer = ''";
		$unansweredq = run_my_query( $questionquery, "Error pulling submitted question count from database");
		$num_rows = get_num_rows( $unansweredq );
		
		if ($num_rows > 0)
			print "<tr bordercolor=\"$background\"> 
					<td nowrap><strong><a href=\"$PHP_SELF?page=questions&catid=unanswered\">Unanswered Questions</a></strong></td>
					<td>Questions in the database with no answers</td>
				</tr>";
		
		if ($accesslevel == 1)
			print "
				<tr bordercolor=\"$background\"> 
					<td><strong><a href=\"$PHP_SELF?page=users\">Manage Users</a></strong></td>
					<td>Add/edit/delete users allowed to manage the FAQ</td>
				</tr>";

		print "
			<tr bordercolor=\"$background\"> 
				<td><strong><a href=\"$PHP_SELF?page=logout\">Logout</a></strong></td>
				<td>Exit the CascadianFAQ system</td>
			</tr>
		</table>
		";
		adminfooter();
		include ("footer.php");
	}

	// Category management area
	elseif ($page == "categories") {
		// Display cat list and cat add/edit form
		if (!isset ($_POST['action']) ) {
			ConnectToDatabase();
			if ( isset ($_GET['editcatid']) ) {
				$editcatid = $_GET['editcatid'];
				$catquery = "Select cat, description, maincat FROM cfaq_cats WHERE catid = $editcatid";
				$catinfo = run_my_query( $catquery, "Error pulling category information from database");
				$catinfo = fetch_my_array( $catinfo );
				
				$cat = stripslashes($catinfo[cat]);
				$description = stripslashes($catinfo[description]);
				if ($catinfo[maincat] != "")
					$maincat = $catinfo[maincat];
				else
					$maincat = "";
					
				$pagename = "Update Category<BR>or click <a href=\"$PHP_SELF?page=categories\">here to add a new cat</a>";
			}
			else {
				$cat = "";
				$description = "";
				$maincat = 0;
				$pagename = "Add Category";
			}
		
			include ("header.php");
			
			print "
				<h4 align=\"center\">Manage Categories</h4>
				<p align=\"center\"><strong>$result</strong></p>
				
				<p>To edit a category, click on its name in the current category list.  You can then edit its name, description, and set its parent category.  To add a cat, just fill in its information and click the button.</p>
				
				<p>If you move it to a new parent category, all of its subcategories stay with it and become &quot;grandchildren&quot; under the new category.  Deleting a parent cat will orphan its subcats.</p>
				
				<table width=\"90%\" border=\"0\" align=\"center\">
				<tr valign=\"top\" align=\"left\"> 
					<td> 
						<p align=\"center\"><strong>$pagename</strong></p>
						<form name=\"form1\" method=\"post\" action=\"$PHP_SELF?page=categories\">";
			
			if ( isset ($editcatid) )
				print "<input type=\"hidden\" name=\"editcatid\" value=\"$editcatid\">";

				print "
						<table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\">
							<tr valign=\"top\"> 
								<td><strong>Category Name</strong></td>
								<td> 
									<input type=\"text\" name=\"cat\" size=\"25\" maxlength=\"25\" value=\"$cat\">
								</td>
							</tr>
							<tr valign=\"top\"> 
								<td><strong>Description</strong></td>
								<td> 
									<input type=\"text\" name=\"description\" maxlength=\"255\" size=\"30\" value=\"$description\">
								</td>
							</tr>
							<tr valign=\"top\"> 
								<td><strong>Subcat of</strong></td>
								<td> 
									<select name=\"maincat\">";
				// If this is a main category, and not an orphan, highlight Main Category
				if ($maincat == 0 && $maincat != "")
					print "<option value=\"0\" selected>Main Category</option>";
				else
					print "<option value=\"0\">Main Category</option>";
					
				// Only show assigned cats, if at zero access level
				if ($accesslevel == 0)
					$maincatsq = "Select cfaq_cats.catid, cat FROM cfaq_cats INNER JOIN cfaq_admintocats ON cfaq_cats.catid = cfaq_admintocats.catid WHERE maincat = 0 AND cfaq_admintocats.username = '$username'";
				else

					$maincatsq = "Select catid, cat FROM cfaq_cats WHERE maincat = 0 ORDER BY cat";

				$maincats = run_my_query( $maincatsq, "Error pulling main category list from database");
				
				while ($maincatlist = fetch_my_array( $maincats )) {
					$thiscat = stripslashes($maincatlist[cat]);
					
					if ($maincatlist[catid] == $maincat) 
						print "<option value=\"$maincatlist[catid]\" selected>$thiscat</option>";
					else
						print "<option value=\"$maincatlist[catid]\">$thiscat</option>";
					
					if ($maincatlist[catid] != $editcatid)
						subcats_option("$maincatlist[catid]", "$maincat", 2);
				}
				
				print "
									</select>
								</td>
							</tr>";
				if (!isset($editcatid))
					print "<input type=\"hidden\" name=\"action\" value=\"addcat\">";
				else
					print "<tr><td colspan=\"2\" align=\"center\"><input type=\"radio\" name=\"action\" value=\"updatecat\" checked> Update Category | <input type=\"radio\" name=\"action\" value=\"deletecat\"> Delete Category</td></tr>";
				
				print "
							<tr align=\"center\" valign=\"top\"> 
								<td colspan=\"2\"> 
									<input type=\"submit\" name=\"Submit\" value=\"Submit\">
								</td>
							</tr>
						</table>
					</form>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
				</td>
				<td>
					<p align=\"center\"><strong>Current Categories</strong></p>
					<ul>
					";
						if ($accesslevel == 0)
							$maincats = "Select cfaq_cats.catid, cat FROM cfaq_cats INNER JOIN cfaq_admintocats ON cfaq_cats.catid = cfaq_admintocats.catid WHERE maincat = 0 AND cfaq_admintocats.username = '$username'";
						else
							$maincats = "Select catid, cat FROM cfaq_cats WHERE maincat = 0 ORDER BY cat";
						$cats = run_my_query( $maincats, "Error pulling main category list from database");
						
						while ($maincatlist = fetch_my_array( $cats )) {
							$maincat = stripslashes($maincatlist[cat]);
							print "<li><a href=\"$PHP_SELF?page=categories&editcatid=$maincatlist[catid]\">$maincat</a></li>";
							print "<ul>";
							subcats_list("$maincatlist[catid]");
							print "</ul>";
						}
						
						$orphanedcatsq = "Select catid, cat FROM cfaq_cats WHERE maincat IS NULL ORDER BY cat";
						$orphanedcats = run_my_query( $orphanedcatsq, "Error pulling orphaned category list from database");
						print "<li>Orphaned Categories</li>";
						print "<ul>";
							while ($orphancatlist = fetch_my_array( $orphanedcats )) {
								$thiscat = stripslashes($orphancatlist[cat]);
								print "<li><a href=\"$PHP_SELF?page=categories&editcatid=$orphancatlist[catid]\">$thiscat</a></li>";
							}
						print "</ul>";

				print "
					</ul>
				</td>
			</tr>
			</table>
			<p>&nbsp;</p>
			";
			adminfooter();
			include ("footer.php");

		}
		else {
			$action = $_POST['action'];
			
			if (isset($_POST['editcatid']))
				$editcatid = $_POST['editcatid'];

			$cat = $_POST['cat'];
			$description = $_POST['description'];
			$maincat = $_POST['maincat'];

			if ($action == "addcat")
				addcat($cat, $description, $maincat);
			elseif ($action == "updatecat")
				updatecat($editcatid, $cat, $description, $maincat);
			elseif ($action == "deletecat")
				deletecat ($editcatid);
			else
				$results = "<p>Invalid action call.</p>";
			
			header( "Location: $PHP_SELF?page=categories&result=$result" );
		}
	}
	
	// Administrate user submitted questions
	elseif ($page == "submittedq") {
		// Display question list and question add/edit form
		if (!isset ($_POST['action']) ) {
			ConnectToDatabase();
			// Display list of submittedq if one hasn't been selected yet
			include ("header.php");
			// Display add form and current submittedq for the selected category
			print "<h4 align=\"center\">User Submitted Questions</h4>";
			
			?>
			
			<SCRIPT LANGUAGE="JavaScript">
			<!-- Original:  Ronnie T. Moore -->
			<!-- Web Site:  The JavaScript Source -->
			
			<!-- Dynamic 'fix' by: Nannette Thacker -->
			<!-- Web Site: http://www.shiningstar.net -->
			
			<!-- This script and many more are available free online at -->
			<!-- The JavaScript Source!! http://javascript.internet.com -->
			
			<!-- Begin
			function textCounter(field, countfield, maxlimit) {
			if (field.value.length > maxlimit) // if too long...trim it!
			field.value = field.value.substring(0, maxlimit);
			// otherwise, update 'characters left' counter
			else 
			countfield.value = maxlimit - field.value.length;
			}
			// End -->
			</script>

			<?php

			if ( isset ($_GET['submittedqid']) ) {
				$submittedqid = $_GET['submittedqid'];
				$qquery = "Select question, submittername, submitteremail, submitterip, suggestedcat, datesubmitted FROM cfaq_submissions WHERE submissionid = $submittedqid";
				$qinfo = run_my_query( $qquery, "Error pulling submitted question information from database");
				$qinfo = fetch_my_array( $qinfo );
				
				$question = stripslashes($qinfo[question]);
				$submittername = $qinfo[submittername];
				$submitteremail = $qinfo[submitteremail];
				$submitterip = $qinfo[submitterip];
				$suggestedcat = $qinfo[suggestedcat];
				$datesubmitted = $qinfo[datesubmitted];
			}
			
			print "<p align=\"center\"><strong>$result</strong></p>
				
				<p>To review a user submitted question, click on it.  If you want to add it to your FAQ, add an answer, choose accept, and hit the update button.  Otherwise, choose reject, and it will be deleted.</p>
				
				<p>Note: you can use HTML to format the answer, if desired, however you must include either a &lt;p&gt;, &lt;BR&gt;, or &lt;ul&gt; tag in order to keep the system from converting your line breaks.  If none of those tags are found, the hard returns will be preserved.</p>
				
				<table width=\"90%\" border=\"0\" align=\"center\">
				<tr valign=\"top\" align=\"left\"> 
					<td> 
						<form name=\"form1\" method=\"post\" action=\"$PHP_SELF?page=submittedq\">";
			
			if ( isset ($submittedqid) ) {
				print "<input type=\"hidden\" name=\"submittedqid\" value=\"$submittedqid\">

						<table width=\"500\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\">
							<tr valign=\"top\"> 
								<td><strong>Question (maximum length 255)</strong></td>
								<td> 
									<textarea name=\"question\" rows=\"4\" cols=\"40\" onKeyDown=\"textCounter(this.form.question,this.form.remLen,255);\" onKeyUp=\"textCounter(this.form.question,this.form.remLen,255);\">$question</textarea>
									<br>
									<input readonly type=text name=remLen size=3 maxlength=3 value=\"255\"> characters left
								</td>
							</tr>
							<tr valign=\"top\"> 
								<td><strong>Answer</strong></td>
								<td> 
									<textarea name=\"answer\" rows=\"5\" cols=\"40\">$answer</textarea>
								</td>
							</tr>
							<tr valign=\"top\"> 
								<td><strong>Suggested Category</strong></td>
								<td> 
									<select name=\"categorylist[]\" multiple size=\"5\">";
				
				$assignedlist[] = $suggestedcat;
				
				if ($accesslevel == 0)
					$maincats = "Select cfaq_cats.catid, cat FROM cfaq_cats INNER JOIN cfaq_admintocats ON cfaq_cats.catid = cfaq_admintocats.catid WHERE maincat = 0 AND cfaq_admintocats.username = '$username'";
				else
					$maincats = "Select catid, cat FROM cfaq_cats WHERE maincat = 0 ORDER BY cat";
				$cats = run_my_query( $maincats, "Error pulling main category list from database");
				
				while ($maincatlist = fetch_my_array( $cats )) {
					if ( in_array( "$maincatlist[catid]", $assignedlist) )
						print "<option value=\"$maincatlist[catid]\" selected>$maincatlist[cat]</option>";
					else
						print "<option value=\"$maincatlist[catid]\">$maincatlist[cat]</option>";
					
					subcats_qoption($maincatlist[catid], "$maincat", 2, $assignedlist);
				}
				
				print "
									</select>
								</td>
							</tr>
							<tr valign=\"top\"> 
								<td><strong>Date Submitted</strong></td>
								<td> 
									$datesubmitted (if accepted, date added will be set to this date)
									<input type=\"hidden\" name=\"datesubmitted\" value=\"$datesubmitted\">
								</td>
							</tr>
							<tr valign=\"top\"> 
								<td><strong>Submitter</strong></td>
								<td> 
									$submittername - $submitteremail<BR>
									IP: $submitterip
								</td>
							</tr>
							<tr><td colspan=\"2\" align=\"center\"><input type=\"radio\" name=\"action\" value=\"acceptquestion\" checked> Accept Question | <input type=\"radio\" name=\"action\" value=\"rejectquestion\"> Reject Question</td></tr>

							<tr align=\"center\" valign=\"top\"> 
								<td colspan=\"2\"> 
									<input type=\"submit\" name=\"Submit\" value=\"Submit\">
								</td>
							</tr>
						</table>
					</form>
				</td>
				</tr>
				";
			}
			print "<tr>
				<td>
					<p align=\"center\"><strong>Unprocessed User Submitted Questions</strong></p>
					<ul>
					";
						$questionquery = "Select submissionid, question, datesubmitted FROM cfaq_submissions  ORDER BY datesubmitted DESC";
						$submittedq = run_my_query( $questionquery, "Error pulling question list from database");
						
						while ($questionlist = fetch_my_array( $submittedq )) {
							$question = stripslashes($questionlist[question]);
							print "<li><a href=\"$PHP_SELF?page=submittedq&submittedqid=$questionlist[submissionid]\">$question</a> - Submitted on $questionlist[datesubmitted]</li>";
						}
				print "
						</ul>
					</td>
				</tr>
				</table>
				<p>&nbsp;</p>
				";
		
			adminfooter();
			include ("footer.php");
	
		}
		else {
			$action = $_POST['action'];
			
			$submittedqid = $_POST['submittedqid'];
			$datesubmitted = $_POST['datesubmitted'];
			$question = $_POST['question'];
			$answer = $_POST['answer'];
			$categorylist = $_POST['categorylist'];
			
			// call the appropriate function depending on the action variable
			if ($action == "acceptquestion")
				addsubmittedquestion($submittedqid, $question, $answer, $categorylist, $datesubmitted);
			elseif ($action == "rejectquestion")
				rejectsubmittedquestion ($submittedqid, $question);
			else
				$results = "<p>Invalid action call.</p>";
			
			header( "Location: $PHP_SELF?page=submittedq&result=$result" );
		}
	}
	
	// Administrate questions
	elseif ($page == "questions") {
		// Display question list and question add/edit form
		if (! isset ($_POST['action']) ) {
			ConnectToDatabase();
			// Display list of cats if one hasn't been selected yet
			if ( !isset($_GET['catid']) && !isset($_GET['editqid']) ) {
				include ("header.php");
				print "
					<h4 align=\"center\">Manage Questions</h4>
					<p>Please select which category you would like to work in.</p>
					<p><strong>Note:</strong> The selected category will not show questions that are assigned to its subcategories unless that question is also assigned to the choosen category.</p>
					<ul>
						";
						if ($accesslevel == 0)
							$maincats = "Select cfaq_cats.catid, cat FROM cfaq_cats INNER JOIN cfaq_admintocats ON cfaq_cats.catid = cfaq_admintocats.catid WHERE maincat = 0 AND cfaq_admintocats.username = '$username'";
						else
							$maincats = "Select catid, cat FROM cfaq_cats WHERE maincat = 0 ORDER BY cat";
						$cats = run_my_query( $maincats, "Error pulling main category list from database");
						
						while ($maincatlist = fetch_my_array( $cats )) {
							$maincat = stripslashes($maincatlist[cat]);
							$thiscatid = $maincatlist[catid];
							print "<li><a href=\"$PHP_SELF?page=questions&catid=$maincatlist[catid]\">$maincat</a></li>";
							print "<ul>";
							subcats_qlist($thiscatid);
							print "</ul>";
						}
								
						if ($accesslevel == 1) {

							$questionquery = "Select qid FROM cfaq_qandas WHERE answer IS NULL or answer = ''";
							$unansweredq = run_my_query( $questionquery, "Error pulling unanswered question count from database");
							$num_rows = get_num_rows( $unansweredq );
							
							if ($num_rows > 0)
								print "<li><a href=\"$PHP_SELF?page=questions&catid=unanswered\">Unanswered Questions</a></li>";
								
							
							$questionquery = "Select cfaq_qandas.qid FROM cfaq_qandas LEFT JOIN cfaq_whichcats ON cfaq_qandas.qid = cfaq_whichcats.qid WHERE cfaq_whichcats.catid IS NULL";
							$unansweredq = run_my_query( $questionquery, "Error pulling unanswered question count from database");
							$num_rows = get_num_rows( $unansweredq );
							
							if ($num_rows > 0)
								print "<li><a href=\"$PHP_SELF?page=questions&catid=orphan\">Orphaned Questions</a></li>";
							
							print "<li><a href=\"$PHP_SELF?page=questions&catid=showall\">All Questions in FAQ</a></li>";
						}
						print "</ul>";
			}
			// Display add form and current questions for the selected category
			else {
				if ( isset ($_GET['editqid']) ) {
					$editqid = $_GET['editqid'];
					$qquery = "Select qid, question, answer, dateadded, viewed FROM cfaq_qandas WHERE qid = $editqid";
					$qinfo = run_my_query( $qquery, "Error pulling question information from database");
					$qinfo = fetch_my_array( $qinfo );
					
					$question = stripslashes($qinfo[question]);
					$answer = stripslashes($qinfo[answer]);
					$dateadded = $qinfo[dateadded];
					$viewed = $qinfo[viewed];
					$pagename = "Update Question<BR>or click <a href=\"$PHP_SELF?page=questions\">here to add a new question</a>";
				}
				else {
					$question = "";
					$answer = "";
					$pagename = "Add Question";
				}
			
				include ("header.php");
				$catid = $_GET['catid'];
				
				if ($catid == "unanswered") {
					$cat = "Unanswered Questions";
					$pagetitle = "Manage Unanswered Questions";
					$query = "Select qid, question FROM cfaq_qandas WHERE answer IS NULL OR answer LIKE '' ORDER BY question";
				}
				elseif ($catid == "orphan") {
					$cat = "Orphaned Questions";
					$pagetitle = "Manage Orphaned Questions";
					$query = "Select cfaq_qandas.qid, question FROM cfaq_qandas LEFT JOIN cfaq_whichcats ON cfaq_qandas.qid = cfaq_whichcats.qid WHERE cfaq_whichcats.catid IS NULL ORDER BY question";
				}
				elseif ($catid == "showall") {
					$cat = "All Questions in $faqname";
					$pagetitle = "Manage All Questions";
					$query = "Select qid, question FROM cfaq_qandas ORDER BY question";
				}
				else {
					$catquery = "Select cat FROM cfaq_cats WHERE catid = $catid";
					$thiscat = run_my_query( $catquery, "Error retrieving category name from database");
					$cat = fetch_my_array( $thiscat );
					$cat = stripslashes($cat[cat]);
					$pagetitle = "Manage Questions in $cat";
					$query = "Select cfaq_qandas.qid, question FROM cfaq_qandas INNER JOIN cfaq_whichcats ON cfaq_qandas.qid = cfaq_whichcats.qid WHERE cfaq_whichcats.catid = $catid ORDER BY question";
				}
				
				?>
			
				<SCRIPT LANGUAGE="JavaScript">
				<!-- Original:  Ronnie T. Moore -->
				<!-- Web Site:  The JavaScript Source -->
				
				<!-- Dynamic 'fix' by: Nannette Thacker -->
				<!-- Web Site: http://www.shiningstar.net -->
				
				<!-- This script and many more are available free online at -->
				<!-- The JavaScript Source!! http://javascript.internet.com -->
				
				<!-- Begin
				function textCounter(field, countfield, maxlimit) {
				if (field.value.length > maxlimit) // if too long...trim it!
				field.value = field.value.substring(0, maxlimit);
				// otherwise, update 'characters left' counter
				else 
				countfield.value = maxlimit - field.value.length;
				}
				// End -->
				</script>
	
				<?php
					
				print "
					<h4 align=\"center\">$pagetitle</h4>
					<p align=\"center\"><strong>$result</strong></p>
					
					<p>To edit a question, click on it in the question list at the bottom of the page.  You can then edit the question, its answer, and/or change which categories it is assigned to.   To add a new question, just fill in add form and click the button.</p>
					<p>Note: you can use HTML to format the answer, if desired, however you must include either a &lt;p&gt;, &lt;BR&gt;, or &lt;ul&gt; tag in order to keep the system from converting your line breaks.  If none of those tags are found, the hard returns will be preserved.</p>
					
					<table width=\"90%\" border=\"0\" align=\"center\">
					<tr valign=\"top\" align=\"left\"> 
						<td> 
							<p align=\"center\"><strong>$pagename</strong></p>
							<form name=\"form1\" method=\"post\" action=\"$PHP_SELF?page=questions\">";
				
				if ( isset ($editqid) ) {
					print "<p align=\"center\"><strong>This question has been viewed $viewed times.</strong></p>";
					print "<input type=\"hidden\" name=\"editqid\" value=\"$editqid\">";
				}
					print "
							<table width=\"500\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\">
								<tr valign=\"top\"> 
									<td><strong>Question (maximum length 255)</strong></td>
									<td> 
										<textarea name=\"question\" rows=\"4\" cols=\"40\" onKeyDown=\"textCounter(this.form.question,this.form.remLen,255);\" onKeyUp=\"textCounter(this.form.question,this.form.remLen,255);\">$question</textarea>
										<br>
										<input readonly type=text name=remLen size=3 maxlength=3 value=\"255\"> characters left
									</td>
								</tr>
								<tr valign=\"top\"> 
									<td><strong>Answer</strong></td>
									<td> 
										<textarea name=\"answer\" rows=\"5\" cols=\"40\">$answer</textarea>
									</td>
								</tr>";
	
					if ( isset ($editqid) ) {
							print "<tr valign=\"top\"> 
									<td><strong>Date Added (yyyy-mm-dd)</strong></td>
									<td> 
										<input type=\"text\" name=\"dateadded\" size=\"10\" maxlength=\"10\" value=\"$dateadded\">
									</td>
								</tr>";
					}
						print "<tr valign=\"top\"> 
									<td><strong>Categories</strong></td>
									<td> 
										<select name=\"categorylist[]\" multiple size=\"5\">";
					
					// For questions being edited, pull the list of currently assigned cats
					if (isset($editqid)) {
						$assignedq = "Select catid FROM cfaq_whichcats WHERE qid = $editqid";
						$assignedcatlist = run_my_query( $assignedq, "Error pulling assigned category list from database");
						while ($assignedcats = fetch_my_array( $assignedcatlist )) 
							$assignedlist[] = "$assignedcats[catid]";
					}
					// otherwise set the assigned cat equal to the category being worked in
					elseif (isset($catid))
						$assignedlist[] = $catid;
					else
						$assignedlist[] = "";
					
					$maincats = "Select catid, cat FROM cfaq_cats WHERE maincat = 0 ORDER BY cat";
					$cats = run_my_query( $maincats, "Error pulling main category list from database");
					
					while ($maincatlist = fetch_my_array( $cats )) {
						if ( in_array( "$maincatlist[catid]", $assignedlist) )
							print "<option value=\"$maincatlist[catid]\" selected>$maincatlist[cat]</option>";
						else
							print "<option value=\"$maincatlist[catid]\">$maincatlist[cat]</option>";
						
						subcats_qoption($maincatlist[catid], "$maincat", 2, $assignedlist);
					}
					
					print "
										</select>
									</td>
								</tr>";
					if (!isset($editqid))
						print "<input type=\"hidden\" name=\"action\" value=\"addquestion\">";
					else
						print "<tr><td colspan=\"2\" align=\"center\"><input type=\"radio\" name=\"action\" value=\"updatequestion\" checked> Update Question | <input type=\"radio\" name=\"action\" value=\"deletequestion\"> Delete Question</td></tr>";
					
					print "
								<input type=\"hidden\" name=\"catid\" value=\"$catid\">
								<tr align=\"center\" valign=\"top\"> 
									<td colspan=\"2\"> 
										<input type=\"submit\" name=\"Submit\" value=\"Submit\">
									</td>
								</tr>
							</table>
						</form>
					</td>
					</tr>
					<tr>
					<td>
						<p align=\"center\"><strong>$cat</strong></p>
						<ul>
						";
							$questions = run_my_query( $query, "Error pulling question list from database");
							
							while ($questionlist = fetch_my_array( $questions )) {
								$question = stripslashes($questionlist[question]);
								print "<li><a href=\"$PHP_SELF?page=questions&editqid=$questionlist[qid]&catid=$catid\">$question</a></li>";
							}
					print "
						</ul>
					</td>
				</tr>
				</table>
				<p>&nbsp;</p>
				";
			}
			adminfooter();
			include ("footer.php");
	
		}
		else {
			$action = $_POST['action'];
			
			if (isset($_POST['editqid']))
				$editqid = $_POST['editqid'];
			
			$dateadded = $_POST['dateadded'];
			$question = $_POST['question'];
			$answer = $_POST['answer'];
			$categorylist = $_POST['categorylist'];
			
			// call the appropriate function depending on the action variable
			if ($action == "addquestion")
				addquestion($question, $answer, $categorylist);
			elseif ($action == "updatequestion")
				updatequestion($editqid, $question, $answer, $dateadded, $categorylist);
			elseif ($action == "deletequestion")
				deletequestion ($editqid);
			else
				$results = "<p>Invalid action call.</p>";
			
			if (isset($_POST['catid']) && $_POST['catid'] != '') {
				$catid = $_POST['catid'];
				header( "Location: $PHP_SELF?page=questions&catid=$catid&result=$result" );
			}
			else
				header( "Location: $PHP_SELF?page=questions&catid=unanswered&result=$result" );
		}
	}
	
	
	elseif ($page == "users") {
		// Display user list and user add/edit form
		if (!isset ($_POST['action']) or isset ($_GET['error']) ) {
			ConnectToDatabase();
			if ( isset ($_GET['editusername']) ) {
				$editusername = $_GET['editusername'];
				$userquery = "Select username, firstname, lastname, email, accesslevel FROM cfaq_admin WHERE username = '$editusername'";
				$userinfo = run_my_query( $userquery, "Error pulling user information from database");
				$userinfo = fetch_my_array( $userinfo );
				
				$newusername = stripslashes($userinfo['username']);
				$firstname = stripslashes($userinfo['firstname']);
				$lastname = stripslashes($userinfo['lastname']);
				$useremail = stripslashes($userinfo['email']);
				$accesslevel = $userinfo['accesslevel'];
				
				// For category editors, pull list of assigned cats
				if ($accesslevel == 0) {
					$query = "Select catid FROM cfaq_admintocats WHERE username = '$editusername'";
					$assignedcatlist = run_my_query($query, "Error pulling list of assigned cats from database");
					while ($assignedcats = fetch_my_array( $assignedcatlist )) 
						$assignedlist[] = "$assignedcats[catid]";
				}
				
				$pagename = "Update User<BR>or click <a href=\"$PHP_SELF?page=users\">here to add a new user</a>";
			}
			else {
				$newusername = "";
				$firstname = "";
				$lastname = "";
				$useremail = "";
				$accesslevel = "0";
				$assignedlist[] = "";
				$pagename = "Add User";
			}
		
			include ("header.php");
			print "
				<h4 align=\"center\">Manage Users</h4>";
			if (isset($_GET['result'])) {
				$result = $_GET['result'];
				print "<p align=\"center\"><strong>$result</strong></p>";
			}
			else if  (isset($_GET['error'])) {
				$error = $_GET['error'];
				print "<p align=\"center\"><strong>$error</strong></p>";
			}
				
			print "<p>To edit a user, click on their name in the current user list.  You can then edit all of their info.  To add a user, just fill in their information and click the button.  Duplicate user names are not allowed!</p>
				
				<table width=\"90%\" border=\"0\" align=\"center\">
				<tr valign=\"top\" align=\"left\"> 
					<td> 
						<p align=\"center\"><strong>$pagename</strong></p>
						<form name=\"form1\" method=\"post\" action=\"$PHP_SELF?page=users\">";
			
			if ( isset ($editusername) ) {
				print "<input type=\"hidden\" name=\"editusername\" value=\"$editusername\">";
				$passwordextra = "<BR>Enter new password to change it, otherwise leave blank!";
			}
			else
				$passwordextra == "";
				
			print "
					<table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\">
						<tr valign=\"top\"> 
							<td><strong>First Name</strong></td>
							<td> 
								<input type=\"text\" name=\"firstname\" maxlength=\"25\" size=\"25\" value=\"$firstname\">
							</td>
						</tr>
						<tr valign=\"top\"> 
							<td><strong>Last Name</strong></td>
							<td> 
								<input type=\"text\" name=\"lastname\" maxlength=\"25\" size=\"25\" value=\"$lastname\">
							</td>
						</tr>
						<tr valign=\"top\"> 
							<td><strong>Email</strong></td>
							<td> 
								<input type=\"text\" name=\"useremail\" maxlength=\"255\" size=\"30\" value=\"$useremail\">
							</td>
						</tr>
						<tr valign=\"top\">
							<td><strong>Assigned Categories</strong><BR>(For non-omnipotent admins only)</td>
							<td>
								<select name=\"categorylist[]\" multiple size=\"5\">";
						
						$maincats = "Select catid, cat FROM cfaq_cats WHERE maincat = 0 ORDER BY cat";
						$cats = run_my_query( $maincats, "Error pulling main category list from database");
						
						while ($maincatlist = fetch_my_array( $cats )) {
							if ( in_array( "$maincatlist[catid]", $assignedlist) )
								print "<option value=\"$maincatlist[catid]\" selected>$maincatlist[cat]</option>";
							else
								print "<option value=\"$maincatlist[catid]\">$maincatlist[cat]</option>";
							
							subcats_qoption($maincatlist[catid], "$maincat", 2, $assignedlist);
						}
						print "</select>
							</td>
						</tr>
						<tr valign=\"top\"> 
							<td><strong>User Name (Up to 15 characters - no apostrophes!)</strong></td>
							<td> 
								<input type=\"text\" name=\"newusername\" size=\"15\" maxlength=\"15\" value=\"$newusername\">
							</td>
						</tr>
						<tr valign=\"top\"> 
							<td><strong>Password (Up to 15 characters - no apostrophes!)</strong><em>$passwordextra</em></td>
							<td> 
								<input type=\"password\" name=\"password\" maxlength=\"15\" size=\"15\">
							</td>
						</tr>
						<tr valign=\"top\"> 
							<td><strong>Confirm Password</strong><em>$passwordextra</em></td>
							<td> 
								<input type=\"password\" name=\"confirmpassword\" maxlength=\"15\" size=\"15\">
							</td>
						</tr>
						<tr valign=\"top\">
							<td align=\"left\"><strong>Access Level</strong></td>
							<td align=\"left\" nowrap><input type=\"radio\" name=\"accesslevel\" value=\"0\"";
							if ($accesslevel == 0)
								print " checked";
							print "> Regular | <input type=\"radio\" name=\"accesslevel\" value=\"1\"";
							if ($accesslevel == 1)
								print " checked";
							print "> Omnipotent</td>
						</tr>";
					if (!isset($editusername))
						print "<input type=\"hidden\" name=\"action\" value=\"adduser\">";
					else
						print "<tr><td colspan=\"2\" align=\"center\"><input type=\"radio\" name=\"action\" value=\"updateuser\" checked> Update User | <input type=\"radio\" name=\"action\" value=\"deleteuser\"> Delete User</td></tr>";
			
						print "<tr align=\"center\" valign=\"top\"> 
							<td colspan=\"2\"> 
								<input type=\"submit\" name=\"Submit\" value=\"Submit\">
							</td>
						</tr>
					</table>
				</form>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
			</td>
			<td>
				<p align=\"center\"><strong>Current users</strong></p>
				<ul>
				";
					$mainusers = "Select username, firstname, lastname FROM cfaq_admin ORDER BY username";
					$users = run_my_query( $mainusers, "Error pulling main user list from database");
					
					while ($mainuserlist = fetch_my_array( $users )) {
						$username = stripslashes($mainuserlist[username]);
						$firstname = stripslashes($mainuserlist[firstname]);
						$lastname = stripslashes($mainuserlist[lastname]);
						print "<li><a href=\"$PHP_SELF?page=users&editusername=$username\">$username</a> -- $lastname, $firstname</li>";
					}
			print "
					</ul>
				</td>
			</tr>
			</table>
			<p>&nbsp;</p>
			";
			adminfooter();
			include ("footer.php");
	
		}
		else {
			$action = $_POST['action'];
			
			if (isset($_POST['editusername']))
				$editusername = $_POST['editusername'];
			
			$newusername = $_POST['newusername'];
			$password = $_POST['password'];
			$confirmpassword = $_POST['confirmpassword'];
			$firstname = $_POST['firstname'];
			$lastname = $_POST['lastname'];
			$useremail = $_POST['useremail'];
			$accesslevel = $_POST['accesslevel'];
			$categorylist = $_POST['categorylist'];
			
			if ($action == "adduser")
				adduser($newusername, $password, $confirmpassword, $firstname, $lastname, $useremail, $accesslevel, $categorylist);
			elseif ($action == "updateuser")
				updateuser($editusername, $newusername, $password, $confirmpassword, $firstname, $lastname, $useremail, $accesslevel, $categorylist);
			elseif ($action == "deleteuser")
				deleteuser ($editusername);
			else
				die("Invalid action call.");
			
			if (isset($result))
				if ($action == "updateuser")
					header( "Location: $PHP_SELF?page=users&result=$result&editusername=$editusername" );
				else
					header( "Location: $PHP_SELF?page=users&result=$result" );
		}
	}

	elseif ($page == "logout") {
		session_destroy();
		header( "Location: $PHP_SELF");
	}
}

?>