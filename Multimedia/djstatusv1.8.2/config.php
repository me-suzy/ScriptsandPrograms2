<?php
//////////////////////////////////////////////////////////////////////////////
// DJ Status v1.8.2															//
// ©2005 Nathan Bolender www.nathanbolender.com								//
// Free to use on any website												//
//////////////////////////////////////////////////////////////////////////////
// Configuration
// Add your information in the quotes on each line

# Database
$dbhost = "";		// MySQL Server, Usually localhost
$dbname = ""; 		// MySQL database name
$dbuser = ""; 		// MySQL username
$dbpass = ""; 		// MySQL password

# Shoutcast
$scdef = ""; 		// Default station name to display when server or stream is down (ex: SNRadio)
$scip = ""; 		// ip or url of shoutcast server - no http:// (ex: radio.radiosite.com)
$scport = ""; 		// port of shoutcast server (ex: 8000)
$scpass = ""; 		// password to shoutcast server

# Admin Panel
$adminname = "";    // Name to display when in manual mode
$adminpass = "";	// Password for the administration panel

// End configuration
////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////// Do not edit below this line //////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
$version = "1.8.2";	

$filename = "admin/install/index.php";
	
// Shoutcast Server Stats
$scfp = @fsockopen("$scip", $scport, &$errno, &$errstr, 10);
 if(!$scfp) {
  $scsuccs=1;
# echo'<strong>'.$scdef.' is Offline</strong><br>';
 }
if($scsuccs!=1){
 fputs($scfp,"GET /admin.cgi?pass=$scpass&mode=viewxml HTTP/1.0\r\nUser-Agent: DJ Status v$version (www.nathanbolender.com) (Mozilla Compatible)\r\n\r\n");
 while(!feof($scfp)) {
  $page .= fgets($scfp, 1000);
 }
//define  xml elements
 $loop = array("STREAMSTATUS", "BITRATE", "SERVERTITLE", "CURRENTLISTENERS", "AIM", "ICQ");
 $y=0;
 while($loop[$y]!=''){
  $pageed = ereg_replace(".*<$loop[$y]>", "", $page);
  $scphp = strtolower($loop[$y]);
  $$scphp = ereg_replace("</$loop[$y]>.*", "", $pageed);
  if($loop[$y]==SERVERGENRE || $loop[$y]==SERVERTITLE || $loop[$y]==SONGTITLE || $loop[$y]==SERVERTITLE || $loop[$y]==AIM || $loop[$y]==ICQ)
   $$scphp = urldecode($$scphp);

// uncomment the next line to see all variables
#echo'$'.$scphp.' = '.$$scphp.'<br>';
  $y++;
 }
//end intro xml elements

//get song info and history
 $pageed = ereg_replace(".*<SONGHISTORY>", "", $page);
 $pageed = ereg_replace("<SONGHISTORY>.*", "", $pageed);
 $songatime = explode("<SONG>", $pageed);
 $r=1;
 while($songatime[$r]!=""){
  $t=$r-1;
  $playedat[$t] = ereg_replace(".*<PLAYEDAT>", "", $songatime[$r]);
  $playedat[$t] = ereg_replace("</PLAYEDAT>.*", "", $playedat[$t]);
  $song[$t] = ereg_replace(".*<TITLE>", "", $songatime[$r]);
  $song[$t] = ereg_replace("</TITLE>.*", "", $song[$t]);
  $song[$t] = urldecode($song[$t]);
  $dj[$t] = ereg_replace(".*<SERVERTITLE>", "", $page);
  $dj[$t] = ereg_replace("</SERVERTITLE>.*", "", $pageed);
$r++;
 }
//end song info

fclose($scfp);
}

if (file_exists($filename))  {
$nodb = "1";
}

if (isset($nodb) == FALSE) {
include ("dbconnect.php");
}

?>