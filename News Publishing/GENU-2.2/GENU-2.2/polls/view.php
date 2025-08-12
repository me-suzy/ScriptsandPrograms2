<?php
// -------------------------------------------------------------
//
// $Id: view.php,v 1.4 2005/03/13 13:37:07 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

$sql->query('SELECT question_text, question_votes
		FROM ' . TABLE_QUESTIONS . '
		WHERE question_id = \'' . $_GET['question_id'] . '\'');
$table_questions = $sql->fetch();
if (!$table_questions['question_text'])
{
	error_template($lang['POLLS_VIEW_ERROR']);
}
else
{
	$template->set_file('view', 'polls/view.tpl');
	$template->set_block('view', 'ANSWERS_BLOCK', 'answers');
	$sql->query('SELECT answer_text, answer_votes
			FROM ' . TABLE_ANSWERS . '
			WHERE question_id = \'' . $_GET['question_id'] . '\'');
	while ($table_answers = $sql->fetch())
	{
		if ($table_questions['question_votes'] == 0)
		{
			$image_width = 0;
		}
		else
		{
			$image_width = round(($table_answers['answer_votes'] * 100) / $table_questions['question_votes']);
		}
		$template->set_var(array(
			'ANSWER_TEXT' => $table_answers['answer_text'],
			'ANSWER_VOTES' => $table_answers['answer_votes'],
			'IMAGE_WIDTH' => $image_width));
		$template->parse('answers', 'ANSWERS_BLOCK', true);
	}
	$template->set_var(array(
		'BACK_HOME' => $lang['BACK_HOME'],
		'POLLS_VIEW_HEADER' => $lang['POLLS_VIEW_HEADER'],
		'POLLS_VIEW_OTHER' => $lang['POLLS_VIEW_OTHER'],
		'QUESTION_TEXT' => $table_questions['question_text'],
		'QUESTION_VOTES' => sprintf($lang['POLLS_VIEW_TOTAL'], $table_questions['question_votes']),
		'VOTE' => $lang['VOTE']));
}

page_header($lang['POLLS_VIEW_TITLE']);
$template->pparse('', 'error');
$template->pparse('', 'view');
page_footer();

?>