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
// | $RCSfile: field.php,v $ - $Revision: 1.20 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
cp_header(' &raquo; '.iif($module == 'book', 'Address Book', 'Profile').' Fields');
cp_nav('userfield');

// ############################################################################
// Stores output in $into and clears output buffer
function store_field(&$into) {
	$into = ob_get_contents();
	ob_clean();
	getclass();
}

// ############################################################################
// Set the default cmd
default_var($cmd, 'modify');
default_var($module, 'user');

// ############################################################################
// Define field names and demos
$_fields = array(
	'text' => 'Single-line Text Box (input)',
	'textarea' => 'Multi-line Text Box (textarea)',
	'select' => 'Single-selection Menu (select)',
	'multiselect' => 'Multi-selection Menu (select multiple)',
	'radio' => 'Single-selection Radio Buttons (radio)',
	'checkbox' => 'Multi-selection Checkboxes (checkbox)'
);
$_field_demo = array(
	'text' => '<input type="text" class="bginput" value="Single-line Text Box" />',
	'textarea' => '<textarea rows="3" cols="25">Multi-line Text Box</textarea>',
	'select' => '<select size="1"><option>Single</option><option>Selection</option><option>Menu</option></select>',
	'multiselect' => '<select multiple="multiple" size="3"><option>Multi</option><option>Selection</option><option>Menu</option></select>',
	'radio' => '<input type="radio" name="radioexample" /> Single-selection<br /><input type="radio" name="radioexample" /> Radio Buttons',
	'checkbox' => '<input type="checkbox" name="radioexample" /> Multi-selection<br /><input type="checkbox" name="radioexample" /> Checkboxes',
);

// ############################################################################
// Remove custom field
if ($_POST['cmd'] == 'kill') {
	$field = getinfo('field', $fieldid);

	$DB_site->query("
		DELETE FROM hive_field
		WHERE fieldid = $fieldid
	");
	$DB_site->query("
		DELETE FROM hive_fieldinfo
		WHERE fieldid = $fieldid
	");

	adminlog($fieldid, true);
	cp_redirect('The field has been removed.', "field.php?cmd=modify&module=$field[module]");
}

// ############################################################################
// Remove custom field
if ($cmd == 'remove') {
	$field = getinfo('field', $fieldid);

	adminlog($fieldid);
	startform('field.php', 'kill', 'Are you sure you want to remove this field?');
	starttable('Remove field "'.$field['title'].'" (ID: '.$fieldid.')');
	textrow('Are you <b>sure</b> you want to remove this field? This procedure <b>cannot</b> be reveresed!<br />All information users have entered for this field will be <b>irreversibly</b> deleted.');
	hiddenfield('fieldid', $fieldid);
	endform('Remove Field', '', 'Go Back');
	endtable();
}

// ############################################################################
// Create a new field or update an existing one
if ($_POST['cmd'] == 'update') {
	intme($field['display']);
	intme($field['required']);
	intme($field['min']);
	intme($field['max']);
	intme($field['width']);
	intme($field['height']);
	intme($field['signup']);

	$choices = array();
	$replace = 'choices';
	if ($type != 'text' and $type != 'textarea') {
		$databits = preg_split("#\r?\n#", $field['data']);
		foreach ($databits as $databit) {
			if (empty($databit)) {
				continue;
			}
			$choiceinfo = explode(' ', $databit, 3);
			$choices[$choiceinfo[0]] = array('default' => $choiceinfo[1], 'name' => $choiceinfo[2]);
		}

		// If choices were removed we need to update user's data
		if ($fieldid != 0) {
			$oldfield = getinfo('field', $fieldid);
			$oldfield['data_array'] = @unserialize($oldfield['data']);
			foreach ($oldfield['data_array'] as $choiceid => $choiceinfo) {
				if (!array_key_exists($choiceid, $choices)) {
					$replace = "REPLACE($replace, ',$choiceid,', ',')";
				}
			}
		}
	}
	$field['data'] = serialize($choices);

	if ($fieldid == 0) {
		$DB_site->auto_query('field', $field);
		$fieldid = $DB_site->insert_id();

		adminlog($fieldid, true);
		cp_redirect('The field has been created.', "field.php?cmd=modify&module=$field[module]");
	} else {
		$DB_site->auto_query('field', $field, "fieldid = $fieldid");
		if ($replace != 'choices') {
			$DB_site->query("
				UPDATE hive_fieldinfo
				SET choices = $replace
				WHERE fieldid = $fieldid
			");
			// Clean users' cache
			$DB_site->query('
				UPDATE hive_user
				SET fieldcache = ""
			');
		}
		adminlog($fieldid, true);
		cp_redirect('The field has been updated.', "field.php?cmd=modify&module=$field[module]");
	}
}

// ############################################################################
// Create a new field or update an existing one
if ($cmd == 'edit') {
	echo '<script language="JavaScript" src="../misc/field_choices.js"></script>';

	$field = getinfo('field', $fieldid, false, false);

	echo "<form action=\"field.php\" method=\"post\" name=\"form\"".iif($type != 'text' and $type != 'textarea' and $field['type'] != 'text' and $field['type'] != 'textarea', ' onSubmit="return saveDate(this.choices);"').">\n";
	echo "<input type=\"hidden\" name=\"cmd\" value=\"update\" />\n";

	if ($field === false) {
		$high_count = $DB_site->get_field('
			SELECT MAX(display) AS display
			FROM hive_field
			WHERE module = "'.addslashes($module).'"
		');
		$field = array(
			'fieldid' => 0,
			'type' => $type,
			'module' => $module,
			'display' => $high_count + 1,
			'required' => false,
			'signup' => true,
			'min' => 0,
			'max' => 0,
			'perline' => 5,
			'width' => 150,
		);
		if ($type == 'textarea') {
			$field['height'] = 100;
		} else {
			$field['height'] = 5;
		}
		$optionSize = 5;
		$bigID = 0;
		$defaults = array();
		$fieldid = 0;
		starttable('Create new custom field');
	} else {
		$type = $field['type'];
		$module = $field['module'];
		$bigID = 0;
		$defaults = array();
		$optionSize = 1;
		if (is_array($field['data_array'] = @unserialize($field['data']))) {
			foreach ($field['data_array'] as $choiceid => $choiceinfo) {
				if (trim($choiceinfo['name']) == '') {
					continue;
				}
				if ($choiceinfo['default']) {
					$defaults[] = $choiceid;
				}
				$field['options'] .= "<option value=\"$choiceid\" style=\"color: ".iif($choiceinfo['default'], 'red', 'black')."\">$choiceinfo[name]".iif($choiceinfo['default'], ' (default)')."</option>\n";
				if ($choiceid > $bigID) {
					$bigID = $choiceid;
				}
				$optionSize++;
			}
		}
		starttable('Update custom field "'.$field['title'].'" (ID: '.$fieldid.')');
	}

	// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	// Create option fields
	ob_start();
	// ++++++++++++++++++++++
	inputfield('Default value:<br /><span class="cp_small">The default value for this field when the user enters nothing.<br />Please note that using this will render the "Required" option above useless.</span>', 'field[defvalue]', $field['defvalue']);
	store_field($def_field_text);
	// ++++++++++++++++++++++
	textarea('Default value:<br /><span class="cp_small">The default value for this field when the user enters nothing.<br />Please note that using this will render the "Required" option above useless.</span>', 'field[defvalue]', $field['defvalue']);
	store_field($def_field_area);
	// ++++++++++++++++++++++
	inputfield('Minimum length of value:<br /><span class="cp_small">Set to 0 to have no minimum.</span>', 'field[min]', $field['min']);
	store_field($min_field_text);
	// ++++++++++++++++++++++
	inputfield('Maximum length of value:<br /><span class="cp_small">Set to 0 to have no maximum.</span>', 'field[max]', $field['max']);	
	store_field($max_field_text);
	// ++++++++++++++++++++++
	inputfield('Field width:<br /><span class="cp_small">The visual width of the field, in pixels.</span>', 'field[width]', $field['width']);
	store_field($width_field);
	// ++++++++++++++++++++++
	inputfield('Field height:<br /><span class="cp_small">The visual height of the field, in pixels.</span>', 'field[height]', $field['height']);
	store_field($height_field);
	// ++++++++++++++++++++++
	inputfield('Field height:<br /><span class="cp_small">The number of choices to display before scrolling.</span>', 'field[height]', $field['height']);
	store_field($height_field_choice);
	// ++++++++++++++++++++++
	inputfield('Minimum number of choices:<br /><span class="cp_small">Set to 0 to have no minimum.</span>', 'field[min]', $field['min']);
	store_field($min_field_choice);
	// ++++++++++++++++++++++
	inputfield('Maximum number of choices:<br /><span class="cp_small">Set to 0 to have no maximum.</span>', 'field[max]', $field['max']);	
	store_field($max_field_choice);
	// ++++++++++++++++++++++
	inputfield('Options per line:<br /><span class="cp_small">The number of available options to be displayed on each line.</span>', 'field[perline]', $field['perline']);
	store_field($perline_field);
	// ++++++++++++++++++++++
	yesno('Allow custom value:<br /><span class="cp_small">Can the user enter a custom value, in addition to or instead of picking one of the choices you provided?</span>', 'field[custom]', $field['custom']);
	store_field($custom_field);
	// ++++++++++++++++++++++
	tablerow(array('Available choices:<br /><span class="cp_small">Edit the list of options users will be able to select from.<br />Click the New Choice button to add a new choice, and<br />choose a default choice with the Make Default button.</span><br /><br />
<input type="button" class="button" name="new" value="New Choice" onClick="addChoice(this.form);" style="width: 150px;" />
<input type="button" class="button" name="makedefault" value="Make Default" disabled="disabled" onClick="makeDefault(this.form);" style="width: 150px;" /><br /><br />
<input type="button" class="button" name="remove" value="Remove Choice" disabled="disabled" onClick="removeChoice(this.form);" style="width: 150px;" />
<input type="button" class="button" name="rename" value="Rename Choice" disabled="disabled" onClick="renameChoice(this.form.choices);" style="width: 150px;" />', '<select name="choices" style="width: 230px;" size="'.$optionSize.'" onChange="updateDisabled(this.form);">
'.$field['options'].'
</select><br /><br />
<input type="button" class="button" style="width: 110px;" name="up" value="Move Up" disabled="disabled" onClick="moveUp(this.form.choices);" />
<input type="button" class="button" style="width: 110px;" name="down" value="Move Down" disabled="disabled" onClick="moveDown(this.form.choices);" />'), true, true);
	store_field($choices_field);
	// ++++++++++++++++++++++
	ob_end_clean();
	// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

	textrow('<b>Type of field: '.$_fields["$type"].'</b><br />&nbsp;');
	getclass();
	inputfield('Field title:', 'field[title]', $field['title']);
	getclass();
	textarea('Description of field:', 'field[description]', $field['description']);
	getclass();
	inputfield('Display order:<br /><span class="cp_small">All fields will be displayed in this order. Set to 0 to hide this field.</span>', 'field[display]', $field['display']);

	tablehead(array('Field options'), 2);

	// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	if ($type == 'select') {
?><script language="JavaScript">
<!--

var bigID = <?php echo $bigID; ?>;
var defaults = new Array("0", "<?php echo implode('","', $defaults); ?>");
var onlyOneDef = true;

// -->
</script><?php
		echo $choices_field;
		echo $custom_field;
		echo $width_field;
	}

	// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	if ($type == 'multiselect') {
?><script language="JavaScript">
<!--

var bigID = <?php echo $bigID; ?>;
var defaults = new Array("0", "<?php echo implode('","', $defaults); ?>");
var onlyOneDef = false;

// -->
</script><?php
		echo $choices_field;
		echo $min_field_choice;
		echo $max_field_choice;
		echo $height_field_choice;
	}

	// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	if ($type == 'radio') {
?><script language="JavaScript">
<!--

var bigID = <?php echo $bigID; ?>;
var defaults = new Array("0", "<?php echo implode('","', $defaults); ?>");
var onlyOneDef = true;

// -->
</script><?php
		echo $choices_field;
		echo $perline_field;
		echo $custom_field;
		echo $width_field;
	}

	// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	if ($type == 'checkbox') {
?><script language="JavaScript">
<!--

var bigID = <?php echo $bigID; ?>;
var defaults = new Array("0", "<?php echo implode('","', $defaults); ?>");
var onlyOneDef = false;

// -->
</script><?php
		echo $choices_field;
		echo $min_field_choice;
		echo $max_field_choice;
		echo $perline_field;
	}

	// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	getclass();
	yesno('Required field:<br /><span class="cp_small">Enable this to require the user to fill this field in.</span>', 'field[required]', $field['required']);
	getclass();
	//textarea('Error message:<br /><span class="cp_small">If the users enters an invalid value for this field, this (optional) message will be displayed along with the normal system-error message..</span>', 'field[error]', $field['error']);
	//getclass();
	if ($module == 'user') {
		yesno('Display on sign-up:<br /><span class="cp_small">If this is turned on, users will be asked to fill this field in when signing up.</span>', 'field[signup]', $field['signup']);
		getclass();
	}

	// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	if ($type == 'text') {
		echo $def_field_text;
		echo $min_field_text;
		echo $max_field_text;
		echo $width_field;
	}

	// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	if ($type == 'textarea') {
		echo $def_field_area;
		echo $min_field_text;
		echo $max_field_text;
		echo $width_field;
		echo $height_field;
	}

	// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	hiddenfield('fieldid', $fieldid);
	hiddenfield('field[type]', $type);
	hiddenfield('field[module]', $module);
	hiddenfield('field[data]');
	if ($fieldid == 0) {
		endform('Create custom field');
	} else {
		endform('Update custom field');
	}
	endtable();
}

// ############################################################################
// List the fields
if ($cmd == 'modify') {
	adminlog();

	$fields = $DB_site->query('
		SELECT *
		FROM hive_field
		WHERE module = "'.addslashes($module).'"
		ORDER BY display
	');
	if ($DB_site->num_rows($fields) > 0) {
		startform('field.php', 'display');
		hiddenfield('module', $module);
	}
	starttable('', '550');
	$cells = array(
		'Order',
		'Title',
		'Type of Field',
		'Code Name',
		'Options'
	);
	tablehead($cells);
	if ($DB_site->num_rows($fields) < 1) {
		textrow('No fields', count($cells), 1);
		emptyrow(count($cells));
	} else {
		while ($field = $DB_site->fetch_array($fields)) {
			$cells = array(
				'<input type="text" name="displays['.$field['fieldid'].']" value="'.$field['display'].'" class="bginput" size="3" />',
				"<a href=\"field.php?cmd=edit&fieldid=$field[fieldid]\">$field[title]</a>",
				preg_replace('#\W\([^\)]*\)#', '', $_fields["$field[type]"]),
				"\$hiveuser[field$field[fieldid]]",
				makelink('edit', "field.php?cmd=edit&fieldid=$field[fieldid]"). '-' . makelink('remove', "field.php?cmd=remove&fieldid=$field[fieldid]")
			);
			tablerow($cells);
		}
		endform('Update Field Orders', '', '', '', count($cells));
	}
	endtable();

?><script language="JavaScript">
<!--

var field_infos = new Array();
<?php
foreach ($_field_demo as $type => $info) {
	echo "field_infos['$type'] = \"".addslashes($info)."\";\n";
}
?>

function updateFieldInfo(type) {
	getElement('field_info').innerHTML = field_infos[type];
}

// -->
</script><?php

	echo '<br /><br />';
	startform('field.php', 'edit');
	starttable('Create new custom field', '550');
	hiddenfield('module', $module);
	selectbox('Select type of field:<br /><br /><span class="cp_small" id="field_info"></span>', 'type', $_fields, -1, false, '', 6, ' onChange="updateFieldInfo(this.options[this.selectedIndex].value);" onDblClick="this.form.submit();"');
	endform('Proceed...');
	endtable();

	echo '<br /><br />';
	starttable('', '550');
	textrow('A <b>custom field</b> is an input field you define and users can fill in in their profile. This can be used to collect additional information from your users, which is not available in the built-in fields HiveMail&trade; has.');
	endtable();
}

// ############################################################################
// Add new field menu
if ($cmd == 'add') {
?><script language="JavaScript">
<!--

var field_infos = new Array();
<?php
foreach ($_field_demo as $type => $info) {
	echo "field_infos['$type'] = \"".addslashes($info)."\";\n";
}
?>

function updateFieldInfo(type) {
	getElement('field_info').innerHTML = field_infos[type];
}

// -->
</script><?php

	startform('field.php', 'edit');
	starttable('Create new custom field', '450');
	hiddenfield('module', $module);
	selectbox('Select type of field:<br /><br /><span class="cp_small" id="field_info"></span>', 'type', $_fields, -1, false, '', 6, ' onChange="updateFieldInfo(this.options[this.selectedIndex].value);" onDblClick="this.form.submit();"');
	endform('Proceed...');
	endtable();
}

// ############################################################################
// Update display orders
if ($_POST['cmd'] == 'display') {
	if (!is_array($displays)) {
		adminlog(0, false);
		cp_error('Invalid information specified.');
	} else {
		foreach ($displays as $fieldid => $display) {
			$DB_site->query('
				UPDATE hive_field
				SET display = '.intval($display).'
				WHERE fieldid = '.intval($fieldid).'
			');
			adminlog($fieldid, true);
		}
		cp_redirect('The fields have been updated.', "field.php?cmd=modify&module=$module");
	}
}

cp_footer();
?>