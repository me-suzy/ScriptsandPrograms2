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
## $Source: /home/cvsroot/squizlib/form/formelement_standard.inc,v $
## $Revision: 2.14 $
## $Author: dofford $
## $Date: 2004/01/16 01:11:50 $
#######################################################################
#---------------------------------------------------------------------#


/**
* Hidden Fields
*
* @package Form
* @see Form::Form(), FormElement::FormElement()
*/
class FormElementHidden extends FormElement {
	# This is essentially the same as the parent
	function FormElementHidden($title='',$note='',$value='',$options='') {
		return $this->FormElement($title,$note,$value,$options);
	}

	 ###################################################################################
	# Prints out the section where you choose the default
	function meta_element_default($prefix='') {
		?>
			<tr>
				<td align=right valign=top><p class=backend_data>Default:</td>
				<td>
					<? echo text_box($prefix."value",$this->value, 50, 128,"class=backend_data"); ?>
				</td>
			</tr>
		<?
	}
}//end class FormElementHidden



/**
* Text Boxes
*
* @package Form
* @see Form::Form(), FormElement::FormElement()
*/
class FormElementTextbox extends FormElement {

	var $width     = 30;  # In characters
	var $height    = 1;   # In lines
	var $maxlength = 300; # In characters
	var $select_on_focus = false; # boolean
	var $extra = ''; # for things like OnFocus=blur();

	 ##############
	# Constructor
	function FormElementTextbox($title='',$note='',$value='',$options='') {
		return $this->FormElement($title,$note,$value,$options);
	}


	 ###################################
	# Makes sure the value is a stinrg
	function parse_value($v='') {
		if(!is_string($v)) {
			if(is_array($v) || is_object($v)) {
				$v = implode((($this->height>1)?"\n":" "),$v);
			} else $v = "$v";
		}
		return $v;
	}
	
	 ##########################################
	# Update the extra value 
	function set_extra_value($extra='') {
		if ($extra) $this->extra = $extra;
		return;
	}

	 ###################################################################################
	# Special meta_element stuff specific to this element type (must include "default")
	function meta_element_special($prefix='') {
		?>
			<tr>
				<td align=right valign=top><p class=backend_data>Default:</td>
				<td class="backend_fineprint">
					<? $this->render($prefix); ?>
					<input type="checkbox" name="<?=$prefix?>select_on_focus" <?=($this->select_on_focus) ? "checked" : ""?> value="1"> Select the text on entry
				</td>
			</tr>
			<tr>
				<td align=right>
					<p class=backend_data>Width:</td>
				<td>
					<p class=backend_data>
					<? echo text_box($prefix."width",$this->width, 5, 10,"class=backend_data"); ?>
					Height:
					<? echo text_box($prefix."height",$this->height, 5, 10,"class=backend_data"); ?>
					Maximum Length:
					<? echo text_box($prefix."maxlength",$this->maxlength, 5, 10,"class=backend_data"); ?>
					Extra:
					<? echo text_box($prefix."extra",$this->extra, 15, 100,"class=backend_data"); ?>
				</td>
			</tr>
		<?
	}


	 ###############################################################
	# Special updating processes specific to this element type
	function update_special($prefix='') {
		$this->select_on_focus = ($_REQUEST[$prefix.'select_on_focus']) ? true : false;
		if($_REQUEST[$prefix.'width'])  $this->width  = max(1, (int) $_REQUEST[$prefix.'width']);
		if($_REQUEST[$prefix.'height']) $this->height = max(1, (int) $_REQUEST[$prefix.'height']);
		$this->maxlength = max(0, (int) $_REQUEST[$prefix.'maxlength']);
		$this->extra = ($_REQUEST[$prefix.'extra']) ? $_REQUEST[$prefix.'extra'] : '';
	}

	 #################################
	# Prints the text box to the form
	function render ($prefix='') {
		$extras = ($this->select_on_focus) ? 'onFocus="javascript: this.select();"' : '';
		if ($this->height > 1) {
			echo text_area($prefix."value",$this->value, $this->width, $this->height, (($this->maxlength)?$this->maxlength:0), $extras);
		} else if ($this->extra){
			echo text_box($prefix."value",$this->value, $this->width, (($this->maxlength)?$this->maxlength:0), $this->extra);
		} else {
			echo text_box($prefix."value",$this->value, $this->width, (($this->maxlength)?$this->maxlength:0), $extras);
		}
	}
}





/**
* List Boxes
*
* @package Form
* @see Form::Form(), FormElement::FormElement(), FormElementOptions::FormElementOptions()
*/
class FormElementListbox extends FormElementOptions {

	var $width     = 50;  # In characters
	var $height    = 1;   # In lines

	 ##############
	# Constructor
	function FormElementListbox($title='',$note='',$value='',$options='') {
		$this->FormElementOptions($title,$note,$value,$options);
	}


	 ######################################################
	# Special meta_element fields for an "options" element
	function meta_element_options_special($prefix='') {
		?>
		<tr>
			<td align=right>
				<p class=backend_data>Width:</td>
			<td>
				<p class=backend_data>
				<? echo text_box($prefix."width",$this->width, 5, 10,"class=backend_data"); ?>
				Height:
				<? echo text_box($prefix."height",$this->height, 5, 10,"class=backend_data"); ?>
			</td>
		</tr>
		<?
	}


	 ###############################################################
	# Special updating processes specific to this element type
	function update_options_special($prefix='') {
		if($_REQUEST[$prefix.'width'])  $this->width  = max(1, (int) $_REQUEST[$prefix.'width']);
		if($_REQUEST[$prefix.'height']) $this->height = max((($this->multiple) ? 2 : 1), (int) $_REQUEST[$prefix.'height']);
	}



	 #################################
	# Prints the text box to the form
	function render ($prefix='') {
		if ($this->multiple) {
			echo multiple_combo_box($prefix."value",$this->options(), $this->value(), "", $this->width, $this->height);
		} else {
			echo combo_box($prefix."value",$this->options(), $this->value(), "", $this->width, $this->height);
		}
	}
}



/**
* Radio or Checkboxes
*
* @package Form
* @see Form::Form(), FormElement::FormElement(), FormElementOptions::FormElementOptions()
*/
class FormElementTickbox extends FormElementOptions {

	var $columns    = 1;  # Columns
	var $other      = 0;
	var $other_text = "(Other)";

	 ##############
	# Constructor
	function FormElementTickbox($title='',$note='',$value='',$options='') {
		$this->FormElementOptions($title,$note,$value,$options);
	}

	 ##########################################
	# Changed to handle the "other" option
	function value($new_value='') {
		if ($new_value) $this->set_value($new_value);
		$return_value = $this->value;
		if($this->multiple) {
			if (is_array($return_value)) {
				if (!empty($return_value)) {
					reset($return_value);
					while(list($k,$val) = each($return_value)) {
						if($return_value[$k] == "!!OTHER!!") {
							$return_value[$k] = "[OTHER]: $this->other_text";
						}
					}
				} else {
					$return_value = "";
				}
			}
		} else {
			if (is_array($return_value) && empty($return_value)) {
				$return_value = "";
			}
			if($return_value == "!!OTHER!!") {
				$return_value = "[OTHER]: $this->other_text";
			}
		}
		return $return_value;
	}

	 ##############################################
	# Changed to handle the "other" option
	function nice_value($html=false) {
		$value = $this->value();
		if (is_array($value)) {
			foreach ($value as $val) {
				if(substr($val,0,9) == "[OTHER]: ") {
					$nice_values[] = $val;
				} else {
					$nice_values[] = $this->options[$val];
				}
			}
			return $nice_values;
		}

		# single other option 
		if(substr($value,0,9) == "[OTHER]: ") {
			return $value;
		}

		#default
		return $this->options[$value];
	}

	 ##########################################
	# Changed to handle the "other" option
	function set_value($new_value='') {
		$this->value = $this->parse_value($new_value);
		if($this->multiple) {
			if (is_array($this->value) && !empty($this->value)) {
				reset($this->value);
				while(next($this->value)) {
					$k = key($this->value);
					if(substr($this->value[$k],0,9) == "[OTHER]: ") {
						$this->other_text = substr($this->value[$k],9);
						$this->value[$k] = "!!OTHER!!";
					}
				}
			}
			reset($this->value);
		} else {
			if(substr($this->value,0,9) == "[OTHER]: ") {
				$this->other_text = substr($this->value,9);
				$this->value = "!!OTHER!!";
			}
		}
		return $this->value();
	}

	 ######################################################
	# Special meta_element fields for an "options" element
	function meta_element_options_special($prefix='') {
		?>
		<tr>
			<td align=right>
				<p class=backend_data>"Other":</td>
			<td>
				<p class=backend_data>
				<input type=checkbox name="<? echo $prefix."other"?>" value="1" <? echo (($this->other)?" checked":"") ?>>
			</td>
		</tr>
		<tr>
			<td align=right>
				<p class=backend_data>Columns:</td>
			<td>
				<p class=backend_data>
				<? echo text_box($prefix."columns",$this->columns, 5, 10,"class=backend_data"); ?>
			</td>
		</tr>
		<?
	}


	 ###############################################################
	# Special updating processes specific to this element type
	function update_options_special($prefix='') {
		if ($this->other) $this->other_text = gpc_stripslashes($_REQUEST[$prefix.'other_text']);
		$this->other = gpc_stripslashes($_REQUEST[$prefix.'other']);
		if($_REQUEST[$prefix.'columns']) $this->columns = max(1, (int) $_REQUEST[$prefix.'columns']);
	}


	 ######################################
	# Special element-dependant processing
	function process_special($prefix='') {
		$this->other_text = gpc_stripslashes($_REQUEST[$prefix.'other_text']);
	}

	 #################################
	# Prints the text box to the form
	function render ($prefix='') {
		echo ("<table cellpadding=0 cellspacing=0 border=0><tr>");
		$i = 0;
		if (!empty($this->options)) {
			reset($this->options);
			while(list($key,$val) = each($this->options)) {
				if($this->multiple) {
					echo ("<td valign=middle>&nbsp;<input type=checkbox name=\"$prefix"."value[$i]\" value=\"$key\" ".((in_array($key,$this->value))?" checked":"")."></td><td valign=middle>$val</td>");
				} else {
					echo ("<td valign=middle>&nbsp;<input type=radio name=\"$prefix"."value\" value=\"$key\" ".(($key == $this->value)?" checked":"")."></td><td valign=middle>$val</td>");
				}
				if ((($i++) % $this->columns) == ($this->columns - 1)) {
					echo("</tr><tr>");
				}
			}
		}

		if($this->other) {
			if($this->multiple) {
				echo ("<td valign=middle>&nbsp;<input type=checkbox name=\"$prefix"."value[$i]\" value=\"!!OTHER!!\" ".((in_array("!!OTHER!!",$this->value))?" checked":"")."></td><td valign=middle>".text_box($prefix."other_text",$this->other_text,30/$this->columns,128)."</td>");
			} else {
				echo ("<td valign=middle>&nbsp;<input type=radio name=\"$prefix"."value\" value=\"!!OTHER!!\" ".(("!!OTHER!!" == $this->value)?" checked":"")."></td><td valign=middle>".text_box($prefix."other_text",$this->other_text,30/$this->columns,128)."</td>");
			}
		} else {
			echo "<td></td>";
		}
		echo("</tr></table>");
	}
}


?>
