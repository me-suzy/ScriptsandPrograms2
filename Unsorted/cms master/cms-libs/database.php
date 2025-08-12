<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-libs/database.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================

class DB {

    var $dbhost = "";
    var $dbname = "";
    var $dbuser = "";
    var $dbpass = "";

    function DB($dbhost, $dbname, $dbuser, $dbpass) {
      $this->dbhost = $dbhost;
      $this->dbname = $dbname;
      $this->dbuser = $dbuser;
      $this->dbpass = $dbpass;
    }

    function connect() {
      if (! $connection = mysql_connect($this->dbhost, $this->dbuser, $this->dbpass)) {
    //	    die();
      }
      if (! mysql_select_db($this->dbname)) {
    //	    die();
      }
      return $connection;
    }

    function check() {
      if (! $connection = mysql_connect($this->dbhost, $this->dbuser, $this->dbpass)) {
          return false;
      }
      if (! mysql_select_db($this->dbname)) {
          return false;
      }
      $this->disconnect();
      return true;
    }

    function disconnect() {
    	mysql_close();
    }
    
    function query($sql_query) {
      $qid = mysql_query($sql_query);
      if (! $qid) {
    //	    die();
      }
      return $qid;
    }
    
    function fetch_array($qid) {
    	return mysql_fetch_array($qid);
    }
    
    function fetch_row($qid) {
    	return mysql_fetch_row($qid);
    }

    function fetch_object($qid) {
    	return mysql_fetch_object($qid);
    }

    function num_rows($qid) {
    	return mysql_num_rows($qid);
    }

    function affected_rows() {
    	return mysql_affected_rows();
    }

    function insert_id() {
    	return mysql_insert_id();
    }

    function free_result($qid) {
    	mysql_free_result($qid);
    }

    function num_fields($qid) {
    	return mysql_num_fields($qid);
    }

    function field_name($qid, $fieldnumber) {
    	return mysql_field_name($qid, $fieldnumber);
    }

    function data_seek($qid, $row) {
    	if ($this->db_num_rows($qid)) { return mysql_data_seek($qid, $row); }
    }
}

?>