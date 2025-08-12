<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 */

session_start();

require_once("site.php");
if (isset($_SESSION['site']) && $_SESSION['site'] != $site) {
	unset($_SESSION['usr']);
	$_SESSION['site'] = $site;
}
require_once($system_path . 'adodb.php');
require_once($system_path . 'ow.php');
require_once($system_path . 'basic_user.php');
$userhandler =& getUserHandler();
$userhandler->setWebuser(true);
$userhandler->recognizeUser();
$obj = owNew('frame');
$frameid = 0;
$pageid = 0;
$frameid = $obj->locateDefault();
if (!$frameid) {
	$docs = owNew('document');
	$docs->setsort_col('objectid');
	$docs->listobjects();
	if ($docs->elementscount > 0) {
		$pageid = $docs->elements[0]['objectid'];
	}
}

if (!$frameid && !$pageid) {
	header("HTTP/1.0 500 Server Error");
	?>
	<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
	<HTML><HEAD>
	<TITLE>500 Internal Server Error</TITLE>
	</HEAD><BODY>
	<H1>500 Internal Server Error</H1>
	<?php echo "No default document defined!" ?><P>
	</BODY></HTML>
	<?php
	die();
}

if ($frameid) {
	$obj->readObject($frameid);
	$pageid = $obj->elements[0]['pageid'];
}
if ($pageid) {
	$_GET['pageid'] = $pageid;
	$_REQUEST['pageid'] = $pageid;
	require_once("showpage.php");
} else {
	header("HTTP/1.0 500 Server Error");
	?>
	<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
	<HTML><HEAD>
	<TITLE>500 Internal Server Error</TITLE>
	</HEAD><BODY>
	<H1>500 Internal Server Error</H1>
	<?php echo "No default document defined!" ?><P>
	</BODY></HTML>
	<?php
	die();
}
?>
