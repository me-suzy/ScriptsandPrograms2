<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 */

session_cache_limiter('none');
if (!session_id()) session_start();
if (!isset($site)) $site = (isset($_SESSION['site'])) ? $_SESSION['site'] : null;
if (!isset($viewer_url)) $viewer_url = $_SESSION['viewer_url'];
if (!isset($viewer_path)) $viewer_path = $_SESSION['viewer_path'];
require_once('config.php');
require_once('adodb.php');
require_once('ow.php');
require_once('fileutils.php');
require_once('basic_user.php');
if (!isset($_SESSION['usr']['validuserid'])) {
	$userhandler =& getUserHandler();
	$userhandler->recognizeUser();
}

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

	header('Pragma: public');
	header('Content-Transfer-Encoding: none');
	header('Cache-Control: none');
	header('Content-Type: '.$fobj->elements[0]['mimetype']);
	header('Content-Length: ' . filesize($fobj->elements[0]['realfile']));
	header('Content-Disposition: inline; filename="'.$fobj->elements[0]['name'].'"');
	readfile($fobj->elements[0]['realfile']);
}
?>