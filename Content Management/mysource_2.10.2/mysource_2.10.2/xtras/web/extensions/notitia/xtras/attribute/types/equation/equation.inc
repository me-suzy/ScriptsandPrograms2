<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/equation/equation.inc,v $
## $Revision: 1.20.2.2 $
## $Author: tbarrett $
## $Date: 2004/04/19 05:24:51 $
#######################################################################

#---------------------------------------------------------------------#
include_once(dirname(__FILE__).'/operator/operator.inc');

 #######################################################
# Forgeign keys, linking to other records in the system
class Web_Extension_Notitia_Attribute_Type_Equation extends Web_Extension_Notitia_Attribute_Type {
	
	 ################################################################
	# This is a static thingy that stores special information about
	# a particular type.
	var $settings = array(
			'preferred_alignment' => 'left',
			'generates_varieties' => false,
			'variable'            => false,
			'value_formats' => array(
				'raw' => 'Raw'
			),
			'variation_types'   => array(
				#'replace' => 'Replace',
			),
			'edit_formats' => array (
				#'raw' => 'Uneditable'
			),
			'sort_formats' => array (
			),
			'rule_types' => array (
			),
			'requires_context_values' => true,
			'sub_type_name' => 'equation',
			'xtras_dir' => 'operator',
			'table_settings' => array(
				'sub_table_name' => ''
			)
		);
	var $temp = array(
			'operator_results' => array()
		);

	 ################################################
	# Returns the value formatted in a nice way for printing
	function formatted_value(&$value, $format) {
		switch($format) {
			case 'raw': default:
				return $this->evalute_equation($value);
		}
	}

	 #############################################
	# Prints the interface for filling in a value
	function print_concise_field($prefix,&$value,$options) {
		$printable_value = $this->formatted_value($value, $options);
		?>
		<table>
			<tr><td><b>[Equation-Attribute - cannot be edited]</b></td></tr>
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

	 ###################################################
	# Returns a query object which can be given back to
	# print_search_field() or get_search_sql_clause()
	function &process_search_field($prefix,$format,$not_null) {
		return;
	}

	 ##############################################
	# Returns an SQL WHERE subclause for searching
	function get_search_sql_clause($query,$format) {
		return;
	}

	 ##############################################
	# Returns an SQL expression that evalutates a clause
	function get_search_sql_score($query,$format) {
		return;
	}

	 ###########################
	# Prints out a search field
	function print_search_field($prefix,&$default,$format,$not_null) {
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
	}

	/**
	* Overwrite the get_attribute_type_xtras function cause only this attribute type can tell where he's
	* stashing his xtras (EVERY ATTRIBUTE THAT HAS XTRAS MUST HAVE THIS FUNCTION)
	*
	* @access  public
	* @returns &object
	*/
	function &get_attribute_type_xtras() {
		$xtras = &$this->temp['xtras'];
		if(get_class($xtras) != 'xtrasregistry') {
			$xtras = new XtrasRegistry(dirname(__FILE__).'/operator', get_class($this));
		}
		return $xtras;
	}


	/**
	* Evaluates this attributes equation and returns the value
	*
	* @param   mixed Some values
	* @access  public
	* @returns mixed
	*/
	function evalute_equation($value) {
		# Clear out any potential results of a previous equations evaluations
		$this->temp['operator_results'] = array();
		$operators = $this->get_attribute_xtras();
		$returning_value = null;
		if (!empty($operators)) {
			$value = substr($value, 1);
			$options = array('recordid' => $value);
			$operatorid = end($operators);
			$operator = &$this->get_attribute_type_xtra($operatorid);
			if ($operator->id) {
				$operator_type = &$operator->get_type_handler();
				$cast_type = $operator_type->get_value('cast_type');
				# Values of previous operators evaluation. Currently nothing.
				$input = array();
				$returning_value = $operator_type->evaluate($input, $options);
			}
		}
		return $returning_value;
	}

	/**
	* Prints our equation visually
	*
	* @access  public
	* @returns void
	*/
	function print_our_equation() {
		$operators = $this->get_attribute_xtras();
		if (!empty($operators)) {
			?>
			<script language="javascript">
				
				/* Returns a new array object containing references to the same contents (ie does not recurse) */
				function arrayCopy(source) {
					var res = new Array(source.length);
					for (var i=0; i<source.length; i++) {
						res[i] = source[i];
					}
					return res;
				}
				
				function removeTextKids(elt)
				{
					var kids = elt.childNodes;
					for (var i=0; i<kids.length; )
					{
						if (kids[i].nodeType == 3)
							elt.removeChild(elt.childNodes[i]);
						else
							i++;
					}
				}

				
				/* Swaps the operator data in the given rows, incl the hidden ID fields
				 * precondition: first_row_no < second_row_no	*/
				function swap_operator_rows(first_row_no, second_row_no)
				{
					var first_row = document.getElementById("operator_row_"+first_row_no);
					var second_row = document.getElementById("operator_row_"+second_row_no);
					// clean up the mess that Mozilla makes
					removeTextKids(first_row);
					removeTextKids(second_row);

					var second_row_children = arrayCopy(second_row.childNodes);
					var first_row_children = arrayCopy(first_row.childNodes);
					// remove the cells from their old places
					for (var i=2; i<5; i++) { // data in the 3rd, 4th and 5th columns will be swapped
						first_row.removeChild(first_row_children[i]);
						second_row.removeChild(second_row_children[i]);
					}
					// put the cells in their new places
					for (var i=2; i<5; i++) { // data in the 3rd, 4th and 5th columns will be swapped
						first_row.appendChild(second_row_children[i]);
						second_row.appendChild(first_row_children[i]);
					}
					// swap the ID fields
					var first_id = document.getElementById("operator_at[" + first_row_no + "]");
					var second_id = document.getElementById("operator_at[" + second_row_no + "]");
					var temp_value = first_id.value;
					first_id.value = second_id.value;
					second_id.value = temp_value;
				}

				function move_operator_up(order_no) {
					if (document.getElementById("operator_row_"+ (order_no - 1)) == null) {
						return false;
					}
					swap_operator_rows(order_no, order_no-1);
					// mark the fact that we've reordered the operators
					document.edit.order_changed.value='true';
					return false;
				}

				function move_operator_down(order_no) {
					if (document.getElementById("operator_row_"+ (order_no + 1)) == null) {
						return false;
					}
					swap_operator_rows(order_no+1, order_no);
					// mark the fact that we've reordered the operators
					document.edit.order_changed.value='true'
					return false;
				}

			</script>
			<input type="hidden" name="del_operator" />
			<input type="hidden" name="dupe_operator" />
			<input type="hidden" name="order_changed" />
			<table bgcolor="#888888" cellpadding="0" cellspacing="0" border="0"><tr><td>
			<table cellspacing="1" cellpadding="3" border="0" id="operators_table">
			<tr bgColor="#aaaaaa">
				<td noWrap></td>
				<td><b>Order</b></td>
				<td><b>Operator</b></td>
				<td><b>Cast Type</b></td>
				<td><b>Process</b></td>
			</tr>
			
			<tr bgColor="#dddddd"><td colspan="5" height="10"></td></tr>
			<?
			$active_operator_id = $this->get_active_xtraid();
			foreach($operators as $operatorid) {
				$operator = &$this->get_attribute_type_xtra($operatorid);
				$operator_type_handler = &$operator->get_type_handler();
				$cast_type = $operator_type_handler->get_value('cast_type');
				if (!$cast_type) {
					$cast_type = 'Cast Type Must Be Set';
				}
				$order_no = $operator->get_order_no();
				$operator_name = $operator->get_name();
				$operator_type = $operator->get_type();
				$operators_inputs = $operator_type_handler->get_input_operatorids();
				$operators_comments = $operator_type_handler->get_summary_comments();
				if (empty($operators_inputs)) {
					$inputer_string = 'No Inputs';
				} else {
					$inputers_names = array();
					foreach($operators_inputs as $input_operatorid) {
						$input_operator = &$this->get_attribute_type_xtra($input_operatorid);
						if ($input_operator->id) {
							$inputers_names[] = $input_operator->get_name();
						} else {
							$inputers_names[] = '<b>Input Operator Missing! Operator should be reset!</b>';
						}
					}
					$inputer_string = implode(' , ', $inputers_names);
				}
				$js_operator_name = str_replace('\'', '\\\'', $operator_name);
				$bg_color = ($operatorid == $active_operator_id ? "#ffffff" : "#eeeeee");
				$title = $operatorid == $active_operator_id ? "This is operator you are currently editing" : "";
				?>
					<tr id="operator_row_<?=$order_no?>">
					<td noWrap bgColor="#aaaaaa">

					<a href="javascript: document.edit.active_xtraid.value=document.getElementById('operator_at[' + new String(<?=$order_no?>) + ']').value; document.edit.action.value='Commit'; document.edit.submit();" onmouseover="javascript: window.status='Edit Attribute <?=$js_operator_name?> id=<?=$operatorid?>'; return true;" onmouseout="javascript: window.status='';">
					<img src="images/icons/create.gif" alt="Edit Operator" border=0></a>

					<a href="javascript: document.edit.del_operator.value=document.getElementById('operator_at[' + new String(<?=$order_no?>) + ']').value; document.edit.action.value='Commit'; document.edit.submit();"
					onmouseover="javascript: window.status='Delete Attribute <?=$js_operator_name?> id=<?=$operatorid?>'; return true;" onmouseout="javascript: window.status='';">
					<img src="images/icons/delete.gif" alt="Delete Operator" border=0></a>

					<a href="javascript: document.edit.dupe_operator.value=document.getElementById('operator_at[' + <?=$order_no?> + ']').value; document.edit.action.value='Commit'; document.edit.submit();"
					onmouseover="javascript: window.status='Dupe Attribute <?=$js_operator_name?> id=<?=$operatorid?>'; return true;" onmouseout="javascript: window.status='';">
					<img src="images/icons/dupe.gif" alt="Dupe Operator" border=0 ></a>

					<input src="images/uparrow.gif" name="Move Up" border="0" alt="Move Up" type="image" onclick="javascript: move_operator_up(<?=$order_no?>); return false; "
					onmouseover="javascript: window.status='Move this operator up'; return true;" onmouseout="javascript: window.status='';" />

					<input src="images/downarrow.gif" name="Move Down" border="0" alt="Move Down" type="image" onclick="javascript: move_operator_down(<?=$order_no?>); return false;"
					onmouseover="javascript: window.status='Move this operator down'; return true;" onmouseout="javascript: window.status='';" />

					</td>
					<td bgColor="#aaaaaa" class=backend_fineprint name="order_no" style="text-align: center;">
					<?=$order_no?>
					<input type="hidden" id="operator_at[<?=$order_no?>]" name="operator_at[<?=$order_no?>]" value="<?=$operatorid?>">
					</td>
					
					<td bgColor="<?=$bg_color?>" title="<?=$title?>" class=backend_fineprint name="operator_name"><?=$operator_name?></td>
					
					<td bgColor="<?=$bg_color?>" title="<?=$title?>" class=backend_fineprint name="cast_type">(<i><?=$cast_type?></i>)</td>
					
					<td bgColor="<?=$bg_color?>" title="<?=$title?>" class=backend_fineprint name="description">
						<?=$operator_type?>(<?=$inputer_string?>)
						<p style="font-size: 70%; margin: 0px;">
						<?=(!empty($operators_comments) ? $operators_comments : '&nbsp;')?>
						</p>
					</td>
				</tr><?
			}
			?><tr bgColor="#dddddd"><td colspan="5" height="10"></td></tr><?
			?><tr bgColor="#eeeeee"><td colspan="2"><b><?=$this->attribute->name?></b></td><td colspan="2"><b> = </b></td><td><?=$operator_name?></td></tr><?
			?></table>
			</td></tr></table><?
			# The last value is the result of the equation
		} else {
			echo '<b>There are no operators setup.</b>';
		}
	}

	/**
	* Processes our equation
	*
	* @access  public
	* @returns void
	*/
	function process_our_equation() {
		# maybe the order was changed and then something else was done, so we process any order changes first
		if ($_POST['order_changed']) {
			$i = 1;
			while (isset($_POST['operator_at'][$i])) {
				$attribute_xtra = &$this->get_attribute_type_xtra($_POST['operator_at'][$i]);
				$attribute_xtra->set_order_no($i, true, false);
				$i++;			
			}
		}
		if ($_POST['dupe_operator']) {
			$n = &$this->get_notitia_system();
			$attribute_xtra = &$this->get_attribute_type_xtra($_POST['dupe_operator']);
			if ($attribute_xtra->id) {
				$new_xtraid = $n->create_attribute_type_xtra($attribute_xtra->type, $attribute_xtra->name, $this->id);
				$attribute_xtra->dupe($new_xtraid, array(), false);
			}
		} elseif ($_POST['del_operator']) {
			$attribute_xtra = &$this->get_attribute_type_xtra($_POST['del_operator']);
			if ($attribute_xtra->id) {
				$attribute_xtra->set_delete(true);
			}
		}

	}
}
?>