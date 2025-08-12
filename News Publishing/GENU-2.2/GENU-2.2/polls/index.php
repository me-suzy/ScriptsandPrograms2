<?php
// -------------------------------------------------------------
//
// $Id: index.php,v 1.5 2005/03/13 13:37:07 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

if (!$_GET['page'])
{
	$_GET['page'] = 1;
}
if (!$_GET['list'])
{
	$_GET['list'] = 0;
}
$polls_per_page = POLLS_LIMIT;
$polls_offset = (($_GET['page'] - 1) * $polls_per_page);
$sql->query('SELECT question_id
		FROM ' . TABLE_QUESTIONS . '');
$num_polls = $sql->num_rows();
$num_pages = ceil($num_polls / $polls_per_page);
if ($_GET['page'] != 1)
{
	if ($_GET['page'] > (PAGES_LIMIT * $_GET['list']) + 1)
	{
		$pages_list .= '<a href="./../polls/index.php?page=' . ($_GET['page'] - 1) . '&amp;list=' . $_GET['list'] . '" title="' . $lang['PREVIOUS_PAGE'] . '">&lt;</a> ';
	}
	else
	{
		$pages_list .= '<a href="./../polls/index.php?page=' . ($_GET['page'] - 1) . '&amp;list=' . ($_GET['list'] - 1) . '" title="' . $lang['PREVIOUS_PAGE'] . '">&lt;</a> ';
	}
}
if ($_GET['list'] != 0)
{
	$pages_list .= '<a href="./../polls/index.php?page=' . (PAGES_LIMIT * $_GET['list']) . '&amp;list=' . ($_GET['list'] - 1) . '" title="' . (PAGES_LIMIT * $_GET['list']) . '">-</a> ';
}
for ($current_page = (PAGES_LIMIT * $_GET['list']) + 1; $current_page <= PAGES_LIMIT * ($_GET['list'] + 1) && $current_page <= $num_pages; $current_page++)
{
	if ($_GET['page'] == $current_page)
	{
		$pages_list .= $_GET['page'] . ' ';
	}
	else
	{
		$pages_list .= '<a href="./../polls/index.php?page=' . $current_page . '&amp;list=' . $_GET['list'] . '" title="' . $current_page . '">' . $current_page . '</a> ';
	}
}
if (($_GET['list'] + 1) < ($num_pages / PAGES_LIMIT))
{
	$pages_list .= '<a href="./../polls/index.php?page=' . (PAGES_LIMIT * ($_GET['list'] + 1) + 1) . '&amp;list=' . ($_GET['list'] + 1) . '" title="' . (PAGES_LIMIT * ($_GET['list'] + 1) + 1) . '">+</a> ';
}
if (($num_pages > 1) && ($_GET['page'] != $num_pages))
{
	if ($_GET['page'] < PAGES_LIMIT * ($_GET['list'] + 1))
	{
		$pages_list .= '<a href="./../polls/index.php?page=' . ($_GET['page'] + 1) . '&amp;list=' . $_GET['list'] . '" title="' . $lang['NEXT_PAGE'] . '">&gt;</a> ';
	}
	else
	{
		$pages_list .= '<a href="./../polls/index.php?page=' . ($_GET['page'] + 1) . '&amp;list=' . ($_GET['list'] + 1) . '" title="' . $lang['NEXT_PAGE'] . '">&gt;</a> ';
	}
}
$template->set_file('index', 'polls/index.tpl');
$template->set_block('index', 'QUESTIONS_BLOCK', 'questions');
$sql->query('SELECT question_id, question_text, question_votes
		FROM ' . TABLE_QUESTIONS . '
		ORDER BY question_date DESC
		LIMIT ' . $polls_offset . ', ' . $polls_per_page . '');
while ($table_questions = $sql->fetch())
{
	$template->set_var(array(
		'QUESTION_ID' => $table_questions['question_id'],
		'QUESTION_TEXT' => $table_questions['question_text'],
		'QUESTION_VOTES' => sprintf($lang['POLLS_INDEX_VOTES'], $table_questions['question_votes'])));
	$template->parse('questions', 'QUESTIONS_BLOCK', true);
	$questions_exist = 'true';
}
if ($questions_exist == 'true')
{
	$template->set_var(array(
		'BACK_HOME' => $lang['BACK_HOME'],
		'POLLS_INDEX_HEADER' => $lang['POLLS_INDEX_HEADER'],
		'POLLS_INDEX_PAGES' => sprintf($lang['POLLS_INDEX_PAGES'], $pages_list)));
}
else
{
	error_template($lang['POLLS_INDEX_ERROR']);
}

page_header($lang['POLLS_INDEX_TITLE']);
$template->pparse('', 'error');
if ($questions_exist == 'true')
{
	$template->pparse('', 'index');
}
page_footer();

?>