<?php
/**************************************
 * phpselectform                      *
 **************************************
 * Class for drawing the select form  *
 * used by phpdbform                  *
 *                                    *
 * Paulo Assis <paulo@phpdbform.com>  *
 * 2002 - 05 - 29                     *
 **************************************/
require_once("phpdbform/phpdbform_filter.php");

class phpselectform {
    // DB stuff
    var $table;
    var $db;        // db link
    var $keys;      // keys that identifies one unique row, use commas for more than one
    var $fields;    // fields for showing at the listbox, use commas for more than one
    var $order;     // order used to show the items, use like the order by clause
    var $dbfields;  // fields from keys and fields t ouse in the select clause
                    // mysql doesn't need the fields of the order by to be at the select clause
                    // I don't know about others yet
    var $options;   // options tags for the selectionform
    var $value;     // selected value, same order from keys (array)
	var $cssclass;

    // filter support
    var $filter;

    function phpselectform( $db, $table, $keys, $fields, $order )
    {
        $this->db = $db;
        $this->table = $table;
        $this->keys = explode( ",", $keys );
        $this->fields = explode( ",", $fields );
        $this->order = $order;
        $this->cssclass = "fieldselectbox";
		
        reset( $this->keys );
        reset( $this->fields );
        $this->dbfields = array();
        while( $afield = each($this->keys) )
            $this->dbfields[$afield[1]] = 0;
        while( $afield = each($this->fields) )
            $this->dbfields[$afield[1]] = 0;
        // the database must be connected at this time
    }

    // process input from selection
    // returns true if anything was selected
    function process()
    {
        if( isset($this->filter) ) $this->filter->process();
        $afield = "select_{$this->table}_field";
        if( !isset( $_POST[$afield] ) ) return false;
        $this->value = unserialize( stripslashes($_POST[$afield]) );
        return true;
    }

    function add_filter( $field, $title, $size )
    {
        $this->filter = new phpfilterform( $this->table."_filter", $field, $title, $size );
    }

    // select data from table
    function select_data()
    {
        $stmt = "select ";
        $tot_fields = count($this->dbfields);
        $i = 1;
        reset($this->dbfields);
        while( $afield = each($this->dbfields) )
        {
            $stmt .= $afield[0];
            if(($i++)<$tot_fields) $stmt .= ", ";
        }
        $stmt .= " from {$this->table}";
        if( isset($this->filter) ) $stmt .= $this->filter->get_where_clause();
        // order goes directly into the select
        $stmt .= " order by {$this->order}"; 
		// By Iko (2004-10-17): Language support: _LANGDATALOADFORM
        $ret = $this->db->query( $stmt, _LANGDATALOADFORM );
        $this->options = "";
        $bvalue = serialize($this->value);
        while( $vals = $this->db->fetch_array( $ret ) )
        {
            // select the keys first
            $vkeys = array();
            reset( $this->keys );
            while( $afield = each($this->keys) )
            {
                $vkeys[] = $vals[$afield[1]];
            }
            $avalue = serialize($vkeys);
            $selected = ($bvalue == $avalue)?"selected":"";
            $this->options .= "<option $selected value=\"" . htmlspecialchars( $avalue ) .  "\">";
            // now the fields - I could use implode, but since I need to get the fields first...
            reset( $this->fields );
            $first = true;
            while( $afield = each($this->fields) )
            {
                if( !$first ) $this->options .= " | ";
                else $first = false;
                $this->options .= $vals[$afield[1]];
            }
            $this->options .= "</option>\n";
        }
        $this->db->free_result( $ret );
    }

    // set draw_filter false when you want to draw the filter yourself
    // by calling $dbform->selform->filter->draw()
    function get_string( $draw_filter = true )
    {
		// By Iko (2004-10-17): Language support: _LANGINSERTNEWRECORD
        $txt = "<form method=\"post\" name=\"select_{$this->table}\" action=\"".basename($_SERVER["PHP_SELF"])."\">\n"
				."<select class=\"{$this->cssclass}\" name=\"select_{$this->table}_field\" onChange=\"document.select_{$this->table}.submit()\">\n"
            	."<option value=''>&nbsp;</option>\n<option value=''>"._LANGINSERTNEWRECORD."</option>\n"
				.$this->options."</select>\n</form>\n";
        if( isset($this->filter) ) if( $draw_filter ) $txt .= $this->filter->get_string();
		return $txt;
    }

	function draw( $draw_filter = true )
	{
		print $this->get_string( false );
		if( isset($this->filter) ) if( $draw_filter ) $this->filter->draw();
	}
}
?>
