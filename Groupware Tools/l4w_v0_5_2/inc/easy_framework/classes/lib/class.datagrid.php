<?php

/**
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      not_documented_yet
*/

   /**
    *
    * @version      $Id: class.datagrid.php,v 1.11 2005/08/06 06:57:08 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      not_documented_yet
    */
class datagrid {

	var $data             = null;	
	//var $meta             = null;
	//var $db_conn          = null;
	var $columns          = array(); // of class.columns
	
	var $sess_ident       = "datagrid";
	var $dg_arr           = null;
	
	var $filter           = null;
	
	var $query            = null;
	var $order            = null;
	var $where            = null;
	//var $groupby          = null;
	//var $having           = null;
	
	var $pagesize         = 10;
	var $pagenr           = 0; // first page = 0
	var $caller_script    = null;
	var $add_rows         = array ();
	var $hits_wo_limit    = 0;    // number of found rows without using limit

	var $is_admin         = false;
	
	var $row_class_schema = array ("dg_row_even", "dg_row_odd");
	var $row_style_func   = null;
	
	var $omitt_order      = false;
	var $omitt_limit      = false;
	
	var $searchValue      = " Suche ";
	var $TRonMouseOver    = "";
	var $TRonMouseOut     = "";
	var $TRonDblClick     = '';
	var $TRonClick        = '';
	
	var $cachetime        = 0;
	
	var $translations;
	
	function datagrid ($pagesize = 10, $session_identifier, $caller_script) {
		$this->pagesize      = $pagesize;
		$this->caller_script = $caller_script;
		$this->sess_ident    = $session_identifier;
	}

    function sortdata ($a, $b, $c) {

        if (is_null($this->order)) return 0;
        $order_index     = $this->order[0];
        $order_direction = $this->order[1];
        echo $a[$order_index]." / ".$b[$order_index]."<br>"; 
        if ($a[$order_index] == $b[$order_index]) return 0;
        if ($order_direction = "ASC")
            return ($a[$order_index] < $b[$order_index]) ? -1 : 1;
        else
            return ($a[$order_index] < $b[$order_index]) ? 1 : -1;
    }

	function datagrid_from_2dim_array ($spreadsheet, $caller_script, $first_line_is_headline) {

        $headline = array ();
        $data     =& $spreadsheet;
        if ($first_line_is_headline) {
		    assert ('count($spreadsheet[0]) == count($spreadsheet[1])');
            $headline = array_shift ($data);
    		$this->hits_wo_limit = count ($spreadsheet)-1;
    		for ($j=0; $j < count($headline); $j++) {
				$col = new column ($j, $headline[$j], "", $caller_script);
                $this->columns[] = $col;		    
    		}
        }
        else {
       		$this->hits_wo_limit = count ($spreadsheet);
    		for ($j=0; $j < count($headline); $j++) {
					$col = new column ($j, "col#".$j, "col#".$j, "users.php");
					$this->columns[] = $col;		    				    
    		}
        }


        if ($this->order[0] > 0) {
            // workaround, cannot pass third argument to sortdata
            // (for some reason)
            //foreach ($data
            usort ($data, array ("datagrid", "sortdata"));
        }
                        		
		$dummy = array ();
		$i=0;
		foreach ($data AS $key => $row) {
			$j=0;
			foreach ($row AS $key => $col) {
   				$dummy[$i][$j]['initial_data'] = $spreadsheet[$i][$j];
    			$dummy[$i][$j]['result_data']  = $spreadsheet[$i][$j];
	    		$dummy[$i][$j]['visible'] = true;
				$j++;
			}
			$i++;
		}
		$this->dg_arr =& $dummy;
	}
		
	function datagrid_from_adodb_query ($query, $params, $db_hdl, $calc_header = true) {
		
		// --- init -------------------------------------------------
		$arr    =& $this->dg_arr;
		$offset =  $this->pagenr * $this->pagesize;  
		//$db_hdl->debug = true;
		
		// --- parse query ------------------------------------------
		$parser = new simple_sqlparser ($query, $this->filter);
        if ($this->omitt_order) {
    		$parser->omitt_order();
    	}
    	else {
    		list ($order_col, $order_direction) = $this->order;
	    	$parser->set_order($order_col,$order_direction);
        }
        //die ($parser->dump ());

		// --- filter functionality ---------------------------------
		$searchbox_values = array ();
		foreach ($params AS $key => $value) {
			$key = urldecode($key);
			if (substr ($key,0,10) == "searchbox_") {
				$_SESSION['easy_datagrid'][$this->sess_ident]['Searchbox'][substr($key,10)] = $value;
				$_SESSION['easy_datagrid'][$this->sess_ident]['Searchbox_colname'][$params['mapping_'.str_replace (".", "%2E", substr($key,10))]] = $value;
			}
			elseif (substr ($key,0,13) == "selectionBox_") {
				$_SESSION['easy_datagrid'][$this->sess_ident]['SelectionBox'][substr($key,13)] = $value;
				$_SESSION['easy_datagrid'][$this->sess_ident]['SelectionBox_colname'][$params['mapping_'.str_replace (".", "%2E", substr($key,13))]] = $value;
			}
		}

		if (isset($_SESSION['easy_datagrid'][$this->sess_ident]['Searchbox']) && 
		    $_SESSION['easy_datagrid'][$this->sess_ident]['Searchbox'] != null) {
			foreach ($_SESSION['easy_datagrid'][$this->sess_ident]['Searchbox'] AS $key => $value) {
				if (trim ($value) != "") {
					$parser->add_where_clause ($key." LIKE '%".mysql_escape_string($value)."%'");			    
				}
			}
		}

		if (isset($_SESSION['easy_datagrid'][$this->sess_ident]['SelectionBox']) && 
		    $_SESSION['easy_datagrid'][$this->sess_ident]['SelectionBox'] != null) {
			foreach ($_SESSION['easy_datagrid'][$this->sess_ident]['SelectionBox'] AS $key => $value) {
				if (trim ($value) != "") {
					$parser->add_where_clause ($key." LIKE '%".mysql_escape_string($value)."%'");			    
				}
			}
		}
		
		// --- Substitutes? -----------------------------------------
		foreach ($_POST AS $key => $postvar) {
			if (substr($key, 0,4) == "pre_") {
			    $parser->substitute ('~'.substr ($key,4).'~', $postvar);
			}
		}
		
		// --- get query to use -------------------------------------
		$this->query = $parser->get_query();
		//$executed_query = $this->query; 

		if (!$this->omitt_limit) {
			if ($this->cachetime > 0) {
				$rs = $db_hdl->CacheSelectLimit($this->cachetime, $this->query, $this->pagesize,$offset);			
			}
			else
				$rs = $db_hdl->SelectLimit($this->query, $this->pagesize,$offset);
		}
		else {
			if ($this->cachetime > 0) {
				$rs = $db_hdl->CacheExecute ($this->cachetime, $this->query);
			
			}
			else
				$rs = $db_hdl->Execute ($this->query);
		}

        // --- Error Handling ---------------------------------------		
		if ($db_hdl->ErrorMsg() != null && $db_hdl->ErrorMsg() != "ORA-00000: Normaler, erfolgreicher Abschluss") {
		    echo $db_hdl->ErrorMsg();
		    echo "<br><br><pre>".$this->query."</pre>";
		}
		// Oracle liefert: ORA-00000: Normaler, erfolgreicher Abschluss 
		//assert ('$db_hdl->ErrorMsg() == ""');		
		$cnt    = $rs->FieldCount();
		
		// How many rows are there (without limit clause)?
		$parser->omitt_order();
		$after_from_str = stristr ($parser->get_query()," FROM ");
		$num_of_rows_query = "SELECT COUNT(1)".$after_from_str; //.' '.$where;
		
		//echo $num_of_rows_query;
		if ($this->cachetime > 0) 
			$num_of_rows_rs = $db_hdl->CacheExecute ($num_of_rows_query);			
		else
			$num_of_rows_rs = $db_hdl->Execute ($num_of_rows_query);
		
		$this->hits_wo_limit = $num_of_rows_rs->fields[0];
		if ($calc_header) { // meta info berechnen
			for ($j=0; $j < $cnt; $j++) {
				$field = $rs->FetchField($j);

                $sess      =& $_SESSION['easy_datagrid'][$this->sess_ident];
                $box_value = "";
                if (isset ($sess['Searchbox_colname'][$field->name]))
					$box_value = $sess['Searchbox_colname'][$field->name]; 
                elseif (isset ($sess['SelectionBox_colname'][$field->name]))
					$box_value = $sess['SelectionBox_colname'][$field->name]; 
									
				$col = new column ($j, $field->name, $box_value, $this->caller_script);
				
				// --- name must be unique! ---
				foreach ($this->columns AS $key => $column) {
				    if ($column->name == $field->name)
                        echo "Not unique: ".$field->name;				        
				    assert ('$column->name != $field->name');
				}
				    
				$this->columns[] = $col;
				if (isset($order_col)) {
					if (($j+1) == $order_col) $this->columns[$j]->setOrder($order_direction);	
				}
			}
		}	

		$i=0;
		while (!$rs->EOF) {			
			for ($j=0; $j < $cnt; $j++) {
				$arr[$i][$j]['initial_data'] = $rs->fields[$j];
				$arr[$i][$j]['result_data']  = $rs->fields[$j];
				$arr[$i][$j]['visible']      = true;
				
				// Format handling:
				/*$fld = $rs->FetchField($j);
				$fld_type = $rs->MetaType($fld->type);
				if ( $fld_type == 'D' || $fld_type == 'T') { 
					echo "org: ".$arr[$i][$j]['initial_data'].", conv: ".$rs->UserDate($rs->fields[$j],'m/d/Y')."<br>";
					$arr[$i][$j]['result_data'] = "#"; //$rs->UserDate($rs->fields[$j],'m/d/Y H:i:s');
				}*/
			}
			$i++;
			$rs->MoveNext();
		}	
	}
	
	/*function datagrid_from_db ($query,
							   $stmt_id, 
							   $params, 
							   $db_hdl, 
							   $tablespace = "datagrid",
							   $is_admin   = false) {

		$this->is_admin = $is_admin;
		$this->stmt_id  = $stmt_id;	
		if (is_null($query == null)) {	
			$query_str = "SELECT statement FROM $tablespace.statements
						  WHERE id=$stmt_id";
			//$db_hdl->debug=true;
			$rs        = $db_hdl->Execute($query_str);
			$query     = $rs->fields[0];
		}

		$this->datagrid_from_adodb_query ($query, $params, $db_hdl);
	    
	    $use_db_hdl = newADOCOnnection (DB_VENDOR);
        $use_db_hdl->Connect (DB_HOST, DB_USER, DB_PASSWD, DB_DATABASE);
		
		$query_str = "SELECT 
		                col_id, title, width, 
		                class, searchable, hidden,
		                showSearchButton, visualize
					  FROM $tablespace.cols 
					  WHERE query_id=$stmt_id";
		//$use_db_hdl->debug=true;
		$rs        = $use_db_hdl->Execute($query_str);
		$i=0;
		$cols_arr  = array ();
		while (!$rs->EOF) {			
			$this->columns[$rs->fields['col_id']]->setColumnId    ($rs->fields[0]);
			$this->columns[$rs->fields['col_id']]->setColumnTitle ($rs->fields[1]);
			$this->columns[$rs->fields['col_id']]->setColumnWidth ($rs->fields[2]);
			$this->columns[$rs->fields['col_id']]->setColumnClass ($rs->fields[3]);
			// !!! error
			if ($rs->fields['hidden'] != 0) {
				$this->columns[$rs->fields['col_id']]->setIsVisible (false);
			}
			if ($rs->fields['searchable'] == 0) {
				$this->columns[$rs->fields['col_id']]->setIsSearchable (false);
			}
			else { 
				$this->columns[$rs->fields['col_id']]->setIsSearchable (true);
			}
			
			if ($rs->fields['showSearchButton'] != 0) {
			    $this->columns[$rs->fields['col_id']]->setShowSearchButton(true);
			}
			if ($rs->fields['visualize'] != '') {
			    $this->columns[$rs->fields['col_id']]->visualize = $rs->fields['visualize'];
			}
			$i++;
			$rs->MoveNext();
		}	
		return $this->columns;
	}*/


	function setOrder ($col, $kind_of) {
		$this->order = array ($col, $kind_of);
	}
		
	function setPage ($pagenr) {
		$this->pagenr = $pagenr;
	}

	function setPagesize (&$size) {
		$size = (int) $size;
		if (!$size > 0)
			$size = 15;
		$this->pagesize = $size;
	}

	function setColumnWidth ($pos, $width) {
	    if (!isset ($this->columns[$pos])) 
	        echo $pos." not defined in ".__FILE__.", line ".__LINE__;
		$this->columns[$pos]->setColumnWidth($width);	
	}
	
	function setColumnStyle ($pos, $style) {
		$this->columns[$pos]->setColumnStyle($style);	
	}
	
	function setColumnClass ($pos, $css_class) {
		$this->columns[$pos]->setColumnClass($css_class);	
	}

	function setColumnTitle ($pos, $title) {
	    //if (!@function_exists($this->columns[$pos]->setColumnTitle)) 
	    //    echo $pos."  not defined in ".__FILE__.", line ".__LINE__;
		$this->columns[$pos]->setColumnTitle ($title);
	}
	
	function setPrimary ($pos, $bool = true) {

        // there can only be one primary column which is set to true
        if ($bool) {
            foreach ($this->columns AS $key => $column) {
                $column->primary = false;
            }       
        }
        
		$this->columns[$pos]->primary = $bool;
	}
	
	function getPrimaryValues () {
	    
	    $arr     =& $this->dg_arr;
        $columns =& $this->getColumns();

        $primaryPos = null;
        for ($p=0; $p < count ($columns); $p++) {
            if ($columns[$p]->primary) {
                $primaryPos = $p;   
            }         
        } 
        
        if (is_null($primaryPos)) return null; 
             
    	//if (isset($arr[0])) $fields_cnt = count($arr[0]); 
        $ret   = array ();
    	$line  = 0;
    	
	    if ($arr != null) {
		    foreach ($arr AS $key => $row) {
			    $ret[] = $row[$primaryPos]['initial_data'];
	    	}	    
    	} 
	    return $ret;
	}	

	function setSearchable ($pos, $searchable, $name = null) {
		$this->columns[$pos]->setIsSearchable($searchable);
		if ($name != null)
			$this->columns[$pos]->name = str_replace (".", "%2E", $name);
	}
	
	function setSortable ($pos, $sortable, $name = null) {
		$this->columns[$pos]->setIsSortable($sortable);
	}

	function setSelectionBox ($pos, $bool, $choices, $name = null) {
		$this->columns[$pos]->setSelectionBox($bool, $choices);
		if ($name != null)
			$this->columns[$pos]->name = str_replace (".", "%2E", $name);
	}

	function setShowSearchButton ($pos, $bool) {
		$this->columns[$pos]->setShowSearchButton($bool);
	}
		
	function add_column ($pos, $data_arr) {
		$arr =& $this->dg_arr;
		for ($i=0;$i<count($arr); $i++) {
			$row =& $arr[$i];
			$tmp[0]['initial_data'] = $data_arr[$i];
			$tmp[0]['result_data']  = $data_arr[$i];
			array_splice ($row,$pos,0,$tmp);	
		}
	} 

    function add_filter ($where_clause) {
        $this->filter = $where_clause;    
    }
    	
	function add_calc_column ($pos, $func_str, $mixed = null) {

		$arr =& $this->dg_arr;
		for ($i=0;$i<count($arr); $i++) {
			$row =& $arr[$i];
			$value = call_user_func ($func_str, $row, $mixed);
			$tmp[0]['initial_data'] = $value;
			$tmp[0]['result_data']  = $value;
			$tmp[0]['visible']      = true;
			array_splice ($row,$pos,0,$tmp);
		}
		for ($j=$pos; $j < count ($this->columns); $j++) {
	        $this->columns[$j]->index++;
        }

		$col = new column ($pos, $func_str, "", null);						
		array_splice ($this->columns, $pos, 0, array($col));
	}

	function recalc_column ($pos, $func_str, $mixed = null) {
		$arr =& $this->dg_arr;
		for ($i=0;$i<count($arr); $i++) {
			$row =& $arr[$i];
			$value = call_user_func ($func_str, $row, $mixed);
			$row[$pos]['result_data']  = $value;
		}
	}
	
	function recalc_cell ($pos, $func_str, $mixed = null) {
		$arr =& $this->dg_arr;
		for ($i=0;$i<count($arr); $i++) {
			$row =& $arr[$i];
			$value = call_user_func ($func_str, $row[$pos], $mixed);
			$row[$pos]['result_data']  = $value;
		}
	}

	function remove_column ($pos) {
		$arr =& $this->dg_arr;
		for ($i=0;$i<count($arr); $i++) {
			$row =& $arr[$i];
			array_splice ($row,$pos,1);	
		}
	} 
	
	function swapColumnOrder ($pos1, $pos2) {

        if ($pos1 == $pos2) return;
        		
		// swap columns:
		$dummy = $this->columns[$pos1];
		//var_dump ($this->columns);
		$this->columns[$pos1] = $this->columns[$pos2];
		//var_dump ($this->columns);
		$this->columns[$pos2] = $dummy;
		//var_dump ($this->columns);
		
		// swap array
		$arr =& $this->dg_arr;
		for ($i=0;$i<count($arr); $i++) {
			$row    =& $arr[$i];
			$dummy  = $row[$pos1];
			$row[$pos1] = $row[$pos2];
			$row[$pos2] = $dummy;
		}
	}	
	
	function hide_column ($pos) {
		$this->columns[$pos]->setIsVisible (false);
		$arr =& $this->dg_arr;
		for ($i=0;$i<count($arr); $i++) {
			$arr[$i][$pos]['visible'] = false;
		}		
	}
	
	function getRow ($row) {
		$arr =& $this->dg_arr;
		$ret_arr = array ();
		foreach ($arr[$row] AS $key => $col)
			$ret_arr[] = $col['result_data'];
		//var_dump ($ret_arr);
		return $ret_arr;
			
	}
	
	function getCol ($col) {
		$arr =& $this->dg_arr;
		$ret_arr = array ();
		foreach ($arr AS $key => $row) {
			$ret_arr[] = $row[$col]['result_data'];
		}
		//var_dump ($ret_arr);
		return $ret_arr;
			
	}

    function getColumns () {
        return $this->columns;    
    }
    
    function add_first_row ($row) {
        $arr =& $this->dg_arr;          
            
        $new_row = array ();
        foreach ($row AS $key => $col) {
            $dummy = array (
                "initial_data" => $col,
                "result_data"  => $col,
                "visible"      => true
                );
            $new_row[] = $dummy;    
        }
        if (count($arr) > 0)
            array_unshift ($arr, $new_row);    
        else
            $arr[] = $new_row;
    }    
    	
	// Zunächst nur in letzter Position
	function add_free_row ($content) {
		$this->add_rows[] = $content;	
	}
	
	function set_row_style_func ($func) {
		$this->row_style_func = $func;	
	}

    function getNavLink ($i, $command, $add_params) {
        $ret = '';
        $ret .= "<a href='".$this->caller_script."?";
	    $ret .= "command=$command&pagenr=".($i-1);
	    $ret .= "&order=".$this->order[0];
		$ret .= "&direction=".$this->order[1];
		$ret .= "&entries_per_page=".$this->pagesize.$add_params."'>".$i."</a>&nbsp;";	
        return $ret; 
    }
    	
	function getNavigation ($command, $add_params = "") {
	    
	    $span = 10;
	    
		$ret = "";
		$from_nr = 1+($this->pagenr * $this->pagesize);
		$to_nr   = $this->pagesize + $from_nr-1;
		if ($to_nr > $this->hits_wo_limit) $to_nr = $this->hits_wo_limit;
		$ret .= "<b>[".$this->translations['hits'].": ".$this->hits_wo_limit."</b>]";
		$pages = ceil ($this->hits_wo_limit / $this->pagesize);
		if ($pages > 1) {
    		$ret .= "&nbsp;[";
			if ($this->pagenr >= 1) {
				$ret .= "<a href='".$this->caller_script."?";
				$ret .= "command=$command&pagenr=".($this->pagenr - 1);
				$ret .= "&order=".$this->order[0];
				$ret .= "&direction=".$this->order[1];
				$ret .= "&entries_per_page=".$this->pagesize.$add_params."'><</a>&nbsp;";	
			}
			else
				$ret .= "&nbsp;&nbsp;";
			for ($i=1; $i<=$pages; $i++) {
				if ($i == $this->pagenr+1)
					$ret .= "<b>".$i."</b>&nbsp;";	
				else {		
				    if ($pages < (2*$span)) 	     
                        $ret .= $this->getNavLink ($i, $command, $add_params);
		    		else {
		    		    if ($i == $this->pagenr - $span)
		    		        $ret .= "...&nbsp;";
		    		    elseif ($i >= ($this->pagenr - $span) && $i <= (1+ $this->pagenr + $span))
		    		        $ret .= $this->getNavLink ($i, $command, $add_params);
		    		    elseif ($i == (2 + $this->pagenr + $span))
		    		        $ret .= "&nbsp;...&nbsp;";
              
		    		}
				}
			}
			if ($this->pagenr < $pages-1) {
				$ret .= "<a href='".$this->caller_script."?";
				$ret .= "command=$command&pagenr=".($this->pagenr + 1);
				$ret .= "&order=".$this->order[0];
				$ret .= "&direction=".$this->order[1];
				$ret .= "&entries_per_page=".$this->pagesize.$add_params."'>></a>";	
			}
			else
				$ret .= "&nbsp;&nbsp;";
		$ret .= "]";
		}	
		$ret .= "&nbsp;";
		$ret .= "[".sprintf($this->translations['showing entries'],$from_nr, $to_nr)."]";
		$ret .= "&nbsp;[<input type=text class='per_page' name='entries_per_page' ";
		$ret .= "size=2 value='".$this->pagesize."'> ".$this->translations['per page']."]";
		$ret .= "</td>\n";
		return $ret;
	}
	
	function getHiddenFields () {
		// --- hidden fields -----------------------------------------------
		$ret = array ();
		for ($i=0; $i < count ($this->columns); $i++) {
			$ret[$i]['name']  = $this->columns[$i]->name;
			$ret[$i]['value'] = $this->columns[$i]->col_name;			
		}
		return $ret;
	}

	function getColGroup () {
		// --- colgroup ----------------------------------------------------
		$ret = array();
		$colspan = 0;
		for ($i=0; $i < count ($this->columns); $i++) {
			if ($this->columns[$i]->getIsVisible()) { 
				if ($this->columns[$i]->getColumnWidth() != null) {
				    $ret[$colspan]['width'] = $this->columns[$i]->getColumnWidth();
			    }
			    else {
				    $ret[$colspan]['width'] = '';
			    }
			    $colspan++;
			}
		}
		return array ($ret, $colspan);
	}
		
	function getHeadline ($command, $add_params) {
	    
	    $ret = array ();
	    $col = 0;
		for ($i=0; $i < count ($this->columns); $i++) {
		    //echo $this->columns[$i]->getColumnTitle().": ".$this->columns[$i]->getIsVisible()."<br>";
			$this_class = $this->columns[$i]->getColumnClass();
			$width = "";
			$order_sign = "&nbsp;&nbsp;";
			if ($this->columns[$i]->getIsVisible()) {
				if ($this->columns[$i]->getOrder() == "ASC") {
				    $order_sign = "&darr;";
				}
				if ($this->columns[$i]->getOrder() == "DESC") {
			    	$order_sign = "&uarr;";
				}
				$style = "";
				if ($this->columns[$i]->getColumnStyle() != null) 
					$style = " style='".$this->columns[$i]->getColumnStyle()."'";			    
				$add_params.="&entries_per_page=".$this->pagesize;
				$ret[$col]['class']      = $this_class;
				$ret[$col]['width']      = $width;
				$ret[$col]['style']      = $style;
				$ret[$col]['link']       = $this->columns[$i]->getLink($command, $add_params);
				$ret[$col]['order_sign'] = $order_sign;
			    $col++;
			}
		}
	    return $ret;
	}

	function getSearchRow () {

        $ret = array ();
        $col = 0;
		for ($i=0, $cnt = count($this->columns);$i < $cnt;$i++) {
			if ($this->columns[$i]->getIsVisible()) {
				if ($this->columns[$i]->getIsSearchable()) {
					$ret[$col]['name']  = $this->columns[$i]->getName();
					$ret[$col]['value'] = $this->columns[$i]->searchbox_value;
				    $ret[$col]['type']  = "searchbox";
				}
			    elseif ($this->columns[$i]->getShowSearchButton()) {
					$ret[$col]['name']  = "searchButton"; 
					$ret[$col]['value'] = $this->searchValue;
				    $ret[$col]['type']  = "button";
				}
				else {
					$ret[$col]['name']  = ""; 
					$ret[$col]['value'] = "";
				    $ret[$col]['type']  = "searchbox";
				}
				$col++;
			}
		}
		return $ret;
	}

	
	function getData () {
    	$arr =& $this->dg_arr;

        $ret  = array ();
    	$line = 0;

	    if ($arr != null) {
		    foreach ($arr AS $key => $row) {
				$column  = 0;
				$new_col = 0;
				foreach ($row AS $key => $col) {
		    		if ($this->columns[$column]->getIsVisible()) {
                        $ret[$line][$new_col] = $col;
	    			    $new_col++;
	    			}	
    			    $column++;
		    	}
			    $line++;
			}	    
    	} 
	    return $ret;
	}
	
	function getColumnID ($identifier) {
	
		$id = -1;
		//echo "I".$identifier;
		foreach ($this->columns AS $key => $col) {
			if ($col->name == $identifier) {
				$id = $col->index;
				break;	
			}
		}	
		if ($id == -1)
			echo "Did not find indentifier $identifier in ".__FILE__;
		return $id;
	}
	
	function cut_entry ($row, $param_list) {
		list ($col, $length) = $param_list;
		$ret = $row[$col]['initial_data'];	
		if (strlen($ret) > $length)
			return substr ($ret,0,$length-3)."...";
		return $ret;
	}
	
	function cell_link ($cell, $length) {
		$ret = $cell['initial_data'];	
		if (strlen($ret) > $length)
			return substr ($ret,0,$length-3)."...";
		return "*".$ret;
	}
	
	function getOrderFromQuery ($query) {
	    $parser   = new simple_sqlparser($query);
	    return $parser->getOrderDirection(); 
	}
	
    function setTranslations ($translations) {
	    $this->translations = $translations;
    }	

}

?>
