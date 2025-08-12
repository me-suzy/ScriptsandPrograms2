<?php
/* Copyright (C) 2000 Paulo Assis <paulo@coral.srv.br>
 
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

    function phpdbform_db( $database_name, $database_host, $user_name, $user_passwd ) {
        $this->database = $database_name;
        $this->db_host = $database_host;
        $this->auth_name = $user_name;
        $this->auth_pass = $user_passwd;
    }
    function show_error($msg)
    {
        global $show_errors;
        print $msg;
        //if($show_errors) print mysql_error();
        print mysql_error();
    }
    // Connect to database
    function connect()
    {
        global $error_msg;
        $this->conn = mysql_connect( $this->db_host, $this->auth_name, $this->auth_pass );
        if( !$this->conn ) {
            $this->show_error($error_msg[0]);
            return false;
        }
        if( !mysql_select_db( $this->database, $this->conn ) ) {
            $this->$error_msg[1];
            return false;
        }
        return true;
    }
    // Close the connection
    function close()
    {
        mysql_close($this->conn);
    }
    // Do a query
    function query($stmt,$msg="")
    {
        global $error_msg;
        $ret = mysql_query( $stmt, $this->conn );
        if(!$ret) $this->show_error($error_msg[$msg]);
        return $ret;
    }
    function fetch_array( $ret )
    {
        return mysql_fetch_array($ret);
    }
    function fetch_row( $ret )
    {
        return mysql_fetch_row($ret);
    }
    function free_result( $ret )
    {
        mysql_free_result($ret);
    }
    function num_fields( $ret )
    {
        return mysql_num_fields($ret);
    }
    function field_len( $ret, $num )
    {
        return mysql_field_len($ret, $num);
    }
    function field_name( $ret, $num )
    {
        return mysql_field_name( $ret, $num );
    }
    function num_rows( $ret )
    {
        return mysql_num_rows($ret);
    }
    function field_allow_null( $ret, $num )
    {
        //$ret = MySql result set handle
        //$num = record number
        $meta = mysql_fetch_field ($ret, $num);
        if (!$meta) {
            //Information about field not available.
            return -1;
        }
        if ($meta->not_null == 1) return false;
        else return true;
    }
    // Christophe Conduché
    function insert_id()
    {
        return mysql_insert_id( $this->conn );
    }
    function field_type( $ret, $num )
    {
        return mysql_field_type( $ret, $num );
    }

    function get_fields( $table )
    {
        // returns an array with filed properties
        $ret = array();
        $lfields = mysql_query("SHOW FIELDS FROM $table",$this->conn);
        while($row=mysql_fetch_array($lfields))
        {
            $field = $row["Field"];
            $type = strtolower($row["Type"]);
            $type = stripslashes($type);
            $type = str_replace("binary","",$type);
            $type = str_replace("zerofill","",$type);
            $type = str_replace("unsigned","",$type);
            $length = $type;
            $length = strstr($length,"(");
            $length = str_replace("(","",$length);
            $length = str_replace(")","",$length);
            $length = (int)chop($length);
            $type = chop(eregi_replace("\\(.*\\)", "", $type));
            //print "Field: $field - Mysql: ${row["Type"]} - Type: $type - Length: $length<br>";
            $ret[$field]["type"]=$type;
            $ret[$field]["maxlength"]=$length;
        }
        return $ret;
    }
}
?>