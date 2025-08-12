<?php

// $Id: view.php 255 2005-11-28 06:52:32Z ryan $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2005, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

/*
The Website Baker Project would like to thank Rudolph Lartey <www.carbonect.com>
for his contributions to this module - adding extra field types
*/

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

// Function for generating an optionsfor a select field
function make_option(&$n) {
	// start option group if it exists
	if (substr($n,0,2) == '[=') {
	 	$n = '<optgroup label="'.substr($n,2,strlen($n)).'">';
	} elseif ($n == ']') {
		$n = '</optgroup>';
	} else {
		$n = '<option value="'.$n.'">'.$n.'</option>';
	}
}

// Function for generating a checkbox
function make_checkbox(&$n, $idx, $params) {
	$field_id = $params[0];
	$seperator = $params[1];
	//$n = '<input class="field_checkbox" type="checkbox" id="'.$n.'" name="field'.$field_id.'" value="'.$n.'">'.'<font class="checkbox_label" onclick="javascript: document.getElementById(\''.$n.'\').checked = !document.getElementById(\''.$n.'\').checked;">'.$n.'</font>'.$seperator;
	$n = '<input class="field_checkbox" type="checkbox" id="'.$n.'" name="field'.$field_id.'['.$idx.']" value="'.$n.'">'.'<font class="checkbox_label" onclick="javascript: document.getElementById(\''.$n.'\').checked = !document.getElementById(\''.$n.'\').checked;">'.$n.'</font>'.$seperator;
}

// Function for generating a radio button
function make_radio(&$n, $idx, $params) {
	$field_id = $params[0];
	$group = $params[1];
	$seperator = $params[2];
	$n = '<input class="field_radio" type="radio" id="'.$n.'" name="field'.$field_id.'" value="'.$n.'">'.'<font class="radio_label" onclick="javascript: document.getElementById(\''.$n.'\').checked = true;">'.$n.'</font>'.$seperator;
}

// Work-out if the form has been submitted or not
if($_POST == array()) {

?>
<style type="text/css">
.required {
	color: #FF0000;
}
.field_title {
	font-size: 12px;
	width: 100px;
	vertical-align: top;
	text-align:right;
}
.textfield {
	font-size: 12px;
	width: 200px;
}
.textarea {
	font-size: 12px;
	width: 90%;
	height: 100px;
}
.field_heading {
	font-size: 12px;
	font-weight: bold;
	border-bottom-width: 2px;
	border-bottom-style: solid;
	border-bottom-color: #666666;
	padding-top: 10px;
	color: #666666;
}
.select {
	font-size: 12px;
}
.checkbox_label {
	font-size: 11px;
	cursor: pointer;
}
.radio_label {
	font-size: 11px;
	cursor: pointer;
}
.email {
	font-size: 12px;
	width: 200px;
}
</style>
<?php

// Get settings
$query_settings = $database->query("SELECT header,field_loop,footer,use_captcha FROM ".TABLE_PREFIX."mod_form_settings WHERE section_id = '$section_id'");
if($query_settings->numRows() > 0) {
	$fetch_settings = $query_settings->fetchRow();
	$header = str_replace('{WB_URL}',WB_URL,$fetch_settings['header']);
	$field_loop = $fetch_settings['field_loop'];
	$footer = str_replace('{WB_URL}',WB_URL,$fetch_settings['footer']);
	$use_captcha = $fetch_settings['use_captcha'];
} else {
	$header = '';
	$field_loop = '';
	$footer = '';
}

// Add form starter code
?>
<form name="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<?php

// Print header
echo $header;

// Get list of fields
$query_fields = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_form_fields WHERE section_id = '$section_id' ORDER BY position ASC");
if($query_fields->numRows() > 0) {
	while($field = $query_fields->fetchRow()) {
		// Set field values
		$field_id = $field['field_id'];
		$value = $field['value'];
		// Print field_loop after replacing vars with values
		$vars = array('{TITLE}', '{REQUIRED}');
		$values = array($field['title']);
		if($field['required'] == 1) {
			$values[] = '<font class="required">*</font>';
		} else {
			$values[] = '';
		}
		if($field['type'] == 'textfield') {
			$vars[] = '{FIELD}';
			$values[] = '<input type="text" name="field'.$field_id.'" id="field'.$field_id.'" maxlength="'.$field['extra'].'" value="'.$value.'" class="textfield" />';
		} elseif($field['type'] == 'textarea') {
			$vars[] = '{FIELD}';
			$values[] = '<textarea name="field'.$field_id.'" id="field'.$field_id.'" class="textarea">'.$value.'</textarea>';
		} elseif($field['type'] == 'select') {
			$vars[] = '{FIELD}';
			$options = explode(',', $value);
			array_walk($options, 'make_option');
			$field['extra'] = explode(',',$field['extra']); 
			$values[] = '<select name="field'.$field_id.'[]" id="field'.$field_id.'" size="'.$field['extra'][0].'" '.$field['extra'][1].' class="select">'.implode($options).'</select>';
		} elseif($field['type'] == 'heading') {
			$vars[] = '{FIELD}';
			$values[] = '<input type="hidden" name="field'.$field_id.'" id="field'.$field_id.'" value="===['.$field['title'].']===" />';
			$tmp_field_loop = $field_loop;		// temporarily modify the field loop template
			$field_loop = $field['extra'];
		} elseif($field['type'] == 'checkbox') {
			$vars[] = '{FIELD}';
			$options = explode(',', $value);
			array_walk($options, 'make_checkbox',array($field_id,$field['extra']));
			$values[] = implode($options);
		} elseif($field['type'] == 'radio') {
			$vars[] = '{FIELD}';
			$options = explode(',', $value);
			array_walk($options, 'make_radio',array($field_id,$field['title'],$field['extra']));
			$values[] = implode($options);
		} elseif($field['type'] == 'email') {
			$vars[] = '{FIELD}';
			$values[] = '<input type="text" name="field'.$field_id.'" id="field'.$field_id.'" maxlength="'.$field['extra'].'" class="email" />';
		}
		if($field['type'] != '') {
			echo str_replace($vars, $values, $field_loop);
		}
		if (isset($tmp_field_loop)) $field_loop = $tmp_field_loop;
	}
}

// Captcha
if($use_captcha) {
	$_SESSION['captcha'] = '';
	for($i = 0; $i < 5; $i++) {
		$_SESSION['captcha'] .= rand(0,9);
	}
	?><tr><td class="field_title"><?php echo $TEXT['VERIFICATION']; ?>:</td><td>
	<table cellpadding="2" cellspacing="0" border="0">
	<tr><td><img src="<?php echo WB_URL; ?>/include/captcha.php" alt="Captcha" /></td>
	<td><input type="text" name="captcha" maxlength="5" /></td>
	</tr></table>
	</td></tr>
	<?php
}

// Print footer
echo $footer;

// Add form end code
?>
</form>
<?php

} else {
	
	// Submit form data
	// First start message settings
	$query_settings = $database->query("SELECT email_to,email_from,email_subject,success_message,max_submissions,stored_submissions FROM ".TABLE_PREFIX."mod_form_settings WHERE section_id = '$section_id'");
	if($query_settings->numRows() > 0) {
		$fetch_settings = $query_settings->fetchRow();
		$email_to = $fetch_settings['email_to'];
		$email_from = $fetch_settings['email_from'];
		if(substr($email_from, 0, 5) == 'field') {
			// Set the email from field to what the user entered in the specified field
			$email_from = $wb->add_slashes($_POST[$email_from]);
		}
		$email_subject = $fetch_settings['email_subject'];
		$success_message = $fetch_settings['success_message'];
		$max_submissions = $fetch_settings['max_submissions'];
		$stored_submissions = $fetch_settings['stored_submissions'];
	} else {
		exit($TEXT['UNDER_CONSTRUCTION']);
	}
	$email_body = '';
	
	// Create blank "required" array
	$required = array();
	
	// Loop through fields and add to message body
	// Get list of fields
	$query_fields = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_form_fields WHERE section_id = '$section_id' ORDER BY position ASC");
	if($query_fields->numRows() > 0) {
		while($field = $query_fields->fetchRow()) {
			// Add to message body
			if($field['type'] != '') {
				if(!empty($_POST['field'.$field['field_id']])) {
					if($field['type'] == 'email' AND $admin->validate_email($_POST['field'.$field['field_id']]) == false) {
						$email_error = $MESSAGE['USERS']['INVALID_EMAIL'];
					}
					if($field['type'] == 'heading') {
						$email_body .= $_POST['field'.$field['field_id']]."\n\n";
					} elseif (!is_array($_POST['field'.$field['field_id']])) {
						$email_body .= $field['title'].': '.$_POST['field'.$field['field_id']]."\n\n";
					} else {
						$email_body .= $field['title'].": \n";
						foreach ($_POST['field'.$field['field_id']] as $k=>$v) {
							$email_body .= $v."\n";
						}
						$email_body .= "\n";
					}
				} elseif($field['required'] == 1) {
					$required[] = $field['title'];
				}
			}
		}
	}
	
	// Captcha
	if(extension_loaded('gd') AND function_exists('imageCreateFromJpeg')) { /* Make's sure GD library is installed */
		if(isset($_POST['captcha']) AND $_POST['captcha'] != ''){
			// Check for a mismatch
			if(!isset($_POST['captcha']) OR !isset($_SESSION['captcha']) OR $_POST['captcha'] != $_SESSION['captcha']) {
				$captcha_error = $MESSAGE['MOD_FORM']['INCORRECT_CAPTCHA'];
			}
		} else {
			$captcha_error = $MESSAGE['MOD_FORM']['INCORRECT_CAPTCHA'];
		}
	}
	if(isset($_SESSION['catpcha'])) { unset($_SESSION['captcha']); }
	
	// Addslashes to email body - proposed by Icheb in topic=1170.0
	// $email_body = $wb->add_slashes($email_body);
	
	// Check if the user forgot to enter values into all the required fields
	if($required != array()) {
		if(!isset($MESSAGE['MOD_FORM']['REQUIRED_FIELDS'])) {
			echo 'You must enter details for the following fields';
		} else {
			echo $MESSAGE['MOD_FORM']['REQUIRED_FIELDS'];
		}
		echo ':<br /><ul>';
		foreach($required AS $field_title) {
			echo '<li>'.$field_title;
		}
		if(isset($email_error)) { echo '<li>'.$email_error.'</li>'; }
		if(isset($captcha_error)) { echo '<li>'.$captcha_error.'</li>'; }
		echo '</ul><a href="javascript: history.go(-1);">'.$TEXT['BACK'].'</a>';
		
	} else {
		
		if(isset($email_error)) {
			echo '<br /><ul>';
			echo '<li>'.$email_error.'</li>';
			echo '</ul><a href="javascript: history.go(-1);">'.$TEXT['BACK'].'</a>';
		} elseif(isset($captcha_error)) {
			echo '<br /><ul>';
			echo '<li>'.$captcha_error.'</li>';
			echo '</ul><a href="javascript: history.go(-1);">'.$TEXT['BACK'].'</a>';
		} else {
		
		// Check how many times form has been submitted in last hour
		$query_submissions = $database->query("SELECT submission_id FROM ".TABLE_PREFIX."mod_form_submissions WHERE submitted_when >= '3600'");
		if($query_submissions->numRows() > $max_submissions) {
			// Too many submissions so far this hour
			echo $MESSAGE['MOD_FORM']['EXCESS_SUBMISSIONS'];
			$success = false;
		} else {
			// Now send the email
			if($email_to != '') {
				if($email_from != '') {
					if(mail($email_to,$email_subject,str_replace("\n", '', $email_body),"From: ".$email_from)) { $success = true; }
				} else {
					if(mail($email_to,$email_subject,str_replace("\n", '', $email_body))) { $success = true; }
				}
			}				
			// Write submission to database
			if(isset($admin) AND $admin->get_user_id() > 0) {
				$admin->get_user_id();
			} else {
				$submitted_by = 0;
			}
			$email_body = $wb->add_slashes($email_body);
			$database->query("INSERT INTO ".TABLE_PREFIX."mod_form_submissions (page_id,section_id,submitted_when,submitted_by,body) VALUES ('".PAGE_ID."','$section_id','".mktime()."','$submitted_by','$email_body')");
			// Make sure submissions table isn't too full
			$query_submissions = $database->query("SELECT submission_id FROM ".TABLE_PREFIX."mod_form_submissions ORDER BY submitted_when");
			$num_submissions = $query_submissions->numRows();
			if($num_submissions > $stored_submissions) {
				// Remove excess submission
				$num_to_remove = $num_submissions-$stored_submissions;
				while($submission = $query_submissions->fetchRow()) {
					if($num_to_remove > 0) {
						$submission_id = $submission['submission_id'];
						$database->query("DELETE FROM ".TABLE_PREFIX."mod_form_submissions WHERE submission_id = '$submission_id'");
						$num_to_remove = $num_to_remove-1;
					}
				}
			}
			if(!$database->is_error()) {
				$success = true;
			}
		}
		
		// Now check if the email was sent successfully
		if(isset($success) AND $success == true) {
			echo $success_message;
		} else {
			echo $TEXT['ERROR'];
		}
		
		}
	}
	
}

?>