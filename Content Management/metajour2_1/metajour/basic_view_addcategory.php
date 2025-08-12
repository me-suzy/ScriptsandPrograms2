<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view.php');

class basic_view_addcategory extends basic_view {
	
	function loadLanguage() {
		parent::loadLanguage();
		$this->loadLangFile('basic_view_addcategory');
	}

	function view() {
		$this->context->clearall(); #TODO
		?>
		<html>
		<head>
		<title><?php echo $this->gl('text_title') ?></title>
		<style type="text/css">
			body { background-color: buttonface; font-family: Tahoma; font-size: 8pt; }
			select { font-family: Tahoma; font-size: 8pt; }
			input { font-family: Tahoma; font-size: 8pt; }
			td { font-family: Tahoma; font-size: 8pt; }
		</style>
		</head>
		<body>
		<table style="margin: 10px">
		<tr>
		<td valign="top">
	    	<form name="myForm"  method="post">
	    	<?php echo $this->returnviewpost('addcategory'); ?>
			<table border=0 width="100%" style="border: 1px solid #cccccc">
				<tr>
				<td><?php echo $this->gl('text_1') ?></td>
				</tr>
			</table>
		<input type="hidden" name="categoryid" value="<?php echo $this->data['categoryid']; ?>">
	      	<input type="hidden" name="objectid" value="<?php echo $this->objectidstring($this->objectid) ?>">
	      	<input type="hidden" name="cmd" value="addcategory">
	    	</form>
		</td>
		<td valign="top">
			<table cellpadding=2>
				<tr>
				<td><input type="button" id="OK" value="<?php echo $this->gl('button_ok') ?>" style="width: 80px; height: 22px" onclick="myForm.submit(); " default></td>
				</tr>
				<tr>
				<td><input type="button" id="CANCEL" value="<?php echo $this->gl('button_cancel') ?>" style="width: 80px; height: 22px" onclick="window.close();"></td>
				</tr>
			</table>
		</td>
		</tr>
		</table>
		</body>
		</html>
		<?php
	}
}

?>
