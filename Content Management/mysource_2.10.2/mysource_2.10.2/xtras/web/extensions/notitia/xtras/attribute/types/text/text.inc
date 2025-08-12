<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/text/text.inc,v $
## $Revision: 1.36 $
## $Author: achadszinow $
## $Date: 2004/01/23 02:56:19 $
#######################################################################

#---------------------------------------------------------------------#
global $SQUIZLIB_PATH;
include_once("$SQUIZLIB_PATH/wysiwyg/wysiwyg.inc");

 ####################
# For text fields...
class Web_Extension_Notitia_Attribute_Type_Text extends Web_Extension_Notitia_Attribute_Type {
	
	 ################################################################
	# This is a static thingy that stores special information about
	# a particular type.
	var $settings = array(
			'preferred_alignment' => 'left',
			'generates_varieties' => false,
			'variable'            => true,
			'variation_types'   => array(
				'replace' => 'Replace',
				'prepend' => 'Prepend',
				'append' => 'Append',
				'append_with_space' => 'Append With Space',
				'append_with_dash' => 'Append With Dash',
				'append_with_colon' => 'Append With Colon',
				'append_with_comma' => 'Append With Comma',
				'append_with_slash' => 'Append With Slash',
				'append_in_brackets' => 'Append In Brackets',
				'append_with_newline' => 'Append With New Line',
				'append_with_newline2' => 'Append With Double New Line',
			),
			'value_formats' => array(
				'raw' => 'Raw',
				'nl2br' => 'With HTML Linebreaks',
				'summary' => 'Summary',
				'fl' => 'First Letter'
			),
			'search_formats' => array (
				'match'   => 'Match',
				'partial' => 'Partial Match',
				'complex' => 'Loose Match',
				'first_letter' => 'First Letter Match',
				'and_joiner' => 'And Joiner'
			),
			'edit_formats' => array (
				'raw' => 'Standard',
				'wysiwyg_def' => 'WYSIWYG Defined'
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
			'sub_type_name' => 'text',
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
				return $new_value . $original;
			case 'append':
				return $original . $new_value;
			case 'append_with_space':
				return "$original $new_value";
			case 'append_with_dash':
				return (($original)?"$original - $new_value":$new_value);
			case 'append_with_colon':
				return (($original)?"$original: $new_value":$new_value);
			case 'append_with_comma':
				return (($original)?"$original, $new_value":$new_value);
			case 'append_with_slash':
				return (($original)?"$original / $new_value":$new_value);
			case 'append_in_brackets':
				return (($original)?"$original ($new_value)":$new_value);
			case 'append_with_newline':
				return (($original)?"$original\n$new_value":$new_value);
			case 'append_with_newline2':
				return (($original)?"$original\n\n$new_value":$new_value);
			case 'replace':
				return $new_value;
			default:
				return $original;
				break;
		}
	}

	 ################################################
	# Returns the value formatted in a nice way for printing
	function formatted_value(&$value,$format ='nl2br') {
		if(!$format) $format = 'nl2br';
		switch($format) {
			case 'nl2br':
				return nl2br($value);
			case 'summary':
				# if we are limiting this summary to the first paragraph, set value to be just the first paragraph
				if ($this->parameters['summary_limit'] == 'paragraph') list($value) = explode('<br />',nl2br($value));
				$append_dot_dot_dot = false;

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
										$append_dot_dot_dot = true;
										break;
									}
								}
							}
						}
						break;
					case 'paragraphs' :
						$paras = explode('<br />',nl2br($value));
						$retval = '';
						if (count($paras)) {
							$count = 0;
							foreach ($paras as $para) {
								if (!trim(str_replace('<br />', '', $para))) continue;
								$retval .= $para;
								$count++;
								if ($count >= $this->parameters['summary_items_to_print']) {
									$append_dot_dot_dot = true;
									break;
								} else {
									$retval .= '<br /><br />';
								}
							}
						}
						break;
					case 'words' : default:
						if ($this->parameters['summary_items_to_print'] <= 0) $this->parameters['summary_items_to_print'] = 150;
						$words = explode(' ', $value);
						$retval = '';
						if (count($words)) {
							$count = 0;
							foreach ($words as $word) {
								$retval .= $word;
								$count++;
								if ($count >= $this->parameters['summary_items_to_print']) {
									$append_dot_dot_dot = true;
									break;
								} else {
									$retval .= ' ';
								}
							}
						}
						break;
				}
				return trim($retval,' .').(($append_dot_dot_dot)?'...':'');
			case 'fl':
				return substr(trim(strip_tags($value)), 0,1);
			case 'raw': default:
				return $value;
		}
	}

	#############################################
	# Prints the interface for filling in a value
	function print_field($prefix,&$value, $format) {
		$this->print_concise_field($prefix,$value,$format);
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
		# Check for rules so we need the rules array
		$this->validate_value_rules($value, $rule_broken_msgs);
		# Test for normal settings
		if($h = &$this->get_value('height') <= 1) {
			$value = ereg_replace("[\r\n]+",' ',$value);
		}
		if($ml = &$this->get_value('maxlength')) {
			if(strlen($value) > $ml) $value = substr($value,0,$ml);
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
			case 'and_joiner':
				# Split on white space characters
				$and_joiner_array = preg_split('/\s/',$query);
				$query = '+"'.implode('" +"',$and_joiner_array).'"';
				# Continue into case for complex to call complex search function
			case 'complex':
				$cmp = complex_search_sql_conditions($value_string,$query,(($this->get_value('apostrophes_searchable'))?true:false));
				break;
			case 'first_letter':
				$cmp = 'LEFT('.$value_string.',1) = "'.substr($query,0,1).'"';
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
	* Returns an array of categories, decorated with tree branches.
	*
	* @param int $categoryid The root category to start at (defaults to system root)
	* @param int $max_depth The maximum recursive depth the tree should go to.
	* @returns array
	*/
	function get_category_list_with_sticks($categoryid=0,$max_depth=100) {
		$tree = &$this->get_category_tree();
		return $tree->get_category_list_with_sticks($categoryid,$max_depth);
	}

	/**
	* Returns the value for autocomplete
	*
	* @returns int
	*/
	function get_autocomplete() {
		return $this->parameters['autocomplete'];
	}

	/**
	* Returns the default edit formats ie none
	*
	* @returns array
	*/
	function get_edit_formats() {
		return $this->settings['edit_formats'];
	}

	/**
	* Prints approval status approved value
	*
	* @access public
	* @return void
	*/
	function print_approval_status_approved_value_form() {
		$attribute = &$this->get_attribute($this->parameters['autoc_approval_attributeid']);
		if($attribute->id) {
			$attribute->print_field('approval_status_approved', $this->parameters['autoc_approved_value']);
		} else {
			echo 'Select an approval attribute to use record approval';
		}
		echo '<br /><span class=smallprint>This allows you to choose if a record will appear in the autocomplete list depending on the value of the approval attribute</span>';
	}

	/**
	* Processes approval status approved value
	*
	* @access public
	* @return string
	*/
	function process_approval_status_approved_value_form() {
		$approved_value = &$this->get_value('autoc_approved_value');
		$attribute = &$this->get_attribute($this->parameters['autoc_approval_attributeid']);
		if($attribute->id) {
			return $attribute->process_field('approval_status_approved', $approved_value);
		}
	}

	/**
	* Returns a list of attributes same category this attribute exists within
	*
	* @access public
	* @return array
	*/
	function get_local_attribute_list() {
		$tree = &$this->get_category_tree();
		return $tree->get_context_attributes($this->attribute->categoryid);
	}

	/**
	* Print the raw format of this attribute
	*
	* @param string $prefix The prefix as supplied by Attribute
	* @param string &$value The value that it may have already in it
	* @access  public
	* @returns void
	*/
	function print_raw_format($prefix, &$value) {
		if($this->get_value("height") > 1) {
			echo text_area($prefix."value",$value,$this->get_value("width"),$this->get_value("height"),$this->get_value("maxlength"));
		} else {
			$autocomplete_length = (int) $this->get_autocomplete();
			if($autocomplete_length) {
				$db = &$this->get_db();
				$table_name = $this->get_value_table_name();
				$autos = $db->single_column("SELECT DISTINCT SUBSTRING(value,1,$autocomplete_length) FROM $table_name WHERE attributeid={$this->attribute->id} AND value != '' ORDER BY value");
			}
			echo text_box($prefix."value",$value,$this->get_value("width"),$this->get_value("maxlength"),"class=data",$autos);
		}
	}

	/**
	* Prints the WYSIWYG
	*
	* @access  public
	* @returns void
	*/
	function print_wysiwyg_format() {
		$wysiwyg = &$this->get_wysiwyg('web_extention_notitia_attribute_types_text_wysiwyg');
		$wysiwyg->paint_edit($this->parameters['wysiwyg_def_format'], true);
	}

	/**
	* Processes any changes to the WYSIWYG
	*
	* @access  public
	* @returns string
	*/
	function process_wysiwyg_format() {
		$wysiwyg = &$this->get_wysiwyg('web_extention_notitia_attribute_types_text_wysiwyg');
		if ($this->parameters['wysiwyg_def_format'] != $wysiwyg->get_html()) {
			$this->parameters['wysiwyg_def_format'] = $wysiwyg->get_html();
			return 'WYSIWYG defined has been modified<br />';
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
		true,true,true);
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
	* Returns a keyword replace array and javascript variables by reference of what should be in the wysiwyg
	*
	* @param string $type The type meaning whether they have got one limiter or two
	* @param string $prefix As provided by attribute
	* @param string $value Any current value
	* @param array	&$replace_array The keyword replacement array to add some stuff to
	* @param string &$javascript Any java script it will need to use
	* @access  public
	* @returns void
	*/
	function get_wysiwyg_def($type, $prefix, $value, &$replace_array, &$javascript) {
		if($this->get_value("height") > 1) {
			$replace_array['limiter_1'] = 'Height of attribute is greater than one therefore it is not possible to autocomplete';
			$replace_array['limiter_2'] = 'Height of attribute is greater than one therefore it is not possible to autocomplete';
			$replace_array['entry_field'] = text_area($prefix."value",$value,$this->get_value("width"),$this->get_value("height"),$this->get_value("maxlength"));
		} else {
			$tree = &$this->get_category_tree();
			$autocomplete_length = (int) $this->get_autocomplete();
			$autoc_root_categoryid = $this->parameters['autoc_root_categoryid'];
			$category_array = array($autoc_root_categoryid => $tree->name($autoc_root_categoryid));
			if ($type == 'single') {
				$category_array = $category_array + $this->get_category_list_with_sticks($autoc_root_categoryid);
			} else {
				$category_array = $category_array + $this->get_category_list_with_sticks($autoc_root_categoryid, 1);
				$all_sub_cats = $tree->all_descendantids($autoc_root_categoryid);
				$parent_category_array = array();
				foreach($all_sub_cats as $categoryid) {
					$category = &$this->get_category($categoryid);
					if (!isset($parent_category_array[$category->parentid])) {
						$parent_category_array[$category->parentid] = Array();
					}
					$parent_category_array[$category->parentid][] = array('name' => $category->name, 'cid' => $categoryid);
				}
			}
			$approved_attributeid = $this->parameters['autoc_approval_attributeid'];
			$approved_value = $this->parameters['autoc_approved_value'];
			$db = &$this->get_db();
			$table_name = $this->get_value_table_name();
			if ($approved_attributeid && $approved_value) {
				$values_and_category = $db->associative_column("SELECT SUBSTRING(a.value,1,$autocomplete_length), b.categoryid FROM $table_name a LEFT JOIN xtra_web_extension_notitia_record_to_category b ON (a.recordid = b.recordid) LEFT JOIN $table_name c ON (a.recordid = c.recordid) WHERE a.attributeid={$this->attribute->id} AND a.value != '' AND c.attributeid = '".addslashes($approved_attributeid)."' AND c.value = '".addslashes($approved_value)."' ORDER BY a.value");
			} else {
				$values_and_category = $db->associative_column("SELECT SUBSTRING(a.value,1,$autocomplete_length), b.categoryid FROM $table_name a LEFT JOIN xtra_web_extension_notitia_record_to_category b ON (a.recordid = b.recordid) WHERE a.attributeid={$this->attribute->id} AND a.value != '' ORDER BY a.value");
			}
			$autos = array_keys($values_and_category);
			if (!empty($autos)) {
				global $SQUIZLIB_PATH;
				include_once($SQUIZLIB_PATH.'/var_serialise/var_serialise.inc');

				#textbox input with autocomplete
				$replace_array['entry_field'] = text_box($prefix."value",$value,$this->get_value("width"),$this->get_value("maxlength"),"class=data",$autos);
				#dropdown of categories on change change auto complete options
				$replace_array['limiter_1'] = combo_box($prefix.'sub_category_delimiter', $category_array, '', 'onChange="javascript: '.$prefix.'_auto_complete_update(elementValue(this));"' );
				if ($type == 'single') {
					$replace_array['limiter_1'] = combo_box($prefix.'sub_category_delimiter', $category_array, '', 'onChange="javascript: '.$prefix.'_auto_complete_update(elementValue(this));"');
				} else {
					$replace_array['limiter_1'] = combo_box($prefix.'sub_category_delimiter', $category_array, '', 'onChange="javascript: '.$prefix.'populate(this.form, elementValue(this));"');
					$replace_array['limiter_2'] = combo_box($prefix.'sub_category_delimiter2', array('This Category'), '', 'onChange="javascript: '.$prefix.'_auto_complete_update(elementValue(this));"');
				}
				ob_start();
				?>
				<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('var_serialise', 'var_serialise.js');?>"></script>
				<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('js', 'general.js');?>"></script>

				<script language="JavaScript" type="text/javascript">
					window.<?=$prefix?>_full_auto_options = var_unserialise('<?=var_serialise($values_and_category, true);?>');
					function <?=$prefix?>_auto_complete_update(categoryid) {
						var auto_options = new Array();
						for(var value in window.<?=$prefix?>_full_auto_options) {
							if (array_search(window.<?=$prefix?>_full_auto_options[value], categoryid) != null) {
								auto_options.push(value);
							}
						}
						html_form_text_box_change_auto_complete('<?=$prefix."value"?>', auto_options);
					}
					<?=$prefix?>_auto_complete_update(<?=$autoc_root_categoryid?>);
				</script>
				<SCRIPT language="JavaScript">
					window.<?=$prefix?>parent_category = var_unserialise('<?=var_serialise($parent_category_array, true);?>');
					function <?=$prefix?>populate(form, categoryid) {

						for(var i = form.elements['<?=$prefix?>sub_category_delimiter2'].options.length - 1; i >= 0; i--) {
							form.elements['<?=$prefix?>sub_category_delimiter2'].options[i] = null;
						}
						form.elements['<?=$prefix?>sub_category_delimiter2'].options['0'] = new Option('This Category', categoryid);
						<?=$prefix?>_auto_complete_update(categoryid);
						var i = 1;
						for(var value in window.<?=$prefix?>parent_category[categoryid]) {
							var name = window.<?=$prefix?>parent_category[categoryid][value]['name'];
							var cid = window.<?=$prefix?>parent_category[categoryid][value]['cid'];
							form.elements['<?=$prefix?>sub_category_delimiter2'].options[i] = new Option(name, cid);
							i++;
						}
					}
				</SCRIPT>

				<?
				$javascript = ob_get_contents();
				ob_end_clean();
			} else {
				$replace_array['entry_field'] = text_box($prefix."value",$value,$this->get_value("width"),$this->get_value("maxlength"),"class=data");
			}
		}
	}

	/**
	* Prints a table of wysiwyg keywords
	*
	* @access  public
	* @returns void
	*/
	function print_wysiwyg_keywords() {
		?>
		<table bgcolor="#AAAAAA">
			<tr bgcolor="#CCCCCC">
				<td colspan="2" align="center"><b>Keywords</b></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td nowrap>%entry_field%</td><td>The text box that lets you enter text in</td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td nowrap>%limiter_1%</td><td>If you have just this keyword in you WYSIWYG you will have a dropdown with all categories in the tree. By changing the category it will change what values the autocomplete feature has available to it.</td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td nowrap>%limiter_2%</td><td>By having this keyword and %limiter_1% limiter one will have the root category and only it's sub categories in it. Limiter to will dynamically be filled with the subcategories of the category selected in %limiter_1%. This makes using large category trees less of a problem</td>
			</tr>
		</table>
		<?
	}

	 #############################################
	# Prints the interface for filling in a value
	function print_concise_field($prefix,&$value,$format) {
		switch($format) {
			case 'raw': default:
				$this->print_raw_format($prefix, $value);
			break;
			case 'wysiwyg_def':
				$replace_array = array();
				$wysiwyg = $this->parameters['wysiwyg_def_format'];
				$keywords = retrieve_keywords_replacements($wysiwyg);
				if (in_array('limiter_1', $keywords) && in_array('limiter_2', $keywords)) {
					# two category drop down limiters
					$this->get_wysiwyg_def('double', $prefix, $value, $replace_array, $javascript);
				} elseif (in_array('limiter_1', $keywords)) {
					# one category drop down limitier
					$this->get_wysiwyg_def('single', $prefix, $value, $replace_array, $javascript);
				} else {
					# normal
					ob_start();
					$this->print_raw_format();
					$replace_array['entry_field'] = ob_get_contents();
					ob_end_clean();
				}
				$html = replace_keywords($wysiwyg, $replace_array);
				echo "$html $javascript";
				break;
		}
	}

	 ###########################
	# Prints out a search field
	function print_search_field($prefix,&$default,$format,$not_null,$search_view_format) {
		if($format == 'first_letter') { 
			echo combo_box($prefix."qvalue",array(''=>'','a'=>'A','b'=>'B','c'=>'C','d'=>'D','e'=>'E','f'=>'F','g'=>'G','h'=>'H','i'=>'I','j'=>'J','k'=>'K','l'=>'L','m'=>'M','n'=>'N','o'=>'O','p'=>'P','q'=>'Q','r'=>'R','s'=>'S','t'=>'T','u'=>'U','v'=>'V','w'=>'W','x'=>'X','y'=>'Y','z'=>'Z'));
			return;
		}
		if($format != 'match') {
			$tmp = $this->parameters['height'];
			$this->parameters['height'] = 1;
		}
		echo $this->print_concise_field($prefix.'q',$default, $search_view_format);
		if($format != 'match') {
			$this->parameters['height'] = $tmp;
		}
	}

	/**
	* Sets in the request vars a static search
	*
	* @param   string $prefix Prefix
	* @param   string $value  A value
	* @access  public
	* @returns void
	*/
	function set_search_request($prefix,$value) {
		$_REQUEST[$prefix.'qvalue'] = $value;
	}

	/**
	* Returns a validated user ref attributeid
	*
	* @access  public
	* @returns int
	*/
	function get_syncing_user_ref_attributeid() {
		$syncing_user_ref_attributeid = $this->get_value('syncing_user_ref_attributeid');
		$syncing_user_ref_attribute = &$this->get_attribute($syncing_user_ref_attributeid);
		if ($syncing_user_ref_attribute->id && $syncing_user_ref_attribute->type == 'user_reference') {
			return $syncing_user_ref_attribute->id;
		} else {
			return 0;
		}
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
		$user_ref_attribute = &$this->get_attribute($this->get_syncing_user_ref_attributeid());
		if ($user_ref_attribute->id) {
			$record = &$this->get_record($recordid);
			$userid = $record->get_attribute_formatted_value($user_ref_attribute->id, 'id');
			if ($userid > 0) {
				$users_system = &get_users_system();
				$user = $users_system->get_user($userid);
				if ($user->id) {
					$syncable_user_ref_fields = $this->get_value('syncable_user_ref_fields');
					foreach($syncable_user_ref_fields as $field_code) {
						switch ($field_code) {
							case 'fn':
								# Set the first name
								$user->set_firstname($value);
								break;
							case 'sn':
								# Set the surname
								$user->set_surname($value);
								break;
							case 'm':
								# Set the mobile no
								$user->set_mobile_no($value);
								break;
							case 'c':
								# Set the comments
								$user->set_comments($value);
								break;
							case 'e':
								# Set the Email addy
								$user->set_email($value);
								break;
							default:
								break;
						}
					}
				}
			}
		}
	}
}
?>