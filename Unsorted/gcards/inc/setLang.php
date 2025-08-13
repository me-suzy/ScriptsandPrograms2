<?
	if ($page->languageredirect == $_SERVER['PHP_SELF'])
	{
		if (isset($_GET['setLang'])) $_SESSION['setLang'] = $_GET['setLang'];
	}
	include_once($page->relpath.'inc/lang/'.$lang[$_SESSION['setLang']]['file']);
?>