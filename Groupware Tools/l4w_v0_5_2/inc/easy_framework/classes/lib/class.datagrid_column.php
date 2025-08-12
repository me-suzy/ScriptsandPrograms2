<?php

  /**
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      not_documented_yet
*/

   /**
    *
    * @version      $Id: class.datagrid_column.php,v 1.6 2005/08/06 06:57:08 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      not_documented_yet
    */
 class column {
	
	var $name            = null;  // Exact query column name
	var $col_name        = null;  // Field name (for searching)
	var $title           = null;  // Text to show in header row
	var $link2page       = "";    // 

	var $searchbox_value = "";
	var $searchbutton    = false;
	var $searchable      = false;
	var $sortable        = true;
	var $selectionBox    = false;
	var $selectionChoice = null;
	var $selectionSel    = null;
	var $searchsize      = 10;   // size of searchbox in Chars 
    var $visualize       = '';

	//var $ident           = null;
	var $index           = 0;    // number in row

	var $order           = null; // element of {NONE,DESC,ASC}
	var $width           = null;
	var $style           = null;
	var $class           = "datagrid";

	var $primary         = false;

	var $visible         = true;

    //var $col_id          = null;
		
	//function column ($ident, $name, $searchbox_value, $link2page, $index) {
	function column ($index, $name, $searchbox_value, $link2page) {
		//$this->ident = $ident;
		$this->name       = $name;
		$this->title      = $name;
		$this->col_name   = $name; // should not be overwritten
		$this->link2page  = $link2page;
		$this->index      = $index;
		$this->order      = "NONE";
		$this->searchbox_value = $searchbox_value;
	}

	function getColumnWidth ()  {
		return $this->width;
	}
	
	function setColumnWidth ($width) {
		$this->width = $width;
	}
	
	function getColumnStyle ()  {
		return $this->style;
	}
	
	function setColumnStyle ($style) {
		$this->style = $style;
	}

	function getColumnClass ()  {
		return $this->class;
	}
	
	function setColumnClass ($css_class) {
		$this->class = $css_class;
	}

	function getColumnTitle ()  {
		return $this->title;
	}
	
	function setColumnTitle ($title) {
		$this->title = $title;
	}

	function getIsPrimary ()  {
		return $this->primary;
	}
	
	function setIsPrimary ($primary) {
		$this->primary = $this->primary;
	}

	function getIsVisible ()  {
		return $this->visible;
	}
	
	function setIsVisible ($visible) {
		$this->visible = $visible;
	}
	
	function getIsSearchable ()  {
		return $this->searchable;
	}
	
	function setIsSearchable ($searchable) {
		$this->searchable = $searchable;
	}
			
	function getIsSortable ()  {
		return $this->sortable;
	}
	
	function setIsSortable ($sortable) {
		$this->sortable = $sortable;
	}

	function setSelectionBox ($bool, $choices, $selected = null) {
		$this->selectionBox    = $bool;
		$this->selectionChoice = $choices;
		$this->selectionSel    = $selected;
	}

	function getShowSearchButton ()  {
		return $this->searchbutton;
	}
	
	function setShowSearchButton ($bool) {
		$this->searchbutton = $bool;
	}

	function setColumnId ($col_id) {
		$this->col_id = $col_id;
	}

	function getColumnId ()  {
		return $this->col_id;
	}

	function getName () { return $this->name; }

	function getOrder () { return $this->order; }
		
	function getLink ($command, $add_params = "") { 
	    
	    if (!$this->getIsSortable())
	        return $this->title;
	        
		$direction = "ASC";
		if ($this->getOrder() == "ASC") $direction = "DESC";
		return "<a href='".$this->link2page."?command=$command&col_index=".$this->index."&order=".($this->index+1)."&direction=".$direction.$add_params."'>".$this->title."</a>"; 
	}
	
	function getAdminLink ($command, $add_params = "") { 
		$ret  = "<a href='".$this->link2page."?command=$command&";
		$ret .= "col_index=".$this->index."&order=".($this->index+1);
		$ret .= "&".$add_params."'>".$this->title."</a>";
		return $ret; 
	}
	
	/*function getAdminLink ($stmt_id) { 
		return "<a href='".$this->link."?command=admin&stmt_id=$stmt_id&col_id=".$this->col_id."'>A</a>"; 
	}*/

	function setOrder ($kind_of) {
		$this->order = $kind_of;	
	}


}


?>
