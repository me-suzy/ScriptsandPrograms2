<?php
/**
 * Celeste Project Source File
 * Celeste 2003 1.1.3 Build 0811
 * Aug 11, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2002 celeste Team. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

Class DB {

  var $connection    = 0;
  var $pConnect      = 1;
  var $showError     = 1;
  var $haltOnError   = 1;

  var $errorNo       = 0;
  var $errorMsg      = '';

  var $selectQueries = 0;
  var $updateQueries = 0;

  /**
   * private
   */
  var $autoFree    = 1;

  
  /**
   * constructor
   */
  function DB( $host = '', $username = '', $password = '', $database = '', $pConnect = SET_USE_PCONNECT, $autoConnect = 1 ) {

    $this->pConnect = $pConnect;

    if( $autoConnect )
      $this->connect($host, $username, $password, $database);
  }

  /**
   * connect to mysql server
   */
  function connect($host, $username, $password, $database) {

    $connect_function = ($this->pConnect) ? 'mysql_pconnect' : 'mysql_connect' ;
    $this->connection = $connect_function($host, $username, $password);
  
    if (!$this->connection) {
      $this->halt("Connect to SQL server use ($host, $username, ******) failed.");
      return false;
    }
    /**
     * select database to use
     */
    if( $database ) {
      if (!@ mysql_select_db($database, $this->connection)) {
        $this->halt('Cannot use database '.$database);
        return false;
      }
    }
  }

  /**
   * disconnect
   */
  function disconnect() {
    return mysql_close($this->connection);
  }


  /**
   * basic method : sql query(for select/show)
   */
  function &query($queryString, $beginRow = 0, $limit = 0) {

    //if($beginRow) $beginRow = $beginRow . ',';
    if($limit) $queryString .= ' LIMIT ' . $beginRow .','. $limit;

    $queryid     = mysql_query($queryString, $this->connection);
    $this->selectQueries++;

    if (!$queryid)  $this->halt('Invalid SQL: ' . $queryString);
    return (new DB_RESULT($queryid));
  }

  /**
   * basic method : sql query(for insert/update/replace)
   */
  function update($queryString) {

    $queryid     =  mysql_query($queryString, $this->connection);
	  //print $queryString."\n";
    $this->updateQueries++;

    if (!$queryid)  {
      $this->halt('Invalid SQL: ' . $queryString);
    }
    return $queryid;
  }


  /**
   * basic method : get the result string from sql query('limit 1' auto appended)
   */
  function result($queryString, $appLimit = 1) {

    if ($appLimit)
      $queryString  .= ' LIMIT 1';

    $queryid    = mysql_query($queryString, $this->connection);
	//print $queryString."\n";
    $this->selectQueries++;

    if (!$queryid)
      $this->halt('Invalid SQL: '.$queryString);

    /**
     * get result
     */
    $result =& mysql_fetch_array($queryid, MYSQL_ASSOC);

    /**
     * no results matching the condition
     */
    if(!$result) return false;

    if($this->autoFree)
      mysql_free_result($queryid);

    return (count($result)==1) ? current($result): $result;
  }


  /**
   * fetch all results into a hash var
   */
  function fetch_all_into_array($queryString, $beginRow = 0, $limit = 0) {
    $array = array();

    if($beginRow) $beginRow     = $beginRow . ',';
    if($limit)    $queryString .= ' LIMIT ' . $beginRow . $limit;

    $queryid     = mysql_query($queryString, $this->connection);
	print $queryString;
    $this->selectQueries++;

    if (!$queryid)  $this->halt('Invalid SQL: '.$queryString);

    /**
     * store the selected data in the array
     */
    while($thisrow =& mysql_fetch_array($queryid, MYSQL_ASSOC)) {
      $array[] = $thisrow;
    }

    if($this->autoFree)
      mysql_free_result($queryid);

    return $array;
  }

  /**
   * get last auto_increment id
   */
  function lastid() {
    //$last_insert_id =& $this->result('SELECT last_insert_id()', 0);
    return mysql_insert_id($this->connection);//(int)$last_insert_id;
  }


  /**
   * get next seq id from $this->seqtable
   * for mysql, use auto_increment
   */
  function nextid($tablename) {
    
    return null;    
    /*
    $seqtable = $this->seqtable;
    list($nextid) = mysql_fetch_array(mysql_query("SELECT nextid FROM $seqtable WHERE tablename='$tablename'"));
    if(!$nextid) {
      mysql_query("INSERT into $seqtable (tablename,nextid) values('$tablename',1)");
      return 1;
    } else {
      mysql_query("UPDATE $seqtable set nextid=nextid+1 WHERE tablename='$tablename'");
      return ++$nextid;
    }
    */
  }


  /**
   * return the number of affected rows
   */
  function affected_rows() {
    return mysql_affected_rows($this->connection);
  }


  /**
   * error handle
   */
  function halt($msg) {
    $this->errorMsg = @ mysql_error($this->connection);
    $this->errorNo  = @ mysql_errno($this->connection);

    if ($this->showError)
      $this->haltmsg($msg);

    if ($this->haltOnError)
      exit();
  }
  function haltmsg($msg) {

    //if(function_exists('Database_error_handle')) {
      /**
       * call the handle named 'Database_error_handle'
       */
      //$this->Database_error_handle($msg . "\n" . $this->errorMsg . '(' .$this->errorNo . ')');
    //} else {

      /**
       * print the error msg
       */
      printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
      printf("<b>MySQL Server Error</b>: %s (%s)<br>\n",
        $this->errorMsg, $this->errorNo);
    //}
  }
/*********************************************************
 * database error handle
 * 
 ********************************************************/
function Database_error_handle($errorMsg) {
	global $celeste;

	$errorMsg = sprintf("\n\n-----------------------------------------------------------------\n".
						"Program: %s\n".
						"Time: %s\n".
						"IP: %s\n".
						"Database error: %s",
							$celeste->thisprog, ( function_exists('getTime') ? getTime(time()) : 'Unknown' ),
							$celeste->ipaddress, $errorMsg);
	/**
	 * record the error
	 */
	$fp = fopen( DATA_PATH.'/log/dberror.txt', 'a' );
	fwrite($fp, $errorMsg);
	fclose($fp);
	
	if(defined('CLIENT_PRIORITY') && CLIENT_PRIORITY < 2) {
		echo $errorMsg;
	}

	/**
	 * display the error page
	 */
	celeste_exception_handle( 'database_error', 0 );
} // end of function 'Database_error_handle'


}



Class DB_RESULT {
  var $result_id     = 0;

  var $record        = array();
  var $Row           = 0;
  var $eof           = true;

  function DB_RESULT($result_id) {
    $this->result_id = $result_id;
    $this->eof       = 0;
  }

  /**
   * pointer to the next record
   */
  function next_record() {
    $this->record = @ mysql_fetch_array($this->result_id, MYSQL_ASSOC);
    $this->Row++;
    $status       = is_array($this->record);
    $this->eof    = !$status;
    return $status;
  }
  function fetchInto(&$arr) {
    $stat = $this->next_record();
    $arr  = $this->record;
    return $stat;
  }

  function fetch() {
    //echo mysql_error();
    return mysql_fetch_array($this->result_id, MYSQL_ASSOC);
  }


  /**
   * offset the record pointer to $pos
   */
  function seek($pos = 0) {
    $status = mysql_data_seek($this->result_id, $pos);
    if ($status) {
      $this->Row = $pos;
      return true;
    } else {
      $rows = $this->rows();
      $this->halt("Seek($pos) failed: result has " . $rows . " rows");
      mysql_data_seek($this->result_id, $rows);
      $this->Row = $rows;
      return false;
    }
  }

  function rows() {
    return mysql_num_rows($this->result_id);
  }
  function fields() {
    return mysql_num_fields($this->result_id);
  }

  function get($Name) {
    return $this->record[$Name];
  }

  function free() {
    mysql_free_result($this->result_id);
    $this->result_id = 0;
  }
}
?>