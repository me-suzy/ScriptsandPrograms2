<?php

/**
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      not_documented_yet
*/

   /**
    *
    * @version      $Id: class.datagrid2list.php,v 1.6 2005/08/06 06:57:08 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      not_documented_yet
    */
class datagrid2list {
	
	var $datagrid         = null;
	var $translations     = array (
	                            "hits"            => "Hits",
	                            "showing entries" => "Showing entries from %d to %d",
	                            "per page"        => "per Page"
	                        );
	
	function datagrid2list (&$datagrid) {
	    $this->datagrid = $datagrid;
	}

	
	function echoHiddenFields () {

		/*$ret     = '';
		$columns =& $this->datagrid->getColumns();
		for ($i=0; $i < count ($columns); $i++) {
			$ret .= "<input type=hidden name='mapping_".$columns[$i]->name."'";
			$ret .= " value='".$columns[$i]->col_name."'>\n";	
		}
		return $ret;*/
	}

	function echoColGroup ($show_all) {
	    
	    $columns =& $this->datagrid->getColumns();
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
	    
	    $columns =& $this->datagrid->getColumns();
	    
	    $ret = "<tr class='headline'>\n";
		for ($i=0; $i < count ($columns); $i++) {
			$this_class = $columns[$i]->getColumnClass();
			$width = "";
			$order_sign = "&nbsp;&nbsp;";
			if ($show_all || $columns[$i]->getIsVisible()) {
				$style = "";
				if ($columns[$i]->getColumnStyle() != null) 
					$style = " style='".$columns[$i]->getColumnStyle()."'";			    
				$add_params.="&entries_per_page=".$this->datagrid->pagesize;
				$ret .= '<th class="'.$this_class.'"'.$width.$style.'>'.$columns[$i]->getName()."&nbsp;</th>\n";	
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
			//if ($show_all || $columns[$i]->getIsVisible()) {
			$style = "";
			if ($columns[$i]->getColumnStyle() != null) 
				$style = " style='".$columns[$i]->getColumnStyle()."'";			    
			$add_params.="&entries_per_page=".$this->datagrid->pagesize;
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
		$ret .= "</tr>\n";
	    return $ret;
	}

	function echoSearchRow ($show_all) {

        $columns =& $this->datagrid->getColumns();
        
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
	    
        $columns =& $this->datagrid->getColumns();

    	if (isset($arr[0])) $fields_cnt = count($arr[0]); 
        $ret   = '';
    	$line  = 0;
    	
    	$primary_col = null;
        foreach ($columns AS  $key => $column) {
            if ($column->primary) {
                $primary_col = $column->index;        
                break;
            }
        }    
        
	    if ($arr != null) {
		    foreach ($arr AS  $key => $row) {
			    $row_style = "";
			    if ($this->datagrid->row_style_func != null) {
				    $row_style = call_user_func	($this->row_style_func, $row);
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
		
				foreach ($row AS $key =>  $col) {
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
		
	function getHtmlCode ($show_all, $command, $add_params = "") {
	    $this->datagrid->setTranslations ($this->translations);
	    
		$arr =& $this->datagrid->dg_arr;
		$ret = "";

		$ret = ''; //$this->echoHiddenFields();
    	list ($html, $colspan) = $this->echoColGroup($show_all);
		$ret .= $html;
		//$ret .= $this->echoNavigation($colspan,  $command, $add_params);
        $ret .= $this->echoHeadline  ($show_all, $command, $add_params);
        //$ret .= $this->echoSearchRow ($show_all);
        $ret .= $this->echoData      ($show_all, $arr);
		//$ret .= $this->echoAdditionalFreeRows ();
		
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
        $ret .= $this->echoSearchRow ($show_all);
        $ret .= $this->echoData      ($show_all, $arr);
		$ret .= $this->echoAdditionalFreeRows ();
		return $ret;
	}*/	
	
	function setTranslations ($translations) {
	    $this->translations = $translations;
    }	
}

?>
