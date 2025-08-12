<?
/*
xmlexport.php
Author : Thomas Whitecotton
Email  : admin@ciamosbase.com
Website: http://www.ciamosbase.com
*/

class XMLExport {

	var $db_table = "";
	var $feed = "";
	var $db_fields = array();
	var $items = array();
	var $itemValues = array();

	function set_items($items) {
		$this->items = $items;
	}

	function set_values($values) {
		$this->itemValues = $values;
	}

	function set_db_table($table) {
		$this->db_table = $table;
	}

	function set_db_fields($fields) {
		$this->db_fields = $fields;
	}

	function set_db_info($table, $fields) {
		$this->set_db_table($table);
		$this->set_db_fields($fields);
		$this->set_items($fields);
	}

	function array_extract($array, $extract_type = 1)
	{
	   foreach ( $array as $key => $value )
	   {
		   if ( $extract_type == 1 && is_string($key) )
		   {
			   // delete string keys
			   unset($array[$key]);
		   }
		   elseif ( $extract_type == 2 && is_int($key) )
		   {
			   // delete integer keys
			   unset($array[$key]);
		   }
	   }

	   return $array;
	}

	function addTable() {
	$this->feed .="
	<db:Table xmlns:db='Table'>
		<dbtable>".$this->db_table."</dbtable>
	</db:Table>";
	}

	function addFields() {
	$this->feed .="
	<db:Fields xmlns:db='Fields'>";
		foreach($this->db_fields as $field) {
			$this->feed .="
		<dbfield>".$field."</dbfield>";
		}
	$this->feed .="
	</db:Fields>";
	}

	function startFeed() {
		$this->feed .= "<";
		$this->feed .= "?xml version='1.0' standalone='yes' ?";
		$this->feed .= ">
<db>";
		$this->addTable();
		$this->addFields();
		$this->startEntries();
	}

	function startEntries() {
		$this->feed .= "
	<db:Entries xmlns:db='Entries'>";
	}

	function createItem() {
		$this->feed .= "
		<entry>";
		$x=0;
		foreach($this->itemValues as $value) {
			if($value=="" || empty($value)) {
				$value="";
			}
			$this->feed .= "
			<".$this->items[$x].">".htmlspecialchars(trim($value))."</".$this->items[$x].">";
			$x++;
		}
		$this->feed .= "
		</entry>";
	}

	function addItem($values) {
		$this->set_values($values);
		$this->createItem();
	}

	function endEntries() {
		$this->feed .= "
	</db:Entries>";
	}

	function closeFeed() {
		$this->endEntries();
		$this->feed .= "
</db>";
	}

	function returnFeed() {
		return($this->feed);
	}
}
?>