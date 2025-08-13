<?php
  // file: class.repository_data_object.inc
  // desc: A data object that provides access to the repositories table
  // code: tkirby (kirbyt@yahoo.com)
  //       jeff b (jeff@univrel.pr.uconn.edu)
  // lic : GPL, v2

if (!defined (__CLASS_REPOSITORY_DATA_OBJECT_INC__)) {

define (__CLASS_REPOSITORY_DATA_OBJECT_INC__, true);
include "class.data_object.inc";

class repository_data_object extends data_object
{
	// constructor
	function repository_data_object()
	{
		$this->data_object(); // call parent constructor
	}

	function update($rname, $rdesc, $rpath, $rlevel, $id)
	{	
		$query = "UPDATE repositories SET
             rname = '".addslashes($rname). "',
             rdesc = '".addslashes($rdesc). "',
             rpath = '".addslashes($rpath). "',
             rlevel= '".addslashes($rlevel)."'
             WHERE id='".addslashes($id)."'";
		
		return($this->db->db_query (DB_NAME, $query));
	}
	
	function add($rname, $rdesc, $rpath, $rlevel)
	{
		$query = "INSERT INTO repositories VALUES (
             	'".addslashes($rname). "',
            	'".addslashes($rdesc). "',
            	'".addslashes($rpath). "',
             	'".addslashes($rlevel)."',
             	NULL ) ";        
   		return ($this->db->db_query (DB_NAME, $query));
   	}
	
	function delete($id)
	{	
		$query = "DELETE FROM repositories ".
		 "WHERE id='".addslashes($id)."'";         
        	return ($this->db->db_query (DB_NAME, $query));
	}
	
	function load($id)
	{
		$query = "SELECT * FROM repositories ".
		 "WHERE id='".addslashes($id)."'";
   		return ($this->db->db_query (DB_NAME, $query));
	}
	
	function find()
	{
		$query = "SELECT * FROM repositories ORDER BY rname";
   		return ($this->db->db_query (DB_NAME, $query));
	}
} // end class

} // end if defined
?>
