<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function bbencode($input)
{
	global $url_faqengine;
	$input = " " . $input;
	if (! (strpos($input, "[") && strpos($input, "]")) )
	{
		$input = substr($input, 1);
		return $input;
	}
	$input = bbencode_code($input);
	$input = bbencode_quote($input);
	$input = bbencode_list($input);
	$input = bbencode_php($input);
	$input = preg_replace("/\[b\](.*?)\[\/b\]/si", "<!-- SPCode Start --><B>\\1</B><!-- SPCode End -->", $input);
	$input = preg_replace("/\[i\](.*?)\[\/i\]/si", "<!-- SPCode Start --><I>\\1</I><!-- SPCode End -->", $input);
	$input = preg_replace("/\[s](.*?)\[\/s\]/si", "<!-- SPCode Start --><s>\\1</s><!-- SPCode End -->", $input);
	$input = preg_replace("/\[u](.*?)\[\/u\]/si", "<!-- SPCode Start --><u>\\1</u><!-- SPCode End -->", $input);
	$input = preg_replace("/\[tt\](.*?)\[\/tt\]/si", "<!-- SPCode Start --><tt>\\1</tt><!-- SPCode End -->", $input);
	$input = preg_replace("/\[sub\](.*?)\[\/sub\]/si", "<!-- SPCode Start --><sub>\\1</sub><!-- SPCode End -->", $input);
	$input = preg_replace("/\[sup\](.*?)\[\/sup\]/si", "<!-- SPCode Start --><sup>\\1</sup><!-- SPCode End -->", $input);
	$input = preg_replace("/\[center\](.*?)\[\/center\]/si", "<!-- SPCode Start --><center>\\1</center><!-- SPCode End -->", $input);
	$input = preg_replace("/\[img\](.*?)\[\/img\]/si", "<!-- SPCode Start --><IMG SRC=\"\\1\" BORDER=\"0\"><!-- SPCode End -->", $input);
	$input = preg_replace("/\[unc\](.*?)\[\/unc\]/si", "<!-- SPCode unc Start --><A HREF=\"\\1\" TARGET=\"_blank\">\\1</A><!-- SPCode unc End -->", $input);
	$input = eregi_replace("\\[font=([^\\[]*)\\]([^\\[]*)\\[/font\\]","<!-- SPCode font Start --><font face=\"\\1\">\\2</font><!-- SPCode font End -->",$input);
	$input = eregi_replace("\\[align=([^\\[]*)\\]([^\\[]*)\\[/align\\]","<!-- SPCode align Start --><p align=\"\\1\">\\2</p><!-- SPCode align End -->",$input);
	$input = preg_replace("/\[s\](.*?)\[\/s\]/si", "<!-- SPCode Start --><s>\\1</s><!-- SPCode End -->", $input);
	$patterns = array();
	$replacements = array();
	$patterns[0] = "#\[url\]([a-z]+?://){1}(.*?)\[/url\]#si";
	$replacements[0] = '<!-- SPCode u1 Start --><A HREF="\1\2" TARGET="_blank">\1\2</A><!-- SPCode u1 End -->';
	$patterns[1] = "#\[url\](.*?)\[/url\]#si";
	$replacements[1] = '<!-- SPCode u1 Start --><A HREF="http://\1" TARGET="_blank">\1</A><!-- SPCode u1 End -->';
	$patterns[2] = "#\[url=([a-z]+?://){1}(.*?)\](.*?)\[/url\]#si";
	$replacements[2] = '<!-- SPCode u2 Start --><A HREF="\1\2" TARGET="_blank">\3</A><!-- SPCode u2 End -->';
	$patterns[3] = "#\[url=(.*?)\](.*?)\[/url\]#si";
	$replacements[3] = '<!-- SPCode u2 Start --><A HREF="http://\1" TARGET="_blank">\2</A><!-- SPCode u2 End -->';
	$patterns[4] = "#\[email\](.*?)\[/email\]#si";
	$replacements[4] = '<!-- SPCode Start --><A HREF="mailto:\1">\1</A><!-- SPCode End -->';
	$patterns[5] = "#\[color=(.*?)\](.*?)\[/color\]#si";
	$replacements[5] = '<!-- SPCode color Start --><font color="\1">\2</font><!-- SPCode color End -->';
	$patterns[6] = "#\[size=(.*?)\](.*?)\[/size\]#si";
	$replacements[6] = '<!-- SPCode size Start --><font size="\1">\2</font><!-- SPCode size End -->';
	$patterns[7] = "#\[faqref faq=(.*?) cat=(.*?) prog=(.*?) target=(.*?)\](.*?)\[/faqref\]#si";
	$replacements[7] = '<!-- SPCode faqref2 Start --><A HREF="{url_faqengine}/faq.php?{lang}&display=faq&faqnr=\1&catnr=\2&prog=\3" TARGET="\4">\5</A><!-- SPCode faqref2 End -->';
	$patterns[8] = "#\[faqref faq=(.*?) cat=(.*?) prog=(.*?)\](.*?)\[/faqref\]#si";
	$replacements[8] = '<!-- SPCode faqref Start --><A HREF="{url_faqengine}/faq.php?{lang}&display=faq&faqnr=\1&catnr=\2&prog=\3&onlynewfaq={onlynewfaq}" TARGET="_self">\4</A><!-- SPCode faqref End -->';
	$patterns[9] = "#\[kbref=(.*?) prog=(.*?) target=(.*?)\](.*?)\[/kbref\]#si";
	$replacements[9] = '<!-- SPCode kbref2 Start --><A HREF="{url_faqengine}/kb.php?{lang}&mode=display&kbnr=\1&prog=\2" TARGET="\3">\4</A><!-- SPCode kbref2 End -->';
	$patterns[10] = "#\[kbref=(.*?) prog=(.*?)\](.*?)\[/kbref\]#si";
	$replacements[10] = '<!-- SPCode kbref1 Start --><A HREF="{url_faqengine}/kb.php?{lang}&mode=display&kbnr=\1&prog=\2" TARGET="_self">\3</A><!-- SPCode kbref1 End -->';
	$input = preg_replace($patterns, $replacements, $input);
	$input = substr($input, 1);
	return $input;

}

function bbdecode($input)
{
		$code_start_html = "#<!-- SPCode Code Start -->(.*?)<PRE CLASS=\"bbc_code\">#s";
		$code_end_html = "#</PRE>(.*?)</TABLE><!-- SPCode End -->#s";
		$input = preg_replace($code_start_html, "[code]", $input);
		$input = preg_replace($code_end_html, "[/code]", $input);
		$quote_start_html = "#<!-- SPCode Quote Start -->(.*?)<BLOCKQUOTE CLASS=\"bbc_quote\">#s";
		$quote_end_html = "#</BLOCKQUOTE>(.*?)</TABLE><!-- SPCode Quote End -->#s";
		$input = preg_replace($quote_start_html, "[quote]", $input);
		$input = preg_replace($quote_end_html, "[/quote]", $input);
		$input = preg_replace("#<!-- SPCode Start --><s>(.*?)</s><!-- SPCode End -->#s", "[s]\\1[/s]", $input);
		$input = preg_replace("#<!-- SPCode Start --><u>(.*?)</u><!-- SPCode End -->#s", "[u]\\1[/u]", $input);
		$input = preg_replace("#<!-- SPCode Start --><tt>(.*?)</tt><!-- SPCode End -->#s", "[tt]\\1[/tt]", $input);
		$input = preg_replace("#<!-- SPCode Start --><sub>(.*?)</sub><!-- SPCode End -->#s", "[sub]\\1[/sub]", $input);
		$input = preg_replace("#<!-- SPCode Start --><sup>(.*?)</sup><!-- SPCode End -->#s", "[sup]\\1[/sup]", $input);
		$input = preg_replace("#<!-- SPCode Start --><center>(.*?)</center><!-- SPCode End -->#s", "[center]\\1[/center]", $input);
		$input = preg_replace("#<!-- SPCode Start --><B>(.*?)</B><!-- SPCode End -->#s", "[b]\\1[/b]", $input);
		$input = preg_replace("#<!-- SPCode Start --><I>(.*?)</I><!-- SPCode End -->#s", "[i]\\1[/i]", $input);
		$input = preg_replace("#<!-- SPCode kbref1 Start --><A HREF=\"(.*?)kbnr=(.*?)\" TARGET=\"_self\">(.*?)</A><!-- SPCode kbref1 End -->#s", "[kbref=\\2]\\3[/kbref]", $input);
		$input = preg_replace("#<!-- SPCode kbref2 Start --><A HREF=\"(.*?)kbnr=(.*?)\" TARGET=\"(.*?)\">(.*?)</A><!-- SPCode kbref2 End -->#s", "[kbref=\\2 target=\\3]\\4[/kbref]", $input);
		$input = preg_replace("#<!-- SPCode faqref Start --><A HREF=\"(.*?)nr=(.*?)&catnr=(.*?)&prog=(.*?)&(.*?)\" TARGET=\"_self\">(.*?)</A><!-- SPCode faqref End -->#s", "[faqref faq=\\2 cat=\\3 prog=\\4]\\6[/faqref]", $input);
		$input = preg_replace("#<!-- SPCode faqref2 Start --><A HREF=\"(.*?)nr=(.*?)&catnr=(.*?)&prog=(.*?)\" TARGET=\"(.*?)\">(.*?)</A><!-- SPCode faqref2 End -->#s", "[faqref faq=\\2 cat=\\3 prog=\\4 target=\\5]\\6[/faqref]", $input);
		$input = preg_replace("#<!-- SPCode u2 Start --><A HREF=\"([a-z]+?://)(.*?)\" TARGET=\"_blank\">(.*?)</A><!-- SPCode u2 End -->#s", "[url=\\1\\2]\\3[/url]", $input);
		$input = preg_replace("#<!-- SPCode u1 Start --><A HREF=\"([a-z]+?://)(.*?)\" TARGET=\"_blank\">(.*?)</A><!-- SPCode u1 End -->#s", "[url]\\3[/url]", $input);
		$input = preg_replace("#<!-- SPCode unc Start --><A HREF=\"(.*?)\" TARGET=\"_blank\">(.*?)</A><!-- SPCode unc End -->#s", "[unc]\\2[/unc]", $input);
		$input = preg_replace("#<!-- SPCode Start --><A HREF=\"mailto:(.*?)\">(.*?)</A><!-- SPCode End -->#s", "[email]\\1[/email]", $input);
		$input = preg_replace("#<!-- SPCode Start --><IMG SRC=\"(.*?)\" BORDER=\"0\"><!-- SPCode End -->#s", "[img]\\1[/img]", $input);
		$input = str_replace("<!-- SPCode --><LI>", "[*]", $input);
		$input = str_replace("<!-- SPCode ulist Start --><UL>", "[list]", $input);
		$input = preg_replace("#<!-- SPCode olist Start --><OL TYPE=([A1])>#si", "[list=\\1]", $input);
		$input = str_replace("</UL><!-- SPCode ulist End -->", "[/list]", $input);
		$input = str_replace("</OL><!-- SPCode olist End -->", "[/list]", $input);
		$input = preg_replace("#<!-- SPCode color Start --><font color=\"(.*?)\">(.*?)</font><!-- SPCode color End -->#s", "[color=\\1]\\2[/color]", $input);
		$input = preg_replace("#<!-- SPCode size Start --><font size=\"(.*?)\">(.*?)</font><!-- SPCode size End -->#s", "[size=\\1]\\2[/size]", $input);
		$input = preg_replace("#<!-- SPCode font Start --><font face=\"(.*?)\">(.*?)</font><!-- SPCode font End -->#s", "[font=\\1]\\2[/font]", $input);
		$input = preg_replace("#<!-- SPCode align Start --><p align=\"(.*?)\">(.*?)</p><!-- SPCode align End -->#s", "[align=\\1]\\2[/align]", $input);
		return($input);
}

function bbencode_php($input)
{
	if (!strpos(strtolower($input), "[php]"))
	{
		return $input;
	}

	$input = preg_replace("/\[([0-9]+?)php\]/si", "[#\\1php]", $input);
	$input = preg_replace("/\[\/php([0-9]+?)\]/si", "[/php#\\1]", $input);

	$stack = Array();
	$curr_pos = 1;
	$max_nesting_depth = 0;
	while ($curr_pos && ($curr_pos < strlen($input)))
	{
		$curr_pos = strpos($input, "[", $curr_pos);
		if ($curr_pos)
		{
			$possible_start = substr($input, $curr_pos, 5);
			$possible_end = substr($input, $curr_pos, 6);
			if (strcasecmp("[php]", $possible_start) == 0)
			{
				array_push($stack, $curr_pos);
				++$curr_pos;
			}
			else if (strcasecmp("[/php]", $possible_end) == 0)
			{
				if (sizeof($stack) > 0)
				{
					$curr_nesting_depth = sizeof($stack);
					$max_nesting_depth = ($curr_nesting_depth > $max_nesting_depth) ? $curr_nesting_depth : $max_nesting_depth;
					$start_index = array_pop($stack);
					$before_start_tag = substr($input, 0, $start_index);
					$between_tags = substr($input, $start_index + 5, $curr_pos - $start_index - 5);
					$after_end_tag = substr($input, $curr_pos + 6);
					$input = $before_start_tag . "[" . $curr_nesting_depth . "php]";
					$input .= $between_tags . "[/php" . $curr_nesting_depth . "]";
					$input .= $after_end_tag;
					if (sizeof($stack) > 0)
					{
						$curr_pos = array_pop($stack);
						array_push($stack, $curr_pos);
						++$curr_pos;
					}
					else
					{
						$curr_pos = 1;
					}
				}
				else
				{
					++$curr_pos;
				}
			}
			else
			{
				++$curr_pos;
			}
		}
	}

	if ($max_nesting_depth > 0)
	{
		for ($i = 1; $i <= $max_nesting_depth; ++$i)
		{
			$start_tag = escape_slashes(preg_quote("[" . $i . "php]"));
			$end_tag = escape_slashes(preg_quote("[/php" . $i . "]"));
			$match_count = preg_match_all("/$start_tag(.*?)$end_tag/si", $input, $matches);
			for ($j = 0; $j < $match_count; $j++)
			{
				$before_replace = escape_slashes(preg_quote($matches[1][$j]));
				$after_replace = $matches[1][$j];
				if($i < 2)
				{
					ob_start();
					highlight_string($after_replace);
					$after_replace = ob_get_contents();
					ob_end_clean();
				}
				$str_to_match = $start_tag . $before_replace . $end_tag;
				$input = preg_replace("/$str_to_match/si", "<!-- SPCode PHP Start --><span class=\"phpcode\"><TABLE BORDER=0 ALIGN=CENTER WIDTH=85%><TR><TD><font size=-1>PHP-Code:</font></TD></TR><TR><TD><FONT SIZE=-1><PRE>$after_replace</PRE></FONT></TD></TR></TABLE></span><!-- SPCode PHP End -->", $input);
			}
		}
	}
	$input = preg_replace("/\[#([0-9]+?)php\]/si", "[\\1php]", $input);
	$input = preg_replace("/\[\/php#([0-9]+?)\]/si", "[/php\\1]", $input);
	return $input;
}

function bbencode_quote($input)
{
	if (!strpos(strtolower($input), "[quote]"))
	{
		return $input;
	}

	$stack = Array();
	$curr_pos = 1;
	while ($curr_pos && ($curr_pos < strlen($input)))
	{
		$curr_pos = strpos($input, "[", $curr_pos);

		if ($curr_pos)
		{
			$possible_start = substr($input, $curr_pos, 7);
			$possible_end = substr($input, $curr_pos, 8);
			if (strcasecmp("[quote]", $possible_start) == 0)
			{
				array_push($stack, $curr_pos);
				++$curr_pos;
			}
			else if (strcasecmp("[/quote]", $possible_end) == 0)
			{
				if (sizeof($stack) > 0)
				{
					$start_index = array_pop($stack);
					$before_start_tag = substr($input, 0, $start_index);
					$between_tags = substr($input, $start_index + 7, $curr_pos - $start_index - 7);
					$after_end_tag = substr($input, $curr_pos + 8);
					$input = $before_start_tag . "<!-- SPCode Quote Start --><TABLE BORDER=0 ALIGN=CENTER WIDTH=85%><TR><TD class=\"bbc_quote_title\">{bbc_quote}:</TD></TR><TR><TD><table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"bbc_quote\"><tr><td><BLOCKQUOTE CLASS=\"bbc_quote\">";
					$input .= $between_tags . "</BLOCKQUOTE></TD></TR></TABLE></TD></TR></TABLE><!-- SPCode Quote End -->";
					$input .= $after_end_tag;
					if (sizeof($stack) > 0)
					{
						$curr_pos = array_pop($stack);
						array_push($stack, $curr_pos);
						++$curr_pos;
					}
					else
					{
						$curr_pos = 1;
					}
				}
				else
				{
					++$curr_pos;
				}
			}
			else
			{
				++$curr_pos;
			}
		}
	}

	return $input;
}

function bbencode_code($input)
{
	if (!strpos(strtolower($input), "[code]"))
	{
		return $input;
	}

	$input = preg_replace("/\[([0-9]+?)code\]/si", "[#\\1code]", $input);
	$input = preg_replace("/\[\/code([0-9]+?)\]/si", "[/code#\\1]", $input);

	$stack = Array();
	$curr_pos = 1;
	$max_nesting_depth = 0;
	while ($curr_pos && ($curr_pos < strlen($input)))
	{
		$curr_pos = strpos($input, "[", $curr_pos);
		if ($curr_pos)
		{
			$possible_start = substr($input, $curr_pos, 6);
			$possible_end = substr($input, $curr_pos, 7);
			if (strcasecmp("[code]", $possible_start) == 0)
			{
				array_push($stack, $curr_pos);
				++$curr_pos;
			}
			else if (strcasecmp("[/code]", $possible_end) == 0)
			{
				if (sizeof($stack) > 0)
				{
					$curr_nesting_depth = sizeof($stack);
					$max_nesting_depth = ($curr_nesting_depth > $max_nesting_depth) ? $curr_nesting_depth : $max_nesting_depth;
					$start_index = array_pop($stack);
					$before_start_tag = substr($input, 0, $start_index);
					$between_tags = substr($input, $start_index + 6, $curr_pos - $start_index - 6);
					$after_end_tag = substr($input, $curr_pos + 7);
					$input = $before_start_tag . "[" . $curr_nesting_depth . "code]";
					$input .= $between_tags . "[/code" . $curr_nesting_depth . "]";
					$input .= $after_end_tag;
					if (sizeof($stack) > 0)
					{
						$curr_pos = array_pop($stack);
						array_push($stack, $curr_pos);
						++$curr_pos;
					}
					else
					{
						$curr_pos = 1;
					}
				}
				else
				{
					++$curr_pos;
				}
			}
			else
			{
				++$curr_pos;
			}
		}
	}

	if ($max_nesting_depth > 0)
	{
		for ($i = 1; $i <= $max_nesting_depth; ++$i)
		{
			$start_tag = escape_slashes(preg_quote("[" . $i . "code]"));
			$end_tag = escape_slashes(preg_quote("[/code" . $i . "]"));
			$match_count = preg_match_all("/$start_tag(.*?)$end_tag/si", $input, $matches);
			for ($j = 0; $j < $match_count; $j++)
			{
				$before_replace = escape_slashes(preg_quote($matches[1][$j]));
				$after_replace = $matches[1][$j];
				if($i < 2)
				{
					$after_replace = htmlspecialchars($after_replace);
				}
				$str_to_match = $start_tag . $before_replace . $end_tag;
				$input = preg_replace("/$str_to_match/si", "<!-- SPCode Code Start --><TABLE BORDER=0 ALIGN=CENTER WIDTH=85%><TR><TD class=\"bbc_code_title\">{bbc_code}:</TD></TR><TR><TD><table width=\"100%\" class=\"bbc_code\" cellspacing=\"1\" cellpadding=\"3\"><tr><td><PRE CLASS=\"bbc_code\">$after_replace</PRE></TD></TR></TABLE></TD></TR></TABLE><!-- SPCode End -->\n", $input);
			}
		}
	}
	$input = preg_replace("/\[#([0-9]+?)code\]/si", "[\\1code]", $input);
	$input = preg_replace("/\[\/code#([0-9]+?)\]/si", "[/code\\1]", $input);
	return $input;
}

function bbencode_list($input)
{
	$start_length = Array();
	$start_length["ordered"] = 8;
	$start_length["unordered"] = 6;
	if (!strpos(strtolower($input), "[list"))
	{
		return $input;
	}
	$stack = Array();
	$curr_pos = 1;
	while ($curr_pos && ($curr_pos < strlen($input)))
	{
		$curr_pos = strpos($input, "[", $curr_pos);
		if ($curr_pos)
		{
			$possible_ordered_start = substr($input, $curr_pos, $start_length["ordered"]);
			$possible_unordered_start = substr($input, $curr_pos, $start_length["unordered"]);
			$possible_end = substr($input, $curr_pos, 7);
			if (strcasecmp("[list]", $possible_unordered_start) == 0)
			{
				array_push($stack, array($curr_pos, ""));
				++$curr_pos;
			}
			else if (preg_match("/\[list=([a1])\]/si", $possible_ordered_start, $matches))
			{
				array_push($stack, array($curr_pos, $matches[1]));
				++$curr_pos;
			}
			else if (strcasecmp("[/list]", $possible_end) == 0)
			{
				if (sizeof($stack) > 0)
				{
					$start = array_pop($stack);
					$start_index = $start[0];
					$start_char = $start[1];
					$is_ordered = ($start_char != "");
					$start_tag_length = ($is_ordered) ? $start_length["ordered"] : $start_length["unordered"];
					$before_start_tag = substr($input, 0, $start_index);
					$between_tags = substr($input, $start_index + $start_tag_length, $curr_pos - $start_index - $start_tag_length);
					$between_tags = str_replace("[*]", "<!-- SPCode --><LI>", $between_tags);
					$after_end_tag = substr($input, $curr_pos + 7);
					if ($is_ordered)
					{
						$input = $before_start_tag . "<!-- SPCode olist Start --><OL TYPE=" . $start_char . ">";
						$input .= $between_tags . "</OL><!-- SPCode olist End -->";
					}
					else
					{
						$input = $before_start_tag . "<!-- SPCode ulist Start --><UL>";
						$input .= $between_tags . "</UL><!-- SPCode ulist End -->";
					}
					$input .= $after_end_tag;
					if (sizeof($stack) > 0)
					{
						$a = array_pop($stack);
						$curr_pos = $a[0];
						array_push($stack, $a);
						++$curr_pos;
					}
					else
					{
						$curr_pos = 1;
					}
				}
				else
				{
					++$curr_pos;
				}
			}
			else
			{
				++$curr_pos;
			}
		}
	}
	return $input;
}

function make_clickable($input)
{
	$ret = " " . $input;
	$ret = preg_replace("#([\n ])([a-z]+?)://([^, \n\r]+)#i", "\\1<!-- SPCode auto-link start --><a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a><!-- SPCode auto-link end -->", $ret);
	$ret = preg_replace("#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^, \n\r]*)?)#i", "\\1<!-- SPCode auto-link start --><a href=\"http://www.\\2.\\3\\4\" target=\"_blank\">www.\\2.\\3\\4</a><!-- SPCode auto-link end -->", $ret);
	$ret = substr($ret, 1);
	return($ret);
}

function undo_make_clickable($input)
{
	$input = preg_replace("#<!-- SPCode auto-link start --><a href=\"(.*?)\" target=\"_blank\">.*?</a><!-- SPCode auto-link end -->#i", "\\1", $input);
	return $input;

}

function forbidden_freemailer($email, $db)
{
	global $tableprefix;

	$sql="select * from ".$tableprefix."_freemailer";
	if(!$result = faqe_db_query($sql, $db))
	    die("Could not connect to the database.");
	if (!$myrow = faqe_db_fetch_array($result))
		return false;
	do{
		if(substr_count(strtolower($email), strtolower($myrow["address"]))>0)
			return true;
	} while($myrow = faqe_db_fetch_array($result));
	return false;
}

function gethostname($ipadr, $db, $doresolve)
{
	global $hcprefix;

	$sql="select * from ".$hcprefix."_hostcache where ipadr='$ipadr'";
	if(!$result = faqe_db_query($sql, $db))
	    die("Could not connect to the database.");
	$acthostname="";
	if ((!$myrow = faqe_db_fetch_array($result)) && ($doresolve==true))
	{
		$acthostname=gethostbyaddr($ipadr);
		$sql = "insert into ".$hcprefix."_hostcache (ipadr, hostname) values ('$ipadr','$acthostname')";
		if(!$result = faqe_db_query($sql, $db))
		    die("Could not connect to the database.");
	}
	else
		$acthostname=$myrow["hostname"];
	return $acthostname;
}

function escape_slashes($input)
{
	$output = str_replace('/', '\/', $input);
	return $output;
}

function do_url_session($url)
{
	global $sessid_url, $url_sessid, $sesscookiename;

	$url=ereg_replace("[&?]+$", "", $url);
	if($sessid_url)
	{
		if(strpos(urlencode($sesscookiename),$url)>-1)
			return url;
		$url2="";
		if(strrpos($url,"#")>0)
		{
			$url2=substr($url,strrpos($url,"#"));
			$url=substr($url,0,strrpos($url,"#"));
		}
		$url .= ( strpos($url, "?") != false ?  "&" : "?" ).urlencode($sesscookiename)."=".$url_sessid;
		if(strlen($url2)>0)
			$url.=$url2;
    }
    return $url;
}

function purge_keywords($db)
{
	global $tableprefix;

	$firstvalue=1;
	$keywordnrs="";
	$sql = "select keywordnr from ".$tableprefix."_kb_keywords group by keywordnr";
	if(!$result = faqe_db_query($sql, $db))
	    die("tr bgcolor=\"#cccccc\"><td>Unable to purge keyword table in database.");
	if($myrow=faqe_db_fetch_array($result))
	{
		do{
			if($firstvalue==1)
				$firstvalue=0;
			else
				$keywordnrs.=", ";
			$keywordnrs.=$myrow["keywordnr"];
		}while($myrow=faqe_db_fetch_array($result));
	}
	$sql = "select keywordnr from ".$tableprefix."_faq_keywords group by keywordnr";
	if(!$result = faqe_db_query($sql, $db))
	    die("tr bgcolor=\"#cccccc\"><td>Unable to purge keyword table in database.");
	if($myrow=faqe_db_fetch_array($result))
	{
		do{
			if($firstvalue==1)
				$firstvalue=0;
			else
				$keywordnrs.=", ";
			$keywordnrs.=$myrow["keywordnr"];
		}while($myrow=faqe_db_fetch_array($result));
	}
	if(strlen($keywordnrs)>0)
	{
		$sql = "delete from ".$tableprefix."_keywords where keywordnr not in ($keywordnrs)";
		if(!$result = faqe_db_query($sql, $db))
		    die("tr bgcolor=\"#cccccc\"><td>Unable to purge keyword table in database.");
	}
	else
	{
		$sql = "delete from ".$tableprefix."_keywords";
		if(!$result = faqe_db_query($sql, $db))
		    die("tr bgcolor=\"#cccccc\"><td>Unable to purge keyword table in database.");
	}
}

function faq_ref_select($name="faqref", $jsfunction="faqref", $inputfield="")
{
	global $tableprefix, $db, $l_bbcode_helps;
	global $filterprog, $filterlang, $admedoptions;

	$select = "<SELECT onmouseout=\"helpline('','$inputfield')\" onmouseover=\"helpline('".$l_bbcode_helps[21]."','$inputfield')\" class=\"faqlink\" NAME=\"$name\" onchange=\"$jsfunction(this.options[this.selectedIndex].value, '$inputfield');\">\n";
	$sql="select dat.faqnr, dat.heading, prog.programmname, prog.progid, prog.language, cat.categoryname, cat.catnr from ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where dat.category=cat.catnr and prog.prognr=cat.programm ";
	if(bittst($admedoptions,BIT_1))
	{
		if(isset($filterprog) && ($filterprog>=0))
			$sql.="and prog.prognr=$filterprog ";
		else if(isset($filterlang) && ($filterlang!="none"))
			$sql.="and prog.language='$filterlang' ";
	}
	$sql.="group by dat.faqnr order by prog.language asc";
	if(bittst($admedoptions,BIT_2))
		$sql.=", prog.programmname asc, cat.categoryname asc, dat.heading asc";
	else
		$sql.=", prog.displaypos asc, cat.displaypos asc, dat.displaypos asc";
	if(!$result = faqe_db_query($sql, $db))
		db_die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	$select .= "<option value=\"0|0|0|0\"></option>\n";
	while ($myrow=faqe_db_fetch_array($result))
	{
		$value = $myrow["faqnr"]."|".$myrow["catnr"]."|".$myrow["progid"];
		$display = $myrow["faqnr"]." ".undo_html_ampersand(stripslashes($myrow["heading"]))." (".display_encoded($myrow["categoryname"])." [".display_encoded($myrow["programmname"])."|".$myrow["language"]."])";
		$select .= "  <OPTION value=\"$value\">$display</option>\n";
	}
	$select .= "</SELECT>\n";
	return $select;
}

function faq_ref_select2($name="faqref", $jsfunction="faqref2", $inputfield="", $target="faqdisplay")
{
	global $tableprefix, $db, $l_bbcode_helps;
	global $admedoptions;

	$select = "<SELECT onmouseout=\"helpline('','$inputfield')\" onmouseover=\"helpline('".$l_bbcode_helps[21]."','$inputfield')\" class=\"faqlink\" NAME=\"$name\" onchange=\"$jsfunction(this.options[this.selectedIndex].value, '$inputfield', '$target');\">\n";
	$sql="select dat.faqnr, dat.heading, prog.programmname, prog.progid, prog.language, cat.categoryname, cat.catnr from ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where dat.category=cat.catnr and prog.prognr=cat.programm ";
	$sql.="group by dat.faqnr order by prog.language asc";
	if(bittst($admedoptions,BIT_2))
		$sql.=", prog.programmname asc, cat.categoryname asc, dat.heading asc";
	else
		$sql.=", prog.displaypos asc, cat.displaypos asc, dat.displaypos asc";
	if(!$result = faqe_db_query($sql, $db))
		db_die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	$select .= "<option value=\"0|0|0|0\"></option>\n";
	while ($myrow=faqe_db_fetch_array($result))
	{
		$value = $myrow["faqnr"]."|".$myrow["catnr"]."|".$myrow["progid"];
		$display = $myrow["faqnr"]." ".undo_html_ampersand(stripslashes($myrow["heading"]))." (".display_encoded($myrow["categoryname"])." [".display_encoded($myrow["programmname"])."|".$myrow["language"]."])";
		$select .= "  <OPTION value=\"$value\">$display</option>\n";
	}
	$select .= "</SELECT>\n";
	return $select;
}

function kb_ref_select($name="kbref", $jsfunction="kbref", $inputfield="")
{
	global $tableprefix, $db, $l_bbcode_helps, $l_none;
	$select = "<SELECT NAME=\"$name\" onmouseout=\"helpline('','$inputfield')\" onmouseover=\"helpline('".$l_bbcode_helps[23]."','$inputfield')\" class=\"faqlink\" onchange=\"$jsfunction(this.options[this.selectedIndex].value, '$inputfield');\">\n";
	$sql="select dat.articlenr, dat.heading, dat.category, prog.programmname, prog.language, prog.progid from ".$tableprefix."_kb_articles dat, ".$tableprefix."_programm prog where dat.programm=prog.prognr order by prog.language, prog.displaypos, dat.displaypos";
	if(!$result = faqe_db_query($sql, $db))
		db_die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	$select .= "<option value=\"0|0\"></option>\n";
	while ($myrow=faqe_db_fetch_array($result))
	{
		if($myrow["category"]!=0)
		{
			$tmpsql="select * from ".$tableprefix."_kb_cat where catnr=".$myrow["category"];
			if(!$tmpresult = faqe_db_query($tmpsql, $db))
				db_die("Could not connect to the database.");
			if($tmprow=faqe_db_fetch_array($tmpresult))
				$catname=$tmprow["catname"];
			else
				$catname=$l_none;
		}
		else
			$catname=$l_none;
		$value = $myrow["articlenr"]."|".$myrow["progid"];
		$display = $myrow["articlenr"]." ".undo_html_ampersand(stripslashes($myrow["heading"]))." (".display_encoded($catname)." [".display_encoded($myrow["programmname"])."|".$myrow["language"]."])";
		$select .= "  <OPTION value=\"$value\">$display</option>\n";
	}
	$select .= "</SELECT>\n";
	return $select;
}

function kb_ref_select2($name="kbref", $jsfunction="kbref2", $inputfield="", $target="kbdisplay")
{
	global $tableprefix, $db, $l_bbcode_helps, $l_none;
	$select = "<SELECT NAME=\"$name\" onmouseout=\"helpline('','$inputfield')\" onmouseover=\"helpline('".$l_bbcode_helps[25]."','$inputfield')\" class=\"faqlink\" onchange=\"$jsfunction(this.options[this.selectedIndex].value, '$inputfield', '$target');\">\n";
	$sql="select dat.articlenr, dat.heading, dat.category, prog.programmname, prog.language, prog.progid from ".$tableprefix."_kb_articles dat, ".$tableprefix."_programm prog where dat.programm=prog.prognr order by prog.language, prog.displaypos, dat.displaypos";
	if(!$result = faqe_db_query($sql, $db))
		db_die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	$select .= "<option value=\"0|0\"></option>\n";
	while ($myrow=faqe_db_fetch_array($result))
	{
		if($myrow["category"]!=0)
		{
			$tmpsql="select * from ".$tableprefix."_kb_cat where catnr=".$myrow["category"];
			if(!$tmpresult = faqe_db_query($tmpsql, $db))
				db_die("Could not connect to the database.");
			if($tmprow=faqe_db_fetch_array($tmpresult))
				$catname=$tmprow["catname"];
			else
				$catname=$l_none;
		}
		else
			$catname=$l_none;
		$value = $myrow["articlenr"]."|".$myrow["progid"];
		$display = $myrow["articlenr"]." ".undo_html_ampersand(stripslashes($myrow["heading"]))." (".display_encoded($catname)." [".display_encoded($myrow["programmname"])."|".$myrow["language"]."])";
		$select .= "  <OPTION value=\"$value\">$display</option>\n";
	}
	$select .= "</SELECT>\n";
	return $select;
}

function faq_addopts($name="qref",$jsfunction="faqref",$inputfield="")
{
	global $l_kblink, $l_faqlink, $langvar, $act_lang, $l_addgfx, $l_bbcode_helps;

	$text="";
	$text.="<table class=\"faqlink\" width=\"99%\" align=\"center\">";
	$text.="<tr><td valign=\"middle\">";
	$text.=$l_faqlink.": ".faq_ref_select($name,$jsfunction,$inputfield);
	$text.="</td></tr><tr><td>";
	$text.="<tr><td valign=\"middle\">";
	$text.=$l_kblink.": ".kb_ref_select2($name."kb","kbref2",$inputfield);
	$text.="</td></tr><tr><td>";
	$text.="<a href=\"javascript:gfx_uploader('$inputfield')\">";
	$text.="<img name=\"bbcp2_$inputfield\" onmouseout=\"unhighlightbutton('','$inputfield','bbcp2_$inputfield','bbcode_pic2.gif')\" onmouseover=\"highlightbutton('".$l_bbcode_helps[22]."','$inputfield','bbcp2_$inputfield','bbcode_pic2.gif')\" src=\"gfx/bbcodes/normal/bbcode_pic2.gif\" title=\"$l_addgfx\" alt=\"$l_addgfx\" border=\"0\" align=\"absmiddle\"></a>";
	$text.="</td></tr></table>";
	return $text;
}

function kb_addopts($name="qref",$jsfunction="kbref",$inputfield="")
{
	global $l_kblink, $l_faqlink, $langvar, $act_lang, $l_addgfx, $l_bbcode_helps;

	$text="";
	$text.="<table class=\"faqlink\" width=\"99%\" align=\"center\">";
	$text.="<tr><td valign=\"middle\">";
	$text.=$l_kblink.": ".kb_ref_select($name,$jsfunction,$inputfield);
	$text.="</td></tr><tr><td>";
	$text.="<tr><td valign=\"middle\">";
	$text.=$l_faqlink.": ".faq_ref_select2($name."faq","faqref2",$inputfield);
	$text.="</td></tr><tr><td>";
	$text.="<a href=\"javascript:gfx_uploader2('$inputfield')\">";
	$text.="<img name=\"bbcp2_$inputfield\" onmouseout=\"unhighlightbutton('','$inputfield','bbcp2_$inputfield','bbcode_pic2.gif')\" onmouseover=\"highlightbutton('".$l_bbcode_helps[22]."','$inputfield','bbcp2_$inputfield','bbcode_pic2.gif')\" src=\"gfx/bbcodes/normal/bbcode_pic2.gif\" title=\"$l_addgfx\" alt=\"$l_addgfx\" border=\"0\" align=\"absmiddle\"></a>";
	$text.="</td></tr></table>";
	return $text;
}

function mail_smtp($receiver,$subject,$mailbody,$sender)
{
	global $smtpserver, $smtpport, $smtpauth, $smtpuser, $smtppasswd, $path_faqe;
	global $contentcharset, $crlf;

	include_once($path_faqe.'/includes/htmlMimeMail.inc');
	include_once($path_faqe.'/includes/smtp.inc');
	include_once($path_faqe.'/includes/RFC822.inc');

	$mail = new htmlMimeMail();
	$mail->setCrlf($crlf);
	$mail->setTextCharset($contentcharset);
	$mail->setText($mailbody);
	$mail->setSubject($subject);
	$mail->setFrom($sender);
	$receivers=explode(", ",$receiver);
	for($i=0;$i<count($receivers);$i++)
	{
		$localreceiver=array($receivers[$i]);
		$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
		$mail->send($localreceiver, "smtp");
	}
}

function getRealFileExtension($filename)
{
  return ereg( ".([^\.]+)$", $filename, $r ) ? $r[1] : "";
}

function getRealFilename($filename)
{
	$tmpext=".".getRealFileExtension($filename);
	$tmpfilename=str_replace($tmpext,"",$filename);
	return($tmpfilename);
}

function getUploadFileType($filename)
{
	global $tableprefix, $db;
	$fileext=".".getRealFileExtension($filename);
	$sql="select mime.* from ".$tableprefix."_mimetypes mime, ".$tableprefix."_fileextensions ext where mime.entrynr=ext.mimetype and ext.extension='$fileext'";
	if(!$result = faqe_db_query($sql, $db))
		die("Unable to connect to database");
	if($myrow=faqe_db_fetch_array($result))
		return($myrow["mimetype"]);
	else
		return "application/octetstream";
}
function getSortMarker($currentorder, $column, $maxcolumn)
{
	$sortdown="<img src=\"gfx/down2.gif\" width=\"12\" height=\"9\" border=\"0\" align=\"baseline\">";
	$sortup="<img src=\"gfx/up2.gif\" width=\"12\" height=\"9\" border=\"0\" align=\"baseline\">";
	$nosort="<img src=\"gfx/space.gif\" width=\"12\" height=\"9\" border=\"0\" align=\"baseline\">";

	if($column>$maxcolumn)
		return "";
	$currentcolumn=floor($currentorder/10);
	$currentdirection=$currentorder%10;
	if($currentcolumn!=$column)
		return $nosort;
	if($currentdirection==1)
		return $sortdown;
	else
		return $sortup;
}

function getSortURL($currentorder, $column, $maxcolumn, $baseurl, $anchor="")
{
	if($column>$maxcolumn)
		return "";
	$currentcolumn=floor($currentorder/10);
	$currentdirection=$currentorder%10;
	if($column!=$currentcolumn)
		$sorturl=$baseurl."&sorting=".$column."2";
	else if($currentdirection==1)
		$sorturl=$baseurl."&sorting=".$column."2";
	else
		$sorturl=$baseurl."&sorting=".$column."1";
	if($anchor)
		$sorturl.="#".$anchor;
	return $sorturl;
}

function tz_select($default, $name="timezone")
{
	global $timezones;

	$tzselect="<select name=\"$name\">";
	for($i=0;$i<count($timezones);$i++)
	{
		$tzselect.="<option value=\"$i\"";
		if($i==$default)
			$tzselect.=" selected";
		$tzselect.=">";
		$tzselect.=$timezones[$i][0];
		$tzselect.="</option>";
	}
	$tzselect.="</select>";
	return $tzselect;
}
?>