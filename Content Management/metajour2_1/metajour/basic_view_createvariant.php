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
require_once('basic_field.php');

class basic_view_createvariant extends basic_view {

	function loadLanguage() {
		parent::loadLanguage();
		$this->loadLangFile('basic_view_createvariant');
	}
	
	function view() {
		$obj = owRead($this->objectid[0]);
		$this->context->clearall(); #TODO
		?>
		<html>
		<head>
		<title><?php echo $this->gl('text_title'); ?></title>
		<style type="text/css">
			body { background-color: buttonface; font-family: Tahoma; font-size: 8pt; }
			select { font-family: Tahoma; font-size: 8pt; }
			input { font-family: Tahoma; font-size: 8pt; }
			td { font-family: Tahoma; font-size: 8pt; }
		</style>
		<script language="JavaScript" FOR="CANCEL" event="onclick">
			window.close();
		</script>
		<script language="JavaScript" FOR="OK" event="onclick">
			myForm.submit();
		</script>
		</head>
		<body>
		<table style="margin: 10px">
		<tr>
		<td valign="top">
	    	<form name="myForm"  method="post">
	      	<input type="hidden" name="cmd" value="createvariant">
	      	<input type="hidden" name="objectid" value="<?php echo $this->objectidstring($this->objectid) ?>">
	    	<?php echo $this->returnviewpost('createvariant'); ?>
			<table border=0 width="100%" style="border: 1px solid #cccccc">
				<tr>
				<td><?php echo $this->gl('text_1'); ?></td>
				<td><select name="language" id="language" style="width: 200px">
				<?php
				if ($this->userhandler->getRestrictLanguage()) {
					echo '<option value="'.$this->userhandler->getObjectLanguage().'">'.$this->userhandler->getObjectLanguage().'</option>';
				} else {
					$field = new basic_field($this);
					echo $field->listalllanguages($this->userhandler->getLastVariantLanguage());
				}
				?>
				</select>
				</td>
				</tr>
			</table>
	    	</form>
		</td>
		<td valign="top">
			<table cellpadding=2>
				<tr>
				<td><input type="button" id="OK" value="<?php echo $this->gl('button_ok') ?>" style="width: 80px; height: 22px" default></td>
				</tr>
				<tr>
				<td><input type="button" id="CANCEL" value="<?php echo $this->gl('button_cancel') ?>" style="width: 80px; height: 22px"></td>
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