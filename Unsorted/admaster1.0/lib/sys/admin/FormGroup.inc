<?

class FormGroup extends DataObject 
{
	var $name = "FormGroup";
	
	var $updateError   = "The form group has not been updated.",
		$updateSuccess = "The form group has been updated.",
		$addError      = "The new form group has not been added.",
		$addSuccess    = "The new form group has been added.",
		$deleteError   = "Some of selected form groups have not deleted.",
		$deleteSuccess = "All selected form groups have been deleted.";
	
	var $fieldValue = array ('Name'        => '',
					  		 'Description' => '',
					  		 'Method'      => '',
					  		 'Action'      => '');
					  		
	var $fieldName  = array ('Name'        => 'The form group name',
					  		 'Description' => 'The form group description',
					  		 'Method'      => 'The form group method',
					  		 'Action'      => 'Link to script');
	
	var $fieldError = array ('Name'        => 'not correct',
					  		 'Description' => 'not correct',
					  		 'Method'      => 'not correct',
					  		 'Action'      => 'not correct');
	
	var $error;
	
	function FormGroup ()
	{
		$this->error = array ();	
	}
	
	function input ($fieldName, $fieldValue)
	{
		if ($fieldName == 'Method')
		{
			$input = "<select name=$fieldName>\n";
			
			if ($fieldValue == 'POST')
				$input .= "<option value=POST selected>POST\n";
			else
				$input .= "<option value=POST>POST\n";
			
			if ($fieldValue == 'GET' || $fieldValue != 'POST')
				$input .= "<option value=GET selected>GET\n";
			else
				$input .= "<option value=GET>GET\n";			
				
			$input .= "</select>";
		}
		else if ($fieldName == 'Description')
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
		if ($fieldName == 'Method')
		{
			if ($fieldValue != 'POST' && $fieldValue != 'GET')
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
		else if ($fieldName == 'Description')
		{
			if (strlen ($fieldValue) == 0)
				return false;
		} 
		
		return true;
	}
}
?>