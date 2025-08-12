<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0
	
/** Objects of this class desrcibe a database connection.
* Here because of legacy.
*/
class DatabaseConnection {
	var $host;
	var $port;
	var $password;
	var $username;
	var $databaseName;
	var $dbSource;
	
	function DatabaseConnection($value="") {
		// constructor that sets the connection
		
		if ($value == "default") { $this -> setDefaults(); }
		
	}
	
	function setDefaults() {
		$this->makeConnection();
	}
	
	function makeConnection() {
		$this -> connect($this ->getHost(),
					$this ->getPort(),
					$this ->getUsername(),
					$this ->getPassword(),
					$this ->getDatabaseName());
	}
	
	function setHost($value) {
		// sets the host
		$this->host=$value;
	}
	
	function setPassword($value) {
		// sets the password
		$this->password=$value;
	}
	
	function setUsername($value) {
		// sets the username
		$this->username=$value;
	}
	
	function setDatabaseName($value) {
		// sets the databaseName
		$this->databaseName=$value;
	}
	
	function setPort($value) {
		// sets the port#
		$this->port=$value;
	}
	
	function getHost() {
		// returns the host without the port#
		return $this->host;
	}
	
	function getPort() {
		//returns the port#
		return $this->port;
	}
	
	function getPassword() {
		//returns the password
		return $this->password;
	}
	
	function getUsername() {
		//returns the  username
		return $this->username;		
	}
	
	function getDatabaseName() {
		// returns the databasename
		return $this->databaseName;
	}
	
	
	function connect($host,$port,$username,$password,$dbName) {
		// establishes the connection and selects the db
		$hostport="$host:$port";
		$this->dbSource = mysql_connect($hostport,$username,$password);
		mysql_select_db($dbName,$this->dbSource);
	}
	
	function getDBSource() {
		// returns the dbSource
		return $this->dbSource;
	}
	
		
}
?>