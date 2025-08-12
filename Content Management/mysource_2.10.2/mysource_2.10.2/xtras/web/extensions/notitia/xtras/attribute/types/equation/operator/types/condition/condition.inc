<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/equation/operator/types/condition/condition.inc,v $
## $Revision: 1.14 $
## $Author: achadszinow $
## $Date: 2004/04/01 07:35:31 $
#######################################################################

#---------------------------------------------------------------------#

/**
* If condition is true returns operator 1's value else operator 2's value
*/
class Web_Extension_Notitia_Attribute_Type_Attribute_Xtra_Operator_Type_Condition extends Web_Extension_Notitia_Attribute_Type_Attribute_Xtra_Operator {

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
	* @param array &$input Input values - results passed back to calling function/operator
	* @param array $options Extra options we might need to evaluate this dynamically
	* @access public
	* @return mixed
	*/
	function evaluate($input, $options=array()) {
		$value = NULL;
		$input_operators_settings = $this->get_value('input_operators_to_compare');
		$operator1_code = $this->get_value('operator1_code');
		$operator2_code = $this->get_value('operator2_code');

		if (!empty($input_operators_settings) && $operator1_code && $operator2_code) {

			# Evaluate the ops if need be. The returning array would be more up to date than any we have been given.
			$input = $this->evaluate_child_operators($options, $input_operators_settings);

			if (count($input_operators_settings) > 0) {
				# If there's multiple values
				$logic = $this->get_value('condition_logic');
				if (!$logic) $logic = '&&';

				# Translate whatever they gave us into the string true or false for evaluation in the logic string
				foreach($input_operators_settings as $operator_code) {
					$values[] = (($input[$operator_code])?'true':'false');
				}
				$logic_string = implode(' '.$logic.' ', $values);
			}
			if ($logic_string == null) {
				$logic_string = 'false';
			}
			$logic_string = addslashes($logic_string);
			eval("\$logic_result = (($logic_string)?true:false);");
			if ($logic_result) {
				$input = $this->evaluate_child_operators($options, $operator1_code);
				$value = $input[$operator1_code];
			} else {
				$input = $this->evaluate_child_operators($options, $operator2_code);
				$value = $input[$operator2_code];
			}

			$value = stripslashes($value);
		}

		$cast_type = $this->get_value('cast_type');
		return $this->cast_value($value, $cast_type);
	}

	/**
	* Prints a table of dropdowns so you can select what operators get compared
	*
	* @access public
	* @return void
	*/
	function print_operator_selectors() {
		$input_count = $this->get_input_count();
		$columns = (($input_count<4)?$input_count:4);
		$rows = (int) ($input_count / $columns);
		$remainder_cells = ($input_count - ($columns*$rows));

		$prior_xtras_list = $this->get_valid_input_operators_names_wcast();

		$input_operators_settings = $this->get_value('input_operators_to_compare');

		?>
		<table bgcolor="#888888" cellpadding="0" cellspacing="0" border="0"><tr><td>
		<table cellspacing="1" cellpadding="3">
			<tr bgcolor="#aaaaaa">
				<td colspan="<?=$columns?>"><b>Operators to Compare</b></td>
			</tr>
			<tr bgcolor="#dddddd">
		<?
		for($i=0; $i < $columns; $i++) {
			?>
				<td>Column <?=($i+1)?></td>
			<?
		}
		?>
			</tr>
		<?
		for($i=0; $i < $rows; $i++) {
			?>
			<tr bgcolor="#eeeeee">
			<?
			for($j=0; $j < $columns; $j++) {
				$combo_box = combo_box('input_combo_'.$i.'_'.$j, $prior_xtras_list, $input_operators_settings[$i.'_'.$j]);
				?>
				<td><?=$combo_box?></td>
				<?
			}
			?>
			</tr>
			<?
		}
		if ($remainder_cells > 0) {
			?>
			<tr bgcolor="#eeeeee">
			<?
			for($j=0; $j < $remainder_cells; $j++) {
				$combo_box = combo_box('input_combo_'.($rows).'_'.$j, $prior_xtras_list, $input_operators_settings[$rows.'_'.$j]);
				if (($j+1) == $remainder_cells) {
					$colspan = $columns - $remainder_cells + 1;
				} else {
					$colspan = 1;
				}
				?>
				<td colspan="<?=$colspan?>"><?=$combo_box?></td>
				<?
			}
			?>
			</tr>
			<?
		}
		?>
		</table>
		</td></tr></table>
		<?
	}

	/**
	* Processes the table of dropdowns so you can select what operators get compared
	*
	* @access public
	* @return void
	*/
	function process_operator_selectors() {
		$input_operators_settings = &$this->get_value('input_operators_to_compare');
		$input_count = $this->get_input_count();
		$columns = (($input_count<4)?$input_count:4);
		$rows = (int) ($input_count / $columns);
		$remainder_cells = ($input_count - ($columns*$rows));

		for($i=0; $i<$rows; $i++) {
			for($j=0; $j<$columns; $j++) {
				if ($_POST['input_combo_'.$i.'_'.$j] && $input_operators_settings[$i.'_'.$j] != $_POST['input_combo_'.$i.'_'.$j]) {
					$input_operators_settings[$i.'_'.$j] = $_POST['input_combo_'.$i.'_'.$j];
				}
			}
		}

		for($j=0; $j<$remainder_cells; $j++) {
			if ($_POST['input_combo_'.($rows).'_'.$j] && $input_operators_settings[($rows).'_'.$j] != $_POST['input_combo_'.($rows).'_'.$j]) {
				$input_operators_settings[($rows).'_'.$j] = $_POST['input_combo_'.($rows).'_'.$j];
			}
		}
	}

	/**
	* Gets the input count
	* 
	* @access public
	* @return int
	*/
	function get_input_count() {
		return $this->get_value('input_count');
	}

	/**
	* Returns the ids of all the input operators. ALL OPERATORS SHOULD OVERWRITE THIS ONE
	*
	* @access public
	* @return array
	*/
	function get_input_operatorids() {
		$input_operators_settings = $this->get_value('input_operators_to_compare');

		$return_array = array();
		foreach($input_operators_settings as $operator_code) {
			list($operatorid, $operator_type) = explode('_', $operator_code);
			$return_array[] = $operatorid;
		}

		return $return_array;
	}

	/**
	* Returns comments about the operator
	*
	* @access public
	* @return string
	*/
	function get_summary_comments() {
		$combined_format[] = $this->get_value('operator1_code');
		$combined_format[] = $this->get_value('operator2_code');
		if ($combined_format[0] && $combined_format[1]) {
				for ($i=0; $i<2; $i++) {
					preg_match('/([0-9]+)_(.*+)/', $combined_format[$i], $matches);
					list($waste, $xtraid, $cast) = $matches;
					$attribute_xtra = $this->get_attribute_type_xtra($xtraid);
					if ($attribute_xtra->id) {
						$val[$i] = $attribute_xtra->name;
					}
				}
				return "If condition is true return { $val[0] }, otherwise return { $val[1] }";
		}
		return '';
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

		$input_operators_settings = $this->get_value('input_operators_to_compare');
		foreach ($input_operators_settings as $key=>$combined_format) {
			if (!preg_match('/([0-9]+)_(.*+)/', $combined_format, $matches)) continue;
			list($waste, $xtraid, $format) = $matches;
			$parameters['input_operators_to_compare'][$key] = $dupe_map[$xtraid].'_'.$format;
		}

		$operator_code = array('operator1_code', 'operator2_code');
		foreach ($operator_code as $code) {
			if (preg_match('/([0-9]+)_(.*+)/', $parameters[$code], $matches)) {
				list($waste, $xtraid, $format) = $matches;
				$parameters[$code] = $dupe_map[$xtraid].'_'.$format;
			}
		}
		return $parameters;
	}
}
?>