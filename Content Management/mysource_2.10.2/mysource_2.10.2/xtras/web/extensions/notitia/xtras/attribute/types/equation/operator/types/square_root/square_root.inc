<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/equation/operator/types/square_root/square_root.inc,v $
## $Revision: 1.6 $
## $Author: achadszinow $
## $Date: 2004/03/30 06:14:53 $
#######################################################################

#---------------------------------------------------------------------#

/**
* Square roots an operator value
*/
class Web_Extension_Notitia_Attribute_Type_Attribute_Xtra_Operator_Type_Square_Root extends Web_Extension_Notitia_Attribute_Type_Attribute_Xtra_Operator {

	 ################################################################
	# This is a static thingy that stores special information about
	# a particular operator type.
	var $settings = array(
		# What types of value can this attribute handle as input
		'compatiable_input_types' => array(
				'int' => 'Integer',
				'float' => 'Float',
				'double' => 'Double',
				'real' => 'Real'
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
		$operator_code_to_square_root = $this->get_value('operator_code_to_square_root');
		# Evaluate the ops if need be. The new input array will be more up to date.
		$input = $this->evaluate_child_operators($options, $operator_code_to_square_root);
		$value = NULL;

		if (isset($input[$operator_code_to_square_root])) {
			if ($input[$operator_code_to_square_root] > -1) {
				$value = $input[$operator_code_to_square_root];
				$value = sqrt($value);
			}
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
		$operator_code_to_square_root = $this->get_value('operator_code_to_square_root');
		list($operatorid, $operator_type) = explode('_', $operator_code_to_square_root);
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

		$code = 'operator_code_to_square_root';
		if (preg_match('/([0-9]+)_(.*+)/', $parameters[$code], $matches)) {
			list($waste, $xtraid, $format) = $matches;
			$parameters[$code] = $dupe_map[$xtraid].'_'.$format;
		}
		return $parameters;
	}

	/**
	* Returns comments about the operator
	*
	* @access public
	* @return string
	*/
	function get_summary_comments() {
		$names = $this->get_input_names();
		return "sqrt($names[0])";
	}

}
?>