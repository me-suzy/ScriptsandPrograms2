<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/equation/operator/types/sum/sum.inc,v $
## $Revision: 1.14 $
## $Author: achadszinow $
## $Date: 2004/03/30 06:14:53 $
#######################################################################

#---------------------------------------------------------------------#

/**
* Sums other operator values
*/
class Web_Extension_Notitia_Attribute_Type_Attribute_Xtra_Operator_Type_Sum extends Web_Extension_Notitia_Attribute_Type_Attribute_Xtra_Operator {

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
			'string' => 'String*',
			'arint' => 'Array of Integers',
			'arfloat' => 'Array of Floats',
			'ardouble' => 'Array of Doubles',
			'arreal' => 'Array of Reals',
			'arbool' => 'Array of Booleans',
			'arstring' => 'Array of Strings'
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
		$input_count = $this->get_input_count();
		if ($input_count) {
			$input_operators_to_sum = $this->get_value('input_operators_to_sum');
			# Evaluate the ops if need be. The returning array would be more up to date than any we have been given.
			$input = $this->evaluate_child_operators($options, $input_operators_to_sum);
			$values = array();
			$non_number_found = false;
			$values = $this->prepare_values($input_operators_to_sum, $input, $non_number_found);
			$value = $this->sum_values($values, $non_number_found);
		}

		$cast_type = $this->get_value('cast_type');
		return $this->cast_value($value, $cast_type);
	}

	/**
	* Sums values in a value array
	* 
	* @param array $values Each value in array is a summable value
	* @param boolean $non_number_found Extra options we might need to evaluate this dynamically
	* @access public
	* @return mixed
	*/
	function sum_values($values=array(), $non_number_found=false) {
		if (!empty($values)) {
			if ($non_number_found) {
				# String concats
				$value = implode('',$values);
			} else {
				# Number ops
				$value_string = implode('+',$values);
				eval("\$value = $value_string;");
			}
		}
		return $value;
	}

	/**
	* Sums values in a value array
	* 
	* @param array &$input_operators_to_sum Operators that this should sum
	* @param &boolean $non_number_found Extra options we might need to evaluate this dynamically
	* @access public
	* @return mixed
	*/
	function prepare_values($input_operators_to_sum, $input, &$non_number_found) {

		# Create new input array removing anything invalid or not cared about
		foreach($input_operators_to_sum as $xtra_code) {
			if (!array_key_exists($xtra_code, $input)) continue;
			list($xtraid, $cast_type) = explode('_', $xtra_code);
			$cast_type_type = substr($cast_type, 0, 2);
			# We are dealing with a operator that returns arrays of values
			if (preg_match("/ar/", $cast_type_type)) {
				$multi_values = true;
				$cast_type = substr($cast_type, 2);
			} else {
				$multi_values = false;
			}

			if ($cast_type == 'string' || $cast_type == 'arstring') {
				$non_number_found = true;
			}
			if ($multi_values) {
				if (empty($input[$xtra_code])) {
					# This could happen if a frontitia feeds multi stat values with an empty record array
					$values[] = 0;
				} else {
					foreach($input[$xtra_code] as $recordid => $val) {
						$values[] = $val;
					}
				}
			} else {
				$values[] = $input[$xtra_code];
			}
		}

		return $values;
	}

	/**
	* Prints a table of dropdowns so you can select what operators get summed
	* 
	* @param array &$input Input values for this operator however this operator doesn't take any input so ignore
	* @param array $options Extra options we might need to evaluate this dynamically
	* @access public
	* @return void
	*/
	function print_operator_selectors() {
		$input_count = $this->get_input_count();
		$columns = (($input_count<4)?$input_count:4);
		$rows = (int) ($input_count / $columns);
		$remainder_cells = ($input_count - ($columns*$rows));
		$prior_xtras_list = $this->get_valid_input_operators_names_wcast();

		$input_operators_settings = $this->get_value('input_operators_to_sum');

		?>
		<table bgcolor="#888888" cellpadding="0" cellspacing="0" border="0"><tr><td>
		<table cellspacing="1" cellpadding="3">
			<tr bgcolor="#aaaaaa">
				<td colspan="<?=$columns?>"><b>Operators to Sum</b></td>
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
				$combo_box = combo_box('input_combo_'.$rows.'_'.$j, $prior_xtras_list, $input_operators_settings[$rows.'_'.$j]);
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

		echo '<br /><span class="smallprint"><b>NOTE:</b> If you choose an operator that returns something other than a number this operator will sum by concatenate all the operator values.</span>';
	}

	/**
	* Processes the table of dropdowns so you can select what operators get summed
	*
	* @access public
	* @return void
	*/
	function process_operator_selectors() {
		$input_operators_settings = &$this->get_value('input_operators_to_sum');
		$input_count = $this->get_input_count();
		$columns = (($input_count<4)?$input_count:4);
		$rows = (int) ($input_count / $columns);
		$remainder_cells = ($input_count - ($columns * $rows));
		$keys = array();

		for($i=0; $i<$rows; $i++) {
			for($j=0; $j<$columns; $j++) {
				$keys[] = $i.'_'.$j;
				if ($_POST['input_combo_'.$i.'_'.$j] && $input_operators_settings[$i.'_'.$j] != $_POST['input_combo_'.$i.'_'.$j]) {
					$input_operators_settings[$i.'_'.$j] = $_POST['input_combo_'.$i.'_'.$j];
				}
			}
		}

		for($j=0; $j < $remainder_cells; $j++) {
			$keys[] = $rows.'_'.$j;
			if ($_POST['input_combo_'.$rows.'_'.$j] && $input_operators_settings[$rows.'_'.$j] != $_POST['input_combo_'.$rows.'_'.$j]) {
				$input_operators_settings[$rows.'_'.$j] = $_POST['input_combo_'.$rows.'_'.$j];
			}
		}

		# remove unwanted keys
		$input_operators_keys = array_keys($input_operators_settings);
		foreach ($input_operators_keys as $key) {
			if (array_search($key, $keys) === false) {
				unset($input_operators_settings[$key]);
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
		$input_operators_settings = $this->get_value('input_operators_to_sum');

		$return_array = array();
		foreach($input_operators_settings as $operator_code) {
			list($operatorid, $operator_type) = explode('_', $operator_code);
			$return_array[] = $operatorid;
		}

		return $return_array;
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

		$input_operators_settings = $this->get_value('input_operators_to_sum');
		foreach ($input_operators_settings as $key=>$combined_format) {
			if (!preg_match('/([0-9]+)_(.*+)/', $combined_format, $matches)) continue;
			list($waste, $xtraid, $format) = $matches;
			$parameters['input_operators_to_sum'][$key] = $dupe_map[$xtraid].'_'.$format;
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
		return implode(" + ", $names);
	}
}
?>