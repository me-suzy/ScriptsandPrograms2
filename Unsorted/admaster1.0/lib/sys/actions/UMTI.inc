<?
/*
Use templates: Model, LeftPanel


*/
include_once "sys/template/Action.inc";

class UMTI extends Action
{
	// actionVar of global variable that can containt the action value
	var $actionVar     = "Action";
	
	// names of the actions  and the names of methods 
	// that will process them
	var $action        = array ('run'    => 'run');
								
	// default action actionVar
	var $defActionName = 'run'; 	

	function UMTI ($data)
	{
		$this->Action ($data);
		
		global $aid;
		
		if (!isset ($aid))
			$aid = 0;
	}

	function run ()
	{
		// action id
	 	global $uid;
	 	
		$displayable = '';
		
		$db = new DB ();
		
		$db->execute ("select LastTransferDate, LastTransfer, CurrentAmount from MoneyTransfer where UserID = $uid");
		
		$lastTransferDate = stripslashes ($db->recordSet [0][0]);
		$lastTransfer     = stripslashes ($db->recordSet [0][1]);
		$currentAmount    = stripslashes ($db->recordSet [0][2]);
		
		if ($lastTransferDate == "0000-00-00")
			$displayable = $this->getTemplate ("UMTI", "WML_UMTI_UTE");
		else
			$displayable = $this->getTemplate ("UMTI", "WML_UMTI");
		
		$displayable = $this->replace ("LastTransferDate",   $lastTransferDate, $displayable);
		$displayable = $this->replace ("LastTransferAmount", $lastTransfer,     $displayable);
		$displayable = $this->replace ("CurrentAmount",      $currentAmount,    $displayable);
		
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
}
?>