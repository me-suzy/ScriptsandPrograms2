<?
/*
Copyright Information
Script File :  Form.php
Creator:  Jose Blanco (snyper)
Version:  1.0
Date Created: Feb. 20 / 2005
Released :  Feb. 27 / 2005
website: http://x-php.com , Shadowphp.net
e-mail: joseblanco.jr@g-mail.com
Aim: xphp snyper , Junior Snyper
please keep this copyright in place. :)
*/
include_once("./inc/form.db.php");
include_once("./inc/subject.db.php");
include_once("./inc/emails.db.php");
include_once("./inc/options.db.php");
include_once("./inc/functions.php");
// now lets do the email
if($_POST['send'] == true){
if($op1=="0") $f1 = "";  
else $f1 = $_POST['f1'];
if($op2=="0") $f2 = "";
else $f2= $_POST['f2'];
if($op3=="0") $msg3==""; 
else $msg3=$_POST["f3"]; 
if($op4=="0") $msg4==""; 
else $msg4=$_POST["f4"];
if($op5=="0") $msg5==""; 
else $msg5=$_POST["f5"];
if($op6=="0") $msg6==""; 
else $msg6=$_POST["f6"];
if($op7=="0") $msg7=="";
else $msg7=$_POST["f7"];
if($op8=="0") $msg8=="";
else $msg8=$_POST["f8"];
if($op9=="0") $msg9==""; 
else $msg9=$_POST["f9"];
if($op10=="0")  $msg10=="";
else $msg10=$_POST["f10"]; 
$to = $_POST['email2'];
$subject2 = $_POST['subject2'];


if(!$f1){ echo"&middot; &nbsp;Please Fill In your Name.&nbsp;<br> <a href=\"javascript:history.go(-1)\">Go Back</a>";} 
elseif(!$f2){ echo"&middot; &nbsp;Please Fill your E-mail.&nbsp;<br> <a href=\"javascript:history.go(-1)\">Go Back</a>";}
elseif(!eregi("^[a-z0-9\-\_\.]+@[a-z0-9\-]+\.[a-z0-9\-\.]+$", $f2)){
		echo "<br>&nbsp;That is not a valid email address.&nbsp;<br><a href=\"javascript:history.go(-1)\">Go Back</a>";
} 
else{
//Anti-flood, required fields, E-mail format and attachment restictions
$ip = getip();
if(checkifflooding($ip, $floodtime)) {
echo <<<html
 <table width="400" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#B5B59C">
  <tr>
    <td bgcolor="#FFFFCC"><div align="center"><font color="#0000FF">You just used this form! <br>
Please wait some time before using it again!</font></div></td>
  </tr>
</table>

html;

}else{
$mailheaders  = "MIME-Version: 1.0\r\n";
$mailheaders .= "Content-type: text/html; charset=iso-8859-1\r\n";
$mailheaders .= "From: $f1 <$f2>\r\n";
$mailheaders .= "Reply-To: $f1 <$f2>\r\n"; 
if($op3=="0") $msg3==""; 
else $body ="<strong>$bn3:</strong> &nbsp; $msg3 <br>";  
if($op4=="0") $msg4==""; 
else $body .="<strong>$bn4:</strong> &nbsp; $msg4 <br>"; 
if($op5=="0") $msg5==""; 
else $body .="<strong>$bn5:</strong> &nbsp; $msg5 <br>"; 
if($op6=="0") $msg6==""; 
else $body .="<strong>$bn6:</strong> &nbsp; $msg6 <br>"; 
if($op7=="0") $msg7=="";
else $body .="<strong>$bn7:</strong> &nbsp; $msg7 <br>"; 
if($op8=="0") $msg8=="";
else $body .="<strong>$bn8:</strong> &nbsp; $msg8 <br>"; 
if($op9=="0") $msg9==""; 
else $body .="<strong>$bn9:</strong> &nbsp; $msg9 <br>"; 
if($op10=="0")  $msg10=="";
else $body .="<strong>$bn10:</strong> &nbsp; $msg10 <br>"; 

mail($to,$subject2,stripslashes($body), $mailheaders);
echo <<<html
 <table width="400" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#B5B59C">
  <tr>
    <td bgcolor="#FFFFCC"><div align="center"><font color="#0000FF">Thank you $f1, your message has been
        successfully sent.</font></div></td>
  </tr>
</table>

html;
// Flood Protection
$ip = getip();			
	$time = time();
	
	$fp = fopen("./inc/flood.db.php","a+");
	$lock = flock($fp, LOCK_EX);
	if ($lock) { 
     	fseek($file_pointer, 0, SEEK_END);  
		fputs($fp,"$ip=$time");
		flock($fp, LOCK_UN);  
	}
	fclose($fp);
// end flood protection
}

}
}//end email
else{
// check if fields are on
if($op1=="0"){ $bn1="&nbsp;"; $f1=="&nbsp;"; }
else $f1="<input name=\"f1\" type=\"text\" size=\"$w1\" maxlength=\"$m1\">";
if($op2=="0"){ $bn2="&nbsp;"; $f2=="&nbsp;"; }
else $f2="<input name=\"f2\" type=\"text\" size=\"$w2\" maxlength=\"$m2\">";
if($op3=="0"){ $bn3="&nbsp;"; $f3=="&nbsp;"; }
else $f3="<input name=\"f3\" type=\"text\" size=\"$w3\" maxlength=\"$m3\">";
if($op4=="0"){ $bn4="&nbsp;"; $f4=="&nbsp;"; }
else $f4="<input name=\"f4\" type=\"text\" size=\"$w4\" maxlength=\"$m4\">";
if($op5=="0"){ $bn5="&nbsp;"; $f5=="&nbsp;"; }
else $f5="<input name=\"f5\" type=\"text\" size=\"$w5\" maxlength=\"$m5\">";
if($op6=="0"){ $bn6="&nbsp;"; $f6=="&nbsp;"; }
else $f6="<input name=\"f6\" type=\"text\" size=\"$w6\" maxlength=\"$m6\">";
if($op7=="0"){ $bn7="&nbsp;"; $f7=="&nbsp;"; }
else $f7="<input name=\"f7\" type=\"text\" size=\"$w7\" maxlength=\"$m7\">";
if($op8=="0"){ $bn8="&nbsp;"; $f8=="&nbsp;"; }
else $f8="<textarea name=\"f8\" cols=\"$w8\" rows=\"$h1\"></textarea>";
if($op9=="0"){ $bn9="&nbsp;"; $f9=="&nbsp;"; }
else $f9="<textarea name=\"f9\" cols=\"$w9\" rows=\"$h2\"></textarea>"; 
if($op10=="0"){ $bn10="&nbsp;";  $f10=="&nbsp;"; }
else $f10="<textarea name=\"f10\" cols=\"$w10\" rows=\"$h3\"></textarea>";
//subjects

if($sub2 =="" && $sub3 =="" && $sub4 =="" && $sub5 =="" && $sub6 =="" && $sub7 =="" &&$sub8 =="" && $sub9 =="" && $sub10 =="" ){
$subject.= <<<html
<input name="subject2" type="hidden" value="$sub1">
html;
}else{
$subject= <<<html
<select name="subject2">
html;
if($sub1=="") $subject .=""; 
else $subject .="<option value=\"$sv1\">$sub1</option>"; 
if($sub2=="") $subject .="";
else $subject .="<option value=\"$sv2\">$sub2</option>"; 
if($sub3=="") $subject .="";
else $subject .="<option value=\"$sv3\">$sub3</option>"; 
if($sub4=="") $subject .="";
else $subject .="<option value=\"$sv4\">$sub4</option>"; 
if($sub5=="") $subject .="";
else $subject .="<option value=\"$sv5\">$sub5</option>"; 
if($sub6=="") $subject .="";
else $subject .="<option value=\"$sv6\">$sub6</option>"; 
if($sub7=="") $subject .="";
else $subject .="<option value=\"$sv7\">$sub7</option>"; 
if($sub8=="") $subject .="";
else $subject .="<option value=\"$sv8\">$sub8</option>"; 
if($sub9=="") $subject .="";
else $subject .="<option value=\"$sv9\">$sub9</option>"; 
if($sub10=="") $subject .="";
else $subject .="<option value=\"$sv10\">$sub10</option>"; 
$subject.= <<<html
</select>
html;
}
//emails
if($n2 =="" && $n3 =="" && $n4 =="" && $n5 =="" && $n6 =="" && $n7 =="" &&$n8 =="" && $n9 =="" && $n10 =="" ){
$email= <<<html
<input name="email2" type="hidden" value="$e1">
html;
}else{
$email= <<<html
<select name="email2">
html;
if($n1=="") $email .=""; 
else $email .="<option value=\"$e1\">$n1</option>"; 
if($n2=="") $subject .="";
else $email .="<option value=\"$e2\">$n2</option>"; 
if($n3=="") $email .="";
else $email .="<option value=\"$e3\">$n3</option>"; 
if($n4=="") $email .="";
else $email .="<option value=\"$e4\">$n4</option>"; 
if($n5=="") $email .="";
else $email .="<option value=\"$e5\">$n5</option>"; 
if($n6=="") $email .="";
else $email .="<option value=\"$e6\">$n6</option>"; 
if($n7=="") $email .="";
else $email .="<option value=\"$e7\">$n7</option>"; 
if($n8=="") $email .="";
else $email .="<option value=\"$e8\">$n8</option>"; 
if($n9=="") $email .="";
else $email .="<option value=\"$e9\">$n9</option>"; 
if($n10=="") $email .="";
else $email .="<option value=\"$e10\">$n10</option>"; 
$email .= <<<html
</select>
html;
}
//submit button
$submit="<input type=\"submit\" value=\" Send \">";
include("./inc/formtemp.db.php");
$template = str_replace("{fname1}", $bn1, $template);
$template = str_replace("{field1}", $f1, $template);
 $template = str_replace("{fname2}", $bn2, $template);
 $template = str_replace("{field2}", $f2, $template);
$template = str_replace("{fname3}", $bn3, $template);
$template = str_replace("{field3}", $f3, $template);
$template = str_replace("{fname4}", $bn4, $template);
$template = str_replace("{field4}", $f4, $template);
$template = str_replace("{fname5}", $bn5, $template);
$template = str_replace("{field5}", $f5, $template);
$template = str_replace("{fname6}", $bn6, $template);
$template = str_replace("{field6}", $f6, $template);
$template = str_replace("{fname7}", $bn7, $template);
$template = str_replace("{field7}", $f7, $template);
$template = str_replace("{fname8}", $bn8, $template);
$template = str_replace("{field8}", $f8, $template);
 $template = str_replace("{fname9}", $bn9, $template);
 $template = str_replace("{field9}", $f9, $template);
$template = str_replace("{fname10}", $bn10, $template);
$template = str_replace("{field10}", $f10, $template);
//submit button
$template = str_replace("{submit}", $submit, $template);
//subject
$template = str_replace("{subjects}", $subject, $template);
//email
$template = str_replace("{emails}", $email, $template);

echo "<form action=\"http://".$HTTP_HOST.$REQUEST_URI."\" method=\"post\">
<input name=\"send\" type=\"hidden\" value=\"true\">
";
echo $template;
echo "</form>";



}


?>
