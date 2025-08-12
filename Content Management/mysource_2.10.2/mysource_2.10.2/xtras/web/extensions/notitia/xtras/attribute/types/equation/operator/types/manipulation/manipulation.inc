<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/equation/operator/types/manipulation/manipulation.inc,v $
## $Revision: 1.7 $
## $Author: achadszinow $
## $Date: 2004/03/30 06:14:53 $
#######################################################################

#---------------------------------------------------------------------#

/**
* A Formatting operator
*/
class Web_Extension_Notitia_Attribute_Type_Attribute_Xtra_Operator_Type_Manipulation extends Web_Extension_Notitia_Attribute_Type_Attribute_Xtra_Operator {

	 ################################################################
	# This is a static thingy that stores special information about
	# a particular operator type.
	var $settings = array(
		# What types of value can this attribute handle as input
		'compatiable_input_types' => array(
				'int' => 'Integer',
				'float' => 'Float',
				'double' => 'Double',
				'real' => 'Real',
				'bool' => 'Boolean',
				'string' => 'String'
			),
		# Programmed in here so other attributes can decide if it will
		# return a compatible value with it's input prior to getting the value
		'output_casting_options' => array(
				'int' => 'Integer',
				'float' => 'Float',
				'double' => 'Double',
				'real' => 'Real',
				'bool' => 'Boolean',
				'string' => 'String'
			)
		);

	/**
	* Evaluates this operator
	* 
	* @param array &$input Input values for this operator however this operator doesn't take any input so ignore
	* @param array $options Extra options we might need to evaluate this dynamically
	* @access public
	* @return mixed
	*/
	function evaluate($input, $options=array()) {
		$value = NULL;
		$operator_code_to_format = $this->get_value('operator_code_to_manipulate');
		# Evaluate the ops if need be. The new input array will be more up to date.
		$input = $this->evaluate_child_operators($options, $operator_code_to_format);

		$formatting_type = $this->get_value('formatting_type');
		$value = $input[$operator_code_to_format];
		switch($formatting_type) {
			# Functions where there are no args just the manipulation value
			case 'strlen':
			case 'strtoupper':
			case 'strtolower':
			case 'str_word_count':
				eval("\$value = $formatting_type('{$value}');");
				break;
			# Functions where there is manipulation value first, followed by an optional arg
			case 'trim':
			case 'strip_tags':
				$value1 = $this->get_value('value1');
				if ($value1 == '') {
					eval("\$value = $formatting_type('{$value}');");
				} else {
					eval("\$value = $formatting_type('{$value}','{$value1}');");
				}
				break;
			# Functions where there is manipulation value first, followed by an arg
			case 'strstr':
			case 'substr_count':
			case 'strcmp':
				$value1 = $this->get_value('value1');
				eval("\$value = $formatting_type('{$value}','{$value1}');");
				break;
			# Functions where there are two mandatory args first followed by manipulation value
			case 'str_replace':
			case 'ereg_replace':
				$value1 = $this->get_value('value1');
				$value2 = $this->get_value('value2');
				eval("\$value = $formatting_type('{$value1}','{$value2}','{$value}');");
				break;
			# Functions where there is manipulation value first, followed by an arg, followed by an optional arg
			case 'substr':
			case 'strpos':
			case 'substr':
				$value1 = $this->get_value('value1');
				$value2 = $this->get_value('value2');
				if ($value2 == '') {
					eval("\$value = $formatting_type('{$value}','{$value1}');");
				} else {
					eval("\$value = $formatting_type('{$value}','{$value1}','{$value2}');");
				}
				break;
			# Functions where there is manipulation value first, followed by an arg, followed by an optional arg, followed by an optional arg
			case 'str_pad':
			case 'substr_replace':
				$value1 = $this->get_value('value1');
				$value2 = $this->get_value('value2');
				$value3 = $this->get_value('value3');
				if ($value2 == '' && $value3 == '') {
					eval("\$value = $formatting_type('{$value}','{$value1}');");
				} elseif ($value3 == '') {
					eval("\$value = $formatting_type('{$value}','{$value1}','{$value2}');");
				} else {
					eval("\$value = $formatting_type('{$value}','{$value1}','{$value2}',{$value3});");
				}
				break;
			# Functions where there is manipulation value first, followed by an arg, followed by an arg
			case 'strncmp':
				$value1 = $this->get_value('value1');
				$value2 = $this->get_value('value2');
				eval("\$value = $formatting_type('{$value}','{$value1}','{$value2}');");
				break;
			default:
				break;
		}

		$cast_type = $this->get_value('cast_type');
		return $this->cast_value($value, $cast_type);
	}

	/**
	* Returns the ids of all the input operators. ALL OPERATORS SHOULD OVERWRITE THIS ONE
	*
	* @access public
	* @return array
	*/
	function get_input_operatorids() {
		$operator_code_to_format = $this->get_value('operator_code_to_manipulate');
		list($operatorid, $cast_type) = explode('_', $operator_code_to_format);
		return array($operatorid);
	}

	/**
	* Returns the remapped parameters for duping
	*
	* @access public
	* @param array $dupe_map - The dupe map array
	* @return array - The parameters
	*/
	function type_defined_remapper($dupe_map) {
		$parameters = $this->parameters;

		$code = 'operator_code_to_manipulate';
		if (preg_match('/([0-9]+)_(.*+)/', $parameters[$code], $matches)) {
			list($waste, $xtraid, $format) = $matches;
			$parameters[$code] = $dupe_map[$xtraid].'_'.$format;
		}
		return $parameters;
	}

	function formatting_type_is($type) {
		$saved_type = $this->get_value('formatting_type');
		return ($saved_type == $type);
	}

	/**
	* Returns comments about the operator
	*
	* @access public
	* @return string
	*/
	function get_summary_comments() {
		$names = $this->get_input_names();
		$input = $names[0];
		$formatting_type = $this->get_value('formatting_type');
		switch ($formatting_type) {
			case "str_replace":
				$replacee = $this->get_value('value1');
				$replacer = $this->get_value('value2');
				return "Replace \"$replacee\" with \"$replacer\" in $input";
			case "substr_replace":
				$replacer = $this->get_value('value1');
			    $start_index = $this->get_value('value2');
				$length = $this->get_value('value3');

				$res = "Get the region in $input beginning at the {$start_index}th character and ending";
				if ($length < 0) {
					$length *= -1; // make it positive
					$res .= " $length characters from the end";
				} else {
					$res .= " $length characters later";
				}
				$res .= " and replace its contents with \"$replacer\"";
				return $res;
			case "ereg_replace":
				$replacee = $this->get_value('value1');
				$replacer = $this->get_value('value2');
				return "Replace occurrences of the regular expression \"$replacee\" with \"$replacer\" in $input";
			case "strstr":
				$target = $this->get_value('value1');
				return "Get the first occurrence of \"$target\" in $input";
			case "strpos":
				$target = $this->get_value('value1');
				$start_pos = $this->get_value('value2');
				$res = "Find the index of the first occurence of \"$target\" in $input";
				if ($start_pos) {
					$res .= ", starting from character $start_pos";
				}
				return $res;
			case "substr":
				$start_index = $this->get_value('value1');
				$length = $this->get_value('value2');
				return "Starting from the {$start_index}th character, get $length characters of $input, ";
			case "trim":
				return "Remove whitespace from the beginning and end of $input";
			case "strlen":
				return "Get the length of $input";
			case "substr_count":
				$target = $this->get_value('value1');
				return "Count the number of times \"$target\" occurs in $input";
			case "strtoupper":
				return "Convert $input to upper case";
			case "strtolower":
				return "Convert $input to lower case";
			case "strip_tags":
				return "Strip HTML and PHP tags from $input";
			case "str_word_count":
				return "Count the number of words in $input";
			case "str_pad":
				$target_length = $this->get_value('value1');
				$pad_chars = $this->get_value('value2');
				$pad_position = $this->get_value('value3');
				$res = "Pad $input ";
				if ($pad_chars) {
					$res .= " with the characters \"$pad_chars\"";
				} else {
					$res .= " with spaces";
				}
				switch ($pad_position) {
					case "STR_PAD_LEFT":
						$res .= " on the left";
						break;
					case "STR_PAD_BOTH":
						$res .= " on both sides";
						break;
					default:
						$res .= " on the right";
				}
				$res .= " to make its length equal to $target_length";
				return $res;
			case "strcmp":
				$comparison_string = $this->get_value('value1');
				return "Return a negative number if $input is \"less than\" \"$comparison_string\",  a positive number if $input is \"greater\", or zero if they are equal.";
			case "strncmp":
				$comparison_string = $this->get_value('value1');
				$length = $this->get_value('value2');
				return "Return a negative number if the first $length characters of $input are  \"less than\" the first $length characters of \"$comparison_string\",  a positive number if $input is \"greater\", or zero if they are equal.";
			return "";
		}

	
	
	}

}
?>