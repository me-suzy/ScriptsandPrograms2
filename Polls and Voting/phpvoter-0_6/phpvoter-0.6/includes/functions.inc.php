<?php
// Set this to the path of your script
$sitepath = "/path/to/phpvoter";

// Load the configuration file.
require_once("$sitepath/includes/config.inc.php");

// Use the standard database include file
require_once("{$config['sitepath']}/includes/mysql.inc.php");

// Load the templates
require_once("{$config['templatedir']}/{$config['templateset']}/main.tmpl.php");

// Include the language file
require_once("{$config['sitepath']}/languages/{$config['language']}.inc.php");

// Include the authentication script
require_once("{$config['datadir']}/{$config['auth_type']}_auth.inc.php");

// Array of the different states of a question
$config['arrstate'] = array($lang['inactive'], $lang['active'], $lang['unfinished'], $lang['deleted']);


/* *************************** */
/* A list of generic functions */
/* *************************** */

// Return a string containing the template with the given name.
// $tmplarr is an array that contains information to use in the template.
function getTemplate($tmpl, $tmplarr = array()) {
  GLOBAL $config, $lang, $template;
  eval("\$tmplfile = \$config['template']['$tmpl'];");
  if ($tmplfile) {
     include("{$config['templatedir']}/{$config['templateset']}/$tmplfile");
  } else {
     show_error($lang['error_notemplate']);
     die;
  }
  return $template_content;
}

// Read the contents of a file and return it as a string.
function getfile($filename) {
	if (file_exists($filename)) {
		$fd = fopen($filename, "r");
		$contents = fread($fd, filesize($filename));
		fclose($fd);
		return $contents;
	}
}

// Check if a date is valid.
function oj_checkdate($dtm) {
        list($year, $month, $day) = split("-", $dtm);
        $ret = checkdate($month, $day, $year);
        return $ret;
}

// Prints the HTML header. Replaces the string <!--vote--> in the header with the vote if the user hasn't answered the question yet.
function showheader() {
	GLOBAL $template, $lang, $config;
	if (!hasalreadyvoted()) {
		$replacestring = $lang['activequestion'] . letsvote("active");
		$information = "";
	} else {
		$replacestring = $lang['alreadyvoted'];
		$linkvote = linktoactivevote();
		$replacestring .= " <a href=\"{$linkvote}\">{$lang['viewresults']}</a>\n";
		$information = $template['info'];
	}
	$tmplarr = array();
	$tmplarr['vote'] = $replacestring;
	$header = getTemplate("header", $tmplarr);
	print $header . $information;	
}

// Add the result to the database
function saveresult($vote, $phpvoter, $unique = "", $allow = "") {
        GLOBAL $template, $tables, $lang, $REMOTE_ADDR, $config;
	$activevote = getactivevote();
	$unique = (($unique == "yes") || ($unique == 1)) ? true : false;
	// IP must be sent unless unique option is turned off
	if (($REMOTE_ADDR && $unique) || !$unique) {
		// Check if the vote is active.
		if ($activevote == $vote || $allow == "yes" ) {
			// First lets find out which answer the vote was for.
			$sql_query = "SELECT ID, intAnswers FROM {$tables['answer']} WHERE question_id = $vote AND ID = {$phpvoter} ORDER BY ID";
			if ($config['debug']) print "$sql_query<br />";
			$result = MYSQL_QUERY($sql_query);
			$err = mysql_error();
			if ($err) {
				$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
			}
			$number = MYSQL_NUM_ROWS($result);

			if ($number != 0) {
				$answer_id = mysql_result($result, 0, "ID");
				$answers = mysql_result($result, 0, "intAnswers");
			}

			// Let's add a vote to the choice, but only if it's active.
			$sql_query = "UPDATE {$tables['answer']} SET intAnswers = intAnswers + 1 WHERE ID = {$answer_id}";
			if ($config['debug']) print "$sql_query<br />";
			$result = MYSQL_QUERY($sql_query);
			$err = mysql_error();
			if ($err){
				$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
			} else {
				// Seems like it worked out OK.
				if ($unique) {
					savevoteinformation($vote, $answer_id);
				}
				showheader();
				print ($lang['result_saved']);
				showvote($vote);
			}
		} else {
			showheader();
			print($lang['notactive']);
		}
	} else {
		// If the browser hasn't sent an IP, bugger off!
		showheader();
		print ($lang['noip']);
	}
}

// Save some information so that it's not possible to vote twice.
function savevoteinformation($vote, $answer = "") {
	GLOBAL $tables, $config, $REMOTE_ADDR;
	// First we'll add a cookie saying that a vote has been done.
	// I've chosen to keep the cookie for 30 days, it should be adequate.
	setcookie("PHPVoter-{$config['sitekey']}-{$vote}", $vote, time() + 2592000);
	
	// For a little extra safety, let's also save the IP the vote was made from.
	if ($REMOTE_ADDR) {
		// Check if answer should be saved as well.
		if (($config['saveanswer'] == "yes") || ($config['saveanswer'] == 1)) {
			$answerstring = ", intAnswer";
			$answervalue = ", " . $answer;
		} else {
			$answerstring = "";
			$answervalue = "";
		}
		$sql_query = "INSERT INTO {$tables['voted']} (question_id, varIP{$answerstring}) VALUES ({$vote}, '{$REMOTE_ADDR}'{$answervalue})";
		if ($config['debug']) print "$sql_query<br />";
		$result = MYSQL_QUERY($sql_query);
		$err = mysql_error();
		if ($err){
			$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
		}
	}
}

// Get the id for the active vote
function getactivevote() {
	GLOBAL $tables, $config, $lang;
	// Let's get the active vote
	// If several active votes are allowed, get the newest.
	$sql_query = "SELECT ID FROM {$tables['question']} WHERE intState = 1 ORDER BY dteDate DESC";
	if ($config['debug']) print "$sql_query<br />";
	#print "$sql_query<br />";
	$result = MYSQL_QUERY($sql_query);
	$err = mysql_error();
	if($err){
		$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
	} else {
		$number = MYSQL_NUM_ROWS($result);
	
		if ($number != 0) {
			$vote = mysql_result($result, 0, "ID");
		}
	}
	
	if (!$vote) {
		$vote = -1;
	}

	return $vote;
}

// Show the results for the active vote
function showvote($vote = "active", $statelimit = 1) {
	GLOBAL $template, $tables, $lang, $config;
	if ($vote == "active") {
		$vote = getactivevote();
	}
	
	if ($vote < 0) {
		return $lang['no_active_vote'];
	}

	// Now, let's print out the results to the vote
	list($vote, $question, $date, $state, $comment, $publish) = getQuestion($vote);

	if (($vote < 0) || ($state > $statelimit)) {
		show_error($lang['vote_not_found']);
	} else {
		// Get the choices of the question.
		list($choice_id, $choice, $answers, $total) = getAnswers($vote);
		$number = count($choice);

		for ($i = 0; $i < $number; $i++) {
			$width = round(($answers[$i] / (1 + $total)) * $config['width']);
			if ($width < $config['minwidth']) {
				$width = $config['minwidth'];
			}
			if ($total > 0 && $answers[$i] > 0) {
				$nrofvoters = ($answers[$i] == 1) ? $lang['onevote'] : $lang['votes'];
				$percent = round(($answers[$i] / $total) * 100);
				$strAnswer_text = $percent . "%<br />[" . $answers[$i] . " $nrofvoters]";
				$strVotes = "[" . $answers[$i] . " $nrofvoters - " . $percent . "%]";
			} else {
                                $percent = "0";
				$strAnswer_text = "0%<br />[{$lang['no_votes']}]";
				$strVotes = "[{$lang['no_votes']} - 0%]";
			}
			$Vote[$i]['color'] = $template['color'][$i];
			$Vote[$i]['width'] = $width;
			$Vote[$i]['answer'] = $strAnswer_text;
			$Vote[$i]['voters'] = $answers[$i];
			$Vote[$i]['voters_percent'] = $percent;
			$Vote[$i]['votes'] = $strVotes;
			$Vote[$i]['nrofvoters'] = $nrofvoters;
			$Vote[$i]['choice'] = $choice[$i];
		}
		$Vote['comment'] = $comment;
		$Vote['date'] = $date;
		$Vote['question'] = $question;
		$Vote['total'] = $total;
		$Vote['number'] = $number;
		$Vote['state'] = $config['arrstate'][$state];

		// Include the template file
		print getTemplate('showvote', $Vote);
	}
	return $state;
}

// Show the results for the active vote
function old_showvote($vote = "active", $statelimit = 1) {
	GLOBAL $template, $tables, $lang, $config;
	if ($vote == "active") {
		$vote = getactivevote();
	}
	
	if ($vote < 0) {
		return $lang['no_active_vote'];
	}

	// Now, let's print out the results to the vote
	list($vote, $question, $date, $state, $comment, $publish) = getQuestion($vote);

	if (($vote < 0) || ($state > $statelimit)) {
		show_error($lang['vote_not_found']);
	} else {
		// Get the choices of the question.
		list($choice_id, $choice, $answers, $total) = getAnswers($vote);
		$number = count($choice);

		print $template['pre_question_header'] . $date . " - " . $question . $template['post_question_header'] . "\n";
		print $template['pre_question_state'];
		print $lang['question_state'];
		print $config['arrstate'][$state];
		print $template['post_question_state'];
		print $template['pre_question_comment'] . $lang['question_comment'] . $comment . $template['post_question_comment'] . "\n";
		print $template['pre_result'] . "\n";
		for ($i = 0; $i < $number; $i++) {
			#print $template['pre_choice'] . $choice[$i] . $template['post_choice'] . "\n";
			print ($template['pre_answer']);
			$width = round(($answers[$i] / (1 + $total)) * $config['width']);
			if ($width < $config['minwidth']) {
				$width = $config['minwidth'];
			}
			if ($total > 0 && $answers[$i] > 0) {
				$nrofvoters = ($answers[$i] == 1) ? $lang['onevote'] : $lang['votes'];
				$strAnswer_text = round(($answers[$i] / $total) * 100) . "%<br />[" . $answers[$i] . " $nrofvoters]";
				$arrAnswer_text[$i] = "[" . $answers[$i] . " $nrofvoters - " . round(($answers[$i] / $total) * 100) . "%]";
			} else {
				$strAnswer_text = "0%<br />[{$lang['no_votes']}]";
				$arrAnswer_text[$i] = "[{$lang['no_votes']} - 0%]";
			}
			$printtext = str_replace("<!--width-->", $width, $template['middle_answer']);
			$printtext = str_replace("<!--color-->", $template['color'][$i], $printtext);
			$printtext = str_replace("<!--answer-->", $strAnswer_text, $printtext);
			print $printtext;
			print $template['post_answer'];
		}
		print $template['middle_result'];
		for ($i = 0; $i < $number; $i++) {
			$choice_text = str_replace("<!--color-->", $template['color'][$i], $template['pre_choice']);
			$rowcolor = $i % 2 ? " bgcolor=\"{$template['bgcolor']}\"" : "";
			$choice_text = str_replace("<!--alternaterowcolor-->", $rowcolor, $choice_text);
			print $choice_text;
			print $choice[$i];
			print $template['middle_choice'] . $arrAnswer_text[$i] . $template['post_choice'];
		}
		# Print the total number of voters.
		print $template['fontstring'] . $lang['totalvoters'] . $total . "</FONT>\n";
		# Print the post-result html code
		print $template['post_result'];
	}
}

// Snow a question
// $vote = ID of vote to select, "active" or empty to get the current active vote
// $posturl = The URL to be used in the form action attribute.
// $showcomment = Non empty if the questions comment should be shown.
function letsvote($vote = "active", $posturl = "", $showcomment = "") {
        GLOBAL $template, $tables, $lang, $config;
	if ($vote == "active") {
		$vote = getactivevote();
	}
	if (!$posturl || $posturl == "") {
		$posturl = $config['scriptname'];
	}

	if ($vote < 0) {
		return $lang['no_active_vote'];
	}
	
	// Read information about the question from the db
	list($vote, $question, $date, $state, $comment, $publish) = getQuestion($vote);
	if ($vote < 0) {
		show_error($lang['vote_not_found'], "no");
		$printstring = "";
	} else {
		// Get all answers from the db
		list($choice_id, $choice, $answers, $totalanswers) = getAnswers($vote);
		$number = count($choice);

		// Now, let's print out the question and the possible choices
		$tmplarr = array();
		$tmplarr['date'] = $date;
		$tmplarr['question'] = $question;
		$tmplarr['vote'] = $vote;
		$tmplarr['state'] = $state;
		$tmplarr['publish'] = $publish;
		$tmplarr['number'] = $number;
		$tmplarr['posturl'] = $posturl;
		if ($showcomment != "") {
			$tmplarr['comment'] .= $template['pre_question_comment'] . $comment . $template['post_question_comment'];
		}
		for ($i = 0; $i < $number; $i++) {
   		        $tmplarr[$i]['choice_id'] = $choice_id[$i];
			$tmplarr[$i]['choice'] = $choice[$i];
		}
		$printstring = getTemplate("letsvote", $tmplarr);
	}	
	return $printstring;
}

// Get the id of the newest question
function getNewestQuestion() {
	GLOBAL $tables, $lang, $config;
	$sql_query = "SELECT ID FROM {$tables['question']} ORDER BY ID DESC LIMIT 1";
	if ($config['debug']) print "$sql_query<br />";
	$result = MYSQL_QUERY($sql_query);
	$err = mysql_error();
	if ($err) {
		$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
	} else {
		$number = MYSQL_NUM_ROWS($result);
		if ($number) {
			$voteid = MYSQL_RESULT($result, 0, "ID");
		} else {
			$information = "<br />" . $lang['no_votes_in_db'];
		}
	}
	return array($voteid, $information);
}

// Get information about a question
function getQuestion($voteid) {
	GLOBAL $tables, $config, $lang;
	// Let's get the information about the vote from the database
	$sql_query = "SELECT ID, varQuestion, DATE_FORMAT(dteDate, '%Y-%m-%d') AS dteDate, intState, varComment, dtePublish FROM {$tables['question']} WHERE ID = {$voteid}";
	if ($config['debug']) print "$sql_query<br />";
	$result = MYSQL_QUERY($sql_query);
	$err = mysql_error();
	if ($err){
		$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
	} else {
		$number = MYSQL_NUM_ROWS($result);
	
		if ($number != 0) {
			$vote = mysql_result($result, 0, "ID");
			$question = mysql_result($result, 0, "varQuestion");
			$date = mysql_result($result, 0, "dteDate");
			$state = mysql_result($result, 0, "intState");
			$comment = mysql_result($result, 0, "varComment");
			$publish = mysql_result($result, 0, "dtePublish");
		} else {
			$vote = -1;
			$question = ""; 
			$date = "";
			$state = "";
			$comment = "";
			$publish = "";
		}
	}
	return array($vote, $question, $date, $state, $comment, $publish);
}

// Get all answers to a vote
function getAnswers($voteid) {
	GLOBAL $tables, $config, $lang;
	$totalanswers = 0;
	if ($config['debug']) print "$sql_query<br />";
	$sql_query = "SELECT * FROM {$tables['answer']} WHERE question_id = {$voteid} ORDER BY ID";
	if ($config['debug']) print "$sql_query<br />";
	$result = MYSQL_QUERY($sql_query);
	$err = mysql_error();
	if ($err) {
		$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
	}
	$number = MYSQL_NUM_ROWS($result);
	
	for ($i = 0; $i < $number; $i++) {
		$choice_id[$i] = mysql_result($result, $i, "ID");
		$choice[$i] = mysql_result($result, $i, "varChoice");
		$answers[$i] = mysql_result($result, $i, "intAnswers");
		$totalanswers += $answers[$i];
	}

	return array($choice_id, $choice, $answers, $totalanswers);
}

// Get the list of all who has voted for a question
function getVoters($voteid) {
	GLOBAL $tables, $config, $lang;
	// Let's get the information about the voters from the database
	#$sql_query = "SELECT {$tables['voted']}.ID, varIP, varChoice FROM {$tables['voted']}, {$tables['answer']} WHERE {$tables['voted']}.intAnswer = {$tables['answer']}.ID AND {$tables['voted']}.question_ID = {$voteid}";
	$sql_query = "SELECT ID, varIP, intAnswer FROM {$tables['voted']} WHERE question_ID = {$voteid}";
	if ($config['debug']) print "$sql_query<br />";
	$result = MYSQL_QUERY($sql_query);
	$err = mysql_error();
	if ($err){
		$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
	} else {
		$number = MYSQL_NUM_ROWS($result);
	
		for ($i = 0; $i < $number; $i++) {
			$voter_id[$i] = mysql_result($result, $i, "ID");
			$voter_IP[$i] = mysql_result($result, $i, "varIP");
			#$voter_answer[$i] = mysql_result($result, $i, "varChoice");
			$voter_answer[$i] = mysql_result($result, $i, "intAnswer");
		}
	}
	return array($voter_id, $voter_IP, $voter_answer);
}

// Let's check if the person has voted already.
function hasalreadyvoted($vote = "active") {
	GLOBAL $tables, $config, $lang, $REMOTE_ADDR, $HTTP_COOKIE_VARS;
	if ($vote == "active") {
		$vote = getactivevote();
	}

	if ($vote == -1) {
		return true;
	}
	
	// Ok, so first we'll check if a cookie is set.
	$cookie = $HTTP_COOKIE_VARS["PHPVoter-{$config['sitekey']}-{$vote}"];
	if ($cookie == $vote) {
		$ret = true;
	} else {
		$ret = false;
	}
	
	// But we can't be sure just yet, let's try if the IP has voted as well.
	if ($REMOTE_ADDR) {
		$sql_query = "SELECT ID FROM {$tables['voted']} WHERE question_id = $vote AND varIP = '{$REMOTE_ADDR}'";
		if ($config['debug']) print "$sql_query<br />";
		#print "$sql_query<br />";
		$result = MYSQL_QUERY($sql_query);
		$err = mysql_error();
		if ($err){
			$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
		}
		$number = MYSQL_NUM_ROWS($result);
		
		if ($number || $ret) {
			$ret = true;
		} else {
			$ret = false;
		}
	} else {
		// Bugger off! You don't have the REMOTE_ADDR set.
		$ret = false;
	}
	return $ret;
}

function linktoactivevote() {
	GLOBAL $config;
	$voteid = getactivevote();
	if ($voteid >= 0) {
		$link = "{$config['votescript']}?action=show&voteid={$voteid}";
	} else {
		$link = "";
	}
	return $link;
}

function show_error($error, $header = "yes") {
	GLOBAL $template, $lang;
	if ($header == "yes") {
	   print getTemplate('header');
	}
	print $template['pre_error_header'] . $lang['error_header'] . $template['post_error_header'] . "\n";
	print $template['pre_error_string'] . $error . $template['post_error_string'] . "\n";
	print getTemplate('footer');
}

// This function makes a list of all active and inactive votes.
function listvotes($listtype = "public", $orderby = "ID DESC") {
	GLOBAL $template, $tables, $config, $lang;
	#print($html_header);

	$printstring = "";
	$wherestring = "";

	if ($listtype == "public") {
		$wherestring = "WHERE intState < 2";
	} else if ($listtype == "notdeleted") {
		$wherestring = "WHERE intState < 3";
	} else if ($listtype == "all") {
		$wherestring = "";
	} else if (($listtype >= 0) && ($listtype <= 3)) {
		$wherestring = "WHERE intState = {$listtype}";
	}
        $sql_query = "SELECT ID, varQuestion, DATE_FORMAT(dteDate, '%Y-%m-%d') AS dteDate, intState FROM {$tables['question']} {$wherestring} ORDER BY {$orderby}";
	if ($config['debug']) print "$sql_query<br />";
	#print "sql_query: $sql_query<br />";
        $result = MYSQL_QUERY($sql_query);
        $err = mysql_error();
        if ($err){
                $strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
        } else {
	        $number = MYSQL_NUM_ROWS($result);

		if ($number == 0) {
			show_error($lang['no_votes_in_db'], "no");
		} else {
	        	for ($i = 0; $i < $number; $i++) {
				$vote = mysql_result($result, $i, "ID");
				$question = mysql_result($result, $i, "varQuestion");
				$date = mysql_result($result, $i, "dteDate");
				$state = mysql_result($result, $i, "intState");
				if ($listtype == "public") {
					// This is for the public list
					$printstring .= ($i+1) . ". <a href=\"{$config['scriptname']}?action=show&amp;voteid={$vote}\">{$question}</a><br />\n";
				} else {
					// This is for the list on the admin page
					list($voters, $answers) = getAnswerinfo($vote);
					$printstring .= $template['pre_admin_listrow'];
					$printstring .= listitem($vote);
					$printstring .= listitem($question);
					$printstring .= listitem($date);
					$printstring .= listitem(getState($state));
					$printstring .= listitem($answers);
					$printstring .= listitem($voters);
					$printstring .= listitem("<a href=\"{$config['scriptname']}?action=showquestion&amp;voteid={$vote}\">{$lang['showvote']}</a>");
					$printstring .= $template['post_admin_listrow'];
				}
	        	}
		}
	}
	return $printstring;
}

// Create a list item
function listitem($item) {
	GLOBAL $template;
	return $template['pre_admin_listitem'] . $item . $template['post_admin_listitem'];
}

// Return state info
function getState($state) {
	GLOBAL $lang, $config;
	return $config['arrstate'][$state];
}

// Get the number of voters and answers of a question
function getAnswerinfo($question) {
	GLOBAL $tables, $config, $lang;

	$voters = 0;
	$sql_query = "SELECT intAnswers FROM {$tables['answer']} WHERE question_id = {$question} ORDER BY ID";
	if ($config['debug']) print "$sql_query<br />";
	$result = MYSQL_QUERY($sql_query);
	$err = mysql_error();
	if ($err){
		$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
	} else {
		$number = MYSQL_NUM_ROWS($result);
	
		for ($i = 0; $i < $number; $i++) {
			$answers = mysql_result($result, $i, "intAnswers");
			$voters += $answers;
		}
	}
	return array($voters, $number);
}

/**
 * Logs the given information
 *
 * Logs the given information to a logfile and makes sure
 * the files aren't too big.
 *
 * @param string $Operation Title of the operation
 * @param string $Description A short description of what was done
 * @param string $User The user who did the operation
 * @access public
 * @global array $config The configuration options.
 * @global array $lang The language strings
 * @author Olle Johansson <Olle@Johansson.com>
 */
function adminLog($Operation, $Description, $User)
{
  global $config, $lang;
  
  $time = date("Y/m/d:H:i:s O");
  $IP = getenv("REMOTE_ADDR");
  $User_esc = addcslashes($User, ',');
  $Description = nl2br($Description);
  $Description_esc = addcslashes($Description, ',');
  if (file_exists($config['adminlog_filename'])) {
    $logsize = filesize($config['adminlog_filename']);
  } else {
    $logsize = 0;
  }
  $keepfiles = $config['adminlog_keepfiles'];
  if (!is_numeric($keepfiles)) {
    print "{$lang['error_adminkeepnotint']}<br />";
    $keepfiles = 4;
  }
  if ($logsize > $config['adminlog_maxsize']) {
    for ($i = $keepfiles; $i > 0; $i--) {
      if ($i == 1) {
	$oldfile = $config['adminlog_filename'];
      } else {
	$oldfile = $config['adminlog_filename'] . "." . ($i-1);
      }
      $newfile = $config['adminlog_filename'] . "." . $i;
      if (file_exists($oldfile)) {
	rename($oldfile, $newfile);
      }
    }
  }
  error_log("$time,$IP,$Operation,$User_esc,$Description_esc\n",
	    3,
	    $config['adminlog_filename']
	    );
}


?>
