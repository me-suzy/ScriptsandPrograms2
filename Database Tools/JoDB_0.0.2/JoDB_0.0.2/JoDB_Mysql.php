<?php

    /* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

    /*
        Class:          JoDB_Mysql
        Package:        JoDB
        Description:    MySQL driver for JoDB
        Platform:       PHP 5, MySQL 4.1.0 or below
        Author:         Jari Jokinen <jari.jokinen@iki.fi>
        Homepage URL:   http://jari.sigmatic.fi/jodb/
        License:        Free for non-commercial use.
                        For commercial use, contact author.
                        Redistributing the modified source code isn't allowed! 

        Version:        0.0.2
        Released:       2005/07/04
        First release:  2005/05/19
    */

    require_once 'JoDB_Common.php';

    class JoDB_Mysql extends JoDB_Common {

        public function __construct($settings) {
            $this->username = $settings['username'];
            $this->password = $settings['password'];
            $this->database = $settings['database'];
            $this->hostname = $settings['hostname'];
            $this->hostport = $settings['hostport'];
        }

        // DBMS methods

        private function error() {
            return mysql_error($this->connection);
        }
        
        public function connect($settings = NULL) {

            // Method:  JoDB_Mysql::connect()
            // Action:  Connect to DBMS
            // Params:  0: Settings (array) = NULL
            // Return:  TRUE if success (boolean)

            if ($settings) {
                $this->username = $settings['username'];
                $this->password = $settings['password'];
                $this->database = $settings['database'];
                $this->hostname = $settings['hostname'];
                $this->hostport = $settings['hostport'];
            }

            // Connect to DBMS
            $this->connection = mysql_connect(
                $this->hostname . ':' . $this->hostport,
                $this->username,
                $this->password
            );
            if (!$this->connection)
            throw new JoDB_Exception(mysql_error(), __CLASS__, __METHOD__);

            // Select database
            $val = mysql_select_db($this->database, $this->connection);
            if (!$val)
            throw new JoDB_Exception($this->error(), __CLASS__, __METHOD__);

            return TRUE;
        
        }
        
        public function disconnect() {

            // Method:  JoDB_Mysql::disconnect()
            // Action:  Disconnect from DBMS
            // Params:  -
            // Return:  TRUE if success (boolean)

            if (!empty($this->result))
            @mysql_free_result($this->result);

            $val = mysql_close($this->connection);
            if (!$val)
            throw new JoDB_Exception($this->error(), __CLASS__, __METHOD__);

            unset($this->result, $this->connection, $this);
            
            return TRUE;

        }
        
        public function query($query) {

            // Method:  JoDB_Mysql::query()
            // Action:  Execute query on database (SELECT, SHOW, etc.)
            // Params:  0: SQL query (string)
            // Return:  Number of rows returned (integer)

            // Execute query on database
            $this->result = mysql_query($query, $this->connection);
            if (!$this->result)
            throw new JoDB_Exception($this->error(), __CLASS__, __METHOD__);

            // Get number of rows and fields returned
            $this->numRows = mysql_num_rows($this->result);
            $this->numCols = mysql_num_fields($this->result);

            return $this->numRows;
            
        }
        
        public function execute($query) {

            // Method:  JoDB_Mysql::execute()
            // Action:  Execute query on database (INSERT, DELETE, etc.)
            // Params:  0: SQL query (string)
            // Return:  Last inserted ID (string)

            // Execute query on database
            $val = mysql_query($query, $this->connection);
            if (!$val)
            throw new JoDB_Exception($this->error(), __CLASS__, __METHOD__);

            // Get number of rows returned and last inserted ID
            $this->numRows = mysql_num_rows($this->result);
            $this->lastID = mysql_insert_id($this->connection);
            
            return $this->lastID;
            
        }

        public function getRow($query = NULL) {

            // Method:  JoDB_Mysql::getRow()
            // Action:  Fetch single row from database
            // Params:  0: SQL query (string) = NULL
            // Return:  Row (array)

            if ($query) $this->query($query);
            return mysql_fetch_array($this->result, MYSQL_BOTH);
            
        }
        
        public function quote(&$value) {

            // Method:  JoDB_Mysql::quote()
            // Action:  Quote and escape value, in other words: make it safe
            // Params:  Value (string)
            // Return:  TRUE if success (boolean)

            // Strip slashes
            if (get_magic_quotes_gpc()) {
                $value = stripslashes($value);
            }
   
            // Quote if not numeric
            if (!is_numeric($value)) {
                $value = "'" . mysql_real_escape_string($value) . "'";
            }

            return TRUE;
        
        }

    }

?>
