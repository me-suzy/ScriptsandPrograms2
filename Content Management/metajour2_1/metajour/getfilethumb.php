<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 */

if (!session_id()) session_start();
if (!isset($site)) $site = (isset($_SESSION['site'])) ? $_SESSION['site'] : null;
if (!isset($viewer_url)) $viewer_url = $_SESSION['viewer_url'];
if (!isset($viewer_path)) $viewer_path = $_SESSION['viewer_path'];
require_once('config.php');
require_once('adodb.php');
require_once('ow.php');
require_once('fileutils.php');

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
	$w = 0;
	$h = 0;
	$auto = 100;
	if (isset($_REQUEST['w'])) $w = $_REQUEST['w'];
	if (isset($_REQUEST['h'])) $h = $_REQUEST['h'];
	if (isset($_REQUEST['auto'])) $auto = $_REQUEST['auto'];
#	$s = $fobj->getthumb($w,$h,$auto);
#	if ($s) readfile($s);
	
	doConditionalGet(mktime($hour, $min, $sec, $month, $day, $year), $fobj->getObjectId() . $w . $h . $auto);

	$s = $fobj->getthumb($w,$h,$auto);
	header('Pragma: public');
	header('Content-Transfer-Encoding: ');
	header('Cache-Control: none');
	header('Content-Type: '.$fobj->elements[0]['mimetype'].'; name="'.$fobj->elements[0]['name'].'"');
	header('Content-Length: ' . filesize($s));
	header('Content-Disposition: inline; filename="'.$fobj->elements[0]['name'].'"');
	if ($s) readfile($s);
}

?>