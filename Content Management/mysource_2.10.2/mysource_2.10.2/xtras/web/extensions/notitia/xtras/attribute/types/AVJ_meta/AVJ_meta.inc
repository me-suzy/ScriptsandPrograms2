<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/AVJ_meta/AVJ_meta.inc,v $
## $Revision: 1.7 $
## $Author: nduggal $
## $Date: 2004/01/16 04:24:09 $
#######################################################################

#---------------------------------------------------------------------#

 #######################################################
# Forgeign keys, linking to other records in the system
class Web_Extension_Notitia_Attribute_Type_AVJ_Meta extends Web_Extension_Notitia_Attribute_Type {
	
	 ################################################################
	# This is a static thingy that stores special information about
	# a particular type.
	var $settings = array(
			'preferred_alignment' => 'left',
			'generates_varieties' => false,
			'variable'            => false,
			'variation_types'   => array(
				#'replace' => 'Replace',
			),
			'edit_formats' => array (
				#'raw' => 'Uneditable'
			),
			'sort_formats' => array (
				'raw' => 'Unsorted'
			),
			'rule_types' => array (
			),
			'requires_context_values' => true,
			'sub_type_name' => 'meta',
			'search_logic' => 'OR',
			'table_settings' => array(
				'sub_table_name' => ''
			)
		);

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


	 ################################################
	# Returns the value formatted in a nice way for printing
	function formatted_value(&$value, $format) {
		$formats = &$this->get_value('formats');
		$format = substr($format,1);
		if(!$format || !isset($formats[$format])) {
			reset($formats);
			$format = key($formats);
		}

		$layouts  = &$this->get_value('format_layouts');
		$layout   = $layouts[$format];
		$keywords = retrieve_keywords_replacements($layout);
		$keyword_formats = array_flip($this->get_value('format_keywords'));
		$replacements = array();
		foreach($keywords as $keyword) {
			$value = $this->context_value_to_real_formatted_value($value,$keyword_formats[$keyword]);
			if ($value) {
				$padding_start = $this->get_value('padding_start');
				$padding_end   = $this->get_value('padding_end');
				$replacements[$keyword] = $padding_start.$value.$padding_end;
			} else {
				$replacements[$keyword] = '';
			}
		}
		return replace_keywords(nl2br($layout),$replacements);
	}


	 #################################################
	# Converts a special context-value to a real value
	# given an attribute
	function context_value_to_real_formatted_value(&$entity,$format) {
		$attributeid = (int) $format;
		$subformat   = substr(strstr($format,'_'),1);
		$attribute   = &$this->get_attribute($attributeid);
		if($attribute->id) {
			$type = substr($entity,0,1);
			$ids  = substr($entity,1);
			switch($type) {
				case 'r':
					$record = &$this->get_record($ids);
					if($record->id) {
						return $record->get_attribute_formatted_value($attributeid,$subformat);
					} return;
				case 'c':
					return $attribute->get_formatted_default_value($ids,$subformat);
				case 'm':
					$categoryids = explode('|',$ids);
					return $attribute->get_formatted_default_value($categoryids,$subformat);
				default:
					return;
			}
		}
	}


	 #############################################
	# Prints the interface for filling in a value
	function print_concise_field($prefix,&$value,$options) {
		$printable_value = $this->formatted_value($value, $options);
		?>
		<table>
			<tr><td><b>[Meta-Attribute - cannot be edited]</b></td></tr>
			<?
			if (!empty($value)) {
				?>
				<tr><td><?=$printable_value?></td></tr>
				<?
			}
			?>
		</table>
		<?
	}


	#############################################
	# Prints the interface for filling in a value
	function print_field($prefix,&$value,$options) {
		$this->print_concise_field($prefix,$value,$options);
		if($note = &$this->get_value("note")) {
			echo '<span class="smallprint"><br>'.$note.'</span>';
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

	 ################################################
	# Returns an array of search fields and attributes
	function get_search_fields() {
		$search_fields = array();
		foreach($this->get_value('search_allocations') as $search_field => $search_with) {
			if($search_with) {
				$search_fields[$search_with][] = $search_field;
			}
		}
		return $search_fields;
	}


	 ########################################################################
	# Converts a format string into an array of attributeids and subformats
	function get_subformats() {
		$subformats = array();
		foreach(explode('|',$format) as $subformat) {
			$attributeid  = (int) $subformat;
			$subformats[$attributeid] = substr(strstr($subformat,'_'),1);
		}
		return $subformats;
	}


	 ###################################################
	# Returns a query object which can be given back to
	# print_search_field() or get_search_sql_clause()
	function &process_search_field($prefix,$format,$not_null) {
		$search_fields = $this->get_search_fields();
		$subformats    = $this->get_subformats($format);
		$r = array();
		foreach($search_fields as $field_attributeid => $search_attributeids) {
			foreach($search_attributeids as $search_attributeid) {
				$attribute = &$this->get_attribute($search_attributeid);
				if($attribute->id) {
					$q = $attribute->process_search_field(
						$prefix.'_s'.$field_attributeid,
						$subformats[$field_attributeid],
						$not_null);
					if($q) $r[$search_attributeid] = $q;
					else unset($r[$search_attributeid]);
				}
			}
		}
		return $r;
	}

	 ##############################################
	# Returns an SQL WHERE subclause for searching
	function get_search_sql_clause($query,$format,$value_string='v.value') {
		$subformats = array();
		foreach(explode('|',$format) as $subformat) {
			$attributeid  = (int) $subformat;
			$subformats[$attributeid] = substr(strstr($subformat,'_'),1);
		}
		foreach($this->get_value('search_allocations') as $search_field => $search_with) {
			if($search_with) {
				$subformats[$search_field] = $subformats[$search_with];
			}
		}
		# Now we have to do a little breakaway cheat here. Its a bit naughty I know
		# but I can't think of a better way.
		foreach($query as $attributeid => $subquery) {
			$attribute = &$this->get_attribute($attributeid);
			if($attribute->id) {
				if($attribute->type == 'meta') {
					foreach($attribute->get_search_sql_clause($subquery,$subformats[$attributeid],$value_string) as $subattributeid => $subclause) {
						$clauses[$subattributeid] = $subclause;
					}
				} else {
					$clauses[$attribute->id] = $attribute->get_search_sql_clause($subquery,$subformats[$attributeid],$value_string);
				}
			}
		}
		return $clauses;
		#return '0=1) OR ('.implode(') OR (',$clauses).') OR (0=1';
	}


	 ##############################################
	# Returns an SQL expression that evalutates a clause
	function get_search_sql_score($query,$format,$value_string='v.value') {
		$subformats = array();
		foreach(explode('|',$format) as $subformat) {
			$attributeid  = (int) $subformat;
			$subformats[$attributeid] = substr(strstr($subformat,'_'),1);
		}
		foreach($this->get_value('search_allocations') as $search_field => $search_with) {
			if($search_with) {
				$subformats[$search_field] = $subformats[$search_with];
			}
		}
		# Now we have to do a little breakaway cheat here. Its a bit naughty I know
		# but I can't think of a better way.
		$exps = array();
		foreach($query as $attributeid => $subquery) {
			$attribute = &$this->get_attribute($attributeid);
			if($attribute->id) {
				if($attribute->type == 'meta') {
					foreach($attribute->get_search_sql_score($subquery,$subformats[$attributeid],$value_string) as $subattributeid => $subclause) {
						$exps[$subattributeid] = $subclause;
					}
				} else {
					$score = $attribute->get_search_sql_score($subquery,$subformats[$attributeid],$value_string);
					if(strlen($score)) {
						$exps[$attribute->id] = $score;
					}
				}
			}
		}
		return $exps;
	}

	/**
	 ** Returns one of the hard-coded settings (although they're)
	 ** not all hardcorded in this case or we wouldn't be overriding
	 ** this function
	 ** @param setting A string key denoting the type of setting wanted.
	 ** @returns mixed
	 **/
	function &get_setting($setting) {
		switch($setting) {
			case 'value_formats':
				$r = array();
				foreach($this->get_value('formats') as $id => $format) {
					$r["f$id"] = $format;
				}
				return $r;
			case 'search_formats':
				# TO DO
				$search_fields = array();
				foreach($this->get_value('search_allocations') as $search_field => $search_with) {
					if($search_with && !in_array($search_with,$search_fields)) {
						$search_fields[] = $search_with;
					}
				}
				return $this->combined_search_formats($search_fields);
			default:
				return $this->settings[$setting];
		}
	}


	/**
	 ** Given a list of attributes, returns an array of all the combinations
	 ** of possible search formats.
	 ** @param attributeids A list of attribute ids.
	 ** @returns array
	 **/
	 function combined_search_formats($attributeids) {
		 $r = array();
		$formats = array();
		$attributeid = array_pop($attributeids);
		$attribute = &$this->get_attribute($attributeid);
		if(count($attributeids)) {
			if($attribute->id) {
				$subformats = $attribute->get_type_setting('search_formats');
				foreach($this->parameters['search_formats'][$attributeid] as $format) {
					$label = $subformats[$format];
					foreach($this->combined_search_formats($attributeids) as $subformat => $sublabel) {
						$r[$attribute->id.'_'.$format.'|'.$subformat] = "$attribute->name: $label / $sublabel";
					}
				}
			}
		} else {
			if($attribute->id) {
				$subformats = $attribute->get_type_setting('search_formats');
				foreach($this->parameters['search_formats'][$attributeid] as $format) {
					$label = $subformats[$format];
					$r[$attribute->id.'_'.$format] = "$attribute->name: $label";
				}
			}
		}
		return $r;
	}


	/**
	 ** Prints an interface for defining the keywords used in the printable formats for this
	 ** attribute - drawing information from the other attributes in the context.
	 ** @returns void
	 ** @see process_format_definition_field
	 **/
	function print_format_keyword_definition_field() {
		$formats = &$this->get_value('format_keywords');
		$n = &$this->get_notitia_system();
		$n->print_attribute_list('format_keywords', $formats);
	}


	/**
	 ** Processes an interface for defining the keywords used in the printable formats for this
	 ** attribute - drawing information from the other attributes in the context.
	 ** referred category.
	 ** @returns void
	 ** @see print_format_definition_field
	 **/
	function process_format_keyword_definition_field() {
		$formats = &$this->get_value('format_keywords');
		$n = &$this->get_notitia_system();
		$new_format_keyword_code = &$this->get_value('new_format_keyword_code');
		$formats = $n->process_attribute_list('format_keywords', $formats, $new_format_keyword_code);
		$this->set_value('new_format_keyword_code','');
	}


	/**
	 ** Prints an interface for defining the printable formats for this
	 ** attribute - drawing information from the attributes in the
	 ** referred category.
	 ** @returns void
	 ** @see process_format_definition_field
	 **/
	function print_format_definition_field() {
		$formats = $this->get_value('formats');
		$format_layouts = &$this->get_value('format_layouts');
		end($formats);
		$id = key($formats);
		$formats[(((int)$id)+1)] = '';
		foreach($formats as $id => $format) {
			echo '<b>Name:</b> '.text_box("formats[$id]",$format,30,255).'<br />';
			echo text_area("format_layouts[$id]",$format_layouts[$id],40,4).'<hr noshade size="0">';
		}
		echo '<span class="smallprint">Enter format names and layouts. Layouts are defined by using the keyword replacements. E.g. for a Full Name, using the keywords firstname and surname, you would put in the box: "%firstname% %surname%"</span>';
	}


	/**
	 ** Processes an interface for defining the printable formats for this
	 ** attribute - drawing information from the attributes in the
	 ** referred category.
	 ** @returns void
	 ** @see print_format_definition_field
	 **/
	function process_format_definition_field() {
		$formats        = &$this->get_value('formats');
		$format_layouts = &$this->get_value('format_layouts');
		$formats = array();
		$format_layouts = array();
		$new_formats = gpc_stripslashes($_POST['formats']);
		$new_format_layouts = gpc_stripslashes($_POST['format_layouts']);
		foreach($new_formats as $id => $format) {
			if($format) {
				$formats[$id] = $format;
				$format_layouts[$id] = $new_format_layouts[$id];
			}
		}
	}


	/**
	 ** Prints a set of options for definine which of the search formats
	 ** in the searchable attributes can be used in this metaattribute.
	 ** @returns void
	 ** @see process_search_format_options
	 **/
	function print_search_format_options() {
		$search_formats = &$this->get_value('search_formats');
		?>
		<table cellpadding="0" cellspacing="0" border="0" bgcolor="#333333"><tr><td><table cellpadding="3" cellspacing="1" border="0">
			<tr bgcolor="#cccccc">
				<td valign="top" class="backend_field">Attribute</td>
				<td valign="top" class="backend_field">Formats</td>
			</tr>
		<?foreach(array_unique(array_values($this->get_value('search_allocations'))) as $attributeid) {?>
			<?if(!$attribute = &$this->get_attribute($attributeid)) continue;?>
			<tr bgcolor="#cccccc">
				<td valign="top" class="backend_field"><?=$attribute->name?></td>
				<td valign="top" bgcolor="#eeeeee">
					<?
					$formats = $attribute->get_type_setting('search_formats');
					foreach($formats as $format => $format_name) {
						?>
						<nobr>&nbsp;&nbsp;<input type="checkbox" name="search_formats[<?=$attributeid?>][]" value="<?=$format?>" <?=(in_array($format,$search_formats[$attributeid])?'checked':'')?>> <?=$format_name?>&nbsp;&nbsp;</nobr>
						<?
					}
					?>
				</td>
			</tr>
		<?}?>
		</table></td></tr></table>
		<?
	}


	/**
	 ** Processes a set of options for definine which of the search formats
	 ** in the searchable attributes can be used in this metaattribute.
	 ** @returns void
	 ** @see print_search_format_options
	 **/
	function process_search_format_options() {
		$this->parameters['search_formats'] = $_REQUEST['search_formats'];
	}



	/**
	 ** Returns an array of the other attributes in the context
	 ** @returns array
	 **/
	function get_alt_attribute_list() {
		$tree = &$this->get_category_tree();
		$results = $tree->get_context_attributes($this->attribute->categoryid,$this->attribute->productid);
		unset($results[$this->attribute->id]);
		return $results;
	}


	/**
	 ** Returns an array of attribute/format pairs for selection from the current context
	 ** @returns array
	 ** @see print_format_definition_field
	 **/
	function get_alt_attribute_list_with_formats() {
		$r  = array(); 
		foreach($this->get_alt_attribute_list() as $id => $name) {
			if($id == $this->id) continue;
			$attribute = &$this->get_attribute($id);
			foreach($attribute->get_type_setting('value_formats') as $format => $format_name) {
				$r[$id."_$format"] = "$name ($format_name)";
			}
		}
		return $r;
	}

	 ###########################
	# Prints out a search field
	function print_search_field($prefix,&$default,$format,$not_null) {
		$print_labels = &$this->get_value('search_labels');
		$search_fields = array();
		foreach($this->get_value('search_allocations') as $search_field => $search_with) {
			if($search_with) {
				$search_fields[$search_with][] = $search_field;
			}
		}
		$subformats = array();
		foreach(explode('|',$format) as $subformat) {
			$attributeid  = (int) $subformat;
			$subformats[$attributeid] = substr(strstr($subformat,'_'),1);
		}
		
		$count = count($search_fields);
		$i = 0;
		foreach($search_fields as $field_attributeid => $search_attributeids) {
			$i++;
			if($print_labels) {
				$label = '';
				foreach($search_attributeids as $search_attributeid) {
					$attribute = &$this->get_attribute($search_attributeid);
					$label .= "$attribute->name/";
				}
				$label[strlen($label)-1] = ':';
				echo "<b>$label</b><br />";
			}
			$attribute = &$this->get_attribute($field_attributeid);
			if($attribute->id) {
				$subdefault = ((isset($default[$field_attributeid]))?$default[$field_attributeid]:'');
				$attribute->print_search_field(
					$prefix.'_s'.$field_attributeid,
					$subdefault,
					$subformats[$field_attributeid],
					$not_null);
				if ($i < $count) echo '<br />';
			}
		}
	}

	/**
	* Prints this strange concise field
	*
	* @param   string $prefix Prefix
	* @param   string $value  A value
	* @param   string $format A search format
	* @access  public
	* @returns void
	*/
	function set_search_request($prefix,$value,$format) {
		$search_fields = array();
		foreach($this->get_value('search_allocations') as $search_field => $search_with) {
			if($search_with) {
				$search_fields[$search_with][] = $search_field;
			}
		}
		$subformats = array();
		foreach(explode('|',$format) as $subformat) {
			$attributeid  = (int) $subformat;
			$subformats[$attributeid] = substr(strstr($subformat,'_'),1);
		}
		
		$i = 0;
		foreach($search_fields as $field_attributeid => $search_attributeids) {
			$i++;
			$attribute = &$this->get_attribute($field_attributeid);
			if($attribute->id) {
				$subvalue = ((isset($value[$field_attributeid]))?$value[$field_attributeid]:'');
				$attribute->set_search_request(
					$prefix.'_s'.$field_attributeid,
					$subvalue,
					$subformats[$field_attributeid],
					$not_null);
			}
		}
	}
}
?>