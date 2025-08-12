<?php 
// PHPlib <http://phplib.shonline.de/>
// PHPlib includes for database independence:
// require('db_odbc.inc');
// require('db_mysql.inc');
// require('db_pgsql.inc');
// require('db_oracle.inc');
// require('db_sybase.inc');
// 
// Ran into safe mode restrictions across various cart installs so
// decided to include the whole file inline.  We can either copy
// the file and include it, or we include it here.  The cart scales
// better to include it here.

// We also extend the classes to include free_result(), autocommit(),
// commit() and rollback() class functions.  For mysql these do nothing
// but are in place for compatibility.

// see admin.php also; it is almost identical.

$nsecurl = 'CATALOGURL';
$cartdir = 'DIRECTORY';
$securl  = 'SECUREURL';
$secdir  = 'SECDIR';
$maintdir= 'MAINTDIR';

$pub_inc=1;
$databaseeng = 'DATABASEENG';
$dialect  = 'DIALECT';

class DBbase_Sql {
  var $Host     = "";
  var $Database = "";
  var $User     = "";
  var $Password = "";

  var $Link_ID  = 0;
  var $Query_ID = 0;
  var $Record   = array();
  var $Row;

  var $Errno    = 0;
  var $Error    = "";
  
  var $Auto_free   = 0;   ## Set this to 1 for automatic mysql_free_result()
  var $Auto_commit = 0;   ## set this to 1 to automatically commit results

  function connect() {
    global $FC_Link_ID;
	if( !empty($FC_Link_ID) ){
      $this->Link_ID=$FC_Link_ID;
	}
    if ( 0 == $this->Link_ID ) {
      $this->Link_ID=mysql_pconnect($this->Host, $this->User, $this->Password);
      if (!$this->Link_ID) {
        $this->halt("Link-ID == false, pconnect failed");
      }
      if (!mysql_select_db($this->Database,$this->Link_ID)) {
        $this->halt("cannot use database ".$this->Database);
      }
    }
    mysql_select_db($this->Database,$this->Link_ID);
  }

  function query($Query_String) {
    $this->connect();

#   printf("Debug: query = %s<br>\n", $Query_String);

    $this->Query_ID = mysql_query($Query_String,$this->Link_ID);
    $this->Row   = 0;
    $this->Errno = mysql_errno();
    $this->Error = mysql_error();
    if (!$this->Query_ID) {
      $this->halt("Invalid SQL: ".$Query_String);
    }

    return $this->Query_ID;
  }

  function next_record() {
    $this->Record = mysql_fetch_array($this->Query_ID);
    $this->Row   += 1;
    $this->Errno = mysql_errno();
    $this->Error = mysql_error();

    $stat = is_array($this->Record);
    if (!$stat && $this->Auto_free) {
      mysql_free_result($this->Query_ID);
      $this->Query_ID = 0;
    }
    return $stat;
  }

  function seek($pos) {
    $status = mysql_data_seek($this->Query_ID, $pos);
    if ($status)
      $this->Row = $pos;
    return;
  }

  function metadata($table) {
    $count = 0;
    $id    = 0;
    $res   = array();

    $this->connect();
    $id = @mysql_list_fields($this->Database, $table);
    if ($id < 0) {
      $this->Errno = mysql_errno();
      $this->Error = mysql_error();
      $this->halt("Metadata query failed.");
    }
    $count = mysql_num_fields($id);
    
    for ($i=0; $i<$count; $i++) {
      $res[$i]["table"] = mysql_field_table ($id, $i);
      $res[$i]["name"]  = mysql_field_name  ($id, $i);
      $res[$i]["type"]  = mysql_field_type  ($id, $i);
      $res[$i]["len"]   = mysql_field_len   ($id, $i);
      $res[$i]["flags"] = mysql_field_flags ($id, $i);
      $res["meta"][$res[$i]["name"]] = $i;
      $res["num_fields"]= $count;
    }
    
    mysql_free_result($id);
    return $res;
  }

  function affected_rows() {
    return mysql_affected_rows($this->Link_ID);
  }

  function num_rows() {
    return mysql_num_rows($this->Query_ID);
  }

  function num_fields() {
    return mysql_num_fields($this->Query_ID);
  }

  function nf() {
    return $this->num_rows();
  }

  function np() {
    print $this->num_rows();
  }

  function f($Name) {
    return $this->Record[$Name];
  }

  function p($Name) {
    print $this->Record[$Name];
  }
  
  function halt($msg) {
    printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
    printf("<b>MySQL Error</b>: %s (%s)<br>\n",
      $this->Errno,
      $this->Error);
    die("Session halted.");
  }
}

class FC_SQL extends DBbase_Sql {
  var $Host     = "DATABASEHOST";
  var $Database = "DATABASENAME";
  var $User     = "USERID";
  var $Password = "USERPW";

  function free_result() {
    return @mysql_free_result($this->Query_ID);
  }

  function rollback() {
    return 1;
  }

  function commit() {
    return 1;
  }

  function autocommit($onezero) {
    return 1;
  }

  function insert_id($col="",$tbl="",$qual="") {
    return mysql_insert_id($this->Link_ID);
  }
}
?>
