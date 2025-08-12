<?
/*
Use templates: Model, LeftPanel
// aid - action identifier

*/
include_once "sys/template/Action.inc";

class UPP extends Action
{
	// actionVar of global variable that can containt the action value
	var $actionVar     = "Action";
	
	// names of the actions  and the names of methods 
	// that will process them
	var $action        = array ('run'    => 'run');
								
	// default action actionVar
	var $defActionName = 'run'; 	
	
	function UPP ($data)
	{
		$this->Action ($data);
		
		global $aid;
		
		if (!isset ($aid))
			$aid = 0;
	}
	
	// Category Choise Displaying
	function printCCD ()
	{
		$ccdBase  = $this->getTemplate ("UPO", "WML_UPO_CCD");
		
		$ccdBlock = $this->getTemplate ("UPO", "WML_UPO_CCD_CDB");
		
		$block = '';
		
		$db = new DB ();
		$db->execute ("select ID, Name, Description from Category order by ID");
		
		$this->store (array ("aid" => 1));
		
		for ($i = 0; $i < $db->getNumRows (); $i++)
		{
			$id    = $db->recordSet [$i][0];
			$name  = stripslashes ($db->recordSet [$i][1]);
			$desc  = stripslashes ($db->recordSet [$i][2]);
			
			$this->store (array ("CatID" => $id));
			
			$desc = $this->replace ("URL", $this->inURL (), $desc);
			
			$tmpBlock = $ccdBlock;
			$tmpBlock = $this->replace ("Name",        $name, $tmpBlock);
			$tmpBlock = $this->replace ("Description", $desc, $tmpBlock);
	
			$block .= $tmpBlock;
		}
		
		if (strlen ($block) == 0)
			return '';
		
		$ccdBase = $this->replace ("Hidden",   $this->inHidden (),   $ccdBase);
		
		return $this->replace ("WML_UPO_CCD_CDB",   $block,   $ccdBase);	
	}
	// Program Choise Displaying
	function printPCD ()
	{
		global $CatID;
		
		$pcdBase  = $this->getTemplate ("UPO", "WML_UPO_PCD");
		
		$pcdBlock = $this->getTemplate ("UPO", "WML_UPO_PCD_PDB");
		
		$block = '';
		
		$db = new DB ();
		$db->execute ("select ID, Name, ShortInfo from Program where CatID = $CatID order by ID");
		
		$this->store (array ("aid"   => 0));
		
		$pcdBase = $this->replace ("CategoryURL", $this->inURL (), $pcdBase);
		
		$this->store (array ("aid"   => 2));
		$this->store (array ("CatID" => $CatID));
		
		for ($i = 0; $i < $db->getNumRows (); $i++)
		{
			$id    = $db->recordSet [$i][0];
			$name  = stripslashes ($db->recordSet [$i][1]);
			$info  = stripslashes ($db->recordSet [$i][2]);
			
			$this->store (array ("ProgramID" => $id));
			
			$desc = $this->replace ("URL", $this->inURL (), $desc);
			
			$tmpBlock = $pcdBlock;
			$tmpBlock = $this->replace ("Name",        $name,   		$tmpBlock);
			$tmpBlock = $this->replace ("ShortInfo",   $info,   		$tmpBlock);
			$tmpBlock = $this->replace ("URL", 		   $this->inURL (), $tmpBlock);
	
			$block .= $tmpBlock;
		}
		
		if (strlen ($block) == 0)
			return '';
		
		$pcdBase = $this->replace ("Hidden",   $this->inHidden (),   $pcdBase);
		
		return $this->replace ("WML_UPO_PCD_PDB",   $block,   $pcdBase);	
	}

	// Program Info Displaying
	function printPID ()
	{
		global $CatID,
			   $ProgramID;
		
		$pidBase  = $this->getTemplate ("UPP", "WML_UPP_PID");
		
		$db = new DB ();
		$db->execute ("select Name, ShortInfo, Info from Program where ID = $ProgramID");
		
		$this->store (array ("aid"   => 0));
		
		$name  		= stripslashes ($db->recordSet [0][0]);
		$shortInfo  = stripslashes ($db->recordSet [0][1]);
		$info  		= stripslashes ($db->recordSet [0][2]);
		
		$pidBase = $this->replace ("Name", 		$name, 	    $pidBase);
		$pidBase = $this->replace ("ShortInfo", $shortInfo, $pidBase);
		$pidBase = $this->replace ("Info", 		$info, 	    $pidBase);
		
		
		$pidBase = $this->replace ("CategoryURL", $this->inURL (), $pidBase);
		
		$this->store (array ("CatID" => $CatID,
							 "aid"   => 1));

		$pidBase = $this->replace ("ProgramURL", $this->inURL (), $pidBase);
		
		$this->store (array ("ProgramID" => $ProgramID));
		$this->store (array ("aid"   => 3));
		
		$pidBase = $this->replace ("URL", $this->inURL (), $pidBase);
		
		$pidBase = $this->replace ("Hidden",   $this->inHidden (),   $pidBase);
		
		return $pidBase;
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
				$displayable .= $this->printCCD ();
			
				break;
			}
			case 1:
			{
				$displayable .= $this->printPCD ();
				
				break;
			}
			case 2:
			{
				$displayable .= $this->printPID ();
				
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
	}

}
?>