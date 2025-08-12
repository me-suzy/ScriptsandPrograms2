<?php

if(!class_exists('table_interface')){
require_once(getModulePath('adodb').'/adodb.inc.php');
require_once(getModulePath('adodb').'/tohtml.inc.php');

/*
0 = ignore empty fields. All empty fields in array are ignored.
1 = force null. All empty, php null and string 'null' fields are changed to sql NULL values.
2 = force empty. All empty, php null and string 'null' fields are changed to sql empty '' or 0 values.
3 = force value. Value is left as it is. Php null and string 'null' are set to sql NULL values and 
    empty fields '' are set to empty '' sql values.
*/

define('ADODB_FORCE_IGNORE',0);
define('ADODB_FORCE_NULL',1);
define('ADODB_FORCE_EMPTY',2);
define('ADODB_FORCE_VALUE',3);

class table_interface{

    var $primary_key_name;
		var $primary_key_value;
		var $table_name;
		var $sql;
		var $dsn;
		var $connection;
		
		function table_interface($option = array()){
		    $this->__constructor($option);
		}
		
		function __constructor($option = array()){
		    $this->setOption($option);	
		}
	  function _sql(){
		    return 'SELECT * FROM '.$this->table_name.' WHERE '.$this->primary_key_name.' = '.$this->primary_key_value;	
		}
		function setOption($array = array()){
		    $this->primary_key_name = isset($array['primary_key_name'])?$array['primary_key_name']:$this->primary_key_name;
				$this->primary_key_value = isset($array['primary_key_value'])?$array['primary_key_value']:$this->primary_key_value;
				$this->dsn = isset($array['dsn'])?$array['dsn']:$this->dsn;
				$this->table_name = isset($array['table_name'])?$array['table_name']:$this->table_name;				
				$this->_setConnection();
		}
			
		
		function _setConnection(){
		    $this->connection = &ADONewConnection($this->dsn);
				$this->connection->debug = 1;
		}
		
		function insert($record = array()){
		    $this->primary_key_value = -1;
		    $rs = $this->connection->Execute($this->_sql());
				$insertSQL = $this->connection->GetInsertSQL($rs,$record);
				$this->connection->Execute($insertSQL); 
				return True;		
		}
		
		function setPK($primary_key){
		    $this->primary_key_value = $primary_key;
		}
		
		function update($record = array()){
		    $this->primary_key_value = isset($record[$this->primary_key_name])?$record[$this->primary_key_name]:$this->primary_key_value;
				$rs = $this->connection->Execute((string)$this->_sql());
				$updateSQL = $this->connection->GetUpdateSQL($rs,$record);
				$this->connection->Execute($updateSQL);
				return True;				
		}
		
		function delete($primary_key = -1){
		    if($primary_key === '*'){
				    $sql = 'TRUNCATE '.$this->table_name;
				}else{		    
		        $sql = 'DELETE FROM '.$this->table_name.' WHERE '.$this->primary_key_name.' = '.$primary_key;
				}
				$this->connection->Execute($sql);
		}

		function _getPK(){
		    $result = '';
		    $recordSet = &$this->connection->Execute('SELECT * FROM '.$this->table_name.' LIMIT 0,1');
        if (!$recordSet) print $this->connection->ErrorMsg();
        else
				$count = count($recordSet->fields);			
				//while(!$recordSet->EOF){				    			
						for($i=0;$i<$count;$i++){
						    $fld = $recordSet->FetchField($i);
                $type = $recordSet->MetaType($fld->type);
								//echo $i.'.Name = '.$fld->name.'|';
								//echo $i.'.type = '.$type.', ';
								//echo '<br>';
								if($type == 'I'){
								    $result = $fld->name;
										break;
								}						
						}
						//echo '<hr>';
						//break;
						//$recordSet->MoveNext();
				//}		
		    //return $result;   
        $this->primary_key_name = $result;
		}

}

}
?>