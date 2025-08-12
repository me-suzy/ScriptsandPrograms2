<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- Page Template Xtra ------ PHP4 ---------##
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
## File: form/formelement_special.inc
## Desc: A set of special-purpose form element classes
## $Source: /home/cvsroot/squizlib/form/formelement_special.inc,v $
## $Revision: 2.38 $
## $Author: dofford $
## $Date: 2004/02/23 00:29:33 $
#######################################################################
global $SQUIZLIB_PATH;
include_once("$SQUIZLIB_PATH/datetime_field/datetime_field.inc");
#---------------------------------------------------------------------#

/**
* Email addresses, required special checking
*
* @package Form
* @see Form::Form(), FormElement::FormElement(), FormElementTextBox::FormElementTextBox()
*/
class FormElementEmail_Address extends FormElementTextBox {

	 ###################################
	# Makes sure the value is a string
	function parse_value($v='') {
		# make sure that there is no white space around the address
		return trim(FormElementTextBox::parse_value($v));
	}

	 ####################################################################
	# For question types that can mark themselves (e.g. email addresses)
	function answered_correctly() {
		return ($this->answered() && valid_email($this->value()));
	}

}


/**
* Logins must contain only alphanumeric characters or underscores and be 2 or more characers long
*
* @package Form
* @see Form::Form(), FormElement::FormElement(), FormElementTextBox::FormElementTextBox()
*/
class FormElementLogin extends FormElementTextBox {

	 ####################################################################
	# For question types that can mark themselves (e.g. email addresses)
	function answered_correctly() {
		$login_match = "^[a-z][a-z0-9\_]+$";
		return ($this->answered() && eregi($login_match,$this->value()));
	}

}

/**
* Passwords must be 2 or more characers long
*
* @package Form
* @see Form::Form(), FormElement::FormElement(), FormElementTextBox::FormElementTextBox()
*/
class FormElementPassword extends FormElementTextBox {

	var $width = 12;
	var $minimum_length = 5;
	var $max_length     = 20;
	var $require_verify = true;

	 ###################################
	# Makes sure the value is a stinrg
	function parse_value($v='') {
		if(is_string($v)) {
			$newv[0] = $v;
			$newv[1] = $v;
		} else {
			$newv[0] = reset($v);
			$newv[1] = next($v);
		}
		return $newv;
	}

	 ###############################
	# Return the value
	function value($new_value='') {
		if ($new_value) $this->set_value($new_value);
		return $this->value[0];
	}

	 ###################################################################################
	# Special meta_element stuff specific to this element type (must include "default")
	function meta_element_special($prefix='') {
		FormElementTextBox::meta_element_special($prefix);
		?>
			<tr>
				<td align=right valign=top><p class=backend_data>Minimum Length:</td>
				<td>
					<? echo text_box($prefix."minimum_length",$this->minimum_length, 5, 10,"class=backend_data"); ?>
				</td>
			</tr>
			<tr>
				<td align=right valign=top><p class=backend_data>Require Verification:</td>
				<td>
					<input type=checkbox name="<?=$prefix."require_verify"?>" value=1 <?=(($this->require_verify)?"checked":"")?>>
				</td>
			</tr>
		<?
	}

	 ###############################################################
	# Special updating processes specific to this element type
	function update_special($prefix='') {
		FormElementTextBox::update_special($prefix);
		if($this->height > 1) $height = 1;
		$this->minimum_length = max(0, (int) gpc_stripslashes($_REQUEST[$prefix.'minimum_length']));
		$this->require_verify = gpc_stripslashes($_REQUEST[$prefix.'require_verify']);
	}


	 #################################
	# Prints the text box to the form
	function render ($prefix='') {
		echo ereg_replace("type\=[\\\"]?text[\\\"]?","type=password",text_box($prefix."value[0]",$this->value[0], $this->width, (($this->maxlength)?$this->maxlength:0), $this->extra));
		if($this->require_verify) {
			echo "<br>";
			echo ereg_replace("type\=[\\\"]?text[\\\"]?","type=password",text_box($prefix."value[1]",$this->value[1], $this->width, (($this->maxlength)?$this->maxlength:0), $this->extra));
		}
	}

	
	 ####################################################################
	# For question types that can mark themselves (e.g. email addresses)
	function answered_correctly() {
		return (
			$this->answered()
			&& (strlen($this->value[0]) >= $this->minimum_length)
			&& (!$this->require_verify || $this->value[0] == $this->value[1])
		);
	}

}


/**
* Lets the user choose from out predefined $COUNTRIES list
*
* @package Form
* @see Form::Form(), FormElement::FormElement(), FormElementTextBox::FormElementTextBox()
*/
class FormElementCountry extends FormElementListBox {

	 ##############
	# Constructor
	function FormElementCountry($title='',$note='',$value='',$options='') {
		$countries_config = &get_config('Countries');
		$this->FormElementListbox($title,$note,$value,array_merge(array(""),array_keys($countries_config->countries)));
	}


	 #######################################################
	# Let the people the country NAME, not just the code
	function nice_value() {
		$v = FormElementListBox::value();
		$countries_config = &get_config('Countries');
		if(is_array($v)) {
			if (!empty($v)) {
				foreach($v as $k => $vv) {
					if ($this->options['KeyLabels']) {
						$v[$k] = $vv;
					} else {
						$v[$k] = $countries_config->countries[$vv];
					}
				}
			}
			return $v;
		} else {
			if ($this->options['KeyLabels']) {
				return $v;
			} else {
				return $countries_config->countries[$v];
			}
		}
	}

	 ########################################################
	# Allows one to set the options array
	function set_options($options=array()) {
		if(empty($options)) {
			# No Counties? ALL COUNTRES!
			$countries_config = &get_config("Countries");
			$options = array("")+array_keys($countries_config->countries);
		}
		return FormElementListBox::set_options($options);
	}

	 ###################################################
	# Prints the section where you define the "options"
	function meta_element_options_options($prefix='') {
		$countries_config = &get_config('Countries');
		?>
			<tr>
				<td align=right valign=top><p class=backend_data>Options:</td>
				<td>
					<?=multiple_combo_box($prefix."options",array(""=>"")+$countries_config->countries,$this->options(),"",$this->width,20)?><br>
					<span class=backend_smallprint>Select which countries you want available as options.</span><br>
					<span class=backend_smallprint>Use Country Labels as Key values</span>
					<input type=checkbox name="<? echo $prefix."options[KeyLabels]"; ?>" value="1" <? echo (($this->options['KeyLabels'])?" checked":"").'>' ?> <br>
				</td>
			</tr>
		<?
	}

	
	 #################################
	# Prints the text box to the form
	function render ($prefix='') {
		$countries_config = &get_config('Countries');
		$old_options = $this->options();
		$new_options = array();
		if (!empty($old_options)) {
			foreach($old_options as $code) {
				if ($code == 'KeyLabels') continue;
				if ($this->options['KeyLabels']) {
					$new_options[$countries_config->countries[$code]] = $countries_config->countries[$code];
				} else {
					$new_options[$code] = $countries_config->countries[$code];
				}
			}
		}
		$this->options($new_options);
		FormElementListBox::render($prefix);
		$this->options($old_options);
	}

}



/**
* Lets the user specify and date and/or time
*
* @package Form
* @see Form::Form(), FormElement::FormElement()
*/
class FormElementDateTime extends FormElement {

	var $parameters='';

	 ##############
	# Constructor
	function FormElementDateTime($title='',$note='',$value='') {
		$this->FormElement($title,$note,$value);
		$this->parameters = array(
				'show' => array('y','m','d','h','i','m'),
				'style' => array('y'=>'s','m'=>'s','d'=>'s','h'=>'s','i'=>'s','s'=>'s'),
				'min' => '0000-01-01 00:00:00',
				'max' => '9999-12-31 23:59:59',
				'extras' => array()
			);
	}

	 ################################################
	# Makes sure the value is in a reasonable format
	function parse_value($v='') {
		$d = new DateTime_Field('',$v,$this->parameters);
		$d->validate_value();
		return $d->value;
	}

	 #########################################################################
	# Returns the value in a more human-readable format - kids might like it
	function nice_value() {
		list($date,$time) = explode(" ",$this->value());
		list($y,$m,$d)    = explode("-",$date);
		list($h,$i,$s)    = explode(":",$time);
		$show = &$this->parameters['show'];
		if(in_array('d',$show)) $r .= " ".(int) $d;
		if(in_array('m',$show)) $r .= " ".$this->long_month((int)$m);
		if(in_array('y',$show)) $r .= " $y";
		$r .= " ";
		if(in_array('h',$show)) $r .= (($h>12)?$h-1:(($h>0)?(int)$h:12));
		if(in_array('h',$show) && in_array('i',$show)) $r .= ":";
		if(in_array('i',$show)) $r .= $i;
		if(in_array('i',$show) && in_array('s',$show)) $r .= ":";
		if(in_array('s',$show)) $r .= $s;
		if(in_array('h',$show)) $r .= (($h >= 12)?"pm":"am");
		return trim($r);
	}

	 ##########################################
	# Clears the value of this element
	function clear() {
		return $this->set_value('2001-01-01 00:00:00');
	}

	 ###################################################################################
	# Special meta_element stuff specific to this element type (must include "default"
	function meta_element_special($prefix='') {
		$this->check_backwards_compatibility();
		$field = new datetime_field($prefix,$this->value,$this->parameters);
		echo('<tr><td class=backend_data valign=top>Config:<td>');
		$field->print_edit_options();
		echo('</tr>');
		$this->meta_element_default($prefix);
	}


	 ###############################################################
	# Special updating processes specific to this element type
	function update_special($prefix='') {
		$this->check_backwards_compatibility();
		$field = new datetime_field($prefix,$this->value,$this->parameters);
		$field->process_edit_options();
		$field->process_field();
	}


	 ##########################################
	# Update the extra value 
	function set_extra_value($u='', $extra='') {
		$this->parameters['extras'][$u] = $extra;
	}

	 ################################
	# Prints the element in the form
	function render($prefix='') {
		$this->check_backwards_compatibility();
		$field = new datetime_field($prefix,$this->value,$this->parameters);
		$field->print_field();
	}

	 ################################
	# Prints the element in the form
	function process($prefix='') {
		$this->check_backwards_compatibility();
		$field = new datetime_field($prefix,$this->value,$this->parameters);
		$field->process_field();
	}

	 ##################################################
	# Makes any adjustments if this is an old version
	function check_backwards_compatibility() {
		if(isset($this->show_units)) {
			foreach($this->show_units as $k => $v) {
				if($v) $this->parameters['show'][] = $k;
			}
			$m = &$this->min_units;
			$this->parameters['min'] = sprintf("%04d-%02d-%02d %02d:%02d:%02d",$m['y'],$m['m'],$m['d'],$m['h'],$m['i'],$m['s']);
			$m = &$this->max_units;
			$this->parameters['max'] = sprintf("%04d-%02d-%02d %02d:%02d:%02d",$m['y'],$m['m'],$m['d'],$m['h'],$m['i'],$m['s']);
			$map = array('c'=>'s','t'=>'t');
			foreach($this->type_units as $k => $v) {
				$this->parameters['style'][$k] = $map[$v];
			}
			unset($this->show_units);
			unset($this->min_units);
			unset($this->max_units);
			unset($this->type_units);
			unset($this->month_style);
			unset($this->hour_style);
		}
	}


	 ###########################################
	# Given a month number returns the short name
	function short_month($m='') {
		$months = array("","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
		return $months[$m];
	}

	 ###########################################
	# Given a month number returns the long name
	function long_month($m='') {
		$months = array("","January","February","March","April","May","June","July","August","September","October","November","December");
		return $months[$m];
	}


	 ##########################################
	# Converts a string into an array of units
	function string_to_array_units($v="SQUAWIZZ") {
		if($v=="SQUAWIZZ") $v = $this->value();
		$r = array();
		list($date,$time) = explode(" ",$v);
		list($r['y'],$r['m'],$r['d']) = explode("-",$date);
		list($r['h'],$r['i'],$r['s']) = explode(":",$time);
		return $r;
	}

	function answer_equals($val='') {
		$t = $this->string_to_array_units();
		$v = $this->string_to_array_units($val);
		if (!empty($this->show_units)) {
			foreach($this->show_units as $u => $s) {
				if ($s && $t[$u] != $v[$u]) return false;
			}
		}
		return true;
	}
	
	function answer_greater_than($val='') {
		$t = $this->string_to_array_units();
		$v = $this->string_to_array_units($val);
		if (!empty($this->show_units)) {
			foreach($this->show_units as $u => $s) {
				if (!$s) continue;
				$ts .= sprintf("%04d", $t[$u]);
				$vs .= sprintf("%04d", $v[$u]);
			}
		}
		return strcasecmp($ts,$vs) > 0;
	}
	
	function answer_less_than($val='') {
		$t = $this->string_to_array_units();
		$v = $this->string_to_array_units($val);
		if (!empty($this->show_units)) {
			foreach($this->show_units as $u => $s) {
				if (!$s) continue;
				$ts .= sprintf("%04d", $t[$u]);
				$vs .= sprintf("%04d", $v[$u]);
			}
		}
		return strcasecmp($ts,$vs) < 0;
	}


}

/**
* File Upload
*
* @package Form
* @see Form::Form(), FormElement::FormElement()
*/
class FormElementFile extends FormElement {

	var $max_filesize = 0;
	var $display_max_filesize;
	var $dest_filename = '';
	var $restrict_extensions = '';
	var $type = '';
	var $attach_file = 0;

	 ##############
	# Constructor
	function FormElementFile($title='',$note='',$value='',$options='',$standard_vars='') {
		foreach($standard_vars as $key => $val) $this->$key = $val;
		if(!$this->type) $this->type = 'page';
		return $this->FormElement($title,$note,$value,$options);
	}


	 #################
	# parse the value
	function parse_value($v='none') {
		if($v == "none") return "";
		if(is_string($v)) {
			$newv[0] = $v;
			$newv[1] = $v;
		} else {
			$newv[0] = reset($v);
			$newv[1] = next($v);
		}
		return $newv;
	}

	 ###################################
	# Let the name of the uploaded file
	function nice_value($html=false) {
		if($html) return "<a href=\"".$this->value[1]."\">".$this->value[0]."</a>";
		return $this->value[1];
	}

	 ################################
	# Never reset the value
	function process($prefix='') {
		$this->process_special($prefix);
		return;
	}

	 ##############################################
	# Do what needs to be done to the uploaded file
	function process_special($prefix='') {
		# Commit the file uploaded
		$saved_filename = '';
		if (($dest = $this->get_destination()) != '') {
			commit_file_upload($prefix.'value',false,true,'',$dest,$this->dest_filename,&$saved_filename);
		} else {
			return;
		}

		if($saved_filename) {
			$this->value[0] = $saved_filename;
			# If we have a pageid, then lets attach this file to the page, and print a link to it
			if(($this->type == 'page' || !$this->type) && $this->attach_file) {
				$file = new File(0);
				$web_system = &get_web_system();
				$page = &$web_system->get_page();
				$file->create($page->id,$saved_filename,'','','N');
			}

			$web_system = &get_web_system();
			$site = &$web_system->get_site();
			if($site->id) $url = $site->get_url(true);
			else $url = url_protocol().'://'.dirname(dirname($web_system->current_url()))."/?";
			
			switch ($this->type) {
				case 'lib':
				case 'squizlib':
				case 'system' :
				case 'web'    :
				case 'users'  :
					# in general if this is getting called then the file is restricted
					$this->value[1] = $url."mysource_action=send_file&type=$this->type&file=".urlencode($saved_filename);
					break;
				case 'user' :
					global $SESSION,$userid;
					if (!$userid) $userid = $SESSION->user->id;
					$this->value[1] = $url."mysource_action=send_file&type=user&userid=".$userid."&file=".urlencode($saved_filename);
					break;
				case 'site':
					$web_system = &get_web_system();
					$site = &$web_system->get_site();
					$this->value[1] = $url."mysource_action=send_file&type=site&s=$site->id&file=".urlencode($saved_filename);
					break;
				case 'page':
					$web_system = &get_web_system();
					$page = &$web_system->get_page();
					$this->value[1] = $url."mysource_action=send_file&type=page&pageid=$page->id&file=".urlencode($saved_filename);
					break;
				case 'page_template':
					$web_system = &get_web_system();
					$page = &$web_system->get_page();
					$this->value[1] = $url."mysource_action=send_file&type=page_template&p=$page->id&file=".urlencode($saved_filename);
					break;
				default:
					break;
			}
		}
	}

	 ###################################################################################
	# Special meta_element stuff specific to this element type (must include "default")
	function meta_element_special($prefix='') {
		?>
			<tr>
				<td align=right valign=top>
					<p class=backend_data>Max File Size (KB):
				</td>
				<td>
					<p class=backend_data>
					<? 
					echo text_box($prefix."max_filesize",$this->max_filesize,6,128,"class=backend_data"); 
					?>
					<input type=checkbox name=<?=$prefix."display_max_filesize"?> value=1 <?=(($this->display_max_filesize)?"checked":"");?>><span class=backend_smallprint>Display max file size</span>
					<br><span class=backend_smallprint>Set to 0 for no maximum.</span>
				</td>
			</tr>
			<tr>
				<td align=right valign=top>
					<p class=backend_data>Allowed File Types:
				</td>
				<td>
					<p class=backend_data>
					<? 
					echo text_area($prefix."restrict_extensions",$this->restrict_extensions,40,4,"","class=backend_data"); 
					?>
					<br><span class=backend_smallprint>Leave blank for no restrictions. Include the dot in the extension name, eg ".jpg".<br />Use the answered correctly rule to apply restrictions</span>
				</td>
			</tr>
			<?
			if($this->type == "page") {
				?>
				<tr>
					<td align=right valign=top>
						<p class=backend_data>Attach file to page:
					</td>
					<td>
						<p class=backend_data>
						<? echo "<input type=checkbox name=\"".$prefix."attach_file\" value=1 ".(($this->attach_file)?"checked":"").">";?>
					</td>
				</tr>
				<?
			}
			?>
			<tr>
				<td align=right valign=top>
					<p class=backend_data>Rename File To:
				</td>
				<td>
					<p class=backend_data>
					 <? echo text_box($prefix."dest_filename",$this->dest_filename,30,128,"class=backend_data"); ?>
					<br><span class=backend_smallprint>An ID will be appended to this name to make sure it is unique.<br>Leave blank to retain the original filename.</span>
				</td>
			</tr>
		<?
	}


	 ###############################################################
	# Special updating processes specific to this element type
	function update_special($prefix='') {
		$this->max_filesize			=  max(0, (int) gpc_stripslashes($_REQUEST[$prefix.'max_filesize']));
		$this->display_max_filesize = gpc_stripslashes($_REQUEST[$prefix.'display_max_filesize']);
		$this->dest_filename		= ($_REQUEST[$prefix.'dest_filename']) ? gpc_stripslashes($_REQUEST[$prefix.'dest_filename']) : "";
		$this->attach_file			= ((gpc_stripslashes($_REQUEST[$prefix.'attach_file']))?true:false);

		$restrict_extensions = split("[ \n\r\t\,\;]+",gpc_stripslashes($_REQUEST[$prefix.'restrict_extensions']));
		$this->restrict_extensions = "";
		if (!empty($restrict_extensions)) {
			foreach($restrict_extensions as $extension) {
				$extension = trim($extension);
				if(!$extension) continue;
				$this->restrict_extensions .= strtolower($extension)."\n";
			}
		}
	}

	 #################################
	# Prints the text box to the form
	function render ($prefix='') {
		echo file_upload($prefix.'value',$this->get_destination(),"",$this->dest_filename);
		if($this->display_max_filesize && $this->max_filesize) echo "&nbsp;<b>(".easy_filesize(($this->max_filesize*1024))." Max)</b>";
		if($this->value) echo "<br>".$this->nice_value(true);
	}

	 ####################################################################
	# For question types that can mark themselves (e.g. email addresses)
	function answered() {
		if(count($this->value) < 2) return false;
		foreach($this->value as $val) {
			if(!$val) return false;
		}
		return true;
	}

	 ####################################################################
	# For question types that can mark themselves (e.g. email addresses)
	function answered_correctly() {
		if(!$this->answered()) $problem = true;
		if($this->max_filesize) {
			if($this->max_filesize >= filesize($this->get_destination()."/".$this->value[0])) $problem = true;
		}
		if($this->restrict_extensions) {
			if(!stristr($this->restrict_extensions,strtolower(substr(strrchr($this->value[0],"."), 0)))) $problem = true;
		}
		if($problem) {
			$this->value = "";
			unlink($this->get_destination()."/".$this->value[0]);
			return false;
		}
		return true;
	}

	 #############################################
	# Get the destination of this file at run-time
	function get_destination() {
		switch ($this->type) {
			case 'lib':
				global $WEB_PATH;
				$destination = "$WEB_PATH/__lib/";
				break;
			case 'squizlib':
				global $SQUIZLIB_PATH;
				$destination = "$SQUIZLIB_PATH/";
				break;
			case 'system' :
			case 'web'    :
			case 'users'  :
				# in general if this is getting called then the file is restricted
				$destination = get_data_path(false, "$this->type/");
				break;
			case 'user' :
				global $SESSION,$userid;
				if (!$userid) $userid = $SESSION->user->id;
				if (!$userid) return '';
				$destination = get_data_path(false, "$this->type/".$userid);
				break;
			case 'site':
				$web_system = &get_web_system();
				$site = &$web_system->get_site();
				$destination = "$site->data_path/";
				break;
			case 'page':
				$web_system = &get_web_system();
				$page = &$web_system->get_page();
				$destination = "$page->data_path/";
				break;
			case 'page_template':
				global $XTRAS_PATH;
				$web_system = &get_web_system();
				$page = &$web_system->get_page();
				$destination = "$XTRAS_PATH/page/templates/$page->template/images/";
				break;
			default:
				$web_system = &get_web_system();
				$page = &$web_system->get_page();
				$destination = "$page->data_path/";
				break;
		}
		return $destination;
	}
}



/**
* Email List. Allows the user to enter a list of name-email address pairs. 
* If the user wants more rows, pressing Enter on the last row creates a new row.
* 
* The value type is of the form array(0 => array('name' => $name, 'email' => $email), 1 => ...)
*
* @package Form
* @see Form::Form(), FormElement::FormElement()
*/

class FormElementEmail_List extends FormElement {

	var $initial_lines = 1;	# how many intial blank lines to display
	var $name_width = 15;	# width of the name box
	var $email_width = 15;	# width of the email box

	 ##################################
	# Constructor
	function FormElementEmail_List ($title='',$note='',$value='',$options='') {
		FormElement::FormElement($title, $note, $value);
		if (!$value || !is_array($value))
			$this->value = array();

		$this->options = $options;
	}


	 ###################################
	# Makes sure the value is an array
	function parse_value($v='') {
		if(is_string($v)) {
			$newv[0]['name'] = $v;
			$newv[0]['email'] = '';
		} else {
			foreach ($v as $id => $entry) {
				if (!is_array($entry))
					continue;
				$newv[$id]['name'] = $entry['name'];
				$newv[$id]['email'] = $entry['email'];
			}
		}
		return $newv;
	}

	 #########################################################################
	# Returns the value in a more human-readable format - kids might like it
	function nice_value($html=false) {
		$out = '';
		if ($html) {
			foreach ($this->value as $entry) {
				$out .= "<b>{$entry['name']}</b>&nbsp; &lt;{$entry['email']}&gt;<br>\n";
			}
		} else {
			foreach ($this->value as $entry) {
				$out .= "{$entry['name']} <{$email['email']}>\n";
			}
		}
		return $out;
	}

	 ###################################################################################
	# Special meta_element stuff specific to this element type (must include "default"
	function meta_element_special($prefix='') {
		?>
		<tr>
		<td>
		<p class=backend_data>Initial Lines:</p>
		</td>
		<td>
		<?= text_box ($prefix."initial_lines", $this->initial_lines, 1) ?>
		</td>
		</tr>

		
		<tr>
		<td>
		<p class=backend_data>Name Box Width:</p>
		</td>
		<td>
		<?= text_box ($prefix."name_width", $this->name_width, 1) ?>
		</td>
		</tr>

		<tr>
		<td>
		<p class=backend_data>Email Box Width:</p>
		</td>
		<td>
		<?= text_box ($prefix."email_width", $this->email_width, 1) ?>
		</td>
		</tr>
		<?

		$this->meta_element_default($prefix);

	}


	 ###############################################################
	# Special updating processes specific to this element type
	function update_special($prefix='') {
		$this->process ($prefix);
		$this->initial_lines = max (1, $_POST[$prefix."initial_lines"]);
		$this->name_width = max (1, $_POST[$prefix."name_width"]);
		$this->email_width = max (1, $_POST[$prefix."email_width"]);
	}

	 ################################
	# Prints the element in the form
	function render($prefix='') {
		$val =& $this->value;
		if (!is_array ($this->value)) {
			# what the ...?
			return;
		}
		
		?>

		<script language="Javascript">
			function <?=$prefix?>addRow() {
			}
		</script>

		<table>
		<tbody id="<?=$prefix?>tbody">
		<tr>
		<td><b>Name</b></td>
		<td><b>E-mail Address</b></td>
		</tr>
		
		<?
		foreach ($this->value as $i => $entry) {
			?>
			<tr>
			<td>
			<?= text_box($prefix."name[]", $entry['name'], $this->name_width)?>
			</td>
			<td><?= text_box($prefix."email[]", $entry['email'], $this->email_width)?>
			</td>
			</tr>
			<?
		}

		while (++$i < $this->initial_lines - 1) {
			?>
			<tr>
			<td>
			<?= text_box($prefix."name[]", '', $this->name_width)?>
			</td>
			<td><?= text_box($prefix."email[]", '', $this->email_width)?>
			</td>
			</tr>
			<?
		}
		
		# print extra empty line
		
		$js = "onKeyUp=\"
			if (event.keyCode != 13)
				return;

			var lastRow = document.getElementById('".$prefix."last_row');
			var newRow = lastRow.cloneNode(true);
			lastRow.id = null;
			lastRow.parentNode.appendChild(newRow);
			newRow.children[0].children[0].value = '';
			newRow.children[1].children[0].value = '';

			newRow.children[0].children[0].focus();

			return true;\"
		";

		?>
		<tr id="<?=$prefix?>last_row">
		<td>
		<?= text_box($prefix."name[]", '', $this->name_width, '', $js)?>
		</td>
		<td><?= text_box($prefix."email[]", '', $this->email_width, '', $js)?>
		</td>
		</tr>
		<?
		
		?>
		</tbody>
		</table>
		<?
	}

	function process ($prefix='') {
		$names = $_POST[$prefix."name"];
		$emails = $_POST[$prefix."email"];
		if (!is_array ($names) || !is_array($emails)) {
			# what the ...?
			return;
		}

		$count = min (count($names), count($emails));

		$this->value = array();
		$next = 0;
		for($i = 0; $i < $count; $i++) {
			if ($emails[$i]) {
				$this->value[$next]['name'] = $names[$i];
				$this->value[$next]['email'] = $emails[$i];
				$next++;
			}
		}
	}


	 ####################################################################
	# For question types that can mark themselves (e.g. email addresses)
	function answered_correctly() {
		foreach ($this->value as $email) {
			if (!valid_email($email))
				return false;
		}
		# we get this far - emails are OK - but are there any in the first place?
		return ($this->answered());
	}

	 ##################################
	# Function answered
	function answered() {
		return count($this->value) > 0;
	}


	 #######################################
	# Function answered with correct answer
	# for arrays this looks at the *amuont* of answers
	function answer_equals($val='') {
		return (count($this->value) ==  $val);
	}




}

?>