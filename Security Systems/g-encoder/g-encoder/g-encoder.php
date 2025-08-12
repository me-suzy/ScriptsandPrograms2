<?php
/**
 * G-Encoder v1.0
 */
$version = "1.0";
/*
 * Copyright 2005 Yohanes A. Pradono
 * http://www.gravitasi.com
 * Contact me via Yahoo!Messenger with ID : pradonie
 * or email : donie@gravitasi.com
 * or Friendster.com : donie@gravitasi.com
 * 
 * This is not a code encryptor. This script uses base64_encode
 * and Zlib to encode input string. Someone who knows PHP probably
 * could decodes the results.
 * This script also uses ZLIB to compress input string,
 * but since I added additional obfuscator,
 * it is effectively only for string more than 2.5 KBytes
 * 
 * I intend to use OOP here, although it's not necessary,
 * because this is to train me familiarizing with OOP.
 *
 * This script is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this script; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 * 
 * If you are looking for really good PHP code encryptors, try using these:
 * - Zend SafeGuard (including Zend Encoder) by Zend [ http://zend.com ]
 * - IonCube [ http://ioncube.com ]
 * - PHTML Encoder by RS Software Lab [ http://www.rssoftlab.com ]
 * - SourceCop - [ http://sourcecop.com ] 
 * - SourceGuardian [ http://sourceguardian.com ]
 * - phpSHIELD [ http://phpshield.com ]
 * 
 * If you like this script and want to donate me something but don't know what is it,
 * these are my wishlists:
 *
 * 1. Linux SuSE professional latest stable version (I always need this)
 * 2. Job as PHP Programmer (all type: online, part/full time.)
 * 3. Having a Girlfriend :(
 *
 */

// CSS setting
$css = './style/mcdigit.css';

// Start G-Encoder's header
header("Expires: Sun, 10 Jan 1982 03:00:00 GMT"); // My Birthday :)
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
if ($_SERVER['SERVER_PROTOCOL'] == "HTTP/1.0") {
    header("Pragma: no-cache"); // HTTP/1.0
} else {
    header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
} 

class GE {
    /*
     variable $gz_type
     can be gzdeflate or gzcompress
    */
    var $gz_type = 'gzdeflate';
    var $level_compression = 9;

    /*
	this is CSS path
	*/ 
    // var $css = './style/default.css';
    /*
    base64 encoded of g-encoder's icon
    */
    var $icon_enc = 'iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAACZUExURf///57X4Q86h0BAQHZ2drS0tNHR0dLs8Y+jx+X099jv8x9Hj+z3+Q5gmbjh6d/y9cvp76vc5V98r3+Wvw9Mj6+917Lf53i3ze/y9ylhnE9vpz6Isfn8/Q5ZlW6vyT9hn6XZ4/L6+8Xn7WqXvaqqqpSUlFufv1JSUlGYutvb25+wzw9Hjaenp4ityyt4p6a71YSsyllZWYvH1+NQPi0AAAD+SURBVHjapNN7c4IwDADwJHukhVZAFOf7sel0072+/4dbioPT2Xa3M39wUH6XhDYAXBn7P97f3/7caOSrQFKhRId1AHSwCT9YIeauhGE/mEjyaA8ZYhYFCaKuQb9LRCMFoOgY/TYDO1AeV+cXABaIRuMHUSkPqs6gzr5CUmCOQ3ppVn8DYBGD3giCwImZfQR4kMJp24M6OQtTzey2WoYB8MC+YbEESFNfCVdkON3IjkbAgabjLxMBSflqbe+0B+qeA3gfC5irMDAoF89h6QbkiBMfKAo2Dshu5d7jLtqBWwWGlnlRz2wWG3u+7O+fIPbr7e4isf6Ep+ebaHwLMACROw2bqcdjBAAAAABJRU5ErkJggg==';

    /* 
    function compress
    */

    function compress($string, $type, $level)
    {
        global $uncomp, $uncompress;
        switch ($type) {
            case 'gzdeflate':
                $string = gzdeflate($string, $level);
                $uncomp = 'gzinflate';
                $uncompress = base64_encode('gzinflate'); //used for output
                return $string;
                break;

            case 'gzcompress':
                $string = gzcompress($string, $level);
                $uncomp = 'gzuncompress';
                $uncompress = base64_encode('gzuncompress'); //used for output
                return $string;
                break;
        } 
        return $string;
    } 

    /*
	* default Lock Message
	*/
    var $default_msg = 'This script is protected by <a style="color:cyan" href="http://www.gencoder.sf.net"><b><font color="#330099">G-Encoder</font></b></a>';

    /* 
	* Encode and Decode function
	* @param string $string string to be encoded
	* @param int $key random integer for salt key
    */

    function Encode($string, $key)
    {
        $result = '';
        $string = $this->clean_string($string);
        for($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        } 
        $result = $this->compress($result, $this->gz_type, $this->level_compression);
        return base64_encode($result);
    } 
    function Decode($string, $key)
    {
        global $uncomp;
        $result = '';
        $string = base64_decode($string);
        $string = $uncomp($string);
        for($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        } 
        return $result;
    } 

    /*
     Generate random key
    */
    function randomstring()
    {
        return mt_rand(99999999, 9999999999);
    } 

    /*
      clean_string
      to clean the string from backslash
    */
    function clean_string($str)
    {
        $str = stripslashes(trim($str));
        return $str;
    } 

    /*
      ShowInTextarea
      to show the input inside <textarea>
    */
    function ShowInTextarea($str)
    {
        $str = htmlentities($this->clean_string($str));
        return $str;
    } 

    function DecodeStr($str)
    {
        $img = base64_decode($str);
        return $img;
    } 

    /*
    Time out codes
    */
    function _DateToStr($str)
    {
        $str = strtotime($str); 
        // because 'valid until' means it still valids on that day
        $str = $str + (24 * 60 * 60);
        return $str;
    } 
    function _PeriodToStr($days)
    {
        $ts = time() + ($days * 24 * 60 * 60);
        return $ts;
    } 

    function TimeOut_str($ts_limit, $msg)
    { 
        // I use '>' here coz 1 sec after timestamp limit, the script is stopped
        $str = <<<EOG
if(time()>$ts_limit){die($msg);}
EOG;
        return $str;
    } 

    function formatDate($str)
    {
        $str = strtotime($str);
        return $str;
    } 

    /*
	* Address Binding
	* @param string $str contains ip and hostname separated by comas
	* @return string contain code for address binding 
	*/
    function addr_binding_output($str)
    {
        $addr_binding = <<<EOG
function is_ip(\$what){if(ereg('^([0-9]{1,3}\.){3,3}[0-9]{1,3}',\$what)){return true;}else{return false;} }function checkip(\$ip,\$csiext){\$range=explode("/",\$csiext);if (!empty(\$range[1]) AND \$range[1] < 32) {\$maskbits=\$range[1];\$hostbits=32-\$maskbits;\$hostcount = pow(2, \$hostbits)-1;\$ipstart=ip2long(\$range[0]);\$ipend=\$ipstart+\$hostcount;if(ip2long(\$ip)>\$ipstart){if(ip2long(\$ip)<\$ipend){return(true);}}}else{if (ip2long(\$ip)==ip2long(\$range[0])){return(true);}}return(false);}\$check=array();\$check="$str";\$c=explode(',',\$check);\$ip_array=array();\$hostname_array=array();\$server_ip = \$_SERVER['SERVER_ADDR'];\$server_hostname=\$_SERVER['HTTP_HOST'];for(\$i = 0;\$i<count(\$c);\$i++){if(is_ip(\$c[\$i])){\$ip_array[].=trim(\$c[\$i]);}else{\$hostname_array[].=trim(\$c[\$i]);}}\$v=0;for(\$i=0;\$i<count(\$ip_array);\$i++){if(checkip(\$server_ip,\$ip_array[\$i])){\$v=1;}}for(\$i=0;\$i<count(\$hostname_array);\$i++){if(eregi(trim(\$hostname_array[\$i]),\$server_hostname)){\$v=1;}elseif(eregi(substr(\$hostname_array[\$i],1),\$server_hostname)){\$v=1;}}
EOG;
        return $addr_binding;
    }
    
    /*
     * hex_decode
     * @desc convert bin2hex'd string to it's original string
     * @return string
     */
    function hex_decode($string)  {
       for ($i=0; $i < strlen($string); $i)  {
       $decoded .= chr(hexdec(substr($string,$i,2)));
       $i = (float)($i)+2;
       }
       return $decoded;
    }
}

// creating instances of class GE
$obj = new GE;

if ($_GET['get'] == 'icon') {
    header('Content-Type: image/png');
    echo $obj->DecodeStr($obj->icon_enc);
    exit();
} 
// COLLECTING DATA BEFORE ENCODED
// set error to false
$error_flag = 0;
$error_msg = array();
// get the random key
$key = $obj->randomstring();
// Time out
if ($_POST['use_time_out']) {
    $before_encoded .= "\$t = true;";
    if ($_POST['time_out_radio'] == 'period') {
        $batas_waktu = $obj->_PeriodToStr($_POST['postPeriod']);
        $before_encoded .= <<<EOG
if(time()>$batas_waktu){\$t=false;}
EOG;
    } else if ($_POST['time_out_radio'] == 'date') {
        $batas_waktu = $obj->_DateToStr($_POST['postDate']);
        $before_encoded .= <<<EOG
if(time()>$batas_waktu){\$t=false;}
EOG;
    } else {
    } 
} 
// end of time out
// Begin of addr binding
if ($_POST['use_addr_binding'] == 1) {
    $before_encoded .= $obj->addr_binding_output($_POST['postAddr']);
} 
// end of addr binding
// lock message
$lmsg = addslashes($_POST['lock_msg']);
$before_encoded .= <<<EOG
if((isset(\$v) AND \$v==0) OR (isset(\$t) AND \$t==false)){die('$lmsg');}
EOG;

$before_encoded .= $_POST['postSource'];
// check the errors
if ($_POST['go'] == 'encode') {
    if (trim($_POST['postSource']) == '') {
        $error_msg[] = "Please write some codes to be encoded<br />";
        $error_flag = 1;
    } 
    if ($_POST['use_time_out'] AND $_POST['time_out_radio'] == 'period' AND $_POST['postPeriod'] == '') {
        $error_msg[] = "You set the timeout to \"On\" AND choosed Period, but did not set the  Period value<br />";
        $error_flag = 1;
    } else if ($_POST['use_time_out'] AND $_POST['time_out_radio'] == 'date' AND $_POST['postDate'] == 'mm/dd/yyyy') {
        $error_msg[] = "You set the timeout to \"On\" AND choosed Date, but did not set the Date value<br />";
        $error_flag = 1;
    } else if ($_POST['use_time_out'] AND !$_POST['time_out_radio']) {
        $error_msg[] = "You set the Timeout to \"On\" but did not choose between Period or Date!<br />";
        $error_flag = 1;
    }
    if ($_POST['use_addr_binding'] AND trim($_POST['postAddr']) == ""){
        $error_msg[] = "You set the Address Binding to \"On\" but did not fill the value!<br />";
        $error_flag = 1;
    }
} 
// Begin of HTML CODE
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="en">
<head>
    <title>G-Encoder
	 <?=$version?> // Free PHP Encoder using base64_encode() and Zlib</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
    <meta name="MSSmartTagsPreventParsing" content="TRUE" />
    <meta http-equiv="expires" content="-1" />
<?php
if ($_SERVER['SERVER_PROTOCOL'] == "HTTP/1.0") {
    echo("<meta http-equiv=\"pragma\" content=\"no-cache\" />\n");
} else {
    echo("<meta http-equiv=\"Cache-Control\" content=\"no-cache, must-revalidate\" />\n");
} 

?>
    <meta name="Generator" content="G-Encoder <?=$version?>" />
    <meta name="Author" content="Yohanes A. Pradono" />
    <meta name="Keywords" content="PHP, code, codes, encode, encoder, obfuscator, encrypt, encrypter, encryptor, compressor, compression, decode, decoder, base64_encode, base64_decode, base64, zlib, gzcompress, gzuncompress, gzinflate, gzdeflate" />
    <meta name="Description" content="PHP Encoder using base64_decode and Zlib" />
    
<link rel="SHORTCUT ICON" href="<?=$_SERVER['PHP_SELF']?>?get=icon" />
    
<link rel="stylesheet" type="text/css" href="<?=$css;

?>" />

<script language="Javascript" type="text/javascript">
<!--

/*
Select and Copy form element script - By Dynamicdrive.com
For full source, Terms of service, and 100s DTHML scripts
Visit http://www.dynamicdrive.com
*/

//specify whether contents should be auto copied to clipboard (memory)
//Applies only to IE 4+
//0=no, 1=yes
var copytoclip=1

function HighlightAll(theField) {
var tempval=eval("document."+theField)
tempval.focus()
tempval.select()
if (document.all&&copytoclip==1){
therange=tempval.createTextRange()
therange.execCommand("Copy")
window.status="Contents highlighted and copied to clipboard!"
setTimeout("window.status=''",1800)
}
}
//-->
</script>

<script language="Javascript" type="text/javascript">
<!--
function sample()
{
    this.document.ori_code.postSource.value = unescape('%0D%0Aclass%20foo%20%7B%0D%0A%20%20%20%20%2F%2F%20example%20of%20PHP%20codes%0D%0A%20%20%20%20var%20%24a%3B%0D%0A%20%20%20%20var%20%24b%3B%0D%0A%20%20%20%20function%20display%28%29%20%7B%0D%0A%20%20%20%20%20%20%20%20echo%20%22This%20is%20class%20foo%22%3B%0D%0A%20%20%20%20%20%20%20%20echo%20%22a%20%3D%20%22.%24this-%3Ea.%22%22%3B%0D%0A%20%20%20%20%20%20%20%20echo%20%22b%20%3D%20%7B%24this-%3Eb%7D%22%3B%0D%0A%20%20%20%20%7D%0D%0A%20%20%20%20function%20mul%28%29%20%7B%0D%0A%20%20%20%20%20%20%20%20return%20%24this-%3Ea%2A%24this-%3Eb%3B%0D%0A%20%20%20%20%7D%0D%0A%7D%3B%0D%0A%0D%0A%24foo1%20%3D%20new%20foo%3B%0D%0A%24foo1-%3Ea%20%3D%202%3B%0D%0A%24foo1-%3Eb%20%3D%205%3B%0D%0A%24foo1-%3Edisplay%28%29%3B%0D%0A%0D%0A%3F%3E%0D%0A%0D%0A%3Chtml%3E%0D%0A%20%3Cbody%3E%0D%0A%20%20This%20is%20HTML%0D%0A%20%3C%2Fbody%3E%0D%0A%3C%2Fhtml%3E%0D%0A%0D%0A%3C%3F%0D%0A%2F%2F%20back%20to%20PHP%20again%0D%0Aecho%20%24foo1-%3Emul%28%29.%22%22%3B%0D%0A');
    this.document.ori_code.postSource.focus();
    return true;
}

function about(){
window.open('./about.php', 'About', 'width=325,height=342,location=0,menubar=0,toolbar=0,scrollbars=yes,resizable=1,status=0,screenx=245,screeny=102');
}
//-->
</script>    

</head>
<body>

<div id="header">&nbsp;</div>
<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
<tbody>

<tr>
<td class="navCell">
<div class="cpNavOn">
<img src="http://<?=$_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']?>?get=icon" border="0" /><br />
<a href="http://gencoder.sf.net">&nbsp;G-Encoder <?=//$version?>&nbsp;</a>
</div>
<div class="defaultBold" align="center">
Free PHP Encoder using base64_encode() and Zlib
</div>
</td>

</tr>
</tbody></table>

<div id="content" align="center">
<table style="width: 90%;" class="tableBorder" border="0" cellpadding="0" cellspacing="0">
<tbody><tr>
<td class="tablePad">

<form name="ori_code" method="POST" action="<?=$_SERVER["PHP_SELF"]?>">

<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
<tbody>

<?php 
// start to create the output
// ERROR MESSAGE
if ($error_flag == 1) {

    ?>
<tr>
<td class="tableCellTwo">   
<div id="error"><div class="errorheading">
<?php
    echo "<b>ERROR!</b><br />";
    for($i = 0;$i < count($error_msg);$i++) {
        echo "- " . $error_msg[$i];
    } 

    ?>
</div></div>
</td>
</tr>
<?php
} 
// if there is no error
if ($_POST['go'] == 'encode' AND $error_flag == 0) {

    ?>

<!-- Begin of Summary -->
<tr>
<td class="tableCellTwo">

<fieldset><legend> <strong>Summary</strong> </legend>
<div class="default">
Original String Length: <?php echo strlen($_POST['postSource']);

    ?><br /><br />

TimeOut : 
<?php
    if ($_POST['use_time_out'] == 1) {
        echo "<span class='on'>On</span><br />";
        if ($_POST['time_out_radio'] == 'period' AND $_POST['postPeriod']=='1') {
            echo "TimeOut option : Period <br />";
            echo "Valid for : " . $_POST['postPeriod'] . " day";
        } elseif ($_POST['time_out_radio'] == 'period' AND $_POST['postPeriod']!='1') {
            echo "TimeOut option : Period <br />";
            echo "Valid for : " . $_POST['postPeriod'] . " days";
        }else {
            echo "TimeOut option : Date<br />";
            echo "Valid until : " . $_POST['postDate'] . "";
        } 
    } else {
        echo "<span class='off'>Off</span>";
    } 

    ?><br /><br />
Address Binding :
<?php
    if ($_POST['use_addr_binding']) {
        echo "<span class='on'>On</span> <br />";
        echo "Address Allowed : " . $_POST['postAddr'] . "";
    } else {
        echo "<span class='off'>Off</span>";
    } 

    ?><br /><br />
String Length after added with additional option codes but before Encoded :
<?php
    echo strlen($before_encoded);

    ?><br />

String Length after Encoded :
<?php
    echo strlen($obj->Encode($before_encoded, $key));

    ?><br />

String Length after added with self decoder :
<?php
    $ab = $obj->Encode($before_encoded, $key);
    
    // convert string 'base64_decode' to hex
    $b64d = bin2hex('base64_decode');
    
    $output = <<<EOG
function d(\$s,\$k=''){if(\$k==''){for(\$i=0;\$i<strlen(\$s);\$i){\$d.=chr(hexdec(substr(\$s,\$i,2)));\$i=(float)(\$i)+2;}return \$d;}else{\$r='';\$f=d('$b64d');\$u=\$f('$uncompress');\$s=\$u(\$f(\$s));for(\$i=0;\$i<strlen(\$s);\$i++){\$c=substr(\$s,\$i,1);\$kc=substr(\$k,(\$i\%strlen(\$k))-1,1);\$c=chr(ord(\$c)-ord(\$kc));\$r.=\$c;}return \$r;}}eval(d("$ab",$key));
EOG;
    echo strlen($output);

    ?><br />

</div>
</fieldset>
</td>
</tr>
<!-- end of Summary -->

<!-- Begin of encoded string -->
<!--
<tr>
<td class="tableCellTwo">
Encoded string : <br />
<textarea class="textarea" rows='5' cols='100'>
<?=$obj->Encode($before_encoded, $key);

    ?>
</textarea></td>
</tr>
<!--
<!-- end of encoded string -->

<tr>
<td class="tableCellTwo">
<fieldset><legend> <strong>Output</strong> </legend>

<div>
Use this on your PHP script file : <br />
<a href="javascript:HighlightAll('ori_code.output')">Select All</a>
</div><br />
<div class="highlight_bold">&lt;?php</div><textarea class="readonly" name="output" rows='5' cols='100' readonly='readonly'>
<?php
/* this already done at above
    $enc_str .= $obj->Encode($before_encoded, $key);
    $output = <<<EOG
function h(\$s){for(\$i=0;\$i<strlen(\$s);\$i){\$d.=chr(hexdec(substr(\$s,\$i,2)));\$i=(float)(\$i)+2;}return \$d;}function d(\$s,\$k){\$r='';\$f=h('6261736536345f6465636f6465');\$u=\$f('$uncompress');\$s=\$u(\$f(\$s));for(\$i=0;\$i<strlen(\$s);\$i++){\$c=substr(\$s,\$i,1);\$kc=substr(\$k,(\$i\%strlen(\$k))-1,1);\$c=chr(ord(\$c)-ord(\$kc));\$r.=\$c;}return \$r;}eval(d("$enc_str",$key));
EOG;*/
    echo $obj->ShowInTextarea($output);
    echo "\n";
    echo "/* Encoded by G-Encoder $version */\n";

    ?>
</textarea>
<div class="highlight_bold">?&gt;</div>
</fieldset>
</td>
</tr>

<?php
    /* this used to execute your codes for testing
  do not uncomment this if able to be accessed by public
?>
<tr>
<td class="tableCellTwo">    
If it is executed : <br />
<?
echo eval($obj->Decode($obj->Encode($before_encoded, $key), $key));
?>
</td>
</tr>
<?
*/
} 

?>

<tr>
<td class="tableCellTwo">
<fieldset><legend> <strong>Input</strong> </legend>

Enter your codes here. You need to remove <b>&lt;?php</b> OR <b>&lt;?</b> at the beginning of your codes AND <b>?&gt;</b> at the end of your codes.<br /><a href="javascript:void(0)" onclick="javascript:sample()">Click to see a sample.</a><br /><br />

<div class="highlight_bold">&lt;?php</div>
<textarea class="textarea" name="postSource" rows="20" cols="100"><?=$obj->ShowInTextarea($_POST['postSource']);

?></textarea>
<div class="highlight_bold">?&gt;</div>

</fieldset>
</td>
</tr>

<!-- Begin of Time Out -->
<tr>
<td class="tableCellTwo"><fieldset><legend><input type="checkbox" class="checkbox" name="use_time_out" value="1" id="use_time_out"
<?php
if ($_POST['use_time_out']) echo "checked='checked'";

?>
/><label for="use_time_out"> <strong>Time Out</strong> (Script will be valid for/until)</label>&nbsp;</legend><br />
 <input type="radio" name="time_out_radio" id="time_out_radio_period" value="period"
<?php if ($_POST['time_out_radio'] == 'period') echo " checked='checked'";

?>
/><label for="time_out_radio_period"> Period </label><br /><input type="text" name="postPeriod" value="
<?=$_POST['postPeriod']?>
" size="2" maxlength="3" /> days<br /><br />
<input type="radio" name="time_out_radio" id="time_out_radio_date" value="date"
<?php if ($_POST['time_out_radio'] == 'date') echo " checked='checked'";

?>
/><label for="time_out_radio_date"> Date (mm/dd/yyyy)</label><br />
  <script type="text/javascript" src="js/calendar/datetimepicker.js"/></script>  
  <script type="text/javascript">
 
 <?php
$todayDay = date('d');
$todayMonth = date('m');
$todayYear = date('Y');

?>
 
  var todayDay = '<?=$todayDay?>';
  var todayMonth = '<?=$todayMonth?>';
  var todayYear = '<?=$todayYear?>';  
  
  // init the array with the days of the month for every month
  var days = new Array( 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 );

 ///
 /// customization of the javascript calendar
 ///
 var MonthName = new Array();
   MonthName[1-1] = 'January';
   MonthName[2-1] = 'February';
   MonthName[3-1] = 'March';
   MonthName[4-1] = 'April';
   MonthName[5-1] = 'May';
   MonthName[6-1] = 'June';
   MonthName[7-1] = 'July';
   MonthName[8-1] = 'August';
   MonthName[9-1] = 'September';
   MonthName[10-1] = 'October';
   MonthName[11-1] = 'November';
   MonthName[12-1] = 'December';
 
 var WeekDayName = new Array();
   WeekDayName[1-1] = 'Su';
   WeekDayName[2-1] = 'Mo';
   WeekDayName[3-1] = 'Tu';
   WeekDayName[4-1] = 'We';
   WeekDayName[5-1] = 'Th';
   WeekDayName[6-1] = 'Fr';
   WeekDayName[7-1] = 'Sa';
 
 var WindowTitle = "calendar";  
 </script>
<input name="postDate" id="postDate" class="readonly" readonly="readonly" type="text" size="10" value="
<?php 
// default date value
if ($_POST['postDate']):
    echo $_POST['postDate'];
else:
    echo "mm/dd/yyyy";
endif;

?>
" style="margin-bottom: 4px;" />
 <a href="javascript:NewCal('postDate','mmddyyyy',false,24);"><img border="0" src="images/cal.jpg" alt="" /></a>
</fieldset>
</td>
</tr>

<!-- End of Time Out -->

<!-- Begin of Adress Binding -->
<tr>
<td class="tableCellTwo">
<fieldset><legend><input class="checkbox" id="use_addr_binding" type="checkbox" name="use_addr_binding" value="1"
<?php
if ($_POST['use_addr_binding'] == 1) echo " checked='checked' ";

?>
/><label for="use_addr_binding"> <strong>Address Binding</strong> </label>&nbsp;</legend><br />
<div class="default">separated by coma, example:<br /> 213.123.321.312, slowrock.org, .black.or.id, 212.211.122.121</div>
<input type="text" name="postAddr" value="
<?php
echo $_POST['postAddr'];

?>
" size="50" /><br />
</fieldset>
</td>
</tr>
<!-- End of Address Binding -->

<!-- Begin of Lock Message -->
<tr>
<td class="tableCellTwo">
<fieldset><legend> <strong>Lock Message</strong> </legend>
<textarea class="textarea" name="lock_msg" rows="5" cols="80">
<?php
if (trim($_POST['lock_msg']) != '') {
    echo $obj->ShowInTextarea(($obj->clean_string($_POST['lock_msg'])));
} else {
    echo $obj->ShowInTextarea($obj->default_msg);
} 

?>
</textarea>
</fieldset>
</td>
</tr>
<!-- End of Lock Message -->

<tr>
<td class="tableCellTwo" align="right">
<input type="hidden" name="go" value="encode" />
<input class="submit" type="submit" name="choose" value="Encode" align="right" />
</td>
</tr>

</tbody>
</table>

</form>

</td>
</tr>
</tbody>
</table>

</div>

<div class='copyright'>
<a href='http://gencoder.sf.net' target='_blank'>G-Encoder <?=$version?></a> - Copyright &copy; 2005 - Yohanes A. Pradono

<br />
</div>

</body>
</html>