<?php
  // file: class.data_object.inc
  // desc: base data object
  // code: tkirby (kirbyt@yahoo.com)
  // lic : GPL, v2

if (!defined(__CLASS_DATA_OBJECT_INC__)) {

define(__CLASS_DATA_OBJECT_INC__, true);

class data_object
{
	var $db;

	function data_object()
	{
		global $sql;
		//if (is_object($sql)) { $this->db = $sql; }
		$this->db = $sql;
	}

	function count_records ($table) // would be static method if php had...
	{
		$sql = "SELECT COUNT(*) FROM $table";
		$result = $this->db->db_query (DB_NAME, $sql);
		$count = $this->db->fetch_array ($result);
		return $count[0];
	}
} // end class

} // end if not defined

?>
