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

class basic_view_active extends basic_view {
	
	function loadLanguage() {
		parent::loadLanguage();
		$this->loadLangFile('basic_view_active');
	}
	
	function view() {
		$obj = owRead($this->objectid[0]);
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
		<script language="JavaScript" FOR="CANCEL" event="onclick">
			window.close();
		</script>
		<script language="JavaScript" FOR="AKTIVER" event="onclick">
			myForm.val.value = 1;
			myForm.submit();
		</script>
		<script language="JavaScript" FOR="DEAKTIVER" event="onclick">
			myForm.val.value = 0;
			myForm.submit();
		</script>
		</head>
		<body>
    	<form name="myForm"  method="post">
    	<?php echo $this->returnviewpost('active'); ?>
      	<input type="hidden" name="objectid" value="<?php echo $this->objectidstring($this->objectid) ?>">
      	<input type="hidden" name="cmd" value="active">
      	<input type="hidden" name="val" value="">
		<table style="margin: 10px; width: 100%">
		<tr>
		<td valign="middle" align="center" width="300">
		<?php echo $this->gl('text_1') ?><br><br><br>
		</td>
		<td valign="middle" align="left">
			<table cellpadding="2">
			<tr>
			<td><input type="button" id="AKTIVER" value="<?php echo $this->gl('button_activate') ?>" style="width: 80px; height: 22px" default></td>
			</tr>
			<tr>
			<td><input type="button" id="DEAKTIVER" value="<?php echo $this->gl('button_deactivate') ?>" style="width: 80px; height: 22px"></td>
			</tr>
			<tr>
			<td><input type="button" id="CANCEL" value="<?php echo $this->gl('button_cancel') ?>" style="width: 80px; height: 22px"></td>
			</tr>
			</table>
		</td>
		</tr>
		</table>
    	</form>
		</body>
		</html>
		<?php
	}
}

?>