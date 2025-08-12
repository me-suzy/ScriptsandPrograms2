<?
$path =  __FILE__;
$path = preg_replace( "'\\\searchbot\.php'", "", $path);
$path = preg_replace( "'/searchbot\.php'", "", $path);

include($path."/searchbot_config.php");
$agent = $_SERVER['HTTP_USER_AGENT'];
/*This Recognizes:
Ask Jeeves
Atomz
BlackWidow
BoxSeaBot
bSpider (Japenese indexing)
ChristCrawler (Christian Indexing) - christcentral.com
Combine - www.ub2.lu.se
CoolBot (German Search Engine) - suchmaschine21.de
Cruiser - krstarica.com
Deep Index
Desert Realm - SciFi - www.desertrealm.com
Digger - Diggit Search Engine - www.diggit.com
Digital Integrity Robot - www.digital-integrity.com/robotinfo.html
DNAbot - xx.dnainc.co.jp - Japenese
ExactSeek.com
Fido - planetsearch.com
FreeCrawl - Used for EuroSeek - freeside.net
geckobot - http://www.geckobot.com/
Google - www.google.com
Green Research, Inc. - SPAMbot
Gulliver - nothernlight.com
InfoSeek - infoseek.com
Inktomi
IsraeliSearch - www.idc.ac.il/Sandbag/
Lycos - lycos.cs.cmu.edu
Mac Finder - SPAM bot
Marvin - infoseek.de
MSNbot
Muncher - www.goodlookingcooking.co.uk - SearchBot seeking out cooking pages.
NDSpider - www.nationaldirectory.com
NetScoop - www-a2k.is.tokushima-u.ac.jp/search
NetSearch - netsearch.org
PSBot - picsearch.com
Rambler - rambler.ru; Russian search site
River Valley - SPAM bot
Scooter - AltaVistas Indexing Agent - www.altavista.com
Seeker - Lookseek.com
SmartSpider - Knowledgebase Spider - www.engsoftware.com/robots.htm
SpeedySpider - EntireWeb.com
SpiderMan - Yahoo
Tarantula - www.nathan.de
Inktomi - HotBot - www.inktomi.com
Wanderer - 	www.mit.edu/people/mkgray/net - MIT's bot to measure the growth of the web.
WebZinger - www.imaginon.com
Yahoo - Yahoo.com
Yellowpet - Yellowpet.com
*/
$arr = array("ask jeeves", "atomz", "blackwidow", "bot", "boxseabot", "bspider", "christcrawler", "combine", "coolbot", "cruiser", "desertrealm", "deepindex", "digger", "diibot", "dnabot", "exactseek", "fast-webcrawler", "fido", "freecrawl", "geckobot", "green research", "googlebot", "gulliver", "turnitinbot", "ia_archiver", "infoseek", "inktomisearch", "israelisearch", "lycos", "mac finder", "marvin", "msnbot", "muncher", "ndspider", "netscoop", "netsearch", "psbot", "rambler.ru", "river valley", "robot", "scooter", "seeker", "slurp", "smartspider", "speedy", "spiderman", "tarantula", "wanderer", "wz101", "yahoo", "yellowpet");
foreach ($arr as $bot) {
	if (eregi($bot, $agent)) {
		$bot_here = "1";
		$bot_name = ucwords("$bot");
	}
}
if ($bot_here == "1") {
  $credit = "<a href=\"http://jonathan.charpie.com/scripts.php?dir=342727009JCSALT-12\">SearchBot detection by: Jonathan Charpie - 342727009JCSALT-05</a>; Keep track of your <a href=\"http://www.collectandtrack.com/?dir=342727009JCSALT-12\">Pez Collection</a> here!   <a href=\"http://host.charpie.com/?dir=342727009JCSALT-all\"> Compare All Hosting Plans</a> |     <a href=\"http://host.charpie.com/asp_hosting.php?dir=342727009JCSALT-all\">ASP Hosting</a> | <a href=\"http://host.charpie.com/cheap_domain_hosting.php?dir=342727009JCSALT-all\">Cheap Domain Hosting</a> | <a href=\"http://host.charpie.com/cheap_internet_hosting.php?dir=342727009JCSALT-all\">Cheap Internet Hosting</a> | <a href=\"http://host.charpie.com/company_web_site_hosting.php?dir=342727009JCSALT-all\">Company Web Site Hosting</a> | <a href=\"http://host.charpie.com/index.php?dir=342727009JCSALT-all\">Compare All Hosting Plans</a> | <a href=\"http://host.charpie.com/domain_name_hosting.php?dir=342727009JCSALT-all\">Domain Name Hosting</a> | <a href=\"http://host.charpie.com/domain_registration_hosting.php?dir=342727009JCSALT-all\">Domain Registration Hosting</a> | <a href=\"http://host.charpie.com/ecommerce_web_hosting.php?dir=342727009JCSALT-all\">Ecommerce Web Hosting</a> | <a href=\"http://host.charpie.com/email_domain_hosting.php?dir=342727009JCSALT-all\">Email Domain Hosting</a> | <a href=\"http://host.charpie.com/free_domain_hosting.php?dir=342727009JCSALT-all\">Free Domain Hosting</a> | <a href=\"http://host.charpie.com/hosting_domain.php?dir=342727009JCSALT-all\">Hosting a Domain</a> | <a href=\"http://host.charpie.com/linux_hosting.php?dir=342727009JCSALT-all\">Linux Hosting</a> | <a href=\"http://host.charpie.com/multiple_domain_hosting.php?dir=342727009JCSALT-all\">Multiple Domain Hosting</a> | <a href=\"http://host.charpie.com/multiple_domain_web_hosting.php?dir=342727009JCSALT-all\">Multiple Domain Web Hosting</a> | <a href=\"http://host.charpie.com/php_hosting.php?dir=342727009JCSALT-all\">PHP Hosting</a> | <a href=\"http://host.charpie.com/unlimited_domain_hosting.php?dir=342727009JCSALT-all\">Unlimited Domain Hosting</a> | <a href=\"http://host.charpie.com/unlimited_space_domain_hosting.php?dir=342727009JCSALT-all\">Unlimited Space Domain Hosting</a> | <a href=\"http://host.charpie.com/virtual_domain_hosting.php?dir=342727009JCSALT-all\">Virtual Domain Hosting</a> | <a href=\"http://host.charpie.com/web_hosting_company.php?dir=342727009JCSALT-all\">Web Hosting Company</a> | <a href=\"http://host.charpie.com/web_hosting_provider.php?dir=342727009JCSALT-all\">Web Hosting Provider</a>
</p>";
  if($_SERVER['QUERY_STRING'] != ""){
	  $u = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
  } 
  else{
	  $u = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
  } 
  
 echo $credit;
 $delete = array("http://", "www.");
 $u = str_replace($delete, "", $u);
 $prefix = "http://";
 $url = $prefix.$u;
 $today = date("l, M j\<\s\u\p\>S\<\/\s\u\p\> Y - g:i a"); 
 $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
 
  if($logit){
	  $agent = ucwords(substr($agent, 0, strpos($agent, '(')));
      $write = "<FONT COLOR=\"#AA0000\">$today</FONT><br><I>Bot:</I> ". $agent." (<font color=\"#008080\"><small>".$hostname."</small></font>)";
      $write .= "<br><I>Crawled:</I> <small><A HREF=\"$url\">$url</A></small><br><br>\r\n";
	  $fd = fopen ($path."/".$logfile, "a");
	  $write = stripslashes($write);		
	  fwrite ($fd, $write);	  
  }
  if ($agent != '') {
 		$mail_subject = "$agent - $bot_name - Detected";
  } else if ($agent == '') {
  		$mail_subject = "$bot_name - Detected";
  }
  if($emailit){
	    $message = "Date: $today\n\n$agent (".$hostname.")\n";
		$message .= "Crawled: $url\n\n\nRegards\nSearchBot Activity Robot";
		$headers = "From: SearchBot Activity <$youremail>\r\nReply-To: $youremail\r\n";
		mail($youremail, $mail_subject, $message, $headers); 
  }
} 
?>