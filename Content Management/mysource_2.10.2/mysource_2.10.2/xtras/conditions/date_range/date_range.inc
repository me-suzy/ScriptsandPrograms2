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
## $Source: /home/cvsroot/xtras/conditions/date_range/date_range.inc,v $
## $Revision: 1.1 $
## $Author: dofford $
## $Date: 2004/03/03 00:45:01 $
#######################################################################
global $INCLUDE_PATH;
global $SQUIZLIB_PATH;
include_once("$INCLUDE_PATH/condition.inc");
include_once("$SQUIZLIB_PATH/form/formelement.inc");
#---------------------------------------------------------------------#

class Date_Range extends Condition {

	var $width  = 500;
	var $height = 300;

	function evaluate($val) {
		$date1 = array();
		$date2 = array();
		list($date1[y], $date1[m], $date1[d], $date1[h], $date1[i],
			 $date2[y], $date2[m], $date2[d], $date2[h], $date2[i],
			 $customise) = explode(',', $val);

		$current_date = array(	'y' => date('Y'),
								'm' => date('m'),
								'd' => date('d'),
								'h' => date('H'),
								'i' => date('i'),
								's' => date('s'),
								);
		$date1[s] = $current_date[s];
		$date2[s] = '-';

		$date1_string = $this->units_array_to_str($date1);
		$cdate = $this->narrow_current_date($date1, $current_date);
		$cdate_string = $this->units_array_to_str($cdate);

		switch($customise) {
			# After and including the time
			case 'after':
				return (strcmp($cdate_string, $date1_string) >= 0);
				break;
			# Before the time
			case 'before':
				return (strcmp($cdate_string, $date1_string) < 0);
				break;
			case 'within_range':
				$date2_string = $this->units_array_to_str($date2);
				$cdate2 = $this->narrow_current_date($date2, $current_date);
				$cdate2_string = $this->units_array_to_str($cdate2);
				return ((strcmp($cdate_string, $date1_string) >=0) && (strcmp($cdate2_string, $date2_string) < 0));
				break;
		}

		return $test;
	}#end evaluate()

	 #################################################
	# Converts an array with an int for each unit
	# to a  datetime string "yyyy-mm-dd hh:ii:ss"
	function units_array_to_str(&$v) {
		$r = (($v['y'] < 0 || !strlen($v['y']) || $v['y'][0] == '-') ? '----' : sprintf('%04d', $v['y'])).'-'
            .(($v['m'] < 0 || !strlen($v['m']) || $v['m'][0] == '-') ?  '--'  : sprintf('%02d', $v['m'])).'-'
            .(($v['d'] < 0 || !strlen($v['d']) || $v['d'][0] == '-') ?  '--'  : sprintf('%02d', $v['d'])).' '
            .(($v['h'] < 0 || !strlen($v['h']) || $v['h'][0] == '-') ?  '--'  : sprintf('%02d', $v['h'])).':'
            .(($v['i'] < 0 || !strlen($v['i']) || $v['i'][0] == '-') ?  '--'  : sprintf('%02d', $v['i'])).':'
            .(($v['s'] < 0 || !strlen($v['s']) || $v['s'][0] == '-') ?  '--'  : sprintf('%02d', $v['s']));
		return $r;
	}

	function narrow_current_date($date, $current_date) {
		if ($date['y'] == '-') {
			$current_date['y'] = null;
		}
		if ($date['m'] == '-') {
			$current_date['m'] = null;
		}
		if ($date['d'] == '-') {
			$current_date['d'] = null;
		}
		if ($date['h'] == '-') {
			$current_date['h'] = null;
		}
		if ($date['i'] == '-') {
			$current_date['i'] = null;
		}
		if ($date['s'] == '-') {
			$current_date['s'] = null;
		}
		return $current_date;
	}

	 #############################################
	# Prints code to validate the date entered prior to submission
	function print_validate_date() {
		?>
		<script language="javascript" type="text/javascript">
			function validate_date(name) {
				var d = document.getElementById(name + "_d");
				var m = document.getElementById(name + "_m");
				var y = document.getElementById(name + "_y");

				var d = elementValue(d);
				var m = elementValue(m);
				var y = elementValue(y);

				var date_ok = false;

				var is_leap_year = ((y % 4 == 0) || (y % 400 == 0));
				var month_days = new Array(31,28,31,30,31,30,31,31,30,31,31,30,31,30,31);

				if (m == '-' || d == '-') {
					return;
				}

				if ((m == 2) && (d == 29) && (is_leap_year))	{
					date_ok = true;
				}
				else if ((d <= month_days[m-1]) && (d > 0))	{
					date_ok = true;
				}

				if (!date_ok) {
					alert("Invalid date entered");
					obj_d.value = 1;
				}

				return date_ok;
			}
		</script>
		<?
	}

	function create_date($name) {

		$y = date('Y');
		$options = array('-'=>'----');
		for ($i=0; $i<5; $i++) {
			$options[$y] = $y;
			$y++;
		}
		$y = combo_box($name.'_y', $options, date('Y'), 'onChange="validate_date(\''.$name.'\');" id=\''.$name.'_y\'');
		$options = array('-'=>'--');
		
		$options = array('-'=>'--', 1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'May', 6=>'Jun', 7=>'Jul', 8=>'Aug', 9=>'Sep', 10=>'Oct', 11=>'Nov', 12=>'Dec');
		$m = combo_box($name.'_m', $options, (int)date('m'), 'onChange="validate_date(\''.$name.'\');" id=\''.$name.'_m\'');
		
		$options = array('-'=>'--');
		for($i = 1; $i <= 31; $i++) $options[$i] = $i;
		$d = combo_box($name.'_d', $options, (int)date('d'), 'onChange="validate_date(\''.$name.'\');" id=\''.$name.'_d\'');
		
		$options = array('-'=>'--');
		for($i = 0; $i <= 23; $i++) $options[$i] = $i;
		$h = combo_box($name.'_h', $options, (int)date('H'));
		
		$options = array('-'=>'--');
		for($i = 0; $i <= 59; $i++) $options[$i] = $i;
		$i = combo_box($name.'_i', $options, (int)date('i'));
		
		return $d.'/'.$m.'/'.$y.' - '.$h.':'.$i;
	}

	function print_backend() {
		$date1 = $this->create_date('date1');
		$date2 = $this->create_date('date2');
		$this->print_validate_date();
		?>
			<script language="JavaScript" src="<?=squizlib_href('js','form_functions.js');?>"></script>

			<form name="comp_form" onsubmit="save_conditions()">
			<table>
			<tr>
				<td class="bodycopy-popup-heading">Date Format</td>
				<td>Day / Month / Year - Hour : Min </td>
			</tr>
			<tr>
				<td class="bodycopy-popup-heading">Date1</td>
				<td><?=$date1?></td>
			</tr>
			<tr>
				<td class="bodycopy-popup-heading">Customise</td>
				<td>
					<input type=radio name="customise" value="after" checked onclick="javascript: toggle_date2(); return true;"> Show After and Including Date1<br />
					<input type=radio name="customise" value="before" onclick="javascript: toggle_date2(); return true;"> Show Before Date1<br />
					<input type=radio name="customise" value="within_range" onclick="javascript: toggle_date2(); return true;"> Show Within Range Date1-Date2<br />
				</td>
			</tr>
			<tr>
				<td class="bodycopy-popup-heading">Date2</td>
				<td><?=$date2?></td>
			</tr>
			<tr><td colspan="2" align="center"><input type="submit" name="submit" value="Commit"></td></tr>
			</table>
			</form>

			<script language="Javascript">

				get_previous_options();

				function save_conditions() {
					var conditions = new Array();

					conditions[0] = elementValue(document.comp_form.date1_y);
					conditions[1] = elementValue(document.comp_form.date1_m);
					conditions[2] = elementValue(document.comp_form.date1_d);
					conditions[3] = elementValue(document.comp_form.date1_h);
					conditions[4] = elementValue(document.comp_form.date1_i);

					conditions[5] = elementValue(document.comp_form.date2_y);
					conditions[6] = elementValue(document.comp_form.date2_m);
					conditions[7] = elementValue(document.comp_form.date2_d);
					conditions[8] = elementValue(document.comp_form.date2_h);
					conditions[9] = elementValue(document.comp_form.date2_i);

					conditions[10] = elementValue(document.comp_form.customise);

					window.opener.document.main_form.showif_conds.value = conditions;
					window.close();
				}

				function get_previous_options(){
					vals = new String();
					vals = window.opener.document.main_form.showif_conds.value;

					val = new Array();
					val = vals.split(",");

					if(val[0] != "" && val[0] != "undefined"){
						highlightComboElement(document.comp_form.date1_y, val[0]);
					}
					if(val[1] != "" && val[1] != "undefined"){
						highlightComboElement(document.comp_form.date1_m, val[1]);
					}
					if(val[2] != "" && val[2] != "undefined"){
						highlightComboElement(document.comp_form.date1_d, val[2]);
					}
					if(val[3] != "" && val[3] != "undefined"){
						highlightComboElement(document.comp_form.date1_h, val[3]);
					}
					if(val[4] != "" && val[4] != "undefined"){
						highlightComboElement(document.comp_form.date1_i, val[4]);
					}

					if(val[5] != "" && val[5] != "undefined"){
						highlightComboElement(document.comp_form.date2_y, val[5]);
					}
					if(val[6] != "" && val[6] != "undefined"){
						highlightComboElement(document.comp_form.date2_m, val[6]);
					}
					if(val[7] != "" && val[7] != "undefined"){
						highlightComboElement(document.comp_form.date2_d, val[7]);
					}
					if(val[8] != "" && val[8] != "undefined"){
						highlightComboElement(document.comp_form.date2_h, val[8]);
					}
					if(val[9] != "" && val[9] != "undefined"){
						highlightComboElement(document.comp_form.date2_i, val[9]);
					}

					if(val[10] != "" && val[10] != "undefined"){
						checkRadioButton(document.comp_form.customise, val[10]);
					}

					toggle_date2();
				}

				function toggle_date2() {
					var customise_val = elementValue(document.comp_form.customise);
					if (customise_val != 'within_range') {
						document.comp_form.date2_y.disabled = 1;
						document.comp_form.date2_m.disabled = 1;
						document.comp_form.date2_d.disabled = 1;
						document.comp_form.date2_h.disabled = 1;
						document.comp_form.date2_i.disabled = 1;
						document.comp_form.date2_s.disabled = 1;
					} else {
						document.comp_form.date2_y.disabled = 0;
						document.comp_form.date2_m.disabled = 0;
						document.comp_form.date2_d.disabled = 0;
						document.comp_form.date2_h.disabled = 0;
						document.comp_form.date2_i.disabled = 0;
						document.comp_form.date2_s.disabled = 0;
					}
				}
			</script>
		<?
	}

} #end class
?>