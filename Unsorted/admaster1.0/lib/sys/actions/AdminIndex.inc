<?
/*
Use templates: Model, LeftPanel


*/
include_once "sys/template/Action.inc";

class AdminIndex extends Action
{
	// actionVar of global variable that can containt the action value
	var $actionVar     = "ActionGroup";
	
	// names of the actions  and the names of methods 
	// that will process them
	var $action        = array ('UPI'          => 'runUPI', 
								'UserPrograms' => 'userPrograms',
								'Login'        => 'login',
								'Logout'       => 'logout',
								'URP'     	   => 'runURP',
								'UMTI'         => 'runUMTI',
								'Recovery'     => 'recovery',
								'UPP'          => 'runUPP',
								'UBAI'         => 'runUBAI',
								'USO'          => 'runUSO',
								'UPO'          => 'runUPO');
								
	// default action actionVar
	var $defActionName = 'UPP'; 	
	
	function AdminIndex ()
	{
		$this->Action ();
	}
	
	function runUPP ()
	{
		include "sys/actions/UPP.inc";
	
		$uppObj = new UPP (array ("ActionGroup" => "UPP"));
		$this->replace ("MainPanel", $uppObj->display ());	
	}
	function runUSO ()
	{
		global $uid;
		
		if (!$uid)
		{
			$this->store (array ($this->actionVar => "Login"));
			$this->login ();
			return;
		}
		include "sys/actions/USO.inc";
		
		$usoObj = new USO (array ("ActionGroup" => "USO"));

		$this->replace ("MainPanel", $usoObj->display ());
	}
	function runUPO ()
	{
		global $uid;
		
		if (!$uid)
		{
			$this->store (array ($this->actionVar => "Login"));
			$this->login ();
			return;
		}
		include "sys/actions/UPO.inc";
		
		$upoObj = new UPO (array ("ActionGroup" => "UPO"));

		$this->replace ("MainPanel", $upoObj->display ());
	}
	function runUBAI ()
	{
		global $uid;
		
		if (!$uid)
		{
			$this->store (array ($this->actionVar => "Login"));
			$this->login ();
			return;
		}
		include "sys/actions/UBAI.inc";
		
		$ubaiObj = new UBAI (array ("ActionGroup" => "UBAI"));

		$this->replace ("MainPanel", $ubaiObj->display ());
	}
	function runUMTI ()
	{
		global $uid;
		
		if (!$uid)
		{
			$this->store (array ($this->actionVar => "Login"));
			$this->login ();
			return;
		}
		include "sys/actions/UMTI.inc";
		
		$umtiObj = new UMTI (array ("ActionGroup" => "UMTI"));

		$this->replace ("MainPanel", $umtiObj->display ());
	}
	function runUPI ()
	{
		global $uid;
		
		if (!$uid)
		{
			$this->store (array ($this->actionVar => "Login"));
			$this->login ();
			return;
		}
		include "sys/actions/UPI.inc";
		
		$obj = new UPI (array ("ActionGroup" => "UPI"));
		$this->replace ("MainPanel", $obj->display ());
	}
	function recovery ()
	{
		include "sys/actions/Recovery.inc";
		
		$rec = new Recovery (array ("ActionGroup" => "Recovery"));
		$this->replace ("MainPanel", $rec->display ());	
	}
	function login ()
	{
		global $uid;

		if ($uid)
		{
			$this->runUPO ();
			return;
		}
		include "sys/actions/UserLogin.inc";
		$login = new UserLogin (array ("ActionGroup" => "Login"));
		$this->replace ("MainPanel", $login->display ());		
	}
	function logout ()
	{
		$this->runUPP ();
	}
	function runURP ()
	{
		include "sys/actions/URP.inc";
		$urpObj = new URP (array ("ActionGroup" => "URP"));
		
		$this->replace ("MainPanel", $urpObj->display ());
		
	}
	function userPrograms ()
	{
		global $uid;
		
		if (!$uid)
		{
			$this->store (array ($this->actionVar => "Login"));
			$this->login ();
			return;
		}
		include "sys/actions/UserPrograms.inc";
		$programs = new UserPrograms (array ("ActionGroup" => "UserPrograms"));
		$this->replace ("MainPanel", $programs->display ());
	}
	function prepare ()
	{
		global $uid;
		
		$this->add ($this->getTemplate ("Base", "Model"));
		if ($uid)
			$this->replace ("LeftPanel", $this->getTemplate ("AdminBase", "LeftPanelLogged"));
		else
			$this->replace ("LeftPanel", $this->getTemplate ("Base", "LeftPanelLogouted"));
	}
}
?>