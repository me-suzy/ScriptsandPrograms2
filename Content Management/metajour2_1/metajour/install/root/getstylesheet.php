<?php
session_cache_limiter('none');
if (!session_id()) session_start();
if (!isset($site)) $site = (isset($_SESSION['site'])) ? $_SESSION['site'] : null;
if (!isset($viewer_url)) $viewer_url = $_SESSION['viewer_url'];
if (!isset($viewer_path)) $viewer_path = $_SESSION['viewer_path'];
require('site.php');
require_once($system_path . 'config.php');
require_once($system_path . 'adodb.php');
require_once($system_path . 'ow.php');
require_once($system_path . 'fileutils.php');

$fobj = owRead($_REQUEST['objectid']);
if (!$fobj) {
	header("HTTP/1.0 404 Not Found");
	?>
	<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
	<HTML><HEAD><TITLE>404 Page Not Found</TITLE></HEAD>
	<BODY><H1>404 Page Not Found</H1><?php echo $msg ?><P></BODY>
	</HTML>
	<?php
	die();
} else {
	list($date, $time) = explode(' ', $fobj->getChanged());
	list($year, $month, $day) = explode('-', $date);
	list($hour, $min, $sec) = explode(':', $time);
	doConditionalGet(mktime($hour, $min, $sec, $month, $day, $year), $_REQUEST['objectid']);

	header('Pragma: private');
	header('Content-Transfer-Encoding: none');
	header('Cache-Control: none');
	header('Content-Type: text/css');
	header('Content-Length: ' . strlen($fobj->elements[0]['content']));
	header('Content-Disposition: inline; filename="'.$fobj->elements[0]['name'].'"');
	echo $fobj->elements[0]['content'];
}
?>
