<?
function signupemail($u="") {
global $signup,$noreplyemail,$siteaddr,$r;                
$finalcode=substr(md5($u),0,8);
$mailmessage="
From: $noreplyemail@$siteaddr
 
Thank you for signing up with $siteaddr!
Please continue with the one, easy, step to activate
your account.

1) Please continue on with the registration process
by visting the web site below.

http://www.$siteaddr$signup?email=$u&code=$finalcode&r=$r

If you need to contact us for any reason, please visit
the following web page at:
http://www.$siteaddr/$contactus  Thank You!

Once again, thank you for signing up! I hope your
membership is long and enjoyable.

Sincerely,

The $siteaddr Staff
http://www.$siteaddr/";
mail ($u,"Confirm your E-Mail Address",$mailmessage,"From: noreply@$siteaddr\r\nReply-To: noreply@$siteaddr\r\n");
}

function pagecgi() {
global $p,$title;
if ($p=="adinfo") {
$title = $adinfo_title;
} elseif ($p== "$benefits") {
$title = $benefits_title;
} elseif ($p=="cookiejar") {
$title = $cookiejar_title;
} elseif ($p== "rlinks") {
$title = $rlinks_title;
} elseif ($p== "contactus") {
$title = $contactus_title;
} elseif ($p== "help") {
$title = $help_title;
} elseif ($p=="terms") {
$title = $terms_title;
} elseif ($p=="privacy") {
$title = $privacy_title;
} elseif ($p=="enter") {
$title = $enter_title;
} else {
$title = $index_title;
}
}

function mysqlstart() {
global $mysql_port,$mysql_host,$mysql_database,$mysql_username,$mysql_password,$mysql_prefix;
mysql_pconnect($mysql_host,$mysql_username,$mysql_password);
mysql_select_db($mysql_database);
}

function checklogin() {
global $usern,$unpa,$t,$top_links,$userdatadir,$todo,$ufile,$siteaddr,$noreplyemail,$u,$p;   
$todo=strtolower($todo);
$t=strtolower($t);
if (!$u){
message('Error','You must be logged in to view this page');
} 
if (!file_exists($userdatadir.$u)) {   
message('We do not have that e-mail address on file. Remember its case sensitive','Please try again');
}
$fp=fopen($userdatadir.$u,"r");
$login=split("\n",fread($fp,250000));
fclose($fp);
$flag = 0;
for ($idx=0;$idx<count($login);$idx++){
list($first,$second)=split("::",trim($login[$idx]));
$ufile[$first]=$second;
if ($first=="password") {
$rp = $second;
if ($p!=crypt($second,"Cc")) {
$p = crypt($p,"Cc");      
}
if ($p!=crypt($second,"Cc")) {
$flag = 1;
}}
if ($first=="email") {
$resendemail = $second;
}
}
$unpa = "u=" . $u . "&p=" . $p;
$usern="u=".$u;
$top_links =str_replace("unpa",$unpa,$top_links);
$top_links =str_replace("usern",$usern,$top_links);
if ($todo=="sendpass") {  
$message="
Remember! Your E-Mail Address and password
are CaSe SeNsAtIvE. If you are having
trouble logging into your account, copy
and paste your E-Mail Address and password from
below into the login area of the main page.
 
Your E-Mail Address: $u
Your Password: $rp

Both your E-Mail Address and password
are case sensative. Make sure you
type both of them in correctly.

Sincerely,

The $siteaddr Staff
";
mail($resendemail,"Your $siteaddr Password",$message,"From: $noreplyemail@$siteaddr\r\nReply-To: $noreplyemail@$siteaddr\r\n");
message('E Mail Resent','Please check your e-mail for your password.
It should arrive anywhere from 1 to 120 minutes from now');
}
if ($flag != 1) {
if ($todo=="cancel") {
unlink($userdatadir.$u);
message('Account Deleted','Sorry to see you leave');
}
} else {
message('Bad Password','Please try again');
}
return $ufile;
}


function message($a="",$b="",$c=""){ 
echo '<h1 align="center">'.$a.'</h1>
<b>'.$b.'</b><br> 
<i>'.$c.'</i>';
include("bottom.php");
exit;
}

function rotatebanner(){
error_reporting(0);
global $bannerdatadir,$bannerdata;
$banners = file($bannerdata);
srand((double)microtime()*1000000);
$rnum = rand(0,count($banners)-1);
list ($dtitle,$html,$user,$clicks,$style) = split("::", $banners[$rnum]);
   
if (ereg("impressions",$style) && $clicks>0)  {
                if (file_exists($bannerdatadir.$dtitle)) {
                        $fp=fopen ($bannerdatadir.$dtitle,"a");
		        flock($fp,2);
                        fwrite($fp,"1\n");
			flock($fp,3);
                        fclose($fp);
                }
}
error_reporting(1);
return($html);
}
