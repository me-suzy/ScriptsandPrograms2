<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/record_search/record_search.inc,v $
## $Revision: 1.9 $
## $Author: nduggal $
## $Date: 2004/01/16 04:24:09 $
#######################################################################

#---------------------------------------------------------------------#
global $SQUIZLIB_PATH;
include_once("$SQUIZLIB_PATH/wysiwyg/wysiwyg.inc");

 ####################
# For text fields...
class Web_Extension_Notitia_Attribute_Type_Record_Search extends Web_Extension_Notitia_Attribute_Type {
	
	 ################################################################
	# This is a static thingy that stores special information about
	# a particular type.
	var $settings = array(
			'preferred_alignment' => 'left',
			'generates_varieties' => false,
			'variable'            => false,
			'variation_types'   => array(
			),
			'value_formats' => array(
			),
			'search_formats' => array (
				'ad' => 'Attribute Defined'
			),
			'edit_formats' => array (
			),
			'sort_formats' => array (
			),
			'rule_types' => array (
			),
			'requires_context_values' => true,
			'sub_type_name' => 'meta',
			'table_settings' => array(
			'sub_table_name' => ''
			)
	);

	/**
	* Prints a table of wysiwyg keywords
	*
	* @access  public
	* @returns void
	*/
	function print_wysiwyg_keywords() {
		$search_attributes = &$this->get_value('search_attributes');
		#$interlinking_attributes = $this->get_interlinking_attributes();
		#$conversion_value_formats = &$this->get_value('conversion_value_formats');
		#$conversion_dropdown_attributes = $this->get_value('conversion_dropdown_attributes');

		?>
		<table bgcolor="#AAAAAA">
			<tr bgcolor="#CCCCCC">
				<td colspan="2" align="center"><b>Keywords</b></td>
			</tr>
		<?
		foreach($search_attributes as $search_format => $name) {
			# MORE INSTUCTIONS ON EACH KEYWORD AS SOON AS I CAN
			#$list($attributeid, $search_format) = explode('_', $interlinking_attribute_search_format);
			#$attribute = &$this->get_attribute($attributeid);
			#$attribute_type_handler = &$attribute->get_type_handler();
			#$attribute_value_formats = $attribute_type_handler->settings['value_formats'];
			?>
			<tr bgcolor="#DDDDDD">
				<td nowrap>%<?=$name?>%</td><td>Search Keyword</td>
			</tr>
			<?
		}
		?>
		</table>
		<?
	}

	/**
	* Prints the WYSIWYG
	*
	* @access  public
	* @returns void
	*/
	function print_wysiwyg_format() {
		$wysiwyg = &$this->get_wysiwyg('web_extention_notitia_attribute_types_record_search_wysiwyg');
		$wysiwyg->paint_edit($this->parameters['search_wysiwyg'], true);
	}

	/**
	* Processes any changes to the WYSIWYG
	*
	* @access  public
	* @returns string
	*/
	function process_wysiwyg_format() {
		$wysiwyg = &$this->get_wysiwyg('web_extention_notitia_attribute_types_record_search_wysiwyg');
		if ($this->parameters['search_wysiwyg'] != $wysiwyg->get_html()) {
			$this->parameters['search_wysiwyg'] = $wysiwyg->get_html();
			return 'WYSIWYG has been modified<br />';
		}
	}

	/**
	* Return a reference to the wysiwyg object
	*
	* @param string $name The name that it will use while creating this WYSIWYG object
	* @access  public
	* @returns object Wysiwyg
	*/
	function &get_wysiwyg($name) {
		$web_system = &get_web_system();
		$pageid = $this->get_wysiwyg_pageid();
		$page = &$web_system->get_page($pageid);
		$design = &$page->get_design();
		$styles = $design->get_var('bodycopy_styles');
		$styles = $styles['options'];
		$this->_wysiwyg = new wysiwyg($name, $page->siteid, $page->id,
		$design->stylesheet('rel'), $styles,
		true,true,true, false, true);
		return $this->_wysiwyg;
	}

	/**
	* Returns a validated pageid for the wysiwyg page to feed off
	*
	* @access  public
	* @returns int
	*/
	function get_wysiwyg_pageid() {
		$web_system = &get_web_system();
		$page = &$web_system->get_page($this->parameters['wysiwyg_pageid']);
		return $page->id;
	}

	/**
	* No formatted values allowed cause it doesn't print values
	*
	* @param  string &$value  The value to print
	* @param  string $format The format to print it with
	* @access public
	* @return void
	*/
	function formatted_value(&$value, $format) {
	}

	/**
	* No editting of values allowed cause it doesn't store values
	*
	* @param  string $prefix   A prefix
	* @param  string &$value   Current or default value
	* @param  string &$options Options
	* @access public
	* @return void
	*/
	function print_concise_field($prefix,&$value,$options) {
		?>
		<table>
			<tr><td><b>[Record Search - Attribute - cannot be edited]</b></td></tr>
		</table>
		<?
	}

	/**
	* Prints the field if they stored em up they don't
	*
	* @param  string $prefix   A prefix
	* @param  string &$value   Current or default value
	* @param  string &$options Options
	* @access public
	* @return void
	*/
	function print_field($prefix,&$value,$options) {
		$this->print_concise_field($prefix,$value,$options);
		if($note = &$this->get_value('note')) {
			echo '<span class="smallprint"><br />'.$note.'</span>';
		}
	}

	/**
	* Processes changes to an attribute
	*
	* @param  string  $prefix                 The attribute changing prefix
	* @param  &string $value_destination      The current value
	* @param  string  $format                 Don't remember
	* @param  &string &$rules_broken_messages Var to hold broken rull messages
	* @access public
	* @return string
	*/
	function process_field($prefix, &$value_destination, $format, &$rules_broken_messages) {
	}

	/**
	* Checks the value is valid NOTE this should never get called
	*
	* @param  string &$value            The value we need to check against the rules
	* @param  string &$rule_broken_msgs The broken rule messages
	* @access public
	* @return void
	*/
	function validate_value(&$value, &$rule_broken_msgs) {
	}

	/**
	* Prints the search field
	*
	* @param  string $prefix   A prefix
	* @param  string &$default The last default value
	* @param  string $format   The search format
	* @param  string $not_null Do we let them not search this field?
	* @access public
	* @return void
	*/
	function print_search_field($prefix,&$default,$format,$not_null) {
		$search_attributes = $this->get_value('search_attributes');
		$conversion_dropdown_attributes = $this->get_value('conversion_dropdown_attributes');
		$conversion_value_formats = &$this->get_value('conversion_value_formats');
		$reset_option_label = $this->get_value('interlinking_reset_label');
		$interlinking_attributes = $this->get_interlinking_attributes();
		$interlinker_values = array();
		foreach($search_attributes as $combined_format => $keyword) {
			list($subattributeid, $search_format) = explode('_', $combined_format);
			$attribute = &$this->get_attribute($subattributeid);
			$values_and_records = array();
			$values = array();

			if(is_array($default[$subattributeid]) && !empty($default[$subattributeid])) {
				$subdefault = array_pop($default[$subattributeid]);
			} else {
				$subdefault = ((isset($default[$subattributeid]))?$default[$subattributeid]:'');
			}

			if (in_array($combined_format, $conversion_dropdown_attributes)) {
				# BTW the reason for pqvalue is it's a pseudo query value cause we're gonna need to ask the attribute to process is again for real and hopefully they will be compatible

				# Attribute morphing into dropdown (possibly a dependancy dropdown)
				$value_format = $conversion_value_formats[$combined_format];
				$associated_recordids = $attribute->get_associated_recordids();

				foreach($associated_recordids as $recordid) {
					$record = &$this->get_record($recordid);
					$formatted_value = $record->get_attribute_formatted_value($attribute->id, $value_format);
					$values_and_records[$formatted_value][] = $recordid;
					$values[$formatted_value] = $formatted_value;
				}
				$values[$prefix.'_s'.$subattributeid.'pqvalue_reset'] = $reset_option_label;

				if ($this->get_value('s_blank_label') != null) {
					$blank_label = $this->get_value('s_blank_label');
				} else {
					$blank_label = '';
				}

				if ($this->parameters['server_side_sort']) {
					asort($values);
				}

				if (in_array($combined_format, $interlinking_attributes)) {
					
					# It's an interlinker
					$replacements[$keyword] = combo_box($prefix.'_s'.$subattributeid.'pqvalue',
						(array('' => $blank_label) + $values), 
						$subdefault,
						'style="width:'.$this->get_value('s_width').'px"'.'onChange="javascript: '.$prefix.'populate_others(\''.$prefix.'_s'.$subattributeid.'pqvalue\', this)"',
						$this->get_value('s_maxlength') ,
						$this->get_value('s_height')
						);
					# This is for the java script to look up values that need to be changed on all other drop downs
					$interlinker_values[$prefix.'_s'.$subattributeid.'pqvalue'] = $values_and_records;
				} else {
					# It's just a dropdown morph
					$replacements[$keyword] = combo_box($prefix.'_s'.$subattributeid.'pqvalue', 
						(array('' => $blank_label) + $values), 
						$subdefault, 
						'style="width:'.$this->get_value('s_width').'px"',
						$this->get_value('s_maxlength') ,
						$this->get_value('s_height')
						);
				}
			} else {
				# Just print out the attribute search format
				ob_start();
				$attribute->print_search_field($prefix.'_s'.$subattributeid,$subdefault,$search_format);
				$replacements[$keyword] = ob_get_contents();
				ob_end_clean();
			}
		}

		$html = $this->get_value('search_wysiwyg');
		$html = replace_keywords($html, $replacements);

		if (!empty($interlinker_values)) {
			global $SQUIZLIB_PATH;
			include_once($SQUIZLIB_PATH.'/var_serialise/var_serialise.inc');
			$interlinker_prefixs = array_keys($interlinker_values);
			
			if ($this->parameters['server_side_sort']) {
				foreach ($interlinker_prefixs as $key) {
					ksort($interlinker_values[$key]);
				}
			}

			?>
			<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('var_serialise', 'var_serialise.js');?>"></script>
			<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('js', 'general.js');?>"></script>

			<SCRIPT language="JavaScript">
				window.<?=$prefix?>interlinker_values = var_unserialise('<?=var_serialise($interlinker_values, true);?>');
				window.<?=$prefix?>interlinkers = var_unserialise('<?=var_serialise($interlinker_prefixs, true);?>');

				function <?=$prefix?>populate_others(this_extended_prefix, field) {
					// Find out the selected recordid
					var value = elementValue(field);
					var form = field.form;
					var recordids = window.<?=$prefix?>interlinker_values[this_extended_prefix][value];

					for(var other_prefix_key in window.<?=$prefix?>interlinkers) {
						// Make sure the prefixs aren't the same
						var other_prefix = window.<?=$prefix?>interlinkers[other_prefix_key];
						if (other_prefix == null) {
							continue;
						}

						if (this_extended_prefix != other_prefix || value == this_extended_prefix + '_reset') {
							
							//get the previous selected value
							if (value != this_extended_prefix + '_reset') {
								//get the value that has been selected
								var selectedIndex = form.elements[other_prefix].options.selectedIndex;
								var selectedValue = form.elements[other_prefix].options[selectedIndex].value;
							}

							// Wipe the array values
							for(var i = form.elements[other_prefix].options.length - 1; i >= 0; i--) {
								form.elements[other_prefix].options[i] = null;
							}

							if (value == this_extended_prefix + '_reset') {
								// Only initially and on a reset is a blank ok
								form.elements[other_prefix].options['0'] = new Option('<?=$blank_label?>', '');
								var i = 1;
								for(var option_value in window.<?=$prefix?>interlinker_values[other_prefix]) {
									if (window.<?=$prefix?>interlinker_values[other_prefix][option_value] == null || option_value == '') continue;
									// We don't want to add the reset twice while resetting
									form.elements[other_prefix].options[i] = new Option(option_value, option_value);
									i++;
								}
							} else {
								form.elements[other_prefix].options['0'] = new Option('<?=$blank_label?>', '');
								// Now lets go thru all the values
								var i = 1;
								for(var option_value in window.<?=$prefix?>interlinker_values[other_prefix]) {
									if (window.<?=$prefix?>interlinker_values[other_prefix][option_value] == null || option_value == '') continue;
									var option_recordids = window.<?=$prefix?>interlinker_values[other_prefix][option_value];
									if (array_any_common_elements(recordids, option_recordids)) {
										form.elements[other_prefix].options[i] = new Option(option_value, option_value);
										if (option_value == selectedValue) {
											form.elements[other_prefix].options.selectedIndex = i;
										}
										i++;
									}
								}
							}
							form.elements[other_prefix].options[i] = new Option('<?=$reset_option_label?>', other_prefix + '_reset');
						}
					}
				}
			</SCRIPT>
			<?
		}
		echo $html;
	}

	/**
	* Processes the search field for returning a search array
	*
	* @param  string $prefix   A prefix
	* @param  string $format   The search format
	* @param  string $not_null Do we let them not search this field?
	* @access public
	* @return array
	*/
	function &process_search_field($prefix,$format,$not_null) {
		$search_attributes = &$this->get_value('search_attributes');
		$r = array();
		foreach($search_attributes as $combined_format => $keyword) {
			$search_format = substr(strstr($combined_format,'_'),1);
			$attributeid_with_underscore = str_replace($search_format, '', $combined_format);
			$search_attributeid = substr($attributeid_with_underscore, 0, (strlen($attributeid_with_underscore) - 1));
			$attribute = &$this->get_attribute($search_attributeid);
			if($attribute->id) {
				# Ok we an attribute that's getting converted so we'll grab the value from that and register the value with the actual attribute
				if ($_REQUEST[$prefix.'_s'.$search_attributeid.'pqvalue'] != '') {
					$search_value = $_REQUEST[$prefix.'_s'.$search_attributeid.'pqvalue'];
					$attribute->set_search_request($prefix.'_s'.$search_attributeid,$search_value,$search_format);
				}

				$q = $attribute->process_search_field($prefix.'_s'.$search_attributeid,$search_format);
				if($q) {
					$r[$search_attributeid] = $q;
				} else {
					unset($r[$search_attributeid]);
				}
			}
		}
		return $r;
	}

	/**
	* Returns an array of search clauses that this attribute employs
	*
	* @param  string $query    The search query array
	* @param  string $format   The search format
	* @access public
	* @return array
	*/
	function get_search_sql_clause($query,$format) {
		# Abort if the doesn't have anything in it's array
		if (empty($query)) return false;

		# Now for the search clause
		switch($format) {
			case 'ad':
				$search_attributes = &$this->get_value('search_attributes');
				$clauses = array();
				foreach($search_attributes as $combined_format => $keyword) {
					$search_format = substr(strstr($combined_format,'_'),1);
					$attributeid_with_underscore = str_replace($search_format, '', $combined_format);
					$search_attributeid = substr($attributeid_with_underscore, 0, (strlen($attributeid_with_underscore) - 1));
					
					$search_attribute = &$this->get_attribute($search_attributeid);

					//if a search all query
					if ($query[$search_attributeid] == '') {
						if ($this->get_setting('search_logic') == 'OR') {
							$clauses[$search_attributeid] = '0=1';
						} else {
							$clauses[$search_attributeid] = '1=1';
						}
					}
					elseif ($search_attribute->id) {
						$clauses[$search_attributeid] = $search_attribute->get_search_sql_clause($query[$search_attributeid], $search_format, 'v'.$search_attributeid.'.value');
					}
				}
				break;
			default:
				# ABORT if the format is not supported
				return false;
		}
		return $clauses;
	}

	/**
	* Prints up the attribute search list
	*
	* @access public
	* @return void
	*/
	function print_search_attribute_list() {
		$search_attributes = &$this->get_value('search_attributes');
		$category = &$this->get_category($this->attribute->categoryid);
		$category->print_attribute_format_list_selection_form('search_attributes', $search_attributes, 'search');
	}

	/**
	* Processes the search attribute list
	*
	* @access public
	* @return void
	*/
	function process_search_attribute_list() {
		$search_attributes = &$this->get_value('search_attributes');
		$category = &$this->get_category($this->attribute->categoryid);
		$search_attributes = $category->process_attribute_format_list_selection_form('search_attributes', $search_attributes);
	}

	/**
	* Returns an array of search search attributes and search formats
	*
	* @access public
	* @return array
	*/
	function get_search_attributes_list() {
		return $this->get_value('search_attributes');
	}

	/**
	* Returns an array of all the attributes that wish to be converted to a dropdown
	*
	* @access public
	* @return array
	*/
	function get_conversion_dropdown_attributes_list() {
		$search_attributes = &$this->get_value('search_attributes');
		$returning_list = array();
		$conversion_attributes = $this->get_value('conversion_dropdown_attributes');
		foreach($search_attributes as $combined_format => $name) {
			if (in_array($combined_format, $conversion_attributes)) {
				$returning_list[$combined_format] = $name;
			}
		}
		return $returning_list;
	}

	/**
	* Returns an array of all the interlinking attributes
	*
	* @access public
	* @return array
	*/
	function get_interlinking_attributes() {
		return $this->get_value('interlinking_attributes');
	}

	/**
	* Prints an interface for you to select the value format that will be used while converting to a dropdown
	*
	* @access public
	* @return void
	*/
	function print_coversion_formats() {
		$search_attributes = &$this->get_value('search_attributes');
		$conversion_dropdown_attributes = $this->get_value('conversion_dropdown_attributes');
		$conversion_value_formats = $this->get_value('conversion_value_formats');
		?>
		<table border="1">
			<tr>
				<td><b>Attribute Keyword</b></td><td><b>Attribute Format</b></td>
			</tr>
		<?
			foreach($conversion_dropdown_attributes as $conversion_attribute_search_format) {
				list($attributeid, $search_format) = explode('_', $conversion_attribute_search_format);
				$attribute = &$this->get_attribute($attributeid);
				$attribute_type_handler = &$attribute->get_type_handler();
				$attribute_value_formats = $attribute_type_handler->get_setting('value_formats');
				?>
					<tr><td><?=$search_attributes[$conversion_attribute_search_format]?></td><td><?=combo_box('conversion_attribute_format_'.$conversion_attribute_search_format, $attribute_value_formats, $conversion_value_formats[$conversion_attribute_search_format])?></td></tr>
				<?
			}
		?>
		</table>
		<?
		echo "<span class=smallprint><br>Foreach attribute that you are converting to a dropdown you must choose an attribute format for that attribute. This format will be used to print the value in the dropdown</span>";
	}

	/**
	* Processes the conversion format selections
	*
	* @access public
	* @return string
	*/
	function process_coversion_formats() {
		$search_attributes = &$this->get_value('search_attributes');
		$conversion_dropdown_attributes = $this->get_value('conversion_dropdown_attributes');
		$conversion_value_formats = &$this->get_value('conversion_value_formats');
		$msgs = '';
		foreach($conversion_dropdown_attributes as $conversion_attribute_search_format) {
			if ($conversion_value_formats[$conversion_attribute_search_format] != $_POST['conversion_attribute_format_'.$conversion_attribute_search_format]) {
				$conversion_value_formats[$conversion_attribute_search_format] = $_POST['conversion_attribute_format_'.$conversion_attribute_search_format];
				$msgs .= 'Value format has been modified<br />';
			}
		}
		return $msgs;
	}

	/**
	* Returns a setting from this attribute
	*
	* @param  string $setting A setting
	* @access public
	* @return mixed
	*/
	function &get_setting($setting) {
		if ($setting == 'search_logic') {
			return $this->get_value('search_logic');
		} else {
			return $this->settings[$setting];
		}
	}
}
?>