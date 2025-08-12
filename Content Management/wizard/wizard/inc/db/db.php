<?php
/* 
	Generic MySQL database access class   
	(c) 2005 Philip Shaddock, www.wizardinteractive.com
	This file is part of the Wizard Site Framework.

    This file is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    It is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with the Wizard Site Framework; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class DB
{
    var $dbhost = 'localhost';            
    var $dblogin;                         
    var $dbpass;                          
    var $dbname;                          
    var $dblink;                          
    var $queryid;                         
    var $error = array();                 
    var $record = array();                
    var $totalrecords;                    
    var $last_insert_id;                  
    var $previd = 0;                      
    var $transactions_capable = false;    
    var $begin_work = false;              

    
    function get_dbhost()
    {
        return $this->dbhost;

    } // end function

    function get_dblogin()
    {
        return $this->dblogin;

    } // end function

    function get_dbpass()
    {
        return $this->dbpass;

    } // end function

    function get_dbname()
    {
        return $this->dbname;

    } // end function

    function set_dbhost($value)
    {
        return $this->dbhost = $value;

    } // end function

    function set_dblogin($value)
    {
        return $this->dblogin = $value;

    } // end function

    function set_dbpass($value)
    {
        return $this->dbpass = $value;

    } // end function

    function set_dbname($value)
    {
        return $this->dbname = $value;

    } // end function

    function get_errors()
    {
        return $this->error;

    } // end function

    
    function DB()
    {
	
	    $dblogin = DB_USER;
		$dbpass = DB_PASS;
		$dbname = DB_DATABASE;
		$dbhost = DB_SERVER; 
	
        $this->set_dblogin($dblogin);
        $this->set_dbpass($dbpass);
        $this->set_dbname($dbname);

        if ($dbhost != null) {
            $this->set_dbhost($dbhost);
        }

    } // end function

    
    function connect()
    {
        $this->dblink = @mysql_pconnect($this->dbhost, $this->dblogin, $this->dbpass);

        if (!$this->dblink) {
            $this->return_error('Unable to connect to the database.');
        }

        $t = @mysql_select_db($this->dbname, $this->dblink);

        if (!$t) {
            $this->return_error('Unable to change databases.');
        }

        if ($this->serverHasTransaction()) {
            $this->transactions_capable = true;
        }

        return $this->dblink;

    } // end function

    function close()
    {
        $test = @mysql_close($this->dblink);

        if (!$test) {
            $this->return_error('Unable to close the connection.');
        }

        unset($this->dblink);

    } // end function

    
    function return_error($message)
    {
        return $this->error[] = $message.' '.mysql_error().'.';

    } // end function

    
    function showErrors()
    {
        if ($this->hasErrors()) {
            reset($this->error);

            $errcount = count($this->error);    //count the number of error messages

            echo "<p>Error(s) found: <b>'$errcount'</b></p>\n";

            // print all the error messages.
            while (list($key, $val) = each($this->error)) {
                echo "+ $val<br>\n";
            }

            $this->resetErrors();
        }

    } // end function

    
    function hasErrors()
    {
        if (count($this->error) > 0) {
            return true;
        } else {
            return false;
        }

    } // end function

    
    function resetErrors()
    {
        if ($this->hasErrors()) {
            unset($this->error);
            $this->error = array();
        }

    } // end function

    
    function query($sql)
    {
        if (empty($this->dblink)) {
            // check to see if there is an open connection. If not, create one.
            $this->connect();
        }

        $this->queryid = @mysql_query($sql, $this->dblink);

        if (!$this->queryid) {
            if ($this->begin_work) {
                $this->rollbackTransaction();
            }

            $this->return_error('Unable to perform the query <b>' . $sql . '</b>.');
        }

        $this->previd = 0;

        return $this->queryid;

    } // end function

    
    function next_record()
    {
        if (isset($this->queryid)) {
            $this->previd++;
            return $this->record = @mysql_fetch_array($this->queryid);
        } else {
            $this->return_error('No query specified.');
        }

    } // end function

    
    function move_first()
    {
        if (isset($this->queryid)) {
            $t = @mysql_data_seek($this->queryid, 0);

            if ($t) {
                $this->previd = 0;
                return $this->next_record();
            } else {
                $this->return_error('Cant move to the first record.');
            }
        } else {
            $this->return_error('No query specified.');
        }

    } // end function

    
    function move_last()
    {
        if (isset($this->queryid)) {
            $this->previd = $this->resultCount()-1;

            $t = @mysql_data_seek($this->queryid, $this->previd);

            if ($t) {
                return $this->next_record();
            } else {
                $this->return_error('Cant move to the last record.');
            }
        } else {
            $this->return_error('No query specified.');
        }

    } // end function

    
    function move_next()
    {
        return $this->next_record();

    } // end function

    
    function move_prev()
    {
        if (isset($this->queryid)) {
            if ($this->previd > 1) {
                $this->previd--;

                $t = @mysql_data_seek($this->queryid, --$this->previd);

                if ($t) {
                    return $this->next_record();
                } else {
                    $this->return_error('Cant move to the previous record.');
                }
            } else {
                $this->return_error('BOF: First record has been reached.');
            }
        } else {
            $this->return_error('No query specified.');
        }

    } // end function


    
    function lastId()
    {
        $this->last_insert_id = @mysql_insert_id($this->dblink);

        if (!$this->last_insert_id) {
            $this->return_error('Unable to get the last inserted id from MySQL.');
        }

        return $this->last_insert_id;

    } // end function

    
    function num_rows()
    {
        $this->totalrecords = @mysql_num_rows($this->queryid);

        if (!$this->totalrecords) {
            $this->return_error('Unable to count the number of rows returned');
        }

        return $this->totalrecords;

    } // end function
	
	
	 function affected_rows() {
		return mysql_affected_rows($this->dblink);
	}

    function resultExist()
    {
        if (isset($this->queryid) && ($this->num_rows() > 0)) {
            return true;
        }

        return false;

    } // end function

    
    function clear($result = 0)
    {
        if ($result != 0) {
            $t = @mysql_free_result($result);

            if (!$t) {
                $this->return_error('Unable to free the results from memory');
            }
        } else {
            if (isset($this->queryid)) {
                $t = @mysql_free_result($this->queryid);

                if (!$t) {
                    $this->return_error('Unable to free the results from memory (internal).');
                }
            } else {
                $this->return_error('No SELECT query performed, so nothing to clear.');
            }
        }

    } // end function

    
    function serverHasTransaction()
    {
	
        $this->query('SHOW VARIABLES');

        if ($this->resultExist()) {
            while ($this->next_record()) {
                if ($this->record['Variable_name'] == 'have_bdb' && $this->record['Value'] == 'YES') {
                    $this->transactions_capable = true;
                    return true;
                }

                if ($this->record['Variable_name'] == 'have_gemini' && $this->record['Value'] == 'YES') {
                    $this->transactions_capable = true;
                    return true;
                }

                if ($this->record['Variable_name'] == 'have_innodb' && $this->record['Value'] == 'YES') {
                    $this->transactions_capable = true;
                    return true;
                }
            }
        }

        return false;

    } // end function

    
    function beginTransaction()
    {
        if ($this->transactions_capable) {
            $this->query('BEGIN');
            $this->begin_work = true;
        }

    } // end function

    
    function commitTransaction()
    {
        if ($this->transactions_capable) {
            if ($this->begin_work) {
                $this->query('COMMIT');
                $this->begin_work = false;
            }
        }
    }

    
    function rollbackTransaction()
    {
        if ($this->transactions_capable) {
            if ($this->begin_work) {
                $this->query('ROLLBACK');
                $this->begin_work = false;
            }
        }

    } // end function

} // end class

?>