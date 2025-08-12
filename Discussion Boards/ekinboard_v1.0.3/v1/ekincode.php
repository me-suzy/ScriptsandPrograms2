<?PHP
function ekincode($message,$theme){


    $message = htmlspecialchars($message);

$badword_sql = mysql_query("SELECT * FROM wordfilters");
while($word_row = mysql_fetch_array($badword_sql)){
    $message = eregi_replace($word_row[word],$word_row[replacement],$message);
}

	$message = str_replace("$", "&#36;", $message);
	$message = str_replace(Chr(13), " <br> ", $message);
while (preg_match('#\[hilight\](.*?)\[/hilight\]#is', $message)){
    $message = preg_replace('#\[hilight\](.*?)\[/hilight\]#is', '<span class=hilight_str>\\1</span>', $message);
}
while (preg_match('#\[b\](.*?)\[/b\]#is', $message)){
    $message = preg_replace('#\[b\](.*?)\[/b\]#is', '<b>\\1</b>', $message);
}
while (preg_match('#\[i\](.*?)\[/i\]#is', $message)){
    $message = preg_replace('#\[i\](.*?)\[/i\]#is', '<i>\\1</i>', $message);
}
while (preg_match('#\[u\](.*?)\[/u\]#is', $message)){
	$message = preg_replace('#\[u\](.*?)\[/u\]#is', '<u>\\1</u>', $message);
}
	$message = preg_replace('#\[big\](.*?)\[/big\]#is', '<big>\\1</big>', $message);
	$message = preg_replace('#\[sm\](.*?)\[/sm\]#is', '<small>\\1</small>', $message);


	$smilie_folder = 'templates/'. $theme .'/images/smilies/';

	if(file_exists($smilie_folder)) {

		$message = str_replace(':P', '<img alt="smilie for :P" title=":P" src="'
		.$smilie_folder.'tongue.gif"> ', $message);
		$message = str_replace(':p', '<img alt="smilie for :p" title=":p" src="'
		.$smilie_folder.'tongue.gif"> ', $message);

		$message = str_replace(':d', ' <img alt="smilie for :d" title=":d" src="'
		.$smilie_folder.'grin.gif">  ', $message);
		$message = str_replace(':D', '<img alt="smilie for :D" title=":D" src="'
		.$smilie_folder.'grin.gif"> ', $message);

		$message = str_replace(':sleep:', '<img alt="smilie for :sleep:" title=":sleep:" src="'
		.$smilie_folder.'sleeping.gif"> ', $message);

		$message = str_replace(':)', '<img alt="smilie for :)" title=":)" src="'
		.$smilie_folder.'excited.gif"> ', $message);

		$message = str_replace(':(', '<img alt="smilie for :(" title=":(" src="'
		.$smilie_folder.'dissapointed.gif"> ', $message);

		$message = str_replace(':sad:', '<img alt="smilie for :sad:" title=":sad:" src="'
		.$smilie_folder.'sad.gif"> ', $message);

		$message = str_replace(':O', '<img alt="smilie for :O" title=":O" src="'
		.$smilie_folder.'shocked.gif"> ', $message);
		$message = str_replace(':o', ' <img alt="smilie for :o" title=":o" src="'
		.$smilie_folder.'shocked.gif"> ', $message);

		$message = str_replace('-.-', '<img alt="smilie for -.-" title="-.-" src="'
		.$smilie_folder.'closedeyes.gif"> ', $message);

		$message = str_replace('o.o', '<img alt="smilie for o.o" title="o.o" src="'
		.$smilie_folder.'bigeyes.gif"> ', $message);
		$message = str_replace('O.O', '<img alt="smilie for o.o" title="o.o" src="'
		.$smilie_folder.'bigeyes.gif"> ', $message);
		$message = str_replace('O.o', '<img alt="smilie for o.o" title="o.o" src="'
		.$smilie_folder.'bigeyes.gif"> ', $message);
		$message = str_replace('o.O', '<img alt="smilie for o.o" title="o.o" src="'
		.$smilie_folder.'bigeyes.gif"> ', $message);
	}

$url_start = "<a target=_blank href=";
$url_close = ">";
$img_start = "<img border=0 src=";
$img_close = " alt=\"EKINboard Image\">";
$quote_start = "<center><table width=90% border=0 cellpadding=1 cellspacing=0>
		<tr><td class=quotetop>Quote</td></tr><tr><td class=quotemain style=\"overflow: auto;\">";
$quote_close = "</td></tr></table>
		<table border=0 width=100%><tr><td height=5></td></tr></table></center>";
$code_start = "<center><table width=90% border=0 cellpadding=1 cellspacing=0>
		<tr><td class=codetop>Code</td></tr><tr><td class=codemain style=\"overflow: auto;\">";
$code_close = "</td></tr></table>
		<table border=0 width=100%><tr><td height=5></td></tr></table></center>";
$any = "(.*?)";

if (eregi('http://', $message)){
$message = preg_replace("#\[url\](.*?)\[/url\]#is", '<a href="\\1" target=_blank>\\1</a>', $message);
$message = preg_replace("#\[url=$any\]$any\[/url\]#is", "<a href=\"\\1\" target=_blank>\\2</a>", $message);
} else {
$message = preg_replace("#\[url\](.*?)\[/url\]#is", '<a href="http://\\1" target=_blank>\\1</a>', $message);
$message = preg_replace("#\[url=$any\]$any\[/url\]#is", "<a href=\"http://\\1\" target=_blank>\\2</a>", $message);
}

	$message = preg_replace("#\[img\]$any\[/img\]#is","$img_start\\1$img_close",$message);	
	$message = preg_replace("#\[img=$any\]#is","$img_start\\1$img_close",$message);

while (preg_match('#\[quote=(.*)\](.*)\[/quote\]#is', $message)){
$message = preg_replace('#\[quote=(.*?)\](.*?)\[/quote\]#is', '<center><table width=90% border=0 cellpadding=1 cellspacing=0>
		<tr><td class=quotetop>Quote - \\1</td></tr><tr><td class=quotemain style=\"overflow: auto;\">\\2</td></tr></table>
		<table border=0 width=100%><tr><td height=5></td></tr></table></center>', $message);
}




while (preg_match('#\[quote\](.*)\[/quote\]#is', $message)){
	$message = preg_replace('#\[quote\](.*?)\[/quote\]#is', "$quote_start\\1$quote_close", $message);
}

while (preg_match('#\[code\](.*)\[/code\]#is', $message)){
	$message = preg_replace('#\[code\](.*?)\[/code\]#is', "$code_start\\1$code_close", $message);
}
	return ($message );
}


function pmcode($message){
    $message = htmlspecialchars($message);

$badword_sql = mysql_query("SELECT * FROM wordfilters");
while($word_row = mysql_fetch_array($badword_sql)){
    $message = eregi_replace($word_row[word],$word_row[replacement],$message);
}

$redtable_start = "<center><table width=90% class=pm_codetop>
		<tr><td style=\"overflow: auto;\">";
$redtable_close = "</td></tr></table>
		<table border=0 width=100%><tr><td height=5></td></tr></table></center>";

	$message = str_replace("$", "&#36;", $message);
	$message = str_replace(Chr(13), " <br> ", $message);

while (preg_match('#\[redtable\](.*?)\[/redtable\]#is', $message)){
    $message = preg_replace('#\[redtable\](.*?)\[/redtable\]#is', $redtable_start .'\\1'. $redtable_close, $message);
}
while (preg_match('#\[hilight\](.*?)\[/hilight\]#is', $message)){
    $message = preg_replace('#\[hilight\](.*?)\[/hilight\]#is', '<span class=hilight_str>\\1</span>', $message);
}
while (preg_match('#\[b\](.*?)\[/b\]#is', $message)){
    $message = preg_replace('#\[b\](.*?)\[/b\]#is', '<b>\\1</b>', $message);
}
while (preg_match('#\[i\](.*?)\[/i\]#is', $message)){
    $message = preg_replace('#\[i\](.*?)\[/i\]#is', '<i>\\1</i>', $message);
}
while (preg_match('#\[u\](.*?)\[/u\]#is', $message)){
	$message = preg_replace('#\[u\](.*?)\[/u\]#is', '<u>\\1</u>', $message);
}
	$message = preg_replace('#\[big\](.*?)\[/big\]#is', '<big>\\1</big>', $message);
	$message = preg_replace('#\[sm\](.*?)\[/sm\]#is', '<small>\\1</small>', $message);

$url_start = "<a target=_blank href=";
$url_close = ">";
$img_start = "<img border=0 src=";
$img_close = " alt=\"EKINboard Image\">";
$quote_start = "<center><table width=90% border=0 cellpadding=1 cellspacing=0>
		<tr><td class=pm_codetop>Quote</td></tr><tr><td class=pm_codecontent style=\"overflow: auto;\">";
$quote_close = "</td></tr></table>
		<table border=0 width=100%><tr><td height=5></td></tr></table></center>";
$code_start = "<center><table width=90% border=0 cellpadding=1 cellspacing=0>
		<tr><td class=pm_codetop>Code</td></tr><tr><td class=pm_codecontent style=\"overflow: auto;\">";
$code_close = "</td></tr></table>
		<table border=0 width=100%><tr><td height=5></td></tr></table></center>";
$any = "(.*?)";



if (eregi('http://', $message)){
	$message = preg_replace("#\[url\](.*?)\[/url\]#is", '<a href="\\1" target=_blank>\\1</a>', $message);
	$message = preg_replace("#\[url=$any\]$any\[/url\]#is", "<a href=\"\\1\" target=_blank>\\2</a>", $message);
} else {
	$message = preg_replace("#\[url\](.*?)\[/url\]#is", '<a href="http://\\1" target=_blank>\\1</a>', $message);
	$message = preg_replace("#\[url=$any\]$any\[/url\]#is", "<a href=\"http://\\1\" target=_blank>\\2</a>", $message);
}



while (preg_match('#\[quote=(.*)\](.*)\[/quote\]#is', $message)){
	$message = preg_replace('#\[quote=(.*?)\](.*?)\[/quote\]#is', '<center><table width=90% border=0 cellpadding=1 cellspacing=0>
		<tr><td class=pm_codetop>Quote - \\1</td></tr><tr><td class=pm_codecontent>\\2</td></tr></table>
		<table border=0 width=100%><tr><td height=5></td></tr></table></center>', $message);
}


	$message = preg_replace("#\[img\]$any\[/img\]#is","$img_start\\1$img_close",$message);
	$message = preg_replace("#\[img=$any\]#is","$img_start\\1$img_close",$message);

while (preg_match('#\[quote\](.*)\[/quote\]#is', $message)){
	$message = preg_replace('#\[quote\](.*?)\[/quote\]#is', "$quote_start\\1$quote_close", $message);
}

while (preg_match('#\[code\](.*)\[/code\]#is', $message)){
	$message = preg_replace('#\[code\](.*?)\[/code\]#is', "$code_start\\1$code_close", $message);
}
	return ($message );
}

?>
