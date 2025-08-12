<?php
// -------------------------------------------------------------
//
// $Id: browse.php,v 1.4 2005/03/13 13:37:07 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

$sql->query('SELECT category_id, category_name
		FROM ' . TABLE_CATEGORIES . '
		WHERE category_level != \'1\'
		ORDER BY category_name');
while ($table_categories = $sql->fetch())
{
	$category_name_options .= '<option value="' . $table_categories['category_id'] . '">' . $table_categories['category_name'] . '</option>';
}
$sql->query('SELECT DISTINCT news_month
		FROM ' . TABLE_NEWS . '
		ORDER BY news_month');
while ($table_news = $sql->fetch())
{
	$month_list = array(
		'01' => $lang['JANUARY'],
		'02' => $lang['FEBRUARY'],
		'03' => $lang['MARCH'],
		'04' => $lang['APRIL'],
		'05' => $lang['MAY'],
		'06' => $lang['JUNE'],
		'07' => $lang['JULY'],
		'08' => $lang['AUGUST'],
		'09' => $lang['SEPTEMBER'],
		'10' => $lang['OCTOBER'],
		'11' => $lang['NOVEMBER'],
		'12' => $lang['DECEMBER']);
	$news_month_options .= '<option value="' . $table_news['news_month'] . '">' . $month_list[$table_news['news_month']] . '</option>';
}
$sql->query('SELECT DISTINCT news_year
		FROM ' . TABLE_NEWS . '
		ORDER BY news_year');
while ($table_news = $sql->fetch())
{
	$news_year_options .= '<option>' . $table_news['news_year'] . '</option>';
}
$template->set_file('browse', 'news/browse.tpl');
$template->set_var(array(
	'BACK_HOME' => $lang['BACK_HOME'],
	'BROWSE' => $lang['BROWSE'],
	'CATEGORY_NAME_OPTIONS' => $category_name_options,
	'FORM_CATEGORY_NAME' => $lang['FORM_CATEGORY_NAME'],
	'FORM_NEWS_MONTH' => $lang['FORM_NEWS_MONTH'],
	'FORM_NEWS_YEAR' => $lang['FORM_NEWS_YEAR'],
	'NEWS_BROWSE_HEADER' => $lang['NEWS_BROWSE_HEADER'],
	'NEWS_MONTH_OPTIONS' => $news_month_options,
	'NEWS_YEAR_OPTIONS' => $news_year_options));

page_header($lang['NEWS_BROWSE_TITLE']);
$template->pparse('', 'browse');
page_footer();

?>