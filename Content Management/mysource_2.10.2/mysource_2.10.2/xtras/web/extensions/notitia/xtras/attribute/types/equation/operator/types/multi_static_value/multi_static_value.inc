<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/equation/operator/types/multi_static_value/multi_static_value.inc,v $
## $Revision: 1.14 $
## $Author: achadszinow $
## $Date: 2004/03/30 06:14:53 $
#######################################################################

#---------------------------------------------------------------------#

/**
* A Static value
*/
class Web_Extension_Notitia_Attribute_Type_Attribute_Xtra_Operator_Type_Multi_Static_Value extends Web_Extension_Notitia_Attribute_Type_Attribute_Xtra_Operator {

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
			'string' => 'String*'
			),
		# Programmed in here so other attributes can decide if it will
		# return a compatible value with it's input prior to getting the value
		'output_casting_options' => array(
				'arint' => 'Array of Integers',
				'arfloat' => 'Array of Floats',
				'ardouble' => 'Array of Doubles',
				'arreal' => 'Array of Reals',
				'arbool' => 'Array of Booleans',
				'arstring' => 'Array of Strings'
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

		$record_source = $this->get_value('record_source');
		switch($record_source) {
			case 'frl':
				$session = &get_mysource_session();
				$recordids = $session->get_var('frontitia_record_list_equation_recordids');
				if (!is_array($recordids)) {
					$recordids = array();
				}
				break;
			case 'lrl':
				$categoryid = $this->get_value('record_root_category');
				$category = $this->get_category($categoryid);

				$attribute_queries = array();
				$restrict_recordids = array();

				# Get the searchable attributes list
				$searchable_attributes = $this->get_value('frav_searching_elements');

				# process the attributes
				$override = $this->parameters['frav_attributes_overrides'];
				#set the prefix to be local to the frav scope
				$prefix = 'frav_';

				$search_keywords = $this->get_frav_keywords($input);
				if (!empty($override)) {
					foreach($override as $combined_format => $value) {
						# Handle any special keyword replacements
						$value = replace_keywords($value, $search_keywords);

						list($attributeid, $format) = explode('_', $combined_format);
						$attribute = &$this->get_attribute($attributeid);
						if ($attribute->id) {
							$attribute_type = &$attribute->get_type_handler();
							$attribute_type->set_search_request($prefix.$combined_format.'_', $value, $format);
							$query = &$attribute->process_search_field($prefix.$combined_format.'_', $format);
							if ($query) {
								$attribute_queries[$attributeid][$format][] = $query;
							}
						}
					}
				}

				$sort_formats = array_keys($this->get_value('frav_sortable_attributes'));
				if(empty($sort_formats)) {
					$sort_formats = array('_score');
				}

				$recordids = $category->search_records($attribute_queries, $sort_formats, $this->get_value('frav_scope'), $this->get_value('frav_logic'), $restrict_recordids);
			break;
			default:
				$recordids = array();
				break;
		}

		$value = $this->gather_values($recordids);
		$cast_type = $this->get_value('cast_type');

		return $this->cast_value($value, $cast_type);
	}

	/**
	* Gets the static value
	* 
	* @param int $recordid A possibly needed dynamic recordid
	* @access public
	* @return mixed
	*/
	function gather_values($recordids=array()) {
		$static_value_type = $this->get_value('static_value_type');
		if (is_array($recordids) && $static_value_type) {
			$r_values = array();
			# This function doesn't care about input cause there will be none
			switch($static_value_type) {
				case 'frav':
				case 'trav': # This record attribute value
					foreach($recordids as $recordid) {
						$record = &$this->get_record($recordid);
						if (!$record->id) return;
						$attribute_value_combined_format = $this->get_value('attribute_value_format');
						$attributeid = (int) $attribute_value_combined_format;
						$attribute = &$this->get_attribute($attributeid);
						if (!$attribute->id) return;
						$format = substr(strstr($attribute_value_combined_format,'_'),1);
						$r_values[$recordid] = $record->get_attribute_formatted_value($attribute->id, $format);
					}
				break;
				default:
					break;
			}
			return $r_values;
		} else {
			return array();
		}
	}

	/**
	* Returns the ids of all the input operators. ALL OPERATORS SHOULD OVERWRITE THIS ONE
	*
	* @access public
	* @return array
	*/
	function get_input_operatorids() {
	}

	/**
	* Decides whether to show the category selector dropdown
	* 
	* @access public
	* @return boolean
	*/
	function show_root_category_selector() {
		return ($this->get_value('static_value_type') == 'trav' || $this->get_value('static_value_type') == 'frav');
	}

	/**
	* Decides whether to show the find record entry 
	* 
	* @access public
	* @return boolean
	*/
	function show_find_record_attribute_value() {
		return ($this->get_value('static_value_type') ==  'frav' && $this->get_value('record_root_category'));
	}

	/**
	* Gets the record root category
	* 
	* @access public
	* @return int
	*/
	function get_record_root_category() {
		return $this->get_value('record_root_category');
	}

	/**
	* Gets an array with stix of our notitia category structure
	* 
	* @access public
	* @return array
	*/
	function get_category_tree_list() {
		$tree = &$this->get_category_tree();
		return $tree->get_category_list_with_sticks(0,100);
	}

	/**
	* Gets the attribute and formats
	* 
	* @access public
	* @return array
	*/
	function get_record_category_attributes() {
		$r = array();
		$attribute_list = $this->get_attribute_list();
		foreach($attribute_list as $id => $name) {
			$attribute = &$this->get_attribute($id);
			if ($attribute->type == 'equation') {
				$equation_type = &$attribute->get_type_handler();
				$operators = $equation_type->get_attribute_xtras();
				# This could happen if there is a half setup equation somewhere and prevent crashing
				if (empty($operators)) continue;
				$last_operatorid = end($operators);
				$operator = &$attribute->get_attribute_type_xtra($last_operatorid);
				$operator_type_handler = &$operator->get_type_handler();
				$cast_type = $operator_type_handler->get_value('cast_type');
				if (!in_array($cast_type,array_keys($this->settings['compatiable_input_types']))) {
					continue;
				}
			}
			$value_formats = $attribute->get_type_setting('value_formats');
			foreach($value_formats as $format => $format_name) {
				$r[$id.'_'.$format] = $name.' ('.$format_name.')';
			}
		}
		return $r;
	}

	/**
	* Gets this list of attributes in the record root category
	* 
	* @access public
	* @return array
	*/
	function get_attribute_list() {
		$record_root_category = $this->get_record_root_category();
		$category = &$this->get_category($record_root_category);
		$results = array();
		if ($category->id) {
			$tree = &$this->get_category_tree();
			$results = $tree->get_context_attributes($category->id);
		}
		return $results;
	}

	/**
	* Prints out the local attribute list
	*
	* @access  public
	* @returns void
	*/
	function print_frav_attribute_list_general() {
		$list = $this->get_value('frav_searching_elements');
		$category = &$this->get_category($this->get_value('record_root_category'));
		if($category->id) {
			echo 'Select attribute formats to use. Once selected you may enter a keyword.';
			$tree = &$this->get_category_tree();
			$set_count = 0;
			if($order) {
				$order_array = array_flip(array_keys($list));
			}
			?>
			<table cellpadding="0" cellspacing="0" border="0" bgcolor="#333333"><tr><td><table cellpadding="1" cellspacing="1" border="0">
				<tr bgcolor="#cccccc">
					<td valign="top" class="backend_field">Attribute</td>
					<td valign="top" class="backend_field">Formats</td>
				</tr>
			<?
				$attribute_printed = false;
				$attributes = $tree->get_context_attributes($category->id);
				foreach($attributes as $attributeid => $name) {
					# Check to see if it's a foreign key
					$attribute = &$this->get_attribute($attributeid);
					?>
					<tr bgcolor="#cccccc">
						<td valign="top" class="backend_field"><?=$name?></td>
						<td valign="top">
						<?
						if($attribute->id) {
							$formats = $attribute->get_type_setting('search_formats');
							if(count($formats) > 0) {
								$attribute_printed = true;
								?>
								<table cellpadding="2" cellspacing="1" width="100%" height="100%">
									<tr bgcolor="#eeeeee">
								<?
								$format_count = 0;
								$total_formats = count($formats);
								foreach($formats as $format => $format_name) {
									$option_code = $attributeid.'_'.$format;

									$set = array_key_exists($option_code, $list);
									$label = $list[$option_code];

									if($format_count + 1 == $total_formats) {

										$colspan = 3 - $format_count % 3;
										$c_width = 33*$colspan;
									} else {
										$c_width = 33;
										$colspan=1;
									}
									?>
									<td valign="top" <?=($set?'bgcolor="#ffffaa"':'')?> width="<?=$c_width?>%" colspan="<?=$colspan?>">
										<input type="checkbox" name="local_set[]" value="<?=$option_code?>" <?=($set?'checked':'')?>>&nbsp;<?=$format_name?><br>
										<?
										if($set) {
											$set_count++;

											# New attribute initialisation
											if ($label == ' ') {
												$label = $attribute->process_search_field("local_labels_{$option_code}", $format);
											}

											# Print the search field for the attribute
											$attribute->print_search_field("local_labels_{$option_code}", $label, $format);
										}
										?>
									</td>
									<?
									if($format_count++ % 3 + 1 == 3) echo '</tr><tr bgcolor="#eeeeee">';
								}
								?>
									</tr>
								</table>
								<?
							}
						}
						?>
						</td>
					</tr><?
				}
				if (!$attribute_printed) {?>
					<tr bgcolor=#cccccc>
						<td colspan="2" valign="top" class="backend_field">No Local Attributes are available in this category</td>
					</tr>
				<?}?>
			</table></td></tr></table>
			<?
		}
	}

	/**
	* Saves any changes to the attribute list
	*
	* @access  public
	* @returns void
	*/
	function process_frav_attribute_list_general() {
		$list = &$this->get_value('frav_searching_elements');
		$category = &$this->get_category($this->get_value('record_root_category'));
		if($category->id) {
			$attributes = $category->process_attribute_format_list_selection_form('local', $attributes);
			$set = $_REQUEST['local_set'];
			if ($set == null) {
				$set = array();
			}
			# Delete any that are no longer being used
			$list_keys = array_keys($list);
			$options_to_delete = array_diff($list_keys, $set);
			foreach($options_to_delete as $option_code) {
				unset($list[$option_code]);
			}

			foreach($set as $option_code) {
				list($attributeid, $format) = explode('_', $option_code);
				$attribute = &$this->get_attribute($attributeid);
				if(!$attribute->id) continue;
				$answer = $attribute->process_search_field("local_labels_{$option_code}", $format);
				if ($list[$option_code] != $answer) {
					# Save the answer
					$list[$option_code] = $answer;
					# Since these dudes are changing something up here we probably want to reset any override search
					$_POST['restore_'.$option_code] = true;
				} elseif (!isset($list[$option_code])) {
					# New attribute initialisation
					$list[$option_code] = ' ';
				}
			}
		}
	}

	/**
	* Prints out an special keywords that can be used in search overrides
	*
	* @access  public
	* @returns void
	*/
	function print_frav_overrides_instructions() {
		?>
		<table bgcolor="#888888" cellpadding="0" cellspacing="0" border="0"><tr><td>
		<table cellspacing="1" cellpadding="3">
			<tr bgcolor="#aaaaaa"><td colspan="2"><b>Search Attributes and Values</b></td></tr>
			<tr bgcolor="#eeeeee">
				<td colspan="3">In this section for each attribute that is being searched you can choose to override the search value. This can be useful in cases where you wish to use special keywords as listed below.</td>
			</tr>
			<tr bgcolor="#eeeeee">
				<td>Add Overide Keyword Replacement</td>
				<td colspan="2"><?$this->print_operator_selectors();?></td>
			</tr>
			<tr bgcolor="#eeeeee">
				<td nowrap>%categoryid%</td>
				<td nowrap colspan="2">This will be replaced with root category id.</td>
			</tr>
			<tr bgcolor="#eeeeee">
				<td nowrap>%now%</td>
				<td nowrap colspan="2">The current datetime</td>
			</tr>
			<?
				$input_operators_settings = $this->get_value('frav_operator_override');
				foreach ($input_operators_settings as $combined_format=>$label) {
					?>
					<tr bgcolor="#eeeeee">
					<td nowrap><?=$label?></td>
					<td nowrap>%<?=$combined_format?>%</td>
					<td><input type="checkbox" name="input_override_remove_<?=$combined_format?>" VALUE="1">Remove</td>
					</tr>
					<?
				}
			?>
		</table></td></tr></table>
		<?
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
		$prior_xtras_list = $this->get_valid_input_operators_names_wcast();
		$input_operators_settings = $this->get_value('frav_operator_override');
		foreach ($input_operators_settings as $key=>$label) {
			if (in_array($label, $prior_xtras_list)) {
				unset($prior_xtras_list[$key]);
			}
		}
		$prior_xtras_list = array_merge(array(''=>'None'), $prior_xtras_list);
		echo combo_box('input_override', $prior_xtras_list);
	}

	/**
	* Processes the table of dropdowns so you can select what operators get summed
	*
	* @access public
	* @return void
	*/
	function process_operator_selectors() {
		$input_operators_settings = &$this->get_value('frav_operator_override');
		$prior_xtras_list = $this->get_valid_input_operators_names_wcast();
		
		# remove a keyword
		foreach ($input_operators_settings as $combined_format=>$label) {
			if ($_POST['input_override_remove_'.$combined_format]) {
				unset($input_operators_settings[$combined_format]);
			}
			if (!in_array($combined_format, array_keys($prior_xtras_list))) {
				unset($input_operators_settings[$combined_format]);
			}
		}
		# add a keyword 
		if ($_POST['input_override'] && $_POST['input_override'] !='') {
			$input_operators_settings[$_POST['input_override']] = $prior_xtras_list[$_POST['input_override']];
		}
	}

	/**
	* Prints out all the attributes that are being searched and lets you override values if you choose. This area is so you can enter special keywords that you wouldn't be able to do in the other box cause things like selection don't allow you to enter text
	*
	* @access  public
	* @returns void
	*/
	function print_frav_overrides() {
		$list = $this->parameters['frav_searching_elements'];
		$override = $this->parameters['frav_attributes_overrides'];
		?>
		<table bgcolor="#888888" cellpadding="0" cellspacing="0" border="0"><tr><td>
		<table cellspacing="1" cellpadding="3">
			<tr bgcolor="#eeeeee">
				<td><b>Attribute Name</b></td>
				<td><b>Search Format</b></td>
				<td><b>Searching Value</b></td>
				<td><b>Cancel Override</b></td>
			</tr>
			<?
			foreach($list as $combined_format => $value) {
				if (isset($override)) {
					$value = $override[$combined_format];
				}
				if (is_array($value)) {
					$value = serialize($value);
					?><input type="hidden" name="type_array_<?=$combined_format?>" value="1"><?
				}
				list($attributeid, $format) = explode('_', $combined_format);
				$attribute = &$this->get_attribute($attributeid);
			?>
			<tr bgcolor="#eeeeee">
				<td><b><?=$attribute->name?></b></td>
				<td><?=$format?></td>
				<td><?=text_box('override_'.$combined_format, $value, 50)?></td>
				<td><input type="checkbox" name="restore_<?=$combined_format?>" VALUE="1"> Restore</td>
			</tr>
			<?
			}
			?>
			<tr bgcolor="#eeeeee"><td colspan="4">Here it is possible to override any set searches and use special keywords</td></tr>
		</table></td></tr></table>
		<?
	}

	/**
	* Processes the override interface
	*
	* @access  public
	* @returns void
	*/
	function process_frav_overrides() {
		$list = $this->parameters['frav_searching_elements'];
		$override = &$this->parameters['frav_attributes_overrides'];
		foreach($list as $combined_format => $value) {
			if ($_POST['override_'.$combined_format] && $override[$combined_format] != $_POST['override_'.$combined_format]) {
				$override[$combined_format] = $_POST['override_'.$combined_format];
			}
			if ($_POST['restore_'.$combined_format]) {
				$override[$combined_format] = $list[$combined_format];
			}
		}
		if (count($list) < count($override)) {
			# We must have something in override to delete
			$override_keys = array_keys($override);
			foreach($override_keys as $combined_format) {
				if (!array_key_exists($combined_format,$list)) {
					unset($override[$combined_format]);
				}
			}
		}
	}

	/**
	* Prints out the sortable attributes for the record list tab
	*
	* @access  public
	* @returns void
	*/
	function print_frav_sortable_attribute_list() {
		$category = &$this->get_category($this->get_value('record_root_category'));
		if($category->id) {
			echo "Select attributes to sort by. Once selected you may enter a label for the dropdown list.";
			$category->print_attribute_format_list_selection_form('frav_sortable_attributes', $this->get_value('frav_sortable_attributes'),'sort',1);
		}
	}

	/**
	* Processes any calls from print_sortable_attribute_list_record_list()
	*
	* @access  public
	* @returns void
	*/
	function process_frav_sortable_attribute_list() {
		$category = &$this->get_category($this->get_value('record_root_category'));
		if($category->id) {
			$this->parameters['frav_sortable_attributes'] = $category->process_attribute_format_list_selection_form('frav_sortable_attributes', $this->parameters['frav_sortable_attributes']);
		}
	}

	function get_frav_keywords($input) {
		$searching_now = date('Y-m-d H:i:s', time());

		# Get the searchable attributes list
		$searchable_attributes = $this->get_value('frav_searching_elements');

		#process the operator overrides
		$input_operators_settings = &$this->get_value('frav_operator_override');

		# Create input_operators_values array 
		foreach($input_operators_settings as $xtra_code=>$label) {
			if (!array_key_exists($xtra_code, $input)) continue;
			list($xtraid, $cast_type) = explode('_', $xtra_code);
			$input_operators_values[$xtra_code] = $input[$xtra_code];
		}

		return array_merge(
					array(	'categoryid' => $this->get_value('record_root_category'), 
							'now' => $searching_now),
					$input_operators_values
				);
	}

	/**
	* Returns a reference to an object
	*
	* @access public
	* @return &object
	*/
	function &get_category_tree() {
		$e = &$this->get_notitia_system();
		return $e->get_category_tree();
	}

	/**
	* Gets the category object
	*
	* @param int $categoryid The id of the category
	* @access public
	* @return &object
	*/
	function &get_category($categoryid) {
		$e = &$this->get_notitia_system();
		return $e->get_category($categoryid);
	}

	/**
	* Gets the attribute object
	*
	* @param int $attributeid The id of the attribute
	* @access public
	* @return &object
	*/
	function &get_attribute($attributeid) {
		$n = &$this->get_notitia_system();
		return $n->get_attribute($attributeid);
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
		$type = $this->get_value('static_value_type');
		if ($type == 'frav') {
			$input_operators_settings = $this->get_value('frav_operator_override');
			$parameters['frav_operator_override'] = array();
			foreach ($input_operators_settings as $combined_format=>$label) {
				if (!preg_match('/([0-9]+)_(.*+)/',$combined_format,$matches)) continue;
				list($waste,$xtraid, $format) = $matches;
				$parameters['frav_operator_override'][$dupe_map[$xtraid].'_'.$format] = $label;
			}
			$override = $this->parameters['frav_attributes_overrides'];
			foreach ($override as $attribcombined_format=>$combined_format) {
				if (!preg_match('/([0-9]+)_(.*+)/',$combined_format,$matches)) continue;
				list($waste,$xtraid, $format) = $matches;
				$parameters['frav_attributes_overrides'][$attribcombined_format] = $dupe_map[$xtraid].'_'.$format;
			}
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
		$res = "Produce an array of static values gathered from ";
		$record_source = $this->get_value('record_source');
		switch ($record_source) {
			case "frl":
				$res .= "a frontitia record list";
				break;
			case "lrl":
				$res .= "a local list";
		}
		return $res;
	}



}
?>