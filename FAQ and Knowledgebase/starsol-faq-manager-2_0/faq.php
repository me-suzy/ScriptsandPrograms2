<?php

############################################################################
############################################################################
##                                                                        ##
## This script is copyright Rupe Parnell (Starsol.co.uk) 2003 - 2005.     ##
##                                                                        ##
## Distribution of this file, and/or any other files in this package, via ##
## any means, withour prior written consent of the author is prohibited.  ##
##                                                                        ##
## Starsol.co.uk takes no responsibility for any damages caused by the    ##
## usage of this script, and does not guarantee compatibility with all    ##
## servers.                                                               ##
##                                                                        ##
## Please use the contact form at                                         ##
## http://www.starsol.co.uk/support.php if you need any help or have      ##
## any questions about this script.                                       ##
##                                                                        ##
############################################################################
############################################################################

require_once('faq-functions.php');

$t = $_GET[t];

if ($_POST[t]){
	$t = $_POST[t];
} elseif ($_GET[t]){
	$t = $_GET[t];
} elseif ($_SERVER['QUERY_STRING']){
	$t = $_SERVER['QUERY_STRING'];
} else {
	$t = "sc";
}

connect_to_mysql();

switch ($t) {

	case "sc":

		$result = @mysql_query('SELECT name FROM '.$db_prefix.'c ORDER BY name ASC') or deal_with_mysql_error('Category Data Retrieval MySQL Error (sc).','clean');

		if (mysql_num_rows($result) == '0'){
			$main_content .= '<p class="prose" style="text-align: left;">There are currently no FAQ categories in the '.$site_name.' FAQ database.</p>'."\n";
		} else {
			$main_content .= '<p class="prose" style="text-align: left;">The following is a list of all categories of frequently asked questions that are currently in our database:</p>'."\n";

			$main_content .= '<ul>'."\n";
			while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
				$main_content .= '<li class="prose"><a href="'.$_SERVER[PHP_SELF].'?t=ql&amp;c='.urlencode($row[0]).'">'.htmlentities($row[0], ENT_QUOTES).'</a><br /></li>'."\n";
			}
			$main_content .= '</ul>'."\n\n";
		}

		ep($title,$meta_description,$meta_keywords,$meta_robots,$main_content);

	break;

	case "sq":

		if (!$_GET[uin]){
			$main_content .= '<p class="prose" style="text-align: left;">Sorry, no FAQ unique idenfication number was specified.</p>';
			$meta_robots = 'noindex,nofollow';
			ep($title,$meta_description,$meta_keywords,$meta_robots,$main_content);
			@mysql_close();
			exit;
		}

		$result = @mysql_fetch_array(mysql_query('SELECT uin,qu,an,category FROM '.$db_prefix.'q WHERE uin="'.$_GET[uin].'"')) or deal_with_mysql_error('FAQ Data Retrieval MySQL Error (sq).','clean');

		$title = $site_name.' FAQ: '.strip_tags($result[qu]);
		$meta_description = 'Answer to the following frequently asked question: '.strip_tags($result[qu]);

		$main_content .= '<p class="prose" style="text-align: left;"><b>Q.</b>&nbsp;&nbsp;'.nl2br($result[qu]).'</p>'."\n\n";

		$main_content .= '<p class="prose" style="text-align: left;"><b>A.</b>&nbsp;&nbsp;'.nl2br($result[an]).'</p>'."\n\n";

		if ($rating_switch){
			$main_content .= '<p class="rate">Did you find this FAQ useful? [<a href="'.$_SERVER[PHP_SELF].'?t=rq&amp;uin='.$_GET[uin].'&amp;rating=1">Yes</a>] [<a href="'.$_SERVER[PHP_SELF].'?t=rq&amp;uin='.$_GET[uin].'&amp;rating=0">No</a>]</p>';
		}

		$main_content .= '<p class="prose" style="text-align: left;">'."\n";
		$main_content .= 'View all questions in the '.htmlentities($result[category], ENT_QUOTES).' category of this FAQ? <a href="'.$_SERVER[PHP_SELF].'?t=ql&amp;c='.urlencode($result[category]).'">Take me there</a>.<br />'."\n";
		$main_content .= 'View all categories of questions in this FAQ? <a href="'.$_SERVER[PHP_SELF].'?t=sc">Take me there</a>.<br />'."\n";
		$main_content .= '</p>'."\n\n";

		ep($title,$meta_description,$meta_keywords,$meta_robots,$main_content);

	break;

	case "ql":

		$prelim = 'SELECT * FROM '.$db_prefix.'q';

		if ($_GET[c]){
			$prelim .= ' WHERE category="'.$_GET[c].'"';
		}

		$nq = mysql_num_rows(mysql_query($prelim));

		if (!$nq){
			$main_content .= '<p class="prose" style="text-align: left;">Sorry, there are currently no frequently asked questions';
			if ($_GET[c]){
				$main_content .= ' in the <i>'.htmlentities($_GET[c], ENT_QUOTES).'</i> category';
			}
			$main_content .= ' in the database</p>'."\n\n";
			$main_content .= '<p class="prose" style="text-align: left;">Follow <a href="javascript:history.go(-1)">this link</a> to go back the page you were previously looking at.</p>'."\n\n";
			ep($title,$meta_description,$meta_keywords,$meta_robots,$main_content);
			@mysql_close();
			exit;
		}

		$actual = $prelim.' ORDER BY qu ASC';

		$main_content .= '<p class="prose" style="text-align: left;">The following is a list of all '.$nq.' frequently asked questions';

		if ($_GET[c]){
			$main_content .= ' in the <i>'.htmlentities($_GET[c], ENT_QUOTES).'</i> category';
		}

		$main_content .= ' that are currently in our database:</p>'."\n\n".'<ul>'."\n";

		$result = @mysql_query($actual) or deal_with_mysql_error('Questions Data Retrieval MySQL Error (ql).','clean');

		if ($_GET[c]){
			$title = $site_name.' FAQ: '.$_GET[c];
			$meta_description = $_GET[c].' FAQs on '.$site_name;
		}

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
			$main_content .= '<li class="prose"><a href="'.$_SERVER[PHP_SELF].'?t=sq&amp;uin='.$row[0].'">'.$row[1].'</a></li>'."\n";
		}

		$main_content .= '</ul>'."\n\n";

		ep($title,$meta_description,$meta_keywords,$meta_robots,$main_content);

	break;

	case "se":

		$prelim = 'SELECT * FROM '.$db_prefix.'q';

		if ($_GET[c]){
			$prelim .= ' WHERE category="'.$_GET[c].'"';
		}

		$nq = mysql_num_rows(mysql_query($prelim));

		if (!$nq){
			$main_content .= '<p class="prose" style="text-align: left;">Sorry, there are currently no frequently asked questions';
			if ($_GET[c]){
				$main_content .= ' in the <i>'.htmlentities($_GET[c], ENT_QUOTES).'</i> category';
			}
			$main_content .= ' in the database</p>'."\n\n";
			$main_content .= '<p class="prose" style="text-align: left;">Follow <a href="javascript:history.go(-1)">this link</a> to go back the page you were previously looking at.</p>'."\n\n";
			ep($title,$meta_description,$meta_keywords,$meta_robots,$main_content);
			@mysql_close();
			exit;
		}

		$actual = $prelim.' ORDER BY qu ASC';

		$main_content .= '<p class="prose" style="text-align: left;">The following is a list of all '.$nq.' frequently asked questions and answers';

		if ($_GET[c]){
			$main_content .= ', in the <i>'.htmlentities($_GET[c], ENT_QUOTES).'</i> category,';
		}

		$main_content .= ' that are currently in our database:</p>'."\n\n".'<ul>';

		$result = @mysql_query($actual) or deal_with_mysql_error('Questions Data Retrieval MySQL Error (ql).','clean');

		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
			$main_content .= '<li class="prose"><b>Q.</b>&nbsp;&nbsp;'.nl2br($row[1]).'<br /><b>A.</b>&nbsp;&nbsp;'.nl2br($row[2]);
			if ($rating_switch){
				$main_content .= '<br /><span class="rate">Did you find this FAQ useful? [<a href="'.$_SERVER[PHP_SELF].'?t=rq&amp;uin='.$row[0].'&amp;rating=1">Yes</a>] [<a href="'.$_SERVER[PHP_SELF].'?t=rq&amp;uin='.$row[0].'&amp;rating=0">No</a>]</span>';
			}
			$main_content .= '</li>'."\n";
		}

		$main_content .= '</ul>'."\n\n";

		ep($title,$meta_description,$meta_keywords,$meta_robots,$main_content);

	break;

	case "rq":

		$meta_robots = 'noindex,nofollow';
		if (in_array(getenv('REMOTE_ADDR'),$banned_ips)){
			$main_content .= '<p class="prose" style="text-align: left;">Sorry, this I.P. address has been disallowed from rating FAQs.</p>';
			ep($title,$meta_description,$meta_keywords,$meta_robots,$main_content);
			@mysql_close();
			exit;
		}
		foreach ($spiders as $bad){
			if (strpos(strtolower($_SERVER[HTTP_USER_AGENT]), $bad)){
				$main_content .= '<p class="prose" style="text-align: left;">Sorry, this HTTP user agent has been disallowed from rating FAQs.</p>';
				ep($title,$meta_description,$meta_keywords,$meta_robots,$main_content);
				@mysql_close();
				exit;
			}
		}
		if (!$_GET[uin]){
			$main_content .= '<p class="prose" style="text-align: left;">Sorry, no FAQ unique idenfication number was specified.</p>';
			ep($title,$meta_description,$meta_keywords,$meta_robots,$main_content);
			@mysql_close();
			exit;
		}
		if ($_GET[rating] != '0' AND $_GET[rating] != '1'){
			$main_content .= '<p class="prose" style="text-align: left;">Sorry, the rating specified was invalid.</p>';
			ep($title,$meta_description,$meta_keywords,$meta_robots,$main_content);
			@mysql_close();
			exit;
		}

		$result = @mysql_fetch_array(mysql_query('SELECT uin,qu,an,category FROM '.$db_prefix.'q WHERE uin="'.$_GET[uin].'"')) or deal_with_mysql_error('FAQ Data Retrieval MySQL Error (rq). ','clean');

		$title = $site_name.' FAQ: '.strip_tags($result[qu]);
		$meta_description = 'Answer to the following frequently asked question: '.strip_tags($result[qu]);

		$main_content .= '<p class="prose" style="text-align: left;"><b>Q.</b>&nbsp;&nbsp;'.nl2br($result[qu]).'</p>'."\n\n";

		$main_content .= '<p class="prose" style="text-align: left;"><b>A.</b>&nbsp;&nbsp;'.nl2br($result[an]).'</p>'."\n\n";

		$main_content .= '<p class="prose" style="text-align: left;">'."\n";
		$main_content .= 'View all questions in the '.htmlentities($result[category], ENT_QUOTES).' category of this FAQ? <a href="'.$_SERVER[PHP_SELF].'?t=ql&amp;c='.urlencode($result[category]).'">Take me there</a>.<br />'."\n";
		$main_content .= 'View all categories of questions in this FAQ? <a href="'.$_SERVER[PHP_SELF].'?t=sc">Take me there</a>.<br />'."\n";
		$main_content .= '</p>'."\n\n";

		if ($_COOKIE[starsol_faq_ratings]){
			$faqs_rated = explode(',',$_COOKIE[starsol_faq_ratings]);
		} else {
			$faqs_rated = array();
		}

		if (!in_array($_GET[uin], $faqs_rated)){
			if (mysql_num_rows(mysql_query('SELECT uin FROM '.$db_prefix.'ratings WHERE qu="'.$_GET[uin].'" AND ip="'.getenv('REMOTE_ADDR').'"')) == '0'){
				@mysql_query('INSERT INTO '.$db_prefix.'ratings VALUES("","'.$_GET[uin].'","'.$_GET[rating].'","'.getenv('REMOTE_ADDR').'","'.gmdate('U').'")') or deal_with_mysql_error('Rating Insertion MySQL Error (rq). ','clean');
				count_faq($_GET[uin]);
				if ($_GET[rating]){
					$main_content .= '<p class="prose" style="text-align: left;">You have rated this FAQ to be <b>useful</b>.</p>';
				} else {
					$main_content .= '<p class="prose" style="text-align: left;">You have rated this FAQ to be <b>not useful</b>.</p>';
				}
			} else {
				$main_content .= '<p class="prose" style="text-align: left;">Your rating for this FAQ was not entered into the database because there has already been a rating entered from this IP address.</p>';
			}
		} else {
			$main_content .= '<p class="prose" style="text-align: left;">Your rating for this FAQ was not entered into the database because there has already been a rating entered from this computer.</p>';
		}

		if (!$_COOKIE[starsol_faq_ratings]){
			$_COOKIE[starsol_faq_ratings] = $_GET[uin];
		} else {
			$_COOKIE[starsol_faq_ratings] .= ','.$_GET[uin];
		}

		setcookie('starsol_faq_ratings', $_COOKIE[starsol_faq_ratings], time()+60*60*24*365, '/', '.'.$site_domain, 0);

		ep($title,$meta_description,$meta_keywords,$meta_robots,$main_content);

	break;

}

@mysql_close();

?>