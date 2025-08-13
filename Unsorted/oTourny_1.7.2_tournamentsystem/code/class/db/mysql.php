<?php if(!defined('CONFIG')) die;
/*****************************************************************************
  http://www.muze.nl/en/software/abstractdb/

  Abstract DB , MySQL module, version 2.0b3

  Copyright (C) 1998  Muze

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

*****************************************************************************

  for information,comments or bugreports, mail abstractdb@muze.nl

  Changelog:

  v2.0 mar. 2000
    - added query->fieldname()

  v2.0b3 22 jan. 1998
    - db->db() constructor now sets a type variable (db->type) with the
      default value of 'database_type'.
    - new function query->error() which returns a description of the last
      mysql error.
    - changed db->nextid() to use autoincrement capabilities of mysql, code
      contributed by Brian Moon.
    - added check $this->result!=0 in query->getrow
    - added @ on mysql_data_seek in query->firstrow

  v2.0b2 1 dec. 1998
    - fixed 2 small bugs in db->nextid() when db_sequence doesn't exist yet.

  v2.0b1 first version with the new interface.

*****************************************************************************/

class db {

  var $connect_id;
  var $type;

  function db($database_type="mysql") {
    $this->type="mysql";
  }

  function open($database="{database}", $host="{host}", $user="{user}", $password="{password}") {
    $this->connect_id=@mysql_pconnect($host, $user, $password) or $this->connect_id=@mysql_pconnect($host, "root", '');
    if ($this->connect_id) {
      $result=mysql_select_db($database);
      if (!$result) {
        mysql_close($this->connect_id);
        $this->connect_id=$result;
      }
    }
    return $this->connect_id;
  }

  function lock($table, $mode="write") {
  // mode maybe 'read' or 'write'

    $query=new query($this, "lock tables $table $mode");
    $result=$query->result;
    return $result;
  }

  function unlock() {
  // unlocks any and all tables which this process locked

    $query=new query($this, "unlock tables");
    $result=$query->result;
    return $result;
  }

  function nextid($sequence) {
  // Function returns the next available id for $sequence, if it's not
  // already defined, the first id will start at 1.
  // This function will create a table for each sequence called
  // '{sequence_name}_seq' in the current database.
  // Based on code by Brian Moon.

    $esequence=ereg_replace("'","''",$sequence)."_seq";
    $query=new query($this, "REPLACE INTO $esequence values ('', nextval+1)");
    if ($query->result) {
      $nextid=mysql_insert_id($this->connect_id);
    } else {
      $query->query($this, "CREATE TABLE $esequence ( seq char(1)
DEFAULT '' NOT NULL, nextval bigint(20) unsigned DEFAULT '0' NOT NULL auto_increment,
PRIMARY KEY (seq), KEY nextval (nextval) )");
      // there's no way to check if a create table has succeeded except by trying to insert
      // a new value. Since you don't want an endless loop, a recursive call to
      // nextid should not be made:
      $query->query($this, "REPLACE INTO $esequence VALUES ( '', nextval+1 )");
      if ($query->result) {
        $nextid=mysql_insert_id($this->connect_id);
      } else {
        $nextid=0;
      }
    }
    return $nextid;
  }

  function error() {
    return mysql_errno($this->connect_id).": ".mysql_error($this->connect_id);
  }

  function close() {
  // Closes the database connection and frees any query results left.

    if ($this->query_id && is_array($this->query_id)) {
      while (list($key,$val)=each($this->query_id)) {
        @mysql_free_result($val);
      }
    }
    $result=@mysql_close($this->connect_id);
    return $result;
  }

  function addquery($query_id) {
  // Function used by the constructor of query. Notifies the
  // this object of the existance of a query_result for later cleanup
  // internal function, don't use it yourself.

    $this->query_id[]=$query_id;
  }

};

/************************************** QUERY ***************************/

class query {

  var $result;
  var $row;

  function query(&$db, $query="") {
  // Constructor of the query object.
  // executes the query, notifies the db object of the query result to clean
  // up later

    if ($query) {
      if ($this->result) {
        $this->free(); // query not called as constructor therefore there may
                       // be something to clean up.
      }
      $this->result=mysql_query($query, $db->connect_id);
      $db->addquery($this->result);
    }
  }

  function getrow() {
  // Gets the next row for processing with $this->field function later.

    if ($this->result) {
      $this->row= @mysql_fetch_array($this->result);
    } else {
      $this->row=0;
    }
    return $this->row;
  }

  function field($field) {
  // get the value of the field with name $field
  // in the current row

    $result=$this->row[$field];
    return $result;
  }

  function fieldname($fieldnum) {
  // return the name of field number $fieldnum
  // only call this after query->getrow() has been called at least once

    return mysql_field_name( $this->result, $fieldnum );
  }

  function firstrow() {
  // return the current row pointer to the first row
  // (CAUTION: other versions may execute the query again!! (e.g. for oracle))

    $result=@mysql_data_seek($this->result,0);
    if ($result) {
      $result=$this->getrow();
    }
    return $this->row;
  }

  function free() {
  // free the mysql result tables

    return @mysql_free_result($this->result);
  }

};

/************************************** Test ***************************/
 //Called During Install
  //Checks that DB is valid and will work
 function verify_db(){
  $querys = new db_querys($_POST["db_name"], $_POST["db_host"], $_POST["db_user"], $_POST["db_pass"]);

  //quick reference db
  $dbapi =& $querys->db;

  //grab error code if any
  $errors["connect"]["error"] = $dbapi->error();

  if($dbapi->connect_id){ //valid connection
   $errors["connect"]["valid"] = true;

   //get a list of the tables
   $query =& $querys->query("SHOW TABLES", true);
   //get list from mysql
   $query->load();
   //grab any error codes
   $errors["tables"]["error"] = $dbapi->error();

   //make sure db isnt empty
   if(!empty($query->db_data))
    $errors["tables"]["valid"] = true;
   else //db is empty
    $errors["tables"]["valid"] = false;

   unset($query);
  }else //didnt work
   $errors["connect"]["valid"] = false;

  //clean up db
  $querys->cleanup();

  return $errors;
 }

?>