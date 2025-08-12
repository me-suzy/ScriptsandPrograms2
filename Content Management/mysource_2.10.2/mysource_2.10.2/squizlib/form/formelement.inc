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
## File: form/formelement.inc
## Desc: A PHP class (and descendant class set) nazied to the form class.
## $Source: /home/cvsroot/squizlib/form/formelement.inc,v $
## $Revision: 2.27.2.2 $
## $Author: achadszinow $
## $Date: 2004/05/13 02:18:06 $
#######################################################################
#---------------------------------------------------------------------#


 ####################################
# Global array of form element types
global $FORM_ELEMENT_TYPES;
$FORM_ELEMENT_TYPES= array (
	'hidden'        => 'Hidden Field',
	'textbox'       => 'Text Box',
	'listbox'       => 'Dropdown/List Box',
	'tickbox'       => 'Tickbox List',
	'datetime'      => 'Date/Time',
	'email_address' => 'Email Address',
	'country'       => 'Country',
	'login'         => 'Login/Username',
	'password'      => 'Password',
	'file'          => 'File Upload',
	'email_list'	=> 'Email List'
);



/**
* ELEMENTS - Stored in an array inside the Form class
*
* @package Form
* @see Form::Form()
*/
class FormElement extends Object {


	 ###################################
	# Variables
	#var $id    = 0;          # Each element in the form has a unique identifier
	#var $form_name = 'form'; # Sometimes it help to know your forms name
	var $title  = '';    # Should be changed to something more creative
	var $note   = '';        # Optional longer version of the name with instructions
	var $value  = '';        # The current value !
	var $use_session_auto_answer = false;
	var $session_auto_answer_val = ''; # A string value to auto answer the question from a session value

	 ##################################
	# Constructor
	function FormElement ($title='',$note='',$value='',$options='') {
		if ($title) $this->title($title);
		if ($note)  $this->note  = $note;
		if ($value) $this->value($value);
	}


	 ##########################################
	# Sets the name of the element, or returns it
	function title($new_title='') {
		if ($new_title) $this->title = $new_title;
		return $this->title;
	}

	 ##########################################
	# Sets the value of the element (if given), or returns it
	function value($new_value='') {
		if ($new_value) $this->set_value($new_value);
		return $this->value;
	}

	 
	 ##########################################
	# Sets the value of the element (unconditionally), or returns it
	function set_value($new_value='') {
		$this->value = $this->parse_value($new_value);
		return $this->value();
	}

	 ################################################
	# Makes sure the value is in a reasonable format
	function parse_value($v='') {
		return $v;
	}

	 #########################################################################
	# Returns the value in a more human-readable format - kids might like it
	function nice_value($html=false) {
		return $this->value();
	}

	 ##########################################
	# Clears the value of this element
	function clear() {
		$this->value = "";
		return $this->value;
	}

	 ##################################
	# Returns the current element type
	function type() {
		$type = ereg_replace("^formelement","",get_class($this));
		if (!$type) $type = "hidden";
		return $type;
	}

	 ##################################
	# Determines if the FormElement should use session auto answering
	function set_session_auto_answer($bool) {
		$this->use_session_auto_answer = $bool;
	}

	 ################################################################################
	# Prints the section of a HTML (meta)form which allows the maintenance of this object
	function meta_element($prefix='',$questionid=0,$order=false) {
		?>
		<table cellpadding=2 cellspacing=0 border=0>
			<input type=hidden name="<?=$prefix?>action">
			<? if($order) { ?>
				<tr>
					<td align=right><p class=backend_data>Order</td>
					<td valign=top class=backend_data>
						<? echo text_box($prefix."order",$order,2,3,"class=backend_data"); ?>
						<? echo "<input type=hidden name=".$prefix."old_order value=".$order.">"; ?>

					</td>
				</tr>
			<? } ?>
			<tr>
				<td align=right><p class=backend_data>Remove?</td>
				<td valign=top class=backend_data>
					<? echo ("<input type=checkbox name=$prefix"."delete value=1>"); ?>
					(entire question)
				</td>
			</tr>
			<tr>
				<td align=right><p class=backend_data>Title:</td>
				<td valign=top>
					<? echo text_box($prefix."title",$this->title, 30, '', "class=backend_data"); ?>
				</td>
			</tr>
			<tr>
				<td valign=top align=right><p class=backend_data>Note:</td>
				<td valign=top>
					<? echo text_area($prefix."note",$this->note, 50, 3, 1024,"class=backend_data"); ?>
				</td>
			</tr> 
			<?if ($this->use_session_auto_answer) {  ?>
			<tr>
				<td valign=top align=right><p class=backend_data>Session Auto Value:</td>
				<td valign=top>
					<? echo text_box($prefix."session_auto_answer_val",$this->session_auto_answer_val, 30, '',"class=backend_data"); ?>
				</td>
			</tr>
			<?}?>
			<?  $this->meta_element_special($prefix);  ?>
		</table>
		<?
	}


	 ###################################################################################
	# Special meta_element stuff specific to this element type (must include "default"
	function meta_element_special($prefix='') {

		$this->meta_element_default($prefix);
	}


	 ###################################################################################
	# Prints out the section where you choose the default
	function meta_element_default($prefix='') {
		?>
			<tr>
				<td align=right valign=top><p class=backend_data>Default:</td>
				<td>
					<?
			$this->render($prefix);
			?>
				</td>
			</tr>
		<?
	}

		
	 #################################################################
	# Processes input from the the form generated by meta_form
	function update($prefix='') {

		# Update form attributes
		$this->title(gpc_stripslashes($_REQUEST[$prefix.'title']));
		$this->set_value(stripslashes_array($_REQUEST[$prefix.'value']));
		$this->note = gpc_stripslashes($_REQUEST[$prefix.'note']);
		if ($this->use_session_auto_answer) {
			$this->session_auto_answer_val = gpc_stripslashes($_REQUEST[$prefix.'session_auto_answer_val']);
		}

		# Run element-type-specific updating
		$message .= $this->update_special($prefix);
		return $message;
	}

	 ###############################################################
	# Special updating processes specific to this element type
	function update_special($prefix='') {
		# Nothing to go here because this is the parent.
	}

	 ##########################################
	# Changes the type of this object
	function change_type($type='', $standard_vars) {
		global $FORM_ELEMENT_TYPES;
		$old_type = $this->type();
		$session_auto_answer_val = $this->session_auto_answer_val;
		eval("\$this = new FormElement$type(\$this->title,\$this->note,\$this->value,\$this->options, \$standard_vars);");
		$this->session_auto_answer_val = $session_auto_answer_val;
		return "Question type changed from ".$FORM_ELEMENT_TYPES[$old_type]." to ".$FORM_ELEMENT_TYPES[$type];
	}

	 ################################
	# Prints the element in the form
	function render($prefix='') {

		$val = htmlspecialchars($this->value());
		echo ("<input type=hidden name=\"$prefix"."value\" value=\"$val\">");
	}

	 ###############################
	# Processes the submitted form
	function process($prefix='') {
		$global_vars = array('value');
		foreach($global_vars as $global_var) {
			$$global_var = $_REQUEST[$prefix.$global_var];
			if (is_string($$global_var)) $$global_var = gpc_stripslashes($$global_var);
			elseif(is_array($$global_var)) stripslashes_array($$global_var);
		}
		$this->set_value($value);
		$this->process_special($prefix);
	}

	 ######################################
	# Special element-dependant processing
	function process_special($prefix='') {
		# Nothing to go here yet
	}


	 ##################################
	# Function answered
	function answered() {
		if (is_array($this->value())) { 
			return count($this->value());
		}
		else
			return strlen(trim($this->value()));
	}


	 ####################################################################
	# For question types that can mark themselves (e.g. email addresses)
	function answered_correctly() {
		return $this->answered();
	}

	 
	 #######################################
	# Function answered with correct answer
	# for arrays this looks at the *amuont* of answers
	function answer_equals($val='') {
		return strcasecmp($this->value(), $val) == 0;
	}



	 #####################################################################
	# Does a container contain a certain value.. string/array independant
	function generic_contains($container='',$val='') {
		if(is_array($val)) {
			if(is_array($container)) {
				foreach($val as $el) {
					if(!in_array($el,$container)) return false;
				}
				return true;
			} elseif (!empty($val)) {
				foreach($val as $el) {
					if(!stristr($container,$el)) return false;
				}
				return true;
			}
			return false;
		} else {
			if(is_array($container)) {
				return in_array($val,$container);
			} else {
				return stristr($container,$val);
			}
		}
	}


	 ##############################################
	# Answer matches a particular regular expression
	function answer_regexp_match($val='') {
		return ereg($val,$this->value());
	}

	 
	 ##############################################
	# Function answered containing a certain value
	function answer_contains($val='') {
		return $this->generic_contains($this->value(), $val);
	}
	
	 ##################################################
	# Function answered is contained in a certain value
	function answer_contained_in($val='') {
		return $this->generic_contains($val,$this->value());
	}
	
	 #############################################
	# Function answer greater than a certain value
	# for arrays this looks at the *amuont* of answers
	function answer_greater_than($val='') {
		if (is_int($val) || is_double($val)) {
			return ($this->value() > $val);
		} else {
			return strcasecmp($this->value(), $val) > 0;
		}
	}


	 #######################################
	# Function answer less than a certain value
	function answer_less_than($val='') {
		if (is_int($val) || is_double($val)) {
			return ($this->value() < $val);
		} else {
			return strcasecmp($this->value(), $val) < 0;
		}
	}


}


/**
* Form elements that have options like list and tickboxes
*
* @package Form
* @see Form::Form(), FormElement::FormElement()
*/
class FormElementOptions extends FormElement {

	var $multiple  = 0;       # Can you select multiple options?
	var $options   = array(); # The options array itself

	var $value     = array(); # The value is an array now

	 ##############
	# Constructor
	function FormElementOptions($title='',$note='',$value='',$options='') {
		if ($title)   $this->title($title);
		if ($note)    $this->note    = $note;
		if ($value)   $this->set_value($value);
		if ($options) $this->options($options);
	}


	 ########################################################
	# Allows one to set the options array
	function options($options=array()) {
		if ($options) return $this->set_options($options);
		return $this->options;
	}

	 ########################################################
	# Allows one to set the options array
	function set_options($options='') {
		if (is_array($options)) {
			$this->options = $options;
			return $this->options;
		} 
		$new_options = array();
		$options = split("[\r\n]+",$options);
		if (!empty($options)) {
			while(list($id,$option) = each($options)) {
				list($key,$val) = explode(":",$option);
				$key = trim($key); $val = trim($val);
				if (!$val) $val = $key;
				if ($key) $new_options[$key] = $val;
			}
		}
		$this->options = $new_options;
		return $this->options;
	}

	 #########################################################################
	# Returns the value in a more human-readable format - kids might like it
	function nice_value($html=false) {
		$value = $this->value();
		if (is_array($value)) {
			foreach ($value as $val) {
				$nice_values[] = $this->options[$val];
			}
			return $nice_values;
		}
		return $this->options[$value];
	}

	 ###################################################################################
	# Special meta_element stuff specific to this element type (must include "default")
	function meta_element_special($prefix='') {

		$this->meta_element_options_options($prefix);

		$this->meta_element_default($prefix);

		?>
			<tr>
				<td align=right><p class=backend_data>Multiple:</td>
				<td>
					<input type=checkbox name="<? echo $prefix."multiple"?>" value="1" <? echo (($this->multiple)?" checked":"") ?>>
					<span class=backend_data>Allowed</span>
				</td>
			</tr>
		<? $this->meta_element_options_special($prefix);
	}


	 ###################################################
	# Prints the section where you define the "options"
	function meta_element_options_options($prefix='') {

		?>
			<tr>
				<td align=right valign=top><p class=backend_data>Options:</td>
				<td>
					<?
					reset($this->options);
					while(list($key,$val) = each($this->options)) {
						$display_options .= $key . (("$key" != "$val")?" : $val\n":"\n");
					}
					echo text_area($prefix."options",$display_options, 50, 5, 30000,"class=backend_data wrap='off'");
					?><br>
					<span class=backend_smallprint>One option per line.<br>You may separate values and labels by a colon (:)<br>e.g. "NSW : New South Wales"</span>
				</td>
			</tr>
		<?
	}


	 ######################################################
	# Special meta_element fields for an "options" element
	function meta_element_options_special($prefix='') {
		# nothing in here yet
	}


	 ################################################
	# Makes sure the value is in a reasonable format
	function parse_value($v=array()) {
		if (!$v || empty($v)) return array(); # Must always have at least a blank array
		return $v;
	}


	 ###############################################################
	# Special updating processes specific to this element type
	function update_special($prefix='') {
		$multiple = gpc_stripslashes($_REQUEST[$prefix.'multiple']);
		$options = stripslashes_array($_REQUEST[$prefix.'options']);
		
		if(!$this->multiple && $multiple) {
			$this->multiple = $multiple;
			$this->value = array($this->value);
		} elseif($this->multiple && !$multiple) {
			$this->multiple = $multiple;
			$this->value = $this->value[0];
		}
		
		$this->set_options($options);
		$this->update_options_special($prefix);
	}


	 ###############################################################
	# Special updating processes specific to this element type
	function update_options_special($prefix='') {
		# Nothing to go here yet
	}

	 ##################################
	# Function answered
	function answered() {
		if (is_array($this->value())) {
			return count($this->value());
		}
		
		return strlen(trim($this->value()));
	}


	 #######################################
	# Function answered with correct answer
	# for arrays this looks at the *amuont* of answers
	function answer_equals($val='') {
		return (equal_arrays($this->value(),$val));
	}

	 ##############################################
	# Answer matches a particular regular expression
	function answer_regexp_match($val='') {
		$values = $this->value();
		if (!empty($values)) {
			foreach($values as $value) {
				if (!ereg($val,$value)) return false;
			}
		}
		return true;
	}


	 #############################################
	# Function answer greater than a certain value
	# for arrays this looks at the *amuont* of answers
	function answer_greater_than($val='') {
		return (count($this->value()) > count($val));
	}


	 #######################################
	# Function answer less than a certain value
	function answer_less_than($val='') {
		return (count($this->value()) < count($val));
	}

}


?>
