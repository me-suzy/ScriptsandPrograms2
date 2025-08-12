<?
	if ($page->languageredirect == $_SERVER['PHP_SELF']) {
		if (isset($_GET['setLang'])) $_SESSION['setLang'] = $_GET['setLang'];
	}
	
	$langFile = $page->relpath.'inc/lang/'.$lang[$_SESSION['setLang']]['file'];
	
	if (file_exists($langFile)) {
		include_once($langFile);
	}
	else {
		echo "Could not find language file $langFile";
	}
?>