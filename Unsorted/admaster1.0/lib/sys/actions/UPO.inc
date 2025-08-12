<?
/*
Use templates: Model, LeftPanel
// aid - action identifier

*/
include_once "sys/template/Action.inc";

class UPO extends Action
{
	// actionVar of global variable that can containt the action value
	var $actionVar     = "Action";
	
	// names of the actions  and the names of methods 
	// that will process them
	var $action        = array ('run'    => 'run');
								
	// default action actionVar
	var $defActionName = 'run'; 	
	
	function UPO ($data)
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
		
		$pidBase  = $this->getTemplate ("UPO", "WML_UPO_PID");
		
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
	
	//  Code Type Displaying
	function printCTD ()
	{
		global $CatID,
			   $ProgramID,
			   $SiteID;
		
		global $uid;
		
		// does user have any sites?
		
		$sdb = new DB ();
		
		$sdb->execute ("select ID from UserSite where UserID = $uid");
		
		if ($sdb->getNumRows () == 0)
			return $this->getTemplate ("UPO", "WML_UPO_SCD_SLE");
		
		$ctdBase  = $this->getTemplate ("UPO", "WML_UPO_CTD");
		
		$ctdBlock = $this->getTemplate ("UPO", "WML_UPO_CTD_TDB");
		
		$this->store (array ("aid"   => 0));
		$ctdBase = $this->replace ("CategoryURL", $this->inURL (), $ctdBase);
		
		$this->store (array ("CatID" => $CatID,
							 "aid"   => 1));

		$ctdBase = $this->replace ("ProgramURL", $this->inURL (), $ctdBase);

		$this->store (array ("ProgramID" => $ProgramID,
							 "aid"   	 => 2));

		$ctdBase = $this->replace ("ProgramInfoURL", $this->inURL (), $ctdBase);		

		$this->store (array ("SiteID" => $SiteID,
							 "aid"    => 3));

		$ctdBase = $this->replace ("SiteURL", $this->inURL (), $ctdBase);	
		
		$block = '';
		
		$db = new DB ();
		$db->execute ("select ID, Name, Description from Code where ProgramID = $ProgramID order by ID");
		
		$this->store (array ("aid"   => 5));
		
		for ($i = 0; $i < $db->getNumRows (); $i++)
		{
			$id    = $db->recordSet [$i][0];
			$name = stripslashes ($db->recordSet [$i][1]);
			$desc   = stripslashes ($db->recordSet [$i][2]);
			
			$this->store (array ("CodeID" => $id));
			
			$tmpBlock = $ctdBlock;
			$tmpBlock = $this->replace ("Name",        $name,   		$tmpBlock);
			$tmpBlock = $this->replace ("Description", $desc,   		$tmpBlock);
			$tmpBlock = $this->replace ("URL", 		   $this->inURL (), $tmpBlock);
	
			$block .= $tmpBlock;
		}
		
		if (strlen ($block) == 0)
			return '';
		
		$ctdBase = $this->replace ("Hidden",   $this->inHidden (),   $ctdBase);
		
		return $this->replace ("WML_UPO_CTD_TDB",   $block,   $ctdBase);	
	}
	
	//  Getting Code Displaying
	function printGCD ()
	{
		global $CatID,
			   $ProgramID,
			   $CodeID;
		
		global $uid;
		
		$tmplBase  = $this->getTemplate ("UPO", "WML_UPO_GCD");
		
		$this->store (array ("aid"   => 0));
		$tmplBase = $this->replace ("CategoryURL", $this->inURL (), $tmplBase);
		
		$this->store (array ("CatID" => $CatID,
							 "aid"   => 1));

		$tmplBase = $this->replace ("ProgramURL", $this->inURL (), $tmplBase);

		$this->store (array ("ProgramID" => $ProgramID,
							 "aid"   	 => 2));

		$tmplBase = $this->replace ("ProgramInfoURL", $this->inURL (), $tmplBase);		

		
		$this->store (array ("aid"    => 4));

		$tmplBase = $this->replace ("CodeURL", $this->inURL (), $tmplBase);
		
		$db = new DB ();
		$db->execute ("select Code from Code where ID = $CodeID");
		
		$code = stripslashes ($db->recordSet [0][0]);
	
		// get silly js thing :-((( 
		$db->execute ("select ID, Title from UserSite where UserID = $uid order by ID");
		$siteBlock = $this->getTemplate ("UPO", "WML_UPO_SCD_SDB");
		
		$block = '';
		$refID0 = '';
		
		for ($i = 0; $i < $db->getNumRows (); $i++)
		{	
			$SiteID = stripslashes ($db->recordSet [$i][0]);
			$title = stripslashes ($db->recordSet [$i][1]);
			
			if ($i == 0)
				$refID0 = $SiteID;	
				
			$tmpBlock = $siteBlock;
			$tmpBlock = $this->replace ("SiteID",    $SiteID, $tmpBlock);
			$tmpBlock = $this->replace ("SiteInfo",  $title, $tmpBlock);
			$this->store (array ("SiteID$id" => $SiteID));
	
			$block .= $tmpBlock;
		}		
	
		$code = $this->replace ("UserID", $uid, $code);
		$code = $this->replace ("ProgramID", $ProgramID, $code);
		$code0 = $code;
		$code = $this->jsreplace ("SiteID", $refID0, $code);
		$code = $this->jsreplace ("type", "", $code);
		
		$code0 = htmlentities (preg_replace ("/\s+/", " ", $code0), ENT_QUOTES);
		//$code = htmlspecialchars (str_replace ("'", '"', $code));
		
		$this->store (array ("Code" => $code0));
	
		$tmplBase = $this->replace ("Code", $code, $tmplBase);	
		$tmplBase = $this->replace ("WML_UPO_SCD_SDB", $block, $tmplBase);
		$tmplBase = $this->replace ("Hidden", $this->inHidden (), $tmplBase);
		
		return $tmplBase;	
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
			case 3:
			{
				;/*$displayable .= $this->printSCD ();

				break;*/
			}
			case 4:
			{
				$displayable .= $this->printCTD ();
				
				break;			
			}
			case 5:
			{
				$displayable .= $this->printGCD ();
				
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
	/*// Site Choise Displaying
	function printSCD ()
	{
		global $CatID,
			   $ProgramID;
		
		global $uid;
		
		$scdBase  = $this->getTemplate ("UPO", "WML_UPO_SCD");
		
		$scdBlock = $this->getTemplate ("UPO", "WML_UPO_SCD_SDB");
		
		$this->store (array ("aid"   => 0));
		$scdBase = $this->replace ("CategoryURL", $this->inURL (), $scdBase);
		
		$this->store (array ("CatID" => $CatID,
							 "aid"   => 1));

		$scdBase = $this->replace ("ProgramURL", $this->inURL (), $scdBase);

		$this->store (array ("ProgramID" => $ProgramID,
							 "aid"   	 => 2));

		$scdBase = $this->replace ("ProgramInfoURL", $this->inURL (), $scdBase);		
		
		$block = '';
		
		$db = new DB ();
		$db->execute ("select ID, Title, URL from UserSite where UserID = $uid order by ID");
		
		$this->store (array ("aid"   => 4));
		
		for ($i = 0; $i < $db->getNumRows (); $i++)
		{
			$id    = $db->recordSet [$i][0];
			$title = stripslashes ($db->recordSet [$i][1]);
			$URL   = stripslashes ($db->recordSet [$i][2]);
			
			$this->store (array ("SiteID" => $id));
			
			$tmpBlock = $scdBlock;
			$tmpBlock = $this->replace ("Title",     $title,   		  $tmpBlock);
			$tmpBlock = $this->replace ("SiteURL",   $URL,   		  $tmpBlock);
			$tmpBlock = $this->replace ("URL", 		 $this->inURL (), $tmpBlock);
	
			$block .= $tmpBlock;
		}
		
		if (strlen ($block) == 0)
			return $this->getTemplate ("UPO", "WML_UPO_SCD_SLE");
		
		$scdBase = $this->replace ("Hidden",   $this->inHidden (),   $scdBase);
		
		return $this->replace ("WML_UPO_SCD_SDB",   $block,   $scdBase);	
	}*/
}
?>