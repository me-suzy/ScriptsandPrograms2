<?php
/**
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      not_documented_yet
*/

   /**
    *
    * @version      $Id: class.datarow.php,v 1.7 2005/08/06 06:57:08 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      not_documented_yet
    */
require_once ('class.input.php');
require_once ('class.textarea.php');
require_once ('class.hidden.php');
require_once ('class.select.php');

class datarow {

	var $dr_arr	= null; // Datarow Array	
	var $db_hdl = null; // Database Handler
	
	function datarow ($db_hdl) {
		$this->db_hdl = $db_hdl;
	}
	
	function setWidget ($j, $name, $widget) {
		$numargs  = func_num_args();
		$arg_list = func_get_args();
    	switch($numargs){
			case 3: 
				$this->dr_arr[$j]['widget'] = 
					new $widget ($name, $this->dr_arr[$j]['initial_data']);
				break;
			case 4: 
				$this->dr_arr[$j]['widget'] = 
					new $widget ($name, $this->dr_arr[$j]['initial_data'], 
						$arg_list[3]);
				break;
			case 5: 
				$this->dr_arr[$j]['widget'] = 
					new $widget ($name, $this->dr_arr[$j]['initial_data'], 
						$arg_list[3], $arg_list[4]);
				break;
			default:
				die ("too many arguments");
		} // switch
		$this->dr_arr[$j]['widgetclass'] = $widget;
		if ($widget == 'hidden') {
		    $this->dr_arr[$j]['visible'] = false;
		}
		/*if ($attributes != null) {
		    $this->dr_arr[$j]['widget']->setAttributes ($attributes);
		}*/
	}
		
	function setEditable ($j, $bool) {
		$this->dr_arr[$j]['editable'] = $bool;		
	}
	
	function FetchData ($query) {
		$this->query  = $query;	

		$arr =& $this->dr_arr;
		$rs = $this->db_hdl->Execute($this->query);
		if ($this->db_hdl->ErrorMsg() != null) {
		    echo $this->db_hdl->ErrorMsg();
		}
		assert ('$this->db_hdl->ErrorMsg() == ""');		
		$cnt    = $rs->FieldCount();
		
		// Assert that there is exactly one row retrieved!!!
		
		//$field = $rs->FetchField($j);
		//$col = new column ($j, $field->name, $this->caller_script, $j);

		$i=0;
		while (!$rs->EOF && $i<1) {			
			for ($j=0; $j < $cnt; $j++) {
				$field = $rs->FetchField($j);
				$fieldname = $field->name;
				($fieldname != "") ? $key = $fieldname : $key = $j;
				$arr[$key]['initial_data'] = $rs->fields[$j];
				$arr[$key]['result_data']  = $rs->fields[$j];
				$arr[$key]['title']        = $field->name;
				$arr[$key]['visible']      = true;
				$arr[$key]['editable']     = true;
				$arr[$key]['widget']       = null;
				$arr[$key]['widgetclass']  = null;
				
				if ($arr[$key]['result_data'] == "") {
				    $arr[$key]['result_data'] = "&nbsp;";
				}
			}
			$i++;
			$rs->MoveNext();
		}	
	}

	function PrepareNew ($fields_arr) {

		$arr =& $this->dr_arr;
		//$cnt    = count($fields_arr);
		//for ($j=0; $j < $cnt; $j++) {
		$j=0;
		foreach ($fields_arr AS $key => $value) {
			$arr[$j]['initial_data'] = $value;
			$arr[$j]['result_data']  = $value;
			$arr[$j]['title']        = $key;
			$arr[$j]['visible']      = true;
			$arr[$j]['editable']     = true;
			$arr[$j]['widget']       = null;
			$arr[$j]['widgetclass']  = null;
			$j++;
		}		
	}
	
	function getHTML () {
		
		$dr_arr =& $this->dr_arr;		
		$ret = "<table border=1>\n";

		//for ($i=0; $i < count ($dr_arr); $i++) {
		foreach ($dr_arr AS $key => $value) {
			if (!$dr_arr[$key]['visible']) continue;
			$ret .= "<tr><td><b>".$dr_arr[$key]['title']."</b></td>\n";
			if ($dr_arr[$key]['widget'] != null) {
				$widget = $dr_arr[$key]['widget'];
				$ret .= "<td>".$widget->getHTML()."</td></tr>\n";				    
			}
			else {
				$ret .= "<td>".$dr_arr[$key]['result_data']."</td></tr>\n";	
			}
		}
		$ret .= "\n</table>\n";
		// Alle hidden fields
		//for ($i=0; $i < count ($this->dr_arr); $i++) {
		foreach ($dr_arr AS $key => $value) {
			if ($dr_arr[$key]['widgetclass'] == 'hidden') {
				$widget = $dr_arr[$key]['widget'];
				$ret .= "\n".$widget->getHTML()."\n";				    
			}
		}
		return $ret;
	}
	
}

?>
