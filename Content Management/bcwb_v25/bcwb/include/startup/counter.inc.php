<?
/* Get the Browser data */

if((ereg("Nav", $_SERVER["HTTP_USER_AGENT"])) || (ereg("Gold", $_SERVER["HTTP_USER_AGENT"])) || (ereg("X11", $_SERVER["HTTP_USER_AGENT"])) || (ereg("Mozilla", $_SERVER["HTTP_USER_AGENT"])) || (ereg("Netscape", $_SERVER["HTTP_USER_AGENT"])) AND (!ereg("MSIE", $_SERVER["HTTP_USER_AGENT"]) AND (!ereg("Konqueror", $_SERVER["HTTP_USER_AGENT"])))) $browser = "Netscape";
elseif(ereg("MSIE", $_SERVER["HTTP_USER_AGENT"])) $browser = "MSIE";
elseif(ereg("Lynx", $_SERVER["HTTP_USER_AGENT"])) $browser = "Lynx";
elseif(ereg("Opera", $_SERVER["HTTP_USER_AGENT"])) $browser = "Opera";
elseif(ereg("WebTV", $_SERVER["HTTP_USER_AGENT"])) $browser = "WebTV";
elseif(ereg("Konqueror", $_SERVER["HTTP_USER_AGENT"])) $browser = "Konqueror";
elseif((eregi("bot", $_SERVER["HTTP_USER_AGENT"])) || (ereg("Google", $_SERVER["HTTP_USER_AGENT"])) || (ereg("Slurp", $_SERVER["HTTP_USER_AGENT"])) || (ereg("Scooter", $_SERVER["HTTP_USER_AGENT"])) || (eregi("Spider", $_SERVER["HTTP_USER_AGENT"])) || (eregi("Infoseek", $_SERVER["HTTP_USER_AGENT"]))) $browser = "Bot";
else $browser = "Other";

/* Get the Operating System data */

if(ereg("Win", $_SERVER["HTTP_USER_AGENT"])) $os = "Windows";
elseif((ereg("Mac", $_SERVER["HTTP_USER_AGENT"])) || (ereg("PPC", $_SERVER["HTTP_USER_AGENT"]))) $os = "Mac";
elseif(ereg("Linux", $_SERVER["HTTP_USER_AGENT"])) $os = "Linux";
elseif(ereg("FreeBSD", $_SERVER["HTTP_USER_AGENT"])) $os = "FreeBSD";
elseif(ereg("SunOS", $_SERVER["HTTP_USER_AGENT"])) $os = "SunOS";
elseif(ereg("IRIX", $_SERVER["HTTP_USER_AGENT"])) $os = "IRIX";
elseif(ereg("BeOS", $_SERVER["HTTP_USER_AGENT"])) $os = "BeOS";
elseif(ereg("OS/2", $_SERVER["HTTP_USER_AGENT"])) $os = "OS/2";
elseif(ereg("AIX", $_SERVER["HTTP_USER_AGENT"])) $os = "AIX";
else $os = "Other";

/* Save on the databases the obtained values */

$reffer = urldecode (getenv("HTTP_REFERER"));
$reffer = (eregi("^".$http_path, $reffer) ? "" : $reffer);

$stat=
date("Y-m-d H:i:s")." |\t".
getenv("REMOTE_ADDR")." |\t".
$GLOBALS["REQUEST_URI"]." |\t".
$reffer." |\t".
$browser." |\t".
$os." |\t".
$SESSION_ID."\r\n";

if($_GET["action"]!="stats") {
$fp = @fopen($root_path."log/".date("Y.m.d").".log", "a");
if (flock($fp, LOCK_EX)) { 
   fwrite($fp, $stat);
   flock($fp, LOCK_UN); 
}
fclose($fp);
}

?>