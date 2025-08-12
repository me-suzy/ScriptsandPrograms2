<?php
/*
////////////////////////////////////////////////
//             Utopia Software                //
//      http://www.utopiasoftware.net         //
//             Utopia News Pro                //
////////////////////////////////////////////////
*/

require('functions.inc.php');
session_start();
$USER = unp_getUser();
unp_getsettings();

isset($_GET['action']) ? $action = $_GET['action'] : $action = '';
isset($_GET['verify']) ? $verify = $_GET['verify'] : $verify = '';

$newsphploc = $unpurl.'/news.php';
$tempfilename = 'tempnews.txt';
$news_txt = 'news.txt';
/* Headlines Cache
$headlinesphploc = $unpurl.'/headlines.php';
$tempfilename = 'tempheadlines.txt';
$headlines_txt = 'headlines.txt';
*/

// +------------------------------------------------------------------+
// | Process                                                          |
// +------------------------------------------------------------------+
if ($action == '')
{
	include('header.php');
	unp_openbox();
	echo '
	<strong>News Cache Tools</strong>&nbsp;';
	unp_faqLink(2);
	echo '<br />';
	if (file_exists($news_txt))
	{
		echo '<a href="news.txt">View News Cache</a><br />'."\n\n";
		echo '<a href="newscache.php?action=generate">Update News Cache</a><br />'."\n\n";
		echo '<a href="newscache.php?action=delcache">Delete News Cache</a><br />'."\n\n";
	}
	else
	{
		echo '<a href="newscache.php?action=generate">Create News Cache</a><br />';
	}
	echo '<br /><br /><strong>What is the news cache?</strong><br />For information about the news cache, be sure to check the <a href="faq.php?action=category&amp;catid=2" target="_blank">FAQ</a>.';
	unp_closebox();
	include('footer.php');
}

// +------------------------------------------------------------------+
// | Generate News Cache                                              |
// +------------------------------------------------------------------+
if ($action == 'generate')
{
	include('header.php');
	unp_openbox();
	echo '
	<strong>Building News Cache...</strong><br />';
	@unlink($tempfilename); // kill this as it might still be lying around
	
	echo 'Opening news file...';
	$dynnews = @fopen($newsphploc, 'r');
	if (!$dynnews)
	{
		echo 'There was an error opening news.php. Please go back and try again. Update aborted.';
		unp_closebox();
		include('footer.php');
		exit;
	}
	echo 'OK<br /><br />';
	echo 'Saving news...';
	$htmldata = '';
	while (!feof($dynnews))
	{
		$htmldata = $htmldata.fread($dynnews, 1024);
	}
	echo 'OK<br /><br />';
	fclose($dynnews);
	echo 'Creating temporary news storage...';
	$tempfile = @fopen($tempfilename, 'w');
	if (!$tempfile)
	{
		echo 'There was an error creating the temporary file. Please go back and try again. Update aborted.';
		unp_closebox();
		include('footer.php');
		exit;
	}
	echo 'OK<br /><br />';
	echo 'Writing news to temporary storage...';
	fwrite($tempfile, $htmldata);
	echo 'OK<br /><br />';
	fclose($tempfile);
	echo 'Copying news from temporary storage into permanent storage...';
	$ok = @copy($tempfilename, $news_txt);
	echo 'OK<br /><br />';
	echo 'Removing temporary storage...';
	unlink($tempfilename);
	echo 'OK<br /><br />';
	echo 'News cache created successfully.';
	unp_closebox();
	include('footer.php');
}

// +------------------------------------------------------------------+
// | Delete News Cache                                                |
// +------------------------------------------------------------------+
if ($action == 'delcache')
{
	if (($USER['groupid'] != 1) && ($USER['groupid'] != 2))
	{
		unp_msgBox($gp_permserror);
		exit;
	}
	if ($verify == '')
	{
		include('header.php');
		unp_openbox();
		echo '
		<strong>Are you sure you want to delete the cache?</strong><br />';
		echo '<a href="newscache.php?action=delcache&verify=1">Yes</a><br />
		<a href="newscache.php">No</a>';
		unp_closebox();
		include('footer.php');
	}
	elseif ($verify == 1)
	{
		if (!file_exists($news_txt))
		{
			unp_msgBox('No news cache exists.');
			exit;
		}
		include('header.php');
		unp_openbox();
		echo '
		<strong>Deleting News Cache...</strong><br />';
		$delcache = @unlink($news_txt);
		if (!$delcache)
		{
			unp_msgBox('<strong>Fatal Error:</strong> Error removing news cache. Ensure that you have proper permissions to do so.');
			include('footer.php');
			exit;
		}
		echo 'News cache successfully removed!';
		unp_closebox();
		include('footer.php');
	}
	else
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
}
?>