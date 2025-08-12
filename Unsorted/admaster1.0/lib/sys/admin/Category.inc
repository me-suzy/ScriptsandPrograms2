<?
class Category extends DataObject 
{
	var $name = "Category";
	
	var $updateError   = "The category has not been updated.",
		$updateSuccess = "The category has been updated.",
		$addError      = "The new category has not been added.",
		$addSuccess    = "The new category has been added.",
		$deleteError   = "Some of selected categories have not deleted.",
		$deleteSuccess = "All selected categories have been deleted.";
	
	var $fieldValue = array ('Name'       => '',
					  		 'Description' => '');
					  		
	var $fieldName  = array ('Name'       => 'The category title',
					  		 'Description' => 'The category description');
	
	var $fieldError = array ('Name'       => 'not correct',
					  		 'Description' => 'not correct');
	
	var $error;
	
	function Category ()
	{
		$this->error = array ();	
	}
	function delete ($id)
	{
		$db = new DB ();
		
		if ($db->execute ("delete from ".$this->name." where ID = $id"))
		{
			$this->message = $this->deleteSuccess;
			$db->execute ("delete from Program where CatID = $id");
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
		} 
		
		return true;
	}
}
?>