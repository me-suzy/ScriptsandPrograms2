<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/equation/operator/types/format/format.inc,v $
## $Revision: 1.8 $
## $Author: achadszinow $
## $Date: 2004/03/30 06:14:53 $
#######################################################################

#---------------------------------------------------------------------#

/**
* A Formatting operator
*/
class Web_Extension_Notitia_Attribute_Type_Attribute_Xtra_Operator_Type_Format extends Web_Extension_Notitia_Attribute_Type_Attribute_Xtra_Operator {

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
		$operator_code_to_format = $this->get_value('operator_code_to_format');
		# Evaluate the ops if need be. The new input array will be more up to date.
		$input = $this->evaluate_child_operators($options, $operator_code_to_format);
		$value = NULL;

		if (isset($input[$operator_code_to_format])) {
			$value = $input[$operator_code_to_format];
			list($xtraid, $cast_type) = explode('_', $operator_code_to_format);

			if ($cast_type == 'int' || $cast_type == 'float' || $cast_type == 'double' || $cast_type == 'real') {
				$dec_point_char = $this->get_value('dec_point_char');
				if (!$dec_point_char) {
					$dec_point_char = '.';
				}
				$dec = $this->get_value('decimals');
				if ($dec) {
					switch ($this->get_value('operatorid_to_format')) {
						case 'r': # Round the value
							$value = round($value, $dec);
							break;
						default: case 't': # Trim the value
							# Trimming will be done by the number format
							break;
					}
					$value = number_format($value, $dec, $dec_point_char, $this->get_value('thousands_sep_char'));
				} else {
					list($int, $float) = explode('.', $value);
					$start = number_format($int, '0', $dec_point_char, $this->get_value('thousands_sep_char'));
					# This '.' is notitia float format so it's not $dec_point_char
					if ($float > 0) {
						$value = $start.$dec_point_char.$float;
					} else {
						$value = $start;
					}
				}
			}

			$value = $this->get_value('prefix').$value.$this->get_value('suffix');
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
		$operator_code_to_format = $this->get_value('operator_code_to_format');
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

		$code = 'operator_code_to_format';
		if (preg_match('/([0-9]+)_(.*+)/', $parameters[$code], $matches)) {
			list($waste, $xtraid, $format) = $matches;
			$parameters[$code] = $dupe_map[$xtraid].'_'.$format;
		}
		return $parameters;
	}

	/**
	* Returns comments about the operator. 
	*
	* @access public
	* @return string
	*/
	function get_summary_comments() {
		$inputers_names = $this->get_input_names();
		$cast_type = htmlentities($this->get_value('cast_type')); 
		$res = "Convert $inputers_names[0] to a $cast_type";
		$thousands_separator = htmlentities($this->get_value('thousands_sep_char')); 
		if ($thousands_separator) {
			$res .= ", separating thousands using \"$thousands_separator\"";
		}
		$decimal_point = htmlentities($this->get_value('dec_point_char'));
		if ($decimal_point) {
			$res .= ", using \"$decimal_point\" as the decimal point";
		}
		$decimal_places = htmlentities($this->get_value('decimals')); 
		$round_or_trim = $this->get_value("operatorid_to_format");
		if ($decimal_places) {
			$res .= ", expressing it to $decimal_places decimal places";
			if ($round_or_trim == 't') {
				$res .= " by trimming";
			} else {
				$res .= " by rounding";
			}
		}
		$prefix = htmlentities($this->get_value('prefix')); 
		if ($prefix) {
			$res .= ", prepending \"$prefix\"";
		}
		$suffix = htmlentities($this->get_value('suffix')); 
		if ($suffix) {
			$res .= ", appending \"$suffix\"";
		}

		return $res;
	}


}
?>