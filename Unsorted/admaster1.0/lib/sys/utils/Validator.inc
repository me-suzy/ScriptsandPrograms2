<?

class Validator 
{
	var $formID;
	
	var $errorMsg;	
	
	function Validator ($formGroupName, $formName)
	{
		$db = new DB ();
		$db->execute ("select ID from FormGroup where Name = '$formGroupName'");
		$formGroupID = $db->recordSet [0][0];
		
		$db->execute ("select ID from Form where Name = '$formName' and GroupID = $formGroupID");
		$this->formID = $db->recordSet [0][0];
		
		$this->errorMsg      = '';
	}
	
	function error ()
	{
		return $errorMsg;
	}
	
	function isCorrect ($alias, $value)
	{
		$db = new DB ();
		
		$query = "select Alias, Type, Name, Error, MaxLength, MinLength, MinInclusive, MaxInclusive, 
						 MinExclusive, MaxExclusive, Pattern 
				  from   FormElement 
				  where  Alias = '$alias' and FormID = ".$this->formID;
		$db->execute ($query);
		
		if ($db->getNumRows () == 0)
			return true;
		
		$type         = $db->recordSet [0][1];
		$name         = $db->recordSet [0][2];
		$error        = $db->recordSet [0][3];
		$maxLength    = $db->recordSet [0][4];
		$minLength    = $db->recordSet [0][5];	
		$minInclusive = $db->recordSet [0][6];
		$maxInclusive = $db->recordSet [0][7];
		$minExclusive = $db->recordSet [0][8];
		$maxExclusive = $db->recordSet [0][9];
		$pattern      = $db->recordSet [0][10];
		
		if ($type == 'string')
		{
			if ((strlen ($pattern) > 0 && !ereg ($pattern, $value)) ||
				 strlen ($value) < $minLength || 
				 strlen ($value) > $maxLength)
			{
				$this->errorMsg = $error;
				
				return false;
			}
			
			return true;
		}
		
		// check this is a number at all
		if ($value*1 != $value)
		{
			$this->errorMsg = $error;
				
			return false;		
		}
		// check for integer
		if ($type == 'int' && $value != floor ($value))
		{
			$this->errorMsg = $error;
				
			return false;		
		}
		
		if ((strlen ($minInclusive) > 0 && $value < $minInclusive) || 
			(strlen ($maxInclusive) > 0 && $value > $maxInclusive) || 
			(strlen ($minExclusive) > 0 && $value <= $minExclusive) || 
			(strlen ($maxExclusive) > 0 && $value >= $maxExclusive))
		{
			$this->errorMsg = $error;
				
			return false;			
		}
	
		return true;
	}
}
?>