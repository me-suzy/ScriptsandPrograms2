<?

class TemplateGroup extends DataObject 
{
	var $name = "TemplateGroup";
	
	var $updateError   = "The group has not been updated.",
		$updateSuccess = "The group has been updated.",
		$addError      = "The new group has not been added.",
		$addSuccess    = "The new group has been added.",
		$deleteError   = "Some of selected groups have not deleted.",
		$deleteSuccess = "All selected groups have been deleted.";
	
	var $fieldValue = array ('Name'        => '',
					  		 'Description' => '');
					  		
	var $fieldName  = array ('Name'        => 'The template group name',
					  		 'Description' => 'The template group description');
	
	var $fieldError = array ('Name'        => 'not correct',
					  		 'Description' => 'not correct');
	
	var $error;
	
	function TemplateGroup ()
	{
		$this->error = array ();	
	}
	function delete ($id)
	{
		$db = new DB ();
		
		if ($db->execute ("delete from ".$this->name." where ID = $id"))
		{
			$this->message = $this->deleteSuccess;
			$db->execute ("delete from Template where GroupID = $id");
			return true;
		}
		
		$this->message = $this->deleteError;
		return false;
	}
	function input ($fieldName, $fieldValue)
	{
		if ($fieldName == 'Description')
		{
			$input = "<textarea name=$fieldName rows=3 cols=30>$fieldValue</textarea>\n";
		}
		else 
		{
			$input = "<input type=text size=35 maxsize=50 name=$fieldName value=\"$fieldValue\">\n";
		}
		
		return $input;
	}
	function checkField ($fieldName, $fieldValue)
	{
		if ($fieldName == 'Name')
		{
			if (strlen ($fieldValue) == 0)
				return false;	

			$fieldValue = ereg_replace ("[a-zA-Z0-9_]+", "", $fieldValue);
			
			if (strlen ($fieldValue) > 0)
				return false;
		}
		else if ($fieldName == 'Description')
		{
			if (strlen ($fieldValue) == 0)
				return false;
		} 
		
		return true;
	}
}
?>