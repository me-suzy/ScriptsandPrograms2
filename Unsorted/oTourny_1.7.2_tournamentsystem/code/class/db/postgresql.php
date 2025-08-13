<?php if(!defined('CONFIG')) die;

/*****************************************************************************

  http://www.muze.nl/en/software/abstractdb/



  Abstract DB, PostgreSQL module, version 2.0b2



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

    - fixed a bug in nextid() (typo)

    - added query->fieldname($fieldnum)

    - db->open now uses the new postgres connect string

    - db constructor now tests whether postgres functions are available

      and if not, tries to load the module "pgsql.so".

  v2.0b2

    -  new function db->error() returns errormessage of the last error.

    -  constructor db->db() now sets db->type to "postgresql"



  v2.0b1 first version with the new interface.



*****************************************************************************/



class db {



  var $connect_id;

  var $type;



  function db($database_type="postgresql") {

     $this->type="postgresql";

     if (!function_exists("pg_pconnect")) {

       dl("pgsql.so");

     }

  }



  //open($database="{database}", $host="{host}:{port}", $user="", $password="") {

  //$conn_string = "host=sheep port=5432 dbname=test user=lamb password=bar";

  function open($database = FALSE, $host = FALSE, $user = FALSE, $password = FALSE) {

  // $host::="hostname:port-number"



    //check if its a valid db

    if($database === FALSE)

     die("DB name not given");



    //check if its a valid host

    if($database === FALSE)

     die("Host not given");



    $connstr = "dbname=".$database;



    if ($host !== FALSE && $host != '') {

      list($ip, $port) = split(":", $host);



      //make sure port is an INT

      $port = (INT) $port;



      if($ip != '') //possibly valid

       $connstr .= " host=" . $ip;



      if($port > 0) //possibly valid

       $connstr .= " port=" . $port;

    }

    if ($user !== FALSE) {

      $connstr .= " user=".$user;

    }

    if ($password !== FALSE) {

      $connstr .= " password=".$password;

    }



    //Check if DB string is probably valid

    if($connstr == '')

     die("DB Connect String Empty");



    return $this->connect_id = pg_pconnect($connstr) or die("DB unable to connect: ". pg_last_error());

  }



  function lock($table, $mode="write") {

  // mode may be 'read' or 'write'

  // read does nothing in postgres, since it's transaction based

  // only write mode will actually lock a table.

    if ($mode="write") {

      $query=new query($this, "lock table $table");

      $result=$query->result;

    } else {

      $result=1;

    }

    return $result;

  }



  function unlock() {

  // unlocks any and all tables which this process locked (I think :)

  // postgres unlocks tables on commit.

    $query=new query($this, "commit");

    $result=$query->result;

    return $result;

  }



  function nextid($sequence) {

    $esequence=ereg_replace("'","''",$sequence);

    if (($query=new query($this, "select nextval('$esequence') as nextid")) && $query->getrow()) {

      $nextid=$query->field("nextid");

    } else {

      if ($query->query($this, "create sequence $sequence") && $query->result) {

        $nextid=$this->nextid($sequence);

      } else {

        $nextid=0;

      }

    }

    return $nextid;

  }



  function error() {

    return pg_errormessage($this->connect_id);

  }



  function close() {

  // Closes the database connection and frees any query results left.



    $query=new query($this, "commit");

    if ($this->query_id && is_array($this->query_id)) {

      while (list($key,$val)=each($this->query_id)) {

        @pg_free_result($val);

      }

    }

    $result=@pg_close($this->connect_id);

    return $result;

  }



  function addquery($query_id) {

  // Function used by the constructor of query. Notifies the

  // this object of the existance of a query_result for later cleanup

  // internal function, don't use it yourself.



    $this->query_id[]=$query_id;

  }



};



/*********************************** QUERY *********************************/



class query {



  var $result;

  var $row;

  var $curr_row;



  function query(&$db, $query) {

  // Constructor of the query object.

  // executes the query, notifies the db object of the query result to clean

  // up later

    if ($this->result) {

      $this->free(); // query not called as constructor therefore there may

                     // be something to clean up.

    }



    //Remove all ` - since Postgre doesnt like it

    $query = preg_replace('/`(.*?)`/','\\1',$query);



    $this->result=@pg_Exec($db->connect_id, $query);

    $db->addquery($this->result);

    $this->curr_row=0;

  }



  function getrow() {

  // Gets the next row for processing with $this->field function later.



    $this->row=@pg_fetch_array($this->result, $this->curr_row);

    $this->curr_row++;

    return $this->row;

  }



  function field($field) {

  // get the value of the field with name $field

  // in the current row



    return $this->row[$field];

  }



  function fieldname($fieldnum) {

  // get the name of field number $fieldnum from the current row

  // only call this after a getrow() or firstrow() call



    return pg_fieldname ( $this->result, $fieldnum );

  }



  function firstrow() {

  // return the current row pointer to the first row

  // (CAUTION: may execute the query again!! (e.g. for oracle))



    $this->curr_row=0;

    return $this->getrow();

  }



  function free() {

  // free the postgresql result tables



    return @pg_FreeResult($this->result);

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

   $query =& $querys->query("select * from emails", true);

   //get list

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
