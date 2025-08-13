<?php

//        !!!!!!!!!!!!!!! DO NOT PUT SLASH AT THE END OF THE PATH'S STRINGS !!!!!!!!!!!!!!!

// Path to CGi-BIN of the domain
$cgipath="/home/www/........domain.com/cgi-bin";

// Path to logfiles of the site, read manual cariefully about it
$logpath="/home/www/........domain.com/cgi-bin/fet/secured/logfiles";

// Path to toplists templates files
$templpath="/home/www/........domain.com/templates";

// Exout URL to send overclicking traffic
$exout = "http://www.........domain.com/exout.html";

// Default URL to send traffic in case of errorrs and bot filtering.

$defurl= "http://www.........domain.com/error.html";

// Path to html files of the domain
$htmlpath="/home/www/........domain.com/html";

// CJ domain name without www.
$domain="domain.com";

// Path to perl
$perlpath="/usr/bin/perl";

// Path to php
$phppath="/usr/local/bin/php";

// Path to DATE service
$datepath="/bin/date";

// Path to SH

$shpath="/bin/sh";

// Path to FIND utility

$findpath="/usr/bin/find";

// Path to RM utility

$rmpath="/bin/rm";


// ---------------------------------------- DO NOT CHANGE ANYTHING BELOW THIS LINE -------------------------------------


function convert($source, $dest, $perm) {
  global $cgipath, $logpath, $templpath, $exout, $defurl, $perlpath, $phppath, $datepath, $findpath, $htmlpath, $domain, $fetcjpath, $fetcjerrorurl, $fetcjmainurl, $shpath, $templpath, $rmpath;

  echo "Converting $source to $dest\n";
  $fp = join("",@file($source));
  $fp=str_replace("\r\n","\n",$fp);
  $fp=str_replace("FET_CJ_PATH",$fetcjpath,$fp);
  $fp=str_replace("FET_CJ_DEFAULT_URL",$defurl,$fp);
  $fp=str_replace("FET_CJ_EXOUT_URL",$defurl,$fp);
  $fp=str_replace("FET_CJ_HTML_PATH",$htmlpath,$fp);
  $fp=str_replace("FET_CJ_MAIN_URL",$fetcjmainurl,$fp);
  $fp=str_replace("FET_CJ_DOMAIN",$domain,$fp);
  $fp=str_replace("FET_CJ_LOG_PATH",$logpath,$fp);
  $fp=str_replace("PATH_TO_DATE",$datepath,$fp);
  $fp=str_replace("PATH_TO_PERL",$perlpath,$fp);
  $fp=str_replace("PATH_TO_PHP",$phppath,$fp);
  $fp=str_replace("PATH_TO_FIND",$findpath,$fp);
  $fp=str_replace("PATH_TO_SH",$shpath,$fp);
  $fp=str_replace("FET_CJ_TOPLIST_TEMPLATES_PATH",$templpath,$fp);
  $fp=str_replace("PATH_TO_RM",$rmpath,$fp);
  $fpl=@fopen($dest,"w");
  @fputs($fpl, $fp);
  @fclose($fpl);
  @chmod($dest,$perm);
}

echo"Creating FET catalog $cgipath/fet\n\n";
$fetcjpath = "$cgipath/fet";
if (!file_exists($fetcjpath)) if (!@mkdir( $fetcjpath, 0755)) {echo "Error creating $fetcjpath catalog\n"; exit; }
@chmod($fetcjpath,0755);
$fetcjerrorurl = "http://$domain/error.html";
$fetcjmainurl = "http://$domain";

if (!file_exists("$fetcjpath/secured")){
     echo "Creating $fetcjpath/secured\n";
     if (!@mkdir( "$fetcjpath/secured", 0755)) {echo "Error creating $fetcjpath/secured catalog\n"; exit;}
     @chmod("$fetcjpath/secured", 0755);
}
if (!file_exists("$fetcjpath/secured/perm")){
     echo "Creating $fetcjpath/secured/perm\n";
     if (!@mkdir( "$fetcjpath/secured/perm", 0777)) {echo "Error creating $fetcjpath/secured/perm catalog\n"; exit;}
     @chmod("$fetcjpath/secured/perm", 0777);
}
if (!file_exists("$fetcjpath/secured/permset")){
     echo "Creating $fetcjpath/secured/permset\n";
     if (!@mkdir( "$fetcjpath/secured/permset", 0777)) {echo "Error creating $fetcjpath/secured/permset catalog\n"; exit;}
     @chmod("$fetcjpath/secured/permset", 0777);
}
if (!file_exists("$fetcjpath/secured/fetdata")){
     echo "Creating $fetcjpath/secured/fetdata\n";
     if (!@mkdir( "$fetcjpath/secured/fetdata", 0777)) {echo "Error creating $fetcjpath/secured/fetdata catalog\n"; exit;}
     @chmod("$fetcjpath/secured/fetdata", 0777);
}
if (!file_exists("$fetcjpath/secured/backup")){
     echo "Creating $fetcjpath/secured/backup\n";
     if (!@mkdir( "$fetcjpath/secured/backup", 0777)) {echo "Error creating $fetcjpath/secured/backup catalog\n"; exit;}
     @chmod("$fetcjpath/secured/backup", 0777);
}
if (!file_exists("$fetcjpath/secured/logfiles")){
     echo "Creating $fetcjpath/secured/logfiles\n";
     if (!@mkdir( "$fetcjpath/secured/logfiles", 0777)) {echo "Error creating $fetcjpath/secured/logfiles catalog\n"; exit;}
     @chmod("$fetcjpath/secured/logfiles", 0777);
}
if (!file_exists("$fetcjpath/secured/archive")){
     echo "Creating $fetcjpath/secured/archive\n";
     if (!@mkdir( "$fetcjpath/secured/archive", 0777)) {echo "Error creating $fetcjpath/secured/archive catalog\n"; exit;}
     @chmod("$fetcjpath/secured/archive", 0777);
}
if (!file_exists("$templpath")){
     echo "Creating $templpath\n";
     if (!@mkdir( "$templpath", 0777)) {echo "Error creating $templpath catalog\n"; exit;}
     @chmod("$templpath", 0777);
}
$log2 = $logpath . "/users";
$ex_log=explode("/",$log2);
$tp='';
foreach($ex_log as $log_el){
        $tp.="/$log_el";
        if(!file_exists($tp)){
            @mkdir("$tp",0777);
            @chmod("$tp",0777);
        }
}
if (!file_exists("$logpath/users")){
     echo "Creating $logpath/users\n";
     if (!@mkdir( "$logpath/users", 0777)) {echo "Error creating $logpath/users catalog\n"; exit;}
     @chmod("$logpath", 0777);
}

if (!file_exists("$htmlpath/fetimg")){
     echo "Creating $htmlpath/fetimg\n";
     if (!@mkdir( "$htmlpath/fetimg", 0755)) {echo "Error creating $htmlpath/fetimg catalog\n"; exit;}
     @chmod("$htmlpath/fetimg", 0755);
}

echo "Copying fetmaxset.txt to $fetcjpath/secured/fetdata/fetmaxset\n";
@copy("files/fetmaxset.txt","$fetcjpath/secured/fetdata/fetmaxset");
if(!file_exists("$fetcjpath/secured/fetdata/fetmaxset")){ echo "Error copying fetmaxset.txt\n"; exit;}
@chmod("$fetcjpath/secured/fetdata/fetmaxset",0777);

echo "Copying fetmaximums.txt to $fetcjpath/secured/fetdata/fetmaximums\n";
@copy("files/fetmaximums.txt","$fetcjpath/secured/fetdata/fetmaximums");
if(!file_exists("$fetcjpath/secured/fetdata/fetmaximums")){ echo "Error copying fetmaximums.txt\n"; exit;}
@chmod("$fetcjpath/secured/fetdata/fetmaximums",0777);

echo "Copying anticheat.txt to $fetcjpath/secured/fetdata/anticheat\n";
@copy("files/anticheat.txt","$fetcjpath/secured/fetdata/anticheat");
if(!file_exists("$fetcjpath/secured/fetdata/anticheat")){ echo "Error copying anticheat.txt\n"; exit;}
@chmod("$fetcjpath/secured/fetdata/anticheat",0777);

echo "Copying fetsettings.txt to $fetcjpath/secured/fetdata/fetsettings\n";
@copy("files/fetsettings.txt","$fetcjpath/secured/fetdata/fetsettings");
if(!file_exists("$fetcjpath/secured/fetdata/fetsettings")){ echo "Error copying fetsettings.txt\n"; exit;}
@chmod("$fetcjpath/secured/fetdata/fetsettings",0777);

echo "Copying fetmail.txt to $fetcjpath/secured/fetdata/fetmail\n";
@copy("files/fetmail.txt","$fetcjpath/secured/fetdata/fetmail");
if(!file_exists("$fetcjpath/secured/fetdata/fetmail")){ echo "Error copying fetmail.txt\n"; exit;}
@chmod("$fetcjpath/secured/fetdata/fetmail",0777);

echo "Copying fetadminstate.txt to $fetcjpath/secured/fetdata/fetadminstate\n";
@copy("files/fetadminstate.txt","$fetcjpath/secured/fetdata/fetadminstate");
if(!file_exists("$fetcjpath/secured/fetdata/fetadminstate")){ echo "Error copying fetadminstate.txt\n"; exit;}
@chmod("$fetcjpath/secured/fetdata/fetadminstate",0777);

echo "Copying blacklist.txt to $fetcjpath/secured/fetdata/blacklist\n";
@copy("files/blacklist.txt","$fetcjpath/secured/fetdata/blacklist");
if(!file_exists("$fetcjpath/secured/fetdata/blacklist")){ echo "Error copying blacklist.txt\n"; exit;}
@chmod("$fetcjpath/secured/fetdata/blacklist",0777);

echo "Copying fetconsole.txt to $fetcjpath/secured/fetdata/fetconsole\n";
@copy("files/fetconsole.txt","$fetcjpath/secured/fetdata/fetconsole");
if(!file_exists("$fetcjpath/secured/fetdata/fetconsole")){ echo "Error copying fetconsole.txt\n"; exit;}
@chmod("$fetcjpath/secured/fetdata/fetconsole",0777);

echo "Copying fetevents.txt to $fetcjpath/secured/fetdata/fetevents\n";
@copy("files/fetevents.txt","$fetcjpath/secured/fetdata/fetevents");
if(!file_exists("$fetcjpath/secured/fetdata/fetevents")){ echo "Error copying fetevents.txt\n"; exit;}
@chmod("$fetcjpath/secured/fetdata/fetevents",0777);

echo "Copying fetlinks.txt to $fetcjpath/secured/fetdata/fetlinks\n";
@copy("files/fetlinks.txt","$fetcjpath/secured/fetdata/fetlinks");
if(!file_exists("$fetcjpath/secured/fetdata/fetlinks")){ echo "Error copying fetlinks.txt\n"; exit;}
@chmod("$fetcjpath/secured/fetdata/fetlinks",0777);

echo "Copying fetpages.txt to $fetcjpath/secured/fetdata/fetpages\n";
@copy("files/fetpages.txt","$fetcjpath/secured/fetdata/fetpages");
if(!file_exists("$fetcjpath/secured/fetdata/fetpages")){ echo "Error copying fetpages.txt\n"; exit;}
@chmod("$fetcjpath/secured/fetdata/fetpages",0777);

echo "Copying fettop.txt to $fetcjpath/secured/fetdata/fettop\n";
@copy("files/fettop.txt","$fetcjpath/secured/fetdata/fettop");
if(!file_exists("$fetcjpath/secured/fetdata/fettop")){ echo "Error copying fettop.txt\n"; exit;}
@chmod("$fetcjpath/secured/fetdata/fettop",0777);

echo "Copying fettoptimes.txt to $fetcjpath/secured/fetdata/fettoptimes\n";
@copy("files/fettoptimes.txt","$fetcjpath/secured/fetdata/fettoptimes");
if(!file_exists("$fetcjpath/secured/fetdata/fettoptimes")){ echo "Error copying fettoptimes.txt\n"; exit;}
@chmod("$fetcjpath/secured/fetdata/fettoptimes",0777);

echo "Copying fetmembers2.txt to $fetcjpath/secured/fetdata/fetmembers2\n";
@copy("files/fetmembers2.txt","$fetcjpath/secured/fetdata/fetmembers2");
if(!file_exists("$fetcjpath/secured/fetdata/fetmembers2")){ echo "Error copying fetmembers2.txt\n"; exit;}
@chmod("$fetcjpath/secured/fetdata/fetmembers2",0777);

echo "Copying fetmembers3.txt to $fetcjpath/secured/fetdata/fetmembers3\n";
@copy("files/fetmembers3.txt","$fetcjpath/secured/fetdata/fetmembers3");
if(!file_exists("$fetcjpath/secured/fetdata/fetmembers3")){ echo "Error copying fetmembers3.txt\n"; exit;}
@chmod("$fetcjpath/secured/fetdata/fetmembers3",0777);

echo "Copying new-fetmembers1.txt to $fetcjpath/secured/fetdata/new-fetmembers1\n";
@copy("files/new-fetmembers1.txt","$fetcjpath/secured/fetdata/new-fetmembers1");
if(!file_exists("$fetcjpath/secured/fetdata/new-fetmembers1")){ echo "Error copying new-fetmembers1.txt\n"; exit;}
@chmod("$fetcjpath/secured/fetdata/new-fetmembers1",0777);

echo "Copying new-fetmembers2.txt to $fetcjpath/secured/fetdata/new-fetmembers2\n";
@copy("files/new-fetmembers2.txt","$fetcjpath/secured/fetdata/new-fetmembers2");
if(!file_exists("$fetcjpath/secured/fetdata/new-fetmembers2")){ echo "Error copying new-fetmembers2.txt\n"; exit;}
@chmod("$fetcjpath/secured/fetdata/new-fetmembers2",0777);

echo "Copying rules.txt to $fetcjpath/secured/fetdata/rules\n";
@copy("files/rules.txt","$fetcjpath/secured/fetdata/rules");
if(!file_exists("$fetcjpath/secured/fetdata/rules")){ echo "Error copying rules.txt\n"; exit;}
@chmod("$fetcjpath/secured/fetdata/rules",0777);

echo "Copying wmpage.txt to $fetcjpath/secured/fetdata/wmpage\n\n";
@copy("files/wmpage.txt","$fetcjpath/secured/fetdata/wmpage");
if(!file_exists("$fetcjpath/secured/fetdata/wmpage")){ echo "Error copying wmpage.txt\n"; exit;}
@chmod("$fetcjpath/secured/fetdata/wmpage",0777);

echo "Copying ttoplist.html to $templpath/ttoplist.html\n";
@copy("files/ttoplist.html","$templpath/ttoplist.html");
if(!file_exists("$templpath/ttoplist.html")){ echo "Error copying ttoplist.html\n"; exit;}
@chmod("$templpath/ttoplist.html",0777);

echo "Copying error.html to $htmlpath/error.html\n\n";
@copy("files/error.html","$htmlpath/error.html");
if(!file_exists("$htmlpath/error.html")){ echo "Error copying error.html\n"; exit;}
@chmod("$htmlpath/error.html",0777);

echo "Copying green.gif to $htmlpath/fetimg/green.gif\n";
@copy("files/green.gif","$htmlpath/fetimg/green.gif");
if(!file_exists("$htmlpath/fetimg/green.gif")){ echo "Error copying green.gif\n"; exit;}
@chmod("$htmlpath/fetimg/green.gif",0644);

echo "Copying black.gif to $htmlpath/fetimg/black.gif\n";
@copy("files/black.gif","$htmlpath/fetimg/black.gif");
if(!file_exists("$htmlpath/fetimg/black.gif")){ echo "Error copying black.gif\n"; exit;}
@chmod("$htmlpath/fetimg/black.gif",0644);

echo "Copying gray.gif to $htmlpath/fetimg/gray.gif\n";
@copy("files/gray.gif","$htmlpath/fetimg/gray.gif");
if(!file_exists("$htmlpath/fetimg/gray.gif")){ echo "Error copying gray.gif\n"; exit;}
@chmod("$htmlpath/fetimg/gray.gif",0644);

echo "Copying red.gif to $htmlpath/fetimg/red.gif\n";
@copy("files/red.gif","$htmlpath/fetimg/red.gif");
if(!file_exists("$htmlpath/fetimg/red.gif")){ echo "Error copying red.gif\n"; exit;}
@chmod("$htmlpath/fetimg/red.gif",0644);

echo "Copying out.exe to $htmlpath/out.php\n";
@copy("files/out.exe","$htmlpath/out.php");
if(!file_exists("$htmlpath/out.php")){ echo "Error copying out.exe\n"; exit;}
@chmod("$htmlpath/out.php",0644);

echo "Copying index_html.exe to $htmlpath/index_html.php\n";
@copy("files/index_html.exe","$htmlpath/index_html.php");
if(!file_exists("$htmlpath/index_html.php")){ echo "Error copying index_html.exe\n"; exit;}
@chmod("$htmlpath/index_html.php",0644);

echo "Copying index_php.exe to $htmlpath/index_php.php\n";
@copy("files/index_php.exe","$htmlpath/index_php.php");
if(!file_exists("$htmlpath/index_php.php")){ echo "Error copying index_php.exe\n"; exit;}
@chmod("$htmlpath/index_php.php",0644);

echo "Copying index_include.exe to $htmlpath/index_include.php\n";
@copy("files/index_include.exe","$htmlpath/index_include.php");
if(!file_exists("$htmlpath/index_include.php")){ echo "Error copying index_include.exe\n"; exit;}
@chmod("$htmlpath/index_include.php",0644);

echo "Copying mastercron.exe to $fetcjpath/secured/mastercron.php\n";
@copy("files/mastercron.exe","$fetcjpath/secured/mastercron.php");
if(!file_exists("$fetcjpath/secured/mastercron.php")){ echo "Error copying mastercron.exe\n"; exit;}
@chmod("$fetcjpath/secured/mastercron.php",0644);

convert( "files/add.cgi", "$fetcjpath/add.cgi", 0755 );
convert( "files/cgi-lib.pl", "$fetcjpath/cgi-lib.pl", 0755 );
convert( "files/console.exe", "$htmlpath/console.php", 0644 );
convert( "files/cgi-lib.pl", "$fetcjpath/secured/cgi-lib.pl", 0755 );
convert( "files/mastermain.cgi", "$fetcjpath/secured/mastermain.cgi", 0755 );
convert( "files/mastertopcron.cgi", "$fetcjpath/secured/mastertopcron.cgi", 0755 );
convert( "files/mastertop.cgi", "$fetcjpath/secured/mastertop.cgi", 0755 );
convert( "files/mainset.php", "$fetcjpath/secured/mainset.php", 0644 );
convert( "files/mainset.php", "$htmlpath/mainset.php", 0644 );
convert( "files/cleaner.sh", "$fetcjpath/secured/cleaner.sh", 0755 );
convert( "files/fetadminsettings.txt","$fetcjpath/secured/fetdata/fetadminsettings", 0777);
convert( "files/fetmembers1.txt","$fetcjpath/secured/fetdata/fetmembers1", 0777);
convert( "files/fetexout.txt","$fetcjpath/secured/fetdata/fetexout", 0777);

echo"Creating stat files\n";

echo "\t- $fetcjpath/secured/fetdata/fetc\n";
@touch("$fetcjpath/secured/fetdata/fetc");
@chmod("$fetcjpath/secured/fetdata/fetc",0777);

echo "\t- $fetcjpath/secured/fetdata/fetdaycookie\n";
@touch("$fetcjpath/secured/fetdata/fetdaycookie");
@chmod("$fetcjpath/secured/fetdata/fetdaycookie",0777);

echo "\t- $fetcjpath/secured/fetdata/fetdayin\n";
@touch("$fetcjpath/secured/fetdata/fetdayin");
@chmod("$fetcjpath/secured/fetdata/fetdayin",0777);

echo "\t- $fetcjpath/secured/fetdata/fetdayout\n";
@touch("$fetcjpath/secured/fetdata/fetdayout");
@chmod("$fetcjpath/secured/fetdata/fetdayout",0777);

echo "\t- $fetcjpath/secured/fetdata/fethourcookie\n";
@touch("$fetcjpath/secured/fetdata/fethourcookie");
@chmod("$fetcjpath/secured/fetdata/fethourcookie",0777);

echo "\t- $fetcjpath/secured/fetdata/fethourin\n";
@touch("$fetcjpath/secured/fetdata/fethourin");
@chmod("$fetcjpath/secured/fetdata/fethourin",0777);

echo "\t- $fetcjpath/secured/fetdata/fethourout\n";
@touch("$fetcjpath/secured/fetdata/fethourout");
@chmod("$fetcjpath/secured/fetdata/fethourout",0777);

echo "\t- $fetcjpath/secured/fetdata/fetproxdayclick\n";
@touch("$fetcjpath/secured/fetdata/fetproxdayclick");
@chmod("$fetcjpath/secured/fetdata/fetproxdayclick",0777);

echo "\t- $fetcjpath/secured/fetdata/fetproxdayin\n";
@touch("$fetcjpath/secured/fetdata/fetproxdayin");
@chmod("$fetcjpath/secured/fetdata/fetproxdayin",0777);

echo "\t- $fetcjpath/secured/fetdata/fetproxhourclick\n";
@touch("$fetcjpath/secured/fetdata/fetproxhourclick");
@chmod("$fetcjpath/secured/fetdata/fetproxhourclick",0777);

echo "\t- $fetcjpath/secured/fetdata/fetproxhourin\n";
@touch("$fetcjpath/secured/fetdata/fetproxhourin");
@chmod("$fetcjpath/secured/fetdata/fetproxhourin",0777);

echo "\t- $fetcjpath/secured/fetdata/fetreferrers\n";
@touch("$fetcjpath/secured/fetdata/fetreferrers");
@chmod("$fetcjpath/secured/fetdata/fetreferrers",0777);

echo "\t- $fetcjpath/secured/fetdata/fetstats\n";
@touch("$fetcjpath/secured/fetdata/fetstats");
@chmod("$fetcjpath/secured/fetdata/fetstats",0777);

echo "\t- $fetcjpath/secured/fetdata/fetlangd\n";
@touch("$fetcjpath/secured/fetdata/fetlangd");
@chmod("$fetcjpath/secured/fetdata/fetlangd",0777);

echo "\t- $fetcjpath/secured/fetdata/fetlangh\n";
@touch("$fetcjpath/secured/fetdata/fetlangh");
@chmod("$fetcjpath/secured/fetdata/fetlangh",0777);

echo "\t- $fetcjpath/secured/fetdata/fetbonusd\n";
@touch("$fetcjpath/secured/fetdata/fetbonusd");
@chmod("$fetcjpath/secured/fetdata/fetbonusd",0777);

echo"\n\n\n----------------------Installation Complete----------------------\n\n";
echo"You need to insert following lines in CRONTAB to start your script working: \n\n\n";
echo"00,02,04,06,08,10,12,14,16,18,20,22,24,26,28,30,32,34,36,38,40,42,44,46,48,50,52,54,56,58 * * * * cd $fetcjpath/secured; $phppath -q mastercron.php > /dev/null\n";
echo"01,16,31,46 * * * * $fetcjpath/secured/mastertopcron.cgi > /dev/null\n";
echo"01,16,31,46 * * * * $fetcjpath/secured/cleaner.sh > /dev/null\n";

echo"\n\nOr\n\n\n";
echo"01,03,05,07,09,11,13,15,17,19,21,23,25,27,29,31,33,35,37,39,41,43,45,47,49,51,53,55,57,59 * * * * cd $fetcjpath/secured; $phppath -q mastercron.php > /dev/null\n";
echo"02,17,32,47 * * * * $fetcjpath/secured/mastertopcron.cgi > /dev/null\n";
echo"02,17,32,47 * * * * $fetcjpath/secured/cleaner.sh > /dev/null\n\n\n";
echo"Or\n\n\n";

echo"*/2 * * * * cd $fetcjpath/secured; $phppath -q mastercron.php > /dev/null\n";
echo"*/15 * * * * $fetcjpath/secured/mastertopcron.cgi > /dev/null\n";
echo"*/15 * * * * $fetcjpath/secured/cleaner.sh > /dev/null\n\n\n";
echo "Depends on your system's possibilities\n\n\n";

?>