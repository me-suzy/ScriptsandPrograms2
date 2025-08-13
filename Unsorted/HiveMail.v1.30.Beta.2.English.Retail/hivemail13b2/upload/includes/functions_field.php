<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | $RCSfile: functions_field.php,v $ - $Revision: 1.12 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Processes input for fields
function process_field_value($field, $value, $custom, &$errcode, &$errinfo) {
	$field['data_array'] = @unserialize($field['data']);
	$errcode = '';

	intme($field['min']);
	intme($field['max']);
	intme($field['width']);
	intme($field['height']);

	switch ($field['type']) {
		case 'text':
		case 'textarea':
			$value = trim($value);

			if ($field['required'] and empty($value)) {
				$errcode = 'required_empty';
				return false;
			} elseif ($field['min'] > 0 and strlen($value) < $field['min']) {
				$errcode = 'below_min_text';
				$errinfo = strlen($value);
				return false;
			} elseif ($field['max'] > 0 and strlen($value) > $field['max']) {
				$errcode = 'over_max_text';
				$errinfo = strlen($value);
				return false;
			} else {
				return $value;
			}
			break;

		case 'select':
		case 'radio':
			intme($value);
			if ($field['custom']) {
				$custom = trim($custom);
			} else {
				$custom = '';
			}

			if ($field['required'] and !empty($custom) and ($value == 0 or !isset($field['data_array'][$value]))) {
				$errcode = 'required_empty';
				return false;
			} else {
				return iif(!empty($custom), $custom, $value);
			}
			break;

		case 'multiselect':
		case 'checkbox':
			if (!is_array($value)) {
				if ($field['required']) {
					$errcode = 'required_empty';
					return false;
				} else {
					$value = array();
				}
			}

			foreach ($value as $choicekey => $choiceid) {
				if (!isset($field['data_array'][$choiceid])) {
					unset($value[$choicekey]);
				}
			}

			if ($field['required'] and empty($value)) {
				$errcode = 'required_empty';
				return false;
			} elseif ($field['min'] > 0 and count($value) < $field['min']) {
				$errcode = 'below_min_options';
				$errinfo = count($value);
				return false;
			} elseif ($field['max'] > 0 and count($value) > $field['max']) {
				$errcode = 'over_max_options';
				$errinfo = count($value);
				return false;
			} else {
				return $value;
			}
			break;
	}
}

// ############################################################################
// Creates HTML code for the field
function make_field_html($field, $current = null) {
	global $on_submit;

	$field['data_array'] = @unserialize($field['data']);
	$fieldcode = '';

	intme($field['width']);
	intme($field['height']);
	intme($field['min']);
	intme($field['max']);

	// ########################################################################
	if ($field['type'] == 'text') {
		if ($current === false) {
			$field['defvalue'] = '';
		} else {
			if ($current !== null) {
				$field['defvalue'] = $current;
			}
			$field['defvalue'] = htmlchars($field['defvalue']);
		}
		$maxlength = iif($field['max'] > 0, "maxlength=\"$field[max]\"");
		eval(makeeval('fieldcode', 'options_personal_fields_text'));
	}

	// ########################################################################
	if ($field['type'] == 'textarea') {
		if ($current === false) {
			$field['defvalue'] = '';
		} else {
			if ($current !== null) {
				$field['defvalue'] = $current;
			}
			$field['defvalue'] = htmlchars($field['defvalue']);
		}
		eval(makeeval('fieldcode', 'options_personal_fields_textarea'));
	}

	// ########################################################################
	if ($field['type'] == 'select') {
		if ($field['custom']) {
			$onchange = 'onChange="if (this.options[this.selectedIndex].value != -1) this.form.fields_custom_'.$field['fieldid'].'.value = \'\';"';
			$on_submit .= 'if (this.fields_'.$field['fieldid'].'.options[this.selectedIndex].value != -1) this.fields_custom_'.$field['fieldid'].'.value = \'\'; ';
		} else {
			$onchange = '';
		}

		$default_index = 0;
		$select_index = 1;
		$options = '';
		$foundcurrent = false;
		foreach ($field['data_array'] as $choiceid => $choiceinfo) {
			if (trim($choiceinfo['name']) == '') {
				continue;
			}
			if ($current === false) {
				$choiceinfo['default'] = false;
			} else {
				if ($current !== null) {
					$choiceinfo['default'] = ($choiceid == $current);
					$foundcurrent = ($foundcurrent or $choiceinfo['default']);
				}
			}
			$options .= "<option value=\"$choiceid\"".iif($choiceinfo['default'], ' selected="selected"').">".htmlchars($choiceinfo['name'])."</option>\n";
			if ($choiceinfo['default']) {
				$default_index = $select_index;
			}
			$select_index++;
		}
		if ($current !== null and $current !== false and !$foundcurrent) {
			$customvalue = $current;
			$otherselected = 'selected="selected"';
		} else {
			$otherselected = $customvalue = '';
		}

		if ($field['custom']) {
			$on_submit .= 'this.fields_'.$field['fieldid'].'.selectedIndex = ((this.fields_custom_'.$field['fieldid'].'.value != \'\') ? ('.$select_index.') : ('.$default_index.')); ';
		}

		eval(makeeval('fieldcode', 'options_personal_fields_select'));
	}

	// ########################################################################
	if ($field['type'] == 'multiselect') {
		$total = 0;
		$options = '';
		foreach ($field['data_array'] as $choiceid => $choiceinfo) {
			if (trim($choiceinfo['name']) == '') {
				continue;
			}
			if ($current === false) {
				$choiceinfo['default'] = false;
			} else {
				if ($current !== null) {
					$choiceinfo['default'] = in_array($choiceid, $current);
				}
			}
			if ($choiceinfo['default']) {
				$total++;
			}
			$options .= "<option value=\"$choiceid\"".iif($choiceinfo['default'], ' selected="selected"')." />".htmlchars($choiceinfo['name'])."</option>\n";
		}

		eval(makeeval('fieldcode', 'options_personal_fields_multiselect'));
	}

	// ########################################################################
	if ($field['type'] == 'radio') {
		$count = $field['perline'];
		$options = '';
		$foundcurrent = false;
		foreach ($field['data_array'] as $choiceid => $choiceinfo) {
			if (trim($choiceinfo['name']) == '') {
				continue;
			}
			if ($current === false) {
				$choiceinfo['default'] = false;
			} else {
				if ($current !== null) {
					$choiceinfo['default'] = ($choiceid == $current);
					$foundcurrent = ($foundcurrent or $choiceinfo['default']);
				}
			}
			$checked = iif($choiceinfo['default'], 'checked="checked"');
			$choiceinfo['name'] = htmlchars($choiceinfo['name']);
			eval(makeeval('options', 'options_personal_fields_radio_option', 1));
			$count++;
			if ($count%$field['perline'] == 0) {
				$options .= '<br />';
			}
			if ($choiceinfo['default']) {
				$default_choice = $choiceid;
			}
		}
		if ($current !== null and $current !== false and !$foundcurrent) {
			$customvalue = $current;
			$otherchecked = 'checked="checked"';
		} else {
			$otherchecked = $customvalue = '';
		}

		if ($field['custom']) {
			$linebreak = iif($count%$field['perline'] != 0, '<br />');
			$on_submit .= 'if (this.fields_custom_'.$field['fieldid'].'.value != \'\') { this.default_radio_'.$field['fieldid'].'.checked = true; } else { this.fields'.$field['fieldid'].'choice'.$default_choice.'.checked = true; } ';
		}

		eval(makeeval('fieldcode', 'options_personal_fields_radio'));
	}

	// ########################################################################
	if ($field['type'] == 'checkbox') {
		$total = 0;
		$count = $field['perline'];
		$options = '';
		foreach ($field['data_array'] as $choiceid => $choiceinfo) {
			if (trim($choiceinfo['name']) == '') {
				continue;
			}
			if ($current === false) {
				$choiceinfo['default'] = false;
			} else {
				if ($current !== null) {
					$choiceinfo['default'] = in_array($choiceid, $current);
				}
			}
			if ($choiceinfo['default']) {
				$total++;
			}
			$checked = iif($choiceinfo['default'], 'checked="checked"');
			$choiceinfo['name'] = htmlchars($choiceinfo['name']);
			eval(makeeval('options', 'options_personal_fields_checkbox_option', 1));
			$count++;
			if ($count%$field['perline'] == 0) {
				$options .= '<br />';
			}
		}

		eval(makeeval('fieldcode', 'options_personal_fields_checkbox'));
	}

	return $fieldcode;
}

?>