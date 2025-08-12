<?php

/**
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      not_documented_yet
*/

   /**
    *
    * @version      $Id: class.datagrid2html.php,v 1.13 2005/08/06 06:57:08 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      not_documented_yet
    */
class datagrid2html {
	
	var $datagrid         = null;
	var $translations     = array (
	                            "hits"            => "Hits",
	                            "showing entries" => "Showing entries from %d to %d",
	                            "per page"        => "per Page"
	                        );
	
	function datagrid2html (&$datagrid) {
	    $this->datagrid = $datagrid;
	}

	
	function echoHiddenFields () {

		$ret     = '';
		$columns = $this->datagrid->getColumns();
		for ($i=0; $i < count ($columns); $i++) {
			$ret .= "<input type=hidden name='mapping_".$columns[$i]->name."'";
			$ret .= " value='".$columns[$i]->col_name."'>\n";	
		}
		return $ret;
	}

	function echoColGroup ($show_all) {
	    
	    $columns = $this->datagrid->getColumns();
		$ret     = "<table class=\"datagrid\">";
		
		$ret .= "\n<colgroup>";
		$colspan = 0;
		for ($i=0; $i < count ($columns); $i++) {
			if ($show_all || $columns[$i]->getIsVisible()) { 
				if ($columns[$i]->getColumnWidth() != null) 
					$ret .= '<col width="'.$columns[$i]->getColumnWidth().'">';
				else
					$ret .= '<col>';
				$colspan++;
			}
		}
		$ret .= "</colgroup>\n";
		return array ($ret, $colspan);
	}

    function echoNavigation ($colspan, $command, $add_params) {

		$ret = "<tr>\n";
		$ret .= '<td class="navigation" colspan='.$colspan.'>'.$this->datagrid->getNavigation($command,$add_params)."</td>\n";	
		$ret .= "</tr>\n";
        return $ret;
        
    }
	
	function echoHeadline ($show_all, $command, $add_params) {
	    
	    $columns = $this->datagrid->getColumns();
	    
	    if ($add_params != '' && substr ($add_params,0,1) != '&')   
	        $add_params = "&".$add_params;
	    
	    $ret = "<tr class='headline'>\n";
		for ($i=0; $i < count ($columns); $i++) {
			$this_class = $columns[$i]->getColumnClass();
			$width = "";
			$order_sign = "&nbsp;&nbsp;";
			if ($show_all || $columns[$i]->getIsVisible()) {
				if ($columns[$i]->getOrder() == "ASC") {
				    $order_sign = "&darr;";
				}
				if ($columns[$i]->getOrder() == "DESC") {
			    	$order_sign = "&uarr;";
				}
				$style = "";
				if ($columns[$i]->getColumnStyle() != null) 
					$style = " style='".$columns[$i]->getColumnStyle()."'";			    
				//$add_params .= "&entries_per_page=".$this->datagrid->pagesize;
				$ret .= '<th class="'.$this_class.'"'.$width.$style.'>'.$columns[$i]->getLink($command, $add_params)."&nbsp;".$order_sign."</th>\n";	
			}
		}
		$ret .= "</tr>\n";
	    return $ret;
	}

	function echoAdminHeadline ($show_all, $command, $add_params) {
	   
	   $columns =& $this->datagrid->getColumns();
	   
	   $ret = "<tr class='headline_admin'>\n";
		for ($i=0; $i < count ($columns); $i++) {
			$this_class = $columns[$i]->getColumnClass();
			$width      = "";
			if ($columns[$i]->getIsVisible()) {
				$style = "";
				if ($columns[$i]->getColumnStyle() != null) 
					$style = " style='".$columns[$i]->getColumnStyle()."'";	
						    
				$ret .= '<th>';
				$ret .= $columns[$i]->getAdminLink($command, $add_params)."&nbsp;";
	
	            $linktext = "[".$columns[$i]->getName()."]";
	            if (!$columns[$i]->getIsVisible())
	                $linktext = "<strike>$linktext</strike>";
	            $ret .= "<a href='".$columns[$i]->link2page."?command=$command&";
			    $ret .= "col_index=".$i."&layout=true";
			    $ret .= $add_params."'><br>".$linktext."</a>";
			
				$ret .= "</th>\n";	
			}
		}
		$ret .= "</tr>\n";
	    return $ret;
	}

	function echoSearchRow ($show_all) {

        $columns = $this->datagrid->getColumns();
        
		// --- Searchrow --------------------------------------------------
	    $ret = '';
		$show_search_row = false;
		$i = 0;
		while($i < count($columns)){
			if ($columns[$i++]->getIsSearchable()) {
			    $show_search_row = true;
				break;
			}
		} // while

		$searchrow_names = array ();
		if (($show_search_row)) {
			$ret .= "<tr>\n";
			for ($i=0, $cnt = count($columns);$i < $cnt;$i++) {
				if ($show_all || $columns[$i]->getIsVisible()) {
					$ret .= "<td class='searchrow'>";
					if ($columns[$i]->getIsSearchable()) {
						$val  = $columns[$i]->searchbox_value;
						$ret .= "<input type='text' value='".$val."'
									name='searchbox_".$columns[$i]->getName()."'
									class='searchbox_".$columns[$i]->getName()."'>";				    
						$searchrow_names[$i] = $columns[$i]->getName();
					}
					elseif ($columns[$i]->selectionBox) {
						$val  = $columns[$i]->searchbox_value;
						$ret .= "<select name='selectionBox_".$columns[$i]->getName()."'
									class='selectionBox_".$columns[$i]->getName()."'>";				    
						foreach ($columns[$i]->selectionChoice AS $key => $value) {
							($val == $key) ? $sel = " selected" : $sel = "";
							$ret .= "<option value='".$key."' $sel>".$value."</option>\n";	
						}	
						$ret .= "</select>\n";						
					}
					else {
						$ret .= "&nbsp;";
					}
					if ($columns[$i]->getShowSearchButton()) {
					    $ret .= "<input type=submit name='SearchButton' value='".$this->datagrid->searchValue."'>";
					}
					$ret .= "</td>\n";
				}
			}
			$ret .= "</tr>\n";
		}    
		return $ret;
	}
	
	function echoData ($show_all, &$arr, $admin = false) {
	    
        $columns = $this->datagrid->getColumns();

    	if (isset($arr[0])) $fields_cnt = count($arr[0]); 
        $ret   = '';
    	$line  = 0;
    	
    	$primary_col = null;
        //foreach ($columns AS $column) {
        for ($p=0; $p < count ($columns); $p++) {
            if ($columns[$p]->primary) {
                $primary_col = $p;        
                break;
            }
        }    

	    if ($arr != null) {
		    foreach ($arr AS $key => $row) {
			    $row_style = "";
			    if ($this->datagrid->row_style_func != null) {
			        //die ($this->row_style_func);
				    $row_style = call_user_func	($this->datagrid->row_style_func, $row);
			        $row_style = "style='".$row_style."'";
			    }
                
				$TRonMouseOver = '';
    			$TRonMouseOut  = '';
        		$TRonMouseDown = '';
        		$TRonClick     = '';
    	    	$TRonDblClick  = '';

                if (isset ($this->datagrid->TRonMouseOver))
    				$TRonMouseOver = str_replace ("~line~", $line, $this->datagrid->TRonMouseOver);
                if (isset ($this->datagrid->TRonMouseOut))
        			$TRonMouseOut  = str_replace ("~line~", $line, $this->datagrid->TRonMouseOut);
                if (isset ($this->datagrid->TRonMouseDown))
	        		$TRonMouseDown = str_replace ("~line~", $line, $this->datagrid->TRonMouseDown);
                if (isset ($this->datagrid->TRonClick))
	        		$TRonClick     = str_replace ("~line~", $line, $this->datagrid->TRonClick);
                if (isset ($this->datagrid->TRonDblClick))
	    	    	$TRonDblClick  = str_replace ("~line~", $line, $this->datagrid->TRonDblClick);

                $tr_id = '';
                if (!is_null($primary_col)) {
                    $tr_id = "id=".$row[$primary_col]['initial_data'];
                }    
			
		    	$ret .= "<tr $row_style $tr_id $TRonMouseOver $TRonMouseOut $TRonMouseDown $TRonClick $TRonDblClick>\n";
				$column = 0;
		
				foreach ($row AS $key=> $col) {
    				$this_class = $columns[$column]->getColumnClass();
	    			
		    		if ($show_all || $columns[$column]->getIsVisible()) {
		    			if (count($this->datagrid->row_class_schema) != 0)
			    			$this_class = $this->datagrid->row_class_schema[$line % count($this->datagrid->row_class_schema)];
				    	$style = "";
					    if (isset ($columns[$column])) {
						    if ($columns[$column]->getColumnStyle() != null) 
							    $style = " style='".$columns[$column]->getColumnStyle()."'";			    
					    }

						$ret .= '<td class="'.$this_class.'"'.$style.'>'.$col['result_data']."&nbsp;</td>\n";			
    					
	    			}	
	    			$column++;
		    	}
			    $ret .=  "</tr>\n";
			    $line++;
			}	    
    	} 
	    return $ret;
	}
		
	function echoAdditionalFreeRows () {
	    $ret = '';
	    // --- Additional free rows ---------------------------------------
		for ($i=0, $cnt = count($this->datagrid->add_rows); $i<$cnt; $i++) {
			$ret .= "<tr>";
			$ret .= $this->add_rows[$i];
			$ret .= "</tr>\n"; 
		}
		$ret .= "</table>\n";
	    return $ret;
	}
		
	function debug_dump_arr ($show_all, $command, $add_params = "") {
	    $this->datagrid->setTranslations ($this->translations);

		$arr =& $this->datagrid->dg_arr;
		$ret = "";

		$ret .= $this->echoHiddenFields();
    	list ($html, $colspan) = $this->echoColGroup($show_all);
		$ret .= $html;
		$ret .= $this->echoNavigation($colspan,  $command, $add_params);
        $ret .= $this->echoHeadline  ($show_all, $command, $add_params);
        $ret .= $this->echoSearchRow ($show_all);
        $ret .= $this->echoData      ($show_all, $arr);
		$ret .= $this->echoAdditionalFreeRows ();
		
		return $ret;
	}	
		
	function renderAdminFromDB (
				&$db_hdl, 
				$datagrid_table, 
				$columns_table, 
				$datagrid_name, 
				$command,
				$add_params) {

	    $this->datagrid->setTranslations ($this->translations);

		$arr      =& $this->datagrid->dg_arr;
		$ret      = "";
		$show_all = false;
		
		// set default "unvisible"
        //foreach ($this->datagrid->columns AS $column) {
	    for ($i=0; $i < count ($this->datagrid->columns); $i++) {
	        $this->datagrid->columns[$i]->visible=false;
        }	

        $query = "
            select d.datagrid_id, c.column_id, c.column_identifier, order_nr, c.column_name, c.width
            FROM $datagrid_table d
            LEFT JOIN $columns_table c ON d.datagrid_id=c.datagrid_id
            WHERE d.name='$datagrid_name' AND mandator_id=".$_SESSION['mandator']."
            order by c.order_nr
                ";
        $res = $db_hdl->Execute ($query);
        $col = 0;
        $cnt = $res->RecordCount();
        while (!$res->EOF) {
        	$add_params .= "&datagrid_id=".$res->fields['datagrid_id'];
        	
            $col_ident   = $res->fields['column_identifier'];
            $col_name    = $res->fields['column_name'];
            $col_id      = $res->fields['column_id'];

            $grid_col_id = $this->datagrid->getColumnID ($col_ident);
			
            // change order, don't do that for the last entry
            if (($col+1) < $cnt)
	            $this->datagrid->swapColumnOrder ($col, $grid_col_id);

            // set column visible
			$this->datagrid->columns[$grid_col_id]->visible=true;
            
            $res->MoveNext();    
            $col++;
        }   
        //var_dump ($arr);
        //var_dump ($this->datagrid->columns);

		$ret .= $this->echoHiddenFields();
    	list ($html, $colspan) = $this->echoColGroup($show_all);
		$ret .= $html;
		//$ret .= $this->echoNavigation($colspan,  $command, null);
        $ret .= $this->echoAdminHeadline  ($show_all, $command, $add_params);
        //$ret .= $this->echoSearchRow ($show_all);
        $ret .= $this->echoData      ($show_all, $arr);
		//$ret .= $this->echoAdditionalFreeRows ();
		
		return $ret;
	}	

	function renderFromDB (
				&$db_hdl, 
				$datagrid_table, 
				$columns_table, 
				$datagrid_name, 
				$command,
				$add_params = "") {
	    $this->datagrid->setTranslations ($this->translations);

		$arr      =& $this->datagrid->dg_arr;
		$ret      = "";
		$show_all = false;

		/*echo "=== COlumns 1 === <br>";
        var_dump ($this->datagrid->columns);
        echo "=== arr 1 ======= <br>";
        var_dump ($arr);*/
		
	    for ($i=0; $i < count ($this->datagrid->columns); $i++) {
	        $this->datagrid->columns[$i]->visible=false;
        }	

        $query = "
            select d.datagrid_id, d.searchButtonCol, c.column_id, c.column_identifier, 
				   c.visible, order_nr, c.column_name, c.width, c.is_primary, c.searchable, c.sortable
            FROM $datagrid_table d
            LEFT JOIN $columns_table c ON d.datagrid_id=c.datagrid_id
            WHERE d.name='$datagrid_name' AND d.mandator_id=".$_SESSION['mandator']."
            order by c.order_nr
                ";
        //echo $query;
        $res = $db_hdl->Execute ($query);
        $col = 0;
        $cnt = count ($this->datagrid->columns); //$res->RecordCount();
//die ("#".$cnt);
		// return info if no record was found:
		if ($res->RecordCount() == 0) 
			return "Query empty";
		
		if ($cnt != count($this->datagrid->columns))
			echo "Column count does not match";
			
        $add_params .= "&datagrid_id=".$res->fields['datagrid_id'];
        
        while (!$res->EOF) {
        	
            $col_ident       = $res->fields['column_identifier'];
            $col_name        = $res->fields['column_name'];
            $col_id          = $res->fields['column_id'];
            $col_width       = $res->fields['width'];
            $col_primary     = (bool)$res->fields['is_primary'];
            $col_search      = (bool)$res->fields['searchable'];
            $col_sort        = (bool)$res->fields['sortable'];
            $searchButtonCol = $res->fields['searchButtonCol'];
            $grid_col_id     = $this->datagrid->getColumnID ($col_ident);

            // change order, don't do that for the last entry
            if (($col+1) < $cnt) {
                //echo "swapping $col with $grid_col_id <br>";
	            $this->datagrid->swapColumnOrder ($col, $grid_col_id);
	            
	            /*echo "=== COlumns $col === <br>";
                var_dump ($this->datagrid->columns);
                echo "=== arr $col ======= <br>";
                var_dump ($arr);*/
	            
            }
//var_dump ($col);
            // set column visible
            if ((bool)$res->fields['visible'])
				$this->datagrid->columns[$col]->visible=true;
            
            // set title
            if (trim($col_name) != '')
	            $this->datagrid->setColumnTitle ($col, translate($col_name));
	        else
	            $this->datagrid->setColumnTitle ($col, '');	        

            // set primary 
            if ($col_primary)
	            $this->datagrid->setPrimary ($col, true);

            // set searchable
            if ($col_search)
	            $this->datagrid->setSearchable ($col, true);

            // set sortable
            if ($col_sort)
	            $this->datagrid->setSortable ($col, true);
            else
	            $this->datagrid->setSortable ($col, false);
            
            // set width
            if ($col_width > 0)
	            $this->datagrid->setColumnWidth ($col, $col_width);

            // set SearchButton
            if ($searchButtonCol > 0 && $searchButtonCol == $col)
                $this->datagrid->setShowSearchButton ($col, true);
            
            $res->MoveNext();    
            $col++;
        }   
        

        //var_dump ($arr);
        //var_dump ($this->datagrid->columns);

		$ret .= $this->echoHiddenFields();
    	list ($html, $colspan) = $this->echoColGroup($show_all);
		$ret .= $html;
		$ret .= $this->echoNavigation($colspan,  $command, null);
        $ret .= $this->echoHeadline  ($show_all, $command, $add_params);
        $ret .= $this->echoSearchRow ($show_all);
        $ret .= $this->echoData      ($show_all, $arr);
		$ret .= $this->echoAdditionalFreeRows ();
		
		return $ret;
	}
	
	/*function render4Admin ($show_all, $command, $add_params = "") {
		$arr =& $this->datagrid->dg_arr;
		$ret = "";
		
		$ret .= $this->echoHiddenFields();
    	list ($html, $colspan) = $this->echoColGroup($show_all);
		$ret .= $html;
		//$ret .= $this->echoNavigation($colspan,  $command, $add_params);
        $ret .= $this->echoAdminHeadline  ($show_all, $command, $add_params, true);
        //$ret .= $this->echoSearchRow ($show_all);
        $ret .= $this->echoData      ($show_all, $arr);
		//$ret .= $this->echoAdditionalFreeRows ();
		return $ret;
	}*/
	
	function setTranslations ($translations) {
	    $this->translations = $translations;
    }	
}

?>
