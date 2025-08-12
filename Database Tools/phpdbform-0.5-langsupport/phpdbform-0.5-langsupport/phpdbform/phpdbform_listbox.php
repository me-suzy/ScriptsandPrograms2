<?php
/**************************************
 * phpdbform_listbox                  *
 **************************************
 * ListBox with db list control       *
 * even using a db conn, it can be    *
 * used at phpform.                   *
 *                                    *
 * Paulo Assis <paulo@phpdbform.com>  *
 * 2001 - 06 - 13                     *
 **************************************/

require_once("phpdbform/phpdbform_field.php");

class phpdbform_listbox extends phpdbform_field {
    var $db;
    var $table;
    var $lbkey;
    var $lbvalue;
    var $order;

    // todo: add support for more than one key/value (would use 2+ fields)
    function phpdbform_listbox( &$form, $field, $title, &$db, $table, $key, $value, $order )
    {
		$this->phpdbform_field( $form, $field, $title );
        $this->db = $db;
        $this->table = $table;
        $this->lbkey = $key;
        if( is_array($value) ) $this->lbvalue = $value;
		else {
			$tok = strtok($value,",");
			while( $tok )
			{
				$this->lbvalue[] = $tok;
				$tok = strtok (",");
			}
		}
        $this->order = $order;
		$this->cssclass = "fieldlistbox";
		$form->add( $this );
    }

    function get_string()
    {
        if( strlen($this->onblur) ) $javascript = "onblur=\"{$this->onblur}\"";
        else $javascript="";
        if( !empty($this->title) ) $txt = $this->title."<br>";
		else $txt = "";
		$vfields = "";
		reset($this->lbvalue);
		while( $vfld = each($this->lbvalue) ) $vfields .= ", ".$vfld[1];
        $stmt = "select {$this->lbkey} {$vfields} from {$this->table} order by {$this->order}";
        $ret = $this->db->query( $stmt, "populating listbox" );
        $txt .= "<select class=\"{$this->cssclass}\" name=\"$this->key\" $javascript {$this->tag_extra}>\n";
        while( $row = $this->db->fetch_row($ret) )
        {
            $selected = ($row[0] == $this->value)?"selected":"";
            $txt .= "<option value=\"".htmlspecialchars($row[0])."\" $selected>";
			for( $i = 0; $i < count($this->lbvalue); ++$i )
			{
				if( $i > 0 ) $txt .= " | ";
				$txt .= htmlspecialchars($row[1+$i]);
			}
			$txt .= "</option>\n";
        }
        return $txt."</select>\n";
    }

    function process()
    {
        if( isset( $_POST[$this->key] ) ) {
            $this->value = $_POST[$this->key];
            $this->delmagic();
        }
    }
}
?>