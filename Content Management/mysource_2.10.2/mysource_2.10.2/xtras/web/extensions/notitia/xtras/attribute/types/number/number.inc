<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/number/number.inc,v $
## $Revision: 1.20 $
## $Author: achadszinow $
## $Date: 2004/03/22 04:21:57 $
#######################################################################

#---------------------------------------------------------------------#

 ####################
# For numeric fields
class Web_Extension_Notitia_Attribute_Type_Number extends Web_Extension_Notitia_Attribute_Type {
	

	 ################################################################
	# This is a static thingy that stores special information about
	# a particular type.
	var $settings = array(
			'preferred_alignment' => 'right',
			'generates_varieties' => false,
			'variable'            => true,
			'variation_types'     => array(
				'replace'        => 'Replace',
				'add'            => 'Add',
				'multiply'       => 'Multiply',
				'percentage'     => 'Percentage',
				'add_percentage' => 'Add percentage',
			),
			'value_formats' => array(
				'raw' => 'Raw',
				'formatted' => 'Formatted',
				'textural' => 'Textural'
			),
			'search_formats' => array (
				'match' => 'Complete Match',
				'range'  => 'Within Range',
				'less'    => 'Less Than or Equal To/Max',
				'greater'  => 'Greater Than or Equal To/Min'
			),
			'edit_formats' => array (
				'raw' => 'Standard'
			),
			'sort_formats' => array (
				'raw' => 'Ascending',
				'raw<' => 'Descending'
			),
			'rule_types' => array (
				'must_answer' => 'Must be answered',
			),
			'requires_context_values' => false,
			'sub_type_name' => 'number',
			'table_settings' => array(
				'sub_table_name' => '_number'
			),
		);

	 
	 ##################################################
	# Takes an original values, and variation and a 
	# variation type, and returns the varied result
	function vary_value($type, &$new_value, &$original) {
		switch($type) {
			case 'add':
				return $original + $new_value;
				break;
			case 'multiply':
				return $original * $new_value;
				break;
			case 'percentage':
				return $original * ($new_value / 100);
				break;
			case 'add_percentage':
				return $original + $original * ($new_value / 100);
				break;
			case 'replace':
				return $new_value;
			default:
				return $original;
				break;
		}
	}
	
	
	 #############################################################
	# Makes a string out of a vlue, for storing in the database
	function unserialize(&$v) {
		settype($v, 'float');
	}


	 #############################################################
	#  Returns the value formatted in a nice way for printing
	function formatted_value(&$value, $format = 'formatted') {
		if(!$format) $format = 'formatted';
		switch($format) {
			case 'formatted':
				$dec_point_char = $this->get_value('dec_point_char');
				if (!$dec_point_char) {
					$dec_point_char = '.';
				}
				if ($this->get_value('decimals')) {
					$dec = $this->get_value('decimals');
					$v = number_format($value, $dec, $dec_point_char, $this->get_value('thousands_sep_char'));
				} else {
					list($int, $float) = explode('.', $value);
					$start = number_format($int, '0', $dec_point_char, $this->get_value('thousands_sep_char'));
					# This '.' is notitia float format so it's not $dec_point_char
					if ($float > 0) {
						$v = $start.$dec_point_char.$float;
					} else {
						$v = $start;
					}
				}
				return $this->get_value('prefix') . $v . $this->get_value('suffix');
			case 'textural':
				return $this->readable_number($value);
				break;
			case 'raw': default:
				return $value;
		}
	}
	

	 #############################################
	# Prints the interface for filling in a value
	function print_concise_field($prefix,&$value) {
		$max = &$this->get_value('max');
		$min = &$this->get_value('min');
		$dec = &$this->get_value('decimals');
		$box_size = $this->get_value('box_width');

		#this code is for backwards compatibility and was put in 19/02/03 
		#can take this out in many years from now
		if (empty($box_size)) {
			$n = max(abs($max),abs($min));
			if($n < 10000000) {
				$box_size = 0;
				while($n >= 1) {$box_size++; $n /= 10;}
				$box_size += 1 + $dec;
			}
			if($box_size < 0 || $box_size > 20) $box_size = 20;
		}

		$js = "this.value = parseFloat(this.value.replace(/[^0-9\.\-]+/g,'')); if (isNaN(this.value)) this.value = 0;";
		if(strlen($min)) $js .= "if(this.value < $min) this.value = $min;";
		if(strlen($max)) $js .= "if(this.value > $max) this.value = $max;";
		echo $this->get_value("prefix");
		echo text_box($prefix."value",$value,$box_size,0,"class=data onchange=\"$js\"");
		echo $this->get_value("suffix");
	}
	
	#############################################
	# Prints the interface for filling in a value
	function print_field($prefix,&$value) {
		$this->print_concise_field($prefix,$value);
		if($note = &$this->get_value("note")) {
			echo("<span class=smallprint><br>$note</span>");
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

		# do normal checks
		if(is_string($value)) {
			$value = preg_replace("/^[^0-9\-\+\.]+|[\, ]+/",'',$value);
		}
		$value = (double) $value;
	}

	 ###########################
	# Prints out a search field
	function print_search_field($prefix,&$default,$format,$not_null) {
		switch($format) {
			case 'match': case 'less': case 'greater':
				$this->print_concise_field($prefix.'q',$default);
				break;
			case 'range':
				$this->print_concise_field($prefix.'q_t',$default['to']);
				echo '<br />';
				$this->print_concise_field($prefix.'q_f',$default['from']);
				break;
		}
	}

	/**
	* Sets search requests in the param. Used for static searching
	*
	* @param   string $prefix Prefix
	* @param   mixed  $value  A search value
	* @param   string $format A search format
	* @access  public
	* @returns void
	*/
	function set_search_request($prefix,$value,$format) {
		switch($format) {
			case 'match': case 'less': case 'greater':
				$_REQUEST[$prefix.'qvalue'] = $value;
				break;
			case 'range':
				$_REQUEST[$prefix.'q_tvalue'] = $value['to'];
				$_REQUEST[$prefix.'q_fvalue'] = $value['from'];
				break;
		}
	}

	 ###################################################
	# Returns a query object which can be given back to
	# print_search_field() or get_search_sql_clause()
	function &process_search_field($prefix,$format,$not_null) {
		if($not_null) $q = 0;
		else $q = '';
		switch($format) {
			case 'match': case 'less': case 'greater':
				$type = $_REQUEST[$prefix.'qvalue'];
				if(strlen($type) == 0) return $q;
				$this->process_field($prefix.'q',$q);
				break;
			case 'range':
				$type1 = $_REQUEST[$prefix.'q_tvalue'];
				$type2 = $_REQUEST[$prefix.'q_fvalue'];
				if(strlen($type1) + strlen($type2) == 0) return $q;
				$this->process_field($prefix.'q_f',$q['from']);
				$this->process_field($prefix.'q_t',$q['to']);
				break;
			default:
				$type = $_REQUEST[$prefix.'q'];
				$q = &$type;
				break;
		}
		return $q;
	}

	 ##############################################
	# Returns an SQL WHERE subclause for searching
	function get_search_sql_clause($query,$format,$value_string='v.value') {
		# Check that the value is valid
		switch($format) {
			case 'match': case 'less': case 'greater':
				if (!is_numeric($query)) {
					# ABORT
					return false;
				}
				break;
			case 'range':
				if (!is_numeric($query['to']) || !is_numeric($query['from'])) { 
					# ABORT
					return false;
				}
				break;
			default:
				# ABORT
				return false;
			break;
		}

		# Now for the search clause
		switch($format) {
			case 'match':
				$match_clause = $value_string.'='.$query;
				break;
			case 'less':
				$match_clause = $value_string.'<'.$query;
				break;
			case 'greater':
				$match_clause = $value_string.'>='.$query;
				break;
			case 'range':
				$from = min($query['to'],$query['from']);
				$to   = max($query['to'],$query['from']);
				$match_clause = $value_string.'>='.$from.' AND '.$value_string.'<='.$to;
				break;
		}
		return $match_clause;
	}


	 ###############################################################
	# Returns an SQL expression that evalutates to a relevancy score
	function get_search_sql_score($query,$format,$value_string='v.value') {
		return;
	}

	 ########################################################
	# Returns an SQL subclause for sorting in an ORDER clause
	function get_search_sql_sort_clause($format,$value_string='v.value') {
		return '('.$value_string.'+0)';
	}

	/**
	* Turns a int into words. It's kind of american though
	*
	* @param int $number The int to turn into words
	* @return string
	*/
	function readable_number($number) {
		static $bits_a = array('Thousand', 'Million', 'Billion', 'Trillion', 'Zillion');
		static $bits_b = array('Ten', 'Twenty', 'Thirty', 'Fourty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety');
		static $bits_c = array('One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen');
		if ($number == 0) {
			return 'Zero';
		}
		$out = ($number < 0)?'Minus ':'';
		$number = abs($number);
		for($i = count($bits_a); $i > 0; $i--) {
			$p = pow(1000, $i);
			if ($number > $p) {
				$b = floor($number/$p);
				$number -= $p * $b;
				$out .= $this->readable_number($b).' '.$bits_a[$i-1].(($number)?', ':'');
			}
		}
		if ($number > 100) {
			$b = floor($number/100);
			$number -= 100 * $b;
			$out .= $this->readable_number($b).' Hundred'.(($number)?' and ':' ');
		}
		if ($number >= 20) {
			$b = floor($number/10);
			$number -= 10 * $b; 
			$out .= $bits_b[$b-1].' ';
		}
		if ($number) {
			$out .= $bits_c[$number-1];
		}
		return $out;
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
		if ($this->attribute->plays_role('auto_increment')) {
			$role = &$this->attribute->get_role_handler('auto_increment');
			# This may get run twice but it should work. Aim to get rid of the run twice in the long run.
			$role->increment($value);
		}
	}
}
?>