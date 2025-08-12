<?
include_once "sys/db/DB.inc";

class DBObject
{
	// name of the table
	var $name = "Program";
	
	function DBObject ($name)
	{
		$this->name = $name;
	}
	
	function listByField ($fieldName = "", $fieldValue = "", $whatField = "")
	{
		if (strlen ($whatField) == 0)
			$whatField = "ID";
		$whereClause = "";
		if (strlen ($fieldName) > 0)
			$whereClause = "where $fieldName = '$fieldValue'";
			
		$db = new DB ();
		$db->execute ("select $whatField from ".$this->name." $whereClause order by ID");
				
		$ID = array ();
		for ($i = 0; $i < $db->getNumRows (); $i++)
			$ID [sizeof ($ID)] = $db->recordSet [$i][0];
		
		return $ID;		
	}
	function delete ($id)
	{
		$db = new DB ();
		
		if ($db->execute ("delete from ".$this->name." where ID = $id"))
		{
			$this->message = $this->deleteSuccess;
			return true;
		}
		
		$this->message = $this->deleteError;
		return false;
	}
	function field ($fieldName, $id)
	{
		$db = new DB ();
		if (!$db->execute ("select $fieldName from ".$this->name." where ID = $id") || 
			 $db->getNumRows () == 0)
			return "";

		return stripslashes ($db->recordSet [0][0]);
	}
}
?>