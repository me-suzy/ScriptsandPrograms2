<?

	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	// This script is copyrighted to CreateYourGetPaid©       \\
	// Duplication, selling, or transferring of this script   \\
	// is a violation of the copyright and purchase agreement.\\
	// Alteration of this script in any way voids any         \\
	// responsibility CreateYourGetPaid© has towards the      \\
	// functioning of the script. Altering the script in an   \\
	// attempt to unlock other functions of the program that  \\
	// have not been purchased is a violation of the          \\
	// purchase agreement and forbidden by CreateYourGetPaid© \\
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	
	$GLOBALS["login"]	= "yes";
	
	include "lib/.htconfig.php";
	
	$tml->RegisterVar("TITLE", _LANG_MEMBERS_TITLE);
	
	if($user->IsLoggedIn())
	{
		if($_GET["action"] == "logout")
		{
			$user->Logoff();
			
			header("Location: " . _SITE_URL . "/index.php?sid=" . $session->ID);
		}
		else
		{
			$tml->loadFromFile("pages/header");
			$tml->Parse();
			
			$tml->RegisterVar("FNAME", $user->Get("fname"));
			$tml->RegisterVar("SNAME", $user->Get("sname"));
			$tml->RegisterVar("EMAIL", $user->Get("email"));
			
			$tml->loadFromFile("pages/members2");
			$tml->Parse();
			
			$tml->loadFromFile("pages/footer");
			$tml->Parse();
			
			$tml->Output();
		}
	}
	elseif($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if($_POST["action"] == "login")
		{
			if(!$_POST["email"] || !$_POST["password"])
				exit($error->Report(_LANG_MEMBERS_TITLE, _LANG_ERROR_FIELDEMPTY));
			elseif(!$user->IsEmail($_POST["email"]))
				exit($error->Report(_LANG_MEMBERS_TITLE, _LANG_MEMBERS_BADUSERNAME));
			elseif(!$user->IsPassword($_POST["email"], $_POST["password"]))
				exit($error->Report(_LANG_MEMBERS_TITLE, _LANG_MEMBERS_BADPASSWORD));
			elseif(!$user->IsActive($_POST["email"]))
				exit($error->Report(_LANG_MEMBERS_TITLE, _LANG_MEMBERS_NOTACTIVATED));
			
			$user->Login($_POST["email"], $_POST["password"], $_POST["public"] == "on" ? 2 : 1);
			
			header("Location: " . _SITE_URL . "/members.php?sid=" . $session->ID);
		}
		elseif($_POST["action"] == "resend")
		{
			if(!$_POST["email"])
				exit($error->Report(_LANG_MEMBERS_TITLE, _LANG_ERROR_FIELDEMPTY));
			elseif(!$user->IsEmail($_POST["email"]))
				exit($error->Report(_LANG_MEMBERS_TITLE, _LANG_MEMBERS_BADUSERNAME));
			
			$user->ResendPassword($_POST["email"]);
			
			$main->printText(_LANG_MEMBERS_PASSWORDRESENT);
		}
		elseif($_POST["action"] == "cancel")
		{
			if(!$_POST["email"] || !$_POST["password"])
				exit($error->Report(_LANG_MEMBERS_TITLE, _LANG_ERROR_FIELDEMPTY));
			elseif(!$user->IsEmail($_POST["email"]))
				exit($error->Report(_LANG_MEMBERS_TITLE, _LANG_MEMBERS_BADUSERNAME));
			elseif(!$user->IsPassword($_POST["email"], $_POST["password"]))
				exit($error->Report(_LANG_MEMBERS_TITLE, _LANG_MEMBERS_BADPASSWORD));
			
			if($_POST["confirm"] == "yes")
			{
				$user->Remove($db->Fetch("SELECT id FROM users WHERE email='" . $_POST["email"] . "' AND password='" . $_POST["password"] . "'"));
				
				$main->printText(_LANG_MEMBERS_ACCOUNTDELETED);
			}
			else
			{
				$tml->loadFromFile("pages/header");
				$tml->Parse();
				
				$tml->RegisterVar("EMAIL",		$_POST["email"]);
				$tml->RegisterVar("PASSWORD",	$_POST["password"]);
				
				$tml->loadFromFile("pages/unsubscribe");
				$tml->Parse();
				
				$tml->loadFromFile("pages/footer");
				$tml->Parse();
				
				$tml->Output();
			}
		}
		else
			$error->Fatal(_LANG_MEMBERS_TITLE, _LANG_ERROR_ERROROCCURED);
	}
	else
	{
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		$tml->loadFromFile("pages/members1");
		$tml->Parse();
		
		$tml->loadFromFile("pages/footer");
		$tml->Parse();
		
		$tml->Output();
	}

?>