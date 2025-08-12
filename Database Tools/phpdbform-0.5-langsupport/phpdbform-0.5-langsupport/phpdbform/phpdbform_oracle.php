<?php
/* Copyright (C) 2000 Paulo Assis <paulo@coral.srv.br>
				 2003 Elton Minetto <minetto@unochapeco.rct-sc.br>
 
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
Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.  */

class phpdbform_db {
    var $database;
    var $db_host;
    var $auth_name;
    var $auth_pass;
    var $conn;
    var $cont = 0;

    function phpdbform_db( $database_name, $database_host, $user_name, $user_passwd ) { // OK
        $this->database = $database_name;
        $this->db_host = $database_host;
        $this->auth_name = $user_name;
        $this->auth_pass = $user_passwd;
    }
    function show_error($msg)
    {
        global $show_errors;
        print $msg;
    }
    // Connect to database
    function connect() 
    {
        global $error_msg;
        $this->conn = ocilogon($this->auth_name,$this->auth_pass,$this->database);
        if( !$this->conn ) {
            $this->show_error("Connection Error");
            return false;
        }
        return true;
    }
    // Close the connection
    function close()
    {
    	ocicommit($this->conn);
        OCILogOff ($this->conn); 
    }
    // Do a query
    function query($stmt,$msg) 
    {
        global $error_msg;
		$ret = ociparse($this->conn,$stmt);        
        if(!$ret) 
        	$this->show_error(ocierror($this->stmt));
        else 
        	ociexecute($ret);	
        return $ret;
    }
    function fetch_array( $ret )
    {
    	$tmp = OCIFetchInto($ret,$results);
    	for($i=0;$i<$tmp;$i++)
    	{
    		$vet[$i] = $results[$i];
    	}	
    	for($i=1;$i<=$tmp;$i++)
    	{
    		$vet[strtolower($this->field_name( $ret, $i ))] = $results[$i-1];
    	}	
    	return $vet;
    }
    function fetch_row( $ret )
    {
    	$tmp = OCIFetchInto($ret,$results);
    	for($i=0;$i<$tmp;$i++)
    	{
    		$vet[$i] = $results[$i];
    	}	
    	for($i=1;$i<=$tmp;$i++)
    	{
    		$vet[strtolower($this->field_name( $ret, $i ))] = $results[$i-1];
    	}	
    	return $vet;
        
    }
    function free_result( $ret )
    {
        OCIFreeStatement($ret);
    }
    function num_fields( $ret )
    {
        return OCINumCols($ret);
    }
    function field_len( $ret, $num ) 
    {
        return OCIColumnSize($ret,$num);
    }
    function field_name( $ret, $num )
    {
        return OCIColumnName($ret,$num);
    }
    function num_rows( $ret )
    {
        return OCIRowCount($ret);
    }
    function field_allow_null( $ret, $num ) //TODO
    {
    	//echo "field_allow_null<br>";
        //$meta = mysql_fetch_field ($ret, $num);
        //if (!$meta) {
            //Information about field not available.
            //return -1;
        //}
        //if ($meta->not_null == 1) return false;
        //else return true;
        return 1;
    }
    // Christophe Conduché
    function insert_id() //TODO
    {
    	//echo "insert_id<br>";
        //return mysql_insert_id( $this->conn );
        return 0;
    }
    function field_type( $ret, $num )
    {
        return OCIColumnType($ret,$num);
    }

    function get_fields( $table )
    {
        $ret = array();
		$stmt = ociparse($this->conn,"select * from $table");        
		OCIExecute($stmt);
		$ncols = OCINumCols($stmt);
		for ( $i = 1; $i <= $ncols; $i++ ) 
		{
			$column_name  = OCIColumnName($stmt,$i);
			$column_type  = OCIColumnType($stmt,$i);
			$column_size  = OCIColumnSize($stmt,$i);
			$field = strtolower($column_name);
			$ret[$field]["type"] = strtolower($column_type);
			$ret[$field]["maxlength"] = $column_size;
		}	        
        return $ret;
    }
}
?>