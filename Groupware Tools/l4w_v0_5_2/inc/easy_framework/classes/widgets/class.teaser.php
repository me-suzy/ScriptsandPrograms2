<?php
/**
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      not_documented_yet
*/

   /**
    *
    * @version      $Id: class.teaser.php,v 1.8 2005/08/06 06:57:08 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      not_documented_yet
    */
class teaser {

	var $query            = null;
	var $db_hdl           = null;
	var $max              = null;
	var $dg_arr           = null;
	
	function teaser ($max) {
		$this->max = $max;
	}
		
	function teaser_from_adodb_query ($query, $db_hdl) {
		$this->query  = $query;
		$this->db_hdl = $db_hdl;

		$arr =& $this->dg_arr;
		
		//$db_hdl->debug = true;
		$rs     = $db_hdl->SelectLimit($query, $this->max, 0);
		$cnt    = $rs->FieldCount();
		
		$i=0;
		while (!$rs->EOF) {			
			for ($j=0; $j < $cnt; $j++) {
				$arr[$i][$j]['initial_data'] = $rs->fields[$j];
				$arr[$i][$j]['result_data']  = $rs->fields[$j];
				$arr[$i][$j]['visible']      = true;
			}
			$i++;
			$rs->MoveNext();
		}	
	}
	
	function recalc_column ($pos, $func_str) {
		$arr =& $this->dg_arr;
		for ($i=0;$i<count($arr); $i++) {
			$row =& $arr[$i];
			$value = call_user_func ($func_str, $row);
			$row[$pos]['result_data']  = $value;
		}
		
	}
	

	function getContent () {
		$arr =& $this->dg_arr;
		$ret = '<table border=0 cellspacing=0 cellpadding=2>';
//print_r ($arr);
		if (isset($arr[0])) $fields_cnt = count($arr[0]); 
		if ($arr != null) {
			$i    = 0;
			foreach ($arr AS $key =>  $row) {
				$j    = 0;
				foreach ($row AS  $key => $col) {
					if ($arr[$i][$j]['visible']) {
						$css_class = "teaser_".$j;
						$ret .= '<tr><td class="'.$css_class.'">'.$col['result_data']."</td></tr>\n";			
					}	
					$j++;
				}
				$i++;
			}	    
		}
		$ret .= "</table>";		
		return $ret;
	}
	
	
}

?>
