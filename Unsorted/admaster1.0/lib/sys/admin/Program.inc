<?

class Program extends DataObject 
{
	var $name = "Program";
	
	var $updateError   = "The web master program has not been updated.",
		$updateSuccess = "The web master program has been updated.",
		$addError      = "The new web master program has not been added.",
		$addSuccess    = "The new web master program has been added.",
		$deleteError   = "Some of selected web master programs have not deleted.",
		$deleteSuccess = "All selected web master programs have been deleted.";
	
	var $fieldValue = array ('CatID'       => '',
							 'Name'       => '',
					  		 'Description' => '');
					  		
	var $fieldName  = array ('CatID'       => 'The category ID',
							 'Name'       => 'The program title',
					  		 'Description' => 'The program description');
	
	var $fieldError = array ('CatID'       => 'not correct',
							 'Name'       => 'not correct',
					  		 'Description' => 'not correct');
	
	var $error;
	
	function Program ()
	{
		$this->error = array ();	
	}
	function delete ($id)
	{
		$db = new DB ();
		
		if ($db->execute ("delete from ".$this->name." where ID = $id"))
		{
			$this->message = $this->deleteSuccess;
			$db->execute ("delete from Code where ProgramID = $id");
			return true;
		}
		
		$this->message = $this->deleteError;
		return false;
	}
	function input ($fieldName, $fieldValue)
	{
		if ($fieldName == 'CatID')
		{
			$input = "<b>$fieldValue</b>\n";
			$input .= "<input type=hidden name=$fieldName value=$fieldValue>";
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
		if ($fieldName == 'Name')
		{
			if (strlen ($fieldValue) == 0)
				return false;
		} 
		
		return true;
	}
}
?>