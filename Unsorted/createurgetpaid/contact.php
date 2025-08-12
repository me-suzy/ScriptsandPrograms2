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

	include "lib/.htconfig.php";

	$tml->RegisterVar("TITLE", _LANG_CONTACT_TITLE);

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(!$_POST["name"] || !$_POST["email"] || !$_POST["text"])
			exit($error->Report(_LANG_CONTACT_TITLE, _LANG_ERROR_FIELDEMPTY));
		
		$tml->RegisterVar("NAME",	$_POST["name"]);
		$tml->RegisterVar("EMAIL",	$_POST["email"]);
		$tml->RegisterVar("TYPE",	$_POST["type"]);
		$tml->RegisterVar("TEXT",	nl2br($_POST["text"]));
		
		$tml->loadFromFile("emails/contact");
		$tml->Parse(1);
		
		$main->sendMail(_EMAIL_CONTACT, _LANG_CONTACT_FORM, $tml->GetParsedContent(), $_POST["email"]);
		
		$main->printText(_LANG_CONTACT_MAILSENT);
	}
	else
	{
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		$tml->loadFromFile("pages/contact");
		$tml->Parse();
		
		$tml->loadFromFile("pages/footer");
		$tml->Parse();
		
		$tml->Output();
	}

?>