<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage field
 */

require_once('field.php');

class relationfield extends field {
	var $_combocols = array();
	var $_combocolsep = ' ';
	var $obj;

	var $_filter_category = FALSE;
	var $_filter_name = FALSE;
	var $_sort_colname = 'name';
	var $_sort_way = 'ASC';
	var $_filter_searchcolname = array();
	var $_filter_searchvalue = array();
	var $_filter_searchtype = array();
	var $_filter_advsearchcolname = array();
	var $_filter_advsearchvalue = array();
	var $_filter_advsearchtype = array();
	var $_listaccess = true;
	var $_forcevariant = true;
	var $_disablenone = false;

	function setfilter_category($value) {
		$this->_filter_category = $value;
	}

	function setfilter_name($value) {
		$this->_filter_name = $value;
	}
	
	function setfilter_search($colname, $value, $type = 0) {
		$this->_filter_searchcolname[] = $colname;
		$this->_filter_searchvalue[] = $value;
		$this->_filter_searchtype[] = $type;
	}

	function setfilter_advsearch($colname, $value, $type = 0) {
		$this->_filter_advsearchcolname[] = $colname;
		$this->_filter_advsearchvalue[] = $value;
		$this->_filter_advsearchtype[] = $type;
	}

	function setsort_col($colname) {
		$this->_sort_colname = $colname;
	}

	function setsort_way($s) {
		$this->_sort_way = $s;
	}

	function forceVariant($value = true) {
		$this->_forcevariant = $value;
	}

	function setFilters() {
		if ($this->_forcevariant) $this->obj->forceVariant();
		$this->obj->setlistaccess($this->_listaccess);
		$this->obj->setsort_col($this->_sort_colname);
		$this->obj->setsort_way($this->_sort_way);
		if ($this->_filter_category) $this->obj->setfilter_category($this->_filter_category);
		if ($this->_filter_name) $this->obj->setfilter_name($this->_filter_name);
		if (!empty($this->_filter_searchcolname)) {
			$this->obj->filter_searchcolname = $this->_filter_searchcolname;
			$this->obj->filter_searchvalue = $this->_filter_searchvalue;
			$this->obj->filter_searchtype = $this->_filter_searchtype;
		}
		if (!empty($this->_filter_advsearchcolname)) {
			$this->obj->filter_advsearchcolname = $this->_filter_advsearchcolname;
			$this->obj->filter_advsearchvalue = $this->_filter_advsearchvalue;
			$this->obj->filter_advsearchtype = $this->_filter_advsearchtype;
		}
	}
	
	function setListColSeparator($value) {
		$this->_combocolsep = $value;
	}
	
	function setListCols($cols) {
		if (is_array($cols)) {
			$this->_combocols = $cols;
		} else {
			$this->_combocols[0] = $cols;
		}
	}
	
	function _listAllObjects($type, $value, $emptynone = false) {
		$this->obj = owNew($type);

		if (!$this->_disablenone) {
			if (in_array('default',$this->obj->getviews())) {
				$std = true;
				$res = '<option value="0">'.$this->view->gl('select_standard').'</option>';
			} else {
				$std = false;
				if ($emptynone) {
					$res = '<option value="">'.$this->view->gl('select_none').'</option>';
				} else {
					$res = '<option value="0">'.$this->view->gl('select_none').'</option>';
				}
			}
		}

		$this->setFilters();
		$this->obj->listobjects();
		$z = 0;
		if (is_array($value)) {
			$selected = array();
			$unselected = array();
			while ($z < $this->obj->elementscount) {
				if ($std && $this->obj->elements[$z]['standard'] == 1) {
					$standard = ' '.$this->view->gl('select_standard');
				} else {
					$standard = '';
				}

				if (in_array($this->obj->elements[$z]['objectid'],$value)) {
					$selected[$this->obj->elements[$z]['objectid']] = $this->obj->elements[$z]['name'] . $standard;
				} else {
					$unselected[$this->obj->elements[$z]['objectid']] = $this->obj->elements[$z]['name'] . $standard;
				}
				$z++;
			}
			
			foreach ($selected as $objectid=>$name) {
				$res .='<option value="' . $objectid . '" SELECTED>' . $name."\n";
			}
			foreach ($unselected as $objectid=>$name) {
				$res .='<option value="' . $objectid . '">' . $name."\n";
			}
	
		} else {
			while ($z < $this->obj->elementscount) {
				if ($std && $this->obj->elements[$z]['standard'] == 1) {
					$standard = ' '.$this->view->gl('select_standard');
				} else {
					$standard = '';
				}
				$selection = '';
				if (is_array($value)) {
					if (in_array($this->obj->elements[$z]['objectid'],$value)) $selection = ' SELECTED';
				} else {
				if ($this->obj->elements[$z]['objectid'] == $value) $selection = ' SELECTED';
			}
			$name = '';
			if (empty($this->_combocols)) {
				$name = $this->obj->elements[$z]['name'] . $standard;
			} else {
				foreach ($this->_combocols as $c) {
					if (!empty($name)) $name .= $this->_combocolsep;
					$tmp = owReadTextual($this->obj->elements[$z]['objectid'], $c);
					$name .= $tmp[0]['fieldrep'];
				}
			}
				$res .='<option value="' . $this->obj->elements[$z]['objectid'] . '"'.$selection.'>' .$name. "\n";
				$z++;
			}
		
		}
		return $res;
	}

	function events() {
		return '';
	}
		
	function formOut() {
					$s .= '<select validate="'.$this->_fieldvalidate.'" name="'.$this->_fieldname.'" id="'.$this->_fieldname.'" style="width:356px; '.$this->_fieldstyle.'" '.$this->events();
					if ($this->_fieldonchange) $s .= ' onchange="'.$this->_fieldonchange.'"';
					if ($this->_fieldonfocus) $s .= ' onfocus="'.$this->_fieldonfocus.'"';
					$s .= '>';
					$s .= $this->_listAllObjects($this->_fieldrelation, $this->_fieldvalue);
					$s .= '</select>';
/*
					if (isset($field['detailfield'])) {

						$jsstr = '
							if (typeof(document.metaform.'.$field['detailfield'].'.options) != \'undefined\' && typeof(' . $field['name'] . '_data) != \'undefined\') {
								var selectedValue = document.metaform.'.$field['name'].'.options[document.metaform.'.$field['name'].'.selectedIndex].value;
								var origvalue = document.metaform.'.$field['detailfield'].'.options[document.metaform.'.$field['detailfield'].'.selectedIndex].value;
								document.metaform.'.$field['detailfield'].'.options.length = 1;
								var values = '.$field['name'].'_data[selectedValue];

								var selectedIndex = 0;
								if (typeof(values) != \'undefined\' && values.length) {
									for (var i = 0; i < values.length; i++) {
										document.metaform.'.$field['detailfield'].'.options[document.metaform.'.$field['detailfield'].'.options.length] = new Option(values[i][1], values[i][0]);
										if (values[i][0] == origvalue) selectedIndex = i + 1;
									}
								}

								document.metaform.'.$field['detailfield'].'.selectedIndex = selectedIndex;
							}';
						$this->view->context->attachOnLoad('document.metaform.'.$field['name'].'.onchange');
						echo '<script type="text/javascript">
						document.metaform.'.$field['name'].'.onchange = function() {
	   					'.$jsstr.'
					   //if (foreignfield.onchange)
	   					//foreignfield.onchange();

						}
						</script>';
					}*/
		#return '<input type="text" validate="'.$this->_fieldvalidate.'" name="'.$this->_fieldname.'" id="'.$this->_fieldname.'" value="'.htmlspecialchars($this->getValueOutput()).'" style="width: 350px; '.$this->_fieldstyle.'">';
		if ($this->disabledOnValue() && $this->_fieldvalue != 0) {
			$s .= '<script type="text/javascript">';
			$s .= 'document.getElementById(\''.$this->_fieldname.'\').disabled = true;';
			$s .= '</script>';
		}
		return $s;
	}
	
	function listOut() {
		return owReadName($this->_fieldvalue);
	}
	
	function viewOut() {
		return owReadName($this->_fieldvalue);
	}
	
}

?>