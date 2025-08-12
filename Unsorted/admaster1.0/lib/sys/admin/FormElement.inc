<?

class FormElement extends DataObject 
{
	var $name = "FormElement";
	
	var $updateError   = "The form element has not been updated.",
		$updateSuccess = "The form element has been updated.",
		$addError      = "The new form element has not been added.",
		$addSuccess    = "The new form element has been added.",
		$deleteError   = "Some of selected form elements have not deleted.",
		$deleteSuccess = "All selected form elements have been deleted.";
	
	var $fieldValue = array ('FormID'      => '',
					  	     'Name'        => '',
					  		 'Description' => '',
					  		 'Error'       => '',
					  		 'Template'    => '',
					  		 'Value'       => '',
					  		 'Type'        => '');
					  		
	var $fieldName  = array ('FormID'      => 'ID of the form',
					  	     'Name'        => 'Name of the new element',
					  		 'Description' => 'Description',
					  		 'Error'       => 'Error message',
					  		 'Template'    => 'Template',
					  		 'Value'       => 'Default value',
					  		 'Type'        => 'Skip this for first level elements');
	
	var $fieldError = array ('FormID'      => 'not correct',
					  	     'Name'        => 'not correct',
					  		 'Description' => 'not correct',
					  		 'Error'       => '',
					  		 'Template'    => 'not correct',
					  		 'Value'       => '',
					  		 'Type'        => '');
	
	var $error;
	
	function FormElement ()
	{
		$this->error = array ();	
	}
	
	function input ($fieldName, $fieldValue)
	{
		if ($fieldName == 'FormID')
		{
			$input = "<b>$fieldValue</b>\n";
			$input .= "<input type=hidden name=$fieldName value=$fieldValue>";
		}
		else if ($fieldName == 'Description' || $fieldName == 'Template')
		{
			$input = "<textarea name=$fieldName rows=3 cols=30>$fieldValue</textarea>\n";
		}
		else if ($fieldName == 'Type')
		{
			$input = "<select name=$fieldName>\n";
			
			if ($fieldValue == '1')
				$input .= "<option value=1 selected>Second level\n";
			else
				$input .= "<option value=1>Second level\n";
			
			if ($fieldValue == '0' || $fieldValue != '1')
				$input .= "<option value=0 selected>First level\n";
			else
				$input .= "<option value=0>First level\n";			
				
			$input .= "</select>";
		}
		else 
		{
			$input = "<input type=text size=35 maxsize=50 name=$fieldName value=\"$fieldValue\">\n";
		}
		
		return $input;
	}
	function checkField ($fieldName, $fieldValue)
	{
		if ($fieldName == 'Type')
		{
			if ($fieldValue != '1' && $fieldValue != '0')
				return false;
		}
		else if ($fieldName == 'Name')
		{
			if (strlen ($fieldValue) == 0)
				return false;	

			$fieldValue = ereg_replace ("[a-zA-Z]+", "", $fieldValue);
			
			if (strlen ($fieldValue) > 0)
				return false;
		}
		else if ($fieldName == 'Description' || $fieldName == 'Template')
		{
			if (strlen ($fieldValue) == 0)
				return false;
		} 
		
		return true;
	}
}
?>