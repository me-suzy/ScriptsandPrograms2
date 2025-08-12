<?php

/*****************************************
* File      :   $RCSfile: functions.database.php,v $
* Project   :   Contenido
* Descr     :   Contenido Database Functions
*
* Author    :   $Author: timo.hummel $
*               
* Created   :   04.06.2003
* Modified  :   $Date: 2003/10/17 12:21:57 $
*
* © four for business AG, www.4fb.de
*
* $Id: functions.database.php,v 1.13 2003/10/17 12:21:57 timo.hummel Exp $
******************************************/

function dbDumpArea ( $id )
{
	$db = new DB_Upgrade;
	
	$sql = 'SELECT name FROM con_area WHERE idarea = $id';
	$db->query($sql);
	if (!$db->next_record()) { return; }
	$name = $db->f("name");
	
	// First step: Dump
	$sql = "SELECT * FROM con_area WHERE idarea = $id OR parent_id = '$name'";
	$db->query($sql);
	
	//if (!$db->next_record()) { return; }
	
	//echo '$area['.$id.'] = array (';
	
	dbDumpAreasAsArray('$area',$db);
	
	
	 
}

//function dbDump

function dbDumpAreasAsArray ($arrayname, $db)
{
  		$values = array();
  		
  		$metadata = $db->metadata();
		
		if (!is_array($metadata))
		{
			return false;
		}
		
		echo '$startidarea = $db->nextid( $cfg["tab"]["area"] );'."\n\n";
		
		$nextid = 0;
		while ($db->next_record())
		{
			$nextid += 1;
    		foreach ($metadata as $entry)
    		{
    			
    			$key = $entry['name'];
    			$value = $db->f($entry['name']);
    			
    			if ($key == "idarea")
    			{
    				$value = '$startidarea+'.$nextid;
    			}
    			echo $arrayname.'[$startidarea+'.$nextid."]"."['"
    			     .$key.
                     "'] = '".
                     $value."';\n";
                     
                $sql = 'SELECT * FROM '.$cfg["tab"]["nav_sub"].' WHERE idarea = '.$db->f("idarea");
                $db2 = new DB_Upgrade;
                $db2->query($sql);
                dbDumpNavSub('$navsub', $db2, $nextid);
    		}
    		echo 'dbInsertData( $cfg["tab"]["area"], '.$arrayname.'[$startidarea+'.$nextid."]);\n";
    		echo "\n";
		}		
}

function dbDumpNavSub ($arrayname, $db, $nextidarea)
{
	$values = array();
  		
  		$metadata = $db->metadata();
		
		if (!is_array($metadata))
		{
			return false;
		}
		
		echo ' $startidnavs = $db->nextid( $cfg["tab"]["nav_sub"] );'."\n\n";
		
		$nextid = 0;
		while ($db->next_record())
		{
			$nextid += 1;
    		foreach ($metadata as $entry)
    		{
    			
    			$key = $entry['name'];
    			$value = $db->f($entry['name']);
    			
    			if ($key == "idarea")
    			{
    				$value = '$startidarea+'.$nextidarea;
    			}
    			echo " ". $arrayname.'[$startidnavs+'.$nextid."]"."['"
    			     .$key.
                     "'] = '".
                     $value."';\n";
                     
                $sql = 'SELECT * FROM '.$cfg["tab"]["nav_sub"].' WHERE idarea = '.$db->f("idarea");
                $db2 = new DB_Upgrade;
                //dbDumpNavSub('$navsub', $db2);
    		}
    		echo 'dbInsertData( $cfg["tab"]["area"], '.$arrayname.'[$startidarea+'.$nextid."]);\n";
    		echo "\n";
		}		
	
	
}
 
	
function dbInsertData ( $table, $data )
{
	$db = new DB_Upgrade;
	
	$sql = "INSERT INTO $table SET ";
	
	foreach ($data as $key => $value)
	{
		$sql .= $key . ", '".$value."' ";
	}

	echo $sql;	
}
			
function dbUpgradeTable( $table, $field, $type, $null, $key, $default, $extra, $upgradeStatement)
{

	$db = new DB_Upgrade;
	$db2 = new DB_Upgrade;

	/* Function logic:
	   1 .) Check if the table exists
 	   1a.) If not, create it with the field specification
       2 .) If the table exists:
       2a.) If the field exists and the fields type is matching, exit
       2b.) If the field exists and the field's content type is not matching, try to convert first (e.g. string to int
		    or int to string), then use the upgrade statement if applicable
	
	   Note about the upgrade statement:
		- the code must be eval'able
		- the code needs to read $oldVal (old field value) and needs to set $newVal (value to which the field will be set)
		- $oldVal might be empty if the field didn't exist
		- $tableValues['fieldname'] contains the already existing values
	*/

	/* Parameter checking for $null
       If parameter is "" or "NULL" or "YES", we know that
	   we want the colum to forbid null entries.
	 */ 
	if ($null == "NULL" || $null == "YES")
	{
		$parameter['NULL'] = "NULL";
		$null = "YES";
	} else {
		$parameter['NULL'] = "NOT NULL";
		$null = "";
	}

	/* Parameter checking for $key
       If parameter is "" or "NULL" or "YES", we know that
	   we want the primary key.
	 */	
	if ($key == "PRI")
	{
		$parameter['KEY'] = "PRIMARY KEY";
	} else {
		$parameter['KEY'] = "";
	}
	
	
	/* Parameter check for $default
       If set, create a default value */
	if ($default != "")
	{
		$parameter['DEFAULT'] = "DEFAULT '$default'";
	}
	
	
	
	if (!dbTableExists($table))
	{
		$createTable = "  CREATE TABLE $table ($field $type ".$parameter['NULL']." ".$parameter['DEFAULT']." ".$parameter['KEY'] .")";
		$db->query($createTable);
		return;						
	} 
	
	

	$structure = dbGetColumns($table);
	$savedPrimaryKey = dbGetPrimaryKeyName($table);
	
	$createField = "  ALTER TABLE $table ADD COLUMN $field $type ".$parameter['NULL']." ".$parameter['DEFAULT']." ".$parameter['KEY'];
	$db->query($createField);
	return; 
	
	
	/* Third check: Compare field properties */
	if (($structure[$field]['Type'] != $type) ||
	    ($structure[$field]['Null'] != $null) ||
	    ($structure[$field]['Key'] != $key) ||
	    ($structure[$field]['Default'] != $default) ||
	    ($structure[$field]['Extra'] != $extra))
	{
		
		if ($structure[$field]['Key'] == "PRI")
		{
			return "The primary key is not allowed to change";
		}
		
		/* Save old values */
		if ($savedPrimaryKey != "")
		{
    		$sql = "  SELECT $savedPrimaryKey, $field FROM $table";
    		$db->query($sql);
    		
    		while ($db->next_record())
    		{
    			$oldValues[$db->f($savedPrimaryKey)] = $db->f($field);
    		}
		}
		
		$alterField = "  ALTER TABLE $table CHANGE COLUMN $field $field $type ".$parameter['NULL']." ".$parameter['DEFAULT']." ".$parameter['KEY'];
		$db->query($alterField);
		
		if (is_array($oldValues))
		{
			foreach ($oldValues as $pUpKey => $pUpValue)
			{
				$sql = "  SELECT * FROM $table WHERE $savedPrimaryKey = '$pUpKey'";
				$db2->query($sql);
				$db2->next_record();
				
				$tableValues = $db2->copyResultToArray();
				$oldVal = $pUpValue;
				$newVal = $pUpValue;
				eval($upgradeStatement);
			
				$newVal = addslashes($newVal);
				$sql = "  UPDATE $table SET $field = '$newVal' WHERE $savedPrimaryKey = '$pUpKey'";
				$db2->query($sql); 
			
			}
		}
		
		
		
	}
	    

}

function dbTableExists ($table)
{
	$db = new DB_Upgrade;

	$sql = "SHOW TABLES";
	$db->query($sql);
	while ($db->next_record())
	{
		if ($db->f(0) == $table)
		{
			return true;
		}
	}
	
	return false;
}

function dbGetColumns ($table)
{
	$db = new DB_Upgrade;
	
	$sql = "SHOW COLUMNS FROM $table";
	
	$db->query($sql);
	while ($db->next_record())
	{
		$structure[$db->f("Field")] = $db->copyResultToArray();
	}
	
	return $structure;
}

function dbGetPrimaryKeyName ($table)
{
	$structure = dbGetColumns($table);
	
	foreach ($structure as $mykey => $value)
	{
		if ($value['Key'] == "PRI")
		{
			return ($mykey);
		}
	}

}

function dbDumpStructure ($table)
{
	global $cfg;

	
	echo "<pre>";
	$structure = dbGetColumns($cfg["tab"][$table]);
	
	foreach ($structure as $key => $value)
	{
		$tab = str_replace("con_","",$cfg["tab"][$table]);
		//function dbUpgradeTable( $table, $field, $type, $null, $key, $default, $extra, $upgradeStatement)
		echo "dbUpgradeTable(\$prefix.\"_$tab\", '$key', '"
		      .$value['Type'].
              "', '"
              .$value['Null'].
              "', '"
              .$value['Key'].
              "', '"
              .$value['Default'].
              "', '"
              .$value['Extra'].
              "','');";
		echo "\n";
	}
	echo "</pre>";
}

function dbDumpData ($table)
{
	global $cfg;
	$db = new DB_Upgrade;
	
	echo "<pre>";
	$structure = dbGetColumns($cfg["tab"][$table]);
	
	$sql = "SELECT * FROM " . $cfg["tab"][$table];
	//echo $sql;
	
	echo '$db = new DB_Upgrade; $db->query("DELETE FROM ".$cfg["tab"]["'.$table.'"]);'."\n";
	$db->query($sql);
	
	while ($db->next_record())
	{
		$count++;
		
		echo '$'.$table.$count.' = array(';

		foreach ($structure as $key => $value)
		{
			$entry[$key] = "'$key' => '".addslashes($db->f($key))."'";
			
		}
		
		
		echo implode(', ',$entry);
		echo ');'."\n";
		echo $targetLink ."\n";
		echo "dbUpgradeData('$table', \$".$table.$count.");";
		echo "\n\n";	
	}
}

function dbUpgradeData ($table, $valuesArray)
{
	global $cfg;
	$db = new DB_Upgrade;
	
	$sql = "INSERT INTO ".$cfg["tab"][$table]." SET ";
	foreach ($valuesArray as $key => $value)
		{
			$addValues[] = "$key = '$value'";
		}
		
		$param = implode(', ', $addValues);
		
		$sql .= $param;
	
		//echo $sql;	
		$db->query($sql);
	
}

function dbUpdateSequence($sequencetable, $table)
{
	global $cfg;
	
	$key = dbGetPrimaryKeyName($table);
	
	$db = new DB_Upgrade;
	
	if ($key != "")
	{
    	$sql = "SELECT ".$key." FROM ". $table ." ORDER BY " . $key ." DESC";
    	$db->query($sql);
    	
    	if ($db->next_record())
    	{
    		$highestval = $db->f($key);
    	} else {
    		$highestval = 0;
    	}
    	
    	$sql = "DELETE FROM " . $sequencetable . " WHERE seq_name = '".$table."'";
    	$db->query($sql);
    	
    	$sql = "INSERT INTO " . $sequencetable ." SET seq_name = '".$table."', nextid = ".($highestval+1);
    	$db->query($sql);
	}
}
?>
