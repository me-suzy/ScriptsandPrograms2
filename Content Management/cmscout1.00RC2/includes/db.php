<?php
/**************************************************************************
    FILENAME        :   db.php
    PURPOSE OF FILE :   Database class
    LAST UPDATED    :   08 June 2005
    COPYRIGHT       :   © 2005 CMScout Group
    WWW             :   www.cmscout.za.org
    LICENSE         :   GPL vs2.0
    
    

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
**************************************************************************/
?>
<?php
 class database {
 	var $dbname;
	var $dbhost;
	var $dbusername;
	var $dbpassword;
    var $dbprefix;
	var $busy = false; 
	var $connection;
	var $queryresult = 0;
    var $counter = 0;
    
    function reset_counter()
    {
        $this->counter = 0;
    }

    function get_counter()
    {
        return $this->counter;
    }
	
	function database($dbname, $dbhost, $dbusername, $dbpassword, $dbprefix) 
    {
		$this->dbname = $dbname;
		$this->dbhost = $dbhost;
		$this->dbusername = $dbusername;
		$this->dbpassword = $dbpassword;
        $this->dbprefix = $dbprefix;
		$this->connection = mysql_pconnect($this->dbhost, $this->dbusername, $this->dbpassword);
		$SelectedDB = mysql_select_db($this->dbname) or die(mysql_error());
		
		if ($this->connection) 
        {
			return $this->connection;
		} 
        else 
        {
			return 0;
		}
	} //database
	
	function closedatabase() 
    {
	}//closedatabase
	
	function select_query($tablename="", $special = false, $log=false, $page=false, $desc=false) 
    {
		global $tree, $check, $config, $debug;
        
		if ($this->queryresult != 0) 
        {
			$this->queryresult = 0;
		}
		$sql = "SELECT * FROM {$this->dbprefix}$tablename ";
		if ($special) 
        {
			$sql = $sql . $special;
		}
		if ($config["debug"] == "true") 
        {
			$debug .= $sql . "<br>";
		}

		$query = mysql_query($sql);

		if ($query) 
        {
			$this->queryresult = $query;
            $this->counter++;
			return $this->queryresult;
		} 
        else 
        {
            error_message("Database error with statement $sql. Error was: " . mysql_error());
			return false;
		}
	}//select_query
	
	function insert_query($tablename, $values, $page=false, $desc=false, $log=false) 
    {
		global $tree, $check, $config, $debug;
        
		$query = "";
		if ($this->queryresult != 0) 
        {
			$this->queryresult = 0;
		}	
		$sql = "INSERT INTO {$this->dbprefix}$tablename VALUES ($values)";
		if ($config["debug"] == "true") 
        {
			$debug =  $sql . '<br>';
		}
		$query .= mysql_query($sql);

		if ($query) 
        {
			$this->queryresult = $query;
            $this->counter++;
			return $this->queryresult;
		} 
        else
        {
			error_message("Database error with statement $sql. Error was: " . mysql_error());
			return false;
		}
	}//insert_query
	
	function delete_query($tablename, $where=false, $page=false, $desc=false, $log=false) 
    {
		global $tree, $check, $config, $debug;
		
		if ($this->queryresult != 0) 
        {
			$this->queryresult = 0;
		}
		$sql = "DELETE FROM {$this->dbprefix}$tablename";
		if ($where) {
			$sql = $sql . " WHERE " . $where;
		}
		if ($config["debug"] == "true") 
        {
			$debug .=  $sql . "<br>";
		}
		$query = mysql_query($sql);
		if ($query) 
        {
			$this->queryresult = $query;
            $this->counter++;
			return $this->queryresult;
		} 
        else 
        {
			error_message("Database error with statement $sql. Error was: " . mysql_error());
			return false;
		}
	}//delete_query
	
	function update_query($tablename, $set, $where=false, $page=false, $desc=false, $log=false) 
    {
		global $tree, $check, $config, $debug;
		
		if ($this->queryresult != 0) 
        {
			$this->queryresult = 0;
		}
		$sql = "UPDATE {$this->dbprefix}$tablename SET $set";
		if ($where) 
        {
			$sql = $sql . " WHERE " . $where;
		}
		if ($config["debug"] == "true")
        {
			$debug .=  $sql . "<br>";
		}
		$query = mysql_query($sql);
        if ($query) 
        {
			$this->queryresult = $query;
            $this->counter++;
			return $this->queryresult;
		} 
        else 
        {
			error_message("Database error with statement $sql. Error was: " . mysql_error());
			return false;
		}
	}//update_query
	
	function fetch_array($query=false) 
    {
		if ($query) 
        {
			$array = mysql_fetch_array($query);
			if (isset($array)) 
            {
				return $array;
			}
            else 
            {
    			error_message("Database error with fetch. Error was: " . mysql_error());
				return false;
			}
		} 
        else 
        {
			$array = mysql_fetch_array($this->queryresult);
			if (isset($array)) 
            {
				return $array;
			} 
            else 
            {
    			error_message("Database error with fetch. Error was: " . mysql_error());
				return false;
			}
		}
	}//fetchassoc
	
	function num_rows($query=false) 
    {
		if ($query) {
			$number = mysql_num_rows($query);
			if (isset($number)) 
            {
				return $number;
			} 
            else
            {
    			error_message("Database error with number of rows. Error was: " . mysql_error());
				return false;
			}
		} else {
			$number =  mysql_num_rows($this->queryresult);
			if (isset($number)) 
            {
				return $number;
			} 
            else 
            {
    			error_message("Database error with number of rows. Error was: " . mysql_error());
				return false;
			}
		}
	}//numrows
	
	function free_result($query=false) 
    {
		if ($query) 
        {
			$free = mysql_free_result($query);
			if ($free) 
            {
				return true;
			} 
            else 
            {
				return false;
			}
		} 
        else
        {
			$free =  mysql_free_result($this->queryresult);
			if ($free) 
            {
				return true;
			} 
            else 
            {
				return false;
			}
		}
	}//free_result
	
}
?>