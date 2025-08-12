<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 */

session_start();
require_once('config.php');
require_once('adodb.php');
require_once('core/util/func.php');
require_once('core/systemclass.php');
require_once('ow.php');
require_once('basic_context.php');
require_once('basic_control.php');
require_once('basic_error.php');
require_once('basic_event.php');
require_once('basic_user.php');

$userhandler =& getUserHandler();
$userhandler->setWebuser(false);
if (isset($_REQUEST['_app'])) $userhandler->setAppName($_REQUEST['_app']);

if (!$userhandler->LoggedIn()) {
	header('Location: index.php?expired=1&load='.urlencode($_SERVER['REQUEST_URI']));
}
header("Cache-Control: private");

if (isset($_REQUEST['objectid'])) {
	$objectid = explode(",",$_REQUEST['objectid']);
} else {
	$objectid = array();
}

$cmd = (isset($_REQUEST['cmd'])) ? explode(",",$_REQUEST['cmd']) : array();
$view = (isset($_REQUEST['view'])) ? explode(",",$_REQUEST['view']) : array();

if (isset($_REQUEST['objectid'])) $otype = owGetDatatype($objectid[0]);
if (isset($_REQUEST['otype'])) $otype = $_REQUEST['otype'];

$context = (isset($_REQUEST['_context'])) ? getcontext($otype, $_REQUEST['_context']) : getcontext($otype);
$controller = getcontrol($otype,$objectid,$context);
$controller->model($cmd);
$controller->viewcomplete($view);
?>