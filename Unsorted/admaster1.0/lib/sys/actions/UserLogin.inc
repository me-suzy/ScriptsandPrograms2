<?
/*
Use templates: Model, LeftPanel


*/
include_once "sys/template/Action.inc";
class UserLogin extends Action
{
	// actionVar of global variable that can containt the action value
	var $actionVar     = "Action";
	
	// names of the actions  and the names of methods 
	// that will process them
	var $action        = array ('Login'    => 'login');
								
	// default action actionVar
	var $defActionName = 'Login'; 	
	
	function UserLogin ($data)
	{
		$this->Action ($data);
	}
	function login ()
	{
		global $UserName,
			   $UserPswd,
			   $logOn;
		
		$mainTmpl    = $this->getTemplate ("Login", "Login");				
		$errorTmpl   = $this->getTemplate ("Login", "LoginError");

		if (!isset ($UserName))
			$errorTmpl = "";
		
		$mainTmpl = $this->replace ("Error", $errorTmpl, $mainTmpl);
		
		$mainTmpl = $this->replace ("Hidden", $this->inHidden (), $mainTmpl);
		
		$this->replace ("Main", $mainTmpl);		
	}

	function prepare ()
	{
		$this->add ($this->getTemplate ("Base", "MainPanel"));
		$this->replace ("TopControls", "&nbsp;");	
	}
	
}
?>