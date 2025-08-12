<?php
// -------------------------------------------------------------
//
// $Id: vote.php,v 1.7 2005/03/28 12:30:33 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

if (!$_POST['vote'])
{
	$sql->query('SELECT question_id, question_text
			FROM ' . TABLE_QUESTIONS . '
			WHERE question_id = \'' . $_GET['question_id'] . '\'');
	$table_questions = $sql->fetch();
	if (!$table_questions['question_id'])
	{
		error_template($lang['POLLS_VOTE_ERROR1']);
	}
	else
	{
		$template->set_file('vote', 'polls/vote.tpl');
		$template->set_block('vote', 'ANSWERS_BLOCK', 'answers');
		$sql->query('SELECT answer_id, answer_text
				FROM ' . TABLE_ANSWERS . '
				WHERE question_id = \'' . $_GET['question_id'] . '\'');
		while ($table_answers = $sql->fetch())
		{
			$template->set_var(array(
				'ANSWER_ID' => $table_answers['answer_id'],
				'ANSWER_TEXT' => $table_answers['answer_text']));
			$template->parse('answers', 'ANSWERS_BLOCK', true);
		}
		$template->set_var(array(
			'BACK_HOME' => $lang['BACK_HOME'],
			'POLLS_VOTE_HEADER' => $lang['POLLS_VOTE_HEADER'],
			'POLLS_VOTE_OTHER' => $lang['POLLS_VOTE_OTHER'],
			'POLLS_VOTE_RESULTS' => $lang['POLLS_VOTE_RESULTS'],
			'QUESTION_ID' => $table_questions['question_id'],
			'QUESTION_TEXT' => $table_questions['question_text'],
			'VOTE' => $lang['VOTE']));
	}
}
else
{
	$date_format = get_date_format();
	$date_offset = get_date_offset();
	$sql->query('SELECT vote_date
			FROM ' . TABLE_VOTES . '
			WHERE question_id = \'' . $_POST['question_id'] . '\' AND user_ip = \'' . $_SERVER['REMOTE_ADDR'] . '\'');
	while ($table_votes = $sql->fetch())
	{
		$vote_date = date($date_format, (($table_votes['vote_date'] + VOTE_INTERVAL) + $date_offset));
		if ($table_votes['vote_date'] >= (time() - VOTE_INTERVAL))
		{
			$error .= sprintf($lang['POLLS_VOTE_ERROR2'], $vote_date);
		}
	}
	if (!$_POST['answer_id'])
	{
		$error .= $lang['NO_ANSWER_TEXT'];
	}
	if ($error)
	{
		error_template($error);
	}
	else
	{
		$sql->query('INSERT INTO ' . TABLE_VOTES . ' (question_id, user_ip, vote_date)
				VALUES (\'' . $_POST['question_id'] . '\', \'' . $_SERVER['REMOTE_ADDR'] . '\', \'' . time() . '\')');
		$sql->query('UPDATE ' . TABLE_ANSWERS . '
				SET answer_votes = answer_votes + 1
				WHERE answer_id = \'' . $_POST['answer_id'] . '\'');
		$sql->query('UPDATE ' . TABLE_QUESTIONS . '
				SET question_votes = question_votes + 1
				WHERE question_id = \'' . $_POST['question_id'] . '\'');
		success_template($lang['POLLS_VOTE_SUCCESS']);
		header('Refresh: 3; URL= ./../polls/view.php?question_id=' . $_POST['question_id'] . '');
	}
}

page_header($lang['POLLS_VOTE_TITLE']);
$template->pparse('', 'error');
$template->pparse('', 'success');
$template->pparse('', 'vote');
page_footer();

?>