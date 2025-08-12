<?php
/**************************************
 * phpdbform                          *
 **************************************
 * Main class for phpdbform           *
 * with database access               *
 *                                    *
 * Paulo Assis <paulo@phpdbform.com>  *
 * 2001 - 02 - 06                     *
 **************************************/

require_once("phpdbform/phpdbform_form.php");
require_once("phpdbform/phpdbform_selectform.php");

class phpdbform extends phpform {
    // DB stuff
    var $table;
    var $db;        // db link
    var $dbfields;  // fields from database
    var $keys;      // keys that identifies one unique row, use commas for more than one
    var $selform;   // form for selecting rows
    var $mode;      // mode of the form, insert or update
    var $keyvalue;  // value of the key, if updatemode

    // Events fired just before executing the specified action
    // it must return true, so the process may continue
    // if it returns false, no action is taken (it's assumed that the event did it)
    var $oninsert;
    var $onupdate;
    var $ondelete;

    // db - database object linking into the db server
    // table - table name
    // keys - fields separeted by comma that select an unique row
    // sel_fields - fields shown at the selection box
    // sel_order - order used to sort the list at the selection box
    function phpdbform( $db, $table, $keys, $sel_fields="", $sel_order="" )
    {
        session_start();
        $this->db = $db;
        $this->table = $table;
        $this->keys = explode( ",", $keys );

        // call parents constructor - use tablename as formname
        $this->phpform( $table );
		// this will tell fields that they can get its size
		$this->hasdblink = true;
        // if sel_fields == "" then the user don't want the select form!
        if( $sel_fields == "" )
        {
            $this->selform = 0;
        } else {
            // if sel_order == "" then we use the keys as the order for listing the select form
            if( $sel_order == "" ) $sel_order = $keys;
            $this->selform = new phpselectform( $this->db, $this->table, $keys, $sel_fields, $sel_order );
        }
        // at the beggining, the form starts in insertmode
        // then, at the process we check if it has akey defined, it enters update mode
        // after a delete action, it should enter insert mode
        $this->mode = "insert";
        // fill field lenghts
        $this->dbfields = $this->db->get_fields( $table );
    }

    function add_filter( $field, $title, $size )
    {
		// By Iko (2004-10-17): Language support: _LANGERRORFILTER
        if( !empty($this->selform) )
            $this->selform->add_filter( $field, $title, $size );
        else echo _LANGERRORFILTER;
    }

    // select data from table
    function select_data()
    {
        if( !$this->keyvalue ) return false;
        $stmt = "select ";
        $tot_fields = count($this->fields);
        $i = 1;
        reset($this->fields);
        while( $afield = each($this->fields) )
        {
            $stmt .= $afield[1]->field;
            if(($i++)<$tot_fields) $stmt .= ", ";
        }
        $stmt .= " from {$this->table} where ";
        // read values from keys
        reset( $this->keyvalue );
        $i = 0;
        while( $akey = each( $this->keyvalue ) )
        {
            if( $i > 0 ) $stmt .= " AND ";
            $stmt .= trim( $this->keys[$i] ) . " = '{$akey[1]}'";
            $i++;
        }
        //print "<textarea rows=10 cols=40>$stmt</textarea>";
		// By Iko (2004-10-17): Language support: _LANGDATALOADDB
        $ret = $this->db->query( $stmt, _LANGDATALOADDB );
        $vals = $this->db->fetch_row( $ret );
        $this->db->free_result( $ret );
        if( !$vals ) return false;
        reset( $vals );
        reset( $this->fields );
        while( $afield = each($this->fields) )
        {
            $val = each( $vals );
            $this->fields[$afield[1]->field]->value = $val[1];
        }
        return true;
    }

    // insert data from form to table
    function insert_data()
    {
        $stmt = "insert into ".$this->table." ( ";
		$first = false;
        reset($this->fields);
        while( $afield = each($this->fields) )
        {
			if( !$afield[1]->updatable ) continue;
			if( $first ) $stmt .= ", ";
			else $first = true;
            $stmt .= $afield[1]->field;
        }
        $stmt .= " ) values ( ";
		$first = false;
        reset($this->fields);
        while( $afield = each($this->fields) )
        {
			if( !$afield[1]->updatable ) continue;
			if( $first ) $stmt .= ", ";
			else $first = true;
            $field = $afield[1]->field;
            // always add slahes because we remove the slashes
            $stmt .= "'".addslashes( $this->fields[$field]->value )."'";
        }
        $stmt .= " )";
        $this->db->query( $stmt, _LANGDATAINSERT );
    }

    // update data from form to table
    function update_data()
    {
        $stmt = "update ".$this->table." set ";
        $first = false;
        reset($this->fields);
        while( $afield = each($this->fields) )
        {
			if( !$afield[1]->updatable ) continue;
			if( $first ) $stmt .= ", ";
			else $first = true;
            $stmt .= $afield[1]->field . " = '"
                  .addslashes($this->fields[$afield[1]->field]->value)."'";
        }
        $stmt .= " where ";
        reset( $this->keyvalue );
        $i = 0;
        while( $akey = each( $this->keyvalue ) )
        {
            if( $i > 0 ) $stmt .= " AND ";
            $stmt .= trim( $this->keys[$i] ) . " = '{$akey[1]}'";
            $i++;
        }
        //echo "<textarea rows=6 cols=80>$stmt</textarea>";
		// By Iko (2004-10-17): Language support: _LANGDATAUPDATE
        $this->db->query( $stmt, _LANGDATAUPDATE );
    }

    // delete the recrod
    function delete_data()
    {
        $stmt = "delete from ".$this->table . " where ";
        reset( $this->keyvalue );
        $i = 0;
        while( $akey = each( $this->keyvalue ) )
        {
            if( $i > 0 ) $stmt .= " AND ";
            $stmt .= trim( $this->keys[$i] ) . " = '{$akey[1]}'";
            $i++;
        }
        //echo "<textarea rows=6 cols=80>$stmt</textarea>";
		// By Iko (2004-10-17): Language support: _LANGDATADELETE
        $this->db->query( $stmt, _LANGDATADELETE );
    }

    function draw_delete_button( $button_text )
    {
        //Nik Chankov 2002.06.15 /show delete buton control
        //if($this->selform->value)
        if( $this->mode != "insert" )
        {
        	print "<input type=\"submit\" name=\"submit_delete\" class=\"fieldbutton\" value=\"$button_text\">\n";
        }
    }

    function draw_submit( $button_text, $draw_delete = true )
    {
        phpform::draw_submit( $button_text );
        echo "&nbsp;&nbsp;&nbsp;";
        //Nik Chankov 2002.06.15 /show delete buton control
		// By Iko (2004-10-17): Language support: _LANGDELETE
        if( $draw_delete ) $this->draw_delete_button( _LANGDELETE );
    }

    function draw_header()
    {
		phpform::draw_header();
		print "<input type=\"hidden\" name=\"{$this->table}_sess_key\" value=\""
			.htmlspecialchars(serialize($this->keyvalue))."\">\n";
		print "<input type=\"hidden\" name=\"{$this->table}_sess_mode\" value=\""
			.htmlspecialchars($this->mode)."\">\n";
    }

    function draw()
    {
        if( $this->selform != 0 ) $this->selform->draw();
        phpform::draw();
    }

    function process()
    {
        if( $this->selform != 0 ) $selformprocessed = $this->selform->process();
        if( !phpform::process() && !$this->noproc )
        {
            // if this form didn´t processed, see if select processed
            // first check if there is a select form
            $selected = false;
            if( $this->selform != 0 )
            {
                // See if any key was selected by selform
                $selected = $selformprocessed;
                if( $selected && !$this->selform->value ) $selected = false;
                if( $selected ) $this->keyvalue = $this->selform->value;
            }
            // If there was no selform, or selform selected nothing
            // try to see if the user has set keyvalue
            // how user can set keyvalue? using $form->keyvalue = "xxx,xxx"
            if( !$selected && count($this->keyvalue) > 0 ) $selected = true;
            // Something filled keyvalue, try loading the values into phpdbform
            if( $selected )
            {
                if( $this->select_data() )
                {
                    // found data!
                    $this->mode = "update";
                } else {
                    // some error occurred, clear phpdbform and set insertmode
                    $this->clear();
                    $this->mode = "insert";
                }
            // nothing was selected, go to insertmode
            } else $this->mode = "insert";
            // if there is a select form, fill it with data
            if( $this->selform != 0 ) $this->selform->select_data();
            return;
        }
        // the form processed anything, lets work
        // first get key and value from session
        if( isset( $_POST["{$this->table}_sess_mode"] ) )
        {
			$this->mode = $_POST["{$this->table}_sess_mode"];
			// can be a hack...
			if( $this->mode != "insert" && $this->mode != "update" && $this->mode != "delete" )
				die( "Invalid mode : $this->mode" );
			$temp = $_POST["{$this->table}_sess_key"];
			if( get_magic_quotes_gpc() ) $temp = stripslashes($temp);
			$this->keyvalue = unserialize($temp);
        }
		if( !$this->noproc )
		{
	        // if delete button was pressed, goto deletemode
	        if( isset( $_POST["submit_delete"] ) ) $this->mode = "delete";
	        if( $this->mode == "update" )
	        {
	            if( $this->selform != 0 ) $this->selform->value = $this->keyvalue;
	            // update data
	            if( isset( $this->onupdate ) )
	            {
	                if( call_user_func($this->onupdate, $this) ) $this->update_data();
	            }
	            else $this->update_data();
	        } else if( $this->mode == "insert" )
	        {
	            // insert data
	            if( isset( $this->oninsert ) )
	            {
	                if( call_user_func($this->oninsert,$this) ) $this->insert_data();
	            }
	            else $this->insert_data();
	            // clear values
	            $this->clear();
	        } else if( $this->mode == "delete" )
	        {
	            // delete data
	            if( isset( $this->ondelete ) )
	            {
	                if( call_user_func($this->ondelete,$this) ) $this->delete_data();
	            }
	            else $this->delete_data();
	            $this->clear();
				$this->keyvalue = "";
				$this->mode = "insert";
	        }
		} else {
	        if( $this->mode == "update" )
	        {
	            if( $this->selform != 0 ) $this->selform->value = $this->keyvalue;
	            // update data
	        }
		}
        // if there is a select form, fill it with data
        if( $this->selform != 0 ) $this->selform->select_data();
    }
}
?>
