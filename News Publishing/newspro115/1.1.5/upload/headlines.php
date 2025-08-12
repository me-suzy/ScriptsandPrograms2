<?php
/*
////////////////////////////////////////////////
//             Utopia Software                //
//      http://www.utopiasoftware.net         //
//             Utopia News Pro                //
////////////////////////////////////////////////
*/

require('functions.inc.php');
unp_getSettings();
if ($headlinesallowance == 1)
{
	$templatesused = 'headlines_displaybit';
	unp_cacheTemplates($templatesused);
	// init news class
	require('news.inc.php');
	$h = new News;
	$h->unp_getStyle();
	// ** GET HEADLINES ** //
	$get_hls = $DB->query("SELECT `newsid`,`date`,`subject`,`poster` FROM `unp_news` ORDER BY `newsid` DESC LIMIT $headlineslimit");
	while ($headlines = $DB->fetch_array($get_hls))
	{
		$newsid = $headlines['newsid'];
		$date = unp_date($dateformat, $headlines['date']);
		$subject = $h->unp_doSubjectFormat($headlines['subject']);
		$poster = $headlines['poster'];
		eval('$headlines_displaybit = "'.unp_printTemplate('headlines_displaybit').'";');
		unp_echoTemplate($headlines_displaybit);
	}
}
else
{
	unp_msgBox($gp_invalidrequest);
}
?>