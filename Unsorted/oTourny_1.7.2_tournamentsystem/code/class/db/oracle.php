<?php if(!defined('CONFIG')) die;

/*****************************************************************************

  http://www.muze.nl/en/software/abstractdb/



  Abstract DB, Oracle module, version 2.0b2



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



  v2.0

  - added query->fieldname()



  v2.0b2

  - changed db->lock() function to always lock in 'exclusive' mode.

  - changed most of the functions to not give PHP warnings, use the

    return values to check if the funtion worked, and the error function

    to get possible database errors.

  - added new db->error() function. It returns the description of the

    last error.



  v2.0b1 first version with the new interface.



*****************************************************************************/



class db {



  var $connect_id;

  var $type;



  function db($database_type="oracle") {

    $this->type="oracle";

  }



  function open($sid="{database}", $home="{host}", $user="{user}", $password="{password}") {

  // $home is the oracle home directory, instead of the usual host name



    PutEnv("ORACLE_HOME=".$home);

    PutEnv("ORACLE_SID=".$sid);

    $this->connect_id=ora_logon($user, $password);

    ora_commitoff($this->connect_id);

    return $this->connect_id;

  }



  function lock($table, $mode="write") {

  // since updates are only visible after a commit

  // a "read" lock is all that's necessary. So lock will

  // always lock the table in 'exclusive' mode in which

  // you can still read the table, but all other queries are blocked.

    $query=new query($this, "lock table $table in exclusive mode");

    $result=$query->result;

    return $result;

  }



  function unlock() {

  // unlocks any and all tables which this process locked

  // oracle unlocks tables on commit (or rollback).

    $query=new query($this, "commit");

    $result=$query->result;

    return $result;

  }



  function nextid($sequence) {

  // uses oracle sequence in a strange query which should garantee one row

  // returned and therefore one update on the sequence :)



    $esequence=ereg_replace("'","''",$sequence);

    if (($query=new query($this, "select $esequence.nextval as nextid from cat where table_name=UPPER('$esequence')")) && $query->getrow()) {

      $nextid=$query->field("nextid");

    } else {

      if ($query->query($this, "create sequence $esequence") && $query->result) {

        $nextid=$this->nextid($sequence);

      } else {

        $nextid=0;

      }

    }

    return $nextid;

  }



  function error() {

    // this will only work correctly from php 3.0.6 and upward.

    return ora_error($this->connect_id);

  }



  function close() {

  // Closes the database connection and frees any query results left.



    ora_commit($this->connect_id);

    if ($this->query_id && is_array($this->query_id)) {

      while (list($key,$val)=each($this->query_id)) {

        @ora_close($val);

      }

    }

    $result=@ora_logoff($this->connect_id);

    return $result;

  }



  function addquery($query_id) {

  // Function used by the constructor of query. Notifies the

  // this object of the existance of a query_result for later cleanup

  // internal function, don't use it yourself.



    $this->query_id[]=$query_id;

  }



};



/********************************* QUERY *********************************/



class query {



  var $result;

  var $row;

  var $cursor;



  function query(&$db, $query) {

  // Constructor of the query object.

  // executes the query, notifies the db object of the query result to clean

  // up later

    if (!$this->cursor) {

      $this->cursor=ora_open($db->connect_id);

      $db->addquery($this->cursor);

    }

    $result=ora_parse($this->cursor, $query);

    if ($result>=0) {

      $this->result=ora_exec($this->cursor);

    } else {

      $this->result=0;

    }

  }



  function getrow() {

  // Gets the next row for processing with $this->field function later.



    return $this->row=ora_fetch($this->cursor);

  }



  function field($field) {

  // get the value of the field with name $field

  // in the current row



    $i=0;

    $result=0;

    while (!$result && $i<Ora_NumCols($this->cursor)) {

      if (Ora_ColumnName($this->cursor, $i)==strtoupper($field)) {

        $result=Ora_GetColumn($this->cursor, $i);

      }

      $i++;

    }

    return $result;

  }



  function fieldname($fieldnum) {

  // return the name of field number $fieldnum

  // only call this after query->getrow() has been called at least once



    return Ora_ColumnName( $this->cursor, $fieldnum );

  }



  function firstrow() {

  // return the current row pointer to the first row

  // (CAUTION: executes the query again!!



    $this->result=ora_exec($this->cursor);

    return $this->getrow();

  }



  function free() {

  // free the oracle result tables



    return @ora_close($this->cursor);

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