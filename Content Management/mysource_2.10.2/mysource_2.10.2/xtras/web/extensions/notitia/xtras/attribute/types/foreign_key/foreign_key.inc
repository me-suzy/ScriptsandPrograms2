<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/foreign_key/foreign_key.inc,v $
## $Revision: 1.72 $
## $Author: dofford $
## $Date: 2004/04/05 11:36:17 $
#######################################################################

#---------------------------------------------------------------------#

global $SQUIZLIB_PATH;
include_once($SQUIZLIB_PATH.'/wysiwyg/wysiwyg.inc');
include_once($SQUIZLIB_PATH.'/var_serialise/var_serialise.inc');

 #######################################################
# Forgeign keys, linking to other records in the system
class Web_Extension_Notitia_Attribute_Type_Foreign_Key extends Web_Extension_Notitia_Attribute_Type {
	
	 ################################################################
	# This is a static thingy that stores special information about
	# a particular type.
	var $settings = array(
			'preferred_alignment' => 'left',
			'generates_varieties' => false,
			'variable'            => true,
			'variation_types'   => array(
				'replace' => 'Replace',
				'append'  => 'Combine',
			),
			'value_formats' => array (
				'_ids'   => 'Array of recordids',
				's_ids'  => 'String of recordids',
				's_cids' => 'String of Categoryids'
			),
			'edit_formats' => array (
				'raw' => 'Search field',
				'search_inherit' => 'Search field w/subformat select',
				'checkboxes' => 'Checkboxes',
				'select' => 'Select Box',
				'sel_add_chk_disp' => 'Select Box Add, Check Box Remove',
				'select_cat' => 'Select Box with Category Select'
			),
			'sort_formats' => array (
				'raw' => 'By Record ID',
				'raw<' => 'By Record ID Desc.'
			),
			'rule_types' => array (
				'must_link_x_woperator' => 'Must Link to X records w/operator'
			),
			'sub_type_name' => 'foreign_key',
			'table_settings' => array(
				'sub_table_name' => ''
			)

		);
	var $temp = array();

	 ##################################################
	# Takes an original values, and variation and a 
	# variation type, and returns the varied result
	function vary_value($type, &$new_value, &$original) {
		switch($type) {
			case 'replace':
				return $new_value;
			case 'append':
				return array_splice(array_merge($original,$new_value),0,$this->get_value('multiple'));
			default:
				return $original;
				break;
		}
	}

	 #######################################################
	# Returns the value formatted in a nice way for printing
	function formatted_value(&$value, $format = '') {
		if(!$format) $format = $this->get_value('default_format');
		$sort_formats = array_keys($this->get_value('foreignid_sort_formats'));
		$separating_char = $this->get_mul_val_separating_character();
		$f_recordids = $this->get_foreign_recordids($value,$sort_formats);
		if($format == '_ids' || $format == 's_ids') {
			if ($format == 's_ids') {
				$r = implode($separating_char, $f_recordids);
			} else {
				$r = $f_recordids;
			}
		} elseif ($format == 's_cids') {
			if ($this->not_reversed()) {
				$ref_categoryid = $this->get_value('ref_categoryid');
			} elseif ($this->reversed()) {
				$ref_attribute = &$this->get_attribute($this->get_value('ref_attributeid'));
				$ref_categoryid = $ref_attribute->get_value('ref_categoryid');
			}
			$categoryids = array();
			if ($ref_categoryid) {
				$tree = &$this->get_category_tree();
				$descendants = $tree->all_descendantids($ref_categoryid);
				$descendants[] = $ref_categoryid;
				foreach ($f_recordids as $recordid) {
					$record = &$this->get_record($recordid);
					if ($record->id) {
						foreach ($record->categoryids as $catid) {
							if (in_array($catid, $descendants)) {
								$categoryids[] = $catid;
							}
						}
					}
				}
			}
			$r = implode($separating_char, $categoryids);
		} else {
			$attributeid = (int) $format;
			$attribute   = &$this->get_attribute($attributeid);
			$r = '';
			if($attribute->id) {
				$subformat = substr(strstr($format,'_'),1);
				foreach($f_recordids as $recordid) {
					$record = &$this->get_record($recordid);
					if($record->id) {
						if($r) {
							$r .= $separating_char;
						}
						$r .= $record->get_attribute_formatted_value($attributeid,$subformat);
					}
				}
			}
		}
		return $r;
	}

	 ######################################################
	# Returns a list of the foreign recordids, optionally
	# sorted, given a record id.
	function get_foreign_recordids($value,$sorting=array()) {
		if($value) {
			$temp = $this->strip_outer_pipes($value);
			$r = explode('|', $temp);
		} else {
			$r = array();
		}

		if(count($sorting) > 0 && count($r) > 1) {
			if ($this->reversed()) {
				# The category this foreign key exists in
				$attribute = &$this->get_attribute($this->get_value('ref_attributeid'));
				$category = &$this->get_category($attribute->categoryid);
			} else {
				$category = &$this->get_category($this->get_value('ref_categoryid'));
			}
			if($category->id) {
				$queries = array();
				$r = $category->resort_recordids($r, $sorting);
			}
		}
		return $r;
	}

	 #############################################
	# Prints the interface for filling in a value
	function print_concise_field($prefix,&$value,$format) {
		$default_format = $this->get_value('default_format');
		$attributeid = (int) $default_format;
		$subformat   = substr(strstr($default_format,'_'),1);
		$no_records_text_msg = $this->parameters['no_records_text_msg']?$this->parameters['no_records_text_msg']:'There are no records to link to';

		if (!$format) {
			$format = $this->get_value('linking_interface');
		}

		if($this->reversed()) {
			echo '<b>[Reverse foreign key - cannot be edited]</b><br />';
			$recordids = $this->get_foreign_recordids($value);
			if (!empty($recordids)) {
				$printing_string = '<UL>';
				foreach($recordids as $recordid) {
					$record = &$this->get_record($recordid);
					if(!$record->id) continue;
					$fv = $record->get_attribute_formatted_value($attributeid, $subformat);
					$printing_string .= "<li>$fv</li>";
					$printed = true;
				}
				$printing_string .= '</ul>';
				if ($printed) {
					echo "$printing_string";
				} else {
					echo 'This reverse foreign has no foreign keys pointing to it<br />';
				}
			} else {
				echo 'This reverse foreign has no foreign keys pointing to it<br />';
			}
			return;
		}
		$pointing_to_categoryid = &$this->get_value('ref_categoryid');
		$pointing_to_category = &$this->get_category($pointing_to_categoryid);
		if (!$pointing_to_category) {
			echo "<b>Foreign key must have a category!</b><br />";
			return;
		}

		if($value) {
			$recordids = $this->get_foreign_recordids($value);
		} else {
			$recordids = array();
		}

		switch($format) {
			case 'sel_add_chk_disp':
				echo '<table border="0" cellspacing="1" cellpadding="1">';
				foreach($recordids as $recordid) {
					$record = &$this->get_record($recordid);
					if(!$record->id) continue;
					$fv = $record->get_attribute_formatted_value($attributeid,$subformat);
					echo '<tr><td valign="top"><input type="checkbox" name="'.$prefix.'_keep[]" value="'.$recordid.'" checked></td><td valign="top" colspan="2">'.$fv.($GLOBALS['IN_BACKEND']?'<a href="'.$record->get_backend_href().'">*</a>':'').'</td></tr>';
				}

				$recordids_with_values = $this->get_linkable_records_with_select_labels($attributeid, $subformat);

				echo '<tr><td valign="top" colspan="2">';
				if (empty($recordids_with_values)) {
					echo "<b>$no_records_text_msg</b>";
				} else {
					echo "<select name=\"{$prefix}_add[]\"";
					if ($this->get_value('multiple') != 1) echo ' multiple';
					echo ">";
					echo '<option value="0"></option>';
					foreach($recordids_with_values as $recordid => $fv) {
						echo '<option value="'.$recordid;
						echo '">'.$fv.'</option>';
					}
					echo '</select>';
				}
				echo '</td></tr></table>';
				break;
			case 'checkboxes':
				$recordids_with_values = $this->get_linkable_records_with_select_labels($attributeid, $subformat);

				echo '<table border="0" cellspacing="1" cellpadding="1">';
					echo '<tr><td valign="top">';
				if (empty($recordids_with_values)) {
					echo "<b>$no_records_text_msg</b>";
				} else {
					foreach($recordids_with_values as $recordid => $fv) {
						echo "<tr><td><input type=".(($this->get_value('multiple') != 1) ? 'checkbox' : 'radio')." name=\"{$prefix}_add[]\" value=\"$recordid\"";
						if (in_array($recordid, $recordids)) echo ' checked';
						echo ">$fv</td></tr>";
					}
				}
				echo '</td></tr></table>';
				break;

			case 'select' :
				$recordids_with_values = $this->get_linkable_records_with_select_labels($attributeid, $subformat);
				echo '<table border="0" cellspacing="1" cellpadding="1">';
				echo '<tr><td valign="top">';
				if (empty($recordids_with_values)) {
					echo "<b>$no_records_text_msg</b>";
				} else {
					echo "<select name=\"{$prefix}_add[]\"";
					if ($this->get_value('multiple') != 1) echo ' multiple';
					echo ">";
					echo '<option value="0"></option>';
					foreach($recordids_with_values as $recordid => $fv) {
						# Same ol way
						echo "<option value=\"$recordid\"";
						if (in_array($recordid, $recordids)) echo ' selected';
						echo ">$fv</option>";
						}
					echo '</select>';
				}
				echo '</td></tr></table>';
				break;
			case 'select_cat' :
				$ref_categoryid = $this->get_value('ref_categoryid');
				$ref_category = &$this->get_category($ref_categoryid);
				$catids_recids_values = $this->get_category_linkable_records_with_select_labels($attributeid, $subformat);
				$format = $this->parameters['select_cat_format'];
				if ($format == '') {
					$format = '%category_select%%record_select%';
				}
				$sub_cats = array();
				if ($this->parameters['select_cat_text'] && !in_array('all', $this->parameters['select_cat_ignore'])) {
					$sub_cats = array('all' => $this->parameters['select_cat_text']);
				}
				$sub_cats += $this->get_category_list_with_sticks($ref_categoryid, 100, true, $this->parameters['select_cat_ignore']);

				if (empty($catids_recids_values)) {
					echo "<b>$no_records_text_msg</b>";
				} else {
					$hidden_record_select = false;
					$count = $this->temp['category_linkable_records_with_select_labels_count'];
					$num_recs = count($recordids);
					$category_selected_value = '';
					# assume all records are in the same category
					if ($num_recs > 0 && $num_recs != $count) {
						$record = &$this->get_record($recordids[0]);
						foreach ($record->categoryids as $catid) {
							if (isset($sub_cats[$catid])) {
								$category_selected_value = $catid;
								break;
							}
					 	}
					} elseif ($num_recs == $count) {
						 $category_selected_value = 'all';
					}

					$used_keywords = retrieve_keywords_replacements($format);
					if (!in_array('record_select', $used_keywords)) {
						$hidden_record_select = true;
						$sub_cats = array('' => '') + $sub_cats;
						for ($i = 0; $i < $count; $i++) {
							echo "\n<input type=\"hidden\" name=\"{$prefix}_add[$i]\" value=2>";
						}
					} elseif ($this->parameters['select_cat_text']) {
						# the records might not be in the one category
						$category_selected_value = 'all';
					}
					# create keywords
					$cat_select = "<select id=\"{$prefix}_cats\" onChange=\"javascript: populate_recordids();\">";
                                        foreach ($sub_cats as $catid => $name) {
                                               $cat_select .= "<option value=\"$catid\">".$name.'</option>';
					}
					$cat_select .= '</select>';
					$rec_select = "<select name=\"{$prefix}_add[]\" id=\"{$prefix}_add\"";
					if ($this->get_value('multiple') != 1) $rec_select .= ' multiple';
					$rec_select .= ">";
					$rec_select .= '</select>';
					
					$keywords = array('record_select'=>$rec_select, 'category_select'=>$cat_select);
					$format = replace_keywords($format, $keywords);
					echo $format;
				}
				?>
				<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('var_serialise', 'var_serialise.js');?>"></script>
				<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('js', 'general.js');?>"></script>
				<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('js', 'form_functions.js');?>"></script>
				<script language="JavaScript" type="text/javascript">
					window.<?=$prefix?>catids_recids_values = var_unserialise('<?=var_serialise($catids_recids_values, true);?>');
					window.<?=$prefix?>recordids = var_unserialise('<?=var_serialise($recordids, true);?>');
					var hidden_record_select = <?=($hidden_record_select?'true':'false');?>;

					populate_recordids(true);

					function populate_recordids(first_time) {
						var record_count = <?=$count;?>;
						var cats = document.getElementById('<?=$prefix?>_cats');
						var multiple = <?=(($this->parameters['multiple'] == 0)?'false':'true')?>;
						if (cats == undefined) return;
						
						if (first_time) {
							// set the selected category
							highlightComboElement(cats, '<?=$category_selected_value?>');
						}

						var current_catid = elementValue(cats);
						var catids_recids_values = window.<?=$prefix?>catids_recids_values;
						var form = cats.form;
						var recordids = window.<?=$prefix?>recordids;
						
						// Wipe the array values
						if (hidden_record_select) {
							var record_add = form.<?=$prefix?>_add;
							for (var i=0; i < record_count; i++) {
								form['<?=$prefix?>_add['+i+']'].value = null;
							}
						} else {
							var record_add = document.getElementById('<?=$prefix?>_add');
							if (record_add == undefined) return;
							for(var i = record_add.options.length - 1; i >= 0; i--) {
								record_add.options[i] = null;
							}
							if (multiple) {
								record_add.options[0] = new Option('', 0);
							}
						}

						// set the record ids
						var i = 0;
						for (var catid in catids_recids_values) {
							if (catids_recids_values[catid] == null || catid == '') continue;
							if (catid != current_catid && current_catid != 'all' &&  current_catid != undefined) continue;
							for (var recid in catids_recids_values[catid]) {
								if (catids_recids_values[catid][recid] == null || recid == '') continue;
								if (hidden_record_select) {
									form['<?=$prefix?>_add['+i+']'].value = recid;
									i++;
								} else {
									if (multiple) i++;
									record_add.options[i] = new Option(catids_recids_values[catid][recid], recid);
									if (array_search(recordids, recid) != null) {
										record_add.options[i].selected = true;
									}
									if (!multiple) i++;
								}
							}
						}
					}
				</script>
				<?
				break;
			case 'search': default:
				echo '<table cellpadding="2" cellspacing="0" border="0">';
				foreach($recordids as $recordid) {
					$record = &$this->get_record($recordid);
					if(!$record->id) continue;
					$fv = $record->get_attribute_formatted_value($attributeid,$subformat);
					echo '<tr><td valign="top"><input type="checkbox" name="'.$prefix.'_keep[]" value="'.$recordid.'" checked></td><td valign="top" colspan="2">'.$fv.($GLOBALS['IN_BACKEND']?'<a href="'.$record->get_backend_href().'">*</a>':'').'</td></tr>';
				}

				$search_attribute = &$this->get_attribute($this->parameters['search_attributeids']['link']);
				if(!$search_attribute->id) {
					echo 'A search attribute has not been defined for this foreign key.';
				} else {
					# Print a search box
					echo '<tr><td valign="top"><input type="hidden" name="'.$prefix.'_search" value="1"></td>';
					echo '<td valign="top">';
					$value = $nothing;
					$search_attribute->print_search_field($prefix.'_q', $value, $this->parameters['search_formats']['link'], '', $this->parameters['search_view_format']);
					echo '</td></tr>';
				}
				echo '</table>';
				break;
		}
	}

	#############################################
	# Prints the interface for filling in a value
	function print_field($prefix,&$value,$format) {
		if (!$format) $format = $this->get_value('linking_interface');
		$this->print_concise_field($prefix,$value,$format);
		if($note = &$this->get_value('note')) {
			echo '<span class="smallprint">'.$note.'</span>';
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
		if($this->reversed()) return;

		# Save this value just incase we need it if a rule breaks
		$initial_value_destination_value = $value_destination;
		$keeps  = $_POST[$prefix.'_keep'];
		$search = $_POST[$prefix.'_search'];
		$adds = $_POST[$prefix.'_add'];

		$ref_category = &$this->get_category($this->get_ref_categoryid());
		$recordids = array();
		if($ref_category->id) {
			foreach($keeps as $recordid) {
				$record = &$this->get_record($recordid);
				if(!$record->id || !$ref_category->record_exists_beneath($recordid)) continue;
				$recordids[] = $record->id;
			}
			if($search) {
				$search_attribute = &$this->get_attribute($this->parameters['search_attributeids']['link']);
				if($search_attribute->id) {
					$search_format = $this->parameters['search_formats']['link'];
					$search_value = $search_attribute->process_search_field($prefix.'_q', $search_format);
					if ($search_value) {
						$queries[$search_attribute->id][$search_format][] = $search_value;
						$recordids = array_merge($recordids,$ref_category->search_records($queries,array(),1));
					}
				}
			} elseif (!empty($adds)) {
				foreach($adds as $recordid) {
					$record = &$this->get_record($recordid);
					if(!$record->id || !$ref_category->record_exists_beneath($recordid)) continue;
					$recordids[] = $record->id;
				}
			}
		}
		$old_recordids   = explode('|',$this->strip_outer_pipes($value_destination));
		if($this->get_value('multiple')) {
			$recordids = array_splice($recordids,0,$this->get_value('multiple'));
		}
		$value_destination = implode('|',$recordids);
		if (!empty($value_destination)) {
			$value_destination = $this->add_outer_pipes($value_destination);
		}
		$default_format_attributeid = (int) $this->get_value('default_format');
		$default_format_format      = substr(strstr($this->get_value('default_format'),'_'),1);

		# kill any records that were linked but aren't now
		foreach(array_diff($old_recordids,$recordids) as $lost_recordid) {
			$record = &$this->get_record($lost_recordid);
			if($record->id) {
				$fv = $record->get_attribute_formatted_value($default_format_attributeid, $default_format_format);
				$ms[] = "$fv removed from {$this->attribute->name}.";
			}
		}

		# add any records that aren't already in the records currently existing
		foreach(array_diff($recordids,$old_recordids) as $new_recordid) {
			$record = &$this->get_record($new_recordid);
			if($record->id) {
				$fv = $record->get_attribute_formatted_value($default_format_attributeid, $default_format_format);
				$ms[] = "$fv added to {$this->attribute->name}.";
			}
		}
		#check for rules so we need the rules array

		$this->validate_value($value_destination, $rules_broken_messages);

		if ($rules_broken_messages) {
			$value_destination = $initial_value_destination_value;
			return 'Rule broken in attribute '.$this->name.'. '.$rules_broken_messages;
		} else {
			return implode('\n',$ms);
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
	function validate_value(&$value, &$rule_broken_msgs, $validation_type='none') {
		# don't do any validation if it's a reversed fk
		if($this->reversed()) return;

		# do normal checks
		$ref_category = &$this->get_category($this->get_ref_categoryid());
		if(!$ref_category->id) {
			$value = '';
		}
		if ($validation_type == 'none') {
			# We're validating a recordid
			$nids = array();
			if(preg_match("/^\|[0-9]+(\|[0-9]+)*\|$/",$value)) {
				# Strip off the pipes
				$oids = explode('|',$this->strip_outer_pipes($value));
				foreach($oids as $recordid) {
					if($ref_category->record_exists_beneath($recordid)) {
						$nids[] = $recordid;
					}
				}
			}
		} elseif ($validation_type == 'importing') {
			$search_values = explode('|', $value);
			$nids = array();
			# Search for the referred values (USED WHEN IMPORTING)
			$query = array();
			$format = $this->attribute->parameters['search_formats']['import'];
			$search_attributeid = $this->get_search_attribute_import();

			foreach($search_values as $value) {
				$query[$search_attributeid][$format][] = $value;
			}

			$nids = $ref_category->search_records($query, array(), true);
		}
		if($this->get_value('multiple')) {
			$nids = array_splice($nids,0,$this->get_value('multiple'));
		}
		$value = implode('|',$nids);
		if (!empty($value)) {
			$value = $this->add_outer_pipes($value);
		}

		#check for rules so we need the rules array
		$this->validate_value_rules($value, $rule_broken_msgs);
	}

	/**
	* Adds a pipe character to either side of given value and returns it
	*
	* @param   string $v1 Value to add pipes outside of
	* @access  public
	* @returns string
	*/
	function add_outer_pipes($v) {
		$v = $this->strip_outer_pipes($v);
		return "|$v|";
	}

	/**
	* Strips a pipe character from either side of given value and returns it
	*
	* @param   string $v1 Value to add pipes outside of
	* @access  public
	* @returns string
	*/
	function strip_outer_pipes($v) {
		return trim($v, ' |');
	}

	/**
	* Compares to values and returns the string comparison of it
	*
	* @param   &string $v1 Value 1
	* @param   &string $v2 Value 2
	* @access  public
	* @returns boolean
	*/
	function compare_values(&$v1, &$v2) {
		$a = $this->formatted_value($v1);
		$b = $this->formatted_value($v2);
		return (($a == $b) ? 0 : (($a > $b) ? -1 : 1) );
	}

	 ###########################
	# Prints out a search field
	function print_search_field($prefix,&$default,$format,$not_null) {
		$search_attributeid = $this->get_search_attributeid_search($format);
		$search_format = $this->get_search_format_search($format);
		$search_attribute = &$this->get_attribute($search_attributeid);
		if (preg_match("/_fk_search_override_/", $format)) {
			# Strip the first 20 chars off the start because that is just this -> _fk_search_override_
			# What is left is our search categoryid
			$search_categoryid = substr($format, 20);
			$format = '_fk_search_override_';
		}
		switch ($format) {
			case '_ids':
				echo text_box($prefix.'qvalue',$default, '25');
			break;
			case '_fr_exists':
				?>
				<input type="hidden" name="<?=$prefix?>qvalue" value="fr_exists">
				<?
				break;
			case '_fr_nonexists':
				?>
				<input type="hidden" name="<?=$prefix?>qvalue" value="fr_nonexists">
				<?
			break;
			case '_fk_search_override_':
				# By not using a prefix means only one of these can get shown and processed correctly which makes sence cause you wouldn't want to overrider a search category more than once
				?>
				<input type="hidden" name="s_<?=$this->id?>q_fk_search_override" value="<?=$search_categoryid?>">
				<?
				break;
			default:
				if($search_attribute->id) {
					$search_attribute->print_search_field($prefix.'_q',$default, $search_format, $not_null);
				}
				break;
		}
	}

	 ###################################################
	# Returns a query object which can be given back to
	# print_search_field() or get_search_sql_clause()
	function &process_search_field($prefix,$format,$not_null) {
		if(!$format) $format = $this->parameters['search_formats']['search'];
		if (!$prefix) return;
		if (preg_match("/_fk_search_override_/", $format)) {
			$format = '_fk_search_override_';
		}
		switch ($format) {
			case '_ids':
				return $_REQUEST[$prefix.'qvalue'];
			case '_fr_exists':
				return 'fr_exists';
			case '_fr_nonexists':
				return 'fr_nonexists';
			case '_fk_search_override_':
				# Return false cause it's not supposed to search anything
				return false;
			default:
				$search_format = $this->get_search_format_search($format);
				$search_attributeid = $this->get_search_attributeid_search($format);
				$search_attribute = &$this->get_attribute($search_attributeid);
				if($search_attribute->id) {
					return $search_attribute->process_search_field($prefix.'_q',$search_format, $not_null);
				} else {
					return false;
				}
		}
	}

	 #########################################################
	# Given a query, returns the records that should be searched for
	function get_search_query_recordids($query,$format) {
		if (preg_match("/_fk_search_override_/", $format)) return false;
		if(!$format) $format = $this->parameters['search_formats']['search'];
		if(!isset($this->temp['search_query_recordids']['|'.$query.'|'][$format])) {
			$r = &$this->temp['search_query_recordids']['|'.$query.'|'][$format];
			$search_attributeid = $this->get_search_attributeid_search($format);
			$search_format = $this->get_search_format_search($format);
			$search_attribute = &$this->get_attribute($search_attributeid);
			# Do we have any search category overrides cause we'll use that category if that's the case
			$searching_categoryid = $_POST['s_'.$this->id.'q_fk_search_override'];

			# Meaning this category and all it's subs
			$searching_categories = true;
			if (is_array($searching_categoryid)) {
				# We have more than one search category being given so we'll use the root search category and we'll feed it specific categories
				$searching_categories = $searching_categoryid;
				$searching_categoryid = $this->get_ref_categoryid();
			} elseif (!$searching_categoryid) {
				# Normal case
				$searching_categoryid = $this->get_ref_categoryid();
			}

			$ref_category = &$this->get_category($searching_categoryid);
			if($search_attribute->id && $ref_category->id) {
				$queries[$search_attribute->id][$search_format][] = $query;
				$r = $ref_category->search_records($queries,array(),$searching_categories);
			} else {
				$r = array();
			}
		}
		return $this->temp['search_query_recordids']['|'.$query.'|'][$format];
	}

	 ##############################################
	# Returns an SQL WHERE subclause for searching
	function get_search_sql_clause($query,$format,$value_string='v.value') {
		if(!$format) {
			$format = $this->get_value('search_format');
		}
		if($format == '_' || $format == '_ids') {
			# Special case, searching for particular a particular recordid
			return "$value_string LIKE '%|$query|%'";
		} elseif ($format == '_intersect') {
			$recordids = $query;
		} elseif ($format == '_fr_exists') {
			return "$value_string LIKE '%|'";
		} elseif ($format == '_fr_nonexists') {
			return "$value_string = ''";
		} elseif (preg_match("/_fk_search_override_/", $format)){
			# We don't really want to run a search with this one it's sort of special
			return false;
		} else {
			$recordids = $this->get_search_query_recordids($query,$format);
		}
		$r = '0=1';
		$one_to_one = $this->one_to_one();
		foreach($recordids as $recordid) {
			if ($one_to_one) {
				$r .= " OR $value_string = '|$recordid|'";
			} else {
				$r .= " OR $value_string LIKE '%|$recordid|%'";
			}
		}
		return $r;
	}

	 ##############################################
	# Returns an SQL expression that evalutates a clause
	function get_search_sql_score($query,$format,$value_string='v.value') {
		if(!$format) $format = $this->get_value('search_format');
		if (preg_match("/_fk_search_override_/", $format)) {
			# This ones not really a search so we want to abort any attempt at it
			return false;
		}
		if($format == '_' || $format == '_ids' || $format == '_intersect' || $format == '_fr_exists' || $format == '_fr_nonexists') { # Special cases
			return 1;
		}
		$r = '0';
		$recordids = &$this->get_search_query_recordids($query,$format);
		$c = $i = count($recordids);
		$one_to_one = $this->one_to_one();
		foreach($recordids as $recordid) {
			# Recordids are in descending score order
			if ($one_to_one) {
				$r .= " + ($value_string = '|$recordid|') * ".($i--/$c);
			} else {
				$r .= " + ($value_string LIKE '%|$recordid|%') * ".($i--/$c);
			}
		}
		return $r;
	}

	/**
	* Returns true if the foreign key is "reversed".
	*
	* @returns bool
	*/
	function reversed() {
		return $this->get_value('reversed');
	}

	/**
	* Returns true if the foreign key is NOT "reversed".
	*
	* @returns bool
	*/
	function not_reversed() {
		return !$this->get_value('reversed');
	}

	/**
	* Returns an array of foreign key attributes in foreign categories that refer to this category or one of its parents.
	*
	* @returns array
	*/
	function get_reversable_attribute_list() {
		$r = &$this->temp['reversable_attribute_list'];
		if(!is_array($r)) {
			$db = &$this->get_db();
			$tree = &$this->get_category_tree();
			$referable_categoryids = $tree->ancestorids($this->attribute->categoryid);
			$referable_categoryids[] = $this->attribute->categoryid;
			$regexp = '\"ref_categoryid\"\;s\:[0-9]+\:\"('.implode('|',$referable_categoryids).')\"\;';
			$r = $db->associative_array("SELECT attributeid, CONCAT(a.name,' (in ',c.name,')') FROM {$this->attribute->table_name} a LEFT JOIN $tree->table_name c ON a.categoryid=c.categoryid WHERE a.type='{$this->attribute->type}' AND a.parameters REGEXP '$regexp' AND a.parameters NOT LIKE '%\"reversed\";s:1:\"1\"%'");
		}
		return $r;
	}

	/**
	* Returns an array of linking formats for use in the backend
	*
	* @returns array
	*/
	function get_linking_interface_list() {
		return $this->get_setting('edit_formats');
	}

	/**
	* Should only return true if the linking interface is a select
	*
	* @returns boolean
	*/
	function show_search_view_format() {
		return ($this->parameters['linking_interface'] == 'search_inherit');
	}

	/**
	* Gets the refering attributes edit formats
	*
	* @returns array
	*/
	function get_ref_search_view_formats() {
		$attributeid = $this->get_linking_attributeid();
		$attribute = &$this->get_attribute($attributeid);
		$handler = &$attribute->get_type_handler();
		return $handler->get_edit_formats();;
	}

	/**
	* Returns an array of categories, decorated with tree branches.
	*
	* @param int $categoryid The root category to start at (defaults to system root)
	* @param int $max_depth The maximum recursive depth the tree should go to.
	* @param int $include_head include head category
	* @param int $max_depth The maximum recursive depth the tree should go to.
	* @returns array
	*/
	function get_category_list_with_sticks($categoryid=0, $max_depth=100, $include_head=false, $ignore_categoryids=array()) {
		if (!is_array($ignore_categoryids)) $ignore_categoryids = array();
		$tree = &$this->get_category_tree();
		$array = $tree->get_category_list_with_sticks($categoryid, $max_depth, $ignore_categoryids);
		if ($include_head && !in_array($categoryid, $ignore_categoryids)) {
			$category = &$this->get_category($categoryid);
			if ($category->id) {
				$array = array($categoryid => $category->name) + $array;
			}
		}
		return $array;
	}

	/**
	* Returns an array of categories, decorated with tree branches from the ref category down.
	*
	* @returns array
	*/
	function get_ref_category_list_with_sticks($include_head=false) {
		$ref_categoryid = $this->get_ref_categoryid();
		return $this->get_category_list_with_sticks($ref_categoryid, 100, $include_head);
	}

	/**
	* Returns the categoryid of the referred category.
	*
	* @returns int
	*/
	function get_ref_categoryid() {
		if($this->reversed()) {
			$attribute = &$this->get_attribute($this->get_value('ref_attributeid'));
			return $attribute->categoryid;
		}
		return $this->get_value('ref_categoryid');
	}

	/**
	* Gets the attribute id that this foreign key is linking to
	*
	* @access  public
	* @returns int
	*/
	function get_linking_attributeid() {
		if($this->reversed()) {
			return $this->get_value('ref_attributeid');
		} else {
			return $this->parameters['search_attributeids']['link'];
		}
	}

	/**
	* Returns an array of attributes from the referred category
	*
	* @returns array
	*/
	function get_ref_attribute_list() {
		$category = &$this->get_category($this->get_ref_categoryid());
		if($category->id) {
			$tree = &$this->get_category_tree();
			return $tree->get_context_attributes($category->id);
		}
		return array();
	}

	/**
	* Returns an array of selection attributes from the referred category
	*
	* @returns array
	*/
	function get_ref_selection_attributes_with_blank() {
		$ref_attribute_array = $this->get_ref_attribute_list();
		foreach($ref_attribute_array as $attributeid => $attribute_name) {
			$attribute = $this->get_attribute($attributeid);
			if ($attribute->type != 'selection') {
				unset($ref_attribute_array[$attributeid]);
			}
		}
		
		if (empty($ref_attribute_array)) {
			return array(0 => 'No Selection attributes available in refering category');
		} else {
			return array(0 => '') + $ref_attribute_array;
		}
	}

	/**
	* Returns the attribute id that is being used to represent approval status of external records
	*
	* @returns int
	*/
	function get_approval_attributeid() {
		return $this->get_value('approval_attributeid');
	}

	/**
	* Prints approval status approved value
	*
	* @access public
	* @return string
	*/
	function print_approval_value_form() {
		$attribute = &$this->get_attribute($this->get_approval_attributeid());
		if($attribute->id) {
			$attribute->print_field('approval_status_approved', $this->get_value('approved_value'));
		}
	}

	/**
	* Processes approval status approved value
	*
	* @access public
	* @return string
	*/
	function process_approval_value_form() {
		$attribute = &$this->get_attribute($this->get_approval_attributeid());
		if($attribute->id) {
			return $attribute->process_field('approval_status_approved', $this->get_value('approved_value'));
		}
	}

	/**
	* Returns the list of search formats for the link search attribute.
	* @returns array
	*/
	function get_link_search_format_list() {
		$search_attribute = &$this->get_attribute($this->parameters['search_attributeids']['link']);
		if($search_attribute->id) {
			return $search_attribute->get_type_setting('search_formats');
		}
	}

	/**
	* Returns the list of search formats for the search search attribute.
	*
	* @returns array
	*/
	function get_search_search_format_list() {
		$search_attributeid = $this->get_search_attributeid_search();
		$searching_attributes = array($search_attributeid);
		$other_searching_attributeids = $this->get_value('other_search_attributeids');
		if (!empty($other_searching_attributeids)) {
			$searching_attributes = array_merge($searching_attributes, $other_searching_attributeids);
		}

		$search_formats = array();
		if (!empty($searching_attributes)) {
			foreach($searching_attributes as $attributeid) {
				$search_attribute = &$this->get_attribute($attributeid);
				if($search_attribute->id) {
					$our_search_formats = $search_attribute->get_type_setting('search_formats');
					if (!empty($our_search_formats)) {
						$adding_search_formats = array();
						foreach($our_search_formats as $search_format => $name) {
							if ($attributeid == $search_attributeid) {
								$adding_search_formats[$search_format] = $search_attribute->name.' (Default) - '.$name;
							} else {
								$adding_search_formats[$attributeid.'|'.$search_format] = $search_attribute->name.' - '.$name;
							}
						}
						$search_formats = array_merge($search_formats, $adding_search_formats);
					}
				}
			}
		}

		return $search_formats;
	}

	/**
	* Returns the list of search formats for the import search attribute.
	*
	* @returns array
	*/
	function get_import_search_format_list() {
		$search_attribute = &$this->get_attribute($this->parameters['search_attributeids']['import']);
		if($search_attribute->id) {
			return $search_attribute->get_type_setting('search_formats');
		}
	}

	/**
	* Returns one of the hard-coded settings
	* Settings are not all hardcorded in this case or we wouldn't be overriding
	* this function
	*
	* @param string $setting A string key denoting the type of setting wanted.
	* @returns mixed
	*/
	function &get_setting($setting) {
		switch($setting) {
			case 'value_formats':
				$ret_val = array_merge($this->get_value('formats'),$this->settings['value_formats']);
				return $ret_val;
			case 'edit_formats':
				return $this->settings['edit_formats'];
			case 'search_formats':
				$category_limiters = array();
				$lock_category_keywords = $this->get_value('lock_category_keywords');
				if (!empty($lock_category_keywords)) {
					foreach($lock_category_keywords as $categoryid) {
						$category = &$this->get_category($categoryid);
						if ($category->id) {
							$category_limiters['_fk_search_override_'.$categoryid] = 'Category "'.$category->name.'" limiter (Frontitia use only)';
						}
						unset($category);
					}
				}
				$ret_val = array_merge($this->get_search_search_format_list(),array('_ids'=>'Array of recordids', '_fr_exists' => 'Must have linked records', '_fr_nonexists' => 'Does not have linked Records'), $category_limiters);
				return $ret_val;
			default:
				return $this->settings[$setting];
		}
	}

	/**
	* Prints an interface for defining the printable formats for this
	* attribute - drawing information from the attributes in the
	* referred category.
	*
	* @returns void
	* @see process_format_definition_field
	*/
	function print_format_definition_field() {
		$formats = &$this->get_value('formats');
		$n = &$this->get_notitia_system();
		$n->print_attribute_list('formats', $formats);
	}

	/**
	* Processes an interface for defining the printable formats for this attribute
	* This function draws information from the attributes in the referred category.
	*
	* @returns void
	* @see print_format_definition_field
	*/
	function process_format_definition_field() {
		$formats = &$this->get_value('formats');
		$n = &$this->get_notitia_system();
		$new_format_code = &$this->get_value('new_format_code');
		$formats = $n->process_attribute_list('formats', $formats, $new_format_code);
		$this->set_value('new_format_code','');
	}

	/**
	* Prints a form where the user may select attributes and formats from 
	* the foreign category to use when sorting the foreignids for printing/listing.
	*
	* @returns void
	*/
	function print_foreignid_sort_formats_interface() {
		if($this->reversed()) {
			$attribute = &$this->get_attribute($this->get_value('ref_attributeid'));
			$category = &$this->get_category($attribute->categoryid);
		} else {
			$category = &$this->get_category($this->get_ref_categoryid());
		}
		if($category) {
			$category->print_attribute_format_list_selection_form('foreignid_sort_formats',$this->get_value('foreignid_sort_formats'),'sort',1);
		}
	}

	/**
	* Processes a form where the user may select attributes and formats from 
	* the foreign category to use when sorting the foreignids for printing/listing.
	*
	* @returns void
	*/
	function process_foreignid_sort_formats_interface() {
		if($this->reversed()) {
			$attribute = &$this->get_attribute($this->get_value('ref_attributeid'));
			$category = &$this->get_category($attribute->categoryid);
		} else {
			$category = &$this->get_category($this->get_ref_categoryid());
		}
		if($category->id) {
			$this->parameters['foreignid_sort_formats'] = $category->process_attribute_format_list_selection_form('foreignid_sort_formats',$this->parameters['foreignid_sort_formats']);
		}
	}

	/**
	* Returns an array of attribute/format pairs for selection from the ref category referred category.
	*
	* @returns array
	* @see print_format_definition_field
	*/
	function get_ref_attribute_list_with_formats() {
		$r  = array(); 
		$refering_attributes = $this->get_ref_attribute_list();
		foreach($refering_attributes as $id => $name) {
			$attribute = &$this->get_attribute($id);
			foreach($attribute->get_type_setting('value_formats') as $format => $format_name) {
				$r[$id."_$format"] = "$name ($format_name)";
			}
		}
		return $r;
	}

	/**
	* Returns the attribute id of the attribute this fk is using for importing
	*
	* @returns int
	*/
	function get_search_attribute_import() {
		if ($this->not_reversed()) {
			return $this->parameters['search_attributeids']['import'];
		} else {
			return 0;
		}
	}

	/**
	* Returns the attribute id of the FK attribute that this reversed FK is pointing to
	*
	* @returns int
	*/
	function get_ref_attributeid() {
		return $this->get_value('ref_attributeid');
	}

	/**
	* Sets the attribute id of the FK attribute that this reversed FK is pointing to
	*
	* @param int $attributeid The attribute id to set to
	* @returns string
	*/
	function set_ref_attributeid($attributeid) {
		$ref_attributeid = &$this->get_value('ref_attributeid');
		if ($ref_attributeid != $attributeid && !empty($attributeid)) {
			#a valid change has occured meaning this is a reverse foreign key that is changing the FK attribute it is pointing to either from nothing to something or something to something
			$ref_attributeid = $attributeid;
			$db = &$this->get_db();

			$local_attributeid = $this->attribute->id;
			$table_name = $this->get_value_table_name();

			$discard = $db->delete("DELETE FROM $table_name WHERE attributeid = '$local_attributeid'");
			$foreign_links = $db->associative_array("SELECT recordid, value FROM $table_name WHERE attributeid='$attributeid'");
			$local_links = array();

			foreach($foreign_links as $foreign_recordid => $local_recordids) {
				#stip off the start and end |'s before blowing to pieces
				$local_recordids = $this->strip_outer_pipes($local_recordids);
				$fk_linking_recordids = explode('|', $local_recordids);
				foreach($fk_linking_recordids as $local_recordid) {
					if(!$local_recordid) continue;
					$local_links[$local_recordid][] = $foreign_recordid;
				}
			}

			foreach($local_links as $local_recordid => $foreign_recordids) {
				$value = $this->add_outer_pipes(implode('|', $foreign_recordids));
				$db->insert("INSERT INTO $table_name (attributeid,recordid,value) VALUES ('$local_attributeid','$local_recordid','$value')");
			}
			$this->attribute->clear_associated_record_caches();

			return 'Reverse Foreign Key ref attribute has been changed';
		}
	}

	/**
	* Returns the value for truncation
	* @returns int
	*/
	function trunction_value() {
		return $this->get_value('truncation');
	}

	/**
	* Returns the basic new settings for the must link to x records settings
	*
	* @access public
	* @return array
	*/
	function get_new_rule_must_link_x_woperator_settings() {
		return array('type' => 'must_link_x_woperator', 'name' => 'Must Link to X records using operator', 'broken_message' => 'Must link to X records', 'number_records' => '1', 'operator' => '==');
	}

	/**
	* Returns the interface for the must link to x records rule type
	*
	* @param  string $interface_name The basic name of the interface
	* @param  array  $rule_settings  The settings for the rule in question
	* @access public
	* @return string
	*/
	function get_rule_must_link_x_woperator_interface($interface_name, $rule_settings) {
		$broken_message = text_box($interface_name.'_broken_message', $rule_settings['broken_message'], 40);
		$number_record_input = text_box($interface_name.'_link_to_records', $rule_settings['number_records'], 6);
		$operator_types = array('==' => '==', '<' => '<', '>'=> '>');
		$operator_dropdown = combo_box($interface_name.'_operator_type', $operator_types, $rule_settings['operator']);
		$interface  = '<table bgcolor="#AAAAAA">';
		$interface .= '	<tr bgcolor="#DDDDDD">';
		$interface .= '		<td><i>Rule Broken Message</i></td><td>'.$broken_message.'</td>';
		$interface .= '	</tr>';
		$interface .= '	<tr bgcolor="#DDDDDD">';
		$interface .= '		<td><i>Number of records the record must link to</i></td><td>'.$number_record_input.'</td>';
		$interface .= '	</tr>';
		$interface .= '	<tr bgcolor="#DDDDDD">';
		$interface .= '		<td><i>Comparison Operator</i></td><td>'.$operator_dropdown.'</td>';
		$interface .= '	</tr>';
		$interface .= '</table>';
		return $interface;
	}

	/**
	* Processes any changes to the rule must link to x records interface
	*
	* @param  string $interface_name The basic name of the interface
	* @param  array  &$rule_settings  The settings for the rule in question
	* @access public
	* @return string
	*/
	function process_rule_must_link_x_woperator_interface($interface_name, &$rule_settings) {
		$msgs = '';
		if ($_POST[$interface_name.'_broken_message'] != $rule_settings['broken_message'] && isset($_POST[$interface_name.'_broken_message'])) {
			$rule_settings['broken_message'] = $_POST[$interface_name.'_broken_message'];
			$msgs .= 'Broken rule message has been updated<br />';
		}
		if ($_POST[$interface_name.'_link_to_records'] != $rule_settings['number_records'] && isset($_POST[$interface_name.'_link_to_records'])) {
			$rule_settings['number_records'] = $_POST[$interface_name.'_link_to_records'];
			$msgs .= 'Number of records value has been modified<br />';
		}
		if ($_POST[$interface_name.'_operator_type'] != $rule_settings['operator'] && isset($_POST[$interface_name.'_operator_type'])) {
			$rule_settings['operator'] = $_POST[$interface_name.'_operator_type'];
			$msgs .= 'Comparison operator has been modified<br />';
		}
		return $msgs;
	}

	/**
	* Validating must link x value
	*
	* @param  string $value         The value we need to check against the rule
	* @param  array  $rule_settings The settings of the rule we are checking against the value
	* @access public
	* @return string
	*/
	function validate_must_link_x_woperator_rule($value, $rule_settings) {
		$temp = $this->strip_outer_pipes($value);
		if ($value) {
			$r = explode('|', $temp);
		} else {
			$r = array();
		}
		$elements = count($r);
		$operator = $rule_settings['operator'];
		$number_of_record = $rule_settings['number_records'];
		eval("\$return = (($elements $operator $number_of_record)?'':\$rule_settings['broken_message']);");
		return $return;
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
		if ($format == '_ids') {
			$_REQUEST[$prefix.'qvalue'] = $value;
		} else {
			$search_attributeid = $this->get_search_attributeid_search($format);
			$search_format = $this->get_search_format_search($format);
			$search_attribute = &$this->get_attribute($search_attributeid);
			if($search_attribute->id) {
				$search_attribute->set_search_request($prefix.'_q',$value, $search_format);
			}
		}
	}

	/**
	* Gets all linkable recordids if done before it comes from the object temp area otherwise it comes from a search
	*
	* @access  public
	* @returns array
	*/
	function get_all_linkable_recordids() {
		if (is_array($this->temp['all_linkable_recordids'])) {
			return $this->temp['all_linkable_recordids'];
		} else {
			$complex_attribute_queries = array();
			$sort_formats = array_keys($this->get_value('foreignid_sort_formats'));

			$approval_attribute = &$this->get_attribute($this->get_approval_attributeid());
			$approved_value = $this->get_value('approved_value');
			$approval_action = $this->get_value('approval_action');
			if ($approval_attribute->id) {
				# We can hard code this search format cause we know they can only choose selection attributes
				$complex_attribute_queries[$approval_attribute->id]['subset']['foreign_key_approved'] = $approved_value;
				$approved_rule = $approval_attribute->id.'%subset%foreign_key_approved';
			}

			$not_owned_by = array();

			# Go through each ownership rule
			$ownership_count = 0;
			$save_rule_array = array();
			foreach($this->parameters['ownership_rules_list'] as $rule) {
				$source_value = $this->get_ownership_search_value($rule['source'], $rule['source_user_input']);

				list($attributeid, $format) = explode('_', $rule['attribute_format'], 2);
				if ($rule['logic'] == '2') {
					# Record that this ownership rule is something where we want it not owned by the user
					$not_owned_by[] = $ownership_count;
				} elseif ($rule['logic'] == '3') {
					# Find all records in secondary category that are linked to the owner
					$query[$attributeid][$format][] = $source_value;
					$category = &$this->get_category($rule['search_cid']);
					if ($category->id) {
						$record_ids = $category->search_records($query, array(), true);
						$source_value = $record_ids;
						$attributeid = $rule['local_fk_id'];
						$format = '_intersect';
					}
				}
				$complex_attribute_queries[$attributeid][$format]['foreign_key_ownership_'.$ownership_count] = $source_value;
				$save_rule_array[$ownership_count] = $attributeid.'%'.$format.'%foreign_key_ownership_'.$ownership_count;
				$ownership_count++;
			}

			# Work out ownership query
			if ($ownership_count > 0) {
				for($i = 0; $i < $ownership_count; $i++) {
					# Plus 1 to leave room for possibly a logic
					if (in_array($i, $not_owned_by)) {
						$ownership_rules[$i] = array(0=>'NOT', 1=> array('OR', $save_rule_array[$i]));
					} else {
						$ownership_rules[$i] = $save_rule_array[$i];
					}
				}
				# Complete the ownership query
				array_unshift($ownership_rules, $this->parameters['ownership_logic']);

			} else {
				$ownership_rules = 'TRUE';
			}
			
			# left for the possibilty to expand
			$complex_matching_rules = array
												(
												0 => 'AND',
												1 => $approved_rule,
												2 => $ownership_rules,
												);

			$lower_exclude_condition = $this->get_value('exclude_records_from_lower_categories');
			# An upgrade script and change of pset file could elimate the need for this code
			if ($lower_exclude_condition) {
				$lower_exclude_condition = 0;
			} else {
				$lower_exclude_condition = 1;
			}

			$pointing_to_categoryid = &$this->get_value('ref_categoryid');
			$pointing_to_category = &$this->get_category($pointing_to_categoryid);
			if ($pointing_to_category->id) {
				$linkable_recordids = $pointing_to_category->search_records(array(),$sort_formats,$lower_exclude_condition, 'AND', array(), $complex_attribute_queries, $complex_matching_rules);
			} else {
				$linkable_recordids = array();
			}
			$this->temp['all_linkable_recordids'] = $linkable_recordids;
			return $linkable_recordids;
		}
	}

	/**
	* Gets array of recordids and printable values. If done before it comes from the object temp area otherwise it comes from a loop
	*
	* @param   int    $attributeid Attribute id of value formatting attribute
	* @param   string $subformat   Format to print foreign values out with
	* @access  public
	* @returns array
	*/
	function get_linkable_records_with_select_labels($attributeid, $subformat) {
		if (is_array($this->temp['linkable_records_with_select_labels'])) {
			return $this->temp['linkable_records_with_select_labels'];
		} else {
			$linkable_records_with_select_labels = array();
			$trunction_character = $this->get_value('truncation_character');
			$truncation_value = $this->trunction_value();
			$all_recordids = $this->get_all_linkable_recordids();
			foreach($all_recordids as $recordid) {
				$record = &$this->get_record($recordid);
				if(!$record->id) continue;
				$fv = $record->get_attribute_formatted_value($attributeid,$subformat);
				if (!empty($truncation_value) && strlen($fv) > $truncation_value) {
					$fv = substr($fv, 0, $truncation_value);
					$fv = $fv.$trunction_character;
				}
				$linkable_records_with_select_labels[$record->id] = $fv;
			}
			$this->temp['linkable_records_with_select_labels'] = $linkable_records_with_select_labels;
			return $linkable_records_with_select_labels;
		}
	}

	/**
	* Gets array of recordids and printable values. If done before it comes from the object temp area otherwise it comes from a loop
	*
	* @param   int    $attributeid Attribute id of value formatting attribute
	* @param   string $subformat   Format to print foreign values out with
	* @access  public
	* @returns array
	*/
	function get_category_linkable_records_with_select_labels($attributeid, $subformat) {
		if (is_array($this->temp['category_linkable_records_with_select_labels'])) {
			return $this->temp['category_linkable_records_with_select_labels'];
		} else {
			$this->temp['category_linkable_records_with_select_labels_count'] = 0;
			$category_linkable_records_with_select_labels = array();
			$trunction_character = $this->get_value('truncation_character');
			$truncation_value = $this->trunction_value();
			$all_recordids = $this->get_all_linkable_recordids();
			$sub_cats = $this->get_ref_category_and_descendants_ids();
			foreach($all_recordids as $recordid) {
				$record = &$this->get_record($recordid);
				if(!$record->id) continue;
				$fv = $record->get_attribute_formatted_value($attributeid,$subformat);
				if (!empty($truncation_value) && strlen($fv) > $truncation_value) {
					$fv = substr($fv, 0, $truncation_value);
					$fv = $fv.$trunction_character;
				}
				foreach ($record->categoryids as $catid) {
					if (in_array($catid, $sub_cats)) {
						$category_linkable_records_with_select_labels[$catid][$record->id] = $fv;
						$this->temp['category_linkable_records_with_select_labels_count']++;
					}
				}
			}
			$this->temp['category_linkable_records_with_select_labels'] = $category_linkable_records_with_select_labels;
			return $category_linkable_records_with_select_labels;
		}
	}

	function get_ref_category_and_descendants_ids() {
		if (is_array($this->temp['ref_category_and_descendants_ids'])) {
			return $this->temp['ref_category_and_descendants_ids'];
		} else {
			$ref_categoryid = $this->get_value('ref_categoryid');
			$tree = &$this->get_category_tree();
			$sub_cats = array($ref_categoryid);
			$sub_cats = array_merge($sub_cats, $tree->all_descendantids($ref_categoryid));
			$this->temp['ref_category_and_descendants_ids'] = $sub_cats;
			return $sub_cats;
		}
	}

	/**
	* Sets the foreign keys functionality but it's main purpose is if it's going to become a reverse foreign key to wipe all defaults and set it to blank so keep searching working
	*
	* @param   int     $functionality_code 0 = Normal, 1 = Reversed
	* @param   boolean $found_in_post_vars Whether parameter set found it in the post vars cause we aren't setting anything if it didn't
	* @access  public
	* @returns string
	*/
	function set_foreign_key_functionality($functionality_code, $found_in_post_vars=true) {
		$current_functionality = &$this->get_value('reversed');
		if ($current_functionality != $functionality_code && $found_in_post_vars) {
			$current_functionality = $functionality_code;
			$msgs = 'Foreign Key functionality has been changed.<br/>';
			# 0 = Normal, 1 = Reversed
			if ($functionality_code == '1') {
				# So we are changing functionality to reversed? We need to delete the default if it exists and make out default a blank string otherwise it can break searching
				# Remove the default value and any defaultr overrides
				$db = &$this->get_db();
				$default_table_name = $this->get_default_value_table_name();
				$db->delete("DELETE FROM $default_table_name WHERE attributeid='$this->id'");
				$default = '';
				$this->attribute->set_default_override($this->attribute->categoryid,$default);
			}
		}
		return $msgs;
	}

	/**
	* The search attributeid given a format. Run this function cause the search attributeid for foreign keys sometimes is sometimes in the search format because it's an alternative search attributeid
	*
	* @access  public
	* @returns int
	*/
	function get_search_attributeid_search($format) {
		if(!$format) $format = $this->parameters['search_formats']['search'];
		list($search_attributeid, $search_format) = explode('|', $format);
		# Some formats may not have the attributeid with it and that's the default formats
		if (!$search_format) {
			$search_attributeid = $this->parameters['search_attributeids']['search'];
		}
		return $search_attributeid;
	}

	/**
	* The search format given a format. Run this function cause the search format for foreign keys sometimes also has an alternative attributeid in it
	*
	* @access  public
	* @returns string
	*/
	function get_search_format_search($format) {
		if(!$format) $format = $this->parameters['search_formats']['search'];
		list($search_attributeid, $search_format) = explode('|', $format);
		# Some formats may not have the attributeid with it and that's the default formats
		if (!$search_format) {
			$search_format = $format;
		}
		return $search_format;
	}

	/**
	* Returns true if this foreign key is setup to have a one to one relationship
	*
	* @access  public
	* @returns boolean
	*/
	function one_to_one() {
		return ($this->not_reversed() && $this->get_value('multiple'));
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
		# Do not allow the FK to link to itself
		$v = $this->strip_outer_pipes($value);
		$recordids = explode('|', $v);
		if (($key = array_search($recordid, $recordids)) !== false) {
			unset($recordids[$key]);
			if (!empty($recordids)) {
				$value = $this->add_outer_pipes(implode('|', $recordids));
			} else {
				$value = null;
				return;
			}
		}

		# Do special REVERSE FOREIGN KEY checking (I know this will be a normal foreign key because the above check would have exited this function already if it was a reversed foreign key)
		$ref_category = &$this->get_category($this->get_value('ref_categoryid'));

		# Don't do anything if the foreign key isn't pointing to a category
		if ($ref_category->id) {
			$db = &$this->get_db();
			$table_name = $this->get_value_table_name();

			# Look in the refering category for all attributes
			$category_attributeids = $ref_category->associated_attributes();
			$RFK_attribute_ids = array();
			if (!empty($category_attributeids)) {
				# Go through all the attributes looking for a reverse foreign key pointing back to this foreign key
				foreach($category_attributeids as $attributeid) {
					$foreign_key = &$this->get_attribute($attributeid);
					$handler = &$foreign_key->get_type_handler();
					#we only want a list of all foreign keys that are reversed and are refering to this foreign key
					if (	$foreign_key->type == 'foreign_key' &&
							$handler->reversed() &&
							$handler->get_linking_attributeid() == $this->id
						) {
						array_push($RFK_attribute_ids, $foreign_key->id);
					}
				}
			}
			if (!empty($RFK_attribute_ids)) {
				# Since we have reverse foreign keys pointing to this foreign key we now should make sure whatever change we make to this foreign key will also be cool with the others pointing to this foreign key

				# We need this stuff to do the rest first we trim off the outer pipes
				$v = $this->strip_outer_pipes($value);
				$new_fk_recordids = explode('|',$v);
				$old_fk_recordids = $db->single_element("SELECT value FROM $table_name WHERE recordid='$recordid' && attributeid='$this->id'");
				# Strip off outer pipes
				$v = $this->strip_outer_pipes($old_fk_recordids);
				$old_fk_recordids = explode('|', $v);

				# Now we have the two arrays check for any record ids that are being lost
				foreach(array_diff($old_fk_recordids, $new_fk_recordids) as $lost_recordid) {
					# This is a record with a reverse foreign key in it
					$record = &$this->get_record($lost_recordid);
					if($record->id) {
						# For this record's reverse foreign keys remove any mention of $recordid
						foreach($RFK_attribute_ids as $RFK_attribute_id) {
							$rfk_attribute = &$this->get_attribute($RFK_attribute_id);
							$foreign_value_table = $rfk_attribute->get_value_table_name();

							# Remove $recordid
							$linked_recordids = $record->get_attribute_formatted_value($RFK_attribute_id, '_ids');
							$new_linked_records = array_diff($linked_recordids, array($recordid));
							if (!empty($new_linked_records)) {
								$fk_value = '|'.implode('|',$new_linked_records).'|';
								$discard1 = $db->replace("REPLACE INTO $foreign_value_table (value,attributeid,recordid) VALUES ('".addslashes($fk_value)."','$RFK_attribute_id','$lost_recordid')");
							} else {
								$discard = $db->delete("DELETE FROM $foreign_value_table WHERE attributeid = '$RFK_attribute_id' AND recordid = '$lost_recordid'");
							}
							$record->clear_cache();
							$record->load();
						}
					}
				}

				# Check for any record id's that are being added
				foreach(array_diff($new_fk_recordids, $old_fk_recordids) as $new_recordid) {
					# This is a record with a reverse foreign key in it
					$record = &$this->get_record($new_recordid);
					if($record->id) {
						# For this record's reverse foreign keys add any mention of $recordid
						foreach($RFK_attribute_ids as $RFK_attribute_id) {
							$rfk_attribute = &$this->get_attribute($RFK_attribute_id);
							$foreign_value_table = $rfk_attribute->get_value_table_name();

							# Insert $recordid
							$linked_recordids = $record->get_attribute_formatted_value($RFK_attribute_id, '_ids');
							if (count($linked_recordids)) {
								$new_linked_records = array_merge($linked_recordids, $recordid);
							} else {
								$new_linked_records = array($recordid);
							}
							$fk_value = '|'.implode('|',$new_linked_records).'|';
							$discard = $db->replace("REPLACE INTO $foreign_value_table (value,attributeid,recordid) VALUES ('".addslashes($fk_value)."','$RFK_attribute_id','$new_recordid')");
							$record->clear_cache();
							$record->load();
						}
					}
				}
			}
		} # end rfk stuff
	} # end fn

	/**
	* Gets the array that stores the ownership names and their codes that are available
	*
	* @access  public
	* @returns array
	*/
	function get_ownership_rules_array() {
		return array(	'none'=>'-', 
						1=>'Owned by Logged In User query',
						2=>'Not owned by Logged In User query',
						3=>'Owned by Logged In User Squared');
	}

	/**
	* Gets an array of all the sources that ownership can come from
	*
	* @access  public
	* @returns array
	*/
	function get_ownership_source_array() {
		return array('user_input'=>'User Input', 'session_login'=>'Login ID', 'session_login_comments' => 'Login Comments', 'session_mobile_no' => 'Mobile Number');
	}

	/**
	* Prints out an option box of ownership rules that can be added depending on caller
	*
	* @access  public
	* @returns void
	*/
	function print_add_ownership_rule() {
		$all_ownership_rules = $this->get_ownership_rules_array();
		$combo = combo_box('ownership_rule_combo_box', $all_ownership_rules, 'none');
		?><table><tr><td><?=$combo?></td></tr></table><?
	}

	/**
	* Adds the ownership that has been commited
	*
	* @access  public
	* @returns void
	*/
	function process_add_ownership_rule() {
		$logic_to_add = $_POST['ownership_rule_combo_box'];
		# Do some adding if there's something to add
		if ($logic_to_add != 'none' && $logic_to_add != '') {
			$rules_list = &$this->parameters['ownership_rules_list'];
			if (!is_array($rules_list)) $rules_list = array();
			if ($logic_to_add == 3) {
				array_push($rules_list, array('logic'=>$logic_to_add, 'source'=>'session_login', 'source_user_input'=>'', 'attribute_format'=>'0', 'search_cid'=>'0', 'local_fk_id' => '0', 'level'=>'1'));
			} else {
				array_push($rules_list, array('logic'=>$logic_to_add, 'source'=>'session_login', 'source_user_input'=>'', 'attribute_format'=>'0', 'level'=>'1'));
			}
			return 'An Ownership Rule has been added to the extra logic list<br>';
		}
	}

	/**
	* Prints out all ownership rules that have been added to our list depending on the caller
	*
	* @access  public
	* @returns void
	*/
	function print_ownership_rules_list() {
		$ownership_rules_list = $this->parameters['ownership_rules_list'];
		$ownership_source_array = $this->get_ownership_source_array();
		$all_ownership_rules = $this->get_ownership_rules_array();
		?>
		<table bgcolor=#888888 cellpadding="0" cellspacing="0" border="0"><tr><td>
		<table cellspacing="1" cellpadding="3">
			<tr bgcolor="#aaaaaa">
				<td nowrap><b>Ownership Rule</b></td>
				<td nowrap><b>Matching Source</b></td>
				<td nowrap><b>Matching Attribute</b></td>
				<td><b>Delete?</b></td></tr>
		<?
		if (count($ownership_rules_list) > 0) {
			foreach($ownership_rules_list as $key => $rule) {
				$delete_box = $this->create_checkbox('delete_ownership_rule_'.$key, 'Delete?');
				$source_combo = combo_box('ownership_source_'.$key, $ownership_source_array, $rule['source']);
				$level_combo = combo_box('ownership_level_'.$key, array('1'=>'Primary Level', '2'=>'Secondary Level'), $rule['level']);
				if ($rule['logic'] == 3) {
					if ($rule['search_cid']) {
						$attribute_combo = combo_box('ownership_attribute_'.$key, $this->get_attribute_list_with_format_setting('search_formats', $rule['search_cid']), $rule['attribute_format']);
					} else {
						$attribute_combo = '<b>A search category must be selected</b>';
					}
				} else {
					$attribute_combo = combo_box('ownership_attribute_'.$key, $this->get_attribute_list_with_format_setting('search_formats', $this->get_value('ref_categoryid')), $rule['attribute_format']);
				}

				if ($rule['source'] == 'user_input') {
					$user_input_text_box = text_box('ownership_user_input_'.$key, $rule['source_user_input'], 15);
				} else {
					$user_input_text_box = '';
				}
				?>
			<tr bgcolor="#eeeeee">
				<td><?=$all_ownership_rules[$rule['logic']]?><br/><?//=$level_combo?></td>
				<td><?=$source_combo.' '.$user_input_text_box?></td>
				<td><?=$attribute_combo?></td>
				<td><?=$delete_box?></td>
			</tr>
				<?
				if ($rule['logic'] == 3) {
					$all_categories = $this->get_root_category_list();
					$searching_combo = combo_box('ownership_searching_'.$key, $all_categories, $rule['search_cid']);
					$fk_combo = combo_box('ownership_fk_attribute_'.$key, $this->get_local_attribute_list($categoryid), $rule['local_fk_id']);
				?>
			<tr bgcolor="#eeeeee">
				<td colspan="2"><b>Searching Category</b><br /><?=$searching_combo?></td>
				<td colspan="2"><b>Local Foreign Key</b><br /><?=$fk_combo?></td>
			</tr>
				<?
				}
			}
		} else {
			?>
			<tr bgcolor="#aaaaaa">
				<td nowrap colspan="4"><b>There are no Ownership Rules being used</b></td>
			</tr>
			<?
		}
		?>
		</table>
		</td></tr></table>
		<?
	}

	/**
	* Processes any ownership that might have been deleted from the list
	*
	* @access  public
	* @returns string
	*/
	function process_ownership_rules_list() {

		$all_ownership_rules = $this->get_ownership_rules_array();
		$m = '';
		$ownership_rules_list = &$this->parameters['ownership_rules_list'];
		foreach ($ownership_rules_list as $key => $rule) {
			if($_POST['delete_ownership_rule_'.$key]) {
				unset($ownership_rules_list[$key]);
				$m .= 'An Ownership rule has been deleted from the list<br>';
			} else {
				# Check for changes
				if ($_POST['ownership_source_'.$key] != $rule['source'] && isset($_POST['ownership_source_'.$key])) {
					$ownership_rules_list[$key]['source'] = $_POST['ownership_source_'.$key];
					$m .= 'Ownership source has been modified<br>';
				}
				if ($_POST['ownership_level_'.$key] != $rule['level'] && isset($_POST['ownership_level_'.$key])) {
					$ownership_rules_list[$key]['level'] = $_POST['ownership_level_'.$key];
					$m .= 'Ownership level has been modified<br>';
				}
				if ($_POST['ownership_attribute_'.$key] != $rule['attribute_format'] && isset($_POST['ownership_attribute_'.$key])) {
					$ownership_rules_list[$key]['attribute_format'] = $_POST['ownership_attribute_'.$key];
					$m .= 'Ownership attribute has been modified<br>';
				}
				if ($_POST['ownership_user_input_'.$key] != $rule['source_user_input'] && isset($_POST['ownership_user_input_'.$key])) {
					$ownership_rules_list[$key]['source_user_input'] = $_POST['ownership_user_input_'.$key];
					$m .= 'Ownership User Input Source has been modified<br>';
				}
				if ($rule['logic'] == 3) {
					if ($_POST['ownership_fk_attribute_'.$key] != $rule['local_fk_id'] && isset($_POST['ownership_fk_attribute_'.$key])) {
						$ownership_rules_list[$key]['local_fk_id'] = $_POST['ownership_fk_attribute_'.$key];
						$m .= 'Ownership Foreign Key attribute has been modified<br>';
					}
					if ($_POST['ownership_searching_'.$key] != $rule['search_cid'] && isset($_POST['ownership_searching_'.$key])) {
						$ownership_rules_list[$key]['search_cid'] = $_POST['ownership_searching_'.$key];
						$m .= 'Ownership searching category has been modified<br>';
					}
				}
			}
		}

		if ($m) {
			return $m;
		}
	}

	/**
	* Returns a string containing HTML for a check box filled using what's fed
	*
	* @param   string $name The name of the checkbox
	* @param   string $text The text to show after the checkbox
	* @param   boolean $checked Whether the box is checked or not
	* @access  public
	* @returns string
	*/
	function create_checkbox($name, $text, $checked = '', $value) {
		if ($checked) $checked = 'checked';
		else $checked = '';
		if (!$value) $value = 1;
		return '<input type="checkbox" name="'.$name.'" VALUE="'.$value.'" '.$checked.'> '.$text;
	}

	/**
	* Get the local attribute list with search formats
	*
	* @access  public
	* @returns array
	*/
	function get_attribute_list_with_format_setting($format_setting, $categoryid=0) {
		if (!$categoryid) {
			$categoryid = $this->attribute->categoryid;
		}
		$r = array(); 
		$attribute_list = $this->get_local_attribute_list($categoryid);
		foreach($attribute_list as $id => $name) {
			$attribute = &$this->get_attribute($id);
			foreach($attribute->get_type_setting($format_setting) as $format => $format_name) {
				$r[$id.'_'.$format] = "$name ($format_name)";
			}
		}
		return $r;
	}

	/**
	* Returns a list of attributes defined at the current browsing level
	*
	* @param string $categoryid The category id to select attribute from
	* @param string $type Optional limitter of the type of attribute you want
	* @access public
	* @return array
	*/
	function get_local_attribute_list($categoryid=0, $type='') {
		if ($categoryid==0) {
			$categoryid = $this->attribute->categoryid;
		}
		$tree = &$this->get_category_tree();
		$context_attributes = $tree->get_context_attributes($categoryid);
		if ($type == '') {
			return $context_attributes;
		} else {
			$selected_attributes = array();
			foreach($context_attributes as $attributeid => $name) {
				$attribute = &$this->get_attribute($attributeid);
				if ($attribute->type == $type) {
					$selected_attributes[$attributeid] = $name;
				}
			}
			return $selected_attributes;
		}
	}

	/**
	* Returns a list of categories, with sticks, but only three levels deep, for selecting the base category with
	*
	* @access public
	* @return array
	*/
	function get_root_category_list() {
		$tree = &$this->get_category_tree();
		$rootid = 0;
		return array($rootid => "[The Notitia Category Root]") + $tree->get_category_list_with_sticks($rootid,100);
	}

	/**
	* Some common settings that can be used for search values
	*
	* @param string $source What type of value do you want
	* @param string $user_input Needed so if the type of value they want is user input we can give it right back to em
	* @access  public
	* @returns string
	*/
	function get_ownership_search_value($source, $user_input) {
		$session = &get_mysource_session();
		# Find out where to get the owner value from
		switch($source) {
			case 'session_login':
				return $session->user->id;
			case 'session_login_comments':
				return $session->user->comments;
			case 'session_mobile_no':
				return $session->user->mobile_no;
			case 'user_input':
				return $user_input;
			case 'session_attributeid':
				$form_answers = $session->get_var('form_answers');
				return $form_answers['session_attributeid'];
			case 'session_categoryid':
				$form_answers = $session->get_var('form_answers');
				return $form_answers['session_categoryid'];
			default:
				return false;
		}
	}

	/**
	* Returns the separating char that separates multiple values returned cause the record is linked to multiple records
	*
	* @access  public
	* @returns string
	*/
	function get_mul_val_separating_character() {
		$separating_char = $this->get_value('multi_val_sep_char');
		if ($separating_char) {
			return $separating_char;
		} else {
			return ', ';
		}
	}

	/**
	* Prints the category select format
	*
	* @access  public
	* @param int $parameter - the parameter to save
	* @returns void
	*/
	function print_category_select_format() {
		if ($this->get_wysiwyg_pageid()) {
			$this->print_wysiwyg_format('select_cat_format');
		} else {
			echo text_area('select_cat_format', $this->parameters['select_cat_format'], 60, 2);
		}
		echo '<br /><span class="backend_fineprint">Define the format of Select Box with Category Select Concise field.<br />Keywords to be used are %record_select% and %category_select%.</span>';
	}

	/**
	* Processes the category select format
	*
	* @access  public
	* @param int $parameter - the parameter to save
	* @returns void
	*/
	function process_category_select_format() {
		if ($this->get_wysiwyg_pageid()) {
			$return = $this->process_wysiwyg_format('select_cat_format');
		} else {
			if ($_POST['select_cat_format'] != $this->parameters['select_cat_format']) {
				$this->parameters['select_cat_format'] = $_POST['select_cat_format'];
				$return = 'Select Cateogry format has been updated';
			}
		}
		return $return;
	}

	/**
	* Prints the WYSIWYG
	*
	* @access  public
	* @param int $parameter - the parameter to save
	* @returns void
	*/
	function print_wysiwyg_format($parameter) {
		$wysiwyg = &$this->get_wysiwyg('web_extention_notitia_attribute_types_record_search_wysiwyg');
		$wysiwyg->paint_edit($this->parameters[$parameter], true);
	}

	/**
	* Processes any changes to the WYSIWYG
	*
	* @access  public
	* @param int $parameter - the parameter to save
	* @returns string
	*/
	function process_wysiwyg_format($parameter) {
		$wysiwyg = &$this->get_wysiwyg('web_extention_notitia_attribute_types_record_search_wysiwyg');
		if ($this->parameters[$parameter] != $wysiwyg->get_html()) {
			$this->parameters[$parameter] = $wysiwyg->get_html();
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
}
?>
