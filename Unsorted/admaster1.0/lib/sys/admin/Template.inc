<?

class Template extends DataObject 
{
	var $name = "Template";
	
	var $updateError   = "The template has not been updated.",
		$updateSuccess = "The template has been updated.",
		$addError      = "The new template has not been added.",
		$addSuccess    = "The new template has been added.",
		$deleteError   = "Some of selected templates have not deleted.",
		$deleteSuccess = "All selected templates have been deleted.";
	
	var $fieldValue = array ('GroupID'     => '',
					  	     'Name'        => '',
					  		 'Description' => '',
					  		 'Body'        => '');
					  		
	var $fieldName  = array ('GroupID'     => 'ID of the form group',
					  	     'Name'        => 'Name of the new form',
					  		 'Description' => 'Description',
					  		 'Body'        => 'Template body');
	
	var $fieldError = array ('GroupID'     => '',
					  	     'Name'        => 'not correct',
					  		 'Description' => 'not correct',
					  		 'Body'        => '');
	
	var $error;
	
	function Template ()
	{
		$this->error = array ();	
	}
	
	function input ($fieldName, $fieldValue)
	{
		if ($fieldName == 'GroupID')
		{
			$input = "<b>$fieldValue</b>\n";
			$input .= "<input type=hidden name=$fieldName value=$fieldValue>";
		}
		else if ($fieldName == 'Description')
		{
			$input = "<textarea name=$fieldName rows=5 cols=50>$fieldValue</textarea>\n";
		}
		else if ($fieldName == 'Body')
		{
			$fieldValue = htmlspecialchars ($fieldValue);
			$input = "<textarea name=$fieldName rows=25 cols=60>".$fieldValue."</textarea>\n";
		}
		else 
		{
			$input = "<input type=text size=35 maxsize=50 name=$fieldName value=\"$fieldValue\">\n";
		}
		
		return $input;
	}
	function checkField ($fieldName, $fieldValue)
	{
		if ($fieldName == 'GroupID')
		{
			if ($fieldValue*1 == 0)
				return false;
		}
		else if ($fieldName == 'Name')
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