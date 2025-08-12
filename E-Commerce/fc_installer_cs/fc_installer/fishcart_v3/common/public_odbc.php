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
  var $Row      = 0;
  
  var $Errno    = 0;
  var $Error    = "";

  var $Auto_free   = 0;   ## set this to 1 to automatically free results
  var $Auto_commit = 0;   ## set this to 1 to automatically commit results

  function connect() {
    global $FC_Link_ID;
	if( !empty($FC_Link_ID) ){
      $this->Link_ID=$FC_Link_ID;
	}
    if ( 0 == $this->Link_ID ) {
      $this->Link_ID=odbc_pconnect($this->Database, $this->User, $this->Password);
      if (!$this->Link_ID) {
        $this->halt("Link-ID == false, odbc_pconnect failed");
      }
    }
	if ( $this->Auto_commit ) {
      odbc_autocommit($this->Link_ID,1);
	} else {
      odbc_autocommit($this->Link_ID,0);
	}
  }
  
  function query($Query_String) {
    $this->connect();
    
#   printf("<br>Debug: query = %s<br>\n", $Query_String);
    
    $this->Query_ID = odbc_exec($this->Link_ID,$Query_String);
    $this->Row = 0;
    odbc_binmode($this->Query_ID, 1);
    odbc_longreadlen($this->Query_ID, 4096);
    
    if (!$this->Query_ID) {
      $this->Errno = 1;
      $this->Error = "General Error (The ODBC interface cannot return detailed error messages).";
      $this->halt("Invalid SQL: ".$Query_String);
    }
    return $this->Query_ID;
  }
  
  function next_record() {
    $this->Record = array();
    $stat=odbc_fetch_into($this->Query_ID, &$this->Record);
    // nmb $stat=odbc_fetch_into($this->Query_ID, ++$this->Row, &$this->Record);
    if (!$stat) {
      if ($this->Auto_free) {
	    odbc_free_result($this->Query_ID);
        $this->Query_ID = 0;
	  };
    } else {
      // add to Record[<key>]
      $count = odbc_num_fields($this->Query_ID);
      for ($i=1; $i<=$count; $i++)
        $this->Record[strtolower(odbc_field_name ($this->Query_ID, $i)) ] = $this->Record[ $i - 1 ];
    }
    return $stat;
  }
  
  function seek($pos) {
    $this->Row = $pos;
  }

  function metadata($table) {
    $count = 0;
    $id    = 0;
    $res   = array();

    $this->connect();
    $id = odbc_exec($this->Link_ID, "select * from $table");
    if (!$id) {
      $this->Errno = 1;
      $this->Error = "General Error (The ODBC interface cannot return detailed error messages).";
      $this->halt("Metadata query failed.");
    }
    $count = odbc_num_fields($id);
    
    for ($i=1; $i<=$count; $i++) {
      $res[$i]["table"] = $table;
      $name             = odbc_field_name ($id, $i);
      $res[$i]["name"]  = $name;
      $res[$i]["type"]  = odbc_field_type ($id, $name);
      $res[$i]["len"]   = 0;  // can we determine the width of this column?
      $res[$i]["flags"] = ""; // any optional flags to report?
    }
    
    odbc_free_result($id);
    return $res;
  }
  
  function affected_rows() {
    return odbc_num_rows($this->Query_ID);
  }
  
  function num_rows() {
    # Many ODBC drivers don't support odbc_num_rows() on SELECT statements.
    $num_rows = odbc_num_rows($this->Query_ID);
	//printf ($num_rows."<br>");

    # This is a workaround. It is intended to be ugly.
    if ($num_rows < 0) {
      $i=10;
      while (odbc_fetch_row($this->Query_ID, $i)) 
        $i*=10;

      $j=0;
      while ($i!=$j) {
        $k= $j+intval(($i-$j)/2);
        if (odbc_fetch_row($this->Query_ID, $k))
          $j=$k;
        else 
          $i=$k;
        if (($i-$j)==1) {
          if (odbc_fetch_row($this->Query_ID, $i)) 
            $j=$i;
          else 
            $i=$j; 
        };
        //printf("$i $j $k <br>");
      };
      $num_rows=$i;
    }

    return $num_rows;
  }
  
  function num_fields() {
    return count($this->Record)/2;
  }

  function nf() {
    return $this->num_rows();
  }
  
  function np() {
    print $this->num_rows();
  }
  
  function f($Field_Name) {
    return $this->Record[strtolower($Field_Name)];
  }
  
  function p($Field_Name) {
    print $this->f($Field_Name);
  }
  
  function halt($msg) {
    printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
    printf("<b>ODBC Error</b>: %s (%s)<br>\n",
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
    return odbc_free_result($this->Query_ID);
  }

  function rollback() {
    return odbc_rollback($this->Link_ID);
  }

  function commit() {
    return odbc_commit($this->Link_ID);
  }

  function autocommit($onezero) {
    return odbc_autocommit($this->Link_ID,$onezero);
  }

  function insert_id($col="",$tbl="",$qual="") {
   $ires=odbc_exec($this->Link_ID,"select $col from $tbl where $qual");
   if ( !odbc_fetch_row($ires) ) {
    return 0;
   }
   $iseq = odbc_result($ires,"$col");
   odbc_free_result($ires);
   return $iseq;
  }
}

/*
 Ugliness ahead.  This is lifted verbatim from phplib's db_mysql and
 renamed from "DB_Sql" to "DB_Mysql".  One cannot mix databases or
 classes in PHPlib.  So... rename this one.

 All this is brought about because the PHP3 ODBC (Solid, Adabas)
 drivers are not consistent in their ability to swallow more than
 4k of data.  Web page templates need more than 4k of data to work,
 so we'll have to do it in mysql.
*/ 

class DB_MySql {
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
    if ( 0 == $this->Link_ID ) {
      $this->Link_ID=mysql_pconnect($this->Host, $this->User, $this->Password);
      if (!$this->Link_ID) {
        $this->halt("Link-ID == false, pconnect failed");
      }
      if (!mysql_query(sprintf("use %s",$this->Database),$this->Link_ID)) {
        $this->halt("cannot use database ".$this->Database);
      }
    }
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

class FC_TPL extends DB_MySql {
  var $Host     = "localhost";
  var $Database = "TPLDBASE";
  var $User     = "USERID";
  var $Password = "USERPW";

  function free_result() {
    return mysql_free_result($this->Query_ID);
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
    return mysql_insert_id($this->Query_ID);
  }
}
?>
