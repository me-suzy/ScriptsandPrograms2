<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## $Source: /home/cvsroot/xtras/conditions/FPsy_Toggle/FPsy_Toggle.inc,v $
## $Revision: 1.10 $
## $Author: dofford $
## $Date: 2004/02/26 23:27:13 $
#######################################################################
global $INCLUDE_PATH;
global $SQUIZLIB_PATH;
include_once("$INCLUDE_PATH/condition.inc");
include_once("$SQUIZLIB_PATH/form/formelement.inc");
#---------------------------------------------------------------------#

class FPsy_Toggle extends Condition {

	var $width  = 400;
	var $height = 230;

	function evaluate($val) {
		list($cond, $aid, $pid_seek_global, $format, $val_rule) = explode(',', $val);
		# Where should we get the pid from?
		if ($pid_seek_global == '1') {
			$pid = $GLOBALS['xtras_page_templates_frontitia_record_element_pid'];
		} else {
			$pid = $_REQUEST['pid'];
			if ($pid_seek_global == 'notinurl' && ($pid == null || $pid == 0)) {
				$pid = $GLOBALS['xtras_page_templates_frontitia_record_element_pid'];
			}
		}

		# if we dont have a recordid, return true
		# just to be safe - this condition might
		# have been set on a table that doesnt get displayed
		# if the record view
		if (!$pid) return false;

		# get the record
		$notitia = &$this->get_notitia_system();
		$record = &$notitia->get_record($pid);
		if (!$format) {
			$format = 'raw';
		}
		# NA represents if someone set an attribute which didn't have any value formats so in this case we shouldn't run a get_attribute_formatted_value because we risk dieing
		if ($format != 'NA') {
			$value = $record->get_attribute_formatted_value($aid, $format);
			if (isset($val_rule) && $val_rule != '0') {
				switch ($val_rule) {
					case 'contains':
						return FormElement::generic_contains($value, $cond);
					case 'ereg':
						return ereg($cond,$value);
				}
			}
			elseif ($value == $cond) return true;
		}
		return false;
	}#end evaluate()

	function &get_notitia_system() {
		$web_system = &get_web_system();
		return $web_system->get_extension('notitia');
	}
	function print_backend() {
		$attributeid = $_GET['url_aid'];
		if ($attributeid == 'undefined' || !$attributeid) {
			$attributeid = 0;
			$formats = array('raw' =>'No value formats are available yet');
		} else {
			$notitia = &$this->get_notitia_system();
			$attribute = &$notitia->get_attribute($attributeid);
			if ($attribute->id) {
				$formats = $attribute->get_type_setting('value_formats');
				if (empty($formats)) {
					$formats = array('NA' =>"This attribute doesn't have any value formats");
				}
			} else {
				$formats = array('raw' =>'No value formats are available yet');
			}
		}

		$redirect = $_GET['redirect'];
		if ($redirect == 'no') {
			$redirect = 'false';
		} else {
			$redirect = 'true';
		}
		?>
			<style>
				.FPYform_style {
					width: 230px;
				}
			</style>
			<form name="comp_form" onsubmit="save_conditions()">
			<table>
			<tr>
				<td>Attribute ID</td>
				<td><input type="text" name="aid" size="5"  class="FPYform_style"></td>
			</tr>
			<tr>
				<td>Choose Attribute Format</td>
				<td><select name="attribute_format" class="FPYform_style">
				<?
				if (!empty($formats)) {
					foreach($formats as $format => $label) {
						?>
						<option value="<?=$format?>"><?=$label?></option>
						<?
					}
				}
				?>
				</select></td>
			</tr>
			<tr>
				<td>Value Rules</td>
				<td>
					<select name="val_rule" class="FPYform_style">
					<option value="0">Equals</option>
					<option value="contains">Contains</option>
					<option value="ereg">match extended regular expression</option>
				</select>
				</td>
			</tr>
			<tr>
				<td>Value</td>
				<td><input type="text" name="cond" size="5" class="FPYform_style"></td>
			</tr>
			<tr>
				<td>Seek pid in GLOBALS?</td>
				<td>
					<select name="pid_seek_global" class="FPYform_style">
					<option value="0">No</option>
					<option value="1">Yes</option>
					<option value="notinurl">If not in URL</option>
					</select>
				</td>
			</tr>
			<tr><td colspan="2" align="center"><input type="submit" name="submit" value="Commit"></td></tr>
			</table>
			</form>

			<script language="Javascript">

				get_previous_options();
				
				function save_conditions() {
					var conditions = new Array();
					conditions[0] = document.comp_form.cond.value;
					conditions[1] = document.comp_form.aid.value;
					conditions[2] = document.comp_form.pid_seek_global.value;
					conditions[3] = document.comp_form.attribute_format.value;
					conditions[4] = document.comp_form.val_rule.value;
					window.opener.document.main_form.showif_conds.value = conditions;
					window.close();
				}

				function get_previous_options(){
					vals = new String();
					vals = window.opener.document.main_form.showif_conds.value;

					val = new Array();
					val = vals.split(",");
					if(val[0] != "" && val[0] != "undefined"){
						document.comp_form.cond.value = val[0];
					}
					if(val[1] != "" && val[1] != "undefined"){
						document.comp_form.aid.value = val[1];
					}
					if(val[2] != "" && val[2] != "undefined"){
						document.comp_form.pid_seek_global.value = val[2];
					}
					if(val[3] != "" && val[3] != "undefined"){
						document.comp_form.attribute_format.value = val[3];
					}
					if(val[4] != "" && val[4] != "undefined"){
						document.comp_form.val_rule.value = val[4];
					}
					if(<?=$redirect?> == 1){
						window.location = window.location.href + '&redirect=no&url_aid='+val[1];
					}
				}
			</script>
		<?
	}
} #end class FPsy_Toggle
?>
