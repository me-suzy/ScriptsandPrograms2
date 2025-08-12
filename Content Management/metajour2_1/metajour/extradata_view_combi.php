<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view_combi.php');

class extradata_view_combi extends basic_view_combi {

	function customFields() {
		$_obj = owRead($this->objectid[0]);
		ob_start();
		?>
		<script type="text/javascript">
		function createrow() {
			var table = document.getElementById('dyntable');
			var tbody = table.getElementsByTagName('TBODY').item(0);
			var firstrow = table.getElementsByTagName('TR');
			var newrow = firstrow.item(1).cloneNode(true);
			tbody.appendChild(newrow);
		}

		function removerow(button) {
			var td = button.parentElement;
			var tr = td.parentElement;
			var tbody = tr.parentElement;
			if (tbody.children.length > 2) tbody.removeChild(tr);
		}
		</script>
		<table id="dyntable" style="position: relative; left: -2px;">
		<tr>
		<td></td><td><strong><?php echo $this->gl('text_1'); ?></strong></td><td><strong><?php echo $this->gl('text_8'); ?></strong></td><td><strong><?php echo $this->gl('text_2'); ?></strong></td><td><strong><?php echo $this->gl('text_3'); ?></strong></td>
		</tr>
		<?php 
		$dyncount = 0;
		while ($dyncount < sizeof($_obj->elements[0]['fieldname'])) {
		?>
		<tr>
		<td><div class="mformfieldset" style=""><div class="mformlabel" style=""><?php echo $this->gl('text_4'); ?></div><div class="mformfield" style=""><input type="text" name="fieldname[]" value="<?php echo $_obj->elements[0]['fieldname'][$dyncount] ?>"></div></div></td>
			<td><select name="fieldtype[]">
			<option value="<?php echo UI_STRING; ?>" <?php if ($_obj->elements[0]['fieldtype'][$dyncount] == UI_STRING) echo "SELECTED"; ?>>UI_STRING</option>
			<option value="<?php echo UI_TEXT; ?>" <?php if ($_obj->elements[0]['fieldtype'][$dyncount] == UI_TEXT) echo "SELECTED"; ?>>UI_TEXT</option>
			<option value="<?php echo UI_CHECKBOX; ?>" <?php if ($_obj->elements[0]['fieldtype'][$dyncount] == UI_CHECKBOX) echo "SELECTED"; ?>>UI_CHECKBOX</option>
			<option value="<?php echo UI_RELATION; ?>" <?php if ($_obj->elements[0]['fieldtype'][$dyncount] == UI_RELATION) echo "SELECTED"; ?>>UI_RELATION</option>
			<option value="<?php echo UI_LISTDIALOG; ?>" <?php if ($_obj->elements[0]['fieldtype'][$dyncount] == UI_LISTDIALOG) echo "SELECTED"; ?>>UI_LISTDIALOG</option>
			</select></td>
			<td><input type="text" name="fieldrelation[]" value="<?php echo $_obj->elements[0]['fieldrelation'][$dyncount] ?>"></td>
			<td><input type="text" name="fielddescription[]" value="<?php echo $_obj->elements[0]['fielddescription'][$dyncount] ?>"></td>
			<td><input type="text" name="fieldsortorder[]" value="<?php echo $_obj->elements[0]['fieldsortorder'][$dyncount] ?>"></td>
			<td><input type="button" class="mformsubmit" value="<?php echo $this->gl('text_5'); ?>" onclick="removerow(this);"></td>
		</tr>
		<?php 
			$dyncount++;
		} 
		
		if ($dyncount == 0) {
		?>
		<tr>
		<td><div class="mformfieldset" style=""><div class="mformlabel" style=""><?php echo $this->gl('text_6'); ?></div><div class="mformfield" style=""><input type="text" name="fieldname[]" value=""></div></div></td>
			<td><select name="fieldtype[]">
			<option value="<?php echo UI_STRING; ?>">UI_STRING</option>
			<option value="<?php echo UI_TEXT; ?>">UI_TEXT</option>
			<option value="<?php echo UI_CHECKBOX; ?>">UI_CHECKBOX</option>
			<option value="<?php echo UI_RELATION; ?>">UI_RELATION</option>
			<option value="<?php echo UI_LISTDIALOG; ?>">UI_LISTDIALOG</option>
			</select></td>
			<td><input type="text" name="fieldrelation[]" value=""></td>
			<td><input type="text" name="fielddescription[]" value=""></td>
			<td><input type="text" name="fieldsortorder[]" value=""></td>
			<td><input type="button" class="mformsubmit" value="<?php echo $this->gl('text_5'); ?>" onclick="removerow(this);"></td>
		</tr>
		<?php 
		}
		
		?>
		</table>
		<input type="button"  class="mformsubmit"  value="<?php echo $this->gl('text_7'); ?>" onclick="createrow()">
		<br><br>
		<?php
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}

}

?>