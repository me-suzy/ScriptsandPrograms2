<?php
/*
******************************************************************************************
** SB|photoAlbum                                                                        **
** Copyright (C)2005 Ladislav Soukup                                                    **
**                                                                                      **
** URL: http://php.soundboss.cz                                                         **
** URL: http://www.soundboss.cz                                                         **
******************************************************************************************
*/
$langs_detect = explode(",", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
$langs = array_merge(array($_GET["lang"]), $langs_detect, array(pa_default_lang));
foreach($langs as $lang) {
	list($lang) = explode(";", $lang);
	$lang_file = "./photoalbum/lang/" . $lang . ".php";
	if (file_exists($lang_file)){
		include $lang_file;
		define("used_lang_code", $lang);
		break;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo pa_lang_code; ?>" lang="<?php echo pa_lang_code; ?>">
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo pa_charse; ?>" />
	<meta http-equiv="content-language" content="<?php echo pa_lang_code; ?>" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="description" content="<?php echo pa_description; ?>" />
	<meta name="robots" content="ALL,FOLLOW" />
	<meta http-equiv="Cache-control" content="no-cache" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<link rel="SHORTCUT ICON" href="favicon.gif" type="image/x-icon" />
	<link rel="STYLESHEET" href="<?php if (pa_header_include_subdir) echo "photoalbum/" ?>core.css" type="text/css" />
	<link rel="STYLESHEET" href="<?php if (pa_header_include_subdir) echo "photoalbum/" ?>dtree.css" type="text/css" />
	<title><?php echo pa_title; ?></title>
	<script src="<?php if (pa_header_include_subdir) echo "photoalbum/" ?>html_header.js" type="text/javascript"></script>
  </head>
