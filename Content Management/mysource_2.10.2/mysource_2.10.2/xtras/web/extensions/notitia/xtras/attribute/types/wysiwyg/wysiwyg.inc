<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/wysiwyg/wysiwyg.inc,v $
## $Revision: 1.20 $
## $Author: dofford $
## $Date: 2004/03/08 01:12:05 $
#######################################################################
global $SQUIZLIB_PATH;
require_once($SQUIZLIB_PATH.'/wysiwyg/wysiwyg.inc');
#---------------------------------------------------------------------#

 ####################
# For text fields...
class Web_Extension_Notitia_Attribute_Type_WYSIWYG extends Web_Extension_Notitia_Attribute_Type {
	
	 ################################################################
	# This is a static thingy that stores special information about
	# a particular type.
	var $settings = array(
			'preferred_alignment' => 'left',
			'generates_varieties' => false,
			'variable'            => true,
			'value_formats' => array(
				'raw' => 'Raw',
				'summary' => 'Summary'
			),
			'search_formats' => array (
				'match'   => 'Match',
				'partial' => 'Partial Match',
				'complex' => 'Loose Match'
			),
			'edit_formats' => array (
				'raw' => 'WYSIWYG',
			),
			'sort_formats' => array (
				'alpha' => 'Alphabetically',
				'alpha<' => 'Reverse Alpha.',
				'raw' => 'ASCII Order',
				'raw<' => 'Reverse ASCII'
			),
			'rule_types' => array (
				'must_answer' => 'Must be answered',
			),
			'requires_context_values' => false,
			'table_settings' => array(
				'sub_table_name' => ''
			)
		);


	 ################################################
	# Returns the value formatted in a nice way for printing
	function formatted_value(&$value,$format ='nl2br') {
		if(!$format) $format = 'nl2br';
		switch($format) {
			case 'summary':
				$append = '...';
				$value = preg_replace('/(<br \/>)([\s])*(<br \/>)/', '<br />', nl2br(str_replace('<P>','',str_replace('</P>',"\n",$value))));
				# if we are limiting this summary to the first paragraph, set value to be just the first paragraph
				if ($this->parameters['summary_limit'] == 'paragraph') list($value) = explode('<br />',nl2br($value));

				switch ($this->parameters['summary_item']) {
					case 'characters' :
						if ($this->parameters['summary_items_to_print'] <= 0) $this->parameters['summary_items_to_print'] = 150;
						$retval = '';
						if (trim($value) != '') {
							$count = 0;
							for ($i = 0; $i < strlen($value); $i++) {
								$retval .= $value[$i];
								$count++;
								if ($count >= $this->parameters['summary_items_to_print']) {
									# we have printed the number of characters that we are after
									# but we dont want to stop half way through a word, so we
									# keep printing until the next bit of whitspace for neatness
									if (trim($value[($i+1)]) != '') {
										$this->parameters['summary_items_to_print']++;
									} else {
										break;
									}
								}
							}
							if ($count < $this->parameters['summary_items_to_print']) $append = '';
						}
						break;
					case 'paragraphs' :
						$append = '';
						$paras = explode('<br />',nl2br($value));
						$retval = '';
						if (count($paras)) {
							$count = 0;
							foreach ($paras as $para) {
								if (!trim(str_replace('<br />', '', $para))) continue;
								$retval .= $para;
								$count++;
								if ($count >= $this->parameters['summary_items_to_print']) {
									break;
								} else {
									$retval .= '<br /><br />';
								}
							}
						}
						break;
					case 'words' : default : 
						if ($this->parameters['summary_items_to_print'] <= 0) $this->parameters['summary_items_to_print'] = 150;
						$words = explode(' ', $value);
						$retval = '';
						if (count($words)) {
							$count = 0;
							foreach ($words as $word) {
								$retval .= $word;
								$count++;
								if ($count >= $this->parameters['summary_items_to_print']) {
									break;
								} else {
									$retval .= ' ';
								}
							}
						}
						if ($count < $this->parameters['summary_items_to_print']) $append = '';
						break;
				}
				return trim($retval).$append;
			case 'raw': default:
				return $value;
		}
	}
	
	 #############################################
	# Prints the interface for filling in a value
	function print_concise_field($prefix,&$value,$format) {
		$page = &$this->web_system->get_page();
		if ($page->id) {
			$design = &$page->get_design();
			$styles = $design->get_var('bodycopy_styles');
			$styles = $styles['options'];

			$wysiwyg = new wysiwyg($prefix.'value', $page->siteid, $page->id, 
									$design->stylesheet('abs'), $styles, 
									$this->parameters['enable_styles'], $this->parameters['allow_image_uploads'], 
									$this->parameters['allow_colour_selections'], $this->parameters['enable_keywords'],
									$this->parameters['enable_source'], $this->parameters['linking_interface'],
									$this->parameters['enable_ccp'],
									$this->parameters['allow_spell_check'], $this->parameters['allow_file_upload'], $this->parameters['allow_file_delete']);
			$wysiwyg->paint_embeded($value, $this->get_value('width'), $this->get_value('height'));
		} else {
			# just print a normal text box
			echo text_area('squizlib_wysiwyg_content['.$prefix.'value]',$value,60,14);
		}
	}

	#############################################
	# Prints the interface for filling in a value
	function print_field($prefix,&$value, $format) {
		$this->print_concise_field($prefix,$value,$format);
		if($note = &$this->get_value('note')) {
			echo("<span class=smallprint><br>$note</span>");
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
		$input = $_POST['squizlib_wysiwyg_content'][$prefix.'value'];
		$input = stripslashes_array($input);
		$this->validate_value($input, $rules_broken_messages);
		if($value_destination === $input) return;
		if ($rules_broken_messages) {
			return 'Rule broken in attribute '.$this->attribute->name.'. '.$rules_broken_messages;
		} else {
			$value_destination = $input;
			return $this->attribute->name.' updated.';
		}
	}

	 ###################################################
	# Returns a query object which can be given back to
	# print_search_field() or get_search_sql_clause()
	function &process_search_field($prefix,$format,$not_null) {
		$q = '';
		$this->process_field($prefix.'q',$q);
		if($format == 'match') {
			$q = trim($q);
		}
		if($not_null && !$q) return $q = ' ';
		return $q;
	}

	 ##############################################
	# Returns an SQL WHERE subclause for searching
	function get_search_sql_clause($query,$format,$value_string='v.value') {
		switch($format) {
			case 'match':
				$cmp = $value_string.' = "'.addslashes($query).'"';
				break;
			case 'partial':
				$cmp = $value_string.' LIKE "%'.addslashes($query).'%"';
				break;
			case 'complex':
				$cmp = complex_search_sql_conditions($value_string,$query, (($this->get_value('apostrophes_searchable'))?true:false));
				break;
		}
		return $cmp;
	}

	 ##############################################
	# Returns an SQL expression that evalutates a clause
	function get_search_sql_score($query,$format,$value_string='v.value') {
		return complex_search_sql_score($value_string,$query);
	}

	 ########################################################
	# Returns an SQL subclause for sorting in an ORDER clause
	function get_search_sql_sort_clause($format,$value_string='v.value') {
		switch($format) {
		case 'alpha':
			return 'LOWER('.$value_string.')';
		case 'raw': default:
			return $value_string;
		}
	}

	/**
	* Checks the value is valid
	*
	* @param  string &$value            The value we need to check against the rules
	* @param  string &$rule_broken_msgs The broken rule messages
	* @access public
	* @return void
	*/
	function validate_value(&$value, &$rule_broken_msgs) {
		#check for rules so we need the rules array
		$this->validate_value_rules($value, $rule_broken_msgs);
	}

	/**
	* Returns the basic new settings for the must answer rule type
	*
	* @param  string $value         The value we need to check against the rule
	* @param  array  $rule_settings The settings of the rule we are checking against the value
	* @access public
	* @return string
	*/
	function validate_must_answer_rule($value, $rule_settings) {
		return ((strip_tags(trim($value), '<img>') == '')?$rule_settings['broken_message']:'');
	}

	 ###########################
	# Prints out a search field
	function print_search_field($prefix,&$default,$format,$not_null) {
		$search_height = $this->get_value('search_height');
		$search_width = $this->get_value('search_width');
		if (!is_numeric($search_height)) {
			$search_height = 1;
		}
		if (!is_numeric($search_width)) {
			$search_width = 1;
		}
		if($search_height > 1) {
			echo text_area('squizlib_wysiwyg_content['.$prefix.'qvalue]',$default,$search_width,$search_height);
		} else {
			echo text_box('squizlib_wysiwyg_content['.$prefix.'qvalue]',$default,$search_width,'','class="data"');
		}
	}

	/**
	* Sets in the request vars a static search
	*
	* @param   string $prefix Prefix
	* @param   string $value  A value
	* @param   string $format A format
	* @access  public
	* @returns void
	*/
	function set_search_request($prefix,$value,$format) {
		$_REQUEST[$prefix.'qvalue'] = $value;
	}
}
?>