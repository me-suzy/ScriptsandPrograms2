<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/hyperlink/hyperlink.inc,v $
## $Revision: 1.29 $
## $Author: achadszinow $
## $Date: 2004/02/25 01:09:41 $
#######################################################################

#---------------------------------------------------------------------#

 ############################
# For simple hyperlinked text
class Web_Extension_Notitia_Attribute_Type_Hyperlink extends Web_Extension_Notitia_Attribute_Type {
	
	 ################################################################
	# This is a static thingy that stores special information about
	# a particular type.
	var $settings = array(
			'preferred_alignment' => 'left',
			'generates_varieties' => false,
			'variable'            => true,
			'variation_types'   => array(
				'replace' => 'Replace',
				'prepend' => 'Prepend (URL Append)',
				'append' => 'Append',
				'append_with_space' => 'Append With Space (URL Append directly)',
				'append_with_dash' => 'Append With Dash (" ")',
				'append_with_comma' => 'Append With Comma (" ")',
				'append_with_slash' => 'Append With Slash (" ")',
				'append_in_brackets' => 'Append In Brackets (" ")'
			),
			'value_formats' => array(
				'raw' => 'Raw Array',
				'default' => 'Default CSS class',
				'mainheading' => 'Using CSS class "mainheading"',
				'heading' => 'Using CSS class "heading"',
				'subheading' => 'Using CSS class "subheading"',
				'subsubheading' => 'Using CSS class "subsubheading"',
				'smallprint' => 'Using CSS class "smallprint"',
				'fineprint' => 'Using CSS class "fineprint"',
				'raw_link_href' => 'Raw Link HREF',
				'raw_link_href_text' => 'Raw Link HREF Text',
				'raw_link_text' => 'Raw Link Text',
			),
			'search_formats' => array (
				'raw_match' => 'Match Raw Notitia Hyperlink Completely',
				'match_text'  => 'Complete Match Text',
				'match_url' => 'Complete Match URL',
				'partial_text'  => 'Partial Text',
				'partial_url' => 'Partial URL',
				'complex_text'  => 'Complex Text',
				'complex_url' => 'Complex URL',
				'partial' => 'Partial text or url',
				'complex' => 'Complex text or url'
			),
			'edit_formats' => array (
				'raw' => 'Standard'
			),
			'sort_formats' => array (
				'raw' => 'Alphabetically by Label',
				'raw<' => 'Reverse Alphabetically by Label'
			),
			'rule_types' => array (
				'must_answer' => 'Must be answered',
			),
			'requires_context_values' => false,
			'sub_type_name' => 'hyperlink',
			'table_settings' => array(
				'sub_table_name' => ''
			)
	);

	
	 ##################################################
	# Takes an original values, and variation and a 
	# variation type, and returns the varied result
	function vary_value($type, &$new_value, &$original) {
		switch($type) {
			case 'prepend':
				return array('t'=>$new_value['t'].$original['t'], 'u'=>$new_value['u'].$original['u']);
			case 'append':
				return array('t'=>$original['t'].$new_value['t'], 'u'=>$original['u'].$new_value['u']);
			case 'append_with_space':
				return array('t'=>$new_value['t'].' '.$original['t'], 'u'=>$new_value['u'].$original['u']);
			case 'append_with_dash':
				return array('t'=>$new_value['t'].' - '.$original['t'], 'u'=>$new_value['u'].$original['u']);
			case 'append_with_comma':
				return array('t'=>$new_value['t'].', '.$original['t'], 'u'=>$new_value['u'].$original['u']);
			case 'append_with_slash':
				return array('t'=>$new_value['t'].' / '.$original['t'], 'u'=>$new_value['u'].$original['u']);
			case 'append_in_brackets':
				return array('t'=>$new_value['t'].' ('.$original['t'].')', 'u'=>$new_value['u'].$original['u']);
			case 'replace':
				return $new_value;
			default:
				return $original;
				break;
		}
	}

	 ################################################
	# Returns the appropriate prefix for the current
	# URL protocol.
	function get_protocol_prefix() {
		switch($this->get_value('protocol')) {
			case 'mysource':
			case 'mysource_site':
			case 'mysource_page':
			case 'mysource_file':
				$protocol = './?';
				break;
			case 'http': case 'https': case 'ftp':
				$protocol = $this->get_value('protocol').'://';
				break;
			case 'mailto': default:
				$protocol = $this->get_value('protocol');
				if(!$protocol) $protocol = '';
				else $protocol .= ':';
				break;
		}
		return $protocol;
	}

	 #############################################################
	# Makes a string out of a value, for storing in the database
	function serialize(&$v) {
		if (is_array($v)) {
			return "$v[t]|$v[u]";
		}
		return $v;
	}
	function unserialize(&$v) {
		if(substr($v,0,2) == 'a:' && is_array($tmp = unserialize($v))) {
			$v = $tmp;
		} else {
			list($t,$u) = explode('|',$v);
			$v = array('t'=>&$t,'u'=>&$u);
		}
	}


	 ################################################
	# Returns the value formatted in a nice way for printing
	function formatted_value(&$value,$format ='default') {
		if(!$format) $format = 'default';
		if (!is_array($value)) return '';
		switch($format) {
			case 'default':
			case 'mainheading':
			case 'heading':
			case 'subheading':
			case 'subheading':
			case 'subsubheading':
			case 'smallprint':
			case 'fineprint':
				if(!$value['u']) return $value['t'];
				if($format == 'default') $format = '';
				$protocol = $this->get_protocol_prefix();
				if($this->get_value('single_input_box')) $link_text = $value['u'];
				else $link_text = $value['t'];
				$target = $this->get_value('target');
				return '<a href="'.$protocol.$value['u'].'" target="'.$target.'" class="'.$format.'">'.$link_text.'</a>';
			case 'raw_link_href':
				$protocol = $this->get_protocol_prefix();
				return $protocol.$value['u'];
				break;
			case 'raw_link_href_text':
				return $value['u'];
				break;
			case 'raw_link_text':
				if($this->get_value('single_input_box')) return $value['u'];
				return $value['t'];
				break;
			case 'raw':
				return $value;
		}
	}
	
	 #############################################
	# Prints the interface for filling in a value
	function print_concise_field($prefix,&$value) {
		if (!$this->get_value('single_input_box')) {
			echo('<b>Text:</b> ');
			if($this->get_value("height") > 1) {
				echo text_area($prefix."value[t]",$value['t'],$this->get_value("width"),$this->get_value("height"),$this->get_value("maxlength"));
			} else {
				echo text_box($prefix."value[t]",$value['t'],$this->get_value("width"),$this->get_value("maxlength"),'class=Data"');
			}
			echo('<br><b>Link:</b>');
		}
		$protocol = $this->get_value('protocol');
		$protocol_prefix = $this->get_protocol_prefix();
		if(ereg("^mysource",$protocol)) {
			if($protocol == 'mysource') {
				$def = substr($value['u'],0,2);
				echo combo_box($prefix.'value[u][p]',array('s='=>'Site ID:','p='=>'Page ID:','f='=>'File ID:'),$def);
			} else {
				switch($protocol) {
					case 'mysource_site':
						$l = 's'; $n = 'Site'; break;
					case 'mysource_page':
						$l = 'p'; $n = 'Page'; break;
					case 'mysource_file':
						$l = 'f'; $n = 'File'; break;
				}
				echo '<input type="hidden" name="'.$prefix.'value[u][p]" value="'.$l.'=">'.$n.' ID:';
			}
			echo text_box($prefix."value[u][s]", ereg_replace("[^0-9]","",$value['u']),8,25, 'class="data"');
		} else {
			if (!$this->get_value('single_input_box')) {
				echo '<span class="monospace">'.$protocol_prefix.'</span>';
			}
			echo text_box($prefix."value[u]", $value['u'], $this->get_value("url_width") ,$this->get_value("url_maxlength"), 'class="data"');
		}
	}

	#############################################
	# Prints the interface for filling in a value
	function print_field($prefix,&$value) {
		$this->print_concise_field($prefix,$value);
		if($note = &$this->get_value("note")) {
			echo '<span class="smallprint"><br />'.$note.'</span>';
		}
	}


	 #############################################
	# Prints the interface for filling in a value
	function process_field($prefix,&$value_destination, $format, &$rules_broken_messages) {
		$type = $_REQUEST[$prefix.'value'];
		$type = gpc_stripslashes($type);
		$input = &$type;
		if(ereg("^mysource",$this->get_value('protocol'))) {
			$input['u'] = $input['u']['p'].$input['u']['s'];
		}

		$this->validate_value($input, $rules_broken_messages);
		
		if(equal_arrays($value_destination,$input)) return;

		if ($rules_broken_messages) {
			#return nothing so value isn't saved if a rule has been broken
			return 'Rule broken in attribute '.$this->attribute->name.'. '.$rules_broken_messages;
		} else {
			$value_destination = $input;
			return $this->attribute->name.' updated.';
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
	function validate_value(&$value, &$rule_broken_msgs, $type) {
		# Check for rules so we need the rules array
		$this->validate_value_rules($value, $rule_broken_msgs);

		if ($type == 'importing' && $this->get_value('single_input_box') && substr_count($value, '|') == 0) {
			$value = array('t' => '', 'u' => $value);
		}

		# do normal checks
		if(!$this->get_value('protocol') && valid_email($value['u'])) {
			if(!ereg("^mailto\:",$value['u'])) {
				$value['u'] = 'mailto:'.$value['u'];
			}
		}
		if($h = &$this->get_value("height") <= 1) {
			$value['t'] = ereg_replace("[\r\n]+"," ",$value['t']);
		}
		if($ml = &$this->get_value("maxlength")) {
			if(strlen($value['t']) > $ml) $value['t'] = substr($value['t'],0,$ml);
		}
		if($ml = &$this->get_value("url_maxlength")) {
			if(strlen($value['u']) > $ml) $value['u'] = substr($value['u'],0,$ml);
		}
	}

	 ###################################################
	# Returns a query object which can be given back to
	# print_search_field() or get_search_sql_clause()
	function &process_search_field($prefix,$format,$not_null) {
		$type = $_REQUEST[$prefix.'q'];
		$q = trim(gpc_stripslashes($type));
		if($not_null && !$q) {
			$ret_val = ' ';
			return $ret_val;
		}
		return $q;
	}

	 ##############################################
	# Returns an SQL WHERE subclause for searching
	function get_search_sql_clause($query,$format,$value_string='v.value') {
		switch($format) {
			case 'raw_match':
				$cmp = $value_string.' = "'.addslashes($query).'"';
				break;
			case 'match_text':
				$cmp = $value_string.' LIKE "'.addslashes($query).'|%"';
				break;
			case 'match_url':
				$cmp = $value_string.' LIKE "%|'.addslashes($query).'"';
				break;
			case 'partial_text':
				$cmp = $value_string.' LIKE "%'.addslashes($query).'%|%"';
				break;
			case 'partial_url':
				$cmp = $value_string.' LIKE "%|%'.addslashes($query).'%"';
				break;
			case 'complex_text':
				$cmp = complex_search_sql_conditions("SUBSTRING($value_string,0,LOCATE('|',$value_string))",$query,(($this->get_value('apostrophes_searchable'))?true:false));
				break;
			case 'complex_url':
				$cmp = complex_search_sql_conditions("SUBSTRING($value_string,LOCATE('|',$value_string)+1)",$query,(($this->get_value('apostrophes_searchable'))?true:false));
				break;
			case 'partial':
				$cmp = $value_string.' LIKE "%'.addslashes($query).'%"';
				break;
			case 'complex':
				$cmp = complex_search_sql_conditions($value_string,$query,(($this->get_value('apostrophes_searchable'))?true:false));
				break;
		}
		return $cmp;
	}


	 ##############################################
	# Returns an SQL expression that evalutates a clause
	function get_search_sql_score($query,$format,$value_string='v.value') {
		switch($format) {
			case 'match_text': case 'partial_text': case 'complex_text':
				$cmp = complex_search_sql_score("SUBSTRING($value_string,0,LOCATE('|',$value_string))",$query);
				break;
			case 'match_url': case 'partial_url': case 'complex_url':
				$cmp = complex_search_sql_score("SUBSTRING($value_string,LOCATE('|',$value_string)+1)",$query);
				break;
			case 'complex': case 'partial':
				$cmp = complex_search_sql_score($value_string,$query);
				break;
		}
		return $cmp;
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
		if($this->get_value('single_input_box')) return (($value['u'] == '')?$rule_settings['broken_message']:'');
		return (($value['t'] == '' || $value['u'] == '')?$rule_settings['broken_message']:'');
	}

	function compare_values(&$value1,&$value2) {
		return array_compare($value1,$value2);
	}

	 ###########################
	# Prints out a search field
	function print_search_field($prefix,&$default,$format,$not_null) {
		echo text_box($prefix.'q',$default,30);
	}

	/**
	* Sets in the request vars a static search
	*
	* @param   string $prefix Prefix
	* @param   string $value  A value
	* @param   string $format A search format
	* @access  public
	* @returns void
	*/
	function set_search_request($prefix,$value,$format) {
		$_REQUEST[$prefix.'q'] = $value;
	}

	/**
	* Processes any pre save attribute type specifics
	*
	* @access public
	* @param int $recordid - the recordid obviously
	* @param mixed $value - the value
	* @return void
	*/
	function pre_save_record_value($recordid, &$value) {
		$user_ref_attribute = &$this->get_attribute($this->get_value('user_ref_attributeid'));
		if ($user_ref_attribute->id && $user_ref_attribute->type == 'user_reference') {
			$record = &$this->get_record($recordid);
			$userid = $record->get_attribute_formatted_value($user_ref_attribute->id, 'id');
			if ($userid > 0) {
				$users_system = &get_users_system();
				$user = $users_system->get_user($userid);
				if ($user->id) {
					# Email addy should always be in the URL part of the hyperlink
					$user->set_email($value['u']);
				}
			}
		}
	}
}
?>