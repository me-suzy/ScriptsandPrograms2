<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage extension
 */

require_once($system_path.'basic_view_combidialog.php');

class listing_view_combidialog extends basic_view_combidialog {

	function afterForm() {
		$result = '
		<script type="text/javascript">
		var classnamedropdown = document.getElementById("classname");
		classnamedropdown.onchange = function() {
			this.form._ret.value = this.form.view.value;
			this.form.view.value="combidialog";
			this.form.submit();
		}
		</script>
		';
		return $result;
	}
	
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
		<tr><td></td><td><strong><?php echo $this->gl('text_1'); ?></strong></td><td><strong><?php echo $this->gl('text_2'); ?></strong></td></tr>
		<?php 
		if ($_obj->elements[0]['classname'])
			$mycols = owDatatypeColsDesc($_obj->elements[0]['classname']);
		$dyncount = 0;
		while ($dyncount < sizeof($_obj->elements[0]['fieldname'])) {
		?>
		<tr>
		<td><div class="mformfieldset" style=""><div class="mformlabel" style=""><?php echo $this->gl('text_3'); ?></div><div class="mformfield" style=""></div></div></td>
			<td><select name="fieldname[]">
			<?php
			foreach($mycols as $val) {
			?>
			<option value="<?php echo $val['name']; ?>" <?php if ($_obj->elements[0]['fieldname'][$dyncount] == $val['name']) echo "SELECTED"; ?>><?php echo $val['label'] ?></option>
			<?php
			}
			?>
			</select></td>
			<td><input type="text" name="fieldsortorder[]" value="<?php echo $_obj->elements[0]['fieldsortorder'][$dyncount] ?>"></td>
			<td><input type="button" class="mformsubmit" value="<?php echo $this->gl('text_4'); ?>" onclick="removerow(this);"></td>
		</tr>
		<?php 
			$dyncount++;
		} 
		
		if ($dyncount == 0) {
		?>
		<tr>
		<td><div class="mformfieldset" style=""><div class="mformlabel" style=""><?php echo $this->gl('text_3'); ?></div><div class="mformfield" style=""></div></div></td>
			<td><select name="fieldname[]">
			<?php
			if (is_array($mycols)) {
				foreach($mycols as $val) {
				?>
				<option value="<?php echo $val['name']; ?>"><?php echo $val['label'] ?></option>
				<?php
				}
			}
			?>
			</select></td>
			<td><input type="text" name="fieldsortorder[]" value=""></td>			
			<td><input type="button" class="mformsubmit" value="<?php echo $this->gl('text_4'); ?>" onclick="removerow(this);"></td>
		</tr>
		<?php 
		}
		
		?>
		</table>
		<input type="button"  class="mformsubmit"  value="<?php echo $this->gl('text_5'); ?>" onclick="createrow()">
		<br><br>
		<?php
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}

}

?>