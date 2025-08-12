<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/AGNSW_search_selection/AGNSW_search_selection.inc,v $
## $Revision: 1.7 $
## $Author: nduggal $
## $Date: 2004/01/16 06:25:35 $
#######################################################################

#---------------------------------------------------------------------#


class Web_Extension_Notitia_Attribute_Type_AGNSW_Search_Selection extends Web_Extension_Notitia_Attribute_Type {
	

	 ################################################################
	# This is a static thingy that stores special information about
	# a particular type.
	var $settings = array(
			'preferred_alignment' => 'left',
			'generates_varieties' => false,
			'variable'            => false,
			'variation_types'     => array(
			),
			'value_formats' => array(
			),
			'search_formats' => array (
				'sub_match' => 'Sub Match',
			),
			'edit_formats' => array (
			),
			'sort_formats' => array (
			),
			'rule_types' => array (
			),
			'requires_context_values' => true,
			# meta so the search will treat it right
			'sub_type_name' => 'meta',

			'table_settings' => array(
				'sub_table_name' => ''
			)
	);


	 #############################################
	# Convert a string array to a string and back
	function ar2str($array) {
		return implode('|',array_intersect(array_keys($this->get_value('options')),$array));
	}

	function str2ar($string) {
		return array_intersect(array_keys($this->get_value('options')),explode('|',$string));
	}

	 ##################################################
	# Takes an original values, and variation and a 
	# variation type, and returns the varied result
	function vary_value($type, &$new_value, &$original) {
		switch($type) {
			default:
				return $original;
				break;
		}
	}

	 #############################################################
	#  Returns the value formatted in a nice way for printing
	function formatted_value(&$value, $format = 'labels_space') {
		$value = 'None';
	}

	 #############################################
	# Prints the interface for filling in a value
	function print_concise_field($prefix,&$value) {
		?>
		<table>
			<tr><td><b>[AGNSW Search Selection-Attribute - cannot be edited]</b></td></tr>
		</table>
		<?
	}


	 #############################################
	# Prints the interface for filling in a value
	function print_field($prefix,&$value) {
		$this->print_concise_field($prefix,$value);
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
		$input = $_REQUEST[$prefix.'value'];
		$input = gpc_stripslashes($input);
		if(is_array($input)) {
			$input = $this->ar2str($input);
		}
		if((string) $value_destination == (string) $input) {
			return;
		}
		$value_destination = $input;
		return $this->attribute->name.' updated.';
	}
	
	 ###########################################################
	# Prints an interface that allows the user to build their list
	# of options
	function print_option_definition_field() {
		$options = &$this->get_value('options');
		echo '<table cellspacing="0" cellpadding="1" border="0"><tr><td><b>Key</td><td><b>Label</td></tr>';
		reset($options);
		$i = 0;
		while($i < count($options) + 4) {
			list($key,$label) = each($options);
			echo('<tr><td>');
			echo text_box("selection_options_keys[$i]", $key, 20, 50);
			echo('</td><td>');
			echo text_box("selection_options_labels[$i]", $label, 50, 1000);
			echo('</td></tr>');
			$i++;
		}
		echo '</table>';
		echo '<span class="smallprint">Please keep keys short and unique. Keys are unique identifiers of the options. Labels may be longer and descriptive. Some characters are not allowed and will be removed.</span>';
	}


	 ###########################################################
	# Processes an interface that allows the user to build their list
	# of options
	function process_option_definition_field() {
		$options = &$this->get_value('options');
		$selection_options_keys = $_REQUEST['selection_options_keys'];
		$selection_options_labels = $_REQUEST['selection_options_labels'];
		$new_options = array();
		foreach($selection_options_keys as $i => $key) {
			$label = $selection_options_labels[$i];
			$key = ereg_replace("[\|]+","",$key);
			if(!$key) continue;
			$new_options[$key] = $label;
		}
		$options = $new_options;
		return "Options updated.";
	}

	 ###################################################
	# Returns a query object which can be given back to
	# print_search_field() or get_search_sql_clause()
	function &process_search_field($prefix,$format,$not_null) {
		$tmp = $this->parameters['multiple'];
		$this->parameters['multiple'] = 1;
		$this->process_field($prefix.'q',$q);
		$this->parameters['multiple'] = $tmp;
		if(is_array($q)) return $this->ar2str($q);
		return $q;
	}

	 ##############################################
	# Returns an SQL WHERE subclause for searching
	function get_search_sql_clause($query,$format) {
		switch($format) {
			case 'sub_match':
				$attributeid = $this->get_searching_attributeid();

				$subformats[$attributeid] = '(0=1';
				foreach(explode('|',$query) as $e) {
					$subformats[$attributeid] .= ' OR v'.$attributeid.'.value LIKE \'%'.addslashes($e).'%\'';
				}
				$subformats[$attributeid] .= ')';
				break;
		}
		return $subformats;
	}

	/**
	* Prints the form for uploading the csv file.
	* 
	* @returns void
	* @access public
	*/
	function print_file_upload_form() {
		$session = &get_mysource_session();
		$e = &$this->get_notitia_system();
		echo file_upload('csv_option_file', "$e->data_path", 50000000, "$this->codename"."_import.{$session->user->id}.tmp",array('csv','txt'));
		echo '<br /><span class="smallprint">When importing you must delimit your file with comma\'s and the must only be two columns. The first column is key and the second column is label</span>';
	}

	/**
	* Processes the form for uploading the csv file and starts the whole import process.
	* 
	* @returns void
	* @access private
	*/
	function process_file_upload_form() {
		$options = &$this->get_value('options');
		$msgs = '';
		$session = &get_mysource_session();
		$e = &$this->get_notitia_system();
		commit_file_upload('csv_option_file', 1, 0, '', $e->data_path, "$this->codename"."_import.{$session->user->id}.tmp");
		if(file_exists($file = "$e->data_path/$this->codename"."_import.{$session->user->id}.tmp")) {
			global $SQUIZLIB_PATH;
			include_once("$SQUIZLIB_PATH/csv/csv.inc");
			$csv = new Csv($file);
			$csv->set_deliminator(',');
			$csv->import();
			$tmp = $csv->values;
			# delete it
			unlink($file);
			if (!empty($tmp)) {
				foreach($tmp as $values) {
					$key = $values[0];
					$label = $values[1];
					$key = ereg_replace("[\|]+","",$key);
					if(!$key) continue;
					$options[$key] = $label;
					$msgs .= 'Option '.$label.' added<br />';
				}
				return $msgs;
			}
		}
	}

	/**
	* Returns an array of of all local attribute to this one appart from itself
	*
	* @returns array
	*/
	function get_local_attribute_list() {
		$category = &$this->get_category($this->attribute->categoryid);
		$local_attributeids = $category->associated_attributes();
		if (count($local_attributeids) < 2) {
			return array(0=>'There are no local attributes');
		} else {
			$attribute_array = array();
			foreach($local_attributeids as $attributeid) {
				$attribute = &$this->get_attribute($attributeid);
				if (!$attribute->id || $attribute->id == $this->attribute->id) continue;
				$attribute_array[$attributeid] = $attribute->name;
			}
			return $attribute_array;
		}
	}

	/**
	* Returns the value of searching attributeid
	*
	* @returns int
	*/
	function get_searching_attributeid() {
		return $this->get_value('searching_attributeid');
	}

	 ###########################
	# Prints out a search field
	function print_search_field($prefix,&$default,$format,$not_null) {
		switch($format) {
			case 'sub_match':
				if(!$this->parameters['multiple'] && !$not_null) {
					$tmp = $this->parameters['options'];
					$this->parameters['options'] = array(''=>'') + $this->parameters['options'];
				}
				if($this->get_value('multiple')) {
					$value = $this->str2ar($value);
				}
				$this->print_x_concise_field($prefix.'q',$default);
				if(!$this->parameters['multiple'] && !$not_null) {
					$this->parameters['options'] = $tmp;
				}
				break;
		}
	}

	/**
	* Sets search requests in the param. Used for static searching
	*
	* @param   string $prefix Prefix
	* @param   string $value  A search value
	* @param   string $format A search format
	* @access  public
	* @returns void
	*/
	function set_search_request($prefix,$value,$format) {
		switch($format) {
			case 'sub_match':
				$_REQUEST[$prefix.'_q_tvalue'] = $value['to'];
				$_REQUEST[$prefix.'_q_fvalue'] = $value['from'];

				if(!$this->parameters['multiple'] && !$not_null) {
					$tmp = $this->parameters['options'];
					$this->parameters['options'] = array(''=>'') + $this->parameters['options'];
				}
				if($this->get_value('multiple')) {
					$value = $this->str2ar($value);
				}

				if($this->get_value('style') == 'list') {
					$_REQUEST[$prefix.'qvalue'] = $value;
				} else {
					$options = &$this->get_value('options');
					reset($options);
					$i = 0;
					while(list($key,$val) = each($options)) {
						if($this->get_value('multiple')) {
							if (in_array($key,$value)) {
								$_REQUEST[$prefix.'qvalue['.$i.']'] = $key;
							}
						} else {
							if ($key == $value) {
								$_REQUEST[$prefix.'qvalue'] = $key;
							}
						}
						$i++;
					}
				}

				if(!$this->parameters['multiple'] && !$not_null) {
					$this->parameters['options'] = $tmp;
				}

				break;
		}
	}

	/**
	* Prints this strange concise field
	*
	* @param   string $prefix Prefix
	* @param   string $value  A value
	* @access  public
	* @returns void
	*/
	function print_x_concise_field($prefix,&$value) {
		if($this->get_value('style') == 'list') {
			if ($this->get_value('multiple')) {
				echo multiple_combo_box($prefix.'value', $this->get_value('options'), $value, '', $this->get_value('width'), $this->get_value('height'));
			} else {
				echo combo_box($prefix.'value',$this->get_value('options'), $value, $this->get_value('width'),$this->get_value('width'));
			}
		} else {
			echo  '<table cellpadding="0" cellspacing="0" border="0"><tr>';
			$options = &$this->get_value('options');
			reset($options);
			$i = 0;
			while(list($key,$val) = each($options)) {
				if($this->get_value('multiple')) {
					echo '<td>&nbsp;<input type="checkbox" name="'.$prefix.'value['.$i.']" value="'.$key.'" '.((in_array($key,$value))?' checked':'').'> '.$val.'</td>';
				} else {
					echo '<td>&nbsp;<input type="radio" name="'.$prefix.'value" value="'.$key.'" '.(($key == $value)?' checked':'').'> '.$val.'</td>';
				}
				if ((($i++) % $this->get_value('columns')) == ($this->get_value('columns') - 1)) {
					echo '</tr><tr>';
				}
			}
			echo '</tr></table>';
		}
	}
}
?>