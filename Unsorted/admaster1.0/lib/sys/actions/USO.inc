<?
/*
Use templates: Model, LeftPanel
// aid - action identifier

*/
include_once "sys/template/Action.inc";

class USO extends Action
{
	// actionVar of global variable that can containt the action value
	var $actionVar     = "Action";
	
	// names of the actions  and the names of methods 
	// that will process them
	var $action        = array ('run'    => 'run');
								
	// default action actionVar
	var $defActionName = 'run'; 	
	
	var $form = array (array ("Title",
			 				  "URL",
						      "Description"));
	
	function USO ($data)
	{
		$this->Action ($data);
		
		global $aid;
		
		if (!isset ($aid))
			$aid = 3;
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
	
	// Site Delete Displaying
	function printSDD ()
	{
		global $uid;
		
		$sddBase  = $this->getTemplate ("USO", "WML_USO_SDD");
		
		$db = new DB ();
		$db->execute ("select ID from UserSite where UserID = $uid order by ID");

		for ($i = 0; $i < $db->getNumRows (); $i++)
		{
			$id    = $db->recordSet [$i][0];
			
			global ${"DeleteID".($i + 1)};
			
			if (isset (${"DeleteID".($i + 1)}))
			{
				$ldb = new DB ();
				$ldb->execute ("delete from UserSite where ID = $id");
			}
		}

		return $sddBase;
	}
	// Site Addition Result
	function printSAR ()
	{
		global $uid,
			   $Title,
			   $URL,
			   $Description;
		
		$db = new DB ();
		$db->execute ("select ID from UserSite where UserID = $uid and URL = '".addslashes ($URL)."'");
		
		if ($db->getNumRows () > 0)
			return '';
		
		$query = "insert into UserSite (Title, URL, Description, RefID, UserID) 
					   values      ('".addslashes ($Title)."',
					   				'".addslashes ($URL)."',
					   				'".addslashes ($Description)."',
					   				'".md5 ($uid.$URL)."', 
					   				$uid )";
		$db->execute ($query);
		
		return $this->getTemplate ("USO", "WML_USO_SAR");
	}
	
	// Site Updating Itself
	function printSUI ()
	{
		global $SiteID,
			   $uid;	
	
		global $Title,
			   $URL,
			   $Description;
		
		$db = new DB ();
		
		$query = "update UserSite 
				  set    Title = '".addslashes ($Title)."', 
						 URL   = '".addslashes ($URL)."',
						 Description = '".addslashes ($Description)."',
						 RefID       = '".md5 ($uid.$URL)."' 
				  where  ID = $SiteID and UserID = $uid";
		
		$db->execute ($query);
		
		return $this->getTemplate ("USO", "WML_USO_SUI");
	}
	// Site Addition Displayaing
	function printSAD ()
	{
		$sadBase  = $this->getTemplate ("USO", "WML_USO_SAD");
		
		global $uid;
		
		global $Title,
			   $URL,
			   $Description;

		if (!isset ($Title))
			$Title 		 = '';
		if (!isset ($URL))
			$URL   		 = '';
		if (!isset ($Description))
			$Description = '';
	
		$sadBase = $this->replace ("Title",       $Title,       $sadBase);
		$sadBase = $this->replace ("URL",         $URL,         $sadBase);
		$sadBase = $this->replace ("Description", $Description, $sadBase);
	
		$this->store (array ("aid" => 0));
		$sadBase = $this->replace ("Hidden",   $this->inHidden (),   $sadBase);
		
		return $sadBase;
	}
	// Site Updating Displayaing
	function printSUD ()
	{
		$sudBase  = $this->getTemplate ("USO", "WML_USO_SUD");
		
		global $SiteID,
			   $uid;
		
		$db = new DB ();
		$db->execute ("select Title, URL, Description 
					   from   UserSite 
					   where  ID = $SiteID and UserID = $uid");
		
		if ($db->getNumRows () == 0)
			return '';
		
		global $Title,
			   $URL,
			   $Description;
		
		if (!isset ($Title))
			$Title 		 = stripslashes ($db->recordSet [0][0]);
		if (!isset ($URL))
			$URL   		 = stripslashes ($db->recordSet [0][1]);
		if (!isset ($Description))
			$Description = stripslashes ($db->recordSet [0][2]);
	
		$sudBase = $this->replace ("Title",       $Title,       $sudBase);
		$sudBase = $this->replace ("URL",         $URL,         $sudBase);
		$sudBase = $this->replace ("Description", $Description, $sudBase);
	
		$this->store (array ("SiteID" => $SiteID, "aid" => 1));
		$sudBase = $this->replace ("Hidden",   $this->inHidden (),   $sudBase);
		
		return $sudBase;
	}
	// Site Delete Conformation
	function printSDC ()
	{
		global $uid;
		
		$sdcBase  = $this->getTemplate ("USO", "WML_USO_SDC");
		$sdcBlock = $this->getTemplate ("USO", "WML_USO_SDC_DCB");
		
		$block = '';
		
		$db = new DB ();
		$db->execute ("select ID, Title from UserSite where UserID = $uid order by ID");

		for ($i = 0; $i < $db->getNumRows (); $i++)
		{
			$id    = $db->recordSet [$i][0];
			$title = stripslashes ($db->recordSet [$i][1]);
			
			global ${"DeleteID".($i + 1)};
			
			if (isset (${"DeleteID".($i + 1)}))
			{
				$tmpBlock = $sdcBlock;
				$tmpBlock = $this->replace ("Number",      ($i + 1), $tmpBlock);
				$tmpBlock = $this->replace ("Title",       $title,   $tmpBlock);
	
				$block .= $tmpBlock;
				
				$this->store (array ("DeleteID".($i + 1) => "on"));
			}
		}
		
		if (strlen ($block) == 0)
			return '';
		
		$this->store (array ("aid" => 2));
		
		$sdcBase = $this->replace ("Hidden",   $this->inHidden (),   $sdcBase);
		
		return $this->replace ("WML_USO_SDC_DCB",   $block,   $sdcBase);	
	}
	// Site Information Displaying
	function printSID ()
	{
		global $uid;
		
		$sidBase  = $this->getTemplate ("USO", "WML_USO_SID");
		$sidBlock = $this->getTemplate ("USO", "WML_USO_SID_SIB");
		
		$block = '';
		
		$db = new DB ();
		$db->execute ("select ID, Title, Description from UserSite where UserID = $uid order by ID");

		if ($db->getNumRows () == 0)
			return $this->getTemplate ("USO", "WML_USO_SID_ESL");
		
		for ($i = 0; $i < $db->getNumRows (); $i++)
		{
			$id    = $db->recordSet [$i][0];
			$title = stripslashes ($db->recordSet [$i][1]);
			$desc  = stripslashes ($db->recordSet [$i][2]);
			
			$tmpBlock = $sidBlock;
			$tmpBlock = $this->replace ("Number",      ($i + 1), $tmpBlock);
			$tmpBlock = $this->replace ("Title",       $title,    $tmpBlock);
			$tmpBlock = $this->replace ("Description", $desc,     $tmpBlock);
		
			$this->store (array ("SiteID" => $id));
			
			$this->store (array ("aid" => 1));
			$editURL = $this->inURL ();
			
			$this->store (array ("aid" => 2));
			$deleteURL = $this->inURL ();
			
			$tmpBlock = $this->replace ("editURL",   $editURL,   $tmpBlock);
			$tmpBlock = $this->replace ("deleteURL", $deleteURL, $tmpBlock);
			
			$block .= $tmpBlock;
		}
		
		$this->store (array ("aid" => 4));
		
		$sidBase = $this->replace ("Hidden",   $this->inHidden (),   $sidBase);
		
		return $this->replace ("WML_USO_SID_SIB",   $block,   $sidBase);
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
				global $WML_USO_SAD;

				if (!isset ($WML_USO_SAD))
					$displayable .= $this->printSAD ();
				else if (!$this->validateForm (0))
					$displayable .= $this->displayError ().$this->printSAD ();
				else
					$displayable .= $this->printSAR ().$this->printSID ();
			
				break;
			}
			case 1:
			{
				global $WML_USO_SUD;

				if (!isset ($WML_USO_SUD))
					$displayable .= $this->printSUD ();
				else if (!$this->validateForm (0))
					$displayable .= $this->displayError ().$this->printSUD ();
				else
				{
					$displayable .= $this->printSUI ();
					$displayable .= $this->printSID ();
				}
				break;
			}
			case 2:
			{
				$displayable .= $this->printSDD ();
				$displayable .= $this->printSID ();
				
				break;
			}
			case 3:
			{
				$displayable .= $this->printSID ();

				break;
			}
			case 4:
			{
				$sdc = $this->printSDC ();
				
				if (strlen ($sdc) == 0)
					$displayable .= $this->printSID ();
				else
					$displayable .= $this->printSDC ();
				
				break;			
			}
			case 5:
			{
				$displayable .= $this->printSUI ();
				$displayable .= $this->printSID ();
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
				if ($name == "Title")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("USO", "WML_USO_SAD_TNC"));
				}
				else if ($name == "URL")
				{
					if (!$this->isURL ($value))
						$this->addError ($this->getTemplate ("USO", "WML_USO_SAD_UNC"));
					
					global $WML_USO_SUD,
						   $SiteID,
						   $uid;
					
					if (isset ($WML_USO_SUD))
					{
						$db = new DB ();
						$db->execute ("select ID from UserSite where UserID = $uid and ID != $SiteID and URL = '$value'");
						
						if ($db->getNumRows () > 0)
							$this->addError ($this->getTemplate ("USO", "WML_USO_SUD_SUE"));
					
					}
				}
				else if ($name == "Description")
				{
					if (strlen ($value) == 0)
						$this->addError ($this->getTemplate ("USO", "WML_USO_SAD_DNC"));
				}
			}
			default:
				;
		}
		return true;
	}	
	function isURL ($url) 
	{ 
		if ( !( $parts = parse_url( $url ) ) ) 
			return false; 
		else 
		{ 
			if ( $parts[scheme] != "http" && $parts[scheme] != "https" && 
				 $parts[scheme] != "ftp" && $parts[scheme] != "gopher" ) 
				return false; 
			elseif ( !eregi( "^[0-9a-z]([-.]?[0-9a-z])*\.[a-z]{2,3}$", $parts[host], $regs ) ) 
				return false; 
			elseif ( !eregi( "^([0-9a-z-]|[\_])*$", $parts[user], $regs ) ) 
				return false; 
			elseif ( !eregi( "^([0-9a-z-]|[\_])*$", $parts[pass], $regs ) ) 
				return false; 
			elseif ( !eregi( "^[0-9a-z/_\.@~]*$", $parts[path], $regs ) ) 
				return false; 
			else if ( !eregi( "^[0-9a-z?&=#\,]*$", $parts[query], $regs ) ) 
				return false; 
		} 

		return true; 
	}
}
?>