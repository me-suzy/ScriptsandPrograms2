<?php
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
/***************************************************************************
 * Created by: Boesch IT-Consulting (info@boesch-it.de)
 * (c)2002-2005 Boesch IT-Consulting
 * *************************************************************************/
function bbencode($input)
{
	$input = " " . $input;
	if (! (strpos($input, "[") && strpos($input, "]")) )
	{
		$input = substr($input, 1);
		return $input;
	}
	$input = bbencode_code($input);
	$input = bbencode_quote($input);
	$input = bbencode_list($input);
	$input = preg_replace("/\[b\](.*?)\[\/b\]/si", "<!-- SPCode Start --><B>\\1</B><!-- SPCode End -->", $input);
	$input = preg_replace("/\[i\](.*?)\[\/i\]/si", "<!-- SPCode Start --><I>\\1</I><!-- SPCode End -->", $input);
	$input = preg_replace("/\[s\](.*?)\[\/s\]/si", "<!-- SPCode Start --><s>\\1</s><!-- SPCode End -->", $input);
	$input = preg_replace("/\[tt\](.*?)\[\/tt\]/si", "<!-- SPCode Start --><tt>\\1</tt><!-- SPCode End -->", $input);
	$input = preg_replace("/\[sub\](.*?)\[\/sub\]/si", "<!-- SPCode Start --><sub>\\1</sub><!-- SPCode End -->", $input);
	$input = preg_replace("/\[sup\](.*?)\[\/sup\]/si", "<!-- SPCode Start --><sup>\\1</sup><!-- SPCode End -->", $input);
	$input = preg_replace("/\[center\](.*?)\[\/center\]/si", "<!-- SPCode Start --><center>\\1</center><!-- SPCode End -->", $input);
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
	$input = preg_replace($patterns, $replacements, $input);
	$input = substr($input, 1);
	return $input;

}

function bbdecode($input)
{
		$code_start_html = "<!-- SPCode Start --><TABLE BORDER=0 ALIGN=CENTER WIDTH=85%><TR><TD><font size=-1>Code:</font><HR></TD></TR><TR><TD><FONT SIZE=-1><PRE>";
		$code_end_html = "</PRE></FONT></TD></TR><TR><TD><HR></TD></TR></TABLE><!-- SPCode End -->";
		$input = str_replace($code_start_html, "[code]", $input);
		$input = str_replace($code_end_html, "[/code]", $input);
		$quote_start_html = "<!-- SPCode Quote Start --><TABLE BORDER=0 ALIGN=CENTER WIDTH=85%><TR><TD><font size=-1>Quote:</font><HR></TD></TR><TR><TD><FONT SIZE=-1><BLOCKQUOTE>";
		$quote_end_html = "</BLOCKQUOTE></FONT></TD></TR><TR><TD><HR></TD></TR></TABLE><!-- SPCode Quote End -->";
		$input = str_replace($quote_start_html, "[quote]", $input);
		$input = str_replace($quote_end_html, "[/quote]", $input);
		$input = preg_replace("#<!-- SPCode Start --><s>(.*?)</s><!-- SPCode End -->#s", "[s]\\1[/s]", $input);
		$input = preg_replace("#<!-- SPCode Start --><tt>(.*?)</tt><!-- SPCode End -->#s", "[tt]\\1[/tt]", $input);
		$input = preg_replace("#<!-- SPCode Start --><sub>(.*?)</sub><!-- SPCode End -->#s", "[sub]\\1[/sub]", $input);
		$input = preg_replace("#<!-- SPCode Start --><sup>(.*?)</sup><!-- SPCode End -->#s", "[sup]\\1[/sup]", $input);
		$input = preg_replace("#<!-- SPCode Start --><center>(.*?)</center><!-- SPCode End -->#s", "[center]\\1[/center]", $input);
		$input = preg_replace("#<!-- SPCode Start --><B>(.*?)</B><!-- SPCode End -->#s", "[b]\\1[/b]", $input);
		$input = preg_replace("#<!-- SPCode Start --><I>(.*?)</I><!-- SPCode End -->#s", "[i]\\1[/i]", $input);
		$input = preg_replace("#<!-- SPCode u2 Start --><A HREF=\"([a-z]+?://)(.*?)\" TARGET=\"_blank\">(.*?)</A><!-- SPCode u2 End -->#s", "[url=\\1\\2]\\3[/url]", $input);
		$input = preg_replace("#<!-- SPCode u1 Start --><A HREF=\"([a-z]+?://)(.*?)\" TARGET=\"_blank\">(.*?)</A><!-- SPCode u1 End -->#s", "[url]\\3[/url]", $input);
		$input = preg_replace("#<!-- SPCode Start --><A HREF=\"mailto:(.*?)\">(.*?)</A><!-- SPCode End -->#s", "[email]\\1[/email]", $input);
		$input = str_replace("<!-- SPCode --><LI>", "[*]", $input);
		$input = str_replace("<!-- SPCode ulist Start --><UL>", "[list]", $input);
		$input = preg_replace("#<!-- SPCode olist Start --><OL TYPE=([A1])>#si", "[list=\\1]", $input);
		$input = str_replace("</UL><!-- SPCode ulist End -->", "[/list]", $input);
		$input = str_replace("</OL><!-- SPCode olist End -->", "[/list]", $input);
		$input = preg_replace("#<!-- SPCode color Start --><font color=\"(.*?)\">(.*?)</font><!-- SPCode color End -->#s", "[color=\\1]\\2[/color]", $input);
		$input = preg_replace("#<!-- SPCode size Start --><font size=\"(.*?)\">(.*?)</font><!-- SPCode size End -->#s", "[size=\\1]\\2[/color]", $input);
		return($input);
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
					$input = $before_start_tag . "<!-- SPCode Quote Start --><TABLE BORDER=0 ALIGN=CENTER WIDTH=85%><TR><TD><font size=-1>Quote:</font><HR></TD></TR><TR><TD><FONT SIZE=-1><BLOCKQUOTE>";
					$input .= $between_tags . "</BLOCKQUOTE></FONT></TD></TR><TR><TD><HR></TD></TR></TABLE><!-- SPCode Quote End -->";
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
				$input = preg_replace("/$str_to_match/si", "<!-- SPCode Start --><TABLE BORDER=0 ALIGN=CENTER WIDTH=85%><TR><TD><font size=-1>Code:</font><HR></TD></TR><TR><TD><FONT SIZE=-1><PRE>$after_replace</PRE></FONT></TD></TR><TR><TD><HR></TD></TR></TABLE><!-- SPCode End -->", $input);
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

function gethostname($ipadr, $db, $doresolve)
{
	global $hcprefix;

	$sql="select * from ".$hcprefix."_hostcache where ipadr='$ipadr'";
	if(!$result = mysql_query($sql, $db))
	    die("Could not connect to the database.".mysql_error());
	$acthostname="";
	if ((!$myrow = mysql_fetch_array($result)) && ($doresolve==true))
	{
		$acthostname=gethostbyaddr($ipadr);
		$sql = "insert into ".$hcprefix."_hostcache (ipadr, hostname) values ('$ipadr','$acthostname')";
		if(!$result = mysql_query($sql, $db))
		    die("Could not connect to the database.".mysql_error());
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

function mailserver_exists($email)
{
	global $unixserver;
	
	list($null,$emailhost)=split("@",$email);
	if($unixserver)
	{
		if (checkdnsrr($emailhost, "MX"))
			return true;
		else
			return false;
	}
	else
	{
		require_once("Net/DNS.php");
		$res = new Net_DNS_Resolver();
		$answer = $res->search($emailhost, "MX");
		return $answer;
	}
}
?>