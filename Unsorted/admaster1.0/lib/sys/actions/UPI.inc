<?
/*
Use templates: Model, LeftPanel


*/
include_once "sys/template/Action.inc";

class UPI extends Action
{
	// actionVar of global variable that can containt the action value
	var $actionVar     = "Action";
	
	// names of the actions  and the names of methods 
	// that will process them
	var $action        = array ('run'    => 'run');
								
	// default action actionVar
	var $defActionName = 'run'; 	
	
	var $form = array (array ("UserName"),
					   array ("Company",
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
			 			      "BirthYear",
			 		   		  "UserName",
			 		   		  "UserPswdA"));

	function UPI ($data)
	{
		$this->Action ($data);
		
		global $aid;
		
		if (!isset ($aid))
			$aid = 0;
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
		
		if (sizeof ($this->error) == 0)
			return true;
			
		return false;
	}
	
	// User Super Secure
	function printUSS ()
	{
		$tmplBase = $this->getTemplate ("UPI", "WML_UPI_USS");
		
		global $UserName;
		
		if (!isset ($UserName))
			$UserName = '';
		
		$this->store (array ("aid" => 0));
		
		$tmplBase = $this->replace ("UserName", $UserName, $tmplBase);
		
		return $this->replace ("Hidden", $this->inHidden (), $tmplBase);
	}
	
	// User Information Updating
	function printUIU ()
	{
		global $uid;
		
		$tmplBase = $this->getTemplate ("UPI", "WML_UPI_UIU");
		
		$db = new DB ();
		
		for ($i = 0; $i < sizeof ($this->form [1]); $i++)
		{
			global ${$this->form [1][$i]};
			
			if ($this->form [1][$i] == "UserPswdA")
				continue;
				
			if ($this->form [1][$i] == "BirthYear")
				$db->execute ("select year(BirthDate) from User where ID = $uid");
			else if ($this->form [1][$i] == "BirthMonth")
				$db->execute ("select month(BirthDate) from User where ID = $uid");
			else if ($this->form [1][$i] == "BirthDay")
				$db->execute ("select dayofmonth(BirthDate) from User where ID = $uid");
			else
				$db->execute ("select ".$this->form [1][$i]." from User where ID = $uid");
			
			if (!isset (${$this->form [1][$i]}))
				${$this->form [1][$i]} = $db->recordSet [0][0];
			
			
			$tmplBase = $this->replace ($this->form [1][$i], ${$this->form [1][$i]}, $tmplBase);
		}
		
		$this->store (array ("aid" => 1));
		
		return $this->replace ("Hidden", $this->inHidden (), $tmplBase);
	}
	
	// UserInformation Updating Result
	
	function printUIUR ()
	{
		global $uid;
		
		// get global params
		for ($i = 0; $i < sizeof ($this->form [1]); $i++)
			global ${$this->form [1][$i]};
				
		// preliminary actions
		$BirthDate = $BirthYear."-".$BirthMonth."-".$BirthDay;
		
		$pswdBlock = '';
		if (strlen ($UserPswdA) > 0)
			$pswdBlock = "Password    = '".addslashes ($UserPswdA)."', ";
		
		$db = new DB ();
		
		$query = "update User set   UserName    = '".addslashes ($UserName)."', 
									$pswdBlock 
									FirstName   = '".addslashes ($FirstName)."', 
									Name        = '".addslashes ($Name)."', 
									Email       = '".addslashes ($Email)."', 
									Country     = '".addslashes ($Country)."',
									City        = '".addslashes ($City)."' ,
									Location    = '".addslashes ($Location)."', 
									Street      = '".addslashes ($Street)."' , 
									WorkPhone   = '".addslashes ($WorkPhone)."', 
									HomePhone   = '".addslashes ($HomePhone)."', 
									MobilePhone = '".addslashes ($MobilePhone)."', 
									Fax         = '".addslashes ($Fax)."', 
									ZipCode     = '".addslashes ($ZipCode)."', 
									BirthDate   = '$BirthDate', 
									Company     = '$Company'
				 where              ID = $uid";

		$db->execute ($query);		
	
		//
		
		return $this->getTemplate ("UPI", "WML_UPI_UIUR");
	}
	function run ()
	{
		// action id
	 	global $aid;
	 	
		$displayable = '';
		
		switch ($aid)
		{
			case 0:
			{	
				global $WML_UPI_USS;
				
				if (!isset ($WML_UPI_USS))
				{
					$displayable .= $this->printUSS ();
					
					break;
				}
				else if (!$this->validateForm (0))
				{
					$displayable .= $this->displayError ().$this->printUSS ();
					
					break;
				}
				
				$aid = 1;
			}
			case 1:
			{
				global $WML_UPI_UIU;
				
				if (!isset ($WML_UPI_UIU))
				{
					$displayable .= $this->printUIU ();
					
					break;
				}
				else if (!$this->validateForm (1))
				{
					$displayable .= $this->displayError ().$this->printUIU ();
					
					break;
				}
				
				$aid = 2;
			}
			case 2:
			{
				$displayable .= $this->printUIUR ().$this->printUSS ();
				
				break;
			}
			default:
				;
		}
		
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

	function validateItem ($name, $value, $id)
	{
		switch ($id)
		{
			case 0:
			{
				if ($name == "UserName")
				{
					global $uid,
						   $UserPswd,
						   $UserName;
					
					$db = new DB ();
					$db->execute ("select UserName, Password from User where ID = $uid");
					
					
					if ($db->getNumRows () == 0 || 
						$UserName != stripslashes ($db->recordSet [0][0]) ||
						$UserPswd != stripslashes ($db->recordSet [0][1]))
						$this->addError ($this->getTemplate ("UPI", "WML_UPI_USS_NCV"));
				}
				
				break;
			}
			case 1:
			{
				if ($name == "Company")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_0_".$name));
				}
				else if ($name == "FirstName")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_0_".$name));
				}
				else if ($name == "Name")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_0_".$name));
				}
				else if ($name == "Street")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_0_".$name));
				}
				else if ($name == "Country")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_0_".$name));
				}
				else if ($name == "ZipCode")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_0_".$name));
				}
				else if ($name == "Location")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_0_".$name));
				}
				else if ($name == "WorkPhone")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_0_".$name));
				}
				else if ($name == "HomePhone")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_0_".$name));
				}
				else if ($name == "MobilePhone")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_0_".$name));
				}
				else if ($name == "Fax")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("URP", "Error_0_".$name));
				}
				else if ($name == "Email")
				{
					if (!ereg("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*$", $value))
						$this->addError ($this->getTemplate ("URP", "Error_0_".$name));
				}
				else if ($name == "BirthDay")
				{
					if ($value < 1 || $value > 31)
						$this->addError ($this->getTemplate ("URP", "Error_0_".$name));
				}
				else if ($name == "BirthMonth")
				{
					if ($value < 1 || $value > 12)
						$this->addError ($this->getTemplate ("URP", "Error_0_".$name));
				}
				else if ($name == "BirthYear")
				{
					if ($value < 1900 || $value > 1990)
						$this->addError ($this->getTemplate ("URP", "Error_0_".$name));
				}
				else if ($name == "UserName")
				{
					global $uid;
					
					if (strlen ($value) == 0)
					{
						$this->addError ($this->getTemplate ("URP", "Error_2_".$name));
						break;
					}
					$db = new DB ();
					$db->execute ("select ID from User where UserName = '$value' and ID != $uid");

					if ($db->getNumRows () > 0)
						$this->addError ($this->getTemplate ("URP", "Error_2_UserExists"));
				}
				else if ($name == "UserPswdA")
				{
					global $UserPswdA,
						   $UserPswdB;
					
					if ((!isset ($UserPswdA) && !isset ($UserPswdB)) || 
						(strlen ($UserPswdA) == 0 && strlen ($UserPswdB) == 0))
						return;
					if (strlen ($value) < 4 || strlen ($value) > 10)
						$this->addError ($this->getTemplate ("URP", "Error_2_UserPswd"));
					
					if (isset ($UserPswdA) && isset ($UserPswdB) && 
						$UserPswdA != $UserPswdB)
						$this->addError ($this->getTemplate ("URP", "Error_2_PswdDifference"));	
				}
			}
			default:
				;
		}
		return true;
	}	
}
?>