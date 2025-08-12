<?php

/**
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      not_documented_yet
*/

   /**
    *
    * @version      $Id: class.datarow.php,v 1.6 2005/08/06 06:57:08 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      not_documented_yet
    */
class datarow {

	var $data             = null;	
	var $meta             = null;
	var $db_conn          = null;
	var $keys             = array();
	var $vals             = array();
	
	//var $meta_info_exists = true;
	
	function datarow ($query, $statement) {
		$this->db_conn = newADOCOnnection (ADODB_VENDOR);
        $this->db_conn->Connect (ADODB_HOST, ADODB_USER, ADODB_PASSWD, "easy_portal");
				
		// Metaquery
		//$this->db_conn->debug = true;
		$this->meta    = $this->db_conn->Execute("
						SELECT * FROM datarows 
						JOIN statements ON datarows.statement = statements.id
						WHERE statements.meaning='".$statement."' 
						ORDER BY column_order");
		//echo $this->meta->RecordCount();
		if ($this->meta->EOF) {
			// Metainformationen aus DB selbst auslesen
			$this->meta = null;
		}
		
		// Data
		$this->db_conn->SetFetchMode(ADODB_FETCH_NUM);
		$this->data = $this->db_conn->Execute ($query);
	}
	
	function dump_data () {
		
		$this->keys = array ();
		$this->vals = array ();
		
		
		if ($this->meta != null) {
			$rs =& $this->meta;
			while (!$rs->EOF) {
				$column_cnt = count ($this->columns);
				$this->columns[$column_cnt]['title']     = $rs->fields['column_title'];
				$this->columns[$column_cnt]['datatype']  = $rs->fields['column_datatype'];
				$this->columns[$column_cnt]['formatter'] = $rs->fields['column_formatter'];
				$this->columns[$column_cnt]['args']      = $rs->fields['column_formatter_args'];
				$rs->MoveNext();		
			} 
			//print_r ($this->columns);
		}
		else {
			$rs =& $this->data;
			$cnt   = $rs->FieldCount();
			for ($i=0; $i < $cnt; $i++) {
				$key_cnt = count ($this->keys);
				$field = $rs->FetchField($i);
				$this->keys[$key_cnt]['title']     = $field->name;
				$this->keys[$key_cnt]['datatype']  = null;
				$this->keys[$key_cnt]['formatter'] = null;
				$this->keys[$key_cnt]['args']      = null;
			} 
		}

		$rs =& $this->data;
		
		while (!$rs->EOF) {
			$cnt   = $rs->FieldCount();
			for ($i=0; $i < $cnt; $i++) {
				$val_cnt = count ($this->vals);
				$this->vals[$val_cnt]['data']      = $rs->fields[$i];
				$this->vals[$val_cnt]['datatype']  = null;
				$this->vals[$val_cnt]['formatter'] = null;
				$this->vals[$val_cnt]['args']      = null;
			} 
			$rs->MoveNext();
		}
		
		
		$ret = "<table border=1>\n";
		for ($i=0; $i < count ($this->vals); $i++) {
			$ret .= "<tr><td><b>".$this->keys[$i]['title']."</b></td>\n
			         <td>".$this->vals[$i]['data']."</td></tr>\n";	
		}
		$ret .= "\n</table>\n";
		
		
		return $ret;
	}
	
}

?>
