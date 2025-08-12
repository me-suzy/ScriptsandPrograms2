<?
/*
Use templates: Model, LeftPanel


*/
include_once "sys/template/Action.inc";

class UBAI extends Action
{
	// actionVar of global variable that can containt the action value
	var $actionVar     = "Action";
	
	// names of the actions  and the names of methods 
	// that will process them
	var $action        = array ('Update'    => 'update');
								
	// default action actionVar
	var $defActionName = 'Update'; 	
	
	function UBAI ($data)
	{
		$this->Action ($data);
	}
	function validateForm ($step)
	{
		switch ($step)
		{
			case 0:
			{
				global $AccountHolder,
					   $AccountNumber,
					   $BankCode,
					   $BankName,
					   $SWIFT,
					   $BankStreet,
					   $BankCity,
					   $BankCountry;

				// let's do dummy things
				if (!$this->isValid ("AccountHolder", $AccountHolder))
					$this->addError ($this->getTemplate ("UBAI", "Error_AccountHolder"));
				//+
				if (!$this->isValid ("AccountNumber", $AccountNumber))
					$this->addError ($this->getTemplate ("UBAI", "Error_AccountNumber"));
				//+
				if (!$this->isValid ("BankCode", $BankCode))
					$this->addError ($this->getTemplate ("UBAI", "Error_BankCode"));
				//+
				if (!$this->isValid ("BankName", $BankName))
					$this->addError ($this->getTemplate ("UBAI", "Error_BankName"));
				//+
				if (!$this->isValid ("SWIFT", $SWIFT))
					$this->addError ($this->getTemplate ("UBAI", "Error_SWIFT"));
				//+
				if (!$this->isValid ("BankStreet", $BankStreet))
					$this->addError ($this->getTemplate ("UBAI", "Error_BankStreet"));
				//+
				if (!$this->isValid ("BankCity", $BankCity))
					$this->addError ($this->getTemplate ("UBAI", "Error_BankCity"));
				//+
				if (!$this->isValid ("BankCountry", $BankCountry))
					$this->addError ($this->getTemplate ("UBAI", "Error_BankCountry"));
				// waw
			}
			
			default:
				;
		}
		
		if (sizeof ($this->error) == 0)
			$this->finish ($step);
	}
	
	function finish ($step)
	{
		switch ($step)
		{
			case 0:
			{
				global $uid;
				global $AccountHolder,
					   $AccountNumber,
					   $BankCode,
					   $BankName,
					   $SWIFT,
					   $BankStreet,
					   $BankCity,
					   $BankCountry;				
				
				
				$db = new DB ();
				$db->execute ("update BankAccount 
							   set    AccountHolder = '".addslashes ($AccountHolder)."', 
							   		  AccountNumber = '".addslashes ($AccountNumber)."',
							   		  BankCode 		= '".addslashes ($BankCode)."',
							   		  BankName 		= '".addslashes ($BankName)."',
							   		  SWIFT 		= '".addslashes ($SWIFT)."',
							   		  BankStreet 	= '".addslashes ($BankStreet)."',
							   		  BankCity 		= '".addslashes ($BankCity)."',
							   		  BankCountry 	= '".addslashes ($BankCountry)."' 
							   where  UserID 		= $uid");
			}
			default:
				;
		}
	}
	function displayForm ($id)
	{
		global $uid;
		
		global ${"FORM_$id"};
		
		$form	 = $this->getTemplate ("UBAI", "FORM_$id");
		
		$form = $this->replace ("Hidden", $this->inHidden (), $form);

		global $AccountHolder,
			   $AccountNumber,
			   $BankCode,
			   $BankName,
			   $SWIFT,
			   $BankStreet,
			   $BankCity,
			   $BankCountry;		

		$db = new DB ();
		$db->execute ("select AccountHolder, AccountNumber, 
							  BankCode, BankName, SWIFT, 
							  BankStreet, BankCity, BankCountry
					   from   BankAccount 
					   where  UserID = $uid");
		
		if (!isset ($AccountHolder))
			$AccountHolder = stripslashes ($db->recordSet [0][0]);
		
		if (!isset ($AccountNumber))
			$AccountNumber = stripslashes ($db->recordSet [0][1]);
		
		if (!isset ($BankCode))
			$BankCode = stripslashes ($db->recordSet [0][2]);
		
		if (!isset ($BankName))
			$BankName = stripslashes ($db->recordSet [0][3]);
		
		if (!isset ($SWIFT))
			$SWIFT = stripslashes ($db->recordSet [0][4]);
		
		if (!isset ($BankStreet))
			$BankStreet = stripslashes ($db->recordSet [0][5]);
		
		if (!isset ($BankCity))
			$BankCity = stripslashes ($db->recordSet [0][6]);
		
		if (!isset ($BankCountry))
			$BankCountry = stripslashes ($db->recordSet [0][7]);
		
		
		// 
		$form = $this->replace ("AccountHolder", $AccountHolder, $form);
		$form = $this->replace ("AccountNumber", $AccountNumber, $form);
		$form = $this->replace ("BankCode", 	 $BankCode, 	 $form);
		$form = $this->replace ("BankName", 	 $BankName, 	 $form);
		$form = $this->replace ("SWIFT", 	     $SWIFT, 		 $form);
		$form = $this->replace ("BankStreet", 	 $BankStreet, 	 $form);
		$form = $this->replace ("BankCity", 	 $BankCity, 	 $form);
		$form = $this->replace ("BankCountry",   $BankCountry, 	 $form);
		
		$success = $this->getTemplate ("UBAI", "SUCCESS_$id");
		
		$error = $this->displayError ();
		
		if (strlen ($error) == 0)
		{
			if (isset (${"FORM_$id"}))
				return $success.$form;
			else
				return $form;
		}
		
		return $error.$form;
	}
	function update ()
	{
		// very global param
		global $uid;
		
		// more local
	 	global $FORM_0;
	 	
		$displayable = '';
		
		if (isset ($FORM_0))
			$this->validateForm (0);

		$displayable = $this->displayForm (0);
		
		$this->replace ("Main", $displayable);
	}

	function prepare ()
	{
		global $uid;
			   
		$this->add ($this->getTemplate ("Base", "MainPanel"));
		$this->replace ("TopControls", "");
		
		$db = new DB ();
		$db->execute ("select FirstName, Name from User where ID = $uid");
		$fname = htmlspecialchars (stripslashes ($db->recordSet [0][0]));
		$lname = htmlspecialchars (stripslashes ($db->recordSet [0][1]));
		$this->replace ("UserWelcome", "<b>$fname $lname</b> is logged.");
	}

	function isValid ($name, $value)
	{
		if ($name == "AccountHolder")
		{
			if (strlen ($value) == 0)
				return false;
		}
		else if ($name == "AccountNumber")
		{
			if (strlen ($value) == 0)
				return false;		
		}
		else if ($name == "BankCode")
		{
			if (strlen ($value) == 0)
				return false;		
		}
		else if ($name == "BankName")
		{
			if (strlen ($value) == 0)
				return false;		
		}
		else if ($name == "SWIFT")
		{
			if (strlen ($value) == 0)
				return false;		
		}
		else if ($name == "BankStreet")
		{
			if (strlen ($value) == 0)
				return false;		
		}
		else if ($name == "BankCity")
		{
			if (strlen ($value) == 0)
				return false;		
		}
		else if ($name == "BankCountry")
		{
			if (strlen ($value) == 0)
				return false;		
		}
		
		return true;
	}	
}
?>