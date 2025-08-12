<?
/*
Use templates: Model, LeftPanel


*/
include_once "sys/template/Action.inc";

class URP extends Action
{
	// actionVar of global variable that can containt the action value
	var $actionVar     = "Action";
	
	// names of the actions  and the names of methods 
	// that will process them
	var $action        = array ('Update'    => 'update');
								
	// default action actionVar
	var $defActionName = 'Update'; 	
	
	var $form = array (array ("Company",
			 				  "FirstName",
		                   	  "Name",
						      "Street",
						      "Country",
						      "ZipCode",
						      "Location",
						      "WorkPhone",
						      "HomePhone",
						      "MobilePhone",
			 			      "Fax",
			 			      "Email",
			 			      "BirthDay",
			 			      "BirthMonth",
			 			      "BirthYear"),
			 		   array ("Agree1",
			 		   		  "Agree2"),
			 		   array ("UserName",
			 		   		  "UserPswdA"));
	var $toggle = 0;
	
	function URP ($data)
	{
		$this->Action ($data);
	}
	function validateForm ($id)
	{
		for ($i = 0; $i < sizeof ($this->form [$id]); $i++)
		{
			global ${$this->form [$id][$i]};
			
			if (!isset (${$this->form [$id][$i]}))
				${$this->form [$id][$i]} = "NULL";
				
			$this->validateItem ($this->form [$id][$i], ${$this->form [$id][$i]}, $id);
		}
		
		$this->hidePrevInfo ($id - 1);
		
		if (sizeof ($this->error) == 0)
			$this->completeForm ($id);
	}
	
	function hidePrevInfo ($id)
	{
		if ($id < 0)
			return;
			
		for ($j = 0; $j <= $id; $j++)
		{
			for ($i = 0; $i < sizeof ($this->form [$j]); $i++)
			{
				global ${$this->form [$j][$i]};
				
				$this->store (array ($this->form [$j][$i] => ${$this->form [$j][$i]}));
			}		
		}
	}
	function completeForm ($id)
	{
		switch ($id)
		{
			case 0:
			{
				$this->hidePrevInfo (0);
				break;
			}
			case 1:
			{
				$this->hidePrevInfo (0);
				break;
			}
			case 2:
			{
				// get global params
				for ($j = 0; $j <= $id; $j++)
					for ($i = 0; $i < sizeof ($this->form [$j]); $i++)
						global ${$this->form [$j][$i]};

				// preliminary actions
				$BirthDate = $BirthYear."-".$BirthMonth."-".$BirthDay;
				
				if (isset ($Agree1) && $Agree1 == 'true')
					$Agree1 = '1';
				else
					$Agree1 = '0';
			
				
				$db = new DB ();
				
				$query = "insert into User (UserName, 
											Password, 
											FirstName, 
											Name, 
											Email, 
											Country,
											City,
											Location, 
											Street, 
											WorkPhone, 
											HomePhone, 
											MobilePhone, 
											Fax, 
											ZipCode, 
											BirthDate, 
											RegDate,
											Company,
											MwStSelf)
						  values            ('".addslashes ($UserName)."',
						  					 '".addslashes ($UserPswdA)."',
						  					 '".addslashes ($FirstName)."',
						  					 '".addslashes ($Name)."',
						  					 '".addslashes ($Email)."',
						  					 '".addslashes ($Country)."',
						  					 '".addslashes ($City)."',
						  					 '".addslashes ($Location)."',
						  					 '".addslashes ($Street)."',
						  					 '".addslashes ($WorkPhone)."',
						  					 '".addslashes ($HomePhone)."',
						  					 '".addslashes ($MobilePhone)."',
						  					 '".addslashes ($Fax)."',
						  					 '".addslashes ($ZipCode)."',
						  					 '$BirthDate',
						  					 curdate(),
						  					 '".addslashes ($Company)."',
						  					 '$Agree1')";

				$db->execute ($query);
				
				$lid = $db->lastID ();
				
				$db->execute ("insert into BankAccount (UserID) values ($lid)");
				$db->execute ("insert into MoneyTransfer (UserID) values ($lid)");
			}
			default:
				;
		}
	}
	function displayForm ($id)
	{
		global $uid;
		
		global ${"FORM_$id"};
		
		$form	 = $this->getTemplate ("URP", "FORM_$id");
				
		$form = $this->replace ("Hidden", $this->inHidden (), $form);
		
		for ($i = 0; $i < sizeof ($this->form [$id]); $i++)
		{
			global ${$this->form [$id][$i]};
			
			if (isset (${$this->form [$id][$i]}))
				$form = $this->replace ($this->form [$id][$i], ${$this->form [$id][$i]}, $form);
			
		}		
		
		$message = $this->getTemplate ("URP", "Message_$id");
		
		$error = $this->displayError ();
		
		if (strlen ($error) == 0)
		{
			if (isset (${"FORM_$id"}))
			{
				$this->toggle = 1;
				
				if ($id == 2)
				{
					global $UserName;
					
					$message = $this->replace ("UserName", $UserName, $message);
				}
				return $message;
			}
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
	 	global $FORM_0,
	 		   $FORM_1,
	 		   $FORM_2;
	 	
		$displayable = '';
		
		if (isset ($FORM_0))
			$this->validateForm (0);
			
		$displayable = $this->displayForm (0);
		
		if ($this->toggle == 0 && (isset ($FORM_0) || 
			(!isset ($FORM_1) && !isset ($FORM_2))))
		{
			$this->replace ("Main", $displayable);
	
			return;
		}
		
		$this->toggle = 0;
		
		if (isset ($FORM_1))
			$this->validateForm (1);
		
		$displayable = $this->displayForm (1);
		
		if ($this->toggle == 0 && 
			(isset ($FORM_1) || isset ($FORM_0)))
		{
	
			$this->replace ("Main", $displayable);
			
			return;
		}	
		
		$this->toggle = 0;
		
		if (isset ($FORM_2))
			$this->validateForm (2);
		
		$displayable = $this->displayForm (2);
		
		if (isset ($FORM_2) || isset ($FORM_1))
		{
			$this->replace ("Main", $displayable);
			
			return;
		}
	}

	function prepare ()
	{
		global $uid;
			   
		$this->add ($this->getTemplate ("Base", "MainPanel"));
		$this->replace ("TopControls", "");
	}

	function validateItem ($name, $value, $id)
	{
		switch ($id)
		{
			case 0:
			{
				if ($name == "Company")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_".$name));
				}
				else if ($name == "FirstName")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_".$name));
				}
				else if ($name == "Name")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_".$name));
				}
				else if ($name == "Street")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_".$name));
				}
				else if ($name == "Country")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_".$name));
				}
				else if ($name == "ZipCode")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_".$name));
				}
				else if ($name == "Location")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_".$name));
				}
				else if ($name == "WorkPhone")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_".$name));
				}
				else if ($name == "HomePhone")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_".$name));
				}
				else if ($name == "MobilePhone")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_".$name));
				}
				else if ($name == "Fax")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_".$name));
				}
				else if ($name == "Email")
				{
					if (!ereg("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*$", $value))
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_".$name));
				}
				else if ($name == "BirthDay")
				{
					if ($value < 1 || $value > 31)
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_".$name));
				}
				else if ($name == "BirthMonth")
				{
					if ($value < 1 || $value > 12)
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_".$name));
				}
				else if ($name == "BirthYear")
				{
					if ($value < 1900 || $value > 1990)
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_".$name));
				}
			}
			case 1:
			{
				/*if ($name == "Agree1")
				{
					if ($value == "NULL" || $value == "false")
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_".$name));
				}
				else 
				*/
				if ($name == "Agree2")
				{
					if ($value == "NULL")
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_".$name));
				}			
			
			}
			case 2:
			{
				if ($name == "UserName")
				{
					if (strlen ($value) == 0)
					{
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_".$name));
						break;
					}
					$db = new DB ();
					$db->execute ("select ID from User where UserName = '$value'");
					
					if ($db->getNumRows () > 0)
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_UserExists"));
				}
				else if ($name == "UserPswdA")
				{
					global $UserPswdA,
						   $UserPswdB;

					if (!isset ($UserPswdA) || !isset ($UserPswdB) || 
						strlen ($value) < 4 || strlen ($value) > 10)
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_UserPswd"));
					
					if (isset ($UserPswdA) && isset ($UserPswdB) && 
						$UserPswdA != $UserPswdB)
						$this->addError ($this->getTemplate ("URP", "Error_".$id."_PswdDifference"));	
				}
			}
			default:
				;
		}
		return true;
	}	
}
?>