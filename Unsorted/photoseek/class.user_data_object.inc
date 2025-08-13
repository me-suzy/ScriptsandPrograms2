<?php
  // file: class.user_data_object.inc
  // desc: A data object that provides access to the users table
  // code: tkirby (kirbyt@yahoo.com)
  // lic : GPL, v2

if (!defined(__CLASS_USER_DATA_OBJECT_INC__)) {

define(__CLASS_USER_DATA_OBJECT_INC__, true);
include "class.data_object.inc";

class user_data_object extends data_object
{
	// constructor
	function user_data_object()
	{
		$this->data_object(); // call parent constructor
	}

	function update($username, $userdesc, $userpass, $userlevel, $id)
	{	
		$query = "UPDATE users SET
             username = '".addslashes($username). "',
             userdesc = '".addslashes($userdesc). "',
             userpass = '".addslashes($userpass). "',
             userlevel= '".addslashes($userlevel)."'
             WHERE id='".addslashes($id)."'";
		
		return($this->db->db_query (DB_NAME, $query));
	}
	
	function add($username, $userdesc, $userpass, $userlevel)
	{
		$query = "INSERT INTO users VALUES (
             	'".addslashes($username). "',
            	'".addslashes($userdesc). "',
            	'".addslashes($userpass). "',
             	'".addslashes($userlevel)."',
             	NULL ) ";        
   		return ($this->db->db_query (DB_NAME, $query));
   	}
	
	function delete($id)
	{	
		$query = "DELETE FROM users ".
		 "WHERE id='".addslashes($id)."'";         
        return ($this->db->db_query (DB_NAME, $query));
	}
	
	function load($id)
	{
		$query = "SELECT * FROM users ".
		 "WHERE id='".addslashes($id)."'";
   		return ($this->db->db_query (DB_NAME, $query));
	}
	
	function find()
	{
		$query = "SELECT * FROM users ORDER BY username";
   		return ($this->db->db_query (DB_NAME, $query));
	}
} // end class

} // end if not defined

?>
