<?

class Form extends DataObject 
{
	var $name = "Form";
	
	var $updateError   = "The form has not been updated.",
		$updateSuccess = "The form has been updated.",
		$addError      = "The new form has not been added.",
		$addSuccess    = "The new form has been added.",
		$deleteError   = "Some of selected forms have not deleted.",
		$deleteSuccess = "All selected forms have been deleted.";
	
	var $fieldValue = array ('GroupID'     => '',
					  	     'Name'        => '',
					  		 'Description' => '',
					  		 'Template'    => '',
					  		 'StopMessage' => '',
					  		 'Error'       => '',
					  		 'Success'     => '');
					  		
	var $fieldName  = array ('GroupID'     => 'ID of the form group',
					  	     'Name'        => 'Name of the new form',
					  		 'Description' => 'Description',
					  		 'Template'    => 'Template',
					  		 'StopMessage' => 'The message per total error',
					  		 'Error'       => 'The message per wrong form filling',
					  		 'Success'     => 'The message per right form filling');
	
	var $fieldError = array ('GroupID'     => '',
					  	     'Name'        => 'not correct',
					  		 'Description' => 'not correct',
					  		 'Template'    => 'not correct',
					  		 'StopMessage' => '',
					  		 'Error'       => '',
					  		 'Success'     => '');
	
	var $error;
	
	function Form ()
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
		else if ($fieldName == 'Description' || $fieldName == 'Template' || $fieldName == 'StopMessage' || $fieldName == 'Success')
		{
			$input = "<textarea name=$fieldName rows=5 cols=50>$fieldValue</textarea>\n";
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
		else if ($fieldName == 'Name' || $fieldName == 'ActionName')
		{
			if (strlen ($fieldValue) == 0)
				return false;	

			$fieldValue = ereg_replace ("[a-zA-Z]+", "", $fieldValue);
			
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