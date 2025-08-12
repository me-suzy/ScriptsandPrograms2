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
	
	$tml->RegisterVar("TITLE", _LANG_NEWS_TITLE);
	
	if($_GET["action"] == "open")
	{
		if(!isset($_GET["item"]) || $_GET["item"] == "")
			exit($error->Report(_LANG_NEWS_TITLE, _LANG_ERROR_ERROROCCURED));
		
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		$db->Query("SELECT id FROM replies WHERE nid='" . $_GET["item"] . "'");
		
		$tml->registerVar("Num_reacts",	$db->NumRows());
		
		$db->Query("SELECT id, dateStamp, title, text FROM news WHERE dateStamp='" . $_GET["item"] . "'");
		
		$i		= 1;
		
		while($data = $db->NextRow())
		{
			$data["date"]		= date(_SITE_DATESTAMP, $data["dateStamp"]);
			$data["text"]		= nl2br($data["text"]);
			$data["dateStamp"]	= $data["dateStamp"];
			
			$tml->registerVar("NewsID", $data["dateStamp"]);
			
			$tml->registerLoop("News_Item", $i, $data);
			
			$i++;
		}
		
		$db->Query("SELECT id, dateStamp, author, text, remote_addr FROM replies WHERE nid='" . $_GET["item"] . "' ORDER BY dateStamp ASC");
		
		$i		= 1;
		
		while($data = $db->NextRow())
		{
			$data["date"]		= date(_SITE_DATESTAMP, $data["dateStamp"]);
			$data["author"]		= htmlentities($data["author"]);
			$data["text"]		= nl2br(htmlentities($data["text"]));
			
			$tml->registerLoop("News_Reacts", $i, $data);
			
			$i++;
		}
		
		$tml->RegisterVar("OPERATOR", $user->IsOperator() ? 1 : 0);
		
		$tml->loadFromFile("pages/news_item");
		$tml->Parse();
		
		$tml->loadFromFile("pages/footer");
		$tml->Parse();
		
		$tml->Output();
	}
	elseif($_GET["action"] == "reply")
	{
		if(!isset($_GET["item"]) || $_GET["item"] == "")
			exit($error->Report(_LANG_NEWS_TITLE, _LANG_ERROR_ERROROCCURED));
		
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			if(!$_POST["author"] || !$_POST["text"])
				exit($error->Report(_LANG_NEWS_TITLE, _LANG_ERROR_FIELDEMPTY));
			
			$db->Query("INSERT INTO replies (nid, dateStamp, author, text, remote_addr) VALUES ('" . $_GET["item"] . "', '" . time() . "', '" . $_POST["author"] . "', '" . $_POST["text"] . "', '" . $_SERVER["REMOTE_ADDR"] . "');");
			
			$main->printText(_LANG_NEWS_POSTED);
		}
		else
		{
			$tml->loadFromFile("pages/header");
			$tml->Parse();
			
			$title	= $db->Fetch("SELECT title FROM news WHERE dateStamp='" . $_GET["item"] . "'");
			
			$tml->registerVar("Item",	$_GET["item"]);
			$tml->registerVar("Title",	$title);
			
			$tml->loadFromFile("pages/news_reply");
			$tml->Parse();
			
			$tml->loadFromFile("pages/footer");
			$tml->Parse();
			
			$tml->Output();
		}
	}
	else
	{
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		$db->Query("SELECT id, dateStamp, title FROM news ORDER BY dateStamp DESC");
		
		$i	= 1;
		
		while($data = $db->NextRow())
		{
			$db->Query("SELECT id FROM replies WHERE nid='" . $data["dateStamp"] . "'", 2);
			
			$data["date"]		= date(_SITE_DATESTAMP, $data["dateStamp"]);
			$data["replies"]	= $db->NumRows(2);
			
			$tml->RegisterLoop("News", $i, $data);
			
			$i++;
		}
		
		$tml->loadFromFile("pages/news");
		$tml->Parse();
		
		$tml->loadFromFile("pages/footer");
		$tml->Parse();
		
		$tml->Output();
	}
	
?>