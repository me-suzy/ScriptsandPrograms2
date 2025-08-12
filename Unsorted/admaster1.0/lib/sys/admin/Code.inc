<?

class Code extends DataObject 
{
	var $name = "Code";
	
	var $updateError   = "The code has not been updated.",
		$updateSuccess = "The code has been updated.",
		$addError      = "The new code has not been added.",
		$addSuccess    = "The new code has been added.",
		$deleteError   = "Some of selected codes have not deleted.",
		$deleteSuccess = "All selected codes have been deleted.";
	
	var $fieldValue = array ('ProgramID'   => '',
	 						 'Name'       => '',
							 'Type'        => '',
					  		 'Description' => '',
					  		 'Code'        => '');
					  		
	var $fieldName  = array ('ProgramID'   => 'The program ID',
	 						 'Name'       => 'The code title',
							 'Type'        => 'The code type',
					  		 'Description' => 'The code description',
					  		 'Code'        => 'The code');
	
	var $fieldError = array ('ProgramID'   => 'not correct',
	 						 'Name'       => 'not correct',
							 'Type'        => 'not correct',
					  		 'Description' => 'not correct',
					  		 'Code'        => 'not correct');
	
	var $error;
	
	function Code ()
	{
		$this->error = array ();	
	}
	
	function input ($fieldName, $fieldValue)
	{
		if ($fieldName == 'ProgramID')
		{
			$input = "<b>$fieldValue</b>\n";
			$input .= "<input type=hidden name=$fieldName value=$fieldValue>";
		}
		else if ($fieldName == 'Description' || $fieldName == 'Code')
		{
			$input = "<textarea name=$fieldName rows=3 cols=30>$fieldValue</textarea>\n";
		}
		else if ($fieldName == 'Type')
		{
			$db = new DB ();
			$db->execute ("select Name, DefaultCode from CodeType order by ID");
			
			$input = "<select name=$fieldName size=5 onChange=\"select ();\">\n";
			
			if ($db->getNumRows () == 0)
				return "<font color=red><b>Add at least one code type by means of <br>corresponding part of admin interface.</b></font>\n";
			$selected = "";
			$selectStatus = false;
			$hidden = '';
			for ($i = 0; $i < $db->getNumRows (); $i++)
			{
				global ${$fieldName};
							
				if ($selectStatus)
					$selected = "";
				else if ((isset (${$fieldName}) && ${$fieldName} == $db->recordSet [$i][0]) ||
					(!isset (${$fieldName}) && $db->recordSet [$i][0] == $fieldValue))
				{
					$selected = " selected";
					$selectStatus = true;
				}

				$input .= "<option value=\"".$db->recordSet [$i][0]."\"$selected>".$db->recordSet [$i][0];
				$hidden .= "<input type=hidden name=DefaultCode$i value=\"".$db->recordSet [$i][1]."\">";
			}
			$input .= "</select>\n$hidden";

			
			return $input;
		}
		else 
		{
			$input = "<input type=text size=35 maxsize=50 name=$fieldName value=\"$fieldValue\">\n";
		}
		
		return $input;
	}
	function checkField ($fieldName, $fieldValue)
	{
		if ($fieldName == 'Description' || $fieldName == 'Name')
		{
				if (strlen ($fieldValue) == 0)
					return false;		
		}
		
		
		return true;
	}
}
?>