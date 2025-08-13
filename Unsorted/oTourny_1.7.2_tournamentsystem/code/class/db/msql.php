<?php if(!defined('CONFIG')) die;

/*****************************************************************************

  http://www.muze.nl/en/software/abstractdb/



  Abstract DB, mSQL module, version 2.0a



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



*****************************************************************************/



class db {



  var $connect_id;



  function db($database_type="msql") {

    // dl("msql");

  }



  function open($database="{database}", $host="{host}", $user="{user}", $password="{password}") {

    $this->connect_id=msql_pconnect($host, $user, $password);

    if ($this->connect_id) {

      $result=msql_select_db($database);

      if (!$result) {

        msql_close($this->connect_id);

        $this->connect_id=$result;

      }

    }

    return $this->connect_id;

  }



  function lock($table, $mode="write") {

    // msql doesn't support locking, duh!

    return 0;

  }



  function unlock() {

    return 0;

  }



  function nextid($sequence) {

    // msql 2 has sequences, but you can have only one per table...

    // ergo, the only way to do this is to create a table per sequence :)

    // this is possible since sequnce names can't be the same as a table name

    // in 'normal' databases anyway.

    $esequence=ereg_replace("'","''",$sequence);

    if ($query=new query($this->db, "select _seq from $esequence_seq") && $query->getrow()) {

      $result=$query->field("_seq");

    } else {

      $query->query($this->db, "create table $esequence_seq ()");

      $query->query($this->db, "create sequence on $esequence_seq ");

      $result=$this->nextid($sequence);

    }

    return $result;

  }





  function error() {

    return msql_error();

  }



  function close() {

  // Closes the database connection and frees any query results left.



    if ($this->query_id && is_array($this->query_id)) {

      while (list($key,$val)=each($this->query_id)) {

        @msql_free_result($val);

      }

    }

    $result=@msql_close($this->connect_id);

    return $result;

  }



  function addquery($query_id) {

  // Function used by the constructor of query. Notifies the

  // this object of the existance of a query_result for later cleanup

  // internal function, don't use it yourself.



    $this->query_id[]=$query_id;

  }



};





/*************************************** QUERY ***************************/



class query {



  var $result;

  var $row;



  function query(&$db, $query) {

  // Constructor of the query object.

  // executes the query, notifies the db object of the query result to clean

  // up later

    if ($this->result) {

      $this->free(); // query not called as constructor therefore there may

                     // be something to clean up.

    }

    $this->result=msql_query($query, $db->connect_id);

    $db->addquery($this->result);

  }



  function getrow() {

  // Gets the next row for processing with $this->field function later.



    $this->row=msql_fetch_array($this->result);

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



    return msql_fieldname( $this->result, $fieldnum );

  }



  function firstrow() {

  // return the current row pointer to the first row

  // (CAUTION: may execute the query again!! (e.g. for oracle))



    $result=msql_data_seek($this->result,0);

    if ($result) {

      $result=$this->getrow();

    }

    return $this->row;

  }



  function free() {

  // free the msql result tables



    return @msql_free_result($this->result);

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