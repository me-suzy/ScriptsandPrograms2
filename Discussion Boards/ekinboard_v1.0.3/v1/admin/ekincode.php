<?PHP
function ekincode($message,$theme){
        $message = strip_tags($message);

	$message = str_replace(Chr(13), "<br>", $message);
    $message = preg_replace('#\[b\](.*?)\[/b\]#is', '<b>\\1</b>', $message);
    $message = preg_replace('#\[i\](.*?)\[/i\]#is', '<i>\\1</i>', $message);
	$message = preg_replace('#\[u\](.*?)\[/u\]#is', '<u>\\1</u>', $message);
	$message = preg_replace('#\[big\](.*?)\[/big\]#is', '<big>\\1</big>', $message);
	$message = preg_replace('#\[sm\](.*?)\[/sm\]#is', '<small>\\1</small>', $message);    


	$smilie_folder = 'templates/'. $theme .'/images/smilies/';

	if(file_exists($smilie_folder)) {

		$message = str_replace(':P', '<img alt="smilie for :P" title=":P" src="'
		.$smilie_folder.'tongue.gif">', $message);
		$message = str_replace(':p', '<img alt="smilie for :p" title=":p" src="'
		.$smilie_folder.'tongue.gif">', $message);

		$message = str_replace(':d', '<img alt="smilie for :d" title=":d" src="'
		.$smilie_folder.'grin.gif">', $message);
		$message = str_replace(':D', '<img alt="smilie for :D" title=":D" src="'
		.$smilie_folder.'grin.gif">', $message);

		$message = str_replace(':sleep:', '<img alt="smilie for :sleep:" title=":sleep:" src="'
		.$smilie_folder.'sleeping.gif">', $message);

		$message = str_replace(':)', '<img alt="smilie for :)" title=":)" src="'
		.$smilie_folder.'excited.gif">', $message);

		$message = str_replace(':(', '<img alt="smilie for :(" title=":(" src="'
		.$smilie_folder.'dissapointed.gif">', $message);

		$message = str_replace(':sad:', '<img alt="smilie for :sad:" title=":sad:" src="'
		.$smilie_folder.'sad.gif">', $message);

		$message = str_replace(':O', '<img alt="smilie for :O" title=":O" src="'
		.$smilie_folder.'shocked.gif">', $message);
		$message = str_replace(':o', '<img alt="smilie for :o" title=":o" src="'
		.$smilie_folder.'shocked.gif">', $message);

		$message = str_replace('-.-', '<img alt="smilie for -.-" title="-.-" src="'
		.$smilie_folder.'closedeyes.gif">', $message);

		$message = str_replace('o.o', '<img alt="smilie for o.o" title="o.o" src="'
		.$smilie_folder.'bigeyes.gif">', $message);
		$message = str_replace('O.O', '<img alt="smilie for o.o" title="o.o" src="'
		.$smilie_folder.'bigeyes.gif">', $message);
		$message = str_replace('O.o', '<img alt="smilie for o.o" title="o.o" src="'
		.$smilie_folder.'bigeyes.gif">', $message);
		$message = str_replace('o.O', '<img alt="smilie for o.o" title="o.o" src="'
		.$smilie_folder.'bigeyes.gif">', $message);
	}

$url_start = "<a target=_blank href=";
$url_close = ">";
$img_start = "<img border=0 src=";
$img_close = " alt=\"EKINboard Image\">";
$quote_start = "<center><table width=90% border=0 cellpadding=1 cellspacing=0>
		<tr><td class=quotetop>Quote</td></tr><tr><td class=quotemain style=\"overflow: auto;\">";
$quote_close = "</td></tr></table>
		<table border=0 width=100%><tr><td height=5></td></tr></table></center>";
$any = "(.*?)";

$message = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#is", "\\1<a href='http://\\2' target=\"_blank\">\\2</a>", $message);
$message = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $message);
if (eregi('http://', $message)){
$message = preg_replace("#\[url\](.*?)\[/url\]#is", '<a href="\\1" target=_blank>\\1</a>', $message);
$message = preg_replace("#\[url=$any\]$any\[/url\]#is", "<a href=\"\\1\" target=_blank>\\2</a>", $message);
} else {
$message = preg_replace("#\[url\](.*?)\[/url\]#is", '<a href="http://\\1" target=_blank>\\1</a>', $message);
$message = preg_replace("#\[url=$any\]$any\[/url\]#is", "<a href=\"http://\\1\" target=_blank>\\2</a>", $message);
}
$message = preg_replace("^\[[iI][mM][gG]\]$any\[/[iI][mM][gG]\]^","$img_start\\1$img_close",$message);
while (preg_match('#\[quote=(.*)\](.*)\[/quote\]#is', $message)){
$message = preg_replace('#\[quote=(.*?)\](.*?)\[/quote\]#is', '<center><table width=90% border=0 cellpadding=1 cellspacing=0>
		<tr><td class=quotetop>Quote - \\1</td></tr><tr><td class=quotemain>\\2</td></tr></table>
		<table border=0 width=100%><tr><td height=5></td></tr></table></center>', $message);
}




while (preg_match('#\[quote\](.*)\[/quote\]#is', $message)){
$message = preg_replace('#\[quote\](.*?)\[/quote\]#is', "$quote_start\\1$quote_close", $message);
}


	return ($message );
}

?>