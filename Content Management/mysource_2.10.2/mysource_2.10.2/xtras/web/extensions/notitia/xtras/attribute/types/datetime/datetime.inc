<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/datetime/datetime.inc,v $
## $Revision: 1.17 $
## $Author: nduggal $
## $Date: 2004/01/16 06:25:35 $
#######################################################################
global $SQUIZLIB_PATH;
include_once("$SQUIZLIB_PATH/datetime_field/datetime_field.inc");
#---------------------------------------------------------------------#

 ####################
# For numeric fields
class Web_Extension_Notitia_Attribute_Type_DateTime extends Web_Extension_Notitia_Attribute_Type {
	

	 ################################################################
	# This is a static thingy that stores special information about
	# a particular type.
	var $settings = array(
			'preferred_alignment' => 'left',
			'generates_varieties' => false,
			'variable'            => true,
			'variation_types'     => array(
				'replace'        => 'Replace'
				#'add'            => 'Add'
			),
			'value_formats' => array(
				'raw'       => 'Raw',
				'short'     => 'Short',
				'medium'    => 'Medium',
				'long'      => 'Long',
				'timestamp' => 'UNIX Timestamp'
			),
			'search_formats' => array (
				'match'  => 'Complete Match',
				'range'  => 'Within Range',
				'before' => 'Before',
				'after'  => 'After',
				'last_unit' => 'Last hour, day, week...'
			),
			'edit_formats' => array (
				'raw' => 'Standard'
			),
			'sort_formats' => array (
				'raw' => 'Chronologically',
				'raw<' => 'Reverse Chronologically'
			),
			'rule_types' => array (
			),
			'requires_context_values' => false,
			'sub_type_name' => 'datetime',
			'table_settings' => array(
				'sub_table_name' => ''
			)
		);


	 ###################################################
	# Loads the default parameter set that we assume
	# is included with the class
	function load_default_parameters() {
		$this->parameters['min']  = '1970-01-01 00:00:00';
		$this->parameters['max']  = '2030-12-31 23:59:59';
		$this->parameters['show'] = array('y','m','d','h','i','s');
		foreach($this->parameters['show'] as $u) {
			$this->parameters['input_style'][$u] = 's';
		}
		$this->parameters['long_format'] = 'l, F jS, Y H:i:s';
		$this->parameters['medium_format'] = 'r';
		$this->parameters['short_format'] = 'd/m/Y g:ia';
	}

	 
	 ##################################################
	# Takes an original values, and variation and a 
	# variation type, and returns the varied result
	function vary_value($type, &$new_value, &$original) {
		switch($type) {
			default:
				return $new_value;
				break;
		}
	}
	
	 ############################################################################
	# Given a datetime string returns the number of days in whatever month it is
	function days_in_current_month($value) {
		list($m,$l) = explode(' ',$this->format('n L',$value));
		switch($m) {
			case 2:
				return (($l)?29:28);
			case 1: case 3: case 5: case 7: case 8: case 10: case 12:
				return 31;
			default:
				return 30;
		}
	}
	
	 #############################################################
	#  Returns the value formatted in a nice way for printing
	function formatted_value(&$value, $format = 'medium') {
		if(!$format) $format = 'medium';
		switch($format) {
			case 'long': case 'medium': case 'short':
				return $this->format($this->get_value($format.'_format'),$value);
			case 'timestamp':
				return mysql_to_timestamp($value);
			case 'raw': default:
				return $value;
		}
	}
	

	 #############################################
	# Prints the interface for filling in a value
	function print_concise_field($prefix,&$value) {
		$this->check_backwards_compatibility();
		$field = new datetime_field($prefix,$value,$this->parameters);
		$field->print_field();
	}


	 #############################################
	# Prints fields for inputting the min and max
	function print_min_field() {
		$this->check_backwards_compatibility();
		$field = new datetime_field("a{$this->attribute->id}",$value,$this->parameters);
		$field->print_min_field();
	}
	function process_min_field() {
		$this->check_backwards_compatibility();
		$field = new datetime_field("a{$this->attribute->id}",$value,$this->parameters);
		$field->process_min_field();
	}
	function print_max_field() {
		$this->check_backwards_compatibility();
		$field = new datetime_field("a{$this->attribute->id}",$value,$this->parameters);
		$field->print_max_field();
	}
	function process_max_field() {
		$this->check_backwards_compatibility();
		$field = new datetime_field("a{$this->attribute->id}",$value,$this->parameters);
		$field->process_max_field();
	}

	 #############################################
	# Prints the interface for filling in a value
	function print_field($prefix,&$value) {
		$this->print_concise_field($prefix,$value);
		if($note = &$this->get_value("note")) {
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
		$this->check_backwards_compatibility();
		$field = new datetime_field($prefix,$value_destination,$this->parameters);
		if ($field->process_field()) {
			return "{$this->attribute->name} updated.";
		}

	}

	 ################################################
	# Uses date() to format a value in a given way
	function format($format,$value) {
		$this->check_backwards_compatibility();
		$field = new datetime_field("a{$this->attribute->id}",$value,$this->parameters);
		return trim($field->format($format));
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
		$this->check_backwards_compatibility();
		$field = new datetime_field("a{$this->attribute->id}",$value,$this->parameters);
		return $field->validate_value();
	}

	 #########################
	# Prints the edit options
	function print_edit_options() {
		$this->check_backwards_compatibility();
		$field = new datetime_field("a{$this->attribute->id}",$value,$this->parameters);
		return $field->print_edit_options();
	}

	 #########################
	# Prints the edit options
	function process_edit_options() {
		$this->check_backwards_compatibility();
		$field = new datetime_field("a{$this->attribute->id}",$value,$this->parameters);
		return $field->process_edit_options();
	}


	 ###############################################
	# Prints instructions for writing output formats
	function print_output_format_instructions() {
		?>
		Define your output formats using letters to represent time information.<br>
		E.g. "l, F jS Y" => "Sunday, March 9th 1980"
		<ul>
		<?foreach($this->get_format_code_descriptions() as $code => $desc){?>
			<li><b><?=$code?></b> - <?=$desc?></li>
		<?}?>
		</ul>
		Note that if certain elements of the date are blank, affected elements will be removed from the formatted result. Additionally, some elements will not work for dates before 1970.
		<?
	}


	 ####################################
	# Returns as array of all the letter codes
	# for defining formats.
	function get_format_code_descriptions() {
		return array(
			'a' => '"am" or "pm"',
			'A' => '"AM" or "PM"',
			'B' => 'Swatch Internet time',
			'c' => 'Prints "c." or blank depending on whether the date is stored as "circa."',
			'C' => 'Prints "circa." or blank depending on whether the date is stored as "circa."',
			'd' => 'day of the month, 2 digits with leading zeros; i.e. "01" to "31"',
			'D' => 'day of the week, textual, 3 letters; i.e. "Fri"',
			'F' => 'month, textual, long; i.e. "January"',
			'g' => 'hour, 12-hour format without leading zeros; i.e. "1" to "12"',
			'G' => 'hour, 24-hour format without leading zeros; i.e. "0" to "23"',
			'h' => 'hour, 12-hour format; i.e. "01" to "12"',
			'H' => 'hour, 24-hour format; i.e. "00" to "23"',
			'i' => 'minutes; i.e. "00" to "59"',
			'I' => '"1" if Daylight Savings Time, "0" otherwise.',
			'j' => 'day of the month without leading zeros; i.e. "1" to "31"',
			'l' => 'day of the week, textual, long; i.e. "Friday"',
			'L' => 'boolean for whether it is a leap year; i.e. "0" or "1"',
			'm' => 'month; i.e. "01" to "12"',
			'M' => 'month, textual, 3 letters; i.e. "Jan"',
			'n' => 'month without leading zeros; i.e. "1" to "12"',
			'O' => 'Difference to Greenwich time in hours; i.e. "+0200"',
			'r' => 'RFC 822 formatted date; i.e. "Thu, 21 Dec 2000 16:01:07 +0200"',
			's' => 'seconds; i.e. "00" to "59"',
			'S' => 'English ordinal suffix for the day of the month, 2 characters; i.e. "th", "nd"',
			't' => 'number of days in the given month; i.e. "28" to "31"',
			'T' => 'Timezone setting of this machine; i.e. "MDT"',
			'U' => 'seconds since the epoch',
			'w' => 'day of the week, numeric, i.e. "0" (Sunday) to "6" (Saturday)',
			'W' => 'ISO-8601 week number of year, weeks starting on monday',
			'Y' => 'year, 4 digits; i.e. "1999"',
			'y' => 'year, 2 digits; i.e. "99"',
			'z' => 'day of the year; i.e. "0" to "365"',
			'Z' => 'timezone offset in seconds (i.e. "-43200" to "43200"). The offset for timezones west of UTC is always negative, and for those east of UTC is always positive.'
		);
	}

	 ###################################################
	# Returns a query object which can be given back to
	# print_search_field() or get_search_sql_clause()
	function &process_search_field($prefix,$format,$not_null) {
		$type = $_REQUEST[$prefix.'y'];
		$q = '';
		if(!$type && $format != 'range' && $format != 'last_unit') return $q;
		switch($format) {
			case 'match': case 'before': case 'after':
				$this->process_field($prefix.'q',$q);
				break;
			case 'range':
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
		$match_clause = '0=1';
		switch($format) {
			case 'match':
				$match_clause = "STRCMP($value_string,'$query')=0";
				break;
			case 'before':
				$match_clause = "STRCMP($value_string,'$query')<=0";
				break;
			case 'after':
				$match_clause = "STRCMP($value_string,'$query')>=0";
				break;
			case 'range':
				if ($query['from'] == $query['to']) {
					return false;
				}
				$match_clause = "((STRCMP($value_string,'$query[from]')>=0) AND (STRCMP($value_string,'$query[to]')<=0))";
				$match_clause .= " OR ((STRCMP($value_string,'$query[to]')>=0) AND (STRCMP($value_string,'$query[from]')<=0))";
				break;
			case 'last_unit':
				$from = time();
				$to = $from;
				$offsets = array(
					'h' => ($h = 3600),
					'd' => ($d = $h * 24),
					'w' => ($d * 7),
					'm' => ($d * date('t')),
					'y' => ($d * 365.24),
				);
				$from -= (int) $offsets[$query];
				$this->validate_value($from); # Convert from timestamp
				$this->validate_value($to); # Convert from timestamp
				$match_clause = "STRCMP($value_string,'$from')>=0 AND STRCMP(v.value,'$to')<=0";
				break;
			default:
				break;
		}
		return $match_clause;
	}

	 ###############################################################
	# Returns an SQL expression that evalutates to a relevancy score
	function get_search_sql_score($query,$format) {
		return;
	}

	 ##################################################
	# Makes any adjustments if this is an old version
	function check_backwards_compatibility() {
		if(isset($this->parameters['input_style']) && !isset($this->parameters['style'])) {
			$this->parameters['style'] = &$this->parameters['input_style'];
			unset($this->parameters['input_style']);
		}
	}

	 ###########################
	# Prints out a search field
	function print_search_field($prefix,&$default,$format,$not_null) {
		if($default) $checked = 'checked';
		$d = new datetime_field($null,$null,$this->parameters);
		if ($this->parameters['current_datetime']) {
			$dateval = $d->units_array_to_str($this->get_request_array(date('Y-m-d H:i:s')));
			if (($format == 'range' && $default == '') || (is_array($default)
				&& $default['to'] == '---------- --:--:--' 
				&& $default['from'] == '---------- --:--:--')) {
				$default['to'] = $dateval;
				$default['from'] = $dateval;
			} 
			elseif ($default == '---------- --:--:--' || $default == '') {
				$default = $dateval;
			}
		}
		if(!$not_null && $format != 'last_unit' && $format != 'range') 
		{
			echo '<input type="checkbox" name="'.$prefix.'y" value="1" '.$checked.'> '.$this->settings['search_formats'][$format].'<br />';
		}
		switch($format) {
			case 'match': case 'before': case 'after':
				$this->print_concise_field($prefix.'q',$default);
				break;
			case 'range':
				$this->print_concise_field($prefix.'q_t',$default['to']);
				echo '<br />';
				$this->print_concise_field($prefix.'q_f',$default['from']);
				break;
			case 'last_unit':
				$options = array();
				if(!$not_null) {
					$options[''] = 'Any Time';
				}
				$show = implode('',$this->get_value('show'));
				#if(ereg("[sih]",$show)) $options['i'] = 'Last Minute';
				if(strstr('h',$show)) $options['h'] = 'Last Hour';
				if(strstr('d',$show)) $options['d'] = 'Last Day';
				if(strstr('d',$show)) $options['w'] = 'Last Week';
				if(strstr('m',$show)) $options['m'] = 'Last Month';
				if(strstr('y', $show)) $options['y'] = 'Last Year';
				echo combo_box($prefix.'q',$options,$default);
				break;
		}
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
		$_REQUEST[$prefix.'y'] = 1;

		switch($format) {
			case 'match': case 'before': case 'after': case 'last_unit':
				$_REQUEST[$prefix.'q'.'value'] = $this->get_request_array($value);
				break;
			case 'range':
				$_REQUEST[$prefix.'q_t'.'value'] = $this->get_request_array($value['to']);
				$_REQUEST[$prefix.'q_f'.'value'] = $this->get_request_array($value['from']);
				break;
		}
	}
	
	/**
	* Takes a date in the SQL datetime format and turns it into the format
	* that Mysource uses to POST dates => array('y','m','d','h','i','s')
	*
	* @param   string $value  A datetime value
	* @access  public
	* @returns void
	*/
	function get_request_array($value) {
		$field = new datetime_field($prefix,$value,$this->parameters);
		$field->validate_value();
		$request_array = array( 'y'=>(in_array('y',$this->parameters['show'])?$field->get_unit('y'):'----'),
		'm'=>(in_array('m',$this->parameters['show'])?$field->get_unit('m'):'--'),
		'd'=>(in_array('d',$this->parameters['show'])?$field->get_unit('d'):'--'),
		'h'=>(in_array('h',$this->parameters['show'])?$field->get_unit('h'):'--'),
		'i'=>(in_array('i',$this->parameters['show'])?$field->get_unit('i'):'--'),
		's'=>(in_array('s',$this->parameters['show'])?$field->get_unit('s'):'--')
		);
		return $request_array;
	}
}
?>