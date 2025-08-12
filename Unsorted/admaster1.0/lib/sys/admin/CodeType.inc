<?
class CodeType extends DataObject 
{
	var $name = "CodeType";
	
	var $updateError   = "The code type has not been updated.",
		$updateSuccess = "The code type has been updated.",
		$addError      = "The new code type has not been added.",
		$addSuccess    = "The new code type has been added.",
		$deleteError   = "Some of selected code types have not deleted.",
		$deleteSuccess = "All selected code types have been deleted.";
	
	var $fieldValue = array ('Name'       => '',
					  		 'Description' => '',
					  		 'DefaultCode' => '');
					  		
	var $fieldName  = array ('Name'       => 'The type name',
					  		 'Description' => 'The type description',
					  		 'DefaultCode' => 'Default code');
	
	var $fieldError = array ('Name'       => 'not correct',
					  		 'Description' => 'not correct',
					  		 'DefaultCode' => '');
	
	var $error;
	
	function Category ()
	{
		$this->error = array ();	
	}
	
	function input ($fieldName, $fieldValue)
	{
		if ($fieldName == 'Description' || $fieldName == 'DefaultCode')
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
		} 
		
		return true;
	}
}
?>