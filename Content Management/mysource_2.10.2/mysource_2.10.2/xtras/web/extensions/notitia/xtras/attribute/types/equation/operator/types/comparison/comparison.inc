<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/equation/operator/types/comparison/comparison.inc,v $
## $Revision: 1.8 $
## $Author: achadszinow $
## $Date: 2004/03/30 06:14:53 $
#######################################################################

#---------------------------------------------------------------------#

/**
* A compares operator values
*/
class Web_Extension_Notitia_Attribute_Type_Attribute_Xtra_Operator_Type_Comparison extends Web_Extension_Notitia_Attribute_Type_Attribute_Xtra_Operator {

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
		$operator1_code = $this->get_value('operator1_code');
		$operator2_code = $this->get_value('operator2_code');
		$value = NULL;

		list($operatorid1, $operator_type1) = explode('_', $operator1_code);
		list($operatorid2, $operator_type2) = explode('_', $operator2_code);

		# Evaluate the ops if need be (the returning input would be more up to date)
		$input = $this->evaluate_child_operators($options, array($operatorid1, $operatorid2));

		$op1_value = $input[$operator1_code];
		$op2_value = $input[$operator2_code];

		$op1_value = addslashes($op1_value);
		$op2_value = addslashes($op2_value);

		$comparison_tool = $this->get_value('comparision_tool');
		switch ($comparison_tool) {
			case '==': # Equal
			case '<=': # Less than or equal to
			case '!=': #Not equal
			case '>': # Greater than
			case '<': # Less than
			case '>=': # Greater than or equal to
				eval("\$value = ('$op1_value' $comparison_tool '$op2_value');");
			break;
			# In this code area l = length if you didn't pick up on that
			case '=l': # Equal length
			case '!=l': # Not equal length
			case '!=l': # Not equal length
			case '>l': # Greater than length
			case '<l': # Less than length
			case '>=l': # Greater than or equal to length
			case '<=l': # Less than or equal to length
				# Get rid of the l
				$comparison_tool = str_replace('l', '', $comparison_tool);
				eval("\$value = (strlen($op1_value) $comparison_tool strlen($op2_value));");
			break;
		}
		$value = stripslashes($value);
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
		$operator1_code = $this->get_value('operator1_code');
		$operator2_code = $this->get_value('operator2_code');
		list($operatorid1, $operator_type1) = explode('_', $operator1_code);
		list($operatorid2, $operator_type2) = explode('_', $operator2_code);
		return array($operatorid1, $operatorid2);
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
		$operator_code = array('operator1_code', 'operator2_code');
		foreach ($operator_code as $code) {
			if (preg_match('/([0-9]+)_(.*+)/', $parameters[$code], $matches)) {
				list($waste, $xtraid, $format) = $matches;
				$parameters[$code] = $dupe_map[$xtraid].'_'.$format;
			}
		}
		return $parameters;
	}

	/**
	* Returns comments about the operator.  Needs to have comparison tool bit fixed.
	*
	* @access public
	* @return string
	*/
	function get_summary_comments() {
		$inputers_names = $this->get_input_names();
		$comparison_tool = htmlentities($this->get_value('comparison_tool')); // those symbols can be pesky
		return "Is $inputers_names[0] $comparison_tool $inputers_names[1] ?";
	}
}
?>