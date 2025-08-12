<?php

    /* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

    /*
        Class:          JoDB_Pgsql
        Package:        JoDB
        Description:    PostgreSQL driver for JoDB
        Platform:       PHP 5, PostgreSQL 6.5 or above
        Author:         Jari Jokinen <jari.jokinen@iki.fi>
        Homepage URL:   http://jari.sigmatic.fi/jodb/
        License:        Free for non-commercial use.
                        For commercial use, contact author.
                        Redistributing the modified source code isn't allowed!

        Version:        0.0.1
        Released:       2005/05/19
        First release:  2005/05/19
    */

    require_once 'JoDB_Common.php';

    class JoDB_Pgsql extends JoDB_Common {

        public function __construct($settings) {
            $this->username = $settings['username'];
            $this->password = $settings['password'];
            $this->database = $settings['database'];
            $this->hostname = $settings['hostname'];
            $this->hostport = $settings['hostport'];
        }

        // DBMS methods

        private function error($isResult = FALSE) {
            if ($isResult) {
                return pg_result_error($this->result);
            }
            else {
                return pg_last_error($this->connection);
            }
        }
        
        public function connect($settings = NULL) {

            // Method:  JoDB_Pgsql::connect()
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
            $this->connection = pg_connect(
                'host='      . $this->hostname .
                ' port='     . $this->hostport .
                ' dbname='   . $this->database .
                ' user='     . $this->username .
                ' password=' . $this->password
            );
            if (pg_connection_status($this->connection) !== 0)
            throw new JoDB_Exception($this->error(), __CLASS__, __METHOD__);

            return TRUE;
        
        }
        
        public function disconnect() {

            // Method:  JoDB_Pgsql::disconnect()
            // Action:  Disconnect from DBMS
            // Params:  -
            // Return:  TRUE if success (boolean)

            pg_free_result($this->result);

            $val = pg_close($this->connection);
            if (!$val)
            throw new JoDB_Exception($this->error(), __CLASS__, __METHOD__);

            unset($this->result, $this->connection, $this);
            
            return TRUE;

        }
        
        public function query($query) {

            // Method:  JoDB_Pgsql::query()
            // Action:  Execute query on database (SELECT, SHOW, etc.)
            // Params:  0: SQL query (string)
            // Return:  Number of rows returned (integer)

            // Execute query on database
            $this->result = pg_query($this->connection, $query);
            if (!$this->result)
            throw new JoDB_Exception($this->error(TRUE), __CLASS__, __METHOD__);

            // Get number of rows and fields returned
            $this->numRows = pg_num_rows($this->result);
            $this->numCols = pg_num_fields($this->result);

            return $this->numRows;
            
        }
        
        public function execute($query) {

            // Method:  JoDB_Pgsql::execute()
            // Action:  Execute query on database (INSERT, DELETE, etc.)
            // Params:  0: SQL query (string)
            // Return:  Last inserted ID (string)

            // Execute query on database
            $val = pg_query($this->connection, $query);
            if (!$val)
            throw new JoDB_Exception($this->error(TRUE), __CLASS__, __METHOD__);

            // Get number of rows returned and last inserted ID
            $this->numRows = pg_affected_rows($this->result);
            $this->lastID = pg_last_oid($this->result);
            
            return $this->lastID;
            
        }

        public function getRow($query = NULL) {

            // Method:  JoDB_Pgsql::getRow()
            // Action:  Fetch single row from database
            // Params:  0: SQL query (string) = NULL
            // Return:  Row (array)

            if ($query) $this->query($query);
            return pg_fetch_array($this->result, NULL, PGSQL_BOTH);
            
        }
        
        public function quote(&$value) {

            // Method:  JoDB_Pgsql::quote()
            // Action:  Quote and escape value, in other words: make it safe
            // Params:  Value (string)
            // Return:  TRUE if success (boolean)

            // Strip slashes
            if (get_magic_quotes_gpc()) {
                $value = stripslashes($value);
            }
   
            // Quote if not numeric
            if (!is_numeric($value)) {
                $value = "'" . pg_escape_string($value) . "'";
            }

            return TRUE;
        
        }

    }

?>
