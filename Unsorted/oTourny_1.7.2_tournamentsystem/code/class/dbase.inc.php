<? /*************************************************************************************************
Copyright (c) 2003, 2004 Nathan 'Random' Rini
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*************************************************************************************************/ if(!defined('CONFIG')) die;
 /*
  Database Protocol
 */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// db_querys
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 //query list class
 class db_querys {
  var $querys = array();
  var $db; //db api

  function db_querys($DBNAME, $DBSERVERHOST, $DBUSERNAME, $DBPASSWORD){
   $this->db = new db(); //create and open db
   $this->db->open($DBNAME, $DBSERVERHOST, $DBUSERNAME, $DBPASSWORD);
  }

  //returns a query - stops all repeats
  function &query($sql, $force = FALSE){
   if($sql == "") return false;

   if(!$force) //dont double check when force is on
    foreach($this->querys as $obj)
     if($obj->db_sql == $sql) return $obj;

   $obj = new db_query($sql,$this->db, $this);
   return $this->querys[] = &$obj;
  }

  function cleanup(){
   $this->db->close();
  }

  //Grab next id for table
  function nextid($table, $index){global $DB_SEQ_TABLE, $DB_SEQ_INDEX, $DB_SEQ_NEXT;
   //grab table's info
   $query = new db_cmd("SELECT", "sequence", $DB_SEQ_NEXT, "`".$DB_SEQ_TABLE."` = '".$table."' AND `".$DB_SEQ_INDEX."` = '".$index."'", '', '', true);

   //bad entry
   if(empty($query->data) || !$query->data[0]["nextid"] > 0){
    //grab last id
    $query = new db_cmd("SELECT", $table, $index, '', 1, $index . " DESC", true);

    //grab next id from table
    $nextid = ((INT) $query->data[0][$index]) + 1;

    //create table
    $this->query("INSERT INTO sequence (`".$DB_SEQ_TABLE."`,`".$DB_SEQ_INDEX."`,`".$DB_SEQ_NEXT."`) VALUES ('".$table."', '".$index."', '".$nextid."')", '', '', true);

    return $nextid;
   }

   //increment id
   $nextid = $query->data[0]["nextid"] + 1;

   //grab table's info
   new db_cmd("UPDATE", "sequence", array($DB_SEQ_NEXT => $nextid), "`".$DB_SEQ_INDEX."` = '".$index."' AND `".$DB_SEQ_TABLE."` = '".$table."'", '', '', true);

   //grab next id
   return $nextid;
  }
 }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// db_query
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 //query class for db querys extends query class
 class db_query extends query {
  var $db_data     = array(); //all data from query - raw
  var $db_sql      = array(); //sql query called
  var $db_querylst; //db query list that made this

  function db_query($sql, &$db, &$querylst){
   $this->db_querylst = &$querylst;
   $this->db_sql = $sql;

   parent::query($db, $sql); //call parent constructor
  }

  //cleanup
  function clear(){
   $this->error = $this->db_querylst->db->error();
   $this->cleared  = 1;
   $this->db_data  = '';
   //$this->db_sql   = '';
  }

  //grab all data from query
  function load(){
   //check that there is a query to use
   if(!$this->result){$this->clear(); return;}
   if(!empty($this->db_data)) return; //dont reload if already loaded

   //override for 0 is filled
   $this->db_data[0] = $this->getrow();

   if($this->getrow()){//make sure next one loads up
    //grab all the remaining rows
    do if(!empty($this->row)) $this->db_data[] = $this->row;
    while($this->getrow());
   }

   $this->free(); //clear resources
  }
 }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// db_cmd
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 class db_cmd {
  var $query;
  var $data;

  function db_cmd($type, $table, $items = '', $where = '', $limit = '', $order = '', $force = FALSE){
   global $querys;

   switch(strtoupper($type)){
    case "SELECT":
     $sql = "SELECT "
      .($items==''?'*':(is_array($items)?implode(", ", $items):$items))
      .($table==''?'':" FROM ".$table)
      .($where==''?'':" WHERE ".$where)
      .($order==''?'':" ORDER BY ".$order)
      .($limit==''?'':" LIMIT ".$limit);

      $load = 1;
     break;
    case "UPDATE":
     if(is_array($items)){
      foreach($items as $key => $val)
       if($key != ''){
        if(gettype($key) != 'integer') $itemlst .= ($itemlst!=''?", ":'') ."`". $key ."`". "='" . convertsqlquotes($val) . "'";

        else $itemlst .= ($itemlst!=''?", ":'') . $val;
       }
     }else $itemlst = &$items;

     $sql = "UPDATE "
      .$table
      ." SET ".$itemlst
      .($where==''?'':" WHERE ".$where);
     break;
    case "INSERT":
     if(is_array($items)){
      foreach($items as $key => $val)
       if($key != ''){ //Must have Field Name
        $keys   .= ($keys!=''?", ":'')   . "`".$key."`";
        $values .= ($values!=''?", ":'') . "'" . convertsqlquotes($val) . "'";
       }

      $sql = "INSERT INTO ".$table." (".$keys.") VALUES (".$values.") ";
     }else //They only gave an String
      $sql = "INSERT INTO ".$table." SET ".$items;

     $force = TRUE;
     break;
    case "DELETE":
     $sql = "DELETE FROM ".$table.($where==''?'':" WHERE ".$where);
     break;
    case "DROP":
     $sql = "DROP TABLE IF EXISTS ".$table;
     break;
    case "SHOW":
     $sql = "SHOW ".$items.($table==''?'':" FROM ".$table);
     $load = 1;
     break;
   }
   //echo "<font color='#00FF00'>". $sql . "</font><br>";

   if($sql == '') return;

   $this->query = &$querys->query($sql, $force);
   if($load) $this->query->load();
   $this->data = &$this->query->db_data;
  }
 }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// Base Classe - Container
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 class db_table {
  var $objs;  //Obj array - holds reference to all Objs Contained
  var $table; //Db Table
  var $index; //Db Table Index
  var $class; //name of object class

  function db_table($table, $index, $class){
   $this->table = $table;
   $this->index = $index;
   $this->class = $class;

   if($this->class == '') die("Class Not Defined - CLASS");
   if($this->index == '') die("Class Not Defined - INDEX");
   if($this->table == '') die("Class Not Defined - TABLE");
  }

  //save all changes to db
  function update_db(){
   if(!empty($this->objs))
    foreach($this->objs as $obj)
     if(!empty($obj->data_mod) && $obj->id > 0)
      new db_cmd("UPDATE", $this->table, $obj->data_mod, "`".$this->index."` = '".$obj->id."'", 1);
  }

  //retrieve a obj
  function &obj($id = 0, $create = 0, $data = FALSE){
   //Preload an Array
   if(is_array($id)) return $this->pre_load($id);

   //return obj if alreayd declared
   if(!empty($this->objs[$id])) return $this->objs[$id];
   else { //Create the Obj
    if(!$id > 0 && $create) $id = $this->create();

    if($data === FALSE) //Grab Data
     $data =& $this->query_obj($id);

    return $this->objs[$id] = new $this->class($id, &$data, &$this);
  }}

  //Queries a Single Object
  function &query_obj($id){global $queries;
   if(!$id > 0) return; //invalid

   //Query Id
   $query = new db_cmd("select", $this->table, "*", "`". $this->index ."` = '". $id ."'", 1);

   //reference all data
   return $query->data[0];
  }

  //create new obj and then query it from the table
  function create(){global $querys;
   //grab next id
   $id = $querys->nextid($this->table, $this->index);

   //create entry
   new db_cmd("insert", $this->table, array($this->index => $id));

   return $id;
  }

  //Loads an Array of Objs at Once
  function &pre_load($objs){global $querys;
   if(!is_array($objs)) return; //only accept arrays
   if(empty($objs)) return; //ignore nulls

   //Make list of Obj Ids
   foreach($objs as $id)
    if(empty($this->objs[$id])) //dont double call
    if($this->index != '' && $id > 0){ //make sure its valids
     if($list != '') $list .= " OR ";
     $list .= "`".$this->index."` = '".$id."'";

     //make a list of those requested
     $ids[] = $id;
    }

   //all loaded already
   if($list == '') return;

   //Query all Ids
   $query = new db_cmd("select", $this->table, "*", $list);

   //run through and load out all the data
   foreach($query->data as $data)
    if($data[$this->index] > 0) //valid id?
     $this->obj($data[$this->index], false, $data);

   //if obj failed, make sure that we make obj anyway
   foreach($ids as $objid) //call obj with empty data to force it
    $objlist[] =& $this->obj($objid, false, array());

   return $objlist;
  }

  //Delete DB Row
  function delete($id){
   //Abstract
  }

 }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// Base Classe - Multi Container
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 class db_tables {
  var $objs;  //Obj array - holds reference to all Objs Contained
  var $class; //name of object class

  function db_tables($class){
   if(!is_array($class) || empty($class)) die("DB_TABLES only takes an array");

   //save class lists
   $this->class = $class;

   /* format
    $class[class] = array(
     "table" => table,
     "index" => index,
     "class" => table
    )
   */
  }

  //save all changes to db
  function update_db(){
   foreach($this->class as $class)
    if(!empty($this->objs[$class->class]))
     foreach($this->objs[$class->class] as $obj)
      if(!empty($obj->data_mod) && $obj->id > 0)
       new db_cmd("UPDATE", $class->table, $obj->data_mod, "`".$class->index."` = '".$obj->id."'", 1);
  }

  //retrieve a obj
  function &obj($class, $id = 0, $create = 0, $data = FALSE){
   //Preload an Array
   if(is_array($id)) return $this->pre_load($class, $id);

   //return obj if alreayd declared
   if(!empty($this->objs[$class][$id])) return $this->objs[$class][$id];
   else { //Create the Obj
    if(!$id > 0 && $create) $id = $this->create($class);

    if($data === FALSE) //Grab Data
     $data =& $this->query_obj($class, $id);

    return $this->objs[$class][$id] = new $this->class[$class]->class($id, &$data, &$this);
  }}

  //Queries a Single Object
  function &query_obj($class, $id){global $queries;
   if(!$id > 0) return; //invalid

   //Query Id
   $query = new db_cmd("select", $this->class[$class]->class, "*", "`". $this->class[$class]->index ."` = '". $id ."'", 1);

   //reference all data
   return $query->data[0];
  }

  //create new obj and then query it from the table
  function create($class){global $querys;
   //grab next id
   $id = $querys->nextid($this->class[$class]->table, $this->class[$class]->index);

   //create entry
   new db_cmd("insert", $this->class[$class]->table, array($this->class[$class]->index => $id));

   return $id;
  }

  //Loads an Array of Objs at Once
  function &pre_load($class, $objs){global $querys;
   if(!is_array($objs)) return; //only accept arrays
   if(empty($objs)) return; //ignore nulls

   //Make list of Obj Ids
   foreach($objs as $id)
    if(empty($this->objs[$class][$id])){
     if($list != '') $list .= " OR ";
     $list .= "`". $this->class[$class]->index ."` = '".$id."'";
    }

   //Query all Ids
   $query = new db_cmd("select", $this->class[$class]->table, "*", $list);

   //run through and load out all the data
   foreach($query->data as $data)
    if($data[$this->class[$class]->index] > 0) //valid id?
     $objlist[] =& $this->obj($class, $data[$this->class[$class]->index], false, $data);

   return $objlist;
  }

  //Delete DB Row
  function delete($id){
   //Abstract
  }

 }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// Base Classe - Contained Object
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 class db_obj {
  var $id;        //obj id
  var $data;      //obj data
  var $data_mod;  //modified obj data
  var $container; //Ref to Container

  function db_obj($id, &$data, &$container){
   if(!$id > 0) return;

   //Ref to Container
   $this->container =& $container;

   //reference Given Data
   $this->data =& $data;

   //save id
   $this->id = $this->get($this->container->index);
  }

  function delete(){
   //call back to container to remove table entry
   $this->container->delete($this->id);

   //clear local data
   $this->id = 0;
   $this->data = '';
   $this->data_mod = '';
  }

  function get($name){
   return $this->data[$name];
  }

  function set($name = '', $value = ''){
   if(is_array($name))
    foreach($name as $key => $data)
     $this->data_mod[$key] = $this->data[$key] = $data;
   else if($name != '')
    $this->data_mod[$name] = $this->data[$name] = $value;
  }
 }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// Conversion Functions
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 //converts text for sql can save it
 function convertsqlquotes($val){
  return str_replace("\"","\\\"", str_replace("\\\"","\"", str_replace("'","\\'", str_replace("\\'","'",$val))));
 }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>