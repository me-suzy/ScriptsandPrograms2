<?php 
/**
* Basic MySQL functions.
*
* 
* @author Niels Hoffmann <niels.hoffmann@freenet.de>
* @version 1.0.0; 2002/21/08; 10:00:00
*/
Class DatabaseMysql extends systemObject {
	/**
	* the connection pointer to the MySQL Database
	* @var	integer
	*/
	var $connection = "";
	/**
	* the error code
	* @var	string
	*/
	var $error = -1;
	/**
	* the error message if something went wrong
	* @var	string
	*/
	var $error_message = "";
	/**
	* the user login for the MySQL
	* @var	string
	*/
	var $user = "";
	/**
	* the password login for the MySQL
	* @var	string
	*/
	var $pass = "";
	/**
	* the server URL to the MySQL
	* @var	string
	*/
	var $server = "";
	/**
	* the port number for the MySQL
	* @var	string
	*/
	var $port = "";
	/**
	* the database name at the MySQL
	* @var	string
	*/
	var $dbase = "";

	/**
	* definition of the logging codes
	* @var	array
	*/
	var $arrLoggingCodes = array (
		'errors' => array (
			'0' => "general Error",
			'010001' => "Database not available",
			'010002' => "Database not selectable",
			'010003' => "No SQL string defined",
			'010004' => "Error in SQL string",
			'010005' => "Database not available",
			'010006' => "Object Error",
			'031101' => "Datenbankverbindungsfehler",
			'031201' => "Datenbankverbindungsfehler",
			'031301' => "Datenbankverbindungsfehler",
			'031401' => "Datenbankverbindungsfehler",
			'031501' => "Datenbankverbindungsfehler",
		),
		'warnings' => array (
			'0' => "general Warning",
		),
		'notices' => array (
			'0' => "general Notice",
		),
		'debug' => array (
			'0' => "general Debug"
		)
	);
	
	/**
	* initializes the object
	* @access		public
	*/	
	function DatabaseMysql($mysql_user, $mysql_password, $mysql_server, $mysql_databasename = "", $mysql_port = "3306") {
		$this->error = -1;
		$this->systemObject();
		$this->user = $mysql_user;
		$this->pass = $mysql_password;
		$this->server = $mysql_server;
		$this->port = $mysql_port;
		$this->dbase = $mysql_databasename;
	} // end func DatabaseMysql
	
	/**
	* Initializes the databse connection
	* @access		public
	* @return integer database connection pointer, or <false> if an error occures.
	*/
	function init () {
		$this->error = -1;
		$conn = @mysql_pconnect($this->server . ":" . $this->port ,$this->user,$this->pass);
		if (!$conn) $conn = @mysql_connect($this->server . ":" . $this->port ,$this->user,$this->pass);
		//echo $conn;
		if(!$conn) {
			$this->writeErrorLog("010001", mysql_errno().": ". mysql_error(), "init");	
			$this->error = 10001;
			return false;
		}
  		if ($this->dbase) {
			if(!mysql_select_db($this->dbase,$conn)) {
				$this->writeErrorLog("010002", mysql_errno().": ". mysql_error(), "init");	
				$this->error = 10002;
				return false;
			}
  		}
		$this->connection = $conn;
		return $conn;
	} // end func init
  
//	*****************************************************************
//											MySQL Specific methods
//	*****************************************************************
  
  	/**
	* Executes a select query on the database and returns the result-set
	* @access		public
	* @param string $sql complete sql select-string 
	* @return array an 2d-assoziative array of the result or <false> if an error occures
	*/
  	function select ($sql="") {
		$this->error = -1;
  		if(empty($sql)) { 
			$this->writeErrorLog("010003", "", "select");	
			$this->error = 10003;
			return false; 
		}
  		if(!eregi("^select",$sql))
  		{
			$this->writeErrorLog("0", "Not a SQL select string", "select");	
			$this->error = 0;
  			return false;
  		}
  		if(empty($this->connection)) {
			$this->writeErrorLog("010001", "No connection available", "select");	
			$this->error = 10004;
			return false; 
		}
  		$conn = $this->connection;
		if ($this->debug_on) $this->writeDebugLog(0, $sql, "select");
		$results = mysql_query($sql,$conn);
  		if( (!$results) or (empty($results)) ) {
  			mysql_free_result($results);
  			return false;
  		}
  		$count = 0;
  		$data = array();
  		while ( $row = mysql_fetch_array($results))
  		{
  			$data[$count] = $row;
  			$count++;
  		}
  		mysql_free_result($results);
  		return $data;
  	} // end func select
  
  	/**
	* executes an instert query on the database
	* @access		public
	* @param string $sql complete sql insert-string 
	* @return integer the id number of the auto_increment field (if exists) or <false> if an error occures
	*/
  	function insert ($sql="") 	{
		$this->error = -1;
  		if(empty($sql)) {
			$this->writeErrorLog("010003", "", "insert");	
			$this->error = 10003;
			return false; 
		}
  		if(!eregi("^insert",$sql))
  		{
			$this->writeErrorLog("0", "Not a SQL insert string", "insert");	
			$this->error = 0;
  			return false;
  		}
  		if(empty($this->connection)) {
			$this->writeErrorLog("010001", "No connection available", "insert");	
			$this->error = 10001;
			return false; 
		}
  		$conn = $this->connection;
		if ($this->debug_on) $this->writeDebugLog(0, $sql, "insert");
  		$results = mysql_query($sql,$conn);
  		if(!$results) { return false; }
  		$results = mysql_insert_id();
  		return $results;
  	} // end func insert
	
  	/**
	* executes any sql query on the database
	* @access		public
	* @param string $sql complete sql string 
	* @return integer the error code of the sql query or <false> if an error occures
	*/
	function execute($sql="") {
		$this->error = -1;
		if ($this->debug_on) $this->writeDebugLog(0, $sql, "execute");
		if (!$code =  @mysql_query($sql, $this->connection)) {
			$this->writeErrorLog("0", mysql_errno().": ". mysql_error(), "execute");	
			$this->error = 0;
			return false;
		}
		return $code;
	} // end func execute
	
  	/**
	* returns the number of table rows
	* @access		public
	* @param string $table name of the sql table in the database
	* @param string $where_string optional where string to specify the counted table rows
	* @return integer number of tablerows in the specified table
	*/
	function getCount($table, $where_string = "") {
		$this->error = -1;
		$sql = "SELECT count(*) FROM ".$table;
		if (trim ($where_string) != "") $sql .= " " . $where_string;
		if (!$res = $this->select($sql)) {
			$this->writeErrorLog("0", mysql_errno().": ". mysql_error(), "getCount");	
			$this->error = 0;
			return false;
		}
		return $res[0]['count(*)'];
	} // end func getCount
	

  	/**
	* returns the actual MySQL error message
	* @access		public
	* @return	string	$error_text	the error message
	*/
	function getError() {
		return "MySQL Error: ".mysql_errno().": ".mysql_error()."<BR>";
	} // end func getError
	
  	/**
	* returns an array of all tablenames of the specified database
	* @access		public
	* @param	string	$database	name of the database to get the tablelist from
	* @return	array	$a_tablenames	assoziative 2d-array of the tablenames
	*/
	function getTables($database = "") {
		$this->error = -1;
		if ($database == "") $database = $this->mysql_databasename;
		if (!$result = mysql_list_tables($database)) {
			$this->writeErrorLog("0", mysql_errno().": ". mysql_error(), "getTables");	
			$this->error = 0;
			return false;
		}
		$counter = 0;
		$a_tablenames = array();
		while($row = mysql_fetch_array($result)) {
			$a_tablenames[$counter] = $row[0];
			$counter++;
		}
		return $a_tablenames;
	} // end func getTables
	
  	/**
	*returns an array of all fieldnames of the given table
	* @access		public
	* @param	string	$tablename	specifies the tablename to get the fields from
	* @return	array	$a_tablefields	assoziative 2d-array of all tablefields
	*/
	function getTableFields($tablename) {
		$this->error = -1;
		if (!$result = mysql_list_fields ($this->dbase, $tablename) ) {
			$this->writeErrorLog("0", mysql_errno().": ". mysql_error(), "getTableFields");	
			$this->error = 0;
			return false;
		}
		$counter = 0;
		$a_tablefields = array();
		//echo mysql_num_fields($result);
		for ($i=0; $i < mysql_num_fields($result); $i++) {
			$a_tablefields[$counter] =  mysql_field_name ($result, $counter);
			$counter++;
		}
		return $a_tablefields;
	} // end func getTableFields

  	/**
	*returns an array of all fieldnames of the given table
	* @access		public
	* @param	string	$tablename	specifies the tablename to get the fields from
	* @return	array	$a_tablefields	assoziative 2d-array of all tablefields
	*/
	function describeTable($tablename) {
		$this->error = -1;
		if (!$results =  mysql_query("DESCRIBE " . $tablename, $this->connection)) {
			$this->writeErrorLog("0", mysql_errno().": ". mysql_error(), "getTableFields");	
			$this->error = 0;
			return false;
		}
  		while ( $row = mysql_fetch_array($results))
  		{
  			$data[] = $row;
  		}
		return $data;
	} // end func getTableFields

} // end class DatabaseMysql



?>