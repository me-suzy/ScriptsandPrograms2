<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/equation/operator/operator.inc,v $
## $Revision: 1.15 $
## $Author: achadszinow $
## $Date: 2004/03/30 06:14:53 $
#######################################################################

#---------------------------------------------------------------------#

/**
* A class that all operators extend
*/
class Web_Extension_Notitia_Attribute_Type_Attribute_Xtra_Operator extends Web_Extension_Notitia_Attribute_Attribute_Xtra_Type {

	/**
	* Constructor
	*
	* @access private
	* @return &object
	*/
	function Web_Extension_Notitia_Attribute_Type_Attribute_Xtra_Operator(&$attribute_xtra) {
		parent::Web_Extension_Notitia_Attribute_Attribute_Xtra(&$attribute_xtra);
	}

	/**
	* Gets all options that this attribute can cast down to
	*
	* @access public
	* @return array
	*/
	function get_output_casting_options() {
		return $this->get_setting('output_casting_options');
	}

	/**
	* Casts a value to a different type and returns the value
	* 
	* @param mixed $value The value to cast
	* @param string $cast_type The type to cast this value to
	* @access public
	* @return mixed
	*/
	function cast_value($value, $cast_type) {
		if (!$cast_type) $cast_type = 'int';
		# If the value is an array we'll need to cast every value that it returns
		if (is_array($value)) {
			# Strip the ar off the cast type
			$cast_type = substr($cast_type, 2);
			$r_values = array();
			foreach($value as $key => $item) {
				$item = addslashes($item);
				eval("\$item = ((settype(\$item, $cast_type))?\$item:null);");
				$item = stripslashes($item);
				$r_values[$key] = $item;
			}
			return $r_values;
		} else {
			$value = addslashes($value);
			eval("\$value = ((settype(\$value, $cast_type))?\$value:null);");
			$value = stripslashes($value);
			return $value;
		}
	}

	/**
	* Gets all of this attribute types xtras as an array of xtraid_cast_type and xtra name
	*
	* @param string $order_no You can spec an order no to limit your results OR not
	* @param boolean $greater_than If you spec an $order_no you should spec if you wish to grab results > < order_no
	* @param array $allowed_casting If you spec a casting array this is an array of all the allowed types
	* @access public
	* @return array
	*/
	function get_attribute_xtras_names_wcast_types($order_no='none',$greater_than=true, $allowed_casting=array()) {
		$attribute = &$this->get_owning_attribute();
		$attribute_type = &$attribute->get_type_handler();
		$attribute_type_xtras = $attribute_type->get_attribute_xtras($order_no, $greater_than);
		$returning_xtras_names_wcast = array();
		foreach($attribute_type_xtras as $xtraid) {
			$operator = &$this->get_attribute_type_xtra($xtraid);
			$operator_type = &$operator->get_type_handler();
			$cast_type = $operator_type->get_value('cast_type');
			if (!empty($allowed_casting) && !in_array($cast_type, $allowed_casting)) continue;
			$returning_xtras_names_wcast[$xtraid.'_'.$cast_type] = $operator_type->get_name().' (Cast to '.$cast_type.')';
		}
		return $returning_xtras_names_wcast;
	}

	/**
	* Returns an array of operators which this operator can use as inputs. It limits down by only allowing operators with a smaller order_no than this one and also limits it on it's compatible input types
	*
	* @access public
	* @return array
	*/
	function get_valid_input_operators_names_wcast() {
		$our_order_no = $this->get_order_no();
		$compatiable_input_types = $this->get_setting('compatiable_input_types');
		$allowed_input_casting_types = array_keys($compatiable_input_types);
		return $this->get_attribute_xtras_names_wcast_types($our_order_no, false, $allowed_input_casting_types);
	}

	/**
	* Returns the ids of all the input operators. ALL OPERATORS SHOULD OVERWRITE THIS ONE apart from ones that don't have inputs
	*
	* @access public
	* @return array
	*/
	function get_input_operatorids() {
		return array();
	}

	/**
	* Returns the names of all the input operators.  Relies on get_input_operatorids() being
	* properly overridden by the operator subclass.
	*
	* @access public
	* @return array
	*/	
	function get_input_names() {
		$opids = $this->get_input_operatorids();
		foreach($opids as $input_operatorid) {
			$input_operator = &$this->get_attribute_type_xtra($input_operatorid);
			if ($input_operator->id) {
				$inputers_names[] = $input_operator->get_name();
			} else {
				$inputers_names[] = '<b>input operator missing! operator should be reset!</b>';
			}
		}
		return $inputers_names;
	}

	/**
	* Returns comments about the equation
	*
	* @access public
	* @return string
	*/
	function get_summary_comments() {
		return '';
	}

	/**
	* Evaluates child operators listed in the operator ids array.
	*
	* @param array &$input Input values - results passed back to calling function/operator
	* @param array $options Extra options we might need to evaluate this dynamically
	* @param mixed $operatorids An array or operator ids or codes, or a single one passed as a scalar
	* @access public
	* @return string
	*/
	function evaluate_child_operators($options=array(), $operatorids=array()) {
		# For efficiency ongoing results will be stored in our parent attributes temp array cause recursion and references are not the quickest of things with our operators.
		$input = $this->attribute_type->temp['operator_results'];

		if (is_null($operatorids)) {
			# In this case do nothing
			return $input;
		}

		if (!is_array($operatorids)) {
			# Not an array (possibly a single ID?) and we need it to be an array so turn it into one
			$operatorids = array($operatorids);
		}

		foreach($operatorids as $operatorid) {
			# If it's a code and not an operatorid
			if (strpos($operatorid, '_') !== FALSE)
			{
				# Lets get the operatorid
				list($operatorid, $ignore) = explode('_', $operatorid);
			}

			$operator = &$this->get_attribute_type_xtra($operatorid);
			# To prevent crashing
			if (!$operator->id) continue;
			$operator_type = &$operator->get_type_handler();
			$cast_type = $operator_type->get_value('cast_type');
			# Already evaluated?
			if (is_null($input[$operatorid.'_'.$cast_type])) {
				$input[$operatorid.'_'.$cast_type] = $operator_type->evaluate($input, $options);
			}
		}

		# Save what we have learnt
		$this->attribute_type->temp['operator_results'] = $input;

		return $input;
	}
}
?>