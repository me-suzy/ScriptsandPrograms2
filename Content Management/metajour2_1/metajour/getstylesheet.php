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
header('Pragma: public');
header('Content-Transfer-Encoding: none');
header('Cache-Control: none');
header('Content-Type: text/css');
require_once('config.php');
require_once('adodb.php');
require_once('ow.php');
require_once('core/documentclass.php');
require_once('core/stylesheetclass.php');

$stylesheet = 0;

if ($_REQUEST['objectid'] != 0) {
	$obj = owNew('document');
	$obj->setListAccess(true);
	$obj->readObject($_REQUEST['objectid']);
	
	$stylesheet = $obj->elements[0]['stylesheetid'];
}

$styleobj = owNew('stylesheet');
$styleobj->setListAccess(true);
if ($stylesheet == 0) {
	$stylesheet = $styleobj->locatedefault();	
}
if ($stylesheet) {
	$styleobj->readObject($stylesheet);
	echo $styleobj->elements[0]['content'];
	?>
body {
	background-color: #fff;
	color: #000;
	margin: 10px;
}
table {border: dotted grey;
	border-width: 1;
}
td {border: dashed grey;
	border-width: 1;
}
<?php
}
?>