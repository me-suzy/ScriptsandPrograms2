<?php
// We need some common functions
require_once("includes/functions.inc.php");

$user = $HTTP_GET_VARS['user'];
$pass = $HTTP_GET_VARS['pass'];
$action = $HTTP_GET_VARS['action'];
if (!$action || $action == "") {
	$action = $HTTP_POST_VARS['action'];
}
$voteid = $HTTP_GET_VARS['voteid'];
if (!$voteid || $voteid == "") {
	$voteid = $HTTP_POST_VARS['voteid'];
}

// Get the user information
list($user, $pass) = getUser();

if (!authenticate($user, $pass)) {
	print getTemplate("header");
	show_error($lang['error_access_denied'], "no");
	print getTemplate("footer");
} else {
	$template['html_footer'] = "<br /><br /><a href=\"{$config['scriptname']}\">{$lang['admin_return']}</a><br />" . getTemplate("footer");

	if ($action == "admin_list") {
		adminlistvotes("all");
	} else if ($action == "admin_create") {
		changequestion("new");
	} else if ($action == "changequestion") {
		changequestion($voteid);
	} else if ($action == "savequestion") {
		list($voteid, $information) = savequestion();
		changequestion($voteid, $information);
	} else if ($action == "deletechoice") {
		list($voteid, $information) = savequestion();
		$cid = $HTTP_GET_VARS['cid'];
		$information .= "<br />" . deletechoice($cid);
		changequestion($voteid, $information);
	} else if ($action == "showquestion") {
		showquestion($voteid);
	} else if ($action == "setactive") {
		setactive($voteid);
	} else if ($action == "deletequestion") {
		deletequestion($voteid);
	} else if ($action == "letsvote") {
		voteonquestion($voteid);
	} else if ($action == "vote") {
		$phpvoter = $HTTP_POST_VARS['phpvoter'];
		saveresult($voteid, $phpvoter, "no", "yes");
		showmenu();
	} else if ($action == "deleteip") {
		$ipid = $HTTP_GET_VARS['ipid'];
		deleteip($ipid);
	} else {
		showmenu();
	}
}

if ($config['debug']) {
	print $strError;
}

// Show a menu of the options
function showmenu() {
	GLOBAL $template, $lang;
	print getTemplate("header");
	adminheader($lang['admin_menu']);
	print $template['pre_admin_menu'];
	print menuitem("admin_list");
	print menuitem("admin_create");
	print $template['post_admin_menu'];
	print $template['html_footer'];
}

// Create a menu item
function menuitem ($item, $voteid = "", $title = "") {
	GLOBAL $template, $config, $lang;
	$string = $template['pre_admin_menuitem'];
	$votestr = ($voteid != "") ? "&amp;voteid={$voteid}" : "";
	$string .= "<a href=\"{$config['scriptname']}?action={$item}{$votestr}\">";
	if ($title == "") {
		eval("\$title = \$lang['$item'];");
	}
	if ($title == "") {
		$title = $item;
	}
	$string .= $title . "</a>" . $template['post_admin_menuitem'] . "\n";
	return $string;
}

// Show a header
function adminheader($string) {
	GLOBAL $template;
	print $template['pre_admin_header'] . $string . $template['post_admin_header'];
}

// Show a list of all questions.
function adminlistvotes($type = "all") {
	GLOBAL $template, $lang;
	print getTemplate("header");
	adminheader($lang['admin_list_header']);
	$votelist = listvotes($type, "ID ASC");
	if ($votelist != "") {
		print $template['pre_admin_list'];
		print $votelist;
		print $template['post_admin_list'];
		print $template['html_footer'];
	#} else {
	#	show_error($lang['no_votes_in_db']);
	}
}

// Show all information of a question and links to functions
function showquestion($voteid) {
	GLOBAL $template, $config, $lang;
	adminheader($lang['admin_show_vote']);
	// First we'll show the question with results.
	$state = showvote($voteid, 3);
	// Now let's show the list of all who has voted on this question.
	adminheader($lang['admin_voter_list']);
	showvoters($voteid);
	adminheader($lang['admin_menu']);
	print $template['pre_admin_menu'];
	if ($state == 0 || $state == 2) {
	   print menuitem("setactive", $voteid, $lang['admin_set_this_active']);
	}
	print menuitem("deletequestion", $voteid, $lang['admin_delete_question']);
	print menuitem("changequestion", $voteid, $lang['admin_change_question']);
	print menuitem("letsvote", $voteid, $lang['admin_vote_question']);
	print menuitem("admin_list", $voteid, $lang['admin_list']);
	print $template['post_admin_menu'];
	print $template['html_footer'];
}

// List all voters
function showvoters($voteid) {
	GLOBAL $template, $lang, $config;
	list($voter_id, $voter_IP, $voter_answer) = getVoters($voteid);
	$number = count($voter_id);
	$str = "";
	for ($i = 0; $i < $number; $i++) {
		$str .= $template['pre_voter_listrow'];
		$str .= $template['pre_voter_listitem'];
		$str .= $voter_id[$i];
		$str .= $template['post_voter_listitem'];
		$str .= $template['pre_voter_listitem'];
		$str .= $voter_IP[$i];
		$str .= $template['post_voter_listitem'];
		$str .= $template['pre_voter_listitem'];
		$str .= $voter_answer[$i];
		$str .= $template['post_voter_listitem'];
		$str .= $template['pre_voter_listitem'];
		$str .= "<a href=\"{$config['scriptname']}?action=deleteip&amp;ipid={$voter_id[$i]}\">{$lang['delete']}</a>";
		$str .= $template['post_voter_listitem'];
		$str .= $template['post_voter_listrow'];
	}
	if ($str != "") {
		print $template['pre_voter_list'];
		print $str;
		print $template['post_voter_list'];
	} else {
		print $lang['no_voters'];
	}
}

// Show a form to vote on a question
function voteonquestion($voteid) {
	GLOBAL $template, $lang;
	print getTemplate("header");
	adminheader($lang['vote_on_question']);
	print letsvote($voteid, "", "showcomment");
	print $template['html_footer'];
}

// Delete a vote
function deleteip($voter_id) {
	GLOBAL $config, $tables, $template, $lang, $user;
	print getTemplate("header");
	// If answer is saved, also change the number of votes
	if (($config['saveanswer'] == "yes") || $config['saveanswer'] == 1) {
		$sql_query = "SELECT ID, varIP, intAnswer FROM {$tables['voted']} WHERE ID = {$voter_id}";
		if ($config['debug']) print "$sql_query<br />";
		#print "sql_query: $sql_query<br />";
		$result = MYSQL_QUERY($sql_query);
		$err = mysql_error();
		if ($err){
			$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
		} else {
			$number = MYSQL_NUM_ROWS($result);
		
			if ($number != 0) {
				$voter_id = mysql_result($result, 0, "ID");
				$voter_IP = mysql_result($result, 0, "varIP");
				$voter_answer = mysql_result($result, 0, "intAnswer");
			}
		}
		if ($voter_answer) {
			$sql_query = "UPDATE {$tables['answer']} SET intAnswers = intAnswers - 1 WHERE ID = {$voter_answer}";
			if ($config['debug']) print "$sql_query<br />";
			#print "sql_query: $sql_query<br />";
			$result = MYSQL_QUERY($sql_query);
			$err = mysql_error();
			if ($err){
				$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
			} else {
				$information = $lang['votes_updated'];
			}			
		}
	} else {
		$information = $lang['votes_not_updated'];
	}
	$sql_query = "DELETE FROM {$tables['voted']} WHERE ID = {$voter_id}";
	if ($config['debug']) print "$sql_query<br />";
	#print "sql_query: $sql_query<br />";
	$result = MYSQL_QUERY($sql_query);
	$err = mysql_error();
	if ($err) {
		$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
	} else {
		adminheader($lang['identifier_deleted']);
		print $information;
		adminLog("DELETEIP", "ID: $voter_id, IP: $voter_IP, Answer: $voter_answer", $user);
	}
	print $template['html_footer'];
}

// Delete a vote
function deletequestion($question_id) {
	GLOBAL $config, $tables, $template, $lang, $user;
	$information = "";
	print getTemplate("header");
	$sql_query = "UPDATE {$tables['question']} SET intState = 3 WHERE ID = {$question_id}";
	if ($config['debug']) print "$sql_query<br />";
	$result = MYSQL_QUERY($sql_query);
	$err = mysql_error();
	if ($err) {
		$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
	}
	$sql_query = "DELETE FROM {$tables['voted']} WHERE question_ID = {$question_id}";
	if ($config['debug']) print "$sql_query<br />";
	$result = MYSQL_QUERY($sql_query);
	$err = mysql_error();
	if ($err) {
		$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
	} else {
		$information = $lang['voters_deleted'];
	}
	adminLog("DELETEQUESTION", "ID: $question_id", $user);
	adminheader($lang['question_deleted']);
	print $information;
	print $template['html_footer'];
}

// Set the given question to be the active question.
function setactive($voteid) {
	GLOBAL $template, $tables, $config, $lang, $user;
	$sql_query = "UPDATE {$tables['question']} SET intState = 1 WHERE ID = {$voteid}";
	if ($config['debug']) print "$sql_query<br />";
	$result = MYSQL_QUERY($sql_query);
	$err = mysql_error();
	if ($err){
		$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
	} else {
		$information = $lang['vote_activated'];

		// If only one vote can be active, remove all other active votes.
		if (($config['singlevote'] == "yes") || ($config['singlevote'] == 1)) {
			$information = "<br />" . removeactive($voteid);
		}
	}
	adminLog("SETACTIVE", "Vote ID: $voteid", $user);
	print getTemplate("header");
	adminheader($lang['set_active_vote']);
	print $information;
	print $template['html_footer'];
}

// Make all active questions except the one supplied inactive.
function removeactive($voteid) {
	GLOBAL $tables, $lang, $user, $config;
	$sql_query = "UPDATE {$tables['question']} SET intState = 0 WHERE intState = 1 AND ID <> {$voteid}";
	if ($config['debug']) print "$sql_query<br />";
	$result = MYSQL_QUERY($sql_query);
	$err = mysql_error();
	if ($err){
		$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
	} else {
		$information = $lang['one_active_vote'];
	}
	adminLog("REMOVEACTIVE", "Vote ID: $voteid", $user);
	return $information;
}

// Add or change a question
function changequestion($voteid = "new", $information = "") {
        GLOBAL $template, $tables, $lang, $config, $HTTP_POST_VARS;
	if ($information == $lang['error_incorrect_values']) {
		// Couldn't save, let's keep the old values
		$voteid = $HTTP_POST_VARS['voteid'];
		$question = $HTTP_POST_VARS['question'];
		$comment = $HTTP_POST_VARS['comment'];
		$state = $HTTP_POST_VARS['state'];
		$publish = $HTTP_POST_VARS['publish'];
		$choice = $HTTP_POST_VARS['choice'];
		$answers = $HTTP_POST_VARS['answers'];
		$choice_id = $HTTP_POST_VARS['choice_id'];
	} else if ($voteid != "new") {
		// Read information from the database
		list($voteid, $question, $date, $state, $comment, $publish) = getQuestion($voteid);
	} else {
		// New question
		$voteid = -1;
		$question = "";
		$date = "";
		$state = "2";
		$comment = "";
		$publish = date($config['dateformat'], time());
		$nrofanswers = $config['nrofanswers'];
	}
	if ($voteid >= 0) {
		list($choice_id, $choice, $answers, $totalanswers) = getAnswers($voteid);
		$nrofanswers = count($choice_id);
	}
	print getTemplate("header");
	if ($information != "") {
		adminheader($lang['information']);
		print $template['pre_admin_info']. $information . $template['post_admin_info'];
	}
	adminheader($lang['createvote']);
	print <<<ENDSTRING

<form action="{$config['scriptname']}?action=savequestion" name="newquestion" method="post">\n
<input type="hidden" name="voteid" value="{$voteid}" />
{$lang['question']}<br />
<input type="text" name="question" value="{$question}" size="40" maxlength="255" /><br />
{$lang['comment']}<br />
<textarea name="comment" wrap="virtual" cols="42" rows="6">{$comment}</textarea><br />
{$lang['publish']}
<input type="text" name="publish" value="{$publish}" size="10" maxlength="10" /><br />
{$lang['state']}
<select name="state">
ENDSTRING;
	for ($i = 0; $i < count($config['arrstate']); $i++) {
		$selected = ($state == $i) ? " selected=\"selected\"" : "";
		print "<option value=\"{$i}\"{$selected}>{$config['arrstate'][$i]}</option>\n";
	}
	print "</select>";
	
	adminheader($lang['answers']);
	print <<<ENDSTRING
<script language="JavaScript" type="text/javascript">
function deleteitem(item) {
	document.forms['newquestion'].action = "{$config['scriptname']}?action=deletechoice&cid=" + item;
	document.forms['newquestion'].submit();
	return false;
}
</script>
ENDSTRING;
	print $template['pre_createquestion'] . "\n";
	$choices = count($choice_id);
	for ($i = 0; $i <= $nrofanswers; $i++) {
		$cid = ($i >= $choices) ? $lang['new_answer'] : $choice_id[$i];
		$this_choice = ($i >= $choices) ? "" : $choice[$i];
		$this_answers = ($i >= $choices) ? "0" : $answers[$i];
		print <<<ENDSTRING
{$template['pre_createquestion_row']}
{$template['pre_createquestion_item']}<input type="hidden" name="choice_id[{$i}]" value="{$cid}" />{$cid}{$template['post_createquestion_item']}
{$template['pre_createquestion_item']}<input type="text" name="choice[{$i}]" value="{$this_choice}" size="40" maxlength="255" />{$template['post_createquestion_item']}
{$template['pre_createquestion_item']}<input type="text" name="answers[{$i}]" value="{$this_answers}" size="5" maxlength="5" />{$template['post_createquestion_item']}
ENDSTRING;
		if ($this_choice) {
			print <<<ENDSTRING

{$template['pre_createquestion_item']}<a href="javascript:void()" onClick="return deleteitem('{$cid}');">{$lang['delete']}</a>{$template['post_createquestion_item']}

ENDSTRING;
		}
		print $template['post_createquestion_row'];
	}
	print $template['post_createquestion'];
	print "\n<input type=\"submit\" type=\"submit\" value=\"{$lang['save_question']}\" />\n";
	print "</form>\n";
	print $template['html_footer'] . "\n";
}

// Save the information of a question.
function savequestion() {
	GLOBAL $template, $config, $tables, $lang, $HTTP_POST_VARS, $user;
	$voteid = trim($HTTP_POST_VARS['voteid']);
	$question = trim($HTTP_POST_VARS['question']);
	$comment = trim($HTTP_POST_VARS['comment']);
	$state = trim($HTTP_POST_VARS['state']);
	$publish = trim($HTTP_POST_VARS['publish']);
	$choice = $HTTP_POST_VARS['choice'];
	$answers = $HTTP_POST_VARS['answers'];
	$choice_id = $HTTP_POST_VARS['choice_id'];
	$now = date($config['dateformat_long'], time());
	$updated = 0;
	$information = "";

	// Let's see if all values have been entered.
	if (($question == "") || ($state == "") || ($state > 3) || 
		($publish == "") || !oj_checkdate($publish) || 
		(count($choice_id) == 0)) {
		return array($voteid, $lang['error_incorrect_values']);
	}

	// Make sure there is no html in the texts and that
	// all values are escaped.
	$voteid = addslashes($voteid);
	$question = addslashes(strip_tags($question));
	$comment = addslashes(strip_tags($comment, "<i><b><a>"));
	$state = addslashes($state);
	$publish = addslashes($publish);

	$table = $tables['question'];
	$values = "varQuestion = '$question', varComment = '$comment', intState = $state, dteDate = '$now', dtePublish = '$publish'";
	$id = $voteid;
	
	// Let's find out if we are creating or changing a question.
	$insertquery = "INSERT INTO \$table SET \$values";
	$updatequery = "UPDATE \$table SET \$values WHERE ID = \$id";
	if ($voteid >= 0) {
		// Change a current question
		eval("\$sql_query = \"$updatequery\";");
		$Operation = "UPDATEQUESTION";
		#$sql_query2a = "UPDATE {$tables['answers']} SET ";
		#$sql_query2b = "WHERE ID = ";
	} else {
		// Create a new question
		eval("\$sql_query = \"$insertquery\";");
		$Operation = "CREATEQUESTION";
		#$sql_query2a = "INSERT INTO {$tables['answers']} ";
		#$sql_query2b = "";
	}
	// This loop first does all the queries, and uses an update
	// or insert statement where necessary.
	// On the first pass, the question is inserted/updated
	// and on the following passes the answers are updated
	// and lastly any new answers are inserted.
	$i = 0;
	while ($i <= count($choice_id)) {
		// Evaluate the sql query
		#eval("\$query = \"$sql_query\";");
		// Do the query
		#print "sql_query: $sql_query<br>\n";
	        if ($config['debug']) print "$sql_query<br />";
	        $result = MYSQL_QUERY($sql_query);
		$err = mysql_error();
		if ($err) {
			$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
		} else {
			// If we've created a new vote, we need to get its ID.
			if (($i == 0) && ($voteid == -1)) {
				list($voteid, $str) = getNewestQuestion();
				$information .= $str;
			}
			$updated++;
		}
		// Now update the values for the next iteration
		while (($choice[$i] == "") && ($i <= count($choice_id))) {
			$i++;
		}
		$table = $tables['answer'];
		$this_choice = addslashes(strip_tags($choice[$i]));
		$this_answer = addslashes(strip_tags($answers[$i]));
		$values = "varChoice = '$this_choice', intAnswers = $this_answer, question_ID = $voteid";
		$id = $choice_id[$i];
		if ($id == $lang['new_answer']) {
			eval("\$sql_query = \"$insertquery\";");
		} else {
			eval("\$sql_query = \"$updatequery\";");
		}
		$i++;
	}
	$information = $lang['question_updated'];

	// If this question is active and only one active question is allowed
	// set all other active questions to inactive.
	if (($state == 1) && (($config['singlevote'] == "yes") || ($config['singlevote'] == 1))) {
		$information .= "<br />" . removeactive($voteid);
	}

	adminLog($Operation, "Vote ID: $id", $user);

	return array($voteid, $information);
}

// Delete an answer to a question.
function deletechoice($choice_id) {
	GLOBAL $tables, $lang, $user, $config;
	$sql_query = "DELETE FROM {$tables['answer']} WHERE ID = {$choice_id}";
	if ($config['debug']) print "$sql_query<br />";
	#print "sql_query: $sql_query<br />";
	$result = MYSQL_QUERY($sql_query);
	$err = mysql_error();
	if ($err) {
		$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
	} else {
		$information = $lang['answer_deleted'];
	}
	$sql_query = "DELETE FROM {$tables['voted']} WHERE intAnswer = {$choice_id}";
	$result = MYSQL_QUERY($sql_query);
	$err = mysql_error();
	if ($err) {
		$strError .= "{$lang['error_sql_query']}: {$sql_query}<br />\n{$lang['error_sql']}: {$err}<br />\n";
	} else {
		$information .= "<br />{$lang['voters_deleted']}";
	}

	adminLog("DELETECHOICE", "Choice ID: $choice_id", $user);

	return $information;
}

?>
