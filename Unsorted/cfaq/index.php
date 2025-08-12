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

// Determine what to show to the user
if ( isset($_GET['qid']) ) { // Display question and answer
	$qid = $_GET['qid'];
	
	if (isset($_GET['catid']))
		$catid = $_GET['catid'];
	
	include ("header.php");
	ConnectToDatabase();
	if ($topmenu == 1 and isset($catid)) 
		whereareu($catid);
	elseif (isset($frommostrecent) )
		print "<p align=\"center\"><a href=\"$cfaqindex\">Home</a> > Most Recently Asked Questions</p>";
	elseif (isset($frommostpopular))
		print "<p align=\"center\"><a href=\"$cfaqindex\">Home</a> > Most Popular Questions</p>";
	else
		print "<p align=\"center\"><a href=\"$cfaqindex\">Home</a> > <a href=\"$cfaqindex?runsearch=start\">Search FAQ</a></p>";
	
	$query = "Select question, answer, viewed FROM cfaq_qandas WHERE qid = $qid";
	$qinfo = run_my_query( $query, "Error pulling subcat list from database");
	$question = fetch_my_array( $qinfo );
	
	// Strip slashes, then change hard returns to <BR> and spaces to &nbsp; if there are no <BR> or paragraph tags already in place
	$thisquestion = $question[question];
	if (substr_count($thisquestion, "<p") == 0 && substr_count($thisquestion, "<BR>") == 0)
		$thisquestion = nl2br($thisquestion);
	$thisquestion = str_replace("  ", "&nbsp;&nbsp;", $thisquestion);
	$thisquestion = stripslashes($thisquestion);

	$thisanswer = $question[answer];
	if (substr_count($thisanswer, "<p") == 0 && substr_count($thisanswer, "<BR>") == 0 && substr_count($thisanswer, "<ul") == 0)
		$thisanswer = nl2br($thisanswer);
	$thisanswer = str_replace("  ", "&nbsp;&nbsp;", $thisanswer);
	$thisanswer = stripslashes($thisanswer);
	
	print "<P><strong>$thisquestion</strong></P>";
	print "<blockquote>$thisanswer</blockquote>";
	
	$newview = $question[viewed] + 1;
	$query = "UPDATE cfaq_qandas SET viewed = $newview WHERE qid = $qid";
	$qinfo = run_my_query( $query, "Error updating view count");

	footerlinks();
	include ("footer.php");
}

elseif ( isset($_GET['submitq']) or isset($_POST['submitq']) ) { // Question submission page
	// check for form submission.  If none, show form
	if (!isset($_POST['question'])) {
		if (isset($_GET['catid']))
			$catid = $_GET['catid'];

		include ("header.php");
		ConnectToDatabase();
		if ($topmenu == 1 and isset($catid)) 
			whereareu($catid);
		else
			print "<p align=\"center\"><a href=\"$cfaqindex\">Home</a> > <a href=\"$cfaqindex?submitq=yes\">Submit New Question</a></p>";
	
		$submitterip = $_SERVER['REMOTE_ADDR'];
		
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
			<p align=\"center\"><strong>Submit New Question</strong></p>
			<form name=\"form1\" method=\"post\" action=\"$PHP_SELF?page=questions\">
			<input type=\"hidden\" name=\"submitq\" value=\"yes\">
			<table width=\"550\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\">
			<tr valign=\"top\"> 
				<td><strong>New Question</strong><BR>(maximum length 255)</td>
				<td> 
					<textarea name=\"question\" rows=\"4\" cols=\"40\" onKeyDown=\"textCounter(this.form.question,this.form.remLen,255);\" onKeyUp=\"textCounter(this.form.question,this.form.remLen,255);\">$question</textarea>
					<br>
					<input readonly type=text name=remLen size=3 maxlength=3 value=\"255\"> characters left
				</td>
			</tr>
			<tr valign=\"top\"> 
				<td><strong>Your Name</strong></td>
				<td> 
					<input type=\"text\" name=\"submittername\" size=\"30\" maxlength=\"100\">
				</td>
			</tr>
			<tr valign=\"top\"> 
				<td><strong>Your Email</strong><BR>(not required)</td>
				<td> 
					<input type=\"text\" name=\"submitteremail\" size=\"30\" maxlength=\"255\">
				</td>
			</tr>
			<tr valign=\"top\"> 
				<td><strong>Your IP</strong><BR>(recorded for security)</td>
				<td>$submitterip<input type=\"hidden\" name=\"submitterip\" size=\"30\" maxlength=\"255\" value=\"$submitterip\"></td>
			</tr>
			<tr valign=\"top\"> 
				<td><strong>Suggested Category</strong></td>
				<td> 
					<select name=\"catid\" size=\"5\">";
					// For questions being edited, pull the list of currently assigned cats
					if (isset($catid)) {
						$assignedlist[] = $catid;
					}
					// otherwise set the assigned cat equal to the category being worked in
					else
						$assignedlist[] = "";
					
					$maincats = "Select catid, cat FROM cfaq_cats WHERE maincat = 0 ORDER BY cat";
					$cats = run_my_query( $maincats, $link ) or die("Error pulling main category list from database");
					
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
				<td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"Submit\"></td>
			</tr>
			</table>
			</form>
			";
		footerlinks();
		include ("footer.php");
	}
	else {
		$question = $_POST['question'];
		$submittername = $_POST['submittername'];
		$submitteremail = $_POST['submitteremail'];
		$submitterip = $_POST['submitterip'];
		$catid = $_POST['catid'];

		if ($question == "")
			die("You must enter a question to add to the database!");
		if ($submittername == "")
			die("You must enter your name in order to submit a question to the database!");
		
		// if question and name have been entered, call add function
		usersubmittedquestion($question, $submittername, $submitteremail, $submitterip, $catid);

		include ("header.php");
		print "<p align=\"center\"><strong>$result</strong></p>";
		footerlinks();
		include ("footer.php");
	}
}

elseif ( isset($_GET['catid']) ) { // Show subcats and Qs in a category
	$catid = $_GET['catid'];
	include ("header.php");
	ConnectToDatabase();

	if ($topmenu == 1)
		whereareu($catid);

	$query = "SELECT catid, cat, description
		FROM cfaq_cats
		WHERE maincat = $catid
		ORDER BY cat";
	$subcatlist = run_my_query( $query, "Error pulling subcat list from database");
	$num_rows = get_num_rows( $subcatlist );
	
	// Show subcat list if category has any
	if ($num_rows > 0) {
		print "<dl>";
		while ($subcats = fetch_my_array( $subcatlist )) {
			$cat = stripslashes($subcats[cat]);
			print "<dt><a href=\"$cfaqindex?catid=$subcats[catid]\"><strong>$cat</strong>";
			if ($showcounts == 1) {
				$query = "SELECT DISTINCT qid 
					FROM cfaq_whichcats INNER JOIN cfaq_cats ON  cfaq_whichcats.catid = cfaq_cats.catid
					WHERE maincat = $subcats[catid]
						OR cfaq_whichcats.catid = $subcats[catid]";
				$qlist = run_my_query( $query, "Error pulling question counts from database");
				$qcount = get_num_rows( $qlist );
				print " ($qcount)";
			}
			print "</a></dt>";
			if ($usedescripts == 1) {
				$catdescription = stripslashes($subcats[description]);
				print "<dd>$catdescription</dd>";
			}
		}
		print "</dl>";
	}
	
	// Show question list, if any are available
	$query = "SELECT cfaq_qandas.qid, question
		FROM cfaq_qandas INNER JOIN cfaq_whichcats ON cfaq_qandas.qid = cfaq_whichcats.qid
		WHERE catid = $catid
		ORDER BY question";
	$qlist = run_my_query( $query, "Error pulling question list from database");
	$num_rows = get_num_rows( $qlist );
	
	if ($num_rows == 0)
		print "<p align=\"center\">There are no questions listed in this category</p>";
	else {
		print "<p>&nbsp;</p>";
		if ($num_rows == 1)
			print "<p>There is $num_rows question available in this category.</p>";
		else
			print "<p>There are $num_rows questions available in this category.</p>";
		print "<ul>";
		while ($questions = fetch_my_array( $qlist )) {
			$question = stripslashes($questions[question]);
			print "<li> &nbsp; <a href=\"$cfaqindex?qid=$questions[qid]&catid=$catid\">$question</a></li>";
		}
		print "</ul>";
	}
	
	footerlinks();
	include ("footer.php");
}

elseif ( isset($_GET['runsearch']) or isset ($_POST['runsearch'])) {  // search page
	// fixer for having registered globals off
	if (isset($_GET['runsearch']))
		$runsearch = $_GET['runsearch'];
	else	
		$runsearch = $_POST['runsearch'];
		
	include ("header.php");
	if ($runsearch == "yes") { //process search and display results
		$searchterms = escapeshellcmd($_POST['searchterms']);
		$limitcat = $_POST['limitcat'];
		processsearch($searchterms,$limitcat);
	}
	else { // display search dialog
		ConnectToDatabase();	
		print "
			<h4 align=\"center\">Search $faqname</h4>

			<form name=\"form1\" method=\"post\" action=\"$PHP_SELF\">
			<input type=\"hidden\" name=\"runsearch\" value=\"yes\">
			<table width=\"500\" border=\"0\" align=\"center\" cellpadding=\"4\" cellspacing=\"0\">
			<tr align=\"left\" valign=\"top\">
				<td nowrap><p><strong>Enter Search Terms</strong></p></td>
				<td><input name=\"searchterms\" type=\"text\" id=\"searchterms\" size=\"30\" maxlength=\"100\"></td>
			</tr>
			<tr align=\"left\" valign=\"top\">

				<td nowrap><strong>Limit to a specific category?</strong></td>
				<td>
					<select name=\"limitcat\">
					<option value=\"\">-- Search All Categories --</option>
					";

		$maincats = "Select catid, cat FROM cfaq_cats WHERE maincat = 0 ORDER BY cat";
		$cats = run_my_query( $maincats, $link ) or die("Error pulling category list from database");

		while ($maincatlist = fetch_my_array( $cats )) {
			$cat = stripslashes($maincatlist[cat]);
			if ($maincatlist[catid] == $maincat)
				print "<option value=\"$maincatlist[catid]\" selected>$cat</option>";
			else
				print "<option value=\"$maincatlist[catid]\">$cat</option>";
			
			subcats_qoption("$maincatlist[catid]", "$maincat", 2, "");
		}

		print "
					</select>
				</td>
			</tr>
			<tr>
				<td colspan=\"2\" align=\"center\" valign=\"top\">
					<input name=\"Submit\" type=\"submit\" id=\"Submit\" value=\"Run My Seach\">
				</td>
			</tr>
			</table>
			</form>
		";
	}
	footerlinks();
	include ("footer.php");
}

else { // Display entry page
	include ("header.php");
	$isindex = "Yes";
	print "$intro";
	
	if ($mostrecent == 1)
		getmostrecent();

	if ($mostpopular == 1)
		getmostpopular();
		
	if (($mostpopular == 1 && $anypopular == "yes") || $mostrecent == 1)
		print "<strong>Frequently Asked Questions</strong>";
	
	print "<dl>";
	
	ConnectToDatabase();
	$query = "SELECT catid, cat, description
		FROM cfaq_cats
		WHERE maincat = 0
		ORDER BY cat";
	$catlist = run_my_query( $query, "Error pulling cat list from database");
	while ($cat = fetch_my_array( $catlist )) {
		$thiscat = stripslashes($cat[cat]);
		print "<dt><a href=\"$cfaqindex?catid=$cat[catid]\">$thiscat";
		if ($showcounts == 1) {
			$query = "SELECT DISTINCT qid 
				FROM cfaq_whichcats INNER JOIN cfaq_cats ON  cfaq_whichcats.catid = cfaq_cats.catid
				WHERE maincat = $cat[catid]
					OR cfaq_whichcats.catid = $cat[catid]";
			$qlist = run_my_query( $query, "Error pulling question counts from database");
			$qcount = get_num_rows( $qlist );
			print " ($qcount)";
		}
		print "</a></dt>";
		if ($usedescripts == 1) {
			$catdescription = stripslashes($cat[description]);
			print "<dd>$catdescription</dd>";
		}
	}
	
	print "</dl>";
	
	footerlinks();
	include ("footer.php");
}

?>