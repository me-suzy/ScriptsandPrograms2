<?php
if (!ereg("index\.php",$self)){
header("Location: index.php");
    exit();
}
?>
<html>
<head>
<meta http-equiv="expires" content="31 Dec 1990">
<title>develooping flash chat</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 
/*	develooping flash chat	    	                    */
/*	by Juan Carlos Posé                                 */
/*	juancarlos@develooping.com	                        */
/*	version 1.6.5	                                    */


require ('chat/required/config.php');
if(eregi("win", $HTTP_USER_AGENT) and eregi("MSIE", $HTTP_USER_AGENT)){
$browser= "explorerwin";
}
else{
$browser= "";
}


?>
<script language="JavaScript">
<!--
var admin_win = null;
var w = 400, h = 400;
if (document.all) {
   w = document.body.clientWidth; 
   h = document.body.clientHeight;
}
if (document.layers) { 
w = window.innerWidth; 
h = window.innerHeight; 
}
function openBrWindow(theURL,winName,features) {
  admin_win=window.open(theURL,winName,features);
  if (admin_win.moveTo) {
  admin_win.moveTo(w/2,h/2);
  }
}

function check_the_form() { 
  var the_error='';
  var the_error_name='';
  var the_error_password='';
  var the_person=document.the_form.person.value;
  var the_password=document.the_form.password.value;
  var validperson=" abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
<?php 
if ($password_system=="ip"){
?>
  var validpassword=" 0123456789.";
<?php 
}else{
?>
var validpassword="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
<?php
}
?>
if (the_person.length < 4){
the_error = "<?php echo $alert_message_1;?>\n";
}
<?php 
if ($password_system!="ip"){
?>
if (the_password.length < 4){
the_error = the_error + "<?php echo $alert_message_2;?>\n";
}
<?php 
}
?>   
for (var i=0; i<the_person.length; i++) {
   if (validperson.indexOf(the_person.charAt(i)) < 0) {
         the_error_name = "<?php echo $alert_message_3;?>\n";
        }
    }  
for (var i=0; i<the_password.length; i++) {
   if (validpassword.indexOf(the_password.charAt(i)) < 0) {
    <?php 
if ($password_system=="ip"){
?>
the_error_password = "<?php echo $alert_message_4;?>\n";
<?php 
}else{
?>
the_error_password = "<?php echo $alert_message_5;?>\n";
<?php 
}
?>
        }
    }  
the_error = the_error + the_error_name + the_error_password ;
if (the_error!=''){alert('<?php echo $intro_alert;?>\t\t\t\t\t\n\n'+the_error)}
  document.return_the_value = (the_error=='');
}

function abreadmin(){
if (admin_win && admin_win.open && !admin_win.closed){
admin_win.focus();
}else{
openBrWindow('chat/admin.php','admin_win','toolbar=no,scrollbars=no,width=400,height=400');
}
}
//-->
</script>
<style type="text/css">
body {
background-color: #EEEEEE;
font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;  
font-size : 10px;  
}
a:link{ color :#990000;text-decoration: none;}
a:active{ color :#FF9933;text-decoration: none;}
a:visited {  color :#CC6666;text-decoration: none;}
a:hover { text-decoration: underline; 
color : #990000;
}
input, select, textarea{
border : 1px solid #999999;
background-color : #DDDDDD;
color : #666666;
font-size : 10px;
font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
border-width: 1px 0px 0px 1px;
text-indent : 2px;
}
input.but{
border : 1px solid #AAAAAA;
background-color : #CCCCCC;
color : #666666;
font-size : 10px;
font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
border-width: 2px 3px 3px 2px;
}
</style>
</head>
<body bgcolor="#EEEEEE">
<table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle"><OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
 codebase="http://active.macromedia.com/flash2/cabs/swflash.cab#version=4,0,0,0"
 ID="counter" WIDTH=475 HEIGHT=20>
 <PARAM NAME=movie VALUE="chat/count_users.swf?browser=<?php echo $browser;?>&person_word=<?php echo urlencode($person_word);?>&plural_particle=<?php echo urlencode($plural_particle);?>&now_in_the_chat=<?php echo urlencode($now_in_the_chat);?>"><PARAM NAME=menu VALUE=false><PARAM NAME=quality VALUE=best><PARAM NAME=wmode VALUE=transparent><EMBED name="counter" src="chat/count_users.swf?browser=<?php echo $browser;?>&person_word=<?php echo urlencode($person_word);?>&plural_particle=<?php echo urlencode($plural_particle);?>&now_in_the_chat=<?php echo urlencode($now_in_the_chat);?>" menu=false quality=best wmode=transparent WIDTH=475 HEIGHT=20 TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" swLiveConnect="true"></EMBED>
</OBJECT>
<br><br><br>
<form name="the_form" action="chat/index.php" method="POST" onSubmit="check_the_form();return document.return_the_value;">
  <font face="Verdana, Arial, Helvetica, sans-serif" size="1">
  </font>
  <table width="475" border="0" cellpadding="0" cellspacing="0" background="chat/graphics/fondoceldas.jpg">
    <tr align="left" valign="top"> 
      <td width="5" height="5"><img src="chat/graphics/esq1.gif" width="5" height="5"></td>
      <td height="5" colspan="5" background="chat/graphics/la1.gif"><img src="chat/graphics/la1.gif" width="5" height="5"></td>
      <td width="5" height="5" align="right"><img src="chat/graphics/esq2.gif" width="5" height="5"></td>
    </tr>
    <tr>
      <td width="5" rowspan="2" background="chat/graphics/la2.gif"><img src="chat/graphics/la2.gif" width="5" height="5"></td>
      <td colspan="5" align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php echo htmlentities($enter_sentence_1);
if ($password_system!="ip"){
echo htmlentities($enter_sentence_2);
}
echo htmlentities($enter_sentence_3);
?></font>
        <hr size="1" noshade></td>
      <td width="5" rowspan="2" align="center" background="chat/graphics/la3.gif" bgcolor="#FFFFFF"><img src="chat/graphics/la3.gif" width="5" height="5"></td>
    </tr>
    <tr>
    <td align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php if($nametaken==1 and $name_taken){echo"<font color='#9900000'>".$name_taken;}else{echo htmlentities($name_word);}if($nametaken==1){echo"&nbsp;</font>";};?> &nbsp;</font></td>
      <td align="left"><input type="hidden" name="self" value="<?php echo $PHP_SELF;?>"> 
        <input type="text" name="person" maxlength="12" size="8" style="background:#CCCCCC; width:50px;" onfocus="style.backgroundColor='#EEEEEE';" onblur="style.backgroundColor='#CCCCCC';">
        <?php 
if ($password_system=="ip"){

if (getenv("HTTP_CLIENT_IP")) $ip = getenv("HTTP_CLIENT_IP"); 
else if(getenv("HTTP_X_FORWARDED_FOR")) $ip = getenv("HTTP_X_FORWARDED_FOR"); 
else if(getenv("REMOTE_ADDR")) $ip = getenv("REMOTE_ADDR"); 
else $ip = "UNKNOWN"; 

?>
        <input type="hidden" name="password" value="<?php echo $ip;?>">
<?php 
}
?>      </td>
      <td align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<? if ($password_system != "ip"){ echo htmlentities($password_word);}?> </font></td>
      <td align="left">&nbsp; 
        <? if ($password_system != "ip"){?>
        <input type="text" name="password" maxlength="12" size="8" style="background:#CCCCCC; width:50px;" onfocus="style.backgroundColor='#EEEEEE';" onblur="style.backgroundColor='#CCCCCC';">
<?php 
}
?>      </td>
      <td align="right"> 
        <input type="submit" name="Submit" value="<?php echo htmlentities($enter_button);?>"  class="but" id="enviar" onmouseover="style.backgroundColor='#DDDDDD'; style.color='#CC0000';" onmouseout="style.backgroundColor='#CCCCCC'; style.color='#666666'; width:75">      </td>
      </tr>
    <tr align="left" valign="top"> 
<td width="5" height="5" valign="bottom"><img src="chat/graphics/esq3.gif" width="5" height="5"></td> 
<td height="5" colspan="5" background="chat/graphics/la4.gif"><img src="chat/graphics/la4.gif" width="5" height="5"></td>
<td width="5" height="5" align="right" valign="bottom"><img src="chat/graphics/esq4.gif" width="5" height="5"></td>
    </tr>
  </table>
 <table width="475" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td align="center"> <br>
        <font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php echo htmlentities($require_sentence);?>.- </font><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><font color="#990000"><a href="http://www.develooping.com" target="_blank">develooping flash chat</a></font></b> 1.65</font><br>
        <br><br><br><hr size="1" noshade><a href="javascript:abreadmin();"><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#990000"><?php echo htmlentities($link_to_admin);?></font></a>
      </td>
    </tr>
  </table>
</form></td>
  </tr>
</table>
 
</body>
</html>
