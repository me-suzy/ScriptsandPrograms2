<?php 
// Copyright 2001 Philippe Lewicki           phil@sqlfusion.com
  /**
   * sql Save Query Object Object 
   * @see sqlSavedQuery
   * @package PASClass
   */
  /**
  *  Object sqlSavedQuery to manage persistant queries
  *
  *  Based on sqlQuery is store and restore queries from the Database.
  * They are prepared to be used like an sqlQuery object.
  *
  * @author Philippe Lewicki  <phil@sqlfusion.com>
  * @copyright  SQLFusion LLC 2001-2004
  * @version 3.0
  * @package PASClass
  * @access public
  */

class sqlSavedQuery extends sqlQuery {
  /**  name of the sqlSavedQuery object when serialized in database.
   * @var int $id uniq id.
   */
  var $name ;
  /**  Where statement of the query
   * @var string $qwhere
   */
  var $qwhere ;
  /**  table where persistant sqlSavedQuery are stored.
   * @var string $tbl_query
   */
  var $tbl_query = "savedquery";
  /**  Name of the query
   * @var string $name
   */
  var $qname ;
  /**  SQL statement of the query before replacement of the global var..
   * @var string $query SQL statment
   */
  var $query ;
   /**  Flag to tel if the query is ready to be executed. Mean when all the attributes are defined.
   * @var boolean $queryReady  is query ready to be executedSQL statetment
   */
  var $queryReady = true ;

  /**
   * Constructor sqlSavedQuery
   * Get the query from the database built the SQL query from the global vars.
   *
   * @param object sqlConnect $dbc
   * @param int $id unique id of the sqlSavedQuery to be reactivate from database.
   * @access public
   */

  function sqlSavedQuery($dbc, $name) {
    $this->setDisplayErrors(false); 
    $this->dbCon = $dbc ;    
    if (!is_resource($this->dbCon->getDbConId())) {
        $this->setError("SQLConnect object is not an open database connexion");
    } 
    $this->setLog(false);   
    if ($this->dbCon->getUseDatabase()) {
      $this->name = $name ;
      $qGetQuery = new sqlQuery($dbc) ;
      $r = $qGetQuery->query("select * from $this->tbl_query where qname='$this->name'") ;
      $qGetQuery->getNumRows() ;
      $infoquery = $qGetQuery->fetch() ;
      $this->qname = $infoquery->qname ;
      $this->sql_query = $infoquery->query ;
      $this->query = $infoquery->query ;
      $this->sql_order = $infoquery->qorder ;
      $this->pos = $infoquery->qpos ;
      $this->table = explode(":", $infoquery->tablenames)  ;
      $qGetQuery->free() ;
    }  else {
      include_once($this->dbCon->getBaseDirectory()."class/XMLBaseLoad.class.php") ;
      include_once($this->dbCon->getBaseDirectory()."class/XMLFlatDataLoad.class.php") ;
      $regfilename1 = $this->dbCon->getBaseDirectory()."/".$this->tbl_query."/".$name.".sq.xml" ;
      $regfilename2 = $this->dbCon->getProjectDirectory()."/".$this->tbl_query."/".$name.".sq.xml" ;
      if (file_exists($regfilename1)) {
        $xmlSQ = new XMLFlatDataLoad() ;
        $xmlSQ->init($regfilename1) ;
      } elseif(file_exists($regfilename2)) {
        $xmlSQ = new XMLFlatDataLoad() ;
        $xmlSQ->init($regfilename2) ;
      }
      if (is_object($xmlSQ)) {
        $xmlSQ->parse() ;
        $this->qname = $xmlSQ->finaldata["QNAME"] ;
      //  $this->sql_query = $xmlSQ->finaldata["QUERY"] ;
        $this->query = $xmlSQ->finaldata["QUERY"]  ;
        $this->sql_order = $xmlSQ->finaldata["QORDER"] ;
        $this->pos = $xmlSQ->finaldata["QPOS"] ;
        $this->table = explode(":", trim($xmlSQ->finaldata["TABLENAMES"]))  ;
      }
    }
    $this->prepareQuery();
    // Not sure this should stay here.
    if (strlen($this->qwhere) > 0 ) {
      if (eregi("where", $this->sql_query)) {
        $this->sql_query .= " AND ".$this->qwhere;
      } else {
        $this->sql_query .= " WHERE ".$this->qwhere;
      }
    }
    return true ;
  }

  /** 
   * Prepare query
   * Prepare the query and load the params or variables.
   */
   
  function prepareQuery($params=0) {
    if (count($this->table) == 1) {
      $this->table = $this->table[0] ;
    }
    $this->sql_query = $this->getInitialQuery();
    while (ereg('\[([^\[]*)\]', $this->sql_query, $matches)) {
      $field = $matches[1] ;
      if (ereg(":", $field)) {
          $a_paramdefaultvar = explode(":", $field);
          if (function_exists($a_paramdefaultvar[0])) {
              $fieldvalue = $a_paramdefaultvar[0]($a_paramdefaultvar);
              $this->setLog("runned function: ".$a_paramdefaultvar[0]);
          }
      }  elseif (ereg(";", $field)) {
          $a_paramdefaultvar = explode(";", $field);
          if (function_exists($a_paramdefaultvar[0])) {
              $fieldvalue = $a_paramdefaultvar[0]($a_paramdefaultvar);
              $this->setLog("runned function: ".$a_paramdefaultvar[0]);
          }
      } else {
          if (is_array($params)) {
            $fieldvalue = $params[$field] ;
            $this->setLog("\n params is :".$field."=".$fieldvalue);
          } else {
            $fieldvalue = $GLOBALS[$field] ;     
            $this->setLog("\nfieldname: ".$field." - fieldvalue: ".$fieldvalue);     
          }
      }      
      
      if (!isset($fieldvalue)) { $this->queryReady = false; }
      $this->sql_query = eregi_replace('\['.$field.'\]', strval($fieldvalue), $this->sql_query) ;
    }
    $this->setLog("\n SQL Query :".$this->sql_query."---");
  
  }
     
  /**
   * Return the original query string before its replacement with the global vars.
   * @return String $query SQL Query string
   */
  function getInitialQuery() {
    return $this->query ;
  }
  /**
   * Check if the query is ready to be executed.
   * @return boolean $queryReady
   */
  function getQueryReady() {
    return $this->queryReady ;
  }
}
?>