<?php
// FishCart:
// Ran into safe mode restrictions across various cart installs so
// decided to include the whole file inline.  We can either copy
// the file and include it, or we include it here.  The cart scales
// better to include it here.

// We also extend the classes to include free_result(), autocommit(),
// commit() and rollback() class functions.  For mysql these do nothing
// but are in place for compatibility.

// see admin.php also; it is almost identical.

// This uses the OCI8 php module, so php must be compiled with "--with-oci8"
// configured.


$nsecurl = 'CATALOGURL';
$cartdir = 'DIRECTORY';
$securl  = 'SECUREURL';
$secdir  = 'SECDIR';
$maintdir= 'MAINTDIR';

$pub_inc=1;
$databaseeng = 'DATABASEENG';
$dialect  = 'DIALECT';

/*
 * Oracle/OCI8 accessor based on Session Management for PHP3
 *
 * (C) Copyright 1999 Stefan Sels phplib@sels.com
 *
 * based on db_oracle.inc by Luis Francisco Gonzalez Hernandez 
 * contains metadata() from db_oracle.inc 1.10
 *
 * $Id: public_oracle.php,v 1.3 2003/12/24 08:50:33 fcdev Exp $
 *
 */ 

class DBbase_Sql {
  var $Debug    =  0;
  var $sqoe     =  1; // sqoe= show query on error

  var $Database = "";
  var $User     = "";
  var $Password = "";

  var $Link_ID    = 0;
  var $Record    = array();
  var $Row;
  var $Parse;
  var $Error     = "";

  function connect() {
      if ( 0 == $this->Link_ID ) {
          if($this->Debug) {
              printf("<br>Connecting to $this->Database...<br>\n");
          }
          $this->Link_ID=OCIlogon
                ("$this->User","$this->Password","$this->Database");
//		$e = ocierror();
//		echo $e['message']."<br>\n";

          if (!$this->Link_ID) {
              $this->halt("Link-ID == false " .
                          "($this->Link_ID), OCILogon failed");
          } 
          
          if($this->Debug) {
              printf("<br>Obtained the Link_ID: $this->Link_ID<br>\n");
          }   
      }
  }
  
  function query($Query_String) {

	  /* No empty queries, please, since PHP4 chokes on them. */
	  if ($Query_String == "")
		/* The empty query string is passed on from the constructor,
		* when calling the class without a query, e.g. in situations
		* like these: '$db = new DB_Sql_Subclass;'
		*/
		return 0;

      $this->connect();

       $this->Parse=OCIParse($this->Link_ID,$Query_String);
      if(!$this->Parse) {
           $this->Error=OCIError($this->Parse);
      } else { OCIExecute($this->Parse);
          $this->Error=OCIError($this->Parse); 
      }

      $this->Row=0;

      if($this->Debug) {
          printf("Debug: query = %s<br>\n", $Query_String);
      }
      
      if ($this->Error["code"]!=1403 && $this->Error["code"]!=0 && $this->sqoe) 
      echo "<BR><FONT color=red><B>".$this->Error["message"]."<BR>Query :\"$Query_String\"</B></FONT>";
      return $this->Parse;
  }
  
  function next_record() {
      if(0 == OCIFetchInto($this->Parse,$result,OCI_ASSOC+OCI_RETURN_NULLS)) {
          if ($this->Debug) {
            printf("<br>ID: %d,Rows: %d<br>\n",
              $this->Link_ID,$this->num_rows());
          }
          $this->Row        +=1;
          
          $errno=OCIError($this->Parse);
          if(1403 == $errno) { # 1043 means no more records found
              $this->Error="";
              $this->disconnect();
              $stat=0;
          } else {
              $this->Error=OCIError($this->Parse);
              if($this->Debug) {
                  printf("<br>Error: %s",
                  $this->Error["message"]);
              }
              $stat=0;
          }
      } else { 
          for($ix=1;$ix<=OCINumcols($this->Parse);$ix++) {
              $col=strtoupper(OCIColumnname($this->Parse,$ix));
              $colreturn=strtolower($col);
              $this->Record[ "$colreturn" ] = $result["$col"]; 
              if($this->Debug) echo"<b>[$col]</b>:".$result["$col"]."<br>\n";
          }
          $stat=1;
      }

  return $stat;
  }

  function seek($pos) {
      $this->Row=$pos;
  }

  function metadata($table,$full=false) {
      $count = 0;
      $id    = 0;
      $res   = array();
      
    /*
     * Due to compatibility problems with Table we changed the behavior
     * of metadata();
     * depending on $full, metadata returns the following values:
     *
     * - full is false (default):
     * $result[]:
     *   [0]["table"]  table name
     *   [0]["name"]   field name
     *   [0]["type"]   field type
     *   [0]["len"]    field length
     *   [0]["flags"]  field flags ("NOT NULL", "INDEX")
     *   [0]["format"] precision and scale of number (eg. "10,2") or empty
     *   [0]["index"]  name of index (if has one)
     *   [0]["chars"]  number of chars (if any char-type)
     *
     * - full is true
     * $result[]:
     *   ["num_fields"] number of metadata records
     *   [0]["table"]  table name
     *   [0]["name"]   field name
     *   [0]["type"]   field type
     *   [0]["len"]    field length
     *   [0]["flags"]  field flags ("NOT NULL", "INDEX")
     *   [0]["format"] precision and scale of number (eg. "10,2") or empty
     *   [0]["index"]  name of index (if has one)
     *   [0]["chars"]  number of chars (if any char-type)
     *   ["meta"][field name]  index of field named "field name"
     *   The last one is used, if you have a field name, but no index.
     *   Test:  if (isset($result['meta']['myfield'])) {} ...
     */

      $this->connect();

      ## This is a RIGHT OUTER JOIN: "(+)", if you want to see, what
      ## this query results try the following:
      ## $table = new Table; $db = new my_DB_Sql; # you have to make
      ##                                          # your own class
      ## $table->show_results($db->query(see query vvvvvv))
      ##
      $this->query("SELECT T.table_name,T.column_name,T.data_type,".
           "T.data_length,T.data_precision,T.data_scale,T.nullable,".
           "T.char_col_decl_length,I.index_name".
           " FROM ALL_TAB_COLUMNS T,ALL_IND_COLUMNS I".
           " WHERE T.column_name=I.column_name (+)".
           " AND T.table_name=I.table_name (+)".
           " AND T.table_name=UPPER('$table') ORDER BY T.column_id");
      
      $i=0;
      while ($this->next_record()) {
        $res[$i]["table"] =  $this->Record[table_name];
        $res[$i]["name"]  =  strtolower($this->Record[column_name]);
        $res[$i]["type"]  =  $this->Record[data_type];
        $res[$i]["len"]   =  $this->Record[data_length];
        if ($this->Record[index_name]) $res[$i]["flags"] = "INDEX ";
        $res[$i]["flags"] .= ( $this->Record[nullable] == 'N') ? '' : 'NOT NULL';
        $res[$i]["format"]=  (int)$this->Record[data_precision].",".
                             (int)$this->Record[data_scale];
        if ("0,0"==$res[$i]["format"]) $res[$i]["format"]='';
        $res[$i]["index"] =  $this->Record[index_name];
        $res[$i]["chars"] =  $this->Record[char_col_decl_length];
        if ($full) {
                $j=$res[$i]["name"];
                $res["meta"][$j] = $i;
                $res["meta"][strtoupper($j)] = $i;
        }
        if ($full) $res["meta"][$res[$i]["name"]] = $i;
        $i++;
      }
      if ($full) $res["num_fields"]=$i;
#      $this->disconnect();
      return $res;
  }


  function affected_rows() {
    return $this->num_rows();
  }

  function num_rows() {
    return OCIrowcount($this->Parse);
  }

  function num_fields() {
      return OCINumcols($this->Parse);
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

  function disconnect() {
      if($this->Debug) {
          printf("Disconnecting...<br>\n");
      }
      OCILogoff($this->Link_ID);
  }
  
  function halt($msg) {
    printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
    printf("<b>ORACLE Error</b>: %s<br>\n",
      $this->Error["message"]);
    die("Session halted.");
  }

  function lock($table, $mode = "write") {
    $this->connect();
    if ($mode == "write") {
      $Parse=OCIParse($this->Link_ID,"lock table $table in row exclusive mode");
      OCIExecute($Parse); 
    } else {
      $result = 1;
    }
    return $result;
  }
  
  function unlock() {
    return $this->query("commit");
  }

  function table_names() {
   $this->connect();
   $this->query("
   SELECT table_name,tablespace_name
     FROM user_tables");
   $i=0;
   while ($this->next_record())
   {
   $info[$i]["table_name"]     =$this->Record["table_name"];
   $info[$i]["tablespace_name"]=$this->Record["tablespace_name"];
   $i++;
   } 
  return $info;
  }

  function add_specialcharacters($query)
  {
  return str_replace("'","''",$query);
  }

  function split_specialcharacters($query)
  {
  return str_replace("''","'",$query);
  }
}

class FC_SQL extends DBbase_Sql {
  var $Host     = "DATABASEHOST";
  var $Database = "DATABASENAME";
  var $User     = "USERID";
  var $Password = "USERPW";

  function free_result() {
    return 1;
  }

  function rollback() {
    return 1;
  }

  function commit() {
    return $this->query("commit work");
  }

  function autocommit($onezero) {
    return 1;
  }

  function insert_id($col="",$tbl="",$qual="") {
	$stmt=ociparse($this->Link_ID,"select $col from $tbl where $qual");
	ocifetchstatement($stmt,$ires);
	return $ires["$col"];
  }

  // from the code by Mike Green given on this page
  // http://www.phpbuilder.com/lists/phplib-list/2000101/0115.php
  // is limited to one CLOB per table
  function query($Query_String, $bind_var = '', $bind_val = '') {

	/* No empty queries, please, since PHP4 chokes on them. */
	if ($Query_String == "")
		/* The empty query string is passed on from the constructor,
		* when calling the class without a query, e.g. in situations
		* like these: '$db = new DB_Sql_Subclass;'
		*/
		return 0;

	$this->connect();
	if ($bind_var){
		$clob = OCINewDescriptor($this->Link_ID, OCI_D_LOB);
		$Query_String .= " returning $bind_var into :the_blob";
	}

	$this->Parse=OCIParse($this->Link_ID,$Query_String);
	if(!$this->Parse) {
		$this->Error=OCIError($this->Parse);
	}else{
		if ($bind_var) {
			OCIBindByName($this->Parse, ':the_blob', &$clob, -1, OCI_B_CLOB);
			OCIExecute($this->Parse, OCI_DEFAULT);
			if($clob->save($bind_val)){
				OCICommit($this->Link_ID);
			}else{
				$this->Error = "Couldn't insert CLOB into database.";
			}
			OCIFreeDesc($clob);
		} else {
			OCIExecute($this->Parse);
		}
		$this->Error=OCIError($this->Parse);
	}

	$this->Row=0;
	if($this->Debug) {
		printf("Debug: query = %s<br>\n", $Query_String);
	}
	if ($this->Error["code"] != 1403 &&
		$this->Error["code"]!=0 &&
		$this->sqoe)
		echo "<br><font color=red><b>".$this->Error["message"].
			 "<br>Query:\"$Query_String\"</b></font>";

	return $this->Parse;
  }
  
  // modified to allow passing the name of the clob column to load
  function next_record( $clob_var='' ) {
      if(0 == OCIFetchInto($this->Parse,$result,OCI_ASSOC+OCI_RETURN_NULLS)) {
          if ($this->Debug) {
            printf("<br>ID: %d,Rows: %d<br>\n",
              $this->Link_ID,$this->num_rows());
          }
          $this->Row        +=1;
          
          $errno=OCIError($this->Parse);
          if(1403 == $errno) { # 1043 means no more records found
              $this->Error="";
              $this->disconnect();
              $stat=0;
          } else {
              $this->Error=OCIError($this->Parse);
              if($this->Debug) {
                  printf("<br>Error: %s",
                  $this->Error["message"]);
              }
              $stat=0;
          }
      } else { 
          for($ix=1;$ix<=OCINumcols($this->Parse);$ix++) {
              $col=strtoupper(OCIColumnname($this->Parse,$ix));
              $colreturn=strtolower($col);
			  if( $colreturn == $clob_var ){
               $this->Record[ "$colreturn" ] = $result["$col"]->load(); 
			  }else{
               $this->Record[ "$colreturn" ] = $result["$col"]; 
			  }
              if($this->Debug) echo"<b>[$col]</b>:".$result["$col"]."<br>\n";
          }
          $stat=1;
      }

  	return $stat;
  }
}
?>
