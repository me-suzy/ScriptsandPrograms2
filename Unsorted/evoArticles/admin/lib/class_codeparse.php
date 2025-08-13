<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

class Parser
{
	var $allowHTML = 1;
	var $allowBBCode = 1;
	var $doAutoBR = 1;

	function Parser()
	{
		//cons
	}
	
	function replace_br($text)
	{
		$search = array ("/<br>/i","'<br />'i");
		$replace = array ("\n","\n");
		$text = preg_replace($search,$replace,$text);
		return $text;
	}

	function strip_br($text)
	{
		$search = array ("/<br>/i","'<br />'i");
		$replace = array ("","");
		$text = preg_replace($search,$replace,$text);
		return $text;
	}

	function parser_php($msg)
	{
		
		global $settings;		
		
		//have to clean wysiwyg stuff
		$msg = str_replace("<br />","\r",$msg);
		$msg = str_replace("&lt;","<",$msg);
		
				
		$search = array("?>","<?");
		$replace = "";
		$msg = str_replace($search,$replace,$msg);
		$msg = "<?".trim($msg)."?>";	
		$msg = stripslashes($msg);
		
		
		ob_start();
		highlight_string($msg);
		$code = ob_get_contents();
		ob_end_clean();
		
		$code = str_replace("&amp;lt;","&lt;",$code);
		$code = $this->strip_br($code);

		return "<div class=\"htmlbox\">".($settings['usewysiwyg'] == 1 ? nl2br($code) : $code )."</div>";
	}

	function parse_code($msg)
	{
		global $settings;
		preg_match_all("/(\[)(code)(])(\n)*(.*)(\[\/code\])/siU",$msg,$match);
		
		for ($i=0; $i< count($match[0]); $i++)
		{
			$msg = str_replace($match[0][$i],"<div class=\"htmlbox\">".($settings['usewysiwyg'] == 1 ? $match[0][$i] : htmlspecialchars($match[0][$i]) )."</div>",$msg);
		}

		$msg = str_replace("[code]","",$msg);
		$msg = str_replace("[/code]","",$msg);

		return $msg;
	}

	function convert_tag($text)
	{
		 $text = str_replace("&lt;","&amp;lt;",$text);
		 $text = str_replace("&gt;","&amp;gt;",$text);

		 $text = str_replace("<","&lt;",$text);
		 $text = str_replace(">","&gt;",$text);
		

		 return $text;
	}
	
	function unconvert($text)
	{
		$text = str_replace("&amp;lt;","<",$text);
		$text = str_replace("&amp;gt;",">",$text);

		return $text;
	}

	function highlight($word='',$what='')
	{
		if ($word != "")
		{
			if (preg_match("/\+/",$word))
			{
				$split = explode("+",$word);
				foreach ($split as $splitted)
				{
					$what = preg_replace("#".$splitted."#i","<span class=\"highlight\">$splitted</span>",$what);
					$return = $what;
				}
			}
			else
			{
				$return = preg_replace("#".$word."#i","<span class=\"highlight\">$word</span>",$what);
			}

			return $return;
		}
	}

	function nohtml($text)
	{
		$text = preg_replace("'<[\/\!]*?[^<>]*?>'si","",$text);
		return $text;
	}
	
	function remove_tag($text,$tag='')
	{
		if ($tag != "")
		{
			$text = preg_replace("'<".$tag."[\/\!]*?[^<>]*?>'si","",$text);	
		}
		return $text;
	}

	function do_parse($text)
	{
		if ( trim($text) != "")
		{
			  if ($this->allowHTML == 0)
			  {
				  $text = $this->convert_tag($text);
			  }

			  $text = $this->parse_code($text);
			  $text = $this->allowBBCode == 1 ? $this->exec_parse($text):$text;
			  $text = $this->doAutoBR == 1 ? nl2br($text):$text;
		}

		return $text;
	}

	function exec_parse($text)
	{
		 $searcharray = array ( 
								"/(\[)(list)(=)(['\"]?)([^\"']*)(\\4])(.*)(\[\/list)(((=)(\\4)([^\"']*)(\\4]))|(\]))/siU", 
								"/(\[)(list)(])(.*)(\[\/list\])/siU", 
								"/(\[\*\])/siU", 
								"/(\[)(url)(=)(['\"]?)(www\.)([^\"']*)(\\4)(.*)(\[\/url\])/siU", 
								"/(\[)(url)(=)(['\"]?)([^\"']*)(\\4])(.*)(\[\/url\])/siU", 
 							    "/(\[)(url)(])(www\.)([^\"]*)(\[\/url\])/siU", 
 								"/(\[)(url)(])([^\"]*)(\[\/url\])/siU", 
								"/(\[)(b)(])(\n)*(.*)(\[\/b\])/siU", 
								"/(\[)(u)(])(\n)*(.*)(\[\/u\])/siU", 
								"/(\[)(i)(])(\n)*(.*)(\[\/i\])/siU", 
								"/(\[)(pre)(])(\n)*(.*)(\[\/pre\])/siU",
								"/javascript:/i",
								"/vbscript:/i",
								"/about:/i",
								"/(\[)(email)(=)(['\"]?)([^\"']*)(\\4])(.*)(\[\/email\])/siU", 
								"/(\[)(email)(])([^\"]*)(\[\/email\])/siU",
								"/(\[)(img)(])(\n)*([^\"]*)(\[\/img\])/siU",
								"/(\[)(img)(.*)(align=)(['\"]?)([^\"']*)(\\5])(.*)(\[\/img\])/siU"
							);

			$replacearray = array( 
								
									"<ol type=\"\\5\">\\7</ol>", 
									"<ul>\\4</ul>", 
									"<li>", 
									"<a href=\"http://www.\\6\" target=\"_blank\">\\8</a>", 
									"<a href=\"\\5\" target=\"_blank\">\\7</a>", 
									"<a href=\"http://www.\\5\" target=\"_blank\">\\5</a>", 
									"<a href=\"\\4\" target=\"_blank\">\\4</a>", 
									"<b>\\5</b>", 
									"<u>\\5</u>", 
									"<i>\\5</i>", 
									"<pre>code:\\5</pre>",
									
									"java scr1pt",
									"vb scr1pt",
									"ab0ut :",

									"<a href=\"mailto:\\5\" target=\"_blank\">\\7</a>", 
								    "<a href=\"mailto:\\4\" target=\"_blank\">\\4</a>",

								    "<img src=\"\\5\" border=\"0\" alt=\"\" />",
									"<img src=\"\\8\" align=\"\\6\" alt=\"\" />"
			); 

			$text = preg_replace($searcharray, $replacearray, $text);
			$text = str_replace("\\'", "'", $text); 
		
		 	$text = preg_replace("/(\[(php)\])([^\\4\\1]*)(\[\/\\2\])/eiU","\$this->parser_php('\\3')",$text);

			$text = str_replace("[]","",$text);
			$text = str_replace("[pagebreak","<<pagebreak",$text);
			$text = str_replace("<<pagebreak","[pagebreak",$text);
			
			$text = str_replace("&lt;?","",$text);
			$text = str_replace("?&gt;","",$text);
			
			

		return $text;
	}

}
?>