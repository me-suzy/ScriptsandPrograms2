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
## File: include/site_design_base.inc
## Desc: a basic class that is inherited by all classes used in site design 
##       and allows setting of a value to a passed name and the 
##       retrieving the values
## $Source: /home/cvsroot/mysource/include/site_design_base.inc,v $
## $Revision: 2.15 $
## $Author: csmith $
## $Date: 2004/01/15 23:23:07 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/webobject.inc");
#---------------------------------------------------------------------#


class Site_Design_Base extends WebObject {

	var $_set_vars='';		# variables set by the parsed file
	var $_custom_vars='';	# site admin customised vars for this instance of the design

	###########################################################################################################################
	# NOTES on Vars.  
	###########################################################################################################################
	# _set_vars is an array of array's with the structure :
	# _set_vars = array("[NAME]" => array(	
	# 										value_type  = "[TEXT|BOOLEAN|COLOUR|SET|VARIABLE]"
	# 										value       = "[VALUE|VARIABLE_NAME]",
	#                                       description = "[ Some thing useful so that can explain this vars purpose ]",
	# 									[	options     = array("[value]" => "[display]")  ],
	# 									  )
	# so _set_vars can be text vars, boolean's, colour vars, one of a set of vars or a reference to another variable
	# DEFAULT: text-value
	#
	# NOTE ON Variable References : 
	#	they do not reference something in the current scope, always in a higher scope.
	#	this is because it would be possible for people to set up recursive variable calls, 
	#	with the potential of killing a server
	# 
	# _custom_vars is an array name/value pairs, and can be set for any variable except var references
	#
	###########################################################################################################################

	var $_owner;	# this is a reference to the variable that owns this
					# this is used so that get_var() can call further up-stream
					# if it cannot find what it wants in the local instance
					# and so that the top-level object can be referenced


	function Site_Design_Base(&$_owner) {
		WebObject::WebObject();
		$this->_owner = &$_owner;
	}#end Site_Design_Base()

	 #######################################################################################
	# creates a variable from the set tag (as appossed to var being from a tags attributes)
	function set_from_tag(&$tag, $ignore_non_critical_errors_for=Array()) {

		# if we ain't a set operation what are we doing here ????????
		if ($tag['operation'] != "set") {
			$this->_set_error("The tag passed to set_from_tag() was not a SET operation, WHY ????", __FILE__, __LINE__);
			return false;
		}


		$options = array();

		# if this is a set type then get its options
		if (strtolower($tag['attributes']['value_type']) == "set") {
			if (!$tag['self_terminating']) {
				foreach($tag['contents'] as $element) {	
					if ($element['operation'] == "option") {
						$options[$element['attributes']['value']] = $element['contents'][0]['contents'];
					}#end if
				}#end foreach
			}#end if

		# anything that isn't a value type of set should self terminate, could cause other problems
		} elseif (!$tag['self_terminating']) {
			$this->_set_error("The &lt;MySource_SET&gt tags '".$tag['attributes']['name']."' should self terminate, some HTML code could be lost", __FILE__, __LINE__);
		}

		$tag['attributes']['value_type'] = trim(strtolower($tag['attributes']['value_type']));

		# if the type is invalid then default to text
		if ($tag['attributes']['value_type'] && !in_array($tag['attributes']['value_type'], array("text","boolean","colour","set","variable"))) {
			$this->_set_error("Invalid Value Type '".$tag['attributes']['value_type']."' for '".$tag['attributes']['name']."'. Valid types are 'text','boolean','colour','set' or 'variable'. Defaulted to 'text'.", __FILE__, __LINE__);
			$tag['attributes']['value_type'] = "text";
		}#end if

		return $this->_set_var($tag['attributes']['name'], $tag['attributes']['value'], $tag['attributes']['value_type'], $tag['attributes']['description'], $options, $ignore_non_critical_errors_for);

	}#end set_from_tag()

	 #######################################################################################
	# creates a variable for the passed values
	function _set_var($name='', $value='', $value_type='', $description='', $options=array(), $ignore_non_critical_errors_for=Array()) {

		if (!trim($name)) {
			$this->_set_error("Cannot set a variable without a name", __FILE__, __LINE__);
			return false;
		}

		$name = strtolower($name);

		# if there is a value_type, use it
		if (trim($value_type)) {
			$value_type = strtolower(trim($value_type));

		# else if this variable has already been declared, use it's value_type
		} elseif ($this->_set_vars[$name]) {
			$value_type = $this->_set_vars[$name]['value_type'];

			# if this is a set then we want its options
			if ($value_type == "set") {
				$options = $this->_set_vars[$name]['options'];
			}

		# else use the default 'text'
		} else {
			$value_type = "text";

		}#end if

		# some types need special processing (or validation), do it now
		switch ($value_type) {

			case "boolean" :
				$value = $this->_get_bool_value($value);
			break;

			case "colour" :

				# do some checking

				# strip hash
				$value = ereg_replace("^#", "", $value);

				# they can have blank values
				if (strlen($value) > 0) {

					# check length
					if (strlen($value) > 6) {
						if (!in_array($name, $ignore_non_critical_errors_for)) {
							$this->_set_error("Colour variable '$name' is to long, truncating to six characters", __FILE__, __LINE__);
						}
						$value = substr($value, 0, 6);
					} elseif (strlen($value) < 6) {
						if (!in_array($name, $ignore_non_critical_errors_for)) {
							$this->_set_error("Colour variable '$name' is to short, padding with zero's (0) to six characters", __FILE__, __LINE__);
						}
						$value = str_pad($value, 6, "0");
					}

					# make sure we only have the valid chars
					if (($new_value = eregi_replace("[^0-9a-f]", "0", $value)) != $value) {
						if (!in_array($name, $ignore_non_critical_errors_for)) {
							$this->_set_error("Colour variable '$name' had illegal character(s), these have been replaced with zero (0)", __FILE__, __LINE__);
						}
						$value = $new_value;
						unset($new_value);
					}

				}#end if

			break;

			case "set" :

				if (!$options) {
					$this->_set_error("Set variable '$name' doesn't have any options, how can anything value be chosen ??", __FILE__, __LINE__);
					return false;
				}#end if

				if (!in_array($value, array_keys($options))) {
					reset($options);
					$value = key($options);
					if (!in_array($name, $ignore_non_critical_errors_for)) {
						$this->_set_error("Set variable '$name' attempted to set a value that is not one of its options, set to the first option ('$value')", __FILE__, __LINE__);
					}
				}#end if

			break;

			case "variable" :
				
				# cannot set variable references on the global scope, 
				# to much chance for recursion when getting the value
				if (!$this->_owner) {
					$this->_set_error("Cannot set variable reference called '$name' to '$value' on the global scope, just call '$value' when you need the value", __FILE__, __LINE__);
					return false;
				}

			break;

		}#end switch


		if (!$this->_set_vars[$name]) $this->_set_vars[$name] = array();

		$this->_set_vars[$name]['value_type']  = strtolower($value_type);
		$this->_set_vars[$name]['value']       = $value;
		if ($description) $this->_set_vars[$name]['description'] = $description;
		if ($options)     $this->_set_vars[$name]['options']     = $options;

		return true;

	}#end _set_var()

	 #########################################################################################
	# takes and value and tries to guess what they want boolean value they want it to be
	function _get_bool_value($value=0) {

		switch(strtolower(trim($value))) {
			# true values
			case "true" : 
			case "yes"  :
			case "1"    :
			case "on"   :
				$value = 1;
			break;

			# false values
			case "false" : 
			case "no"    :
			case "0"     :
			case "off"   :
			case ""      :
				$value = 0;
			break;

			# oh well
			default :

				$this->_set_error("Cannot set a identify boolean value for '$value', set to false", __FILE__, __LINE__);
				$value = 0;

		}#end switch

		return $value;

	}#end get_bool_value()

	 #######################################################################################
	# creates a variable using just name and value, creates using the defaults
	function set_var($name='', $value='') {
		$this->_set_var($name, $value);
	}

	function set_custom_var($name='', $value='') {
		$name = strtolower($name);
		if ($name) $this->_custom_vars[$name] = $value;
	}

	 ############################################
	# deletes a variable for the passed values
	function delete_var($name='') {
		$name = strtolower($name);
		unset($this->_set_vars[$name]);
		unset($this->_custom_vars[$name]);
	}#end delete_var()

	 #####################################################
	# returns the value for the passed variable name
	function get_var($name='') {
		$name = strtolower($name);

		if ($name) {
			if (isset($this->_custom_vars[$name])) {
				$var = $this->_set_vars[$name];
				$var['value'] = $this->_custom_vars[$name];

				return $var;

			} else if ($this->_set_vars[$name]) {

				# if this is a variable reference, return from our owner
				if ($this->_set_vars[$name]['value_type'] == "variable") {
					return $this->_get_var_from_owner($this->_set_vars[$name]['value'], $pageid);

				# else just return var
				} else {
					return $this->_set_vars[$name];

				}#end if

			# oh well, try our owner
			} else {
				return $this->_get_var_from_owner($name, $pageid);

			}#end if

		}#end if

		return array();

	}#end get_var()

	 #######################################################################################
	# returns the value for the passed variable name from the owner's scope
	function _get_var_from_owner($name='') {
		if ($this->_owner) {
			if (method_exists($this->_owner, "get_var")) {
				return $this->_owner->get_var($name, $pageid);
			}#end if
		}#end if
		return array();
	}

	 #####################################################
	# returns the value for the passed variable name
	function get_val($name='', $pageid=0) {

		$name = strtolower($name);

		if ($name) {
			if (isset($this->_custom_vars[$name])) {
				return $this->_custom_vars[$name];
			} else if (!empty($this->_set_vars[$name])) {

				# if this is a variable reference, return from our owner
				if ($this->_set_vars[$name]['value_type'] == "variable") {
					return $this->_get_val_from_owner($this->_set_vars[$name]['value'], $pageid);

				# else just return var
				} else {
					return $this->_set_vars[$name]['value'];

				}#end if

			# oh well, try our owner
			} else {
				return $this->_get_val_from_owner($name, $pageid);

			}#end if

		}#end if

		return "";

	}#end get_val()

	 #######################################################################################
	# returns the value for the passed variable name from the owner's scope
	function _get_val_from_owner($name='', $pageid=0) {
		if ($this->_owner) {
			if (method_exists($this->_owner, "get_val")) {
				return $this->_owner->get_val($name, $pageid);
			}#end if
		}#end if
		return "";
	}

	 #######################################################################################
	# print's the value for the passed name escaping as necessary
	function print_val(&$tag, $pageid=0) {
		echo $this->_print_modified($tag, $this->get_val($tag['attributes']['name'], $pageid));
	}

	 #######################################################################################
	# used by print_val to escape the value if required, separate fn from print_val()
	# so that print_val() can be overiden as necessary
	function _print_modified(&$tag, $value='') {
		if ($value) {
			# if they want escaping
			if (isset($tag['attributes']['escape']) && $this->_get_bool_value($tag['attributes']['escape'])) {
				$value = htmlspecialchars(addslashes($value));
			}
		}#end if
		return $value;
	}

	 #######################################################################################
	# returns a reference to the owner of the top-level object (ie one without an owner)
	function &get_top_owner() {
		# if we have an owner then we ain't the one
		if (isset($this->_owner)) {
			if (method_exists($this->_owner, "get_top_owner")) {
				return $this->_owner->get_top_owner();
			}#end if
		}#end if
		return $this;

	}#end if


	 ########################################################
	# Sets the owner of this object to the passed reference
	function reset_owner(&$owner) {
		unset($this->_owner);
		if ($owner) {
			$this->_owner = &$owner;
		}
	}

	 ########################################################
	# Unsets the owner of this object returns its reference
	function &unset_owner() {
		$tmp = &$this->_owner;
		unset($this->_owner);
		return $tmp;
	}

	 ########################################################
	# Creates a copy of this object and returns its reference
	function &copy() {
		$owner = &$this->_owner;
		unset($this->_owner);
		$tmp = $this;
		$this->_owner = &$owner;
		return $tmp;
	}

	  #########################################
	 # Converts the object into a nice string
	function pack() {

		$tmp_owner = &$this->unset_owner();
		$packed = serialize($this);
		$this->reset_owner($tmp_owner);

		return $packed;

	}#end pack()


	 ########################################################
	# Takes a string, and unpacks it into the object itself.
	function unpack($stringified='', $class='') {
		$new_object = unserialize($stringified);
		if (get_class($new_object) != strtolower($class)) {
			$this->_set_error('Attempt to unpack yielded an object which did not appear to be a \''.$class.'\': '.$stringified, __FILE__, __LINE__);
			return null;
		}

		return $new_object;

	}#end unpack()

	  #########################################################################
	 # Takes a old version of itself and uses the old versions custom vars to 
	# attempting to save any customisations, previously made
	function update(&$old_this) {

		if (get_class($old_this) != get_class($this)) return false;

		if (!empty($this->_set_vars)) {
			foreach($this->_set_vars as $name => $var) {
				# if there is a custom var called $name in the old object,
				# save its value
				if (isset($old_this->_custom_vars[$name])) {
					$this->set_custom_var($name, $old_this->_custom_vars[$name]);
				}
			}#end foreach
		}

		return true;

	}#end update()


	 ########################################################################
	# prints and processes a backend stuff for customising the _set_vars
	function print_vars_backend(&$backend, $prefix_name='', $uneditable_vars=Array(), $section_title='Editable Values') {

		$prefix_name = str_replace(Array(' ', "\t"), '_', $prefix_name);

		$arr_name = $prefix_name."_set_vars";

		$$arr_name = $_REQUEST[$arr_name];

		$changes_made = false;
		if (!empty($$arr_name)) {
			foreach($$arr_name as $name => $value) {
				$value = gpc_stripslashes($value);
				if ($this->get_val($name) != $value) {
					$this->set_custom_var($name, $value);
					$changes_made = true;
				}
			}
		}
		# only if vars exists try and edit them
		if ($this->_set_vars) {

			$var_keys = array_keys($this->_set_vars);

			# remove all uneditable vars
			foreach($var_keys as $k => $name) {
				# only if they are allowed to edit them
				if (in_array($name, $uneditable_vars)) {
					unset($var_keys[$k]);
				}
			}

			# if there are vars to edit
			if (!empty($var_keys)) {

				$backend->open_section($section_title);
				foreach($var_keys as $name) {
					$field_name = $arr_name."[$name]";
					$this->print_var_field($backend, $name, $field_name);
				}#end foreach

			}#end if

		}#end if vars exists

		return $changes_made;

	}#end print_vars_backend()

	 ########################################################################
	# prints an individual a backend stuff for customising the _set_vars
	function print_var_field(&$backend, $name='', $field_name='', $field_size='50') {

		$name = strtolower($name);

		if (!$this->_set_vars[$name]) return;
		if (stristr($name, "class")) return;

		# depending on the type of variable do different things
		# NOTE: cannot set the edit a variable reference
		switch ($this->_set_vars[$name]['value_type']) {
			
			case "text" : 
				$field = text_box($field_name, $this->get_val($name), $field_size);
			break;

			case "boolean" :
				$field = combo_box($field_name, array("0" => "No", "1" => "Yes"), $this->get_val($name));
			break;

			case "colour" :
				$field = colour_box($field_name,$this->get_val($name), true);
			break;

			case "set" :
				$field = combo_box($field_name, $this->_set_vars[$name]['options'], $this->get_val($name));
			break;

			case "variable" :
				$field = "Variable reference to '".$this->_set_vars[$name]['value']."' whose currently value is '".$this->get_val($name)."'";
			break;

		}#end switch

		# if we know what to do with this variable then open field and print form input
		if ($field) {

			$field_name = ucwords(str_replace("_", " ", $name));
			$backend->open_field($field_name, 'one_liner');

			if ($this->_set_vars[$name]['description']) {
			?> 
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td>
			<?
			}
			echo $field;
			if ($this->_set_vars[$name]['description']) {
			?> 
						</td>
					</tr>
					<tr>
						<td class=backend_fineprint>
							<?=$this->_set_vars[$name]['description']?>
						</td>
					</tr>
				</table>
							
			<?

			}

		}#end if

	}#end print_var_field()


}#end class Site_Design_Base

?>
