<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################### //BBCODE FUNCTIONS\\ ################## \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// this file will actually contain functions for smilies, post icons, censors, and of course bbcode......

// this function will grab all bbcodes from DB...
// cache it into array and return it.. pretty easy
function buildBBCodes() {
	// get all
	$allBBcodes = query("SELECT * FROM bbcode ORDER BY name");

	// intiate array
	$bbcodeinfo = Array();

	// loop
	if(mysql_num_rows($allBBcodes)) {
		while($bbcode = mysql_fetch_array($allBBcodes)) {
			// form array
			$bbcodeinfo[$bbcode['bbcodeid']] = $bbcode;
		}
	}

	// no bbcodes in db.. return false
	else {
		return false;
	}

	// return array created
	return $bbcodeinfo;
}

// build bbcodes...
$bbcodeinfo = buildBBCodes();

// function to build smilies... same method as above
function buildSmilies() {
	// get all
	$allSmilies = query("SELECT * FROM smilies ORDER BY display_order, title");

	// intiate array
	$smileyinfo = Array();

	// loop
	if(mysql_num_rows($allSmilies)) {
		while($smiley = mysql_fetch_array($allSmilies)) {
			// form array
			$smileyinfo[$smiley['smilieid']] = $smiley;
		}
	}

	// no smilies in db.. return false
	else {
		return false;
	}

	// return array created
	return $smileyinfo;
}

// build smileyinfo arr...
$smileyinfo = buildSmilies();

// this function should count all the images in a text
// BEFORE HTML formatting!
function countImages($text) {
	global $foruminfo, $userinfo, $smileyinfo, $forumid;

	$counter = 0;

	if($foruminfo[$forumid]['allow_img']) {
		// count [img] code
		$counter += substr_count($text,"[img]");
		$counter += substr_count($text,"[img=");
	}

	if($foruminfo[$forumid]['allow_html'] OR $userinfo['allow_html']) {
		// count <img
		$counter += substr_count($text,"<img");
	}

	if($foruminfo[$forumid]['allow_smilies']) {
		// count smilies...
		foreach($smileyinfo as $smileyid => $arr) {
			$counter += substr_count($text,$arr['replacement']);
		}
	}

	return $counter;
}

// this function should take care of bad words!
function doCensors($text) {
	global $bboptions;

	// if censor disabled, or no censored words return the text untouched...
	if(!$bboptions['censor_enabled'] OR !$bboptions['censor_words']) {
		return $text;
	}

	// separate censored words..
	$censoredWords = split(" ",$bboptions['censor_words']);

	// loop through all censored words
	foreach($censoredWords as $index => $word) {
		// get length of word..
		$length = strlen($word);

		unset($replace);

		// make our censor
		for($x = 1; $x <= $length; $x++) {
			$replace .= $bboptions['censor_replace'];
		}

		// replace
		$text = preg_replace("|".$word."|i",$replace,$text);
	}

	// return the censored text
	return $text;
}

// this function will attemp to strip smilies
// from any string passed through this... mainly
// used with bbcodes
function stripSmilies($text) {
	global $smileyinfo;

	// loop through all smilies...
	foreach($smileyinfo as $id => $arr) {
		// replace...
		$text = str_replace("<img src=\"".$arr['filepath']."\" alt=\"".$arr['title']."\" />",$arr['replacement'],$text);
	}

	// return smiley stripped text...
	return $text;
}

// this function will be used when highlighting PHP code...
// it will strip all FONT tags, and replace with proper coding...
function stripFont($text) {
	// replace
	$text = preg_replace('|<font color="(.*)">(.*)</font>|isU','<span style="color: $1;">$2</span>',$text);

	// loop...
	for($x = 1; $x <= substr_count($text,"<font"); $x++) {
		$text = preg_replace('|<font color="(.*)">(.*)</font>|isU','<span style="color: $1;">$2</span>',$text);
	}

	// return
	return $text;
}

function trimQuote($one,$two,$three) {
	$two = trim($two);
	return $one.$two.$three;
}

// this function with format the quote
function doQuote($begin,$name,$end) {
	// strip \"
	$name = str_replace('\"','"',$name);

	// strip the smilies...
	$name = stripSmilies($name);

	$name = stripslashes($name);

	// return
	return $begin.$name.$end;
}

// function to format [code]
function formatCode($formatted,$html5) {
	// un html just in case...
	if(!$html5) $formatted = htmlspecialchars($formatted);

	$formatted = str_replace('\"','"',$formatted);

	// strip smilies..
	$formatted = stripSmilies($formatted);

	// replace first instance of \n
	$formatted = preg_replace("|^\r\n|","",$formatted,1);

	eval("\$theCode = \"".getTemplate("bbcode_code")."\";");

	return $theCode;
}

// this function will do the word wrap..
// i'm not sure if i need this...
function wordWrapper($text) {
	global $bboptions;

	// bah.. this function SUCKS...
	//return $text;

	// if it's disabled.. then just get out..
	if(!$bboptions['general_wordwrap']) {
		return $text;
	}

	// get the length
	$length = strlen($text);
	$wordLength = 0;
	$newText = "";
	$tag = 0;

	// loop through each character
	for($x = 0; $x < $length; $x++) {
		// get the current character
		$currChar = substr($text,$x,1);

		// make sure we're not in an HTML tag :o
		if($currChar == "<"/* OR $currChar == "]" OR $currChar == "["*/) {
			$tag++;
		} 
		
		else if($currChar == ">"/* OR $currChar == "[" OR $currChar == "]"*/) {
			$tag--;
		}

		if($tag == 0 AND $currChar != ' ' AND $currChar != '\n' AND $currChar != '\r' AND $currChar != '\r\n') {
			$wordLength++;
		} else {
			$wordLength = 0;
		}

		// if we can cut... then cut!
		if($bboptions['general_wordwrap'] == $wordLength) {
			$currChar .= "\n";
			$wordLength = 0;
		}

		$newText .= $currChar;
	}

	// return the new text created...
	return $newText;
}

// function to highlight php..
// VERY touchy function.. be careful!
function highlightPHP($text,$html5) {
	global $userinfo, $foruminfo, $forumid;

	// we need to do a little fixing if user has HTML enabled...
	if(!$html5) {
		$text = htmlspecialchars($text);
		$text = str_replace("\$","\\\$",$text);
	}

	$text = stripslashes($text);

	// UN html...
	$text = unhtmlspecialchars($text);

	// strip smilies...
	$text = stripSmilies($text);

	// add...
	$text = "<?php WTCBB_CODE_START".$text."\r\n WTCBB_CODE_END";

	$oldError = error_reporting(0);

	@ob_start();
	@highlight_string($text);
	$text = @ob_get_contents();
	@ob_end_clean();

	error_reporting($oldError);

	// remove what we added above..
	$text = str_replace("&lt;?php WTCBB_CODE_START","",$text);
	$text = str_replace("WTCBB_CODE_END","",$text);

	// CREDIT FOR THIS LINE GOES TO SCYTH...
	// http://www.webtrickscentral.com/forums/thread.php?t=1198
	$text = preg_replace('{([\w_]+)(\s*)(\s*\s*\()}m','<a href="http://www.php.net/$1">$1</a>$2$3', $text);

	$text = stripFont($text);

	$find = Array(
		"%<br />%",
		"%</span>\n</span>\n</code>%",
		"%^<code>%",
		"%</code>$%",
		"%</span> </span>\n$%"
	);

	$replace = Array(
		"",
		"</span></span></code>",
		"",
		"",
		"</span> </span>"
	);

	$text = preg_replace($find,$replace,$text);
	$text = preg_replace("|\n|","",$text,1);
	$text = addslashes($text);

	eval("\$theTemplate = \"".getTemplate("bbcode_php")."\";");

	return $theTemplate;
}
			
// function to parse all bbcode...
function parseAllBBCode($text,$parseIMG = 1,$html6 = "") {
	global $bbcodeinfo;

	// attempt to parse urls...
	$text = preg_replace("%(^|\s|=|\])www\.%isU","$1http://www.",$text);
	$text = preg_replace("%(^|\s)(http://.+)($|\s|\n|\r\n|\[)%isU",'$1[url=$2]$2[/url]$3',$text);

	// loop 
	foreach($bbcodeinfo as $id => $arr) {
		if(!$parseIMG AND strtolower($arr['tag']) == "img") {
			continue;
		}

		// what if PHP?
		if(strtolower($arr['tag']) == "php") {
			// get rid of censor bypassing...
			$text = preg_replace("|(\[php\])(\[/php\])|isU","",$text);

			// replace...
			$text = preg_replace("|(\[php\])(.*)(\[/php\])|eisU","highlightPHP('$2','$html6')",$text);

			continue;
		}

		// what if Code?
		if(strtolower($arr['tag']) == "code") {
			// replace {param} with $2
			$arr['replacement'] = str_replace("{param}","$2",$arr['replacement']);

			// get rid of censor bypassing...
			$text = preg_replace("|(\[code\])(\[/code\])|isU","",$text);

			// replace...
			$text = preg_replace("|(\[code\])(.*)(\[/code\])|eisU","formatCode('$2','$html6')",$text);

			continue;
		}
		
		// what if quote?
		if(strtolower($arr['tag']) == "quote" AND $arr['use_option']) {
			// replace {param} with $2
			$arr['replacement'] = str_replace("{param}","$2",$arr['replacement']);

			// split the replacement
			$split = split("{option}",$arr['replacement']);

			// replace
			$text = preg_replace("#\[quote=(.*)\](.*)\[/quote\]#eisU","doQuote('$split[0]','$1','$split[1]')",$text);

			// loop
			for($x = 1; $x <= (substr_count($text,"[quote")); $x++) {
				$text = preg_replace("#\[quote=(.*)\](.*)\[/quote\]#eisU","doQuote('$split[0]','$1','$split[1]')",$text);
			}
			
			continue;
		}

		// url
		if(strtolower($arr['tag']) == 'url' AND $arr['use_option']) {
			// replace {option} with $2
			$arr['replacement'] = str_replace("{option}","$1",$arr['replacement']);

			// replace {param} with $4
			$arr['replacement'] = str_replace("{param}","$3",$arr['replacement']);

			// replace
			$text = preg_replace("#\[".$arr['tag']."=((http|https|ftp|irc).*)\](.*)\[/".$arr['tag']."\]#isU",$arr['replacement'],$text);

			// loop
			for($x = 1; $x <= (substr_count($text,"[".$arr['tag'])+20); $x++) {
				$text = preg_replace("#\[".$arr['tag']."=((http|https|ftp|irc|\.).*)\](.*)\[/".$arr['tag']."\]#isU",$arr['replacement'],$text);
			}
			
			continue;
		}
		
		// no option
		if(strtolower($arr['tag']) == 'url' AND !$arr['use_option']) {
			// replace {param} with $2
			$arr['replacement'] = str_replace("{param}","$2",$arr['replacement']);
			
			// get rid of censor bypassing
			$text = preg_replace("|(\[".$arr['tag']."\])(\[/".$arr['tag']."\])|isU","",$text);

			// replace
			$text = preg_replace("#(\[".$arr['tag']."\])((http|https|ftp|irc).*)(\[/".$arr['tag']."\])#isU",$arr['replacement'],$text);
		}

		// no option
		if(!$arr['use_option']) {
			// replace {param} with $2
			$arr['replacement'] = str_replace("{param}","$2",$arr['replacement']);
			
			// get rid of censor bypassing
			$text = preg_replace("|(\[".$arr['tag']."\])(\[/".$arr['tag']."\])|isU","",$text);

			// replace
			$text = preg_replace("|(\[".$arr['tag']."\])(.*)(\[/".$arr['tag']."\])|isU",$arr['replacement'],$text);
		}

		// option
		else {
			// replace {option} with $2
			$arr['replacement'] = str_replace("{option}","$1",$arr['replacement']);

			// replace {param} with $4
			$arr['replacement'] = str_replace("{param}","$2",$arr['replacement']);

			// replace
			$text = preg_replace("#\[".$arr['tag']."=(.*)\](.*)\[/".$arr['tag']."\]#isU",$arr['replacement'],$text);

			// loop
			for($x = 1; $x <= (substr_count($text,"[".$arr['tag'])+20); $x++) {
				$text = preg_replace("#\[".$arr['tag']."=(.*)\](.*)\[/".$arr['tag']."\]#isU",$arr['replacement'],$text);
			}
		}
	}

	return $text;
}

// this function will parse all smilies...
// this should be fairly easy...
function parseAllSmilies($text) {
	global $smileyinfo;

	// loop
	if(is_array($smileyinfo)) {
		foreach($smileyinfo as $id => $arr) {
			// do the replace...
			$text = str_replace($arr['replacement'],'<img src="'.$arr['filepath'].'" alt="'.$arr['title'].'" />',$text);
		}
	}

	// return the text...
	return $text;
}

// this function will convert the smilies back...
function convertSmilies($text) {
	global $smileyinfo;

	if(is_array($smileyinfo)) {
		foreach($smileyinfo as $id => $arr) {
			$text = str_replace("&lt;img src=&quot;".$arr['filepath']."&quot; alt=&quot;".$arr['title']."&quot; /&gt;",'<img src="'.$arr['filepath'].'" alt="'.$arr['title'].'" />',$text);
		}
	}

	return $text;
}

// function that tacks on default bb code...
function addDefaultBBCode($text,$user) {
	$begin = false;
	$end = false;

	if($user['default_font']) {
		$begin .= '[font=' . $user['default_font'] . ']';
		$end = '[/font]' . $end;
	}

	if($user['default_color']) {
		$begin .= '[color=' . $user['default_color'] . ']';
		$end = '[/color]' . $end;
	}

	if($user['default_size']) {
		$begin .= '[size=' . $user['default_size'] . ']';
		$end = '[/size]' . $end;
	}

	if($begin) {
		return $begin . $text . $end;
	}

	return $text;
}

// this function puts all the functions above 
// together...
function parseMessage($text,$bbcode,$smilies,$img,$html,$bbcode2=true,$smilies2=true,$username="",$postinfo=false,$doDefault='optional') {
	global $foruminfo, $bboptions, $forumid, $userinfo;

	if($html) $text = addslashes($text);

	//$text = wordWrapper($text);

	// now do the smilies...
	// but should we parse it?
	if($smilies2 AND $smilies) {
		$text = parseAllSmilies($text);
	}

	if($html) {
		$text = htmlspecialchars($text);
		$text = convertSmilies($text);
	}

	// so let's parse the bbcode...
	// but should we parse it?
	if($bbcode2 AND $bbcode) {
		if($doDefault != 'optional' AND $doDefault) {
			$text = addDefaultBBCode($text,$postinfo);
		}

		else if(is_array($postinfo) AND $doDefault == 'optional') {
			if($postinfo['defBBCode']) {
				$text = addDefaultBBCode($text,$postinfo);
			}
		}

		$text = parseAllBBCode($text,$img,$html);
	}

	// do replacements...
	$text = replaceReplacements($text);

	// censor thread title and message
	$text = doCensors($text);

	// insert BR's
	$text = nl2br($text);

	// do {poster} and {viewer} replace things... hehe
	$text = preg_replace("|{viewer}|",$userinfo['username'],$text);
	$text = preg_replace("|{poster}|",$username,$text);
	
	// get rid of javascript...
	//$text = preg_replace("|javascript:|isU", "java_script:", $text);

	return $text;
}

?>