<?
class DataObject 
{
	var $name;
	
	var $updateError   = "",
		$updateSuccess = "",
		$addError      = "",
		$addSuccess    = "",
		$deleteError   = "",
		$deleteSuccess = "";
	
	var $message = "";
	
	var $fieldValue = array ();
					  		
	var $fieldName  = array ();
	
	var $fieldError = array ();
	
	var $error;
	
	function DataObject ()
	{
		$this->error = array ();	
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
	function add ()
	{		
		if (!$this->check ())
		{
			$this->message = $this->addError;
			return false; 
		}	
		
		$insertClause = "";
		$insertValues = "";
		
		foreach ($this->fieldValue as $fieldName => $fieldValue)
		{
			if (strlen ($insertClause) > 0)
			{
				$insertClause .= ", ";
				$insertValues .= ", ";
			}

			$insertClause .= $fieldName;
			$insertValues .= "'".addslashes ($fieldValue)."'";
		}
		
		$query = "insert into ".$this->name." ($insertClause) values ($insertValues)";

		$db = new DB ();
		if ($db->execute ($query))
		{
			$this->message = $this->addSuccess;
			$this->clear ();
			return true;
		}
		
		$this->message = $this->addError;
		return false;
	}
	
	function update ($id)
	{		
		if (!$this->check ())
		{
			$this->message = $this->updateError;
			return false; 
		}	
	
		$query = "update ".$this->name." set ";
		
		foreach ($this->fieldValue as $fieldName => $fieldValue)			
			$query .= " $fieldName = '".addslashes ($fieldValue)."',";

		$query = substr ($query, 0, strlen ($query) - 1);
		
		$query .= " where ID = $id";

		$db = new DB ();
		
		if ($db->execute ($query))
		{
			$this->message = $this->updateSuccess;
			$this->clear ();
			return true;
		}
		
		$this->message = $this->updateError;
		return false;
	}
	
	function message ()
	{
		return $this->message;
	}
	
	function clear ()
	{
		foreach ($this->fieldValue as $fieldName => $fieldValue)
		{
			global ${$fieldName};
			
			if (isset (${$fieldName}) && $fieldName != 'GroupID' && $fieldName != 'FormID')
				${$fieldName} = "";

			$this->fieldValue [$fieldName] = "";
		}
	}
	function errorCount ()
	{
		return sizeof ($this->error);
	}

	function listByField ($fieldName = "", $fieldValue = "")
	{
		$whereClause = "";
		if (strlen ($fieldName) > 0)
			$whereClause = "where $fieldName = '$fieldValue'";
			
		$db = new DB ();
		$db->execute ("select ID from ".$this->name." $whereClause order by ID");
				
		$ID = array ();
		for ($i = 0; $i < $db->getNumRows (); $i++)
			$ID [sizeof ($ID)] = $db->recordSet [$i][0];
		
		return $ID;		
	}
	function check ()
	{
		foreach ($this->fieldValue as $fieldName => $fieldValue)
		{
			global ${$fieldName};

			$this->fieldValue [$fieldName] = ${$fieldName};		
			
			if (!isset (${$fieldName}) || !$this->checkField ($fieldName, ${$fieldName}))
				$this->error [$fieldName] = $this->fieldError [$fieldName];
		}		
	
		if ($this->errorCount() > 0)
			return false;
		
		return true;
	}

	function field ($fieldName, $id)
	{
		$db = new DB ();
		
		if (!$db->execute ("select $fieldName from ".$this->name." where ID = $id") || 
			 $db->getNumRows () == 0)
			return "";
		
		return stripslashes ($db->recordSet [0][0]);
	}
	
	function input ($fieldName, $fieldContent) {}
	
	function checkField ($fieldName, $fieldValue) {}
	
	function displayInput ($elementID = 0)
	{
		?>
		<table cellpadding=5 cellspacing=0>
		<?
		foreach ($this->fieldValue as $fieldName => $fieldValue)
		{
			global ${$fieldName};
			
			if (isset (${$fieldName}))
				$this->fieldValue [$fieldName] = ${$fieldName};
			else if ($elementID > 0)
				$this->fieldValue [$fieldName] = $this->field ($fieldName, $elementID);
			else
				$this->fieldValue [$fieldName] = "";	
			
			print "<td width=200 valign=top align=left class=TableHeader>&nbsp;".
				  $this->fieldName [$fieldName]; 
			if (isset ($this->error [$fieldName]))
				print "<br>&nbsp;<font color=red>".$this->error [$fieldName]."</font>";
			
			print "</td>";
			print "<td valign=top align=left>&nbsp;".
				  $this->input ($fieldName, $this->fieldValue [$fieldName])."</td><tr>";
		}
		?>
		</table>
		<?		
	}
}
?>