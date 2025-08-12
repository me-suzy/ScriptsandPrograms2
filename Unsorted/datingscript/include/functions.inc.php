<?
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               functions.inc.php                #
# File purpose            Functions file                   #
# File created by         AzDG <support@azdg.com>          #
############################################################
@mysql_connect(C_HOST, C_USER, C_PASS) or die($w[113]); 
@mysql_select_db(C_BASE) or die($w[114]);

function printm($tmp,$v=NULL) {
global $stime,$w;
if (($v==1)||($v==NULL)) $e=C_EBOT; else $e=C_SBOT;
echo C_ETOP.$tmp.$e;
include_once C_PATH.'/templates/'.C_TEMP.'/footer.php';
die;
}

function mes($tmp) {
echo C_ETOP.$tmp.C_SBOT;
}

function smes($tmp) {
echo $tmp;
}

function sprintm($tmp) {
global $w;
echo '<br><br><br><span class=head><center>'.$tmp.'</center></span><br><br>';
include_once C_PATH.'/templates/'.C_TEMP.'/sfooter.php';
die;
}

function code_gen() 
{
$passgen = preg_replace("/(.)/e","chr(rand(ord('a'),ord('z')))",str_repeat('.',10));
$passgen=md5($passgen);
return $passgen;
}


function not_empty($var1,$var2,$var3)
{
if(!empty($var1)) echo $var1;
elseif(!empty($var2)) echo $var2;
elseif(!empty($var3)) echo $var3;
else echo 'Unknown Error';
}

function pages($from,$step,$count,$param,$colspan)
{
$str='';global $w;
if ($step < $count)
{
$str='<Tr bgcolor='.COLORH.' align=center><td colspan='.$colspan.'>'.$w[115];
$mesdisp = $step;$max = $count;
$from = ($from > $count) ? $count : $from;
$from = ( floor( $from / $mesdisp ) ) * $mesdisp;
if ((C_CPAGE % 2) == 1)	$pc = (int)((C_CPAGE - 1) / 2);
else $pc = (int)(C_CPAGE / 2);	
if ($from > $mesdisp * $pc)	
$str.= "<a href=\"?from=0&step=".$step."&".$param."\">1</a> ";

if ($from > $mesdisp * ($pc + 1)) $str.= "<B> . . . </B>";

for ($nCont=$pc; $nCont >= 1; $nCont--)
if ($from >= $mesdisp * $nCont) {
	$tmpStart = $from - $mesdisp * $nCont;
	$tmpPage = $tmpStart / $mesdisp + 1;
	$str.= "<a href=\"?from=".$tmpStart."&step=".$step."&".$param."\">".$tmpPage."</a> ";	}

$tmpPage = $from / $mesdisp + 1;$str.= " [<B>".$tmpPage."</B>] ";
$tmpMaxPages = (int)(($max - 1) / $mesdisp) * $mesdisp;	
for ($nCont=1; $nCont <= $pc; $nCont++)
if ($from + $mesdisp * $nCont <= $tmpMaxPages) {
	$tmpStart = $from + $mesdisp * $nCont;
	$tmpPage = $tmpStart / $mesdisp + 1;
	$str.= "<a href=\"?from=".$tmpStart."&step=".$step."&".$param."\">".$tmpPage."</a> ";}
if (($from + $mesdisp * ($pc + 1)) < $tmpMaxPages) $str.= "<B> . . . </B>";
if (($from + $mesdisp * $pc) < $tmpMaxPages)	{ 
	$tmpPage = $tmpMaxPages / $mesdisp + 1;
	$str.= "<a href=\"?from=".$tmpMaxPages."&step=".$step."&".$param."\">".$tmpPage."</a> ";		}
$str.="</td></tr>";
}
return $str;
}


function horo($m,$d) { // Return number of horoscope
         switch($m) {
               case "1":$d > 19 ? $h = 2 : $h = 1;break;
               case "2":$d > 18 ? $h = 3 : $h = 2;break;
               case "3":$d > 20 ? $h = 4 : $h = 3;break;
               case "4":$d > 19 ? $h = 5 : $h = 4;break;
               case "5":$d > 20 ? $h = 6 : $h = 5;break;
               case "6":$d > 20 ? $h = 7 : $h = 6;break;
               case "7":$d > 22 ? $h = 8 : $h = 7;break;
               case "8":$d > 22 ? $h = 9 : $h = 8;break;
               case "9":$d > 22 ? $h = 10 : $h = 9;break;
               case "10":$d > 22 ? $h = 11 : $h = 10;break;
               case "11":$d > 21 ? $h = 12 : $h = 11;break;
               case "12":$d > 21 ? $h = 1 : $h = 12;break;
               default:$h = 0;break;
               }
          return $h;
}

function cb($ss) 
{$ss = htmlspecialchars(stripslashes($ss));
$ss = str_replace("\r\n"," <br>","$ss");
$ss = str_replace("\\","","$ss");
$ss = str_replace("'","&rsquo;","$ss");
$ss = str_replace('"',"&quot;","$ss");
$ss = trim($ss);
return $ss;
}

function cbmail($ss) 
{$ss = htmlspecialchars(stripslashes($ss));
$ss = str_replace("\\","","$ss");
$ss = str_replace("'","&rsquo;","$ss");
$ss = str_replace('"',"&quot;","$ss");
$ss = trim($ss);
return $ss;
}

function tb($ss) 
{
$ss = str_replace("<br>","\r\n","$ss");
$ss = str_replace("&rsquo;","'","$ss");
$ss = str_replace('&quot;','"',"$ss");
$ss = str_replace("&lt;","<","$ss");
$ss = str_replace("&gt;",">","$ss");
return $ss;
}

function search_results($from,$step,$count) {
global $w;$str=$w[116].' ';
$m=(($from+$step)>$count) ? $count : ($from+$step);
$str.=$from.' - '.$m.' ['.$w[117].' '.$count.']';
return $str;
}

function template($text,$vars) {
$msg = preg_replace("/{(\w+)}/e", "\$vars['\\1']", $text);
return $msg;
}

function security($var,$error) {
unset($s);unset($m);global $stime,$w,$l;
if(
($var)&&
((!isset($_SESSION['s']))||(!isset($_SESSION['m']))||(!isset($_SESSION['o']))||(!is_numeric($_SESSION['m']))||($_SESSION['o'] != md5(agent()))||($_SESSION['s'] != md5(ip())))) { 
   session_destroy();unset($s);unset($m); 
   include_once C_PATH.'/templates/'.C_TEMP.'/header.php';
   printm($error);}
}

function ssecurity($var,$error) {
unset($s);unset($m);global $stime,$w,$l;
if(($var)&&((!isset($_SESSION['s']))||(!isset($_SESSION['m']))||(!isset($_SESSION['o']))||(!is_numeric($_SESSION['m']))||($_SESSION['o']) != md5(agent())||($_SESSION['s']) != md5(ip()))) { 
   session_destroy();unset($s);unset($m); 
   include_once C_PATH.'/templates/'.C_TEMP.'/sheader.php';
   sprintm($error);}
}

function admin_security($error) {
unset($s);unset($m);unset($adminlogin);unset($adminpass);unset($adminip);global $stime,$w,$l,$x;
if((!isset($_SESSION['adminlogin']))||(!isset($_SESSION['adminpass']))||(!isset($_SESSION['adminip']))||($_SESSION['adminip'] != md5(ip()))||($_SESSION['adminlogin'] != md5(C_ADMINL))||($_SESSION['adminpass'] != md5(C_ADMINP))) { 
   session_destroy();unset($adminlogin);unset($adminpass);unset($adminip);
   include_once C_PATH.'/templates/'.C_TEMP.'/header.php';
   printm($error);}
}

function login($num) {
  global $w;
  switch ($num) {
  case '2':return $w[60];break;
  default:return $w[172];break; // By default login by ID
  }
}

function crm($m,$s) {
$mc='';for ($i=0; $i < strlen($m); $i++) {
$mc .= "&#".ord(substr($m,$i)).";";}
$mc = "<a href=\"mailto:".$mc."?subject=".$s."\">".$mc."</a>"; 
return $mc;}   

function c_email($email) {
	if (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email)) {
		return 1;} else {
		return 0;}
}

function pregtrim($str) {
   return preg_replace("/[^\x20-\xFF]/","",@strval($str));
}
function checkurl($urll) {
   $urll=trim(pregtrim($urll));
   if (strlen($urll)==0) return 1;
   if (!preg_match("~^(?:(?:https?|ftp|telnet)://(?:[a-z0-9_-]{1,32}"."(?::[a-z0-9_-]{1,32})?@)?)?(?:(?:[a-z0-9-]{1,128}\.)+(?:com|net|"."org|mil|edu|arpa|gov|biz|info|aero|inc|name|[a-z]{2})|(?!0)(?:(?"."!0[^.]|255)[0-9]{1,3}\.){3}(?!0|255)[0-9]{1,3})(?:/[a-z0-9.,_@%&"."?+=\~/-]*)?(?:#[^ '\"&<>]*)?$~i",$urll,$ok)) return -1;   
   if (!strstr($urll,"://")) $urll="http://".$urll;
   $urll=preg_replace("~^[a-z]+~ie","strtolower('\\0')",$urll);
   return $urll;
}

function mysql2data($sqldata,$b=NULL) {
   global $wmm;
   $sqldata=str_replace(' ','-',$sqldata);
   $val = explode('-', $sqldata);
   if($b==NULL) {
      $data= substr($wmm[intval($val[1])],0,3).' '.$val[2].' '.$val[0];
   } else {
   $bd=$val[0].$val[1].$val[2];$dat=date("Ymd",time());
   $data=intval(($dat - $bd) / 10000);
   }
   return $data;
}

function ip() { 
if (getenv('HTTP_X_FORWARDED_FOR')) $ip = getenv('HTTP_X_FORWARDED_FOR');
elseif(getenv('REMOTE_ADDR')) $ip = getenv('REMOTE_ADDR'); 
else $ip = getenv('HTTP_CLIENT_IP');
$error='';$not_detect='0.0.0.0'; 
$ipnum=explode('.', $ip);
if (sizeof($ipnum) == '4') {
for($i=0;$i<4;$i++) {
if ($ipnum[$i] != intval($ipnum[$i]) || $ipnum[$i] > 255 || $ipnum[$i] < 0) $error='1';
}
}
else $error='1';
$real_ip = ($error) ? $not_detect : $ip;
return $real_ip;
} 


function agent() { 
  $agent = (getenv('HTTP_USER_AGENT')) ? getenv('HTTP_USER_AGENT') : 'Unknown'; 
  return $agent;
} 

function sendmail($from,$to,$sub,$mes,$type) {
switch ($type) {
case 'html':$h='text/html';break;
case "text":$h="text/plain";break;
default:$h="text/plain";break;}
$head="MIME-Version: 1.0\r\nContent-Type: ".$h."; charset=".C_CHARSET."\r\nFrom: ".$from."\r\nReply-To: ".$from."\r\nX-Mailer: ".C_SNAME;
@mail($to,$sub,$mes,$head);
}

function int2ip($i) {
   $d[0]=(int)($i/256/256/256);
   $d[1]=(int)(($i-$d[0]*256*256*256)/256/256);
   $d[2]=(int)(($i-$d[0]*256*256*256-$d[1]*256*256)/256);
   $d[3]=$i-$d[0]*256*256*256-$d[1]*256*256-$d[2]*256;
   return "$d[0].$d[1].$d[2].$d[3]";
}

function online_users() {
    $sec = 300;
    mysql_query("DELETE FROM ".C_MYSQL_ONLINE_USERS." WHERE (time < DATE_SUB(NOW(), INTERVAL ".$sec." SECOND) AND NOW() > ".$sec.") or time > NOW()")  or die("Delete Error<br>".mysql_error());
    unset($m);
    if((isset($_SESSION["m"]))&&(is_numeric($_SESSION["m"]))) {
    mysql_query("INSERT INTO ".C_MYSQL_ONLINE_USERS." VALUES (NOW(),".$_SESSION['m'].")") or die("Write Error<br>".mysql_error());
    }                             
    $result = mysql_query("SELECT count(DISTINCT user) as total FROM ".C_MYSQL_ONLINE_USERS) or die("Read Error<br>".mysql_error());
    $trows = mysql_fetch_array($result);
    $total = $trows['total'];
    return $total; 
}

function online_quests() {
    $sec = 300;unset($m);
    mysql_query("DELETE FROM ".C_MYSQL_ONLINE_QUESTS." WHERE (time < DATE_SUB(NOW(), INTERVAL ".$sec." SECOND) AND NOW() > ".$sec.") or time > NOW()")  or die("Delete Error<br>".mysql_error());
    if(!isset($_SESSION['m'])) {
    mysql_query("INSERT INTO ".C_MYSQL_ONLINE_QUESTS." VALUES (NOW(),INET_ATON('".ip()."'))") or die("Write Error<br>".mysql_error());
    }                             
    $result = mysql_query("SELECT count(DISTINCT ip) as total FROM ".C_MYSQL_ONLINE_QUESTS) or die("Read Error<br>".mysql_error());
    $trows = mysql_fetch_array($result);
    $total = $trows['total'];
    return $total; 
}

if (isset($_POST)) {while(list($name,$value) = each($_POST))
{$$name = $value;};}; 

if (isset($_GET)) {while(list($name,$value) = each($_GET))
{$$name = $value;};}; 

// Don`t working from 2.0.5
function filename() {return $_ENV['PHP_SELF'];}

function s() {return $SID = C_SESS ? SID : '';}
?>