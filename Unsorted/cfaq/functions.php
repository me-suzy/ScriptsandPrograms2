<?php 
/* 
	CascadianFAQ v4.1 - Last Updated: November 2003
	Summer S. Wilson, Eclectic Designs, http://eclectic-designs.com
	cfaq@eclectic-designs.com
*/

	// To handle lack of register globals
	$PHP_SELF = $_SERVER["PHP_SELF"];
	$currentversion = "4.1"; // Do not edit this line!
	
	// convert dbtype to lower case for comparisions
	if (isset($dbtype))
		$dbtype = strtolower($dbtype);

	// Connects to database
	function ConnectToDatabase() {
		global $link, $dbtype, $dbhost, $dbusername, $dbpassword, $dbname;
		if ($dbtype == "mysql") {
			$link = mysql_connect( "$dbhost", "$dbusername" , "$dbpassword" ) or die( "Couldn't connect to MySQL database");
			mysql_select_db("$dbname", $link) or die("Couldn't open organizer" );
		}
		elseif ($dbtype == "postgresql") {
			$link = pg_connect( "host=$dbhost dbname=$dbname user=$dbusername password=$dbpassword" ) or die( "Couldn't connect to postgresqlSQL database.");
		} 
		else
			die ("Can't find dbtype: $dbtype");
		return $link;
	}
	
	function run_my_query($query, $dieerror) {
		global $dbtype, $link;
		switch ($dbtype){
			case "mysql":
				$result = mysql_query( $query, $link ) or die($dieerror."".mysql_error);
				break;

			case "postgresql": 
				$result = pg_query($link, $query) or die($dieerror.": ".pg_result_error());
				break;
			default:
				die ("Can't find dbtype: $dbtype");
		} 
		return $result;
	}
	
	function fetch_my_array($query) {
		global $dbtype, $link;
		switch ($dbtype){
			case "mysql":
				$result = mysql_fetch_array( $query );
				break;

			case "postgresql": 
				$result = pg_fetch_array($query);
				break;
		} 
		return $result;
	}
	
	function get_num_rows($query) {
		global $dbtype, $link;
		switch ($dbtype){
			case "mysql":
				$result = mysql_num_rows( $query );
				break;

			case "postgresql": 
				$result = pg_num_rows( $query);
				break;
		} 
		return $result;
	}
	
	// Creates and displays top menu on public side FAQ pages
	function whereareu($catid) {
		global $topmenu, $link, $qid, $cfaqindex, $submitq;
		$query = "Select cat, maincat FROM cfaq_cats WHERE catid = $catid";
		$currentcat = run_my_query( $query, "Error pulling cat information from database");
		$currentcat = fetch_my_array( $currentcat );
		
		$catlink =  "<p align=\"center\"><a href=\"$cfaqindex\">Home</a>";
		
		if ($currentcat[maincat] != 0)
			$catlink =  $catlink . buildcatlist($currentcat[maincat]);
		
		$thiscat = stripslashes($currentcat[cat]);
		if ((!isset($qid) || $qid == "") && !isset($submitq))
			$catlink = $catlink . " > $thiscat";
		else
			$catlink = $catlink . " > <a href=\"$cfaqindex?catid=$catid\">$thiscat</a>";
			
		if (isset ($submitq))
			$catlink = $catlink . " > Submit New Question";
		
		$catlink = $catlink . "</p>";
		
		print $catlink;
		return true;
		
	}
	
	function buildcatlist($catid) {
		global $link, $cfaqindex;

		$query = "Select cat, maincat FROM cfaq_cats WHERE catid = $catid";
		$getcatq = run_my_query( $query, "Error pulling main cat for $catid from database");
		$thiscatnum = get_num_rows( $getcatq );
		$thiscat = fetch_my_array( $getcatq );
		
		$catlink =  " > <a href=\"$cfaqindex?catid=$catid\">$thiscat[cat]</a>";
		
		// Recursive call to get remaining cat list
		if ($thiscat[maincat] != 0 and $thiscat[maincat] != "")
			$catlink = buildcatlist($thiscat[maincat]) . $catlink;
		
		return $catlink;
	}
	
	// Footer Menu for admin page
	function adminfooter() {
		include ("config.php");
		if ( session_is_registered ("addymin") ) {
			print "<p align=\"center\">[ <a href=\"$PHP_SELF?page=categories\">Manage Categories</a> | <a href=\"$PHP_SELF?page=questions\">Manage Questions</a>";
			
			if ($_SESSION['accesslevel'] == 1)
				print" | <a href=\"$PHP_SELF?page=users\">Manage Users</a>";

			if ($usersubmit == 1) {
				$questionquery = "Select submissionid FROM cfaq_submissions";
				$submittedq = run_my_query( $questionquery, "Error pulling submitted question count from database");
				$num_rows = get_num_rows( $submittedq );
				
				if ($num_rows > 0)
					print " | <a href=\"$PHP_SELF?page=submittedq\">User Submitted Questions</a>";
			}
	
			$questionquery = "Select qid FROM cfaq_qandas WHERE answer IS NULL or answer = ''";
			$unansweredq = run_my_query( $questionquery, "Error pulling submitted question count from database");
			$num_rows = get_num_rows( $unansweredq );
			
			if ($num_rows > 0)
				print " | <a href=\"$PHP_SELF?page=unansweredqs\">Unanswered Questions</a>";
			
			print "] </p><p align=\"center\">[ <a href=\"$PHP_SELF?\">Main Menu</a> | <a href=\"$cfaqindex\" target=\"_blank\">View FAQ</a> | <a href=\"$PHP_SELF?page=logout\">Logout</a> ]</p>";
		}
		return true;
	}
	
	// Subcats in option format for forms, recalls self to get all levels of cats
	function subcats_option($catid, $maincat, $dashes) {
		global $link, $editcatid;
		$username = $_SESSION['username'];
		if ($_SESSION['accesslevel'] == 0)
			$query = "Select cfaq_cats.catid, cat FROM cfaq_cats INNER JOIN cfaq_admintocats ON cfaq_cats.catid = cfaq_admintocats.catid WHERE maincat = $catid AND cfaq_admintocats.username = '$username' ORDER BY cat";
		else
			$query = "Select catid, cat, maincat FROM cfaq_cats WHERE maincat = $catid ORDER BY cat";

		$sublist = run_my_query( $query, "Error pulling subcategory list from database");
		$num_rows = get_num_rows( $sublist );
		
		if ($num_rows > 0) {
			while ($subcatlist = fetch_my_array( $sublist )) {
				if ($subcatlist[catid] == $maincat)
					print "<option value=\"$subcatlist[catid]\" selected>";
				else
					print "<option value=\"$subcatlist[catid]\">";
				
				$counter = 0;
				while ($counter <= $dashes) {
					print "-";
					$counter = $counter + 1;
				}
				$cat = stripslashes($subcatlist[cat]);
				print " $cat</option>";
				$numdashes = $dashes + 2;
	
				subcats_option($subcatlist[catid], $maincat, $numdashes);
			}
		}
		return true;
	}

	// Subcats in option format for updting question forms, recalls self to get all levels of cats
	function subcats_qoption($catid, $maincat, $dashes, $assignedlist) {
		global $link;
		$query = "Select catid, cat, maincat FROM cfaq_cats WHERE maincat = $catid ORDER BY cat";
		$sublist = run_my_query( $query, "Error pulling subcategory list from database");
		$num_rows = get_num_rows( $sublist );
		
		if ($num_rows > 0) {
			while ($subcatlist = fetch_my_array( $sublist )) {
				if (in_array( "$subcatlist[catid]", $assignedlist))
					print "<option value=\"$subcatlist[catid]\" selected>";
				else
					print "<option value=\"$subcatlist[catid]\">";
				
				$counter = 0;
				while ($counter <= $dashes) {
					print "-";
					$counter = $counter + 1;
				}
				$cat = stripslashes($subcatlist[cat]);
				print " $cat</option>";
				$numdashes = $dashes + 2;
	
				subcats_qoption($subcatlist[catid], $maincat, $numdashes, $assignedlist);
			}
		}
		return true;
	}

	// Subcats in nested list format for display, recalls self to get all levels of cats
	function subcats_list($maincatid) {
		global $link;
		$query = "Select catid, cat FROM cfaq_cats WHERE maincat = $maincatid ORDER BY cat";
		$sublist = run_my_query( $query, "Error pulling subcategory list from database");
		$num_rows = get_num_rows( $sublist );
		
		if ($num_rows > 0) {
			while ($subcatlist = fetch_my_array( $sublist )) {
				$cat = stripslashes($subcatlist[cat]);
				print "<li><a href=\"$PHP_SELF?page=categories&editcatid=$subcatlist[catid]\"> $cat</a></li>";
				print "<ul>";
				subcats_list("$subcatlist[catid]");
				print "</ul>";
			}
		}
		return true;
	}

	// Subcats in nested list format for question management area, recalls self to get all levels of cats
	function subcats_qlist($maincatid) {
		global $link;
		if ($_SESSION['accesslevel'] == 0)
			$query = "Select cfaq_cats.catid, cat FROM cfaq_cats INNER JOIN cfaq_admintocats ON cfaq_cats.catid = cfaq_admintocats.catid WHERE maincat = $maincatid AND cfaq_admintocats.username = '$_SESSION[username]'";
		else
			$query = "Select catid, cat, maincat FROM cfaq_cats WHERE maincat = $maincatid ORDER BY cat";

		$sublist = run_my_query( $query, "Error pulling subcategory list from database");
		$num_rows = get_num_rows( $sublist );
		
		if ($num_rows > 0) {
			while ($subcatlist = fetch_my_array( $sublist )) {
				$cat = stripslashes($subcatlist[cat]);
				print "<li><a href=\"$PHP_SELF?page=questions&catid=$subcatlist[catid]\"> $cat</a></li>";
				print "<ul>";
				subcats_qlist("$subcatlist[catid]");
				print "</ul>";
			}
		}
		return true;
	}


	// Adds Categories to the FAQ
	function addcat($cat, $description, $maincat) {
		global $link, $result;
		$description = addslashes ($description);
		ConnectToDatabase();
		$query = "INSERT INTO cfaq_cats(cat, description, maincat)
					VALUES ('$cat', '$description', '$maincat')";
		$addcat = run_my_query( $query, "Error adding category to the database");
		$result = "$cat has been added successfully";
		return $result;
	}
	
	// Updates categories in FAQ
	function updatecat($catid, $cat, $description, $maincat) {
		global $link, $result;	
		if ($catid != $maincat) {
			$description = addslashes ($description);
			ConnectToDatabase();
		
			$query = "UPDATE cfaq_cats
					  SET cat = '$cat', 
							description = '$description', 
							maincat = '$maincat'
					  WHERE catid = $catid";
			$update = run_my_query( $query, "Error updating category in the database");
			$result = "$cat has been updated successfully";
		}
		else {
			$result = "$cat was not updated because doing so would have made it a subcategory of itself";
		}
		return $result;
	}
	
	// Deletes category in FAQ
	function deletecat ($catid) {
		global $link, $result;
		ConnectToDatabase();

		if ($dbtype == "postgresql")
			run_my_query( "BEGIN", "Error starting transaction commit");

		$query = "Select cat FROM  cfaq_cats WHERE catid = $catid";
		$catnameq = run_my_query( $query, "Error retriving category name from database");
		$catname = fetch_my_array( $catnameq );

		$query = "DELETE FROM cfaq_cats
				  WHERE catid = $catid";
		$deletecat = run_my_query( $query, "Error deleting category from database");

		$query = "UPDATE cfaq_cats
					SET maincat = NULL
				  WHERE maincat = $catid";
		$fixsubs = run_my_query( $query, "Error updating category's subcats from database");

		$query = "DELETE FROM cfaq_whichcats
				  WHERE catid = $catid";
		$fixqs = run_my_query( $query, "Error updating category's subcats from database");
		
		if ($dbtype == "postgresql")
				run_my_query( "COMMIT", "Error starting transaction commit");
				
		$result = "$catname[cat] has been deleted successfully.  Any subcategories it had have been made main categories.";
		return $result;
	}

	// Adds questions to the FAQ
	function addquestion($question, $answer, $catlist) {
		global $link, $result, $dbtype;
		ConnectToDatabase();
		
		if ($question != "") {
			$question = addslashes ($question);
			$answer = addslashes ($answer);
			$today = date("Y-m-d");
			
			if ($dbtype == "postgresql")
				run_my_query( "BEGIN", "Error starting transaction commit");	
				
			$query = "INSERT INTO cfaq_qandas(question, answer, dateadded)
						VALUES ('$question', '$answer', '$today')";
			$addquestion = run_my_query( $query, "Error adding question to the database");
			
			if ($dbtype == "postgresql") {
				$query = "Select max(qid) as qid FROM cfaq_qandas";
				$getidq = run_my_query( $query, "Error pulling question id from the database");
				$getid = fetch_my_array($getidq);
				$qid = $getid[qid];
			}
			else
				$qid = mysql_insert_id();
		
			if ($catlist != "") {
				foreach ($catlist as $cat) {
					$query = "INSERT INTO cfaq_whichcats(catid, qid)
								VALUES ('$cat','$qid')";
					run_my_query( $query, "Error assigning question-category pairs");
				};
				$result = "$question has been added successfully";
			}
			else {
				$result = "$question has been added successfully, but it was not assigned to any categories";
			}
			
			if ($dbtype == "postgresql")
				run_my_query( "COMMIT", "Error committing transaction");
				
		}
		else {
			$result = "You can't add blank questions to the FAQ!";
		}
		return $result;
	}
	
	// Updates questions in FAQ
	function updatequestion($questionid, $question, $answer, $dateadded, $catlist) {
		global $link, $result;	
		$question = addslashes ($question);
		$answer = addslashes ($answer);
		if ($question != "") {
			ConnectToDatabase();
			if ($dbtype == "postgresql")
				run_my_query( "BEGIN", "Error starting transaction commit");

			$query = "UPDATE cfaq_qandas
					  SET question = '$question', 
							answer = '$answer',
							dateadded = '$dateadded'
					  WHERE qid = $questionid";
			$update = run_my_query( $query, "Error updating question in the database");
			
			$query = "DELETE FROM cfaq_whichcats WHERE qid = $questionid";
			run_my_query( $query, "Error deleting old question-category pairs from database");
			
			foreach ($catlist as $cat) {
				$query = "INSERT INTO cfaq_whichcats(catid, qid)
							VALUES ('$cat','$questionid')";
				run_my_query( $query, "Error assigning question-category pairs");
			};
			
			if ($dbtype == "postgresql")
				run_my_query( "COMMIT", "Error committing transaction");
			 
			$result = "$question has been updated successfully";
		}
		else {
			$result = "Question not updated as it was empty.";
		}
		return $result;
	}
	
	// Deletes questions in FAQ
	function deletequestion ($questionid) {
		global $link, $result;
		ConnectToDatabase();

		if ($dbtype == "postgresql")
			run_my_query( "BEGIN", "Error starting transaction commit");

		$query = "Select question FROM  cfaq_qandas WHERE qid = $questionid";
		$getquestioneq = run_my_query( $query, "Error retrieving question text from database");
		$getquestion = fetch_my_array( $getquestioneq );

		$query = "DELETE FROM cfaq_qandas
				  WHERE qid = $questionid";
		$deletequestion = run_my_query( $query, "Error deleting question from database");
		$query = "DELETE FROM cfaq_whichcats WHERE qid = $questionid";
		$fixsubs = run_my_query( $query, "Error deleting question from database");
		$result = "$getquestion[question] has been deleted successfully.";
		
		if ($dbtype == "postgresql")
			run_my_query( "COMMIT", "Error starting transaction commit");
		return $result;
	}
	
	// Adds user submitted question to the FAQ
	function addsubmittedquestion($submittedqid, $question, $answer, $categorylist, $datesubmitted) {
		global $link, $result, $dbtype;
		ConnectToDatabase();
		$question = addslashes ($question);
		$answer = addslashes ($answer);
		if ($question != "") {
			if ($dbtype == "postgresql")
				run_my_query( "BEGIN", "Error starting transaction commit");
			
			$query = "INSERT INTO cfaq_qandas(question, answer, dateadded)
						VALUES ('$question', '$answer', '$datesubmitted')";
			$addquestion = run_my_query( $query, "Error adding user submitted question to real question table");
			
			if ($dbtype == "postgresql") {
				$query = "Select max(qid) as qid FROM cfaq_qandas";
				$getidq = run_my_query( $query, "Error pulling question id from the database");
				$getid = fetch_my_array($getidq);
				$qid = $getid[qid];
			}
			else
				$qid = mysql_insert_id();
			
			// Assign cats to new question
			if ($categorylist != "") {
				foreach ($categorylist as $cat) {
					$query = "INSERT INTO cfaq_whichcats(catid, qid)
								VALUES ('$cat','$qid')";
					run_my_query( $query, "Error assigning question-category pairs");
				};
				$result = "$question has been added successfully";
			}
			else {
				$result = "$question has been added successfully, but it was not assigned to any categories";
			}
			
			if ($dbtype == "postgresql")
				run_my_query( "COMMIT", "Error committing transaction");
		}
		else {
			$result = "Can't add empty questions to the FAQ!";
		}

		// Delete question from user submission table
		$query = "DELETE FROM cfaq_submissions WHERE submissionid = $submittedqid";
		$deleteusersubmission = run_my_query( $query, "Error deleting user submitted question submission table");
		return $result;
	}
	
	// Deletes questions from user submission table
	function rejectsubmittedquestion ($submittedqid, $question) {
		global $link, $result;
		ConnectToDatabase();
		$query = "DELETE FROM cfaq_submissions
				  WHERE submissionid = $submittedqid";
		$deletequestion = run_my_query( $query, "Error deleting rejected question from database");

		$result = "$question has been rejected.";
		return $result;
	}
	
	// Adds user to the FAQ
	function adduser($newusername, $password, $confirmpassword, $firstname, $lastname, $useremail, $accesslevel, $categorylist) {
		global $link, $result;
		ConnectToDatabase();

		$checkerq = "Select username FROM cfaq_admin WHERE username = '$newusername'";
		$checker = run_my_query( $checkerq, $link ) or die("Error checking for duplicate usernames");
		$num_rows = get_num_rows( $checker );
		
		if ($num_rows != 0) {
			$result = "That User Name is already in use.  Please choose another!";
		}
		elseif ($password != $confirmpassword) {
			$result = "Passwords do not match!";
		}
		else {
			if ($dbtype == "postgresql")
				run_my_query( "BEGIN", "Error starting transaction commit");
			
			$firstname = addslashes($firstname);
			$lastname = addslashes($lastname);
			$email = addslashes($email);
			$query = "INSERT INTO cfaq_admin(username, password, firstname, lastname, email, accesslevel)
						VALUES('$newusername','$password','$firstname','$lastname','$useremail','$accesslevel')";
			$adduser = run_my_query( $query, "Error adding user to the database");
			
			if ($accesslevel == 0 & $categorylist != "") {
				foreach ($categorylist as $cat) {
					$query = "INSERT INTO cfaq_admintocats(catid, username)
								VALUES ('$cat','$newusername')";
					run_my_query( $query, "Error assigning admin-category pairs");
				};
			}
			
			if ($dbtype == "postgresql")
				run_my_query( "COMMIT", "Error committing transaction");
			
			$result = "$newusername has been added successfully";
		}
		return $result;
	}
	
	// Updates user in FAQ
	function updateuser($editusername, $newusername, $password, $confirmpassword, $firstname, $lastname, $useremail, $accesslevel, $categorylist) {
		global $link, $result;
		if ($password != "" AND $password != $confirmpassword) {
			$result = "Passwords do not match!";
		}
		else {
			$firstname = addslashes($firstname);
			$lastname = addslashes($lastname);
			$email = addslashes($email);	
			ConnectToDatabase();
			if ($dbtype == "postgresql")
				run_my_query( "BEGIN", "Error starting transaction commit");
	
			if ($password != "" and $confirmpassword != "") {
				$query = "UPDATE cfaq_admin
						  SET username = '$newusername', 
							password = '$password', 
							firstname = '$firstname', 
							lastname = '$lastname', 
							email = '$useremail',
							accesslevel = '$accesslevel'
						  WHERE username = '$editusername'";
			}
			else {
				$query = "UPDATE cfaq_admin
						  SET username = '$newusername', 
							firstname = '$firstname', 
							lastname = '$lastname', 
							email = '$useremail',
							accesslevel = '$accesslevel'
						  WHERE username = '$editusername'";
			}
			
			$updateuser = run_my_query( $query, "Error updating user in the database");
			
			$query = "DELETE FROM cfaq_admintocats WHERE username = '$editusername'";
			run_my_query( $query, "Error deleting old admin-category pairs from database");
			
			if ($accesslevel == 0 & $categorylist != "") {
				foreach ($categorylist as $cat) {
					$query = "INSERT INTO cfaq_admintocats(catid, username)
							VALUES ('$cat','$newusername')";
					run_my_query( $query, "Error assigning admin-category pairs");
				};
			}
			
			if ($dbtype == "postgresql")
				run_my_query( "COMMIT", "Error committing transaction");
					
			$result = "$newusername has been updated successfully";
		}
		return $result;
	}
	
	// Deletes user in FAQ
	function deleteuser ($newusername) {

		global $link, $result;
		ConnectToDatabase();
		$query = "DELETE FROM cfaq_admin
				  WHERE username = '$newusername'";
		$deleteuser = run_my_query( $query, "Error deleting user from database");
		$result = "$newusername has been deleted successfully.";
		return $result;
	}

	// Displays footer links
	function footerlinks () {
		global $catid, $faqname, $givecredit, $usersubmit, $cfaqindex, $isindex, $currentversion;
		if ($usersubmit == 0)
			print "<p align=\"center\">[ <a href=\"$cfaqindex?runsearch=start\">Search FAQ</a>";
		elseif ($catid != "" && $usersubmit = 1) 
			print "<p align=\"center\">[ <a href=\"$cfaqindex?runsearch=start\">Search FAQ</a> | <a href=\"$cfaqindex?submitq=yes&catid=$catid\">Submit Question to This Category</a>";
		elseif ($usersubmit = 1)
			print "<p align=\"center\">[ <a href=\"$cfaqindex?runsearch=start\">Search FAQ</a> | <a href=\"$cfaqindex?submitq=yes\">Submit New Question</a>";
		
		if ($isindex != "Yes")
			print " | <a href=\"$cfaqindex\">Home</a> ";

		print " ]</p>";

		if ($givecredit == 1)
			print "<p align=\"center\" style=\"font-size: 9px; font-family: Arial, Helvetica, san-serif;\">This FAQ is powered by <a href=\"http://eclectic-designs.com/cascadianfaq.php\" target=\"_blank\">CascadianFAQ v$currentversion</a>, developed by Summer S. Wilson at Eclectic Designs.</p>";

		return true;
	}

	// Processes search and displays results
	function processsearch ($searchterms,$limitcat) {
		global $link, $faqname, $cfaqindex;
		ConnectToDatabase();
		
		print "<p align=\"center\"><a href=\"$cfaqindex\">Home</a> > <a href=\"$cfaqindex?runsearch=start\">Search FAQ</a> > Search Results</p>";
		
		if ($limitcat == "")
			$query = "SELECT qid, question FROM cfaq_qandas WHERE question LIKE '%$searchterms%' OR answer LIKE '%$searchterms%' ORDER BY question;";
		else
			$query = "SELECT cfaq_qandas.qid, question FROM cfaq_qandas INNER JOIN cfaq_whichcats ON cfaq_qandas.qid = cfaq_whichcats.qid WHERE (question LIKE '%$searchterms%' OR answer LIKE  '%$searchterms%') AND cfaq_whichcats.catid = $limitcat ORDER BY question;";
		
		$searchq = run_my_query( $query, "Error retrieving search results from database");
		$num_rows = get_num_rows( $searchq );
		
		if ($num_rows > 0) {
			print "<ul>";
			while ($searchresults = fetch_my_array( $searchq )) {
				$question = stripslashes($searchresults[question]);
				$question = trim($question);
				$qid = $searchresults[qid];
				if ($limitcat == "")
					print "<li> &nbsp; <a href=\"$cfaqindex?qid=$qid\">$question</a></li>";
				else
					print "<li> &nbsp; <a href=\"$cfaqindex?qid=$qid&catid=$limitcat\">$question</a></li>";
			}
			print "</ul>";
		}
		else
			print "<p>No items found matching your query.  Please try again</p>";

		return true;
	}

	// Displays most recently added questions
	function getmostrecent() {
		global $link, $cfaqindex, $nummostrecent;
		ConnectToDatabase();
		
		$query = "SELECT qid, question, dateadded
			FROM cfaq_qandas
			ORDER BY dateadded DESC, qid ASC
			LIMIT $nummostrecent";
		$qlist = run_my_query( $query, "Error pulling most recent question list from database");
		$numrows = get_num_rows( $qlist );
		
		
		
		if ($numrows > 0) {
			if ($numrows < $nummostrecent)
				print "<p><strong>$numrows Most Recently Added Questions</strong></p>";
			else
				print "<p><strong>$nummostrecent Most Recently Added Questions</strong></p>";
			print "<ul>";
			while ($questions = fetch_my_array( $qlist )) {
				$question = stripslashes($questions[question]);
				$dateadded = $questions[dateadded];
				$dateaddedparts =  explode("-" ,$questions[dateadded]);
				$month = $dateaddedparts[1];
				$day = $dateaddedparts[2];
				$year = $dateaddedparts[0];
				print "<li> &nbsp; <a href=\"$cfaqindex?qid=$questions[qid]&frommostrecent=yes\">$question</a> (Added $month/$day/$year)</li>";
			}
			print "</ul>";
		}

		return true;
	}

	// Displays most popular questions
	function getmostpopular() {
		global $link, $cfaqindex, $nummostpopular, $anypopular;
		ConnectToDatabase();

		$query = "SELECT cfaq_qandas.qid, question, viewed
			FROM cfaq_qandas
			WHERE viewed > 0
			ORDER BY viewed DESC
			LIMIT $nummostpopular";
		$qlist = run_my_query( $query, "Error pulling most popular question list from database");
		$numrows = get_num_rows( $qlist );
		
		if ($numrows > 0) {
			if ($numrows < $nummostpopular)
				print "<p><strong>$numrows Most Popular Questions</strong></p>";
			else
				print "<p><strong>$nummostpopular Most Popular Questions</strong></p>";
				
			print "<ul>";
			while ($questions = fetch_my_array( $qlist )) {
				$question = stripslashes($questions[question]);
				if ($questions[viewed] == 1)
					$viewed = "1 time";
				else
					$viewed = "$questions[viewed] times";
				print "<li> &nbsp; <a href=\"$cfaqindex?qid=$questions[qid]&frommostpopular=yes\">$question</a> (Viewed $viewed)</li>";
			}
			print "</ul>";
			$anypopular = "yes";
		}
		else
			$anypopular = "no";
		return $anypopular;
	}

	// Adds questions to the FAQ
	function usersubmittedquestion($question, $submittername, $submitteremail, $submitterip, $catid) {
		global $link, $result, $adminemail, $emailonusersubmit, $faqname, $dbtype;
		ConnectToDatabase();
		$question = addslashes ($question);
		$today = date("Y-m-d");
		$query = "INSERT INTO cfaq_submissions(question, submittername, submitteremail, submitterip, suggestedcat, datesubmitted)
					VALUES ('$question', '$submittername', '$submitteremail', '$submitterip', '$catid', '$today')";
		$addquestion = run_my_query( $query, "Error adding submitted question to the database");

		if ($emailonusersubmit == 1) {
			$to = $adminemail;
			$emailsubject = "New Question Submitted To $faqname";
			$serverurl = $_SERVER["HTTP_HOST"];
			$scriptname = str_replace($_SERVER["PHP_SELF"], $cfaqindex, "admin.php");
			$message = "$submittername has submitted a new question to the faq.  The question asked is: $question.";
			$message .= "\n\nTo reject or accept this question, please login to the CascadianFAQ administrator at: http://$serverurl/cfaq/$scriptname.";
			$extra = "From: $adminemail\r\nReply-To: $adminemail\r\n";
			
			mail ( $to, $emailsubject, $message, $extra );
		}
		
		$result = "Your question has been successfully submitted.  Check back later to see if it was accepted by the FAQ Administrator.";
		return $result;
	}
?>