<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 */

session_start();
$site = (isset($_SESSION['site'])) ? $_SESSION['site'] : null;
$viewer_url = $_SESSION['viewer_url'];
$viewer_path = $_SESSION['viewer_path'];
require_once('config.php');
require_once('adodb.php');
require_once('ow.php');

$fobj = owRead($_REQUEST['objectid']);
header('Pragma: public');
header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: pre-check=0, post-check=0, max-age=0');
header('Content-Transfer-Encoding: none');
header('Content-Type: application/octetstream; name="'.$fobj->elements[0]['name'].'."'); //This should work for IE & Opera 
header('Content-Type: application/octet-stream; name="'.$fobj->elements[0]['name'].'."'); //This should work for the rest 
header('Content-Disposition: inline; filename="'.$fobj->elements[0]['name'].'."');
if (isset($_REQUEST['rev']) && is_numeric($_REQUEST['rev'])) {
	readfile($fobj->elements[0]['realfile'] . '. ' . $_REQUEST['rev']);
} else {
	readfile($fobj->elements[0]['realfile']);
}
?>