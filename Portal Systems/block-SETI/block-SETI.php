<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (eregi("block-SETI.php",$_SERVER['PHP_SELF'])) {
    Header("Location: index.php");
    die();
}
/************************************************************************/
# PUT IN YOUR EMAIL ADDRESS USED @ SETI@home
$usremail = "eml@email.eml";
/************************************************************************/
if($usremail == 'eml@email.eml')
  {
  echo('<font color=#ff0000><center>change eMail address in <br>blocks/block-SETI.php<br></center></font>');
  } else {
$fopen_wrapper = @ini_get('allow_url_fopen');
if(!$fopen_wrapper)
  {
  echo('<font color=#ff0000><center>configure your webserver/phpinfo: <br>allow_url_fopen<br></center></font>');

  } else {
$url = $urlbase.$usremail;
$usrresults   = "";
$usrcputime   = "";
$usravgcpu    = "";
$usrlastres   = "";
$usrregister  = "";
$usrrank      = "";
$xmlusrurl  = 'http://setiathome2.ssl.berkeley.edu/fcgi-bin/fcgi?cmd=user_xml&email='.$usremail; 
$totalstatsurl= 'http://setiathome2.ssl.berkeley.edu/totals.html';
$urlbase = "http://setiathome2.ssl.berkeley.edu/fcgi-bin/fcgi".'?'."cmd=user_xml".'&'."email=";
$url = $urlbase.$usremail;
flush();
$retries = 0;
while($retries < 3)
  {
  if(!($fp = @fopen($xmlusrurl,"r")))
    {
    $retries++;
    sleep(2);
    continue;
    }
  else break;
  }
if (!$fp) {
echo  "<TABLE align=\"center\" BORDER=\"0\">";
echo  "<TR><TD>";
echo  "<center><br><font color=#ff0000>SETI ERROR:</font></center>"; 
echo  "<center>no member stats / no totals</center><br>"; 
echo  "</TD></TR>";
echo  "</TABLE>"; 
   } else {
$usrdata = "";
while (!@feof ($fp))
  {
  $usrdata.= @fgets($fp, 4096);
  }
@fclose($fp);
$tbytes         = strlen($usrdata);
$usrdata      = preg_replace("/\n|\r/","",$usrdata);
$usrresults   = preg_replace("/(.*)(<numresults>)(\d{1,})(<\/numresults>)(.*)/i","\\3",$usrdata);
$usrcputime   = preg_replace("/(.*)(<cputime>)(.*?)(<\/cputime>)(.*)/i","\\3",$usrdata);
$usravgcpu    = preg_replace("/(.*)(<avecpu>)(.*?)(<\/avecpu>)(.*)/i","\\3",$usrdata);
$usrlastres   = preg_replace("/(.*)(<lastresulttime>)(.*?)(<\/lastresulttime>)(.*)/i","\\3",$usrdata);
$usrregister  = preg_replace("/(.*)(<regdate>)(.*?)(<\/regdate>)(.*)/i","\\3",$usrdata);
$usrrank      = preg_replace("/(.*)(<rank>)(.*?)(<\/rank>)(.*)/i","\\3",$usrdata);
$dummy          = preg_replace("/(.*)(<userprofile>)(.*?)(<\/userprofile>)(.*)/i","\\3",$usrdata);
$retries = 0;
while($retries < 3)
  {
  if(!($fp = @fopen($totalstatsurl,"r")))
    {
    $retries++;
    sleep(2);
    continue;
    }
  else break;
  }
if (!$fp) {
echo  "<TABLE align=\"center\" BORDER=\"0\">";
echo  "<TR><TD>";
echo  "<center><br><font color=#ff0000>SETI ERROR:</font></center>"; 
echo  "<center>no total stats</center><br>"; 
echo  "</TD></TR>";
echo  "</TABLE>"; 
   } else {
$totaldata = "";
while(!@feof($fp))
  {
  $totaldata.= @fgets($fp, 4096);
  }
@fclose($fp);
$tbytes+= strlen($totaldata);
$temp = preg_replace("/(.*?)(<tr><th>Users<\/th><td>)(\d{1,})(<\/td><td>)(\d{1,})(.*)/is","\\3,\\5",$totaldata);
if($temp != $totaldata)
  {
  $dummy = split(",",$temp);
  $totalusers   = trim($dummy[0]);
  $user24hours  = trim($dummy[1]);
  }
else
  {
  $totalusers = "";
  $user24hours = "";
  }
$temp = preg_replace("/(.*?)(<tr><th>Results received<\/th><td>)(\d{1,})(<\/td><td>)(\d{1,})(.*)/is","\\3,\\5",$totaldata);
if($temp != $totaldata)
  {
  $dummy = split(",",$temp);
  $totalresults   = trim($dummy[0]);
  $results24hours = trim($dummy[1]);
  }
else
  {
  $totalresults   = "";
  $results24hours = "";
  }
$temp = preg_replace("/(.*?)(<tr><th>Total CPU time<\/th><td>)(.*?)(<\/td><td>)(.*?)(<\/td>.*)/is","\\3,\\5",$totaldata);
if($temp != $totaldata)
  {
  $dummy = split(",",$temp);
  $totalcputime   = trim($dummy[0]);
  $cputime24hours = trim($dummy[1]);
  }
else
  {
  $totalcputime   = "0";
  $cputime24hours = "0";
  }
$content .= "<TABLE align=\"center\" BORDER=\"0\">";
$content .= "<TR><TD>";
$content .= "<center><br><a href=\"http://setiathome2.ssl.berkeley.edu/\" target=\"_blank\"><img src=\"images/better_banner.jpg\" width=\"130\" height=\"26\" border=\"0\"></img></a></center><br>"; 
$content .= "</TD></TR>";
$content .= "<TR><TD>";
$content .= "<center><b>Total User CPU Time:</b><br>$usrcputime</center>";
$content .= "<center><b>Avg CPU Time / WU:</b><br>$usravgcpu</center><br>";
$content .= "<center><a href=\"http://setiathome2.ssl.berkeley.edu/cpu.html\" target=\"_blank\"><img src=\"images/DCpuTime.gif\" width=\"130\" height=\"65\" border=\"0\" alt=\"Click for details\"></img></a></center><br>";
$content .= "<center><b>Last Result Returned:</b><br>$usrlastres</center>";
$content .= "<center><b>Registered since:</b><br>$usrregister</center>";
$content .= "<center><b>returned:</b><br>$usrresults WUs</center>";
$content .= "<center><b>Rank: </b>$usrrank</center><br>";
$content .= "<center><a href=\"http://setiathome2.ssl.berkeley.edu/numusers.html\" target=\"_blank\"><img src=\"images/ActiveUsers.gif\" width=\"130\" height=\"65\" border=\"0\" alt=\"Click for details\"></img></a></center><br>";
$content .= "<center><a href=\"http://setiathome2.ssl.berkeley.edu/totals.html\" target=\"_blank\">Worldwide Stats:</center></a>";
$content .= "<center><b>Users: $totalusers</b></center>";
$content .= "<center>(<b>+ </b>$user24hours)</center>";
$content .= "<center><b>CPU: $totalcputime</b></center>";
$content .= "<center>(<b>+ </b>$cputime24hours)</center>";
$content .= "<center><b>WUs: $totalresults</b></center>";
$content .= "<center>(<b>+ </b>$results24hours)</center><br>";
$content .= "</TD></TR>";
$content .= "<TR><TD>";
$content .= "<center>made for PHP-Nuke</center>";
$content .= "<center><a href=\"http://www.hecargo.net/\">by Claus Bamberg</a></center>";
$content .= "</TD></TR>";
$content .= "</TABLE>"; 
$content .= "<br>";
}
}
}
}

?>
