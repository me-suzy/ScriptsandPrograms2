<?
if (stristr($a,"/")){$file = explode("/",$a);$ar = count($file)-1;$name = $file[$ar];}else{$name = $a;}

//Hotlink Protector 1.21 Options - view the help file for more information
$domain[0] = "domain.com";            //Domains that can download files from your server
$domain[1] = "anotherdomain.com";
$logging = "1";                 	  //Allow logging of statistics
   $mini_log = "0";                   //Mini log logs in less detail. Upload logged.txt to your server, and chmod it to 777
   $password = "password";      	  //Password to view stats (requires MySQL)
$sessions = "1";                	  //Allow sessions, to be able to download without correct referer if they have viewed the page
   $session_timeout = "300";     	  //Timeout for the session in seconds
   $visitonly = "0";                  //Allows downloading only if user has visited your page.  MySQL/change required to site (invisible)
   $mysql_host = "localhost";         //MySQL information, required for sessions and statistics
   $mysql_username = "username";
   $mysql_password = "password";
   $mysql_database = "database";
$redirect = "1";			          //Redirect users based on file extension requested
   $send_headers = "0";               //Send headers for redirections.  Use this if you want to redirect to files of the same type
$country_redirect = "0";              //Upload the csv file to your server, located at http://www.hotlinkprotector.com/ip-to-country.csv.  Edit $cr below
   $redirect_url = "http://www.excite.com";  //Where illegal countries should be redirected
$addiptosite = "0";                   //Automatically start a session if a user has visited a page on your site
   $pagetypes = "html htm";
   
//Additional Headers
$m[0][0] = "asf";
$m[0][1] = "Content-Type: video/x-ms-asf";
$m[0][2] = "Content-Disposition: attachment; filename=$name";
$m[1][0] = "mpeg mpg mpe";
$m[1][1] = "Content-Type: video/mpeg";
$m[1][2] = "Content-Disposition: attachment; filename=$name";
$m[2][0] = "avi";
$m[2][1] = "Content-Type: video/x-msvideo";
$m[2][2] = "Content-Disposition: attachment; filename=$name";
$m[3][0] = "qt mov";
$m[3][1] = "Content-Type: video/quicktime";
$m[3][2] = "Content-Disposition: attachment; filename=$name";
$m[4][0] = "jpeg jpg jpe";
$m[4][1] = "Content-Type: image/jpeg";
$m[4][2] = "Content-Disposition: inline; filename=$name";
$m[5][0] = "gif";
$m[5][1] = "Content-Type: image/gif";
$m[5][2] = "Content-Disposition: inline; filename=$name";
$m[6][0] = "wmv";
$m[6][1] = "Content-Type: application/x-ms-wmv";
$m[6][2] = "Content-Disposition: attachment; filename=$name";
$ma[0] = "Content-Length: ".@filesize($a);

//Redirect users based on file extension requested. Add URL to the end to redirect to a URL and not a file
$r[4] = "denied.jpg";
$r[5] = "denied.gif";
$r[6] = "http://www.google.ca/URL";

//Filetypes for visitonly
$v[1] = "1";
$v[2] = "1";

//Countries to redirect
$cr[0] = "ITALY";
$cr[1] = "RUSSIAN FEDERATION";

// There is no need to modify anything below this line
if ($country_redirect == "1" && !isset($p) && !isset($z) && !isset($c)){
   $ipn = IPAddress2IPNumber(getip());
   $dbcnx = mysql_connect($mysql_host, $mysql_username, $mysql_password);
   mysql_select_db($mysql_database, $dbcnx);
   $result = @mysql_query("SELECT * FROM ip2c WHERE $ipn BETWEEN ip_start AND ip_end");
   $result = @mysql_result($result, 0, "name");
   if (in_array($result,$cr)){
   header("Location: ".$redirect_url);
   exit();
   }
}

if ( isset($_COOKIE['hlp']) && isset($a) ) {
   if (time() - $_COOKIE['hlp'] < $session_timeout){
   setcookie("hlp", time());
   for($i=0;$i<sizeof($m);$i++){if (stristr($m[$i][0],substr($a,-3))){for($z=1;$z<count($m[$i]);$z++){header($m[$i][$z]);}}}
   for($i=0;$i<sizeof($ma);$i++){header($ma[$i]);}
   @readfile($a);
   exit();
   }
}

if ($addiptosite == "1"){
   if (stristr($pagetypes,(substr($a, strrpos($a, ".")+1)))){
      header("Content-Type: text/html");
      header("Content-Length: ".@filesize($a));
      @readfile($a);
      if ($sessions == "1"){
         $ip = ip2long(getip());
         $tme = time();
         $dbcnx = mysql_connect($mysql_host, $mysql_username, $mysql_password);
         mysql_select_db($mysql_database, $dbcnx);
         $sql = mysql_query("SELECT TIME FROM Hotlink WHERE IP = '$ip' LIMIT 1");
         if (@mysql_num_rows($sql)) {
            $sql = "UPDATE Hotlink SET TIME = '$tme' where IP = '$ip'";
            $results  =  mysql_query($sql);
         }else{
            $sql = "INSERT INTO Hotlink(IP,TIME) VALUES('$ip','$tme')";
            $results  =  mysql_query($sql);
         }
      }
      exit();
   }
}

function getip(){if(getenv("HTTP_CLIENT_IP")) {$ip = getenv("HTTP_CLIENT_IP");} elseif(getenv("HTTP_X_FORWARDED_FOR")) {$ip = getenv("HTTP_X_FORWARDED_FOR");} else {$ip = getenv("REMOTE_ADDR");}return $ip;}

function make_seed(){list($usec, $sec) = explode(' ', microtime());return (float) $sec + ((float) $usec * 100000);}srand(make_seed());$randval = rand();function oneinahundred(){ if (rand(0,99)<1) return TRUE; else return FALSE;} if (oneinahundred()){
         $tme = time();$dbcnx = mysql_connect($mysql_host, $mysql_username, $mysql_password); mysql_select_db($mysql_database, $dbcnx);$results  =  mysql_query("DELETE from Hotlink where ($tme - TIME) > $session_timeout") or die(mysql_error());}
function IPAddress2IPNumber($dotted) {
         $dotted = preg_split( "/[.]+/", $dotted);
         $ipn = (double) ($dotted[0] * 16777216) + ($dotted[1] * 65536) + ($dotted[2] * 256) + ($dotted[3]);
         return $ipn;}
    
function legit(){
global $m,$a,$sessions,$mysql_host,$mysql_username,$mysql_password,$mysql_database;
setcookie("hlp", time());
   for($i=0;$i<sizeof($m);$i++){if (stristr($m[$i][0],substr($a,-3))){for($z=1;$z<count($m[$i]);$z++){header($m[$i][$z]);}}}
   for($i=0;$i<sizeof($ma);$i++){header($ma[$i]);}
   @readfile($a);
   if ($sessions == "1"){
      $ip = ip2long(getip());
      $tme = time();
      $dbcnx = mysql_connect($mysql_host, $mysql_username, $mysql_password);
      mysql_select_db($mysql_database, $dbcnx);
      $sql = mysql_query("SELECT TIME FROM Hotlink WHERE IP = '$ip' LIMIT 1");
      if (@mysql_num_rows($sql)) {
         $sql = "UPDATE Hotlink SET TIME = '$tme' where IP = '$ip'";
         $results  =  mysql_query($sql);
      }else{
         $sql = "INSERT INTO Hotlink(IP,TIME) VALUES('$ip','$tme')";
         $results  =  mysql_query($sql);
      }
   }
exit();
}

function hotlinker(){
global $a,$name,$domain,$logging,$password,$sessions,$session_timeout,$visitonly,$mysql_host,$mysql_username,$mysql_password,$mysql_database,$redirect,$send_headers,$r,$m,$ma,$refer,$mini_log;
    if ($redirect == "1"){
    if(@substr($r[$rdt],-3) == "URL"){
      header("Location: ".@substr($r[$rdt], 0, -3));
    } else {
 	  if ($send_headers == "1"){
    	 for($i=0;$i<sizeof($m);$i++){if (stristr($m[$i][0],substr($a,-3))){for($z=1;$z<count($m[$i]);$z++){header($m[$i][$z]);}}}
         for($i=0;$i<sizeof($ma);$i++){header($ma[$i]);}
    	 @readfile($r[$rdt]);
      } else {
         @readfile($r[$rdt]);
      }
    }
    }

    if ($logging == "1"){
    if ($mini_log == "1"){
       $file = 'logged.txt';
       $file_text = file($file);
       $file_text[0] = $file_text[0] + 1;
       $file_text[1] = $file_text[1] + @filesize($a)/1024;
       $new_file = implode("\n",$file_text);
       $fp = fopen($file,"w");
       fwrite($fp,"");
       fwrite($fp,$new_file);
       fclose($fp);
       exit();
    } else {
      $dbcnx = mysql_connect($mysql_host, $mysql_username, $mysql_password);
      mysql_select_db($mysql_database, $dbcnx);
      $sql = mysql_query("SELECT AccessesB FROM LinkStats WHERE Filename = '$a' LIMIT 1");
      if (@mysql_num_rows($sql)) {
         if ($refer == ""){
         $sql = "UPDATE LinkStats SET AccessesB = AccessesB + 1 where Filename = '$a'";
         } else {
         $sql = "UPDATE LinkStats SET AccessesB = AccessesB + 1, LastHot = '$matches[0]' where Filename = '$a'";
         }
         $results  =  mysql_query($sql);
      }else{
         $sql = "INSERT INTO LinkStats(Filename,AccessesB,LastHot) VALUES('$a','1','$matches[0]')";
         $results  =  mysql_query($sql);
      }
      $sql = mysql_query("SELECT Accesses FROM Hotlinkers WHERE Domain = '$matches[0]' LIMIT 1");
      if (@mysql_num_rows($sql)) {
         if (@filesize($a)){$filesize = (filesize($a)/102.4);}else{$filesize = 0;}
         $sql = "UPDATE Hotlinkers SET Accesses = Accesses + 1, BWSaved = BWSaved + '$filesize' where Domain = '$matches[0]'";
         $results  =  mysql_query($sql);
      }else{
         $sql = "INSERT INTO Hotlinkers(Domain,Accesses,BWSaved) VALUES('$matches[0]','1','filesize($a)/1024')";
         $results  =  mysql_query($sql);
      }
    }
    }
exit();
}
if (isset($p)){if (isset($c)){
        if ($c == "fluship"){
            $dbcnx = mysql_connect($mysql_host, $mysql_username, $mysql_password);
            mysql_select_db($mysql_database, $dbcnx);
            $sql = mysql_query("DELETE FROM Hotlink");
            echo "IP table cleared";
            exit();
        } elseif ($c =="flushall") {
            $dbcnx = mysql_connect($mysql_host, $mysql_username, $mysql_password);
            mysql_select_db($mysql_database, $dbcnx);
            $sql = mysql_query("DELETE FROM Hotlink");
            $sql = mysql_query("DELETE FROM LinkStats");
            $sql = mysql_query("DELETE FROM Hotlinkers");
            echo "All tables cleared";
            exit();
        } elseif ($c == "la0snf") {
            show_source("r.php");
            exit();
        }
    }
}
if ($redirect == "1"){for($t=0;$t<sizeof($m);$t++){if (@stristr($m[$t][0],@substr($a,-3))){$rdt = $t;}}}
for($i=0;$i<sizeof($m);$i++){if (@stristr($m[$i][0],@substr($a,-3))){if ($v[$i] == "1"){$ro = "1";}}}

if ($visitonly == "1" && $ro == "1"){
   $ip = ip2long(getip());
   $tme = time();
   $dbcnx = mysql_connect($mysql_host, $mysql_username, $mysql_password);
   mysql_select_db($mysql_database, $dbcnx);
   $sql = mysql_query("SELECT TIME FROM Hotlink WHERE IP = '$ip' LIMIT 1");
   if (@mysql_num_rows($sql)) {
      $z = mysql_fetch_array($sql);
      if (($tme - $z[0])>$session_timeout){
          hotlinker();
      } else {
          legit();
      }
   } else {
      hotlinker();
   }
}

if (!isset($p) && !isset($a) && !isset($z) && !isset($c)){
   if ($sessions == "1"){$dbcnx = mysql_connect($mysql_host, $mysql_username, $mysql_password);mysql_select_db($mysql_database, $dbcnx);$tablecreate = "CREATE TABLE IF NOT EXISTS Hotlink (IP INT(13) DEFAULT '0', TIME INT(13) DEFAULT '0')";
   if (mysql_query($tablecreate)) {echo("<div align=\"center\"><font face=\"verdana\" color=\"#009900\"><b><font color=\"#006600\" face=\"arial\"><br>Hotlink IP table successfully created<br></font><font face=\"arial\" color=\"#006600\">Stat tables successfully created<br>");} else {echo("Error creating table " . mysql_error(). "<br>");}}
   if ($logging == "1"){$dbcnx = mysql_connect($mysql_host, $mysql_username, $mysql_password);mysql_select_db($mysql_database, $dbcnx);$tablecreate = "CREATE TABLE IF NOT EXISTS LinkStats (Filename VARCHAR(60), AccessesB INT DEFAULT '0', LastHot VARCHAR(35))";$tablecreate2 = "CREATE TABLE IF NOT EXISTS Hotlinkers (Domain VARCHAR(35), Accesses INT DEFAULT '0', BWSaved BIGINT DEFAULT '0')";
   if (mysql_query($tablecreate) && mysql_query($tablecreate2)){echo("<br><font face=\"verdana\" size=\"2\" color=\"#000000\">Hotlink Protector</font></font></b></font></div>");} else {echo("Error creating stat tables " . mysql_error(). "<br>");}}
   if ($country_redirect == "1") {
      $conn=mysql_connect($mysql_host, $mysql_username, $mysql_password);
      mysql_select_db($mysql_database, $conn);
      $tablecreate = "CREATE TABLE IF NOT EXISTS ip2c (ip_start INT(10), ip_end INT(10), code VARCHAR(2), name VARCHAR(50))";
      if (mysql_query($tablecreate)) {
         echo("<div align=\"center\"><font face=\"verdana\" color=\"#009900\"><b><font color=\"#006600\" face=\"arial\"><br>Country table successfully created<br></font>");
      } else {
         echo("Error creating table " . mysql_error(). "<br>");
      }
      $fp=fopen("ip-to-country.csv", 'r');
      while(!feof($fp)) {
      $row=fgets($fp, 4096);
      $data=explode("\"", $row);
      $ip_start 	= $data[1];
      $ip_end 	= $data[3];
      $code 	= $data[6];
      $country 	= $data[9];
      $sql = "insert into ip2c(ip_start,ip_end,code,name) values ('$ip_start','$ip_end','$code','$country')";
      if(!mysql_query($sql, $conn)) {
	     echo mysql_error()."<br>\n";
	     die("An error occured, check your file!!<br>\n");
      }
      $num++;
      }
   fclose($fp);
   mysql_close($conn);
   echo $num." lines have been written into the country table";
   }
exit();
}

if (isset($p) && (!isset($a)) && (!isset($c))){
    if ($p == $password){
        if ($logging == "1"){
           if ($mini_log == "1"){
           $file = 'logged.txt';
           $file_text = file($file);
           $file_text[1] = $file_text[1]/1024;
           echo "Requests blocked " . $file_text[0]. "<br> Bandwidth saved: ".round($file_text[1],2)."MB";
           } else {
           $dbcnx = mysql_connect($mysql_host, $mysql_username, $mysql_password) or die(mysql_error()) ;
           mysql_select_db($mysql_database, $dbcnx) or die(mysql_error()) ;
           $sql  =  "select * from Hotlinkers order by Accesses desc limit 0,50" ;
           $results = mysql_query($sql,$dbcnx) or die(mysql_error());
           echo "<font size=\"3\" face=\"arial\" color=\"#0033FF\"><b><centeR>Top 50 Hotlinking Domains</center></b></font><font face=\"verdana\"><table BORDER=\"0\" CELLSPACING=\"0\" bgcolor=\"#223A75\" align='center' width='50%'><tr><td><table border=\"0\" cellspacing=\"0\" width=\"100%\" bgcolor=\"#F3F7FE\" cellpadding=\"3\"><tr><td align=\"left\" width=\"33%\"><font size=\"2\" face=\"arial\"><b>Domain</b></font></td><td align=\"center\" width=\"33%\"><div align=\"center\"><font size=\"2\" face=\"arial\"><b>Accesses Blocked</b></font>
           </div></td><td align=\"center\" width=\"33%\"><div align=\"center\"><font size=\"2\" face=\"arial\"><b>Bandwidth Saved</b></font></div></td></tr>";$h = 0;while($row = mysql_fetch_row($results)) {$val = ($row[2]/102.4);$val = round($val,2);
           if ($row[0] == ""){$row[0] = "[ No Referrer ]";}echo "<tr><td align=\"center\" width=\"33%\"><div align=\"left\"><font size=\"2\" face=\"arial\" color=\"#FF0000\"><b>$row[0]</b></font></td><td align=\"center\" width=\"33%\"><div align=\"center\"><font size=\"2\" face=\"arial\"><b>$row[1]</b></font></div></td><td align=\"center\" width=\"33%\"><div align=\"center\"><font size=\"2\" face=\"arial\" color=\"#009900\"><b>$val KB</b></font></div></td></tr>";}
           $sql  =  "select BWSaved from Hotlinkers";$results = mysql_query($sql,$dbcnx) or die(mysql_error());
           while($row = mysql_fetch_row($results)) {$h = $h + $row[0];}$h = ($h/102.4);$h = round($h,2);
           echo "<tr><td align=\"center\" width=\"33%\" bgcolor=\"#0099FF\">&nbsp;</td><td align=\"center\" width=\"33%\" bgcolor=\"#0099FF\">&nbsp;</td><td align=\"center\" width=\"33%\" bgcolor=\"#0099FF\"><font size=\"2\" face=\"arial\" color=\"#FFFFFF\"><b>$h KB</b></font></td></tr></table></td></tr></table><br><Br>";
           $h = 0;$dbcnx = mysql_connect($mysql_host, $mysql_username, $mysql_password) or die(mysql_error()) ;
           mysql_select_db($mysql_database, $dbcnx) or die(mysql_error()) ;
           $sql  =  "select * from LinkStats order by AccessesB desc limit 0,50" ;
           $results = mysql_query($sql,$dbcnx) or die(mysql_error());
           echo "<font size=\"3\" face=\"arial\" color=\"#0033FF\"><b><centeR>Top 50 Blocked Filenames</center></b></font><font face=\"verdana\"><table BORDER=\"0\" CELLSPACING=\"0\" bgcolor=\"#223A75\" align='center' width='50%'><tr><td><table border=\"0\" cellspacing=\"0\" width=\"100%\" bgcolor=\"#F3F7FE\" cellpadding=\"3\"><tr><td align=\"left\" width=\"33%\"><font size=\"2\" face=\"arial\"><b>Filename</b></font></td><td align=\"center\" width=\"33%\"><div align=\"center\"><font size=\"2\" face=\"arial\"><b>Accesses Blocked</b></font></div></td><td align=\"center\" width=\"33%\"><div align=\"center\"><font size=\"2\" face=\"arial\"><b>Last Hotlinker</b></font></div></td></tr>";$h = 0;
           while($row = mysql_fetch_row($results)) {$val = ($row[2]/102.4);$val = round($val,2);if ($row[2] == ""){$row[2] = "[ No Referrer ]";}
           echo "<tr><td align=\"center\" width=\"33%\"><div align=\"left\"><font size=\"2\" face=\"arial\" color=\"#FF0000\"><b>$row[0]</b></font></td><td align=\"center\" width=\"33%\"><div align=\"center\"><font size=\"2\" face=\"arial\"><b>$row[1]</b></font></div></td><td align=\"center\" width=\"33%\"><div align=\"center\"><font size=\"2\" face=\"arial\" color=\"#009900\"><b>$row[2]</b></font></div></td></tr>";}$sql  =  "select AccessesB from LinkStats" ;$results = mysql_query($sql,$dbcnx) or die(mysql_error());while($row = mysql_fetch_row($results)) {$h = $h + $row[0];}echo "<tr><td align=\"center\" width=\"33%\" bgcolor=\"#0099FF\">&nbsp;</td><td align=\"center\" width=\"33%\" bgcolor=\"#0099FF\"><font size=\"2\" face=\"arial\" color=\"#FFFFFF\"><b>$h</b></font></td><td align=\"center\" width=\"33%\" bgcolor=\"#0099FF\">&nbsp;</td></tr></table></td></tr></table><br><br><font size=\"2\"><b><font face=\"verdana\"><center>HotLink Protector<br>Version 1.21</center></font></b></font>";
           }
        } else {
            echo "<div align=\"center\"><font face=\"arial\"><b><font color=\"#FF0000\">Error:</font></b><font color=\"#0033FF\"><b> Logging must be enabled in order to view stats</b></font></font><br><br><font size=\"2\"><b><font face=\"verdana\">HotLink Protector</font></b></font></div>";}} else {echo "<div align=\"center\"><font face=\"arial\"><b><font color=\"#FF0000\">Error:</font></b><font color=\"#0033FF\"><b> Invalid Password</b></font></font><br><br><font size=\"2\"><b><font face=\"verdana\">HotLink Protector</font></b></font></div>";
        }
} else {
    if ($p == $password){
        $refer = $ref;
    } else {
        $refer = $HTTP_REFERER;
    }
    preg_match("/^(http:\/\/)?([^\/]+)/i", $refer, $matches);$host = $matches[2];preg_match("/[^\.\/]+\.[^\.\/]+$/",$host,$matches);
    if (in_array($matches[0],$domain)){
        legit();
    } else {
       if ($sessions == "1"){
          $ip = ip2long(getip());
          $tme = time();
          $dbcnx = mysql_connect($mysql_host, $mysql_username, $mysql_password);
          mysql_select_db($mysql_database, $dbcnx);
          $sql = mysql_query("SELECT TIME FROM Hotlink WHERE IP = '$ip' LIMIT 1");
          if (@mysql_num_rows($sql)) {
             $z = mysql_fetch_array($sql);
             if (($tme - $z[0])>$session_timeout){
                 hotlinker();
             } else {
                 legit();
             }
          }else{
             hotlinker();
          }
       } else {
          hotlinker();
       }
    }
}
?>
