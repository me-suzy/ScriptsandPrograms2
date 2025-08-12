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

class savedsearch_view_load extends basic_view {

	function loadLanguage() {
		parent::loadLanguage();
		$this->loadLangFile('savedsearch_view_load');
	}
	
	function view() {
		#$obj = owRead($this->objectid[0]);
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
		<script language="JavaScript" FOR="OK" event="onclick">
			myForm.submit();
		</script>
		</head>
		<body>
    	<form name="myForm"  method="post">
    	<?php echo $this->returnviewpost('load'); ?>
		<table style="margin: 10px; width: 100%">
		<tr>
		<td valign="middle" align="center" width="300">
		<?php echo $this->gl('text_1') ?><br><br>
			<table border=0>
				<tr>
				<td><?php echo $this->gl('text_2') ?></td>
				<td><select name="savedsearch" id="savedsearch" style="width: 200px">
				<?php
				$field = new basic_field($this);
				$obj = owNew('savedsearch');
				$obj->setfilter_search('class', $this->data['parentotype'], EQUAL);
				$obj->listobjects();
				if (is_array($obj->elements)) {
					foreach ($obj->elements as $element) {
						echo "<option value='" . $element['objectid'] . "'>" . $element['name'] . "</option>\n";
					}
				}
				?>
				</select>
				</td>
				</tr>
			</table>
		</td>
		<td valign="middle" align="left">
			<table cellpadding="2">
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
      	<input type="hidden" name="objectid" value="<?php echo $this->objectidstring($this->objectid) ?>">
      	<input type="hidden" name="cmd" value="load">
      	<input type="hidden" name="parentotype" value="<?php echo $this->data['parentotype'] ?>">
    	</form>
		</body>
		</html>
		<?php
	}
}

?>